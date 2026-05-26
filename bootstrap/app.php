<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Aliases de middleware para uso nas rotas
        $middleware->alias([
            'role'   => \App\Http\Middleware\RequireRole::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        // Aplica verificação de conta activa a todas as rotas autenticadas
        $middleware->appendToGroup('api', \App\Http\Middleware\EnsureUserIsActive::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();