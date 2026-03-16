<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Portifolio;
use App\Models\Post;
use App\Models\Slide;
use Illuminate\Http\Request;

class SiteController extends Controller
{
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

        return view('web.home', compact('slides', 'clientes', 'trabalhos', 'posts'));
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
}
