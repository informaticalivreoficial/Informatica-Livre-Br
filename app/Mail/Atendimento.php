<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Atendimento extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Contato via site',  
            from: new Address(env('MAIL_FROM_ADDRESS'), env('APP_NAME')), // Remetente
            to: [new Address(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))], // Destinatário                
            replyTo: [
                new Address($this->data['email'], $this->data['name']),
            ],
            //bcc: env('MAIL_FROM_ADDRESS'), // Cópia oculta (opcional)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.atendimento',
            with:[
                'nome' => $this->data['name'],
                'email' => $this->data['email'],
                'telefone' => $this->data['phone'],
                'assunto' => $this->data['subject'],
                'mensagem' => $this->data['message']
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
