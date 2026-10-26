<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class CheckoutReturnController extends Controller
{
    public function __invoke(Request $request)
    {
        $pedido = Pedido::query()
            ->with('itens')
            ->where('codigo', (string) $request->query('pedido'))
            ->first();

        return view('web.loja.retorno', [
            'pedido' => $pedido,
            'title' => 'Retorno do pagamento',
        ]);
    }
}
