<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Markdown;

class FaturaClientSend extends Mailable
{
    use Queueable, SerializesModels;

    private $data, $pedido;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, $pedido)
    {
        $this->data = $data;
        $this->pedido = $pedido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->data['siteemail'], $this->data['sitename'])
            ->to($this->data['client_email'], $this->data['client_name'])
            ->bcc('suporte@informaticalivre.com.br')
            ->from($this->data['siteemail'], $this->data['sitename'])
            ->subject('#Fatura Informática Livre')
            ->markdown('emails.send-fatura-client', [
                'uuid' => $this->data['uuid'],
                'nome' => $this->data['client_name'],
                'fatura' => $this->pedido,
        ]);
    }
}
