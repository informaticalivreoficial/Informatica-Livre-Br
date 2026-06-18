<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\{
    Settings,
    Dashboard,
    NotificationsList
};
use App\Livewire\Dashboard\Users\{
    Time,
    Users,
    ViewUser,
    Form,
};
use App\Livewire\Dashboard\Posts\CatPosts;
use App\Livewire\Dashboard\Posts\PostForm;
use App\Livewire\Dashboard\Posts\Posts;
use App\Livewire\Dashboard\Slides\SlideForm;
use App\Livewire\Dashboard\Slides\Slides;
use App\Http\Controllers\Web\{
    ClienteController,
    ProdutoController,
    SiteController,
    WebController
};
use App\Livewire\Dashboard\Permissions\Index as PermissionIndex;
use App\Livewire\Dashboard\Roles\Index as RoleIndex;
use App\Livewire\Dashboard\Companies\Companies;
use App\Livewire\Dashboard\Companies\CompanyForm;
use App\Livewire\Dashboard\Invoices\InvoicesIndex;
use App\Livewire\Dashboard\Invoices\InvoicesShow;
use App\Livewire\Dashboard\Portifolio\PortifolioCategories;
use App\Livewire\Dashboard\Portifolio\PortifolioForm;
use App\Livewire\Dashboard\Portifolio\PortifolioIndex;
use App\Livewire\Dashboard\Reports\InvoiceReport;
use App\Livewire\Dashboard\Reports\ReportIndex;
use App\Livewire\Dashboard\Reports\SubscriptionReport;
use App\Livewire\Dashboard\Safe\Safe;
use App\Livewire\Dashboard\Safe\SafeForm;
use App\Livewire\Dashboard\Service\InvoiceIndex;
use App\Livewire\Dashboard\Service\ServiceForm;
use App\Livewire\Dashboard\Service\ServiceIndex;
use App\Livewire\Dashboard\Service\SubscriptionForm;
use App\Livewire\Dashboard\Service\SubscriptionIndex;
use App\Livewire\Dashboard\Service\SubscriptionShow;
use App\Livewire\Dashboard\Sitemap\SitemapGenerator;

// Route::get('/test-notification/{status}', function ($status) {

//     $admin = \App\Models\User::role('super-admin')->first();

//     $invoice = \App\Models\Invoice::latest()->first();

//     $admin->notify(
//         new \App\Notifications\InvoiceStatusNotification($invoice, $status)
//     );

//     return "Notificação {$status} enviada com sucesso!";
// });

Route::prefix('cliente')->name('cliente.')->group(function () {
    // Acesso público
    Route::get('/entrar', [ClienteController::class, 'entrar'])->name('entrar');
    Route::post('/enviar-link', [ClienteController::class, 'enviarLink'])->name('enviar-link');
    Route::get('/auth/{token}', [ClienteController::class, 'autenticar'])->name('auth');
    Route::post('/sair', [ClienteController::class, 'sair'])->name('sair');

    // Painel protegido
    Route::middleware('cliente.auth')->group(function () {
        Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
        Route::get('/faturas', [ClienteController::class, 'faturas'])->name('faturas');
        Route::get('/servicos', [ClienteController::class, 'servicos'])->name('servicos');
        Route::get('/empresa', [ClienteController::class, 'empresa'])->name('empresa');
    });
});

