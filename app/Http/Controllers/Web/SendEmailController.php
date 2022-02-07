<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Web\ParceiroSend;
use App\Mail\Web\Atendimento;
use App\Mail\Web\AtendimentoRetorno;
use App\Mail\Web\OrcamentoRetorno;
use App\Mail\Web\ProdutoSend;
use App\Mail\Web\SendOrcamento;
use App\Models\Configuracoes;
use App\Models\Newsletter;
use App\Models\Parceiro;
use App\Models\Produto;
use Error;
use Illuminate\Support\Facades\Validator;

class SendEmailController extends Controller
{
    public function sendEmailParceiro(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $parceiro = Parceiro::where('id', $request->parceiro_id)->first();
        if($request->nome == ''){
            $json = "Please fill in the <strong>Name</strong> field";
            return response()->json(['error' => $json]);
        }
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $json = "The <strong>Email</strong> field is empty or does not have a valid format!";
            return response()->json(['error' => $json]);
        }
        if($request->mensagem == ''){
            $json = "Please fill in your <strong>Message</strong>";
            return response()->json(['error' => $json]);
        }
        if(!empty($request->bairro) || !empty($request->cidade)){
            $json = "<strong>ERROR</strong> you are practicing SPAM!"; 
            return response()->json(['error' => $json]);
        }else{

            $data = [
                'sitename' => $parceiro->name,
                'siteemail' => $parceiro->email,
                'reply_name' => $request->nome,
                'reply_email' => $request->email,
                'mensagem' => $request->mensagem,
                'config_site_name' => $Configuracoes->nomedosite
            ];

            $parceiro->email_send_count = $parceiro->email_send_count + 1;
            $parceiro->save();
            
            Mail::send(new ParceiroSend($data));
            
            $json = 'Thank you '.getPrimeiroNome($request->nome).', your message has been sent to our <b>'.$parceiro->name.'</b> partner successfully!'; 
            return response()->json(['sucess' => $json]);
        }
    }    

    public function sendEmail(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        if($request->nome == ''){
            $json = "Por favor preencha o campo <strong>Nome</strong>";
            return response()->json(['error' => $json]);
        }
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $json = "O campo <strong>Email</strong> está vazio ou não tem um formato válido!";
            return response()->json(['error' => $json]);
        }
        if($request->mensagem == ''){
            $json = "Por favor preencha sua <strong>Mensagem</strong>";
            return response()->json(['error' => $json]);
        }
        if(!empty($request->bairro) || !empty($request->cidade)){
            $json = "<strong>ERRO</strong> Você está praticando SPAM!"; 
            return response()->json(['error' => $json]);
        }else{
            $data = [
                'sitename' => $Configuracoes->nomedosite,
                'siteemail' => $Configuracoes->email,
                'reply_name' => $request->nome,
                'reply_email' => $request->email,
                'mensagem' => $request->mensagem
            ];

            $retorno = [
                'sitename' => $Configuracoes->nomedosite,
                'siteemail' => $Configuracoes->email,
                'reply_name' => $request->nome,
                'reply_email' => $request->email
            ];
            
            Mail::send(new Atendimento($data));
            Mail::send(new AtendimentoRetorno($retorno));
            
            $json = "Obrigado {$request->nome} sua mensagem foi enviada com sucesso!"; 
            return response()->json(['sucess' => $json]);
        }
    }

    public function sendNewsletter(Request $request)
    {
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $json = "The <strong>Email</strong> field is empty or does not have a valid format!";
            return response()->json(['error' => $json]);
        }
        if(!empty($request->bairro) || !empty($request->cidade)){
            $json = "<strong>ERROR</strong> you are practicing SPAM!"; 
            return response()->json(['error' => $json]);
        }else{   
            $validaNews = Newsletter::where('email', $request->email)->first();            
            if(!empty($validaNews)){
                Newsletter::where('email', $request->email)->update(['status' => 1]);
                $json = "Your email is already registered!"; 
                return response()->json(['sucess' => $json]);
            }else{
                $NewsletterCreate = Newsletter::create($request->all());
                $NewsletterCreate->save();
                $json = "Thank you Successfully registered!"; 
                return response()->json(['sucess' => $json]);
            }            
        }
    }

    public function sendOrcamento(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        if($request->nome == ''){
            $json = "Por favor preencha o campo <strong>Nome</strong>";
            return response()->json(['error' => $json]);
        }
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $json = "O campo <strong>Email</strong> está vazio ou não tem um formato válido!";
            return response()->json(['error' => $json]);
        }
        if($request->mensagem == ''){
            $json = "Por favor preencha sua <strong>Mensagem</strong>";
            return response()->json(['error' => $json]);
        }
        if(!empty($request->bairro) || !empty($request->cidade)){
            $json = "<strong>ERRO</strong> Você está praticando SPAM!"; 
            return response()->json(['error' => $json]);
        }else{
            $data = [
                'sitename' => $Configuracoes->nomedosite,
                'siteemail' => $Configuracoes->email,
                'reply_name' => $request->nome,
                'reply_email' => $request->email,
                'mensagem' => $request->mensagem
            ];

            $retorno = [
                'sitename' => $Configuracoes->nomedosite,
                'siteemail' => $Configuracoes->email,
                'reply_name' => $request->nome,
                'reply_email' => $request->email
            ];
            
            Mail::send(new SendOrcamento($data));
            Mail::send(new OrcamentoRetorno($retorno));
            
            $json = "Obrigado {$request->nome} sua solicitação foi enviada com sucesso!"; 
            return response()->json(['sucess' => $json]);
        }
    }
}
