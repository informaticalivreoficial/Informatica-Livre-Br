<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Throwable;

class TwoFactorDiagnose extends Command
{
    protected $signature = 'two-factor:diagnose {email : E-mail do usuário}';

    protected $description = 'Diagnostica a estrutura e o envio do código de verificação em duas etapas';

    public function handle(TwoFactorService $service): int
    {
        $this->components->info('Banco de dados');

        $columns = [
            'two_factor_code',
            'two_factor_expires_at',
            'two_factor_sent_at',
            'two_factor_confirmed_at',
            'two_factor_attempts',
        ];

        $missing = [];

        foreach ($columns as $column) {
            $exists = Schema::hasColumn('users', $column);

            if (!$exists) {
                $missing[] = $column;
            }

            $this->components->twoColumnDetail("users.{$column}", $exists ? '<fg=green>ok</>' : '<fg=red>ausente</>');
        }

        $hasTable = Schema::hasTable('two_factor_recovery_codes');

        if (!$hasTable) {
            $missing[] = 'two_factor_recovery_codes';
        }

        $this->components->twoColumnDetail('tabela two_factor_recovery_codes', $hasTable ? '<fg=green>ok</>' : '<fg=red>ausente</>');

        if ($missing) {
            $this->newLine();
            $this->components->error('Execute "php artisan migrate --force" antes de testar o envio.');
        }

        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->components->error('Usuário não encontrado.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->info('Ambiente');

        $compiledPath = config('view.compiled');

        $this->components->twoColumnDetail('APP_ENV', (string) app()->environment());
        $this->components->twoColumnDetail('APP_DEBUG', config('app.debug') ? 'true' : 'false');
        $this->components->twoColumnDetail('queue', (string) config('queue.default'));
        $this->components->twoColumnDetail('views compiladas graváveis', is_writable($compiledPath) ? '<fg=green>sim</>' : '<fg=red>não</>');
        $this->components->twoColumnDetail('league/commonmark', class_exists(\League\CommonMark\CommonMarkConverter::class) ? '<fg=green>instalado</>' : '<fg=red>ausente</>');

        $this->newLine();
        $this->components->info('Transporte de e-mail');
        $this->components->twoColumnDetail('mailer', (string) config('mail.default'));
        $this->components->twoColumnDetail('host', (string) config('mail.mailers.smtp.host'));
        $this->components->twoColumnDetail('porta', (string) config('mail.mailers.smtp.port'));
        $this->components->twoColumnDetail('remetente', (string) config('mail.from.address'));

        $this->newLine();

        try {
            Mail::raw('Teste de envio de e-mail do sistema.', function ($message) use ($user) {
                $message->to($user->email)->subject('Teste de e-mail - ' . config('app.name'));
            });

            $this->components->twoColumnDetail('e-mail simples', '<fg=green>enviado</>');
        } catch (Throwable $e) {
            $this->components->twoColumnDetail('e-mail simples', '<fg=red>falhou</>');
            $this->line('   ' . get_class($e) . ': ' . $e->getMessage());
        }

        $this->newLine();

        try {
            $service->sendCode($user);

            $this->components->twoColumnDetail('código 2FA (markdown + envio)', '<fg=green>enviado</>');
            $this->components->info('Se o e-mail simples funcionou e este falhou, o problema está na view/template markdown.');
        } catch (Throwable $e) {
            $this->components->twoColumnDetail('código 2FA (markdown + envio)', '<fg=red>falhou</>');
            $this->line('   ' . get_class($e) . ': ' . $e->getMessage());
            $this->line('   ' . $e->getFile() . ':' . $e->getLine());
            $this->newLine();
            $this->components->warn('Para entrar no painel enquanto isso: php artisan two-factor:code ' . $user->email);
        }

        return self::SUCCESS;
    }
}
