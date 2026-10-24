<?php

namespace App\Providers;

use App\Models\Post;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        $this->app->singleton('site.view.data', function (): array {
            try {
                return ['configuracoes' => \App\Models\Config::find(1)];
            } catch (\Throwable $e) {
                return ['Links' => collect(), 'configuracoes' => null];
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Notification::extend('telegram', function ($app) {
            return $app->make(TelegramChannel::class);
        });

        // //Páginas no menu frontend
        // $servicos = Post::orderBy('created_at', 'ASC')
        //                 ->postson()
        //                 ->where('categoria', 9)
        //                 ->get();
        // View()->share('menu_servicos', $servicos);

        View::composer('*', function ($view) {
            $view->with(app('site.view.data'));
        });

        if (request()->is('admin/*') || request()->is('dashboard*')) {
            Paginator::useBootstrap();
        } else {
            Paginator::useTailwind();
        }	    
    }
}
