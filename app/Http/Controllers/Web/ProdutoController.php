<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    public function index(): View
    {
        $produtos = Produto::with([
                'images',
                'planos' => fn($q) => $q->where('status', true)->orderBy('ordem'),
            ])
            ->ativo()
            ->orderBy('ordem')
            ->get();

        $head = $this->seo->render(
            'Sistemas - ' . config('app.name'),
            'Sistemas prontos para uso, desenvolvidos com tecnologia moderna e instalados por nossa equipe.',
            route('web.produtos'),
            $produtos->first()?->cover_url ?? asset('theme/images/image.jpg')
        );

        return view('web.loja.produtos', compact('produtos', 'head'));
    }

    public function show(string $slug): View
    {
        $produto = Produto::with([
            'images',
            'planos' => fn($q) => $q->where('status', true)->orderBy('ordem')
        ])
        ->ativo()
        ->where('slug', $slug)
        ->firstOrFail();

        $descricao = $produto->headline
            ?: $produto->descricao
            ?: Str::limit(strip_tags((string) $produto->conteudo), 160)
            ?: 'Sistema pronto para uso, desenvolvido e instalado pela ' . config('app.name');

        $head = $this->seo->render(
            $produto->nome . ' - ' . config('app.name'),
            Str::limit(strip_tags($descricao), 200),
            route('web.produto', $produto->slug),
            $produto->cover_url
        );

        return view('web.loja.produto', compact('produto', 'head'));
    }
}
