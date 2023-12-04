<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fatura;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

    public function faturas($pedido)
    {
        $getPedido = Pedido::where('id', $pedido)->first();
        $faturas = Fatura::orderBy('created_at', 'ASC')->where('pedido', $pedido)->paginate(50);
        return view('admin.pedidos.faturas',[
            'faturas' => $faturas,
            'getPedido' => $getPedido
        ]);
    }

    public function delete(Request $request)
    {        
        $fatura = Fatura::where('id', $request->id)->first();
        dd($fatura);
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);

        if(!empty($fatura)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir esta Fatura?";                      
            return response()->json(['error' => $json,'id' => $request->id]);
        }else{
            return response()->json(['error' => 'Erro ao excluir']);
        }     
    }

    public function deleteon(Request $request)
    {
        $fatura = Fatura::where('id', $request->fatura_id)->first();
        $pedido = $fatura->pedido;
        if(!empty($fatura)){            
            $fatura->delete();
        }
        return Redirect::route('vendas.orcamentos',[
            'pedido' => $pedido
        ])->with([
            'color' => 'success', 
            'message' => 'Fatura removida com sucesso!'
        ]);
    }
}
