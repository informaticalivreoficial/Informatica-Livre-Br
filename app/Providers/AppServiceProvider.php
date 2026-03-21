<?php

namespace App\Providers;

use App\Models\Post;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Schema::defaultStringLength(191);

        try {
            // //Páginas no menu frontend
            // $servicos = Post::orderBy('created_at', 'ASC')
            //                 ->postson()
            //                 ->where('categoria', 9)
            //                 ->get();
            // View()->share('menu_servicos', $servicos);

            $configuracoes = \App\Models\Config::find(1); 
            View()->share('configuracoes', $configuracoes);
        } catch (\Exception $e) {
            View()->share('Links', collect());
            View()->share('configuracoes', null);
        }

        if (request()->is('admin/*') || request()->is('dashboard*')) {
            Paginator::useBootstrap();
        } else {
            Paginator::useTailwind();
        }	    
    }
}
