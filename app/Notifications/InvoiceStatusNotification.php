<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Invoice $invoice,
        public string $status
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return match ($this->status) {

            'paid' => [
                'type' => 'invoice_paid',
                'title' => 'Pagamento recebido',
                'message' => "{$this->invoice->company?->alias_name} realizou um pagamento.",
                'description' => "Fatura #{$this->invoice->id} • R$ " . number_format($this->invoice->amount, 2, ',', '.'),
                'color' => 'success',
                'url' => route('invoices.show', $this->invoice),
            ],

            'canceled' => [
                'type' => 'invoice_canceled',
                'title' => 'Fatura cancelada',
                'message' => "{$this->invoice->company?->alias_name} teve uma fatura cancelada.",
                'description' => "Fatura #{$this->invoice->id}",
                'color' => 'danger',
                'url' => route('invoices.show', $this->invoice),
            ],

            'refunded' => [
                'type' => 'invoice_refunded',
                'title' => 'Pagamento estornado',
                'message' => "{$this->invoice->company?->alias_name} teve um pagamento estornado.",
                'description' => "Fatura #{$this->invoice->id}",
                'color' => 'warning',
                'url' => route('invoices.show', $this->invoice),
            ],

            default => [
                'type' => 'invoice_update',
                'title' => 'Atualização de fatura',
                'message' => 'O status da fatura foi atualizado.',
                'description' => "Fatura #{$this->invoice->id}",
                'color' => 'info',
                'url' => route('invoices.show', $this->invoice),
            ]
        };
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->toArray($notifiable);

        return (new MailMessage)
            ->subject($data['title'])
            ->line($data['message'])
            ->line($data['description']);
    }
}
