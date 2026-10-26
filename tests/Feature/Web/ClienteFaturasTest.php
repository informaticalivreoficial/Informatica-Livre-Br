<?php

namespace Tests\Feature\Web;

use App\Models\Company;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClienteFaturasTest extends TestCase
{
    use DatabaseTransactions;

    private function company(): Company
    {
        Config::create([
            'app_name' => 'Informatica Livre BR',
            'domain' => 'https://example.com',
            'init_date' => 2018,
        ]);

        $company = Company::create([
            'responsable_name' => 'Maria Souza',
            'responsable_email' => 'maria@example.com',
            'email' => 'maria@example.com',
            'alias_name' => 'Clinica Maria',
            'document_company' => '12345678000190',
            'phone' => '12999999999',
            'status' => true,
        ]);

        return $company;
    }

    private function invoice(Company $company, array $attributes = []): Invoice
    {
        $user = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin-faturas@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
        ]);

        $service = Service::create([
            'user_id' => $user->id,
            'name' => 'Manutencao mensal',
            'price' => 100,
            'billing_type' => 'recurring',
            'interval' => 'monthly',
            'status' => true,
        ]);

        $subscription = Subscription::create([
            'service_id' => $service->id,
            'company_id' => $company->id,
            'interval' => 'monthly',
            'amount' => 100,
            'start_date' => today(),
            'next_billing_at' => today(),
            'status' => 'active',
        ]);

        return Invoice::create(array_merge([
            'subscription_id' => $subscription->id,
            'company_id' => $company->id,
            'amount' => 100,
            'due_date' => today(),
            'status' => 'pending',
            'description' => 'Manutencao mensal',
        ], $attributes));
    }

    public function test_lista_de_faturas_mostra_opcao_de_pagar(): void
    {
        $company = $this->company();
        $invoice = $this->invoice($company);

        $this->withSession(['cliente_company_id' => $company->id])
            ->get(route('cliente.faturas'))
            ->assertOk()
            ->assertSee('Gerar pagamento')
            ->assertSee(route('cliente.faturas.pagar', $invoice->id));
    }

    public function test_cliente_gera_cobranca_e_e_redirecionado_para_pagamento(): void
    {
        Http::fake([
            'api.paghiper.com/*' => Http::response([
                'create_request' => [
                    'result' => 'success',
                    'response_message' => 'transacao criada',
                    'transaction_id' => 'TX123',
                    'bank_slip' => [
                        'url_slip' => 'https://paghiper.test/boleto',
                        'digitable_line' => '123456789',
                    ],
                ],
            ], 201),
        ]);

        $company = $this->company();
        $invoice = $this->invoice($company);

        $this->withSession(['cliente_company_id' => $company->id])
            ->post(route('cliente.faturas.pagar', $invoice->id))
            ->assertRedirect('https://paghiper.test/boleto');

        $invoice->refresh();

        $this->assertSame('TX123', $invoice->gateway_reference);
        $this->assertSame('https://paghiper.test/boleto', $invoice->payment_url);
        $this->assertSame('paghiper', $invoice->gateway);
        $this->assertSame('123456789', $invoice->boleto_barcode);

        Http::assertSentCount(1);
    }

    public function test_cliente_com_link_existente_e_redirecionado_sem_gerar_nova_cobranca(): void
    {
        Http::fake();

        $company = $this->company();
        $invoice = $this->invoice($company, [
            'payment_url' => 'https://paghiper.test/link-existente',
        ]);

        $this->withSession(['cliente_company_id' => $company->id])
            ->post(route('cliente.faturas.pagar', $invoice->id))
            ->assertRedirect('https://paghiper.test/link-existente');

        Http::assertNothingSent();
    }

    public function test_cliente_nao_pode_pagar_fatura_de_outra_empresa(): void
    {
        $company = $this->company();

        $outra = Company::create([
            'responsable_name' => 'Outro Responsavel',
            'responsable_email' => 'outro@example.com',
            'email' => 'outro@example.com',
            'alias_name' => 'Outra Empresa',
            'status' => true,
        ]);

        $invoice = $this->invoice($outra);

        $this->withSession(['cliente_company_id' => $company->id])
            ->post(route('cliente.faturas.pagar', $invoice->id))
            ->assertNotFound();
    }
}
