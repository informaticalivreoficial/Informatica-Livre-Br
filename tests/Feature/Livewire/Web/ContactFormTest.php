<?php

namespace Tests\Feature\Livewire\Web;

use App\Livewire\Web\ContactForm;
use App\Mail\Atendimento;
use App\Models\User;
use App\Notifications\NewContactMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
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

    public function test_via_includes_database_and_telegram_channels(): void
    {
        $notification = new NewContactMessage([]);

        $this->assertContains('database', $notification->via(new User()));
        $this->assertContains('telegram', $notification->via(new User()));
    }

    public function test_to_array_returns_database_payload(): void
    {
        $notification = new NewContactMessage([
            'name' => 'João Silva',
            'email' => 'joao@email.com',
            'phone' => '(11) 99999-9999',
            'subject' => 'Orçamento para site',
            'message' => 'Preciso de um site institucional.',
        ]);

        $data = $notification->toArray(new User());

        $this->assertSame('contact_message', $data['type']);
        $this->assertSame('Nova solicitação de orçamento', $data['title']);
        $this->assertSame('João Silva • Orçamento para site', $data['message']);
        $this->assertSame('Preciso de um site institucional.', $data['description']);
        $this->assertSame('info', $data['color']);
    }

    public function test_to_telegram_returns_formatted_message(): void
    {
        $notification = new NewContactMessage([
            'name' => 'João Silva',
            'email' => 'joao@email.com',
            'phone' => '(11) 99999-9999',
            'subject' => 'Orçamento para site',
            'message' => 'Preciso de um site institucional.',
        ]);

        $message = $notification->toTelegram(new User());

        $this->assertStringContainsString('Nova solicitação de orçamento', $message);
        $this->assertStringContainsString('João Silva', $message);
        $this->assertStringContainsString('joao@email.com', $message);
        $this->assertStringContainsString('(11) 99999-9999', $message);
        $this->assertStringContainsString('Orçamento para site', $message);
        $this->assertStringContainsString('Preciso de um site institucional.', $message);
    }

    public function test_to_telegram_uses_fallback_when_phone_missing(): void
    {
        $notification = new NewContactMessage([
            'name' => 'João Silva',
            'email' => 'joao@email.com',
            'phone' => null,
            'subject' => 'Orçamento',
            'message' => 'Mensagem de teste.',
        ]);

        $message = $notification->toTelegram(new User());

        $this->assertStringContainsString('Telefone: Não informado', $message);
    }

    public function test_send_sends_email_and_telegram_to_super_admins(): void
    {
        Mail::fake();
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        $admin = User::factory()->create(['password' => 'password']);
        $admin->assignRole('super-admin');

        Livewire::test(ContactForm::class)
            ->set('name', 'João Silva')
            ->set('email', 'joao@email.com')
            ->set('phone', '(11) 99999-9999')
            ->set('subject', 'Orçamento para site')
            ->set('message', 'Preciso de um site institucional.')
            ->call('send')
            ->assertSet('sent', true);

        Mail::assertSent(Atendimento::class);

        Http::assertSent(function (Request $request) {
            return str_contains($request['text'], 'Nova solicitação de orçamento')
                && str_contains($request['text'], 'João Silva');
        });

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $admin->id,
            'type' => NewContactMessage::class,
        ]);
    }

    public function test_send_ignores_honeypot_fields(): void
    {
        Mail::fake();
        Http::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'João Silva')
            ->set('email', 'joao@email.com')
            ->set('subject', 'Orçamento')
            ->set('message', 'Mensagem de teste.')
            ->set('bairro', 'spam')
            ->call('send')
            ->assertSet('sent', false);

        Mail::assertNothingSent();
        Http::assertNothingSent();
    }
}