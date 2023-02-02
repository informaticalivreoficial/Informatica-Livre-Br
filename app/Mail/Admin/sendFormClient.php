<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Markdown;

class sendFormClient extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->data['siteemail'], $this->data['sitename'])
            ->to($this->data['reply_email'], $this->data['reply_name'])
            ->from($this->data['siteemail'], $this->data['sitename'])
            ->subject('#Orçamento: ' . $this->data['reply_name'])
            ->markdown('emails.form-send-client', [
                'token' => $this->data['token'],
                'nome' => $this->data['nome'],
        ]);
    }
}
