<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CatPortifolio;
use App\Models\Company;
use App\Models\Portifolio;
use App\Models\Post;
use App\Models\Slide;
use App\Support\Seo;
use App\Models\Config;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    protected $seo, $config;

    public function __construct()
    {
        $this->seo = new Seo();
        $this->config = Config::where('id', 1)->first();
    }

    public function home()
    {
        $slides    = Slide::available()->orderBy('created_at', 'desc')->get();
        $clientes  = Company::available()->orderBy('alias_name')->get();
        $trabalhos = Portifolio::with(['images', 'categoryRelation'])
            ->active()
            ->public()
            ->latest()
            ->take(9)
            ->get();
        $posts = Post::postson()->latest()->take(3)->get();

        $head = $this->seo->render($this->config->app_name ?? env('APP_NAME'),
            $this->config->information ?? env('APP_NAME'),
            route('web.home'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.home', [
            'head' => $head,
            'slides' => $slides,
            'clientes' => $clientes,
            'trabalhos' => $trabalhos,
            'posts' => $posts
        ]);
    }

    public function portifolio(Request $request)
    {
        $categorias = CatPortifolio::with(['children' => function($q) {
            $q->whereHas('portifolios', fn($q) => $q->active()->public());
        }])
        ->whereNull('id_pai')
        ->active()
        ->whereHas('children.portifolios', fn($q) => $q->active()->public())
        ->get();

        $trabalhos = Portifolio::with(['images', 'categoryRelation'])
            ->active()
            ->public()
            ->when($request->categoria, fn($q) => $q->where('category', $request->categoria))
            ->latest()
            ->paginate(21);

        $head = $this->seo->render('Nossos Trabalhos - ' . $this->config->app_name ?? env('APP_NAME'),
            'Veja nossos trabalhos e saiba mais sobre nossos clientes.',
            route('web.portifolio'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.portifolio', [
            'head' => $head,
            'trabalhos' => $trabalhos,
            'categorias' => $categorias
        ]);
    }

    public function portifolioSingle($slug)
    {
        $trabalho = Portifolio::with(['images', 'company', 'categoryRelation'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Incrementa views
        $trabalho->increment('views');

        $head = $this->seo->render('Projeto - ' . $trabalho->name . ' - ' . $this->config->app_name ?? env('APP_NAME'),
            'Projeto desenvolvido pela Informática Livre - ' . $trabalho->name,
            route('web.portifolio.single', $trabalho->slug),
            $trabalho->cover() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.projeto', [
            'head' => $head,
            'trabalho' => $trabalho
        ]);
    }

    public function blog(Request $request)
    {
        $posts = Post::postson()
            ->when($request->busca, fn($q) => $q->where('title', 'like', "%{$request->busca}%"))
            ->latest()
            ->paginate(12);

        return view('web.blog.artigos', compact('posts'));
    }

    public function blogSingle($slug)
    {
        $post = Post::where('slug', $slug)->postson()->firstOrFail();
        $post->increment('views');

        $recentes = Post::postson()
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(4)
            ->get();

        $head = $this->seo->render($post->title  . ' - ' . $this->config->app_name ?? env('APP_NAME'),
            $post->title . ' - ' . $this->config->app_name ?? env('APP_NAME'),
            route('web.blog.artigo', $post->slug),
            $post->cover() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.blog.artigo', [
            'head' => $head,
            'post' => $post,
            'recentes' => $recentes
        ]);
    }

    public function blogCategoria($slug)
    {
        $categoria = \App\Models\CatPost::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $posts = Post::postson()
            ->where('category', $categoria->id)
            ->latest()
            ->paginate(12);

        return view('web.blog-categoria', compact('posts', 'categoria'));
    }

    public function contact()
    {
        $head = $this->seo->render('Atendimento - ' . $this->config->app_name ?? env('APP_NAME'),
            'Entre em contato conosco, teremos prazer em atendê-lo!',
            route('web.contact'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        return view('web.atendimento', [
            'head' => $head,
        ]);
    }

    public function privacy()
    {
        $head = $this->seo->render('Política de Privacidade - ' . $this->config->app_name ?? env('APP_NAME'),
            'Leia nossa política de privacidade e saiba como protegemos seus dados.',
            route('web.privacy'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        if(empty($this->config->privacy_policy)){
            return redirect()->route('web.home');
        }

        return view("web.privacy",[
            'head' => $head,
        ]);
    }
    
    public function terms()
    {
        $head = $this->seo->render('Termos e Condições - ' . $this->config->app_name ?? env('APP_NAME'),
            'Leia nossos termos e condições e saiba como seus direitos sejam respeitados.',
            route('web.terms'),
            $this->config->getmetaimg() ?? url(asset('theme/images/image.jpg'))
        );

        if(empty($this->config->terms_condicions)){
            return redirect()->route('web.home');
        }

        return view("web.terms-conditions",[
            'head' => $head,
        ]);
    }
}
