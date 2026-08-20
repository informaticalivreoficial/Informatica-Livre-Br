<?php

namespace Tests\Unit\Services;

use App\Services\TelegramService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);
    }

    public function test_send_message_sends_to_telegram_api(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        $result = app(TelegramService::class)->sendMessage('Olá mundo');

        $this->assertTrue($result);

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://api.telegram.org/bottest-token/sendMessage'
                && $request['chat_id'] === '123456'
                && $request['text'] === 'Olá mundo';
        });
    }

    public function test_send_html_sends_with_parse_mode(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        app(TelegramService::class)->sendHtml('<b>Teste</b>');

        Http::assertSent(function (Request $request) {
            return $request['parse_mode'] === 'HTML'
                && $request['text'] === '<b>Teste</b>';
        });
    }

    public function test_send_message_returns_false_when_token_missing(): void
    {
        config(['services.telegram.bot_token' => null]);

        $this->assertFalse(app(TelegramService::class)->sendMessage('teste'));
    }

    public function test_send_message_returns_false_when_chat_id_missing(): void
    {
        config(['services.telegram.chat_id' => null]);

        $this->assertFalse(app(TelegramService::class)->sendMessage('teste'));
    }

    public function test_send_message_returns_false_when_api_fails(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => false], 500),
        ]);

        $this->assertFalse(app(TelegramService::class)->sendMessage('teste'));
    }

    public function test_send_message_returns_false_when_api_returns_ok_false(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => false]),
        ]);

        $this->assertFalse(app(TelegramService::class)->sendMessage('teste'));
    }
}