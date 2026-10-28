<?php

use App\Http\Middleware\CheckApiToken;
use App\Http\Middleware\ClienteAuth;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'webhooks/paghiper',
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo('/admin');

        $middleware->throttleApi();

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'api.token' => CheckApiToken::class,
            'cliente.auth' => ClienteAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('sitemap:generate')->everyMinute()->withoutOverlapping();
        $schedule->command('companies:generate-tokens')->everyMinute()->withoutOverlapping();
        $schedule->command('invoices:process-recurring')->everyMinute()->withoutOverlapping();
        $schedule->command('loja:cobrar-recorrencias')->dailyAt('06:00')->withoutOverlapping();
        $schedule->command('financial:check-due')->everyMinute()->withoutOverlapping();
        $schedule->command('notifications:clean-old')->everyMinute()->withoutOverlapping();
        $schedule->command('app:clear-logs')->everyMinute()->withoutOverlapping();
    })
    ->create();
