<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CatPostController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\ProdutoController;
use App\Http\Controllers\Admin\CatProdutoController;
use App\Http\Controllers\Admin\ParceiroController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Web\ClienteController;
use App\Http\Controllers\Web\RssFeedController;
use App\Http\Controllers\Web\WebController;

//use Illuminate\Mail\Markdown;

Route::group(['namespace' => 'Web', 'as' => 'web.'], function () {
    
    /** Página Inicial */   
    Route::get('teste-qrcode', [WebController::class, 'qrcode'])->name('qrcode'); 
    Route::get('/', [WebController::class, 'home'])->name('home');     

    //****************************** Política de Privacidade ******************************/
    Route::get('/politica-de-privacidade', [WebController::class, 'politica'])->name('politica');
    Route::get('/consultoria', [WebController::class, 'orcamento'])->name('orcamento');
    Route::get('/quem-somos', [WebController::class, 'quemsomos'])->name('quemsomos');

    //** Página Destaque */
    Route::get('/destaque', 'WebController@spotlight')->name('spotlight');
    
    //** Página Inicial */
    Route::match(['post', 'get'], '/filtro', 'WebController@filter')->name('filter');

    //****************************** Parceiros *********************************************/
    Route::get('/parceiro/{slug}', [WebController::class, 'parceiro'])->name('parceiro');

    //***************************** Cliente ********************************************/
    Route::get('/cliente/login', [ClienteController::class, 'login'])->name('login');
    Route::get('/cliente/meus-passeios', [ClienteController::class, 'passeios'])->name('passeios');
   
    //**************************** Página Inicial ********************************************/
    Route::get('/atendimento', [WebController::class, 'atendimento'])->name('atendimento');
    Route::get('/sendEmail', [WebController::class, 'sendEmail'])->name('sendEmail');
    Route::get('/sendNewsletter', [WebController::class, 'sendNewsletter'])->name('sendNewsletter');
    
    //****************************** Blog ***********************************************/
    Route::get('/blog/artigo/{slug}', [WebController::class, 'artigo'])->name('blog.artigo');
    Route::get('/blog/categoria/{slug}', [WebController::class, 'categoria'])->name('blog.categoria');
    Route::get('/blog', [WebController::class, 'artigos'])->name('blog.artigos');
    Route::match(['get', 'post'],'/blog/pesquisar', [WebController::class, 'searchBlog'])->name('blog.searchBlog');

    //****************************** Notícias *******************************************/
    Route::get('/noticia/{slug}', 'WebController@noticia')->name('noticia');
    Route::get('/noticias', 'WebController@noticias')->name('noticias');

    //****************************** Roteiros *******************************************/
    Route::get('/roteiro/{slug}', [WebController::class, 'roteiro'])->name('roteiro');
    Route::get('/roteiros', [WebController::class, 'roteiros'])->name('roteiros');

    //****************************** Embarcações *******************************************/
    Route::get('/embarcacao/{slug}', [WebController::class, 'embarcacao'])->name('embarcacao');
    Route::get('/embarcacoes', [WebController::class, 'embarcacoes'])->name('embarcacoes');

    ///****************************** Passeios *******************************************/
    Route::get('/passeios/comprar/{passeio}', [WebController::class, 'comprar'])->name('passeios.comprar');
    Route::get('/passeios/carrinhocreate', [WebController::class, 'carrinhocreate'])->name('passeios.carrinhocreate');
    Route::get('/passeios/meu-carrinho', [WebController::class, 'meuCarrinho'])->name('passeios.meucarrinho');
    Route::match(['post', 'get'], '/passeios/{slug}/Payment', [WebController::class, 'paymentsend'])->name('passeios.paymentsend');
    Route::get('/passeios/payment', [WebController::class, 'payment'])->name('passeios.payment');
    Route::get('/passeios/notifications', [WebController::class, 'notifications'])->name('passeios.notifications');
    Route::get('/passeios/voucher/{token}', [WebController::class, 'voucher'])->name('passeios.voucher');

    //****************************** Páginas *******************************************/
    Route::get('/pagina/{slug}', 'WebController@pagina')->name('pagina');
    Route::get('/paginas', 'WebController@paginas')->name('paginas');

    //** Pesquisa */
    Route::match(['post', 'get'], '/pesquisa', 'WebController@pesquisa')->name('pesquisa');

    //** FEED */    
    Route::get('feed', [RssFeedController::class, 'feed'])->name('feed');
    

});

