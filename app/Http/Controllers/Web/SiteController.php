<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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
        $trabalhos = Portifolio::with(['cover', 'categoryRelation'])
            ->active()
            ->public()
            ->latest()
            ->take(6)
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
        $categorias = CatPortifolio::with('children')->whereNull('id_pai')->active()->get();

        $trabalhos = Portifolio::with(['cover', 'categoryRelation'])
            ->active()
            ->public()
            ->when($request->categoria, fn($q) => $q->where('category', $request->categoria))
            ->latest()
            ->paginate(12);

        return view('web.portifolio', compact('trabalhos', 'categorias'));
    }

    public function portifolioSingle($slug)
    {
        $trabalho = Portifolio::with(['images', 'company', 'categoryRelation'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Incrementa views
        $trabalho->increment('views');

        return view('web.portifolio-single', compact('trabalho'));
    }

    public function blog(Request $request)
    {
        $posts = Post::active()
            ->when($request->busca, fn($q) => $q->where('title', 'like', "%{$request->busca}%"))
            ->latest()
            ->paginate(12);

        return view('web.blog', compact('posts'));
    }

    public function blogSingle($slug)
    {
        $post = Post::where('slug', $slug)->active()->firstOrFail();
        $post->increment('views');

        return view('web.blog-single', compact('post'));
    }

    public function blogCategoria($slug)
    {
        $categoria = \App\Models\CatPost::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $posts = Post::active()
            ->where('category', $categoria->id)
            ->latest()
            ->paginate(12);

        return view('web.blog-categoria', compact('posts', 'categoria'));
    }

    public function contato()
    {
        return view('web.contato');
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
