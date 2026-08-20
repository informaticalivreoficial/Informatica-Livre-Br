<?php

namespace App\Notifications;

use App\Models\FinancialTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FinancialDueNotification extends Notification
{
    use Queueable;

    public function __construct(
        public FinancialTransaction $transaction,
        public string $type
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'telegram'];
    }

    public function toTelegram(object $notifiable): string
    {
        $data = $this->toArray($notifiable);

        return "⚠️ {$data['title']}\n{$data['message']}\n{$data['description']}";
    }

    public function toArray(object $notifiable): array
    {
        $dueDate = $this->transaction->dueDate();

        return match ($this->type) {
            'overdue' => [
                'type' => 'financial_due_overdue',
                'title' => 'Despesa vencida',
                'message' => "{$this->transaction->descriptionText()} está vencida.",
                'description' => 'Vencimento: ' . ($dueDate?->format('d/m/Y') ?? '—') . ' • R$ ' . number_format($this->transaction->amount, 2, ',', '.'),
                'color' => 'danger',
                'url' => route('financial.transactions'),
            ],
            default => [
                'type' => 'financial_due_soon',
                'title' => 'Despesa a vencer',
                'message' => "{$this->transaction->descriptionText()} vence em breve.",
                'description' => 'Vencimento: ' . ($dueDate?->format('d/m/Y') ?? '—') . ' • R$ ' . number_format($this->transaction->amount, 2, ',', '.'),
                'color' => 'warning',
                'url' => route('financial.transactions'),
            ],
        };
    }
}