Route::prefix('admin')->middleware('auth')->group( function(){

    //******************* Slides ************************************************/
    Route::get('slides/set-status', [SlideController::class, 'slideSetStatus'])->name('slides.slideSetStatus');
    Route::get('slides/delete', [SlideController::class, 'delete'])->name('slides.delete');
    Route::delete('slides/deleteon', [SlideController::class, 'deleteon'])->name('slides.deleteon');
    Route::put('slides/{slide}', [SlideController::class, 'update'])->name('slides.update');
    Route::get('slides/{slide}/edit', [SlideController::class, 'edit'])->name('slides.edit');
    Route::get('slides/create', [SlideController::class, 'create'])->name('slides.create');
    Route::post('slides/store', [SlideController::class, 'store'])->name('slides.store');
    Route::get('slides', [SlideController::class, 'index'])->name('slides.index');

    //******************** Parceiros *********************************************/
    Route::match(['post', 'get'], 'parceiros/fetchCity', [ParceiroController::class, 'fetchCity'])->name('parceiros.fetchCity');
    Route::get('parceiros/set-status', [ParceiroController::class, 'parceiroSetStatus'])->name('parceiros.parceiroSetStatus');
    Route::post('parceiros/image-set-cover', [ParceiroController::class, 'imageSetCover'])->name('parceiros.imageSetCover');
    Route::delete('parceiros/image-remove', [ParceiroController::class, 'imageRemove'])->name('parceiros.imageRemove');
    Route::delete('parceiros/deleteon', [ParceiroController::class, 'deleteon'])->name('parceiros.deleteon');
    Route::get('parceiros/delete', [ParceiroController::class, 'delete'])->name('parceiros.delete');
    Route::put('parceiros/{id}', [ParceiroController::class, 'update'])->name('parceiros.update');
    Route::get('parceiros/{id}/edit', [ParceiroController::class, 'edit'])->name('parceiros.edit');
    Route::get('parceiros/create', [ParceiroController::class, 'create'])->name('parceiros.create');
    Route::post('parceiros/store', [ParceiroController::class, 'store'])->name('parceiros.store');
    Route::get('parceiros', [ParceiroController::class, 'index'])->name('parceiros.index');
    
    //******************** Pedidos *********************************************/
    Route::get('pedidos/show/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('pedidos', [PedidoController::class, 'index'])->name('pedidos.index');

    //*************************** Produtos Categorias **********************************/
    Route::get('produtos/categorias/delete', [CatProdutoController::class, 'delete'])->name('produtos-categorias.delete');
    Route::delete('produtos/categorias/deleteon', [CatProdutoController::class, 'deleteon'])->name('produtos-categorias.deleteon');
    Route::get('produtos/categorias/{id}/edit', [CatProdutoController::class, 'edit'])->name('produtos-categorias.edit');
    Route::put('produtos/categorias/{id}', [CatProdutoController::class, 'update'])->name('produtos-categorias.update');
    Route::match(['post', 'get'],'produtos/categorias/create/{catpai}', [CatProdutoController::class, 'create'])->name('produtos-categorias.create');
    Route::post('produtos/categorias/store', [CatProdutoController::class, 'store'])->name('produtos-categorias.store');
    Route::get('produtos/categorias', [CatProdutoController::class, 'index'])->name('catprodutos.index');

    //*************************** Produtos *********************************************/
    Route::match(['get', 'post'], 'produtos/pesquisa', [ProdutoController::class, 'search'])->name('produtos.search');
    Route::get('produtos/set-status', [ProdutoController::class, 'produtoSetStatus'])->name('produtos.produtoSetStatus');
    Route::post('produtos/image-set-cover', [ProdutoController::class, 'imageSetCover'])->name('produtos.imageSetCover');
    Route::delete('produtos/image-remove', [ProdutoController::class, 'imageRemove'])->name('produtos.imageRemove');
    Route::delete('produtos/deleteon', [ProdutoController::class, 'deleteon'])->name('produtos.deleteon');
    Route::get('produtos/delete', [ProdutoController::class, 'delete'])->name('produtos.delete');
    Route::put('produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::get('produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
    Route::get('produtos/create', [ProdutoController::class, 'create'])->name('produtos.create');
    Route::post('produtos/store', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::get('produtos', [ProdutoController::class, 'index'])->name('produtos.index');    

    //****************************** Empresas *******************************************/
    Route::match(['post', 'get'], 'empresas/fetchCity', [EmpresaController::class, 'fetchCity'])->name('empresas.fetchCity');
    Route::get('empresas/set-status', [EmpresaController::class, 'empresaSetStatus'])->name('empresas.empresaSetStatus');
    Route::delete('empresas/deleteon', [EmpresaController::class, 'deleteon'])->name('empresas.deleteon');
    Route::get('empresas/delete', [EmpresaController::class, 'delete'])->name('empresas.delete');
    Route::put('empresas/{id}', [EmpresaController::class, 'update'])->name('empresas.update');
    Route::get('empresas/{id}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::get('empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('empresas/store', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');

    //******************** Sitemap *********************************************/
    Route::get('gerarxml', [SitemapController::class, 'gerarxml'])->name('admin.gerarxml');

    //******************** Configurações ***************************************/
    Route::match(['post', 'get'], 'configuracoes/fetchCity', [ConfigController::class, 'fetchCity'])->name('configuracoes.fetchCity');
    Route::put('configuracoes/{config}', [ConfigController::class, 'update'])->name('configuracoes.update');
    Route::get('configuracoes', [ConfigController::class, 'editar'])->name('configuracoes.editar');

    //********************* Categorias para Posts *******************************/
    Route::get('categorias/delete', [CatPostController::class, 'delete'])->name('categorias.delete');
    Route::delete('categorias/deleteon', [CatPostController::class, 'deleteon'])->name('categorias.deleteon');
    Route::put('categorias/posts/{id}', [CatPostController::class, 'update'])->name('categorias.update');
    Route::get('categorias/{id}/edit', [CatPostController::class, 'edit'])->name('categorias.edit');
    Route::match(['post', 'get'],'posts/categorias/create/{catpai}', [CatPostController::class, 'create'])->name('categorias.create');
    Route::post('posts/categorias/store', [CatPostController::class, 'store'])->name('categorias.store');
    Route::get('posts/categorias', [CatPostController::class, 'index'])->name('categorias.index');

    //********************** Blog ************************************************/
    Route::get('posts/set-status', [PostController::class, 'postSetStatus'])->name('posts.postSetStatus');
    Route::get('posts/delete', [PostController::class, 'delete'])->name('posts.delete');
    Route::delete('posts/deleteon', [PostController::class, 'deleteon'])->name('posts.deleteon');
    Route::post('posts/image-set-cover', [PostController::class, 'imageSetCover'])->name('posts.imageSetCover');
    Route::delete('posts/image-remove', [PostController::class, 'imageRemove'])->name('posts.imageRemove');
    Route::put('posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::get('posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts/store', [PostController::class, 'store'])->name('posts.store');
    Route::post('posts/categoriaList', [PostController::class, 'categoriaList'])->name('posts.categoriaList');
    Route::get('posts/artigos', [PostController::class, 'index'])->name('posts.artigos');
    Route::get('posts/noticias', [PostController::class, 'index'])->name('posts.noticias');
    Route::get('posts/paginas', [PostController::class, 'index'])->name('posts.paginas');

    //*********************** Email **********************************************/
    Route::get('email/suporte', [EmailController::class, 'suporte'])->name('email.suporte');
    Route::match(['post', 'get'], 'email/enviar-email', [EmailController::class, 'send'])->name('email.send');
    Route::post('email/sendEmail', [EmailController::class, 'sendEmail'])->name('email.sendEmail');
    Route::match(['post', 'get'], 'email/success', [EmailController::class, 'success'])->name('email.success');

    //*********************** Usuários *******************************************/
    Route::match(['get', 'post'], 'usuarios/pesquisa', [UserController::class, 'search'])->name('users.search');
    Route::match(['post', 'get'], 'usuarios/fetchCity', [UserController::class, 'fetchCity'])->name('users.fetchCity');
    Route::delete('usuarios/deleteon', [UserController::class, 'deleteon'])->name('users.deleteon');
    Route::get('usuarios/set-status', [UserController::class, 'userSetStatus'])->name('users.userSetStatus');
    Route::get('usuarios/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::get('usuarios/time', [UserController::class, 'team'])->name('users.team');
    Route::get('usuarios/view/{id}', [UserController::class, 'show'])->name('users.view');
    Route::put('usuarios/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('usuarios/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('usuarios/create', [UserController::class, 'create'])->name('users.create');
    Route::post('usuarios/store', [UserController::class, 'store'])->name('users.store');
    Route::get('usuarios', [UserController::class, 'index'])->name('users.index');

    Route::get('/', [AdminController::class, 'home'])->name('home');
});

// Route::get('mail', function () {
//     $markdown = new Markdown(view(), config('mail.markdown'));
//     return $markdown->render('emails.compra-retorno');
// });


Auth::routes();
