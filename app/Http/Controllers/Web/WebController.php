<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Web\Atendimento;
use App\Mail\Web\AtendimentoRetorno;
use App\Mail\Web\Compra;
use App\Mail\Web\CompraRetorno;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    CatPortifolio,
    Post,
    CatPost,
    Embarcacao,
    Empresa,
    Newsletter,
    Pedido,
    Portifolio,
    Slide,
    User
};
use App\Services\ConfigService;
use App\Support\Seo;
use Carbon\Carbon;

class WebController extends Controller
{
    protected $configService;
    protected $seo;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
        $this->seo = new Seo();
    }

    public function home()
    {
        $artigos = Post::orderBy('created_at', 'DESC')->postson()->limit(3)->get();
        $slides = Slide::orderBy('created_at', 'DESC')->available()->where('expira', '>=', Carbon::now())->get();
        $empresas = Empresa::orderBy('created_at', 'DESC')->available()->get();
        
        $head = $this->seo->render($this->configService->getConfig()->nomedosite ?? 'Informática Livre',
            $this->configService->getConfig()->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.home'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        ); 

		return view('web.home',[
            'head' => $head,
            'artigos' => $artigos,
            'empresas' => $empresas,
            'slides' => $slides
		]);
    }

    public function quemsomos()
    {
        $projetosCount = Portifolio::count();
        $clientesCount = User::where('client', 1)->count();
        $paginaQuemSomos = Post::where('tipo', 'pagina')->postson()->where('id', 5)->first();
        $head = $this->seo->render('Quem Somos - ' . $this->configService->getConfig()->nomedosite,
            $this->configService->getConfig()->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.quemsomos'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.quem-somos',[
            'head' => $head,
            'paginaQuemSomos' => $paginaQuemSomos,
            'projetosCount' => $projetosCount,
            'clientesCount' => $clientesCount
        ]);
    }

    public function portifolio()
    {
        $catProjetos = CatPortifolio::orderBy('created_at', 'DESC')->whereNotNull('id_pai')->available()->get(); 
        //dd($catProjetos);
        $projetos = Portifolio::orderBy('created_at', 'DESC')->available()->exibir()->get(); 
        $head = $this->seo->render('Portifólio - ' . $this->configService->getConfig()->nomedosite,
            'Confira alguns dos projetos desenvolvidos pela Informática Livre',
            route('web.portifolio'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.portifolio',[
            'head' => $head,
            'catProjetos' => $catProjetos,
            'projetos' => $projetos
        ]);
    }

    public function projeto($slug)
    {
        $projeto = Portifolio::where('slug', $slug)->first();
        $head = $this->seo->render($projeto->name,
            $projeto->headline ?? 'Projeto desenvolvido pela Informática Livre',
            route('web.projeto',$projeto->slug),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.projeto',[
            'head' => $head,
            'projeto' => $projeto
        ]);
    }

    // public function artigo(Request $request)
    // {
    //     $Configuracoes = Configuracoes::where('id', '1')->first();
    //     $post = Post::where('slug', $request->slug)->postson()->first();
        
    //     $categorias = CatPost::orderBy('titulo', 'ASC')
    //         ->where('tipo', 'artigo')
    //         ->get();
    //     $postsMais = Post::orderBy('views', 'DESC')->where('id', '!=', $post->id)->limit(3)->postson()->get();
        
    //     $post->views = $post->views + 1;
    //     $post->save();

    //     $head = $this->seo->render($post->titulo . ' - Blog ' . $Configuracoes->nomedosite ?? 'Informática Livre',
    //         $post->titulo,
    //         route('web.blog.artigo', ['slug' => $post->slug]),
    //         $post->cover() ?? Storage::url($Configuracoes->metaimg)
    //     );

    //     return view('web.blog.artigo', [
    //         'head' => $head,
    //         'post' => $post,
    //         'postsMais' => $postsMais,
    //         'categorias' => $categorias
    //     ]);
    // }

    public function artigos()
    {
        $posts = Post::orderBy('created_at', 'DESC')->where('tipo', '=', 'artigo')->postson()->paginate(10);
        $categorias = CatPost::orderBy('titulo', 'ASC')->where('tipo', 'artigo')->get();
        $head = $this->seo->render('Blog - ' . $this->configService->getConfig()->nomedosite ?? 'Informática Livre',
            'Blog - ' . $this->configService->getConfig()->nomedosite,
            route('web.blog.artigos'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.blog.artigos', [
            'head' => $head,
            'posts' => $posts,
            'categorias' => $categorias
        ]);
    }

    // public function categoria(Request $request)
    // {
    //     $Configuracoes = Configuracoes::where('id', '1')->first();
    //     $categoria = CatPost::where('slug', '=', $request->slug)->first();
    //     $categorias = CatPost::orderBy('titulo', 'ASC')
    //                 ->where('tipo', 'artigo')
    //                 ->where('id', '!=', $categoria->id)->get();
    //     $posts = Post::orderBy('created_at', 'DESC')->where('categoria', '=', $categoria->id)->postson()->paginate(15);
    //     $head = $this->seo->render($categoria->titulo . ' - Blog - ' . $Configuracoes->nomedosite ?? 'Informática Livre',
    //         $categoria->titulo . ' - Blog - ' . $Configuracoes->nomedosite,
    //         route('web.blog.categoria', ['slug' => $request->slug]),
    //         Storage::url($Configuracoes->metaimg)
    //     );
        
    //     return view('web.blog.categoria', [
    //         'head' => $head,
    //         'posts' => $posts,
    //         'categoria' => $categoria,
    //         'categorias' => $categorias
    //     ]);
    // }

    // public function searchBlog(Request $request)
    // {
    //     $Configuracoes = Configuracoes::where('id', '1')->first();

    //     $filters = $request->only('filter');

    //     $posts = Post::where(function($query) use ($request){
    //         if($request->filter){
    //             $query->orWhere('titulo', 'LIKE', "%{$request->filter}%");
    //             $query->orWhere('content', 'LIKE', "%{$request->filter}%");
    //         }
    //     })->postson()->paginate(10);

    //     $head = $this->seo->render('Pesquisa por ' . $request->filter ?? 'Informática Livre',
    //         'Blog - ' . $Configuracoes->nomedosite,
    //         route('web.blog.artigos'),
    //         Storage::url($Configuracoes->metaimg)
    //     );
        
    //     return view('web.blog.artigos',[
    //         'head' => $head,
    //         'posts' => $posts,
    //         'filters' => $filters
    //     ]);
    // }
    
    
    public function atendimento()
    {
        $head = $this->seo->render('Atendimento - ' . $this->configService->getConfig()->nomedosite,
            'Nossa equipe está pronta para melhor atender as demandas de nossos clientes!',
            route('web.atendimento'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );        

        return view('web.atendimento', [
            'head' => $head            
        ]);
    }

    // public function sendEmail(Request $request)
    // {
    //     $Configuracoes = Configuracoes::where('id', '1')->first();
    //     if($request->nome == ''){
    //         $json = "Por favor preencha o campo <strong>Nome</strong>";
    //         return response()->json(['error' => $json]);
    //     }
    //     if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
    //         $json = "O campo <strong>Email</strong> está vazio ou não tem um formato válido!";
    //         return response()->json(['error' => $json]);
    //     }
    //     if($request->mensagem == ''){
    //         $json = "Por favor preencha sua <strong>Mensagem</strong>";
    //         return response()->json(['error' => $json]);
    //     }
    //     if(!empty($request->bairro) || !empty($request->cidade)){
    //         $json = "<strong>ERRO</strong> Você está praticando SPAM!"; 
    //         return response()->json(['error' => $json]);
    //     }else{
    //         $data = [
    //             'sitename' => $Configuracoes->nomedosite,
    //             'siteemail' => $Configuracoes->email,
    //             'reply_name' => $request->nome,
    //             'reply_email' => $request->email,
    //             'mensagem' => $request->mensagem
    //         ];

    //         $retorno = [
    //             'sitename' => $Configuracoes->nomedosite,
    //             'siteemail' => $Configuracoes->email,
    //             'reply_name' => $request->nome,
    //             'reply_email' => $request->email
    //         ];
            
    //         Mail::send(new Atendimento($data));
    //         Mail::send(new AtendimentoRetorno($retorno));
            
    //         $json = "Obrigado {$request->nome} sua mensagem foi enviada com sucesso!"; 
    //         return response()->json(['sucess' => $json]);
    //     }
    // }

    

    // public function sendNewsletter(Request $request)
    // {
    //     if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
    //         $json = "O campo <strong>Email</strong> está vazio ou não tem um formato válido!";
    //         return response()->json(['error' => $json]);
    //     }
    //     if(!empty($request->bairro) || !empty($request->cidade)){
    //         $json = "<strong>ERRO</strong> Você está praticando SPAM!"; 
    //         return response()->json(['error' => $json]);
    //     }else{   
    //         $validaNews = Newsletter::where('email', $request->email)->first();            
    //         if(!empty($validaNews)){
    //             Newsletter::where('email', $request->email)->update(['status' => 1]);
    //             $json = "Seu e-mail já está cadastrado!"; 
    //             return response()->json(['sucess' => $json]);
    //         }else{
    //             $NewsletterCreate = Newsletter::create($request->all());
    //             $NewsletterCreate->save();
    //             $json = "Obrigado Cadastrado com sucesso!"; 
    //             return response()->json(['sucess' => $json]);
    //         }            
    //     }
    // }
}
