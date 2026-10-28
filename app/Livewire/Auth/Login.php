<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\TwoFactorService;
use App\Traits\WithToastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    use WithToastr;

    private const SESSION_KEY = 'login.two_factor';

    private const PENDING_TTL_MINUTES = 15;

    public $email = "";
    public $password = "";
    public $config;

    public bool $awaitingCode = false;
    public bool $showingRecoveryCodes = false;
    public string $code = '';
    public string $maskedEmail = '';
    public array $recoveryCodes = [];
    public int $resendIn = 0;

    public function mount()
    {
        $this->config = \App\Models\Config::first();
    }

    public function login()
    {
        $validator = Validator::make(
            [
                'email' => $this->email,
                'password' => $this->password,
            ],
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            $this->toastError('Preencha e-mail e senha corretamente.');
            return;
        }

        if (RateLimiter::tooManyAttempts(request()->ip(), 10)) {
            $seconds = RateLimiter::availableIn(request()->ip(), 10);
            $this->toastError("Muitas tentativas. Aguarde {$seconds} segundos.");
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit(request()->ip());

            $this->toastError('E-mail ou senha inválidos.');
            return;
        }

        if ($user->status != 1) {
            $this->toastError('Seu usuário está inativo. Entre em contato com o administrador.');
            return;
        }

        RateLimiter::clear(request()->ip());

        if (!$this->sendCode($user)) {
            return;
        }

        $this->password = '';
        $this->code = '';
        $this->awaitingCode = true;

        $this->toastInfo('Enviamos um código de verificação para ' . $this->maskedEmail . '.');
    }

    public function resend(TwoFactorService $service)
    {
        $user = $this->pendingUser();

        if (!$user) {
            $this->cancel();
            $this->toastError('Sessão expirada. Faça login novamente.');
            return;
        }

        $wait = $service->resendIn($user);

        if ($wait > 0) {
            $this->toastWarning("Aguarde {$wait} segundos para solicitar um novo código.");
            return;
        }

        if (!$this->sendCode($user)) {
            return;
        }

        $this->toastSuccess('Novo código enviado para ' . $this->maskedEmail . '.');
    }

    public function verify(TwoFactorService $service)
    {
        $this->validate([
            'code' => 'required|string|max:20',
        ], [], [
            'code' => 'código',
        ]);

        $user = $this->pendingUser();

        if (!$user) {
            $this->cancel();
            $this->toastError('Sessão expirada. Faça login novamente.');
            return;
        }

        $code = trim($this->code);
        $shouldShowRecoveryCodes = $service->remainingRecoveryCodes($user) === 0;

        $valid = preg_match('/^\d{6}$/', $code)
            ? $service->verifyCode($user, $code)
            : $service->verifyRecoveryCode($user, $code);

        if (!$valid) {
            $this->toastError('Código inválido ou expirado.');
            return;
        }

        Auth::login($user);

        session()->forget(self::SESSION_KEY);

        $this->reset('code', 'awaitingCode');

        if ($shouldShowRecoveryCodes) {
            $this->recoveryCodes = $service->generateRecoveryCodes($user);
            $this->showingRecoveryCodes = true;
            return;
        }

        return $this->finishLogin($user);
    }

    public function confirmRecoveryCodes()
    {
        $this->reset('recoveryCodes', 'showingRecoveryCodes');

        return $this->finishLogin(Auth::user());
    }

    public function cancel()
    {
        session()->forget(self::SESSION_KEY);

        $this->reset('awaitingCode', 'showingRecoveryCodes', 'code', 'recoveryCodes', 'maskedEmail', 'resendIn');
    }

    private function sendCode(User $user): bool
    {
        try {
            app(TwoFactorService::class)->sendCode($user);
        } catch (\Throwable $e) {
            report($e);

            \Illuminate\Support\Facades\Log::error('Falha ao enviar o código de verificação em duas etapas', [
                'user_id' => $user->id,
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            $this->toastError('Não foi possível enviar o código de verificação. Tente novamente ou use um código de recuperação.');
            return false;
        }

        session([self::SESSION_KEY => [
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(self::PENDING_TTL_MINUTES)->timestamp,
        ]]);

        $this->maskedEmail = $this->maskEmail($user->email);
        $this->resendIn = TwoFactorService::RESEND_SECONDS;

        return true;
    }

    private function pendingUser(): ?User
    {
        $pending = session(self::SESSION_KEY);

        if (!$pending || ($pending['expires_at'] ?? 0) < now()->timestamp) {
            return null;
        }

        return User::find($pending['user_id']);
    }

    private function finishLogin(?User $user)
    {
        if (!$user) {
            return redirect()->route('login');
        }

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Bem-vindo de volta, ' . \App\Helpers\Renato::getPrimeiroNome($user->name) . '!',
        ]);

        return redirect()->route('admin');
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email) + [1 => ''];

        $visible = mb_substr($name, 0, 2);

        return $visible . str_repeat('*', max(mb_strlen($name) - 2, 2)) . '@' . $domain;
    }

    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login', [
            'config' => $this->config,
        ]);
    }
}
