<?php

namespace App\Mail\Web;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Markdown;

class Compra extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $cliente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, array $cliente)
    {
        $this->data = $data;
        $this->cliente = $cliente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->cliente['email'], $this->cliente['name'])
            ->to($this->data['siteemail'], $this->data['sitename'])
            ->from($this->data['siteemail'], $this->data['sitename'])
            ->subject('⚓️ Venda pelo Site: ' . $this->data['data_passeio'])
            ->markdown('emails.compra', [
                //Pedido
                'status' => $this->data['status'],
                'passeio' => $this->data['passeio'],
                'data_passeio' => $this->data['data_passeio'],
                'qtd_adultos' => $this->data['qtd_adultos'],
                'qtd_zerocinco' => $this->data['qtd_zerocinco'],
                'qtd_seisdoze' => $this->data['qtd_seisdoze'],
                'total_passageiros' =>  $this->data['total_passageiros'],
                'valor_adulto' => $this->data['valor_adulto'],
                'valorCri05' => $this->data['valorCri05'],
                'valorCri06' => $this->data['valorCri06'],
                'total' => $this->data['total'],
                'sitename' => $this->data['sitename'],
                'siteemail' => $this->data['siteemail'],                
                //Cliente
                'name' => $this->cliente['name'],
                'email' => $this->cliente['email'],
                'cpf' => $this->cliente['cpf'],
                'celular' => $this->cliente['celular']
        ]);
    }
}
