<?php

namespace Tests\Feature\Livewire\Dashboard\Invoices;

use App\Livewire\Dashboard\Invoices\InvoiceForm;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class InvoiceFormTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => 'password']);
        $this->user->assignRole('super-admin');
        $this->actingAs($this->user);
    }

    public function test_create_page_renders(): void
    {
        $this->get(route('invoices.create'))
            ->assertOk()
            ->assertSee('Nova Fatura')
            ->assertSee('Pagador avulso');
    }

    public function test_duplicate_page_renders_with_source_invoice(): void
    {
        $invoice = Invoice::factory()->create([
            'company_id'      => Company::factory()->create()->id,
            'subscription_id' => null,
            'description'     => 'Fatura modelo',
        ]);

        $this->get(route('invoices.duplicate', $invoice))
            ->assertOk()
            ->assertSee('Duplicar Fatura #' . $invoice->id)
            ->assertSee('Fatura modelo');
    }

    public function test_creates_custom_payer_invoice_without_subscription(): void
    {
        Http::fake();

        Livewire::test(InvoiceForm::class)
            ->set('payer_type', 'custom')
            ->set('payer_name', 'Maria Souza')
            ->set('payer_document', '123.456.789-01')
            ->set('payer_email', 'maria@teste.com')
            ->set('payer_phone', '(11) 98888-7777')
            ->set('payer_zipcode', '01001-000')
            ->set('payer_street', 'Praça da Sé')
            ->set('payer_number', '100')
            ->set('payer_neighborhood', 'Sé')
            ->set('payer_city', 'São Paulo')
            ->set('payer_state', 'sp')
            ->set('description', 'Consultoria avulsa')
            ->set('amount', '1.250,00')
            ->set('due_date', now()->addDays(10)->toDateString())
            ->set('generate_charge', false)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal');

        $this->assertDatabaseHas('invoices', [
            'company_id'         => null,
            'subscription_id'    => null,
            'payer_name'         => 'Maria Souza',
            'payer_document'     => '123.456.789-01',
            'payer_email'        => 'maria@teste.com',
            'payer_city'         => 'São Paulo',
            'payer_state'        => 'SP',
            'description'        => 'Consultoria avulsa',
            'amount'             => 1250.00,
            'status'             => 'pending',
        ]);

        Http::assertNothingSent();
    }

    public function test_creates_invoice_for_registered_company(): void
    {
        Http::fake();

        $company = Company::factory()->create();

        Livewire::test(InvoiceForm::class)
            ->set('payer_type', 'company')
            ->set('company_id', $company->id)
            ->set('description', 'Mensalidade')
            ->set('amount', '399,90')
            ->set('due_date', now()->addDays(5)->toDateString())
            ->set('generate_charge', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('invoices', [
            'company_id'      => $company->id,
            'subscription_id' => null,
            'payer_name'      => null,
            'amount'          => 399.90,
        ]);
    }

    public function test_generates_paghiper_charge_for_custom_payer(): void
    {
        Http::fake([
            'api.paghiper.com/transaction/create/' => Http::response([
                'create_request' => [
                    'result'         => 'success',
                    'transaction_id' => 'TX-AVULSO-1',
                    'bank_slip'      => ['url_slip' => 'https://paghiper.test/boleto'],
                    'pix'            => ['qrcode_image' => 'https://paghiper.test/pix.png'],
                ],
            ]),
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('payer_type', 'custom')
            ->set('payer_name', 'ACME Serviços LTDA')
            ->set('payer_document', '12.345.678/0001-99')
            ->set('payer_email', 'financeiro@acme.test')
            ->set('payer_zipcode', '01310-100')
            ->set('payer_street', 'Av. Paulista')
            ->set('payer_number', '1000')
            ->set('payer_neighborhood', 'Bela Vista')
            ->set('payer_city', 'São Paulo')
            ->set('payer_state', 'sp')
            ->set('description', 'Manutenção de servidor')
            ->set('amount', '450,00')
            ->set('due_date', now()->addDays(7)->toDateString())
            ->set('generate_charge', true)
            ->call('save')
            ->assertHasNoErrors();

        $invoice = Invoice::where('payer_name', 'ACME Serviços LTDA')->firstOrFail();

        $this->assertSame('TX-AVULSO-1', $invoice->gateway_reference);
        $this->assertSame('https://paghiper.test/boleto', $invoice->payment_url);
        $this->assertSame('paghiper', $invoice->gateway);

        Http::assertSent(function ($request) {
            return $request['items'][0]['description'] === 'Manutenção de servidor'
                && $request['payer_name'] === 'ACME Serviços LTDA'
                && $request['payer_cpf_cnpj'] === '12345678000199'
                && $request['payer_email'] === 'financeiro@acme.test'
                && $request['payer_zip_code'] === '01310100'
                && $request['payer_street'] === 'Av. Paulista'
                && $request['payer_city'] === 'São Paulo'
                && $request['payer_state'] === 'SP';
        });
    }

    public function test_duplicate_mount_fills_fields_from_company_invoice(): void
    {
        $company = Company::factory()->create();

        $invoice = Invoice::factory()->create([
            'company_id'      => $company->id,
            'subscription_id' => null,
            'description'     => 'Retainer mensal',
            'amount'          => 800.00,
        ]);

        Livewire::test(InvoiceForm::class, ['invoice' => $invoice])
            ->assertSet('payer_type', 'company')
            ->assertSet('company_id', $company->id)
            ->assertSet('description', 'Retainer mensal')
            ->assertSet('amount', 800.0)
            ->assertSet('generate_charge', false);
    }

    public function test_duplicate_mount_fills_fields_from_custom_payer_invoice(): void
    {
        $invoice = Invoice::factory()->create([
            'company_id'      => null,
            'subscription_id' => null,
            'payer_name'      => 'João da Silva',
            'payer_document'  => '123.456.789-01',
            'payer_email'     => 'joao@teste.com',
            'payer_city'      => 'Curitiba',
            'payer_state'     => 'PR',
            'description'     => 'Formatação de PC',
            'amount'          => 150.00,
        ]);

        Livewire::test(InvoiceForm::class, ['invoice' => $invoice])
            ->assertSet('payer_type', 'custom')
            ->assertSet('payer_name', 'João da Silva')
            ->assertSet('payer_document', '123.456.789-01')
            ->assertSet('payer_email', 'joao@teste.com')
            ->assertSet('payer_city', 'Curitiba')
            ->assertSet('payer_state', 'PR')
            ->assertSet('generate_charge', false);
    }

    public function test_validates_custom_payer_required_fields(): void
    {
        Livewire::test(InvoiceForm::class)
            ->set('payer_type', 'custom')
            ->set('payer_name', '')
            ->set('payer_document', '')
            ->set('payer_email', '')
            ->set('description', '')
            ->set('amount', 0)
            ->set('due_date', now()->subDay()->toDateString())
            ->set('generate_charge', false)
            ->call('save')
            ->assertHasErrors(['payer_name', 'payer_document', 'payer_email', 'description', 'amount', 'due_date']);
    }

    public function test_requires_company_when_payer_type_is_company(): void
    {
        Livewire::test(InvoiceForm::class)
            ->set('payer_type', 'company')
            ->set('company_id', null)
            ->set('description', 'Mensalidade')
            ->set('amount', '100,00')
            ->set('due_date', now()->addDays(5)->toDateString())
            ->set('generate_charge', false)
            ->call('save')
            ->assertHasErrors(['company_id']);
    }

    public function test_rejects_invalid_document_length(): void
    {
        Livewire::test(InvoiceForm::class)
            ->set('payer_type', 'custom')
            ->set('payer_name', 'Maria Souza')
            ->set('payer_document', '123.456')
            ->set('payer_email', 'maria@teste.com')
            ->set('description', 'Consultoria')
            ->set('amount', '100,00')
            ->set('due_date', now()->addDays(5)->toDateString())
            ->set('generate_charge', false)
            ->call('save')
            ->assertHasErrors(['payer_document']);
    }
}
