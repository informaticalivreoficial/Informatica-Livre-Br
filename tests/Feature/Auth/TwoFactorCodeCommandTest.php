<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TwoFactorCodeCommandTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(): User
    {
        return User::factory()->create([
            'password' => Hash::make('password'),
            'status' => 1,
        ]);
    }

    public function test_gera_codigo_sem_enviar_email(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $this->artisan('two-factor:code', ['email' => $user->email])->assertSuccessful();

        Notification::assertNothingSent();

        $this->assertNotNull($user->fresh()->two_factor_code);
        $this->assertNotNull($user->fresh()->two_factor_expires_at);
    }

    public function test_codigo_gerado_pode_ser_usado_no_login(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        Artisan::call('two-factor:code', ['email' => $user->email]);

        preg_match('/(\d{6})/', Artisan::output(), $matches);

        $this->assertNotEmpty($matches[1] ?? null);
        $this->assertTrue(app(TwoFactorService::class)->verifyCode($user->fresh(), $matches[1]));
    }

    public function test_falha_quando_usuario_nao_existe(): void
    {
        $this->artisan('two-factor:code', ['email' => 'inexistente@example.com'])
            ->assertFailed();
    }
}
