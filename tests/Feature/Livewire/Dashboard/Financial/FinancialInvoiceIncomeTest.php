<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Livewire\Dashboard\Invoices\InvoicesIndex;
use App\Models\Company;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceCategorie;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PagHiperService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FinancialInvoiceIncomeTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Role::where('name', 'super-admin')->exists()) {
            Role::create(['name' => 'super-admin']);
        }

        $this->user = User::factory()->create(['password' => 'password']);
        $this->user->assignRole('super-admin');
        $this->actingAs($this->user);

        config([
            'services.paghiper.api_key' => 'apk_test',
            'services.paghiper.token' => 'token-test',
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);
    }

    protected function makeInvoice(array $attributes = []): Invoice
    {
        $company = Company::factory()->create();
        $category = ServiceCategorie::create(['name' => 'Teste', 'slug' => 'teste-' . uniqid()]);
        $service = Service::create([
            'category_id' => $category->id,
            'user_id' => $this->user->id,
            'name' => 'Serviço de teste',
            'price' => 199.90,
            'billing_type' => 'recurring',
            'interval' => 'monthly',
            'status' => true,
        ]);
        $subscription = Subscription::create([
            'service_id' => $service->id,
            'company_id' => $company->id,
            'interval' => 'monthly',
            'amount' => 199.90,
            'start_date' => now()->toDateString(),
            'next_billing_at' => now()->addMonth()->toDateString(),
            'status' => 'active',
        ]);

        return Invoice::factory()->create(array_merge([
            'company_id' => $company->id,
            'subscription_id' => $subscription->id,
        ], $attributes));
    }

    public function test_register_financial_income_uses_net_amount(): void
    {
        $invoice = $this->makeInvoice([
            'status' => 'paid',
            'paid_at' => now(),
            'amount' => 199.90,
            'gateway_fee' => 2.79,
            'gateway_net_amount' => 197.11,
        ]);

        $transaction = $invoice->registerFinancialIncome();

        $this->assertNotNull($transaction);
        $this->assertEquals(FinancialType::INCOME->value, $transaction->type->value);
        $this->assertEquals(FinancialStatus::RECEIVED->value, $transaction->status->value);
        $this->assertEquals(197.11, (float) $transaction->amount);
        $this->assertEquals($invoice->id, $transaction->invoice_id);
        $this->assertEquals(now()->year, $transaction->year);
        $this->assertEquals(now()->month, $transaction->month);
    }

    public function test_register_financial_income_uses_full_amount_without_fee(): void
    {
        $invoice = $this->makeInvoice([
            'status' => 'paid',
            'paid_at' => now(),
            'amount' => 199.90,
        ]);

        $transaction = $invoice->registerFinancialIncome();

        $this->assertNotNull($transaction);
        $this->assertEquals(199.90, (float) $transaction->amount);
    }

    public function test_register_financial_income_is_idempotent(): void
    {
        $invoice = $this->makeInvoice([
            'status' => 'paid',
            'paid_at' => now(),
            'amount' => 199.90,
        ]);

        $invoice->registerFinancialIncome();
        $invoice->registerFinancialIncome();

        $this->assertEquals(1, FinancialTransaction::where('invoice_id', $invoice->id)->count());
    }

    public function test_register_financial_income_does_nothing_when_not_paid(): void
    {
        $invoice = $this->makeInvoice(['status' => 'pending']);

        $this->assertNull($invoice->registerFinancialIncome());
        $this->assertEquals(0, FinancialTransaction::where('invoice_id', $invoice->id)->count());
    }

    public function test_register_financial_income_creates_faturas_category(): void
    {
        $invoice = $this->makeInvoice([
            'status' => 'paid',
            'paid_at' => now(),
            'amount' => 199.90,
        ]);

        $invoice->registerFinancialIncome();

        $this->assertDatabaseHas('financial_categories', [
            'user_id' => $this->user->id,
            'name' => 'Faturas',
            'type' => FinancialType::INCOME->value,
        ]);
    }

    public function test_webhook_mark_as_paid_stores_fee_and_registers_income(): void
    {
        Notification::fake();

        $invoice = $this->makeInvoice(['status' => 'pending']);

        Http::fake([
            'api.paghiper.com/transaction/notification/*' => Http::response([
                'status_request' => [
                    'result' => 'success',
                    'order_id' => 'INV-' . $invoice->id,
                    'transaction_id' => 'TRX123',
                    'status' => 'paid',
                    'value_cents' => 19990,
                    'value_fee_cents' => 279,
                    'value_cents_paid' => 19990,
                    'pix_code' => ['emv' => '000201...'],
                ],
            ]),
        ]);

        $this->postJson(route('webhooks.paghiper'), [
            'apiKey' => 'apk_test',
            'notification_id' => 'NOTIF123',
            'transaction_id' => 'TRX123',
            'source_api' => 'https://api.paghiper.com',
        ])->assertOk();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
            'gateway_fee' => 2.79,
            'gateway_net_amount' => 197.11,
            'payment_method' => 'pix',
        ]);

        $this->assertDatabaseHas('financial_transactions', [
            'invoice_id' => $invoice->id,
            'type' => FinancialType::INCOME->value,
            'status' => FinancialStatus::RECEIVED->value,
            'amount' => 197.11,
        ]);
    }

    public function test_webhook_uses_pix_notification_endpoint_when_source_api_is_pix(): void
    {
        Notification::fake();

        $invoice = $this->makeInvoice(['status' => 'pending']);

        Http::fake([
            'pix.paghiper.com/invoice/notification/*' => Http::response([
                'status_request' => [
                    'result' => 'success',
                    'order_id' => 'INV-' . $invoice->id,
                    'transaction_id' => 'TRX123',
                    'status' => 'paid',
                    'value_cents' => 19990,
                    'value_fee_cents' => 279,
                    'value_cents_paid' => 19990,
                    'pix_code' => ['emv' => '000201...'],
                ],
            ]),
        ]);

        $this->postJson(route('webhooks.paghiper'), [
            'apiKey' => 'apk_test',
            'notification_id' => 'NOTIF123',
            'transaction_id' => 'TRX123',
            'source_api' => 'https://pix.paghiper.com',
        ])->assertOk();

        Http::assertSent(function (Request $request) {
            return str_contains($request->url(), 'pix.paghiper.com/invoice/notification');
        });

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
            'payment_method' => 'pix',
        ]);
    }

    public function test_webhook_rejects_invalid_api_key(): void
    {
        $invoice = $this->makeInvoice(['status' => 'pending']);

        $this->postJson(route('webhooks.paghiper'), [
            'apiKey' => 'apk_invalida',
            'notification_id' => 'NOTIF123',
            'transaction_id' => 'TRX123',
        ])->assertUnauthorized();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'pending',
        ]);
    }

    public function test_webhook_mark_as_refunded_cancels_linked_transaction(): void
    {
        Notification::fake();

        $invoice = $this->makeInvoice([
            'status' => 'paid',
            'paid_at' => now(),
            'amount' => 199.90,
            'gateway_fee' => 2.79,
            'gateway_net_amount' => 197.11,
        ]);

        $transaction = $invoice->registerFinancialIncome();

        Http::fake([
            'api.paghiper.com/transaction/notification/*' => Http::response([
                'status_request' => [
                    'result' => 'success',
                    'order_id' => 'INV-' . $invoice->id,
                    'transaction_id' => 'TRX123',
                    'status' => 'refunded',
                ],
            ]),
        ]);

        $this->postJson(route('webhooks.paghiper'), [
            'apiKey' => 'apk_test',
            'notification_id' => 'NOTIF123',
            'transaction_id' => 'TRX123',
            'source_api' => 'https://api.paghiper.com',
        ])->assertOk();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'refunded',
        ]);

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'status' => FinancialStatus::CANCELLED->value,
        ]);
    }

    public function test_webhook_mark_as_paid_registers_income_without_authenticated_user(): void
    {
        Notification::fake();
        \Illuminate\Support\Facades\Auth::logout();

        $invoice = $this->makeInvoice(['status' => 'pending']);

        Http::fake([
            'api.paghiper.com/transaction/notification/*' => Http::response([
                'status_request' => [
                    'result' => 'success',
                    'order_id' => 'INV-' . $invoice->id,
                    'transaction_id' => 'TRX123',
                    'status' => 'paid',
                    'value_cents' => 19990,
                    'value_fee_cents' => 279,
                    'value_cents_paid' => 19990,
                ],
            ]),
        ]);

        $this->postJson(route('webhooks.paghiper'), [
            'apiKey' => 'apk_test',
            'notification_id' => 'NOTIF123',
            'transaction_id' => 'TRX123',
            'source_api' => 'https://api.paghiper.com',
        ])->assertOk();

        $this->assertDatabaseHas('financial_transactions', [
            'invoice_id' => $invoice->id,
            'type' => FinancialType::INCOME->value,
            'status' => FinancialStatus::RECEIVED->value,
            'amount' => 197.11,
        ]);
    }

    public function test_webhook_mark_as_refunded_cancels_linked_transaction_without_authenticated_user(): void
    {
        Notification::fake();

        $invoice = $this->makeInvoice([
            'status' => 'paid',
            'paid_at' => now(),
            'amount' => 199.90,
            'gateway_fee' => 2.79,
            'gateway_net_amount' => 197.11,
        ]);

        $transaction = $invoice->registerFinancialIncome();

        \Illuminate\Support\Facades\Auth::logout();

        Http::fake([
            'api.paghiper.com/transaction/notification/*' => Http::response([
                'status_request' => [
                    'result' => 'success',
                    'order_id' => 'INV-' . $invoice->id,
                    'transaction_id' => 'TRX123',
                    'status' => 'refunded',
                ],
            ]),
        ]);

        $this->postJson(route('webhooks.paghiper'), [
            'apiKey' => 'apk_test',
            'notification_id' => 'NOTIF123',
            'transaction_id' => 'TRX123',
            'source_api' => 'https://api.paghiper.com',
        ])->assertOk();

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'status' => FinancialStatus::CANCELLED->value,
        ]);
    }

    public function test_invoices_index_mark_as_paid_registers_income_with_full_amount(): void
    {
        $invoice = $this->makeInvoice(['status' => 'pending', 'amount' => 199.90]);

        Livewire::test(InvoicesIndex::class)
            ->call('markAsPaid', $invoice->id);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
            'gateway_net_amount' => 199.90,
        ]);

        $this->assertDatabaseHas('financial_transactions', [
            'invoice_id' => $invoice->id,
            'type' => FinancialType::INCOME->value,
            'status' => FinancialStatus::RECEIVED->value,
            'amount' => 199.90,
        ]);
    }

    public function test_sync_invoice_registers_income_when_paid(): void
    {
        Http::fake([
            'api.paghiper.com/transaction/status/*' => Http::response([
                'status_request' => [
                    'result' => 'success',
                    'status' => 'paid',
                    'status_date' => now()->toDateTimeString(),
                    'value_cents' => 19990,
                    'value_fee_cents' => 279,
                    'value_cents_paid' => 19990,
                ],
            ]),
        ]);

        $invoice = $this->makeInvoice([
            'status' => 'pending',
            'gateway' => 'paghiper',
            'gateway_reference' => 'TRX123',
        ]);

        app(PagHiperService::class)->syncInvoice($invoice);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
            'gateway_fee' => 2.79,
            'gateway_net_amount' => 197.11,
        ]);

        $this->assertDatabaseHas('financial_transactions', [
            'invoice_id' => $invoice->id,
            'type' => FinancialType::INCOME->value,
            'status' => FinancialStatus::RECEIVED->value,
            'amount' => 197.11,
        ]);
    }
}