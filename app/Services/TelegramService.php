<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function sendMessage(string $message, ?string $chatId = null): bool
    {
        return $this->send('sendMessage', [
            'chat_id' => $chatId ?: config('services.telegram.chat_id'),
            'text' => $message,
        ]);
    }

    public function sendHtml(string $message, ?string $chatId = null): bool
    {
        return $this->send('sendMessage', [
            'chat_id' => $chatId ?: config('services.telegram.chat_id'),
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);
    }

    protected function send(string $method, array $payload): bool
    {
        $token = config('services.telegram.bot_token');

        if (empty($token) || empty($payload['chat_id'])) {
            return false;
        }

        try {
            $response = Http::timeout(10)
                ->post("https://api.telegram.org/bot{$token}/{$method}", $payload);

            if ($response->failed()) {
                Log::warning('Telegram API error', [
                    'method' => $method,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                return false;
            }

            return (bool) ($response->json('ok') ?? false);
        } catch (\Throwable $e) {
            Log::warning('Telegram service exception', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}