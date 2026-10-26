<?php

namespace App\Mail\Loja;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoPago extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Pedido $pedido)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pagamento confirmado do pedido ' . $this->pedido->codigo . ' — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loja.pedido-pago',
            with: [
                'pedido' => $this->pedido->loadMissing('itens', 'licencas'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
