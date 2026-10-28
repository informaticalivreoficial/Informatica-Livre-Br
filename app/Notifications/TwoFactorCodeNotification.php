<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $code,
        public int $expiresInMinutes
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Código de verificação - ' . config('app.name'))
            ->markdown('emails.two-factor-code', [
                'name' => $notifiable->name,
                'code' => $this->code,
                'expiresInMinutes' => $this->expiresInMinutes,
                'email' => $notifiable->email,
            ]);
    }
}
