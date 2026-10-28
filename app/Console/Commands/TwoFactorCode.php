<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Console\Command;

class TwoFactorCode extends Command
{
    protected $signature = 'two-factor:code {email : E-mail do usuário}';

    protected $description = 'Gera um código de verificação em duas etapas sem enviar e-mail (acesso de emergência)';

    public function handle(TwoFactorService $service): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('Usuário não encontrado.');

            return self::FAILURE;
        }

        $code = $service->createCode($user);

        $this->info("Código de verificação para {$user->email}: {$code}");
        $this->line('Válido por ' . TwoFactorService::CODE_TTL_MINUTES . ' minutos e de uso único.');

        return self::SUCCESS;
    }
}
