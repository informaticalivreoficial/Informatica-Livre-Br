<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MakePostArticle extends Notification
{
    use Queueable;

    public function __construct(public Post $post) {}    

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo artigo gerado: ' . $this->post->title)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Um novo artigo foi gerado automaticamente pelo fluxo de conteúdo e está aguardando revisão antes da publicação.')
            ->line('Título: ' . $this->post->title)
            ->when($this->post->excerpt, function (MailMessage $mail) {
                return $mail->line('Resumo: ' . $this->post->excerpt);
            })
            ->action('Revisar artigo', url('/admin/posts/' . $this->post->id . '/edit'))
            ->line('Assim que revisado, você pode publicá-lo no painel.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'ArticleCreated',
            'title' => 'Novo artigo gerado',
            'message' => "Artigo {$this->post->title}",
            'description' => "O artigo está aguardando revisão.",
            'color' => 'success',
            'url' => url('/admin/posts/' . $this->post->id . '/edit'),
        ];
    }
}
