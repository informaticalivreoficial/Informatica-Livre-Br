<?php

namespace Tests\Feature\Livewire\Loja;

use App\Livewire\Dashboard\Loja\Licencas;
use App\Livewire\Dashboard\Loja\Pedidos;
use App\Livewire\Dashboard\Loja\ProdutoForm;
use App\Livewire\Dashboard\Loja\Produtos;
use App\Livewire\Web\Loja\Checkout;
use App\Livewire\Web\Loja\SelecionarPlano;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutoPlano;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class LojaModuleTest extends TestCase
{
    use DatabaseTransactions;

    private function produto(): Produto
    {
        $produto = Produto::create([
            'nome' => 'Sistema de Clinicas',
            'slug' => 'sistema-de-clinicas',
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

    public function test_admin_list_pages_render(): void
    {
        $this->produto();

        Livewire::test(Produtos::class)->assertStatus(200);
        Livewire::test(Pedidos::class)->assertStatus(200);
        Livewire::test(Licencas::class)->assertStatus(200);
    }

    public function test_produto_form_creates_and_edits(): void
    {
        Livewire::test(ProdutoForm::class)->assertStatus(200);

        $produto = $this->produto();

        Livewire::test(ProdutoForm::class, ['produto' => $produto])
            ->assertStatus(200)
            ->assertSet('nome', 'Sistema de Clinicas')
            ->call('openPlanoCreate')
            ->set('planoNome', 'Hospedagem')
            ->set('planoPreco', 149.9)
            ->set('planoBillingType', 'recurring')
            ->set('planoBillingInterval', 'monthly')
            ->call('savePlano')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('produto_planos', [
            'produto_id' => $produto->id,
            'nome' => 'Hospedagem',
            'billing_type' => 'recurring',
        ]);
    }

    public function test_public_store_and_checkout_render(): void
    {
        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Implantacao');

        Livewire::test(SelecionarPlano::class, ['produto' => $produto])
            ->assertStatus(200)
            ->assertSee('Implantacao')
            ->assertSee('/mês');

        Livewire::test(Checkout::class, ['produto' => $produto->slug, 'plano' => $plano->id])
            ->assertStatus(200)
            ->assertSee('Finalizar pedido')
            ->assertSee('Resumo do pedido');
    }

    public function test_checkout_creates_pedido(): void
    {
        Http::fake();

        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Implantacao');

        Livewire::test(Checkout::class, ['produto' => $produto->slug, 'plano' => $plano->id])
            ->set('nome', 'Joao da Silva')
            ->set('email', 'joao@example.com')
            ->set('telefone', '11999999999')
            ->set('cpfCnpj', '12345678901')
            ->set('metodoPagamento', 'pix')
            ->call('finalizar')
            ->assertHasNoErrors();

        $pedido = Pedido::first();

        $this->assertNotNull($pedido);
        $this->assertSame('pendente', $pedido->status_pagamento);
        $this->assertCount(1, $pedido->itens);
        $this->assertSame('Implantacao', $pedido->itens->first()->plano_nome);
    }

    public function test_checkout_page_renders_via_route(): void
    {
        \App\Models\Config::create([
            'app_name' => 'Informatica Livre BR',
            'domain' => 'https://example.com',
            'init_date' => 2018,
        ]);

        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Manutencao');

        $this->get(route('web.checkout', ['produto' => $produto->slug, 'plano' => $plano->id]))
            ->assertOk()
            ->assertSee('Finalizar pedido')
            ->assertSee('Manutencao');
    }

    public function test_public_product_page_renders_plans(): void
    {
        \App\Models\Config::create([
            'app_name' => 'Informatica Livre BR',
            'domain' => 'https://example.com',
            'init_date' => 2018,
        ]);

        $produto = $this->produto();

        $this->get(route('web.produtos'))
            ->assertOk()
            ->assertSee($produto->nome);

        $this->get(route('web.produto', $produto->slug))
            ->assertOk()
            ->assertSee('Implantacao')
            ->assertSee('Manutencao')
            ->assertSee('/mês');
    }

    public function test_admin_pages_render_via_route(): void
    {
        \App\Models\Config::create([
            'app_name' => 'Informatica Livre BR',
            'domain' => 'https://example.com',
            'init_date' => 2018,
        ]);

        \Spatie\Permission\Models\Role::create(['name' => 'super-admin', 'guard_name' => 'web']);

        $user = \App\Models\User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
        ]);
        $user->assignRole('super-admin');

        $produto = $this->produto();

        $this->actingAs($user)->get(route('loja.produtos.index'))->assertOk();
        $this->actingAs($user)->get(route('loja.produtos.create'))->assertOk();
        $this->actingAs($user)->get(route('loja.produtos.edit', $produto))->assertOk();
        $this->actingAs($user)->get(route('loja.pedidos.index'))->assertOk();
        $this->actingAs($user)->get(route('loja.licencas.index'))->assertOk();
    }

    public function test_pedido_mark_as_paid_gera_licenca(): void
    {
        $produto = $this->produto();
        $plano = $produto->planos()->firstWhere('nome', 'Implantacao');

        $pedido = Pedido::create([
            'nome' => 'Maria',
            'email' => 'maria@example.com',
            'subtotal' => 2500,
            'total' => 2500,
            'metodo_pagamento' => 'pix',
        ]);

        $pedido->itens()->create([
            'produto_id' => $produto->id,
            'plano_id' => $plano->id,
            'produto_nome' => $produto->nome,
            'plano_nome' => $plano->nome,
            'preco' => 2500,
        ]);

        $pedido->markAsPaid();

        $this->assertSame('pago', $pedido->fresh()->status_pagamento);
        $this->assertCount(1, $pedido->fresh()->licencas);
    }
}
