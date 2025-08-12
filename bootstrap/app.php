<?php

use App\Providers\AuthServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [__DIR__.'/../routes/setup.php', __DIR__.'/../routes/web.php'],
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'install' => \App\Http\Middleware\EnsureNotInstalled::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'verified' => \App\Http\Middleware\IsVerified::class,
            'auto.logout' => \App\Http\Middleware\AutoLogout::class,
        ]);

        $middleware->web(prepend: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\CaptureAdTrackingParameters::class,
        ]);

        $middleware->authenticateSessions();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        AuthServiceProvider::class,
    ])->create();
