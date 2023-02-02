<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\Web\FormClientAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\Web\ParceiroSend;
use App\Mail\Web\Atendimento;
use App\Mail\Web\AtendimentoRetorno;
use App\Mail\Web\OrcamentoRetorno;
use App\Mail\Web\ProdutoSend;
use App\Mail\Web\SendOrcamento;
use App\Models\Configuracoes;
use App\Models\Empresa;
use App\Models\Newsletter;
use App\Models\Orcamento;
use App\Models\Parceiro;
use App\Models\Produto;
use App\Models\User;
use Carbon\Carbon;
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
            
            $json = 'Thank you '.\App\Helpers\Renato::getPrimeiroNome($request->nome).', your message has been sent to our <b>'.$parceiro->name.'</b> partner successfully!'; 
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
                'telefone' => $request->telefone,
                'mensagem' => $request->mensagem
            ];

            $retorno = [
                'sitename' => $Configuracoes->nomedosite,
                'siteemail' => $Configuracoes->email,
                'reply_name' => $request->nome,
                'reply_email' => $request->email
            ];

            $orcamentoCreate = [
                'name' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'content' => $request->mensagem,
                'token' => \Illuminate\Support\Str::random(20)
            ];
            $orcamento = Orcamento::create($orcamentoCreate);
            $orcamento->save();
            
            Mail::send(new SendOrcamento($data));
            Mail::send(new OrcamentoRetorno($retorno));
            
            $json = "Obrigado {$request->nome} sua solicitação foi enviada com sucesso!"; 
            return response()->json(['sucess' => $json]);
        }
    }

    public function sendFormCaptacao(Request $request)
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
        if(\App\Helpers\Renato::validaCPF($request->cpf) == false){
            $json = "O campo <strong>CPF</strong> está vazio ou não tem um formato válido!";
            return response()->json(['error' => $json]);
        }else{

            $user = [
                'name' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'cpf' => $request->cpf,
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt($request->cpf),
                'senha' => $request->cpf,
                'remember_token' => Str::random(20),
                'created_at' => Carbon::now(),
                'client' => true,
                'status' => 1,
                'notasadicionais' => $request->notas_adicionais
            ];

            $getUser = User::where('email', $request->email)->first();
            if(!$getUser){
                $userCreate = User::create($user);
                $userCreate->save();
            }             

            if(!empty($request->empresa)){
                $empresa = [
                    'user' => $userCreate->id ?? $getUser->id,
                    'social_name' => $request->empresa,
                    'alias_name' => $request->empresa,
                    'email' => $request->email_empresa,
                    'cep' => $request->cep ?? null,
                    'rua' => $request->rua ?? null,
                    'num' => $request->num ?? null,
                    'bairro' => $request->bairro ?? null,
                    'uf' => $request->uf ?? null,
                    'cidade' => $request->cidade ?? null,
                    'complemento' => $request->complemento ?? null,
                    'telefone' => $request->telefone1 ?? null,
                    'celular' => $request->celular ?? null,
                    'whatsapp' => $request->whatsapp ?? null,
                    'notasadicionais' => $request->notas_adicionais ?? null
                ];
                $empresaCreate = Empresa::create($empresa);
                $empresaCreate->save();
            }

            $data = [
                //Cliente
                'sitename' => $Configuracoes->nomedosite,
                'siteemail' => $Configuracoes->email,
                'reply_name' => $request->nome,
                'reply_email' => $request->email,
                'telefone' => $request->telefone,
                'cpf' => $request->cpf,
                //Empresa
                'empresa' => $request->empresa ?? null,
                'email_empresa' => $request->email_empresa ?? null,
                'telefone_empresa' => $request->telefone1 ?? null,
                'celular' => $request->celular ?? null,
                'whatsapp' => $request->whatsapp ?? null,
                'notasadicionais' => $request->notas_adicionais ?? null
            ];
            
            Mail::send(new FormClientAlert($data));
            
            $json = "Obrigado {$request->nome} suas informações foram enviadas com sucesso!"; 
            return response()->json(['sucess' => $json]);
        }
    }
    
}