Route::group(['as' => 'web.'], function () {

    Route::get('/', [SiteController::class, 'home'])->name('home');
    Route::get('/portifolio', [SiteController::class, 'portifolio'])->name('portifolio');
    Route::get('/portifolio/{slug}', [SiteController::class, 'portifolioSingle'])->name('portifolio.single');
    Route::get('/blog', [SiteController::class, 'blog'])->name('blog.artigos');
    Route::get('/blog/artigo/{slug}', [SiteController::class, 'blogSingle'])->name('blog.artigo');
    Route::get('/blog/categoria/{slug}', [SiteController::class, 'blogCategoria'])->name('site.blog.categoria');
    Route::get('/atendimento', [SiteController::class, 'contact'])->name('contact');
    Route::get('/pagina/{slug}', [SiteController::class, 'page'])->name('page');

    
    Route::get('/politica-de-privacidade', [SiteController::class, 'privacy'])->name('privacy');
    Route::get('/termos-e-condicoes', [SiteController::class, 'terms'])->name('terms');

    Route::get('/sistemas', [ProdutoController::class, 'index'])->name('web.produtos');
    Route::get('/sistemas/{slug}', [ProdutoController::class, 'show'])->name('web.produto');
    //Route::get('/checkout/{produto}/{plano}', ...)->name('web.checkout'); // próximo passo
});

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin'], function () {

    Route::get('/', Dashboard::class)->name('admin');
    Route::get('configuracoes', Settings::class)->name('settings');
    Route::get('sitemap-generator', SitemapGenerator::class)->name('sitemap.generator');
    Route::get('notificacoes', NotificationsList::class)->name('notifications.index');

    // Somente Super Admin
    Route::middleware('role:super-admin')->group(function () {
        Route::get('empresas', Companies::class)->name('companies.index');
        Route::get('empresas/cadastrar-empresa', CompanyForm::class)->name('companies.create');
        Route::get('empresas/{company}/editar-empresa', CompanyForm::class)->name('companies.edit'); 
        
        Route::get('usuarios/time', Time::class)->name('users.time');
    });   
    

    //****************************** Cofre *******************************************/
    Route::get('cofre/{safe}/editar', SafeForm::class)->name('safe.edit');
    Route::get('cofre/cadastrar', SafeForm::class)->name('safe.create');
    Route::get('cofre', Safe::class)->name('safes.index');

    //****************************** Serviços ****************************************/
    Route::get('/services', ServiceIndex::class)->name('services.index');
    Route::get('/services/create', ServiceForm::class)->name('services.create');
    Route::get('/services/{service}/edit', ServiceForm::class)->name('services.edit');
    Route::get('/pedidos', SubscriptionIndex::class)->name('services.subscriptions.index');
    Route::get('/pedidos/create', SubscriptionForm::class)->name('services.subscriptions.create');
    Route::get('/pedidos/{subscription}/edit', SubscriptionForm::class)->name('services.subscriptions.edit');
    Route::get('/pedido/{subscription}/show', SubscriptionShow::class)->name('services.subscriptions.show');
    Route::get('/pedidos/{subscription}/faturas', InvoiceIndex::class)->name('services.invoices.index');

    //*********************** Invoices *********************************************/
    Route::get('/faturas', InvoicesIndex::class)->name('invoices.index');
    Route::get('/faturas/{invoice}/visualizar', InvoicesShow::class)->name('invoices.show');

    Route::get('/cargos', RoleIndex::class)->name('admin.roles');
    Route::get('/permissoes', PermissionIndex::class)->name('admin.permissions');
    
    //*********************** Posts *********************************************/
    Route::get('posts/{post}/editar', PostForm::class)->name('posts.edit');
    Route::get('posts/cadastrar', PostForm::class)->name('posts.create');
    Route::get('posts/categorias', CatPosts::class)->name('posts.categories.index');
    Route::get('posts', Posts::class)->name('posts.index');   
   
    
    //*************************** Portifólio *******************************************/
    Route::get('/portifolio/categorias', PortifolioCategories::class)->name('portifolio.categories.index');
    

    Route::get('/portifolio', PortifolioIndex::class)->name('portifolio.index');
    Route::get('/portifolio/cadastrar', PortifolioForm::class)->name('portifolio.create');
    Route::get('/portifolio/{portifolio}/editar', PortifolioForm::class)->name('portifolio.edit');

    
    //*********************** Usuários **********************************************/
    Route::get('usuarios/clientes', Users::class)->name('users.index');
    Route::get('usuarios/time', Time::class)->name('users.time');
    Route::get('usuarios/cadastrar', Form::class)->name('users.create');
    Route::get('usuarios/{userId}/editar', Form::class)->name('users.edit');
    Route::get('usuarios/{user}/visualizar', ViewUser::class)->name('users.view'); 

    //*********************** Slides ********************************************/
    Route::get('slides/{slide}/editar', SlideForm::class)->name('slides.edit');
    Route::get('slides/cadastrar', SlideForm::class)->name('slides.create');
    Route::get('slides', Slides::class)->name('slides.index');

    Route::get('relatorios/pedidos', SubscriptionReport::class)->name('reports.subscriptions');
    Route::get('relatorios/faturas', InvoiceReport::class)->name('reports.invoices');    
    Route::get('relatorios', ReportIndex::class)->name('reports.index');
});

// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});
