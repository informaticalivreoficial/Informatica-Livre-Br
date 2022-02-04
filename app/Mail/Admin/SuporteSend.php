<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuporteSend extends Mailable
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
        $this->replyTo($this->data['email'], $this->data['username']);
        $this->to(env('DESENVOLVEDOR_EMAIL'), $this->data['username']);
        $this->from($this->data['email'], $this->data['username']);
        $this->subject('#Solicitação de suporte - '.$this->data['sitename']);
        $this->markdown('admin.email.sendsuporte', [
            'mensagem' => $this->data['mensagem']
        ]);
        return $this;        
    }
}
