<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminController,
    BancoController,
    CatPortifolioController,
    UserController,
    EmailController,
    PostController,
    CatPostController,
    ConfigController,
    EmpresaController,
    ProdutoController,
    CatProdutoController,
    NewsletterController,
    NotificationController,
    OrcamentoController,
    ParceiroController,
    PedidoController,
    PortifolioController,
    SitemapController,
    SlideController
};
use App\Http\Controllers\Web\{
    ClienteController,
    RssFeedController,
    SendEmailController,
    WebController
};

Route::group(['namespace' => 'Web', 'as' => 'web.'], function () {
    
    /** Página Inicial */
    Route::get('teste-qrcode', [WebController::class, 'qrcode'])->name('qrcode'); 
    Route::get('/', [WebController::class, 'home'])->name('home');   
    
    Route::get('pagar/{pedido}', [PedidoController::class, 'pagar'])->name('pagar');
    Route::post('notification/pagHiper', [PedidoController::class, 'getTransaction'])->name('getTransaction');

    //****************************** Política de Privacidade ******************************/
    Route::get('/politica-de-privacidade', [WebController::class, 'politica'])->name('politica');
    Route::get('/consultoria/produtos', [WebController::class, 'orcamento'])->name('orcamento');
    Route::get('/consultoria/orcamento', [WebController::class, 'formorcamento'])->name('formorcamento');
    Route::get('/quem-somos', [WebController::class, 'quemsomos'])->name('quemsomos');
    Route::get('consultoria/orcamentos/form-client/{token}', [WebController::class, 'formClient'])->name('formClient');

    //** Página Destaque */
    Route::get('/destaque', 'WebController@spotlight')->name('spotlight');
    
    //** Página Inicial */
    Route::match(['post', 'get'], '/filtro', 'WebController@filter')->name('filter');

    //****************************** Parceiros *********************************************/
    Route::get('/parceiro/{slug}', [WebController::class, 'parceiro'])->name('parceiro');

    //***************************** Cliente ********************************************/
    Route::get('/cliente/login', [ClienteController::class, 'login'])->name('login');
    Route::get('/cliente/minha-fatura/{uuid}', [ClienteController::class, 'fatura'])->name('fatura');
   
    //**************************** Emails ********************************************/
    Route::get('/atendimento', [WebController::class, 'atendimento'])->name('atendimento');
    Route::get('/sendEmail', [SendEmailController::class, 'sendEmail'])->name('sendEmail');
    Route::get('/sendNewsletter', [SendEmailController::class, 'sendNewsletter'])->name('sendNewsletter');
    Route::get('/sendOrcamento', [SendEmailController::class, 'sendOrcamento'])->name('sendOrcamento');
    Route::get('/sendFormCaptacao', [SendEmailController::class, 'sendFormCaptacao'])->name('sendFormCaptacao');    
    
    //****************************** Blog ***********************************************/
    Route::get('/blog/artigo/{slug}', [WebController::class, 'artigo'])->name('blog.artigo');
    Route::get('/blog/categoria/{slug}', [WebController::class, 'categoria'])->name('blog.categoria');
    Route::get('/blog', [WebController::class, 'artigos'])->name('blog.artigos');
    Route::match(['get', 'post'],'/blog/pesquisar', [WebController::class, 'searchBlog'])->name('blog.searchBlog');

    //****************************** Portifólio *******************************************/
    Route::get('/portifolio/{slug}', [WebController::class, 'projeto'])->name('projeto');
    Route::get('/portifolio', [WebController::class, 'portifolio'])->name('portifolio');

    //*************************************** Páginas *******************************************/
    Route::get('/pagina/{slug}', [WebController::class, 'pagina'])->name('pagina');

    //** Pesquisa */
    Route::match(['post', 'get'], '/pesquisa', [WebController::class, 'pesquisa'])->name('pesquisa');

    //** FEED */    
    Route::get('feed', [RssFeedController::class, 'feed'])->name('feed');
    

});

