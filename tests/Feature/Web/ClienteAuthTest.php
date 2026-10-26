<?php

namespace Tests\Feature\Web;

use App\Models\Company;
use App\Models\Config;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ClienteAuthTest extends TestCase
{
    use DatabaseTransactions;

    private function company(): Company
    {
        Config::create([
            'app_name' => 'Informatica Livre BR',
            'domain' => 'https://example.com',
            'init_date' => 2018,
        ]);

        return Company::create([
            'responsable_name' => 'Maria Souza',
            'responsable_email' => 'maria@example.com',
            'email' => 'maria@example.com',
            'alias_name' => 'Clinica Maria',
            'status' => true,
        ]);
    }

    public function test_magic_token_autentica_cliente(): void
    {
        $company = $this->company();

        $token = $company->generateMagicToken();

        $company->refresh();

        $this->assertNotNull($company->magic_token_expires_at);
        $this->assertTrue($company->magic_token_expires_at->isFuture());
        $this->assertTrue($company->isMagicTokenValid($token));

        $this->get(route('cliente.auth', $token))
            ->assertRedirect(route('cliente.dashboard'));

        $this->assertSame($company->id, session('cliente_company_id'));

        $company->refresh();

        $this->assertNull($company->magic_token);
        $this->assertNull($company->magic_token_expires_at);
    }

    public function test_token_expirado_nao_autentica(): void
    {
        $company = $this->company();

        $token = $company->generateMagicToken();

        $company->update(['magic_token_expires_at' => now()->subMinute()]);

        $this->get(route('cliente.auth', $token))
            ->assertRedirect(route('cliente.entrar'));

        $this->assertNull(session('cliente_company_id'));
    }

    public function test_pedido_reenviar_link_exige_empresa_autenticada(): void
    {
        $this->company();

        $this->get(route('cliente.pedidos'))
            ->assertRedirect(route('cliente.entrar'));
    }
}
