<?php

namespace Tests\Feature\Livewire\Loja;

use App\Livewire\Web\Loja\Checkout;
use App\Mail\Loja\PedidoPago;
use App\Mail\Loja\PedidoRecebido;
use App\Models\Company;
use App\Models\Config;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutoPlano;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class LojaPagBankTest extends TestCase
{
    use DatabaseTransactions;

    private function produto(): Produto
    {
        $produto = Produto::create([
            'nome' => 'Sistema de Clinicas',
            'slug' => 'sistema-de-clinicas-pagbank',
            'headline' => 'Gestao completa',
            'status' => true,
        ]);

        ProdutoPlano::create([
            'produto_id' => $produto->id,
            'nome' => 'Implantacao',
            'preco' => 2500,
            'billing_type' => 'one_time',
            'status' => true,
            'ordem' => 1,
        ]);

        ProdutoPlano::create([
            'produto_id' => $produto->id,
            'nome' => 'Manutencao',
            'preco' => 199.9,
            'billing_type' => 'recurring',
            'billing_interval' => 'monthly',
            'status' => true,
            'ordem' => 2,
        ]);

        return $produto;
    }

    private function config(): void
    {
        Config::create([
            'app_name' => 'Informatica Livre BR',
            'domain' => 'https://example.com',
            'init_date' => 2018,
        ]);
    }

    private function pagbankResponse(string $url = 'https://pagamento.test/checkout'): array
    {
        return [
            'id' => 'CHEC_TESTE',
            'status' => 'ACTIVE',
            'links' => [
                ['rel' => 'PAY', 'href' => $url, 'method' => 'GET'],
            ],
        ];
    }

    public function test_checkout_cria_pedido_e_redireciona_para_pagbank(): void
    {
        Mail::fake();
        Http::fake(['*' => Http::response($this->pagbankResponse(), 200)]);

        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Implantacao');

        Livewire::test(Checkout::class, ['produto' => $produto->slug, 'plano' => $plano->id])
            ->set('nome', 'Joao da Silva')
            ->set('email', 'joao@example.com')
            ->set('telefone', '11999999999')
            ->set('cpfCnpj', '12345678901')
            ->set('metodoPagamento', 'cartao_credito')
            ->call('finalizar')
            ->assertHasNoErrors()
            ->assertRedirect('https://pagamento.test/checkout');

        $pedido = Pedido::first();

        $this->assertNotNull($pedido);
        $this->assertSame('CHEC_TESTE', $pedido->gateway_id);
        $this->assertSame('https://pagamento.test/checkout', $pedido->payment_url);
        $this->assertSame('cartao_credito', $pedido->metodo_pagamento);
        $this->assertFalse($pedido->recorrente);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/checkouts')
                && $request['payment_methods'][0]['type'] === 'CREDIT_CARD'
                && $request['items'][0]['unit_amount'] === 250000
                && $request['reference_id'] === Pedido::first()->codigo;
        });

        Mail::assertSent(PedidoRecebido::class, fn ($mail) => $mail->hasTo('joao@example.com'));
    }

    public function test_checkout_de_plano_recorrente_agenda_proxima_cobranca(): void
    {
        Mail::fake();
        Http::fake(['*' => Http::response($this->pagbankResponse(), 200)]);

        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Manutencao');

        Livewire::test(Checkout::class, ['produto' => $produto->slug, 'plano' => $plano->id])
            ->set('nome', 'Maria Souza')
            ->set('email', 'maria@example.com')
            ->set('telefone', '11988887777')
            ->set('cpfCnpj', '12345678901')
            ->set('metodoPagamento', 'pix')
            ->call('finalizar')
            ->assertHasNoErrors();

        $pedido = Pedido::first();

        $this->assertTrue($pedido->recorrente);
        $this->assertSame('monthly', $pedido->billing_interval->value);
        $this->assertSame('ativa', $pedido->recorrencia_status->value);
        $this->assertSame(today()->addMonth()->toDateString(), $pedido->proximo_vencimento->toDateString());
    }

    public function test_webhook_pagbank_confirma_pagamento_e_gera_licenca(): void
    {
        Mail::fake();
        config(['services.pagbank.webhook_token' => null]);

        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Implantacao');

        $pedido = Pedido::create([
            'nome' => 'Maria Souza',
            'email' => 'maria@example.com',
            'subtotal' => 2500,
            'total' => 2500,
            'metodo_pagamento' => 'cartao_credito',
        ]);

        $pedido->itens()->create([
            'produto_id' => $produto->id,
            'plano_id' => $plano->id,
            'produto_nome' => $produto->nome,
            'plano_nome' => $plano->nome,
            'preco' => 2500,
        ]);

        $this->postJson(route('webhooks.pagbank'), [
            'id' => 'CHAR_TESTE',
            'reference_id' => $pedido->codigo,
            'status' => 'PAID',
            'charges' => [
                ['id' => 'CHAR_TESTE', 'status' => 'PAID'],
            ],
        ])->assertOk()->assertJson(['received' => true]);

        $pedido->refresh();

        $this->assertSame('pago', $pedido->status_pagamento);
        $this->assertCount(1, $pedido->licencas);
        $this->assertSame('CHAR_TESTE', $pedido->gateway_response['last_notification']['id']);

        Mail::assertSent(PedidoPago::class, fn ($mail) => $mail->hasTo('maria@example.com'));
    }

    public function test_webhook_pagbank_valida_assinatura(): void
    {
        config(['services.pagbank.webhook_token' => 'segredo']);

        $payload = [
            'id' => 'CHAR_ASSINATURA',
            'reference_id' => 'PED-2026-99999',
            'status' => 'PAID',
        ];

        $this->postJson(route('webhooks.pagbank'), $payload)->assertStatus(401);

        $signature = hash('sha256', 'segredo-' . json_encode($payload));

        $this->postJson(route('webhooks.pagbank'), $payload, [
            'x-authenticity-token' => $signature,
        ])->assertStatus(202);
    }

    public function test_comando_cobra_recorrencia_e_avanca_vencimento(): void
    {
        Mail::fake();
        Http::fake(['*' => Http::response($this->pagbankResponse('https://pagamento.test/renovacao'), 200)]);

        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Manutencao');

        $pedido = Pedido::create([
            'nome' => 'Maria Souza',
            'email' => 'maria@example.com',
            'telefone' => '11988887777',
            'cpf_cnpj' => '12345678901',
            'subtotal' => 199.9,
            'total' => 199.9,
            'metodo_pagamento' => 'pix',
            'status_pagamento' => 'pago',
            'status' => 'confirmado',
            'pago_em' => now(),
            'recorrente' => true,
            'billing_interval' => 'monthly',
            'recorrencia_status' => 'ativa',
            'ciclo' => 1,
            'proximo_vencimento' => today(),
        ]);

        $pedido->itens()->create([
            'produto_id' => $produto->id,
            'plano_id' => $plano->id,
            'produto_nome' => $produto->nome,
            'plano_nome' => $plano->nome,
            'preco' => 199.9,
        ]);

        $this->artisan('loja:cobrar-recorrencias')->assertExitCode(0);

        $pedido->refresh();

        $this->assertSame(2, $pedido->ciclo);
        $this->assertSame(today()->addMonth()->toDateString(), $pedido->proximo_vencimento->toDateString());

        $cobranca = Pedido::where('pedido_pai_id', $pedido->id)->first();

        $this->assertNotNull($cobranca);
        $this->assertSame('pendente', $cobranca->status_pagamento);
        $this->assertSame('https://pagamento.test/renovacao', $cobranca->payment_url);
        $this->assertSame(2, $cobranca->ciclo);
        $this->assertCount(1, $cobranca->itens);

        Mail::assertSent(PedidoRecebido::class);

        $this->artisan('loja:cobrar-recorrencias')->assertExitCode(0);

        $this->assertSame(1, Pedido::where('pedido_pai_id', $pedido->id)->count());
    }

    public function test_portal_do_cliente_lista_pedidos_e_licencas(): void
    {
        $this->config();

        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Implantacao');

        $company = Company::create([
            'responsable_name' => 'Maria Souza',
            'responsable_email' => 'maria@example.com',
            'email' => 'maria@example.com',
            'alias_name' => 'Clinica Maria',
            'status' => true,
        ]);

        $pedido = Pedido::create([
            'company_id' => $company->id,
            'nome' => 'Maria Souza',
            'email' => 'maria@example.com',
            'subtotal' => 2500,
            'total' => 2500,
            'metodo_pagamento' => 'pix',
            'status_pagamento' => 'pago',
            'status' => 'confirmado',
            'pago_em' => now(),
        ]);

        $item = $pedido->itens()->create([
            'produto_id' => $produto->id,
            'plano_id' => $plano->id,
            'produto_nome' => $produto->nome,
            'plano_nome' => $plano->nome,
            'preco' => 2500,
        ]);

        $pedido->licencas()->create([
            'pedido_item_id' => $item->id,
            'produto_id' => $produto->id,
            'nome' => 'Maria Souza',
            'email' => 'maria@example.com',
            'chave' => 'ABC123-DEF456-GHI789',
            'status' => 'ativa',
        ]);

        $this->withSession(['cliente_company_id' => $company->id])
            ->get(route('cliente.pedidos'))
            ->assertOk()
            ->assertSee($pedido->codigo);

        $this->withSession(['cliente_company_id' => $company->id])
            ->get(route('cliente.pedido', $pedido->codigo))
            ->assertOk()
            ->assertSee('ABC123-DEF456-GHI789');

        $this->withSession(['cliente_company_id' => $company->id])
            ->get(route('cliente.licencas'))
            ->assertOk()
            ->assertSee('ABC123-DEF456-GHI789');
    }

    public function test_portal_do_cliente_reenvia_link_de_pagamento(): void
    {
        $this->config();

        Http::fake(['*' => Http::response($this->pagbankResponse('https://pagamento.test/novo-link'), 200)]);
        Mail::fake();

        $company = Company::create([
            'responsable_name' => 'Maria Souza',
            'responsable_email' => 'maria@example.com',
            'email' => 'maria@example.com',
            'alias_name' => 'Clinica Maria',
            'status' => true,
        ]);

        $pedido = Pedido::create([
            'company_id' => $company->id,
            'nome' => 'Maria Souza',
            'email' => 'maria@example.com',
            'subtotal' => 100,
            'total' => 100,
            'metodo_pagamento' => 'pix',
        ]);

        $this->withSession(['cliente_company_id' => $company->id])
            ->post(route('cliente.pedido.reenviar', $pedido->codigo))
            ->assertRedirect();

        $this->assertSame('https://pagamento.test/novo-link', $pedido->fresh()->payment_url);
        Mail::assertSent(PedidoRecebido::class);
    }

    public function test_pagina_de_retorno_mostra_status_do_pedido(): void
    {
        $this->config();

        $pedido = Pedido::create([
            'nome' => 'Maria Souza',
            'email' => 'maria@example.com',
            'subtotal' => 100,
            'total' => 100,
            'metodo_pagamento' => 'pix',
        ]);

        $this->get(route('web.checkout.retorno', ['pedido' => $pedido->codigo]))
            ->assertOk()
            ->assertSee($pedido->codigo)
            ->assertSee('Aguardando confirmação');
    }
}
