<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use WebMaster\PagHiper\PagHiper;

class PaghiperController extends Controller
{
    public function request(Request $request)
    {
        $paghiper = new PagHiper(
            env('PAGHIPER_APIKEY'), 
            env('PAGHIPER_TOKEM')
        );

        $transaction = $paghiper->notification()->response(
            $_POST['notification_id'], 
            $_POST['idTransacao']
        );
        //return $transaction;
        if(!empty($transaction)){
            $pedido = Pedido::where('transaction_id', $_POST['idTransacao'])->first();
            $pedido->status = $transaction['status'];
            $pedido->valor = $transaction['value_cents'];
            $pedido->url_slip = $transaction['bank_slip']['url_slip'];
            $pedido->digitable_line = $transaction['bank_slip']['digitable_line'];
            $pedido->vencimento = $transaction['due_date'];
            $pedido->save();
        }
    }
}
