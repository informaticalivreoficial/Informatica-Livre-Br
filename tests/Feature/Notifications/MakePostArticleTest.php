<?php

namespace Tests\Feature\Notifications;

use App\Models\Post;
use App\Models\User;
use App\Notifications\MakePostArticle;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MakePostArticleTest extends TestCase
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
        $notification = new MakePostArticle(new Post());

        $this->assertContains('telegram', $notification->via(new User()));
    }

    public function test_to_telegram_returns_formatted_message(): void
    {
        $post = new Post(['title' => 'Artigo de teste']);
        $post->id = 7;

        $notification = new MakePostArticle($post);

        $message = $notification->toTelegram(new User());

        $this->assertStringContainsString('Novo artigo gerado', $message);
        $this->assertStringContainsString('Artigo de teste', $message);
        $this->assertStringContainsString('/admin/posts/7/edit', $message);
    }

    public function test_sending_notification_sends_telegram_and_stores_database_notification(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole('super-admin');

        $post = new Post(['title' => 'Artigo de teste']);
        $post->id = 7;

        Notification::send($user, new MakePostArticle($post));

        Http::assertSent(function (Request $request) {
            return str_contains($request['text'], 'Novo artigo gerado');
        });

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => MakePostArticle::class,
        ]);
    }
}