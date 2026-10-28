<?php

namespace Tests\Feature\Dashboard;

use App\Livewire\Dashboard\SecuritySettings;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class SecuritySettingsTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'password' => Hash::make('password'),
            'status' => 1,
        ], $attributes));
    }

    public function test_pagina_exige_autenticacao(): void
    {
        $this->get(route('security.index'))->assertRedirect(route('login'));
    }

    public function test_pagina_renderiza_para_usuario_autenticado(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->get(route('security.index'))
            ->assertOk()
            ->assertSee('Verificação em duas etapas');
    }

    public function test_nao_gera_codigos_com_senha_incorreta(): void
    {
        $user = $this->makeUser();

        Livewire::actingAs($user)
            ->test(SecuritySettings::class)
            ->set('current_password', 'senha-errada')
            ->call('regenerate')
            ->assertHasErrors(['current_password']);

        $this->assertSame(0, $user->twoFactorRecoveryCodes()->count());
    }

    public function test_gera_codigos_com_senha_correta(): void
    {
        $user = $this->makeUser();

        Livewire::actingAs($user)
            ->test(SecuritySettings::class)
            ->set('current_password', 'password')
            ->call('regenerate')
            ->assertSet('showingCodes', true)
            ->assertCount('recoveryCodes', TwoFactorService::RECOVERY_CODES_COUNT);

        $this->assertSame(TwoFactorService::RECOVERY_CODES_COUNT, $user->twoFactorRecoveryCodes()->count());
    }
}
