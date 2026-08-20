<?php

namespace App\Notifications\Channels;

use App\Services\TelegramService;
use Illuminate\Notifications\Notification;

class TelegramChannel
{
    protected static array $sent = [];

    public function __construct(private TelegramService $telegram) {}

    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toTelegram')) {
            return;
        }

        $hash = spl_object_hash($notification);

        if (isset(static::$sent[$hash])) {
            return;
        }

        static::$sent[$hash] = true;

        $message = $notification->toTelegram($notifiable);

        if (empty($message)) {
            return;
        }

        $this->telegram->sendMessage($message);
    }
}