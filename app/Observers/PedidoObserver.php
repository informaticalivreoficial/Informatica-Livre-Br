<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Pedido;

class PedidoObserver
{
    public function creating(Pedido $pedido)
    {
        $pedido->uuid = (string) Str::uuid();
    }
}
