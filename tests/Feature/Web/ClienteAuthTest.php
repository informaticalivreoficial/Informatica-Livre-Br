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

    public function test_cliente_atualiza_dados_basicos(): void
    {
        $company = $this->company();

        $this->withSession(['cliente_company_id' => $company->id])
            ->get(route('cliente.empresa'))
            ->assertOk()
            ->assertSee('Salvar alterações');

        $this->withSession(['cliente_company_id' => $company->id])
            ->put(route('cliente.empresa.atualizar'), [
                'alias_name' => 'Clinica Atualizada',
                'social_name' => 'Clinica Maria LTDA',
                'document_company' => '12.345.678/0001-90',
                'email' => 'contato@clinica.com',
                'phone' => '(12) 3333-4444',
                'whatsapp' => '(12) 99999-8888',
                'responsable_name' => 'Maria Silva',
                'responsable_email' => 'maria@clinica.com',
                'responsable_cpf' => '123.456.789-00',
                'zipcode' => '12345-000',
                'street' => 'Rua A',
                'number' => '100',
                'complement' => 'Sala 1',
                'neighborhood' => 'Centro',
                'city' => 'Sao Jose',
                'state' => 'sp',
            ])
            ->assertRedirect(route('cliente.empresa'))
            ->assertSessionHas('success');

        $company->refresh();

        $this->assertSame('Clinica Atualizada', $company->alias_name);
        $this->assertSame('contato@clinica.com', $company->email);
        $this->assertSame('Maria Silva', $company->responsable_name);
        $this->assertSame('SP', $company->state);
        $this->assertSame('1233334444', $company->getRawOriginal('phone'));
        $this->assertSame('12345678000190', $company->getRawOriginal('document_company'));
    }

    public function test_cliente_nao_pode_usar_email_de_outra_empresa(): void
    {
        $company = $this->company();

        Company::create([
            'responsable_name' => 'Outro Responsavel',
            'responsable_email' => 'outro@example.com',
            'email' => 'outro@example.com',
            'alias_name' => 'Outra Empresa',
            'status' => true,
        ]);

        $this->withSession(['cliente_company_id' => $company->id])
            ->put(route('cliente.empresa.atualizar'), [
                'alias_name' => 'Clinica Maria',
                'email' => 'outro@example.com',
                'responsable_name' => 'Maria Souza',
                'responsable_email' => 'maria@example.com',
            ])
            ->assertSessionHasErrors('email');

        $this->assertSame('maria@example.com', $company->fresh()->email);
    }
}
