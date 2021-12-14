<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Web\Atendimento;
use App\Mail\Web\AtendimentoRetorno;
use App\Mail\Web\Compra;
use App\Mail\Web\CompraRetorno;
use App\Models\Configuracoes;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Post,
    CatPost,
    Embarcacao,
    MPException,
    Newsletter,
    Roteiro,
    Passeio,
    Pedido,
    Slide,
    User
};
use Illuminate\Support\Facades\Hash;
use MercadoPago;
use Carbon\Carbon;

class WebController extends Controller
{
    public function home()
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $artigos = Post::orderBy('created_at', 'DESC')->postson()->limit(3)->get();
        $passeios = Passeio::orderBy('created_at', 'DESC')->available()->venda()->limit(6)->get();
        $roteiros = Roteiro::orderBy('created_at', 'DESC')->available()->get();
        $slides = Slide::orderBy('created_at', 'DESC')->available()->where('expira', '>=', Carbon::now())->get();
        $head = $this->seo->render($Configuracoes->nomedosite ?? 'Informática Livre',
            $Configuracoes->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.home'),
            Storage::url($Configuracoes->metaimg ?? 'https://informaticalivre.com/media/metaimg.jpg')
        ); 

		return view('web.home',[
            'head' => $head,
            'artigos' => $artigos,
            'passeios' => $passeios,
            'roteiros' => $roteiros,
            'slides' => $slides
		]);
    }

    public function reservarRoteiro(Request $request)
    {
        $roteiro = Roteiro::where('slug', $request->slug)->available()->first();
        $json = ([
            'redirect' => route('web.roteiro',['slug' => $roteiro->slug])
        ]);
        return response()->json($json);
    }

    public function artigo(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $post = Post::where('slug', $request->slug)->postson()->first();
        
        $categorias = CatPost::orderBy('titulo', 'ASC')
            ->where('tipo', 'artigo')
            ->get();
        $postsMais = Post::orderBy('views', 'DESC')->where('id', '!=', $post->id)->limit(3)->postson()->get();
        
        $post->views = $post->views + 1;
        $post->save();

        $head = $this->seo->render($post->titulo . ' - Blog ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            $post->titulo,
            route('web.blog.artigo', ['slug' => $post->slug]),
            $post->cover() ?? Storage::url($Configuracoes->metaimg)
        );

        return view('web.blog.artigo', [
            'head' => $head,
            'post' => $post,
            'postsMais' => $postsMais,
            'categorias' => $categorias
        ]);
    }

    public function artigos()
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $posts = Post::orderBy('created_at', 'DESC')->where('tipo', '=', 'artigo')->postson()->paginate(10);
        $categorias = CatPost::orderBy('titulo', 'ASC')->where('tipo', 'artigo')->get();
        $head = $this->seo->render('Blog - ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            'Blog - ' . $Configuracoes->nomedosite,
            route('web.blog.artigos'),
            Storage::url($Configuracoes->metaimg)
        );
        return view('web.blog.artigos', [
            'head' => $head,
            'posts' => $posts,
            'categorias' => $categorias
        ]);
    }

    public function categoria(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $categoria = CatPost::where('slug', '=', $request->slug)->first();
        $categorias = CatPost::orderBy('titulo', 'ASC')
                    ->where('tipo', 'artigo')
                    ->where('id', '!=', $categoria->id)->get();
        $posts = Post::orderBy('created_at', 'DESC')->where('categoria', '=', $categoria->id)->postson()->paginate(15);
        $head = $this->seo->render($categoria->titulo . ' - Blog - ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            $categoria->titulo . ' - Blog - ' . $Configuracoes->nomedosite,
            route('web.blog.categoria', ['slug' => $request->slug]),
            Storage::url($Configuracoes->metaimg)
        );
        
        return view('web.blog.categoria', [
            'head' => $head,
            'posts' => $posts,
            'categoria' => $categoria,
            'categorias' => $categorias
        ]);
    }

    public function searchBlog(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();

        $filters = $request->only('filter');

        $posts = Post::where(function($query) use ($request){
            if($request->filter){
                $query->orWhere('titulo', 'LIKE', "%{$request->filter}%");
                $query->orWhere('content', 'LIKE', "%{$request->filter}%");
            }
        })->postson()->paginate(10);

        $head = $this->seo->render('Pesquisa por ' . $request->filter ?? 'Informática Livre',
            'Blog - ' . $Configuracoes->nomedosite,
            route('web.blog.artigos'),
            Storage::url($Configuracoes->metaimg)
        );
        
        return view('web.blog.artigos',[
            'head' => $head,
            'posts' => $posts,
            'filters' => $filters
        ]);
    }

    public function roteiros()
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $roteiros = Roteiro::orderBy('created_at', 'DESC')->available()->paginate(9);

        $head = $this->seo->render('Roteiros - ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            'Roteiros - ' . $Configuracoes->nomedosite,
            route('web.roteiros'),
            Storage::url($Configuracoes->metaimg)
        );
        return view('web.roteiros', [
            'head' => $head,
            'roteiros' => $roteiros
        ]);
    }

    public function roteiro(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $roteiro = Roteiro::where('slug', $request->slug)->available()->first(); 
        
        $roteiro->views = $roteiro->views + 1;
        $roteiro->save();

        $passeios = Passeio::where('roteiro_id', $roteiro->id)->get();
        $pedidos = Pedido::all();

        $head = $this->seo->render($roteiro->name . ' - Roteiros ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            $roteiro->name . ' - Roteiros ' . $Configuracoes->nomedosite,
            route('web.roteiro', ['slug' => $roteiro->slug]),
            $roteiro->cover() ?? Storage::url($Configuracoes->metaimg)
        );

        return view('web.roteiro', [
            'head' => $head,
            'roteiro' => $roteiro,
            'passeios' => $passeios,
            'pedidos' => $pedidos
        ]);
    }

    public function comprar(Request $request, $passeio)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $passeio = Passeio::where('id', $passeio)->available()->first();

        $head = $this->seo->render('Comprar passeio  ' . $passeio->roteiro->name ?? 'Informática Livre',
            'Comprar passeio  ' . $passeio->roteiro->name,
            route('web.passeios.comprar', ['passeio' => $passeio->id]),
            $passeio->roteiro->cover() ?? Storage::url($Configuracoes->metaimg)
        );

        return view('web.passeios.comprar', [
            'head' => $head,
            'passeio' => $passeio
        ]);
    }

    public function carrinhocreate(Request $request)
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
        if(validaCPF($request->cpf) != true){
            $json = "O campo <strong>CPF</strong> está vazio ou não tem um formato válido!";
            return response()->json(['error' => $json]);
        }
        if($request->celular == ''){
            $json = "Por favor preencha o campo <strong>Celular</strong>";
            return response()->json(['error' => $json]);
        }
        if($request->datapasseio == ''){
            $json = "Por favor selecione uma <strong>Data</strong> para seu passeio!";
            return response()->json(['error' => $json]);
        }

        if($request->session()->get('cart')){
            $request->session()->pull('cart', []);
        }

        $request->session()->put('cart', [            
            'product_id'      => Hash::make($request->email),
            'id_passeio'      => $request->id_passeio,
            'cliente'         => $request->nome,
            'cliente_celular' => $request->celular,
            'email'           => $request->email,
            'cpf'             => $request->cpf,
            'qtd_adultos'     => $request->adultos,
            'qtd_zerocinco'   => $request->valor_v_zerocinco,
            'qtd_seisdoze'    => $request->valor_v_seisdoze,
            'data_passeio'    => $request->datapasseio,            
        ]);        

        return response()->json([
            'redirect' => route('web.passeios.meucarrinho')
        ]);
    }

    public function meuCarrinho(Request $request)
    {        
        if(!$request->session()->has('cart')){
            return redirect()->route('web.home');
        }

        $Configuracoes = Configuracoes::where('id', '1')->first();
        $cart = $request->session()->get('cart');
        $passeio = Passeio::where('id', $cart['id_passeio'])->first();
        $head = $this->seo->render('Meu Carrinho',
            'Meu Carrinho de Compras',
            route('web.passeios.meucarrinho'),
            Storage::url($Configuracoes->metaimg ?? 'https://informaticalivre.com/media/metaimg.jpg')
        ); 

        return view('web.passeios.carrinho', [
            'head' => $head,
            'passeio' => $passeio            
        ]);
    }

    public function paymentsend(Request $request, $slug)
    {
        //dd($request->all());
        $Configuracoes = Configuracoes::where('id', '1')->first();
        
        MercadoPago\SDK::setAccessToken("TEST-487731692769092-092202-4831466b259a6d94e0f78edb65df113d-192815433");
                
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = (float)$request->transactionAmount;
        $payment->token = $request->token;
        $payment->description = $request->description;
        $payment->installments = (int)$request->installments;
        $payment->payment_method_id = $request->paymentMethodId;
        $payment->issuer_id = (int)$request->issuer;

        $payer = new MercadoPago\Payer();
        $payer->email = $request->email;
        $payer->identification = array(
            "type" => $request->docType,
            "number" => $request->docNumber
        );

        $payment->payer = $payer;
        $payment->save();

        $error = new MPException($payment);
        
        if($error->verifyTransaction()['class'] == 'success' || $error->verifyTransaction()['class'] == 'pending_contingency' || $error->verifyTransaction()['class'] == 'pending_review_manual'){
            $user = User::where('email', $request->email)->first();
           
            if(empty($user)){   
                //Cria Cliente
                $cliente = [
                    'name' => $request->name,
                    'cpf' => $request->docNumber,
                    'email' => $request->email,
                    'status' => 1,
                    'celular' => $request->celular,
                    'client' => 'on',
                    'password' => bcrypt($request->name.'0981'),
                    'remember_token' => \Illuminate\Support\Str::random(10),                    
                ];
                $clienteCreate = User::create($cliente);
                $clienteCreate->save();

                //Cria Pedido
                $pedido = [
                    'passeio_id' => $request->id_passeio,
                    'token' => \Illuminate\Support\Str::random(30),
                    'user_id' => $clienteCreate->id,
                    'id_gateway' => $payment->id,
                    'data_compra' => $request->data_compra,
                    'status' => 1,
                    'status_gateway' => $payment->status,
                    'valor' => $request->transactionAmount,
                    'description' => $request->description . '<br> 
                        Adutos: ' . $request->qtd_adultos . ' - ('.($request->qtd_adultos * $request->valor_adulto).')'.'<br>
                        Crianças de 0 a 5 anos: ' . $request->qtd_zerocinco . ' - ('.($request->qtd_zerocinco * substr(str_replace('.', '', str_replace(',', '.', $request->valorCri05)), 0, -2)).')'.'<br>
                        Crianças de 6 a 12 anos: ' . $request->qtd_seisdoze . ' - ('.($request->qtd_seisdoze * substr(str_replace('.', '', str_replace(',', '.', $request->valorCri06)), 0, -2)).')',
                    'total_passageiros' =>  $request->qtd_adultos + $request->qtd_zerocinco + $request->qtd_seisdoze   
                ];
                
                $pedidoCreate = Pedido::create($pedido);
                $pedidoCreate->save();
            }else{         
                
                //Cria Pedido
                $pedido = [
                    'passeio_id' => $request->id_passeio,
                    'token' => \Illuminate\Support\Str::random(30),
                    'user_id' => $user->id,
                    'id_gateway' => $payment->id,
                    'data_compra' => $request->data_compra,
                    'status' => 1,
                    'status_gateway' => $payment->status,
                    'valor' => $request->transactionAmount,
                    'description' => $request->description . '<br> 
                        Adutos: ' . $request->qtd_adultos . ' - ('.($request->qtd_adultos * $request->valor_adulto).')'.'<br>
                        Crianças de 0 a 5 anos: ' . $request->qtd_zerocinco . ' - ('.($request->qtd_zerocinco * substr(str_replace('.', '', str_replace(',', '.', $request->valorCri05)), 0, -2)).')'.'<br>
                        Crianças de 6 a 12 anos: ' . $request->qtd_seisdoze . ' - ('.($request->qtd_seisdoze * substr(str_replace('.', '', str_replace(',', '.', $request->valorCri06)), 0, -2)).')',
                    'total_passageiros' =>  $request->qtd_adultos + $request->qtd_zerocinco + $request->qtd_seisdoze   
                ];

                $pedidoCreate = Pedido::create($pedido);
                $pedidoCreate->save();

                //Cliente para o email
                $cliente = [
                    'name' => $user->name,
                    'cpf' => $request->docNumber,
                    'email' => $request->email,
                    'celular' => $request->celular                    
                ];
            }

            $data = [
                'status' => $payment->status,
                'passeio' => $request->description,
                'token' => $pedidoCreate->token,
                'data_passeio' => $request->data_compra,
                'qtd_adultos' => $request->qtd_adultos,
                'qtd_zerocinco' => $request->qtd_zerocinco,
                'qtd_seisdoze' => $request->qtd_seisdoze,
                'total_passageiros' =>  $request->qtd_adultos + $request->qtd_zerocinco + $request->qtd_seisdoze,
                'valor_adulto' => number_format(($request->valor_adulto * $request->qtd_adultos), 2, ',', '.'),
                'valorCri05' => $request->valorCri05,
                'valorCri06' => $request->valorCri06,
                'total' => number_format(($request->transactionAmount), 2, ',', '.'),
                'sitename' => $Configuracoes->nomedosite,
                'siteemail' => $Configuracoes->email,
            ];

            $request->session()->forget('cart');

            Mail::send(new Compra($data, $cliente));
            Mail::send(new CompraRetorno($data, $cliente));
        }

        return redirect()->route('web.passeios.payment')->with([
            'color' => $error->verifyTransaction()['class'], 'message' => $error->verifyTransaction()['message']
        ]);        
    }

    public function payment(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $head = $this->seo->render('Comprar passeio',
            'Comprar passeio',
            route('web.passeios.payment'),
            Storage::url($Configuracoes->metaimg ?? 'https://informaticalivre.com/media/metaimg.jpg')
        );
        
        return view('web.passeios.payment', [
            'head' => $head
        ]);
    }

    public function voucher($token)
    {        
        $pedido = Pedido::where('token', $token)->first();
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $head = $this->seo->render('Voucher',
            'Voucher',
            route('web.passeios.voucher', ['token' => $pedido->token]),
            Storage::url($Configuracoes->metaimg ?? 'https://informaticalivre.com/media/metaimg.jpg')
        );

        return view('web.passeios.faturaprint', [
            'head' => $head,
            'pedido' => $pedido
        ]);
    }
    
    public function atendimento()
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $head = $this->seo->render('Atendimento',
            'Nossa equipe está pronta para melhor atender as demandas de nossos clientes!',
            route('web.atendimento'),
            Storage::url($Configuracoes->metaimg ?? 'https://informaticalivre.com/media/metaimg.jpg')
        );        

        return view('web.atendimento', [
            'head' => $head,
            'Configuracoes' => $Configuracoes            
        ]);
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

    public function embarcacoes()
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $embarcacoes = Embarcacao::orderBy('created_at', 'DESC')->available()->paginate(9);
        $head = $this->seo->render('Embarcações - ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            'Embarcações - ' . $Configuracoes->nomedosite,
            route('web.embarcacoes'),
            Storage::url($Configuracoes->metaimg)
        );
        return view('web.embarcacoes.embarcacoes',[
            'head' => $head,
            'embarcacoes' => $embarcacoes
        ]);
    }

    public function embarcacao(Request $request)
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $embarcacao = Embarcacao::where('slug', $request->slug)->first();
        $head = $this->seo->render('Embarcação - ' . $embarcacao->name . ' - ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            'Embarcação - ' . $embarcacao->name . ' - ' . $Configuracoes->nomedosite,
            route('web.embarcacao', ['slug' => $embarcacao->slug]),
            Storage::url($Configuracoes->metaimg)
        );
        return view('web.embarcacoes.embarcacao',[
            'head' => $head,
            'embarcacao' => $embarcacao
        ]);
    }

    public function sendNewsletter(Request $request)
    {
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $json = "O campo <strong>Email</strong> está vazio ou não tem um formato válido!";
            return response()->json(['error' => $json]);
        }
        if(!empty($request->bairro) || !empty($request->cidade)){
            $json = "<strong>ERRO</strong> Você está praticando SPAM!"; 
            return response()->json(['error' => $json]);
        }else{   
            $validaNews = Newsletter::where('email', $request->email)->first();            
            if(!empty($validaNews)){
                Newsletter::where('email', $request->email)->update(['status' => 1]);
                $json = "Seu e-mail já está cadastrado!"; 
                return response()->json(['sucess' => $json]);
            }else{
                $NewsletterCreate = Newsletter::create($request->all());
                $NewsletterCreate->save();
                $json = "Obrigado Cadastrado com sucesso!"; 
                return response()->json(['sucess' => $json]);
            }            
        }
    }
}
