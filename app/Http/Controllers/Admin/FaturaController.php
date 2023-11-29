<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaturaController extends Controller
{
    public function SetGateway(Request $request)
    {        
        dd($request->all());
        // $pedido = Pedido::find($request->pedido);
        // $pedido->gateway = $request->gateway;
        // $pedido->save();
        
        // $allFaturas = Fatura::where('pedido', $pedido->id)->get();
        // foreach ($allFaturas as $fatura) {
        //     $fatura->gateway = $pedido->gateway;
        //     $fatura->save();
        // }

        // return response()->json(['success' => true]);
    }
}
