<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Admin\sendFormClient;
use App\Models\Configuracoes;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Services\ConfigService;
use Carbon\Carbon;

class OrcamentoController extends Controller
{

    // protected $configService;

    // public function __construct(ConfigService $configService)
    // {
    //     $this->configService = $configService;
    // }

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
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);

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

    public function sendFormCaptacaoClient(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $orcamento = Orcamento::where('id', $request->id)->first();
        $orcamento->form_sendat = now();
        $orcamento->save();

        $data = [            
            'sitename' => $Configuracoes->nomedosite,
            'siteemail' => $Configuracoes->email,
            'reply_name' => $orcamento->nome,
            'reply_email' => $orcamento->email,
            'token' => $orcamento->token,
            'nome' => $orcamento->name,
        ];
        
        Mail::send(new sendFormClient($data));
        
        return response()->json([
            'retorno' => true
        ]);
    }

    public function checkOrcamentoClient()
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $checkOrcamentos = Orcamento::whereDay('created_at', now())->whereNull('form_sendat')->get();
        if(!empty($checkOrcamentos) && $checkOrcamentos->count() > 0){
            foreach($checkOrcamentos as $item){

                $data = [            
                    'sitename' => $Configuracoes->nomedosite,
                    'siteemail' => $Configuracoes->email,
                    'email' => $item->email,
                    'token' => $item->token,
                    'name' => $item->name,
                ];
                
                Mail::send(new sendFormClient($data));

                $item->form_sendat = now();
                $item->save();
            }
        }

        return response()->json([
            'retorno' => true
        ]);
    }
}
