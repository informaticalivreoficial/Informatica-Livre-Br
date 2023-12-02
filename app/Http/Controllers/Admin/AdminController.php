<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Analytics;
use Spatie\Analytics\Period;
use App\Models\{
    CatPost,
    Empresa,
    Fatura,
    Orcamento,
    User,
    Post,
    Produto
};

class AdminController extends Controller
{
    public function home()
    {
        //Users
        $time = User::where('admin', 1)->orWhere('editor', 1)->count();
        $usersAvailable = User::where('client', 1)->available()->count();
        $usersUnavailable = User::where('client', 1)->unavailable()->count();
        //Artigos
        $postsArtigos = Post::where('tipo', 'artigo')->count();
        $postsPaginas = Post::where('tipo', 'pagina')->count();
        $artigosTop = Post::orderBy('views', 'DESC')
                ->where('tipo', 'artigo')
                ->limit(6)
                ->postson()   
                ->get();
        $totalViewsArtigos = Post::orderBy('views', 'DESC')
                ->where('tipo', 'artigo')
                ->postson()
                ->limit(6)
                ->get()
                ->sum('views');
        $paginasTop = Post::orderBy('views', 'DESC')
                ->where('tipo', 'pagina')
                ->limit(6)
                ->postson()   
                ->get();
        $totalViewsPaginas = Post::orderBy('views', 'DESC')
                ->where('tipo', 'pagina')
                ->postson()
                ->limit(6)
                ->get()
                ->sum('views');

          
        //Orçamentos
        $orcamentosPendentes = Orcamento::available()->count();   
        $orcamentosConcluidos = Orcamento::unavailable()->count();   
        //Produtos
        $produtosAvailable = Produto::available()->count();
        $produtosUnavailable = Produto::unavailable()->count();
        $produtosTotal = Produto::all()->count();
        //Empresas
        $empresasAvailable = Empresa::available()->count();
        $empresasUnavailable = Empresa::unavailable()->count();
        $empresasTotal = Empresa::all()->count();
        //Faturas
        $faturasApproved = Fatura::approved()->count();
        $faturasInprocess = Fatura::inprocess()->count();
        $faturasRejected = Fatura::rejected()->count();

        //Analitcs
        $visitasHoje = Analytics::fetchMostVisitedPages(Period::days(1));
        
        $visitas365 = Analytics::fetchTotalVisitorsAndPageViews(Period::months(5));
        
        $top_browser = Analytics::fetchTopBrowsers(Period::months(5), 10);

        $analyticsData = Analytics::get(
                \Spatie\Analytics\Period::months(6), 
                metrics: ['totalUsers', 'sessions', 'screenPageViews'], 
                dimensions: ['month'],
                //orderBy: [OrderBy::metric('data', true)],
        );   
         
        return view('admin.dashboard',[
            'time' => $time,
            'usersAvailable' => $usersAvailable,
            'usersUnavailable' => $usersUnavailable,
            //Artigos
            'artigosTop' => $artigosTop,
            'artigostotalviews' => $totalViewsArtigos,
            //Páginas
            'paginasTop' => $paginasTop,
            'paginastotalviews' => $totalViewsPaginas, 
            //CHART PIZZA
            'postsArtigos' => $postsArtigos,
            'postsPaginas' => $postsPaginas,         
            //Orçamentos
            'orcamentosPendentes' => $orcamentosPendentes,
            'orcamentosConcluidos' => $orcamentosConcluidos,
            //Produtos
            'produtosAvailable' => $produtosAvailable,
            'produtosUnavailable' => $produtosUnavailable,
            'produtosTotal' => $produtosTotal,
            //Empresas
            'empresasAvailable' => $empresasAvailable,
            'empresasUnavailable' => $empresasUnavailable,
            'empresasTotal' => $empresasTotal,
            //Faturas
            'faturasApproved' => $faturasApproved,
            'faturasInprocess' => $faturasInprocess,
            'faturasRejected' => $faturasRejected,
            //Analytics
            'visitasHoje' => $visitasHoje,
            //'visitas365' => $visitas365,
            'analyticsData' => $analyticsData,
            'top_browser' => $top_browser
        ]);
    }
}
