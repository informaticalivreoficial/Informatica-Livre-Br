<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification
{
    use Queueable;

    public function __construct(public array $data) {}

    public function via(object $notifiable): array
    {
        return ['database', 'telegram'];
    }

    public function toTelegram(object $notifiable): string
    {
        $telefone = $this->data['phone'] ?: 'Não informado';

        return "📩 Nova solicitação de orçamento\n"
            . "Nome: {$this->data['name']}\n"
            . "E-mail: {$this->data['email']}\n"
            . "Telefone: {$telefone}\n"
            . "Assunto: {$this->data['subject']}\n"
            . "Mensagem: {$this->data['message']}";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'contact_message',
            'title' => 'Nova solicitação de orçamento',
            'message' => "{$this->data['name']} • {$this->data['subject']}",
            'description' => $this->data['message'],
            'color' => 'info',
            'url' => route('admin'),
        ];
    }
}