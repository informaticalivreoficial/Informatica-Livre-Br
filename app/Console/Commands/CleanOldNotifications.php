<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class CleanOldNotifications extends Command
{
    protected $signature = 'notifications:clean-old';
    protected $description = 'Remove notificações com mais de 90 dias';

    public function handle(): int
    {
        $deleted = DatabaseNotification::query()
            ->where('created_at', '<', Carbon::now()->subDays(90))
            ->delete();

        $this->info("Notificações removidas: {$deleted}");

        return self::SUCCESS;
    }
}
