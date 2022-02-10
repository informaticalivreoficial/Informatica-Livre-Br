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
    Empresa,
    Estados,
    Newsletter,
    Orcamento,
    Pedido,
    Portifolio,
    Produto,
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

    public function politica()
    {
        $head = $this->seo->render('Política de Privacidade - ' . $this->configService->getConfig()->nomedosite ?? 'Informática Livre',
            'Política de Privacidade - ' . $this->configService->getConfig()->nomedosite,
            route('web.politica'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );

        return view('web.politica',[
            'head' => $head
        ]);
    }

    public function portifolio()
    {
        $catProjetos = CatPortifolio::orderBy('created_at', 'DESC')->whereNotNull('id_pai')->available()->get(); 
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
        $projeto->views += 1;
        $projeto->save();
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

    public function orcamento()
    {
        $produtos = Produto::orderBy('valor', 'ASC')
                            ->where('categoria', 4)
                            ->exibir()
                            ->available()
                            ->get();
        $head = $this->seo->render('Soluções para sua empresa - ' . $this->configService->getConfig()->nomedosite,
            'Nossa equipe está pronta para melhor atender as demandas de nossos clientes!',
            route('web.atendimento'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );        

        return view('web.consultoria.produtos', [
            'head' => $head,
            'produtos' => $produtos            
        ]);
    }

    public function formorcamento()
    {        
        $head = $this->seo->render('Orçamento Perdonalizado - ' . $this->configService->getConfig()->nomedosite,
            'Nossa equipe está pronta para melhor atender as demandas de nossos clientes!',
            route('web.atendimento'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );        

        return view('web.consultoria.consultoria', [
            'head' => $head                       
        ]);
    }

    public function formClient($token)
    {
        $estados = Estados::orderBy('estado_nome', 'ASC')->get();
        $orcamento = Orcamento::where('token', $token)->first();
        $head = $this->seo->render('Formulário de Orçamento Perdonalizado - ' . $this->configService->getConfig()->nomedosite,
            'Nossa equipe está pronta para melhor atender as demandas de nossos clientes!',
            route('web.formClient',$token),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.consultoria.formulario-de-retorno',[
            'head' => $head ,
            'orcamento' => $orcamento,
            'estados' => $estados
        ]);
    }

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

    public function artigo(Request $request)
    {
        $post = Post::where('slug', $request->slug)->postson()->first();
        
        $categorias = CatPost::orderBy('titulo', 'ASC')
            ->where('tipo', 'artigo')
            ->get();
        $postsMais = Post::orderBy('views', 'DESC')
            ->where('id', '!=', $post->id)
            ->where('tipo', 'artigo')
            ->limit(4)
            ->postson()
            ->get();
        
        $post->views = $post->views + 1;
        $post->save();

        $head = $this->seo->render($post->titulo ?? 'Informática Livre',
            $post->titulo,
            route('web.blog.artigo', ['slug' => $post->slug]),
            $post->cover() ?? $this->configService->getMetaImg()
        );

        return view('web.blog.artigo', [
            'head' => $head,
            'post' => $post,
            'postsMais' => $postsMais,
            'categorias' => $categorias
        ]);
    }

    public function categoria(Request $request)
    {
        $categoria = CatPost::where('slug', '=', $request->slug)->first();
        $categorias = CatPost::orderBy('titulo', 'ASC')
                    ->where('tipo', 'artigo')
                    ->where('id', '!=', $categoria->id)->get();
        $posts = Post::orderBy('created_at', 'DESC')->where('categoria', '=', $categoria->id)->postson()->paginate(10);
        
        $head = $this->seo->render($categoria->titulo . ' - Blog - ' . $this->configService->getConfig()->nomedosite ?? 'Informática Livre',
            $categoria->titulo . ' - Blog - ' . $this->configService->getConfig()->nomedosite,
            route('web.blog.categoria', ['slug' => $request->slug]),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        
        return view('web.blog.categoria', [
            'head' => $head,
            'posts' => $posts,
            'categoria' => $categoria,
            'categorias' => $categorias
        ]);
    }

    public function pesquisa(Request $request)
    {
        $search = $request->only('search');

        $paginas = Post::where(function($query) use ($request){
            if($request->search){
                $query->orWhere('titulo', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'pagina')->postson();
                $query->orWhere('content', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'pagina')->postson();
            }
        })->postson()->limit(10)->get();

        $artigos = Post::where(function($query) use ($request){
            if($request->search){
                $query->orWhere('titulo', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'artigo')->postson();
                $query->orWhere('content', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'artigo')->postson();
            }
        })->postson()->limit(10)->get();

        $projetos = Portifolio::where(function($query) use ($request){
            if($request->search){
                $query->orWhere('name', 'LIKE', "%{$request->search}%");
                $query->orWhere('content', 'LIKE', "%{$request->search}%");
            }
        })->available()->limit(10)->get();
        
        $head = $this->seo->render('Pesquisa por ' . $request->search ?? 'Informática Livre',
            'Pesquisa - ' . $this->configService->getConfig()->nomedosite,
            route('web.blog.artigos'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        
        return view('web.pesquisa',[
            'head' => $head,
            'paginas' => $paginas,
            'artigos' => $artigos,
            'projetos' => $projetos
        ]);
    }

    public function pagina($slug)
    {
        $projetosCount = Portifolio::count();
        $clientesCount = User::where('client', 1)->count();
        $post = Post::where('slug', $slug)->where('tipo', 'pagina')->postson()->first();        
        $post->views = $post->views + 1;
        $post->save();

        $head = $this->seo->render($post->titulo ?? 'Informática Livre',
            $post->titulo,
            route('web.pagina', ['slug' => $post->slug]),
            $post->cover() ?? $this->configService->getMetaImg()
        );

        return view('web.pagina', [
            'head' => $head,
            'post' => $post,
            'projetosCount' => $projetosCount,
            'clientesCount' => $clientesCount
        ]);
    }
    
    
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
