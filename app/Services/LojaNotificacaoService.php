<?php

namespace App\Services;

use App\Mail\Loja\PedidoPago;
use App\Mail\Loja\PedidoRecebido;
use App\Models\Pedido;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LojaNotificacaoService
{
    public function pedidoRecebido(Pedido $pedido): void
    {
        $this->enviar($pedido->email, new PedidoRecebido($pedido));
    }

    public function pedidoPago(Pedido $pedido): void
    {
        $this->enviar($pedido->email, new PedidoPago($pedido));
    }

    private function enviar(?string $email, Mailable $mailable): void
    {
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send($mailable);
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar e-mail da loja', [
                'email' => $email,
                'mailable' => $mailable::class,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
