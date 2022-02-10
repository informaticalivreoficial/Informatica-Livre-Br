<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrcamentoController extends Controller
{
    public function index()
    {
        $orcamentos = Orcamento::orderBy('created_at', 'DESC')->available()->paginate(25);
        return view('admin.vendas.orcamentos',[
            'orcamentos' => $orcamentos
        ]);
    }

    public function delete(Request $request)
    {
        $orcamento = Orcamento::where('id', $request->id)->first();
        $nome = getPrimeiroNome(Auth::user()->name);

        if(!empty($orcamento)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este orçamento?";                      
            return response()->json(['error' => $json,'id' => $request->id]);
        }else{
            return response()->json(['error' => 'Erro ao excluir']);
        }     
    }

    public function deleteon(Request $request)
    {
        $orcamento = Orcamento::where('id', $request->orcamento_id)->first();
        if(!empty($orcamento)){            
            $orcamento->delete();
        }
        return Redirect::route('vendas.orcamentos')->with([
            'color' => 'success', 
            'message' => 'Orçamento removido com sucesso!'
        ]);
    }
}
