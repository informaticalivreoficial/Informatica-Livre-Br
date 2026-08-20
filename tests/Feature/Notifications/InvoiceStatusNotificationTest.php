<?php

namespace Tests\Feature\Notifications;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoiceStatusNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InvoiceStatusNotificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);
    }

    public function test_via_includes_telegram_channel(): void
    {
        $notification = new InvoiceStatusNotification(new Invoice(), 'paid');

        $this->assertContains('telegram', $notification->via(new User()));
    }

    public function test_to_telegram_returns_formatted_message(): void
    {
        $invoice = new Invoice(['amount' => 199.90]);
        $invoice->id = 42;

        $notification = new InvoiceStatusNotification($invoice, 'paid');

        $message = $notification->toTelegram(new User());

        $this->assertStringContainsString('Pagamento recebido', $message);
        $this->assertStringContainsString('Fatura #42', $message);
        $this->assertStringContainsString('R$ 199,90', $message);
    }

    public function test_sending_notification_sends_telegram_and_stores_database_notification(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole('super-admin');

        $invoice = new Invoice(['amount' => 199.90]);
        $invoice->id = 42;

        Notification::send($user, new InvoiceStatusNotification($invoice, 'paid'));

        Http::assertSent(function (Request $request) {
            return str_contains($request['text'], 'Pagamento recebido');
        });

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => InvoiceStatusNotification::class,
        ]);
    }
}