<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\Login;
use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class TwoFactorLoginTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'password' => Hash::make('password'),
            'status' => 1,
        ], $attributes));
    }

    private function sentCode(User $user): string
    {
        $code = null;

        Notification::assertSentTo($user, TwoFactorCodeNotification::class, function ($notification) use (&$code) {
            $code = $notification->code;

            return true;
        });

        return $code;
    }

    private function startLogin(User $user)
    {
        return Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login');
    }

    public function test_login_com_senha_valida_envia_codigo_e_nao_autentica(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $component = $this->startLogin($user)
            ->assertSet('awaitingCode', true)
            ->assertSee('Verificação em duas etapas')
            ->assertSee('Reenviar código')
            ->assertDontSee('Acessar painel');

        $this->assertStringContainsString('@', $component->get('maskedEmail'));
        $this->assertStringNotContainsString($user->email, $component->get('maskedEmail'));

        $this->assertGuest();
        Notification::assertSentTo($user, TwoFactorCodeNotification::class);
    }

    public function test_codigo_valido_autentica_o_usuario(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $component = $this->startLogin($user);
        $code = $this->sentCode($user);

        $component->set('code', $code)->call('verify');

        $this->assertAuthenticatedAs($user->fresh());
        $this->assertNotNull($user->fresh()->two_factor_confirmed_at);
    }

    public function test_codigo_invalido_nao_autentica(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $this->startLogin($user)
            ->set('code', '000000')
            ->call('verify')
            ->assertDispatched('toast');

        $this->assertGuest();
        $this->assertSame(1, $user->fresh()->two_factor_attempts);
    }

    public function test_codigo_expirado_nao_autentica(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $component = $this->startLogin($user);
        $code = $this->sentCode($user);

        $user->forceFill(['two_factor_expires_at' => now()->subMinute()])->save();

        $component->set('code', $code)->call('verify');

        $this->assertGuest();
    }

    public function test_limite_de_tentativas_invalida_o_codigo(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $component = $this->startLogin($user);
        $code = $this->sentCode($user);

        $user->forceFill(['two_factor_attempts' => TwoFactorService::MAX_ATTEMPTS])->save();

        $component->set('code', $code)->call('verify');

        $this->assertGuest();
    }

    public function test_codigo_de_recuperacao_autentica_e_e_consumido(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $service = app(TwoFactorService::class);
        $codes = $service->generateRecoveryCodes($user);

        $component = $this->startLogin($user);

        $component->set('code', strtolower($codes[0]))->call('verify');

        $this->assertAuthenticatedAs($user->fresh());
        $this->assertSame(TwoFactorService::RECOVERY_CODES_COUNT - 1, $service->remainingRecoveryCodes($user->fresh()));
    }

    public function test_codigo_de_recuperacao_ja_usado_nao_autentica(): void
    {
        Notification::fake();

        $user = $this->makeUser();
        $service = app(TwoFactorService::class);
        $codes = $service->generateRecoveryCodes($user);

        $this->assertTrue($service->verifyRecoveryCode($user, $codes[0]));
        $this->assertFalse($service->verifyRecoveryCode($user->fresh(), $codes[0]));
    }

    public function test_primeiro_acesso_exibe_codigos_de_recuperacao(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $component = $this->startLogin($user);
        $code = $this->sentCode($user);

        $component->set('code', $code)->call('verify')
            ->assertSet('showingRecoveryCodes', true)
            ->assertCount('recoveryCodes', TwoFactorService::RECOVERY_CODES_COUNT)
            ->assertSee('Códigos de recuperação')
            ->assertSee('Copiar códigos')
            ->assertSee('Já salvei, continuar');

        $this->assertSame(TwoFactorService::RECOVERY_CODES_COUNT, $user->fresh()->twoFactorRecoveryCodes()->count());
    }

    public function test_usuario_inativo_nao_recebe_codigo(): void
    {
        Notification::fake();

        $user = $this->makeUser(['status' => 0]);

        $this->startLogin($user)->assertSet('awaitingCode', false);

        $this->assertGuest();
        Notification::assertNothingSent();
    }
}
