<?php

namespace App\Console\Commands;

use App\Enums\FinancialStatus;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Notifications\FinancialDueNotification;
use Illuminate\Console\Command;

class CheckDueFinancialTransactions extends Command
{
    protected $signature = 'financial:check-due {--days=3 : Dias de antecedência para alertar}';
    protected $description = 'Notifica despesas vencidas ou a vencer nos próximos dias';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $transactions = FinancialTransaction::expenses()
            ->where('status', FinancialStatus::PENDING->value)
            ->with(['expense', 'category'])
            ->get()
            ->filter(function (FinancialTransaction $transaction) use ($days) {
                $dueDate = $transaction->dueDate();

                if (!$dueDate) {
                    return false;
                }

                $daysUntil = (int) today()->diffInDays($dueDate, false);

                if ($daysUntil > $days) {
                    return false;
                }

                if ($transaction->due_notified_at && $transaction->due_notified_at->isAfter(now()->subDay())) {
                    return false;
                }

                return true;
            });

        if ($transactions->isEmpty()) {
            $this->info('Nenhuma despesa para notificar.');

            return self::SUCCESS;
        }

        $admins = User::query()
            ->role('super-admin')
            ->get();

        foreach ($transactions as $transaction) {
            $type = $transaction->isOverdue() ? 'overdue' : 'soon';

            foreach ($admins as $admin) {
                $admin->notify(new FinancialDueNotification($transaction, $type));
            }

            $transaction->update(['due_notified_at' => now()]);

            $this->line("Notificado: {$transaction->descriptionText()} ({$type})");
        }

        $this->info("{$transactions->count()} despesa(s) notificada(s).");

        return self::SUCCESS;
    }
}