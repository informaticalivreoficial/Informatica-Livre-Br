<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{
    Portifolio,
    Post
};

class RssFeedController extends Controller
{
    public function feed()
    {
        $posts = Post::orderBy('created_at', 'DESC')->where('tipo', 'artigo')->postson()->limit(10)->get();
        $paginas = Post::orderBy('created_at', 'DESC')->where('tipo', 'pagina')->postson()->limit(10)->get();
        $projetos = Portifolio::orderBy('created_at', 'DESC')->available()->limit(20)->get();

        return response()->view('web.feed', [
            'posts' => $posts,
            'paginas' => $paginas,
            'projetos' => $projetos
        ])->header('Content-Type', 'application/xml');
        
    }
}
