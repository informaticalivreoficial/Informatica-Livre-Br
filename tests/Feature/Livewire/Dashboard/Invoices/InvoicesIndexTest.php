<?php

namespace Tests\Feature\Livewire\Dashboard\Invoices;

use App\Livewire\Dashboard\Invoices\InvoicesIndex;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceCategorie;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class InvoicesIndexTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => 'password']);
        $this->user->assignRole('super-admin');
        $this->actingAs($this->user);
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

    public function test_renders_invoices_with_stats(): void
    {
        $this->makeInvoice(['status' => 'paid']);

        Livewire::test(InvoicesIndex::class)
            ->assertOk()
            ->assertSee('Faturas')
            ->assertSee('Pagas');
    }

    public function test_mark_as_paid_updates_invoice(): void
    {
        $invoice = $this->makeInvoice(['status' => 'pending']);

        Livewire::test(InvoicesIndex::class)
            ->call('markAsPaid', $invoice->id);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
        ]);
    }

    public function test_mark_as_paid_rejects_paid_invoice(): void
    {
        $invoice = $this->makeInvoice(['status' => 'paid']);

        Livewire::test(InvoicesIndex::class)
            ->call('markAsPaid', $invoice->id)
            ->assertDispatched('swal:error');

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
        ]);
    }

    public function test_delete_invoice_removes_record(): void
    {
        $invoice = $this->makeInvoice();

        Livewire::test(InvoicesIndex::class)
            ->call('deleteInvoice', $invoice->id);

        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }
}