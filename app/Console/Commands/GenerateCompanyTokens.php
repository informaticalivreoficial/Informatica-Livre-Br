<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateCompanyTokens extends Command
{
    protected $signature = 'companies:generate-tokens';
    protected $description = 'Gera UUID e token para companies existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Company::whereNull('uuid')->orWhereNull('api_token')->each(function ($company) {
            $company->update([
                'uuid'      => Str::uuid(),
                'api_token' => Str::random(64),
            ]);
        });

        $this->info('Tokens gerados com sucesso!');
    }
}
