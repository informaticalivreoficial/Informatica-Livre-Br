<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use WebMaster\PagHiper\PagHiper;

class BancoController extends Controller
{
    public function index(){
        $bancos = Banco::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.bancos.index', [
            'bancos' => $bancos
        ]);
    }

    public function refresh()
    {
        $paghiper = new PagHiper(
            env('PAGHIPER_APIKEY'), 
            env('PAGHIPER_TOKEM')
        );
        $banckAccounts = $paghiper->banking()->accounts();
        if(!empty($banckAccounts) && $banckAccounts['result'] == 'success'){            
            foreach($banckAccounts['bank_account_list'] as $banco){
                $bancos = Banco::where('bank_account_id', $banco['bank_account_id'])->first();
                if(empty($bancos)){
                    $createBanco = Banco::create($banco);
                    $createBanco->save();                    
                }                
            }
            return Redirect::back()->withInput()->with([
                'color' => 'success',
                'message' => 'Lista de Bancos atualizada com sucesso!',
            ]);
        }else{
            return Redirect::back()->withInput()->with([
                'color' => 'info',
                'message' => 'Não foram encontrados registros!',
            ]);
        }     
    }
}
