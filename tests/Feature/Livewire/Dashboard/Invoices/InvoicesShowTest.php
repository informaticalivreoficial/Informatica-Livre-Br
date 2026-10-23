<?php

namespace Tests\Feature\Livewire\Dashboard\Invoices;

use App\Livewire\Dashboard\Invoices\InvoicesShow;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class InvoicesShowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole('super-admin');
        $this->actingAs($user);
    }

    public function test_generate_charge_for_pending_invoice(): void
    {
        Http::fake([
            'api.paghiper.com/transaction/create/' => Http::response([
                'create_request' => [
                    'result'         => 'success',
                    'transaction_id' => 'TX-SHOW-1',
                    'bank_slip'      => ['url_slip' => 'https://paghiper.test/boleto-show'],
                    'pix'            => ['qrcode_image' => 'https://paghiper.test/pix-show.png'],
                ],
            ]),
        ]);

        $company = Company::factory()->create([
            'email'            => 'cliente@teste.com',
            'document_company' => '12.345.678/0001-99',
        ]);

        $invoice = Invoice::factory()->create([
            'company_id'      => $company->id,
            'subscription_id' => null,
            'description'     => 'Serviço avulso',
        ]);

        Livewire::test(InvoicesShow::class, ['invoice' => $invoice])
            ->assertSee('Gerar cobrança')
            ->call('generateCharge')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $invoice->refresh();

        $this->assertSame('TX-SHOW-1', $invoice->gateway_reference);
        $this->assertSame('https://paghiper.test/boleto-show', $invoice->payment_url);
        $this->assertSame('paghiper', $invoice->gateway);
    }

    public function test_generate_charge_for_custom_payer_invoice(): void
    {
        Http::fake([
            'api.paghiper.com/transaction/create/' => Http::response([
                'create_request' => [
                    'result'         => 'success',
                    'transaction_id' => 'TX-SHOW-2',
                    'bank_slip'      => ['url_slip' => 'https://paghiper.test/boleto-avulso'],
                ],
            ]),
        ]);

        $invoice = Invoice::factory()->create([
            'company_id'      => null,
            'subscription_id' => null,
            'payer_name'      => 'Empresa Avulsa',
            'payer_document'  => '12.345.678/0001-99',
            'payer_email'     => 'avulso@teste.com',
            'description'     => 'Serviço avulso',
        ]);

        Livewire::test(InvoicesShow::class, ['invoice' => $invoice])
            ->assertSee('Empresa Avulsa')
            ->assertSee('Avulso')
            ->call('generateCharge')
            ->assertHasNoErrors()
            ->assertDispatched('swal:success');

        $this->assertSame('TX-SHOW-2', $invoice->fresh()->gateway_reference);

        Http::assertSent(function ($request) {
            return $request['payer_name'] === 'Empresa Avulsa'
                && $request['payer_cpf_cnpj'] === '12345678000199'
                && $request['payer_email'] === 'avulso@teste.com';
        });
    }

    public function test_hides_generate_charge_button_when_invoice_already_has_charge(): void
    {
        $invoice = Invoice::factory()->create([
            'company_id'        => Company::factory()->create()->id,
            'subscription_id'   => null,
            'gateway'           => 'paghiper',
            'gateway_reference' => 'TX-EXISTENTE',
            'payment_url'       => 'https://paghiper.test/boleto',
        ]);

        Livewire::test(InvoicesShow::class, ['invoice' => $invoice])
            ->assertDontSee('Gerar cobrança');
    }
}
