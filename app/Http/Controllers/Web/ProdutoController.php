<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    public function index(): View
    {
        $produtos = Produto::with(['planos' => fn($q) => $q->where('status', true)->orderBy('ordem')])
            ->ativo()
            ->orderBy('ordem')
            ->get();
 
        return view('web.loja.produtos', compact('produtos'));
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
 
        return view('web.loja.produto', compact('produto'));
    }
}
