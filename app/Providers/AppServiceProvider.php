<?php

namespace App\Providers;

use App\Models\Post;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    URL::forceScheme('https');
        Schema::defaultStringLength(191);
        Blade::aliasComponent('admin.components.message', 'message');

        $configuracoes = \App\Models\Configuracoes::find(1); 
        View()->share('configuracoes', $configuracoes);

        //Páginas no menu frontend
        $servicos = Post::orderBy('created_at', 'ASC')
                        ->postson()
                        ->where('categoria', 9)
                        ->get();
        View()->share('menu_servicos', $servicos);

        Paginator::useBootstrap();
    }
}
