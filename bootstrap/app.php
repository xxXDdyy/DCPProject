<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'maintenance' => App\Http\Middleware\DownForMaintenanceMw::class,
            'auth.session' => App\Http\Middleware\RequireLogin::class,
            'role' => App\Http\Middleware\RoleMiddleware::class,
        ]);

        //middleware groups
        $middleware->group('groupMiddleware', [
            App\Http\Middleware\MiddlewareOne::class,
            App\Http\Middleware\MiddlewareTwo::class,
        ]);

        // Temporary for classroom AJAX testing with multiple open login tabs.
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
            'password/change-first-login',
        ]);

        // $middleware->append(App\Http\Middleware\PromotionMw::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
