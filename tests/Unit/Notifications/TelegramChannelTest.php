<?php

namespace Tests\Unit\Notifications;

use App\Notifications\Channels\TelegramChannel;
use App\Services\TelegramService;
use Illuminate\Http\Client\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramChannelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);
    }

    public function test_send_sends_message_when_notification_has_to_telegram(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        $notification = new class extends Notification {
            public function via($notifiable): array
            {
                return ['telegram'];
            }

            public function toTelegram($notifiable): string
            {
                return 'Mensagem de teste';
            }
        };

        $channel = new TelegramChannel(app(TelegramService::class));
        $channel->send(new \stdClass(), $notification);

        Http::assertSent(function (Request $request) {
            return $request['text'] === 'Mensagem de teste';
        });
    }

    public function test_send_skips_when_notification_has_no_to_telegram(): void
    {
        Http::fake();

        $notification = new class extends Notification {
            public function via($notifiable): array
            {
                return ['telegram'];
            }
        };

        $channel = new TelegramChannel(app(TelegramService::class));
        $channel->send(new \stdClass(), $notification);

        Http::assertNothingSent();
    }

    public function test_send_deduplicates_same_notification_instance(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        $notification = new class extends Notification {
            public function via($notifiable): array
            {
                return ['telegram'];
            }

            public function toTelegram($notifiable): string
            {
                return 'Mensagem de teste';
            }
        };

        $channel = new TelegramChannel(app(TelegramService::class));
        $channel->send(new \stdClass(), $notification);
        $channel->send(new \stdClass(), $notification);

        Http::assertSentCount(1);
    }
}