Route::prefix('admin')->middleware('auth')->group( function(){

    //******************************* Newsletter *********************************************/
    Route::match(['post', 'get'], 'listas/padrao', [NewsletterController::class, 'padraoMark'])->name('listas.padrao');
    Route::get('listas/set-status', [NewsletterController::class, 'listaSetStatus'])->name('listas.listaSetStatus');
    Route::get('listas/delete', [NewsletterController::class, 'listaDelete'])->name('listas.delete');
    Route::delete('listas/deleteon', [NewsletterController::class, 'listaDeleteon'])->name('listas.deleteon');
    Route::put('listas/{id}', [NewsletterController::class, 'listasUpdate'])->name('listas.update');
    Route::get('listas/{id}/editar', [NewsletterController::class, 'listasEdit'])->name('listas.edit');
    Route::get('listas/cadastrar', [NewsletterController::class, 'listasCreate'])->name('listas.create');
    Route::post('listas/store', [NewsletterController::class, 'listasStore'])->name('listas.store');
    Route::get('listas', [NewsletterController::class, 'listas'])->name('listas');

    Route::put('listas/email/{id}', [NewsletterController::class, 'newsletterUpdate'])->name('listas.newsletter.update');
    Route::get('listas/email/{id}/edit', [NewsletterController::class, 'newsletterEdit'])->name('listas.newsletter.edit');
    Route::get('listas/email/cadastrar', [NewsletterController::class, 'newsletterCreate'])->name('lista.newsletter.create');
    Route::post('listas/email/store', [NewsletterController::class, 'newsletterStore'])->name('listas.newsletter.store');
    Route::get('listas/emails/categoria/{categoria}', [NewsletterController::class, 'newsletters'])->name('lista.newsletters');

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

    //******************** Bancos *************************************************************/
    Route::get('bancos', [BancoController::class, 'index'])->name('bancos.index');
    Route::get('bancos/refresh', [BancoController::class, 'refresh'])->name('bancos.refresh');
    
    //******************** Vendas *************************************************************/
    Route::get('pedidos/show/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('pedidos/store', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::post('pedidos/store-product', [PedidoController::class, 'storeProduct'])->name('pedidos.storeProduct');
    Route::post('pedidos/store-service', [PedidoController::class, 'storeService'])->name('pedidos.storeService');
    Route::put('pedidos/{id}', [PedidoController::class, 'update'])->name('pedidos.update');
    Route::get('pedidos/{id}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
    Route::get('pedidos', [PedidoController::class, 'index'])->name('pedidos.index'); 
    Route::get('pedidos/sendFormFaturaClient', [PedidoController::class, 'sendFormFaturaClient'])->name('pedidos.sendFormFaturaClient');   
        
    Route::post('itemPedidos/store', [PedidoController::class, 'storeItem'])->name('storeItem.store');

    Route::delete('pedidos/deleteon', [PedidoController::class, 'deleteon'])->name('pedidos.deleteon');
    Route::get('pedidos/delete', [PedidoController::class, 'delete'])->name('pedidos.delete'); 
    Route::get('pedidos/set-status', [PedidoController::class, 'setStatus'])->name('pedidos.setStatus'); 
    Route::get('orcamentos/set-status', [OrcamentoController::class, 'orcamentoSetStatus'])->name('orcamento.orcamentoSetStatus'); 
    Route::delete('orcamentos/deleteon', [OrcamentoController::class, 'deleteon'])->name('orcamento.deleteon');
    Route::get('orcamentos/delete', [OrcamentoController::class, 'delete'])->name('orcamento.delete'); 
    Route::get('orcamentos', [OrcamentoController::class, 'index'])->name('vendas.orcamentos');
    Route::get('/sendFormCaptacaoClient', [OrcamentoController::class, 'sendFormCaptacaoClient'])->name('orcamento.sendFormCaptacaoClient');
    //Route::get('/testecron', [OrcamentoController::class, 'checkOrcamentoClient'])->name('orcamento.checkOrcamentoClient');

    //*************************** Portifólio Categorias **********************************/
    Route::get('portifolio/categorias/delete', [CatPortifolioController::class, 'delete'])->name('portifolio-categorias.delete');
    Route::delete('portifolio/categorias/deleteon', [CatPortifolioController::class, 'deleteon'])->name('portifolio-categorias.deleteon');
    Route::get('portifolio/categorias/{id}/edit', [CatPortifolioController::class, 'edit'])->name('portifolio-categorias.edit');
    Route::put('portifolio/categorias/{id}', [CatPortifolioController::class, 'update'])->name('portifolio-categorias.update');
    Route::match(['post', 'get'],'portifolio/categorias/create/{catpai}', [CatPortifolioController::class, 'create'])->name('portifolio-categorias.create');
    Route::post('portifolio/categorias/store', [CatPortifolioController::class, 'store'])->name('portifolio-categorias.store');
    Route::get('portifolio/categorias', [CatPortifolioController::class, 'index'])->name('catportifolio.index');

    //*************************** Portifólio *******************************************/
    Route::match(['get', 'post'], 'portifolio/pesquisa', [PortifolioController::class, 'search'])->name('portifolio.search');
    Route::get('portifolio/set-status', [PortifolioController::class, 'portifolioSetStatus'])->name('portifolio.portifolioSetStatus');
    Route::post('portifolio/image-set-cover', [PortifolioController::class, 'imageSetCover'])->name('portifolio.imageSetCover');
    Route::delete('portifolio/image-remove', [PortifolioController::class, 'imageRemove'])->name('portifolio.imageRemove');
    Route::delete('portifolio/deleteon', [PortifolioController::class, 'deleteon'])->name('portifolio.deleteon');
    Route::get('portifolio/delete', [PortifolioController::class, 'delete'])->name('portifolio.delete');
    Route::put('portifolio/{id}', [PortifolioController::class, 'update'])->name('portifolio.update');
    Route::get('portifolio/{id}/edit', [PortifolioController::class, 'edit'])->name('portifolio.edit');
    Route::get('portifolio/create', [PortifolioController::class, 'create'])->name('portifolio.create');
    Route::post('portifolio/store', [PortifolioController::class, 'store'])->name('portifolio.store');
    Route::get('portifolio', [PortifolioController::class, 'index'])->name('portifolio.index');

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

    Route::get('/notifications', [NotificationController::class, 'notifications'])->name('notifications');
    Route::put('/notification-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::put('/notification-read', [NotificationController::class, 'markAsRead']);

    Route::get('/', [AdminController::class, 'home'])->name('home');
});

Auth::routes();
