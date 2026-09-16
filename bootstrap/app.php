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
        // API requests never redirect - always return 401 JSON so the frontend interceptor handles it
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        });

        // Falha de ligação à base de dados (host em baixo, timeout de rede, etc.)
        // - sem isto o cliente recebia um genérico "Server Error" (500) que não
        // permite distinguir uma falha de ligação de um erro de programação.
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null; // deixa o comportamento por omissão para pedidos não-API
            }

            return response()->json([
                'message' => 'Não foi possível processar o pedido devido a um problema de ligação à base de dados. Tente novamente dentro de instantes.',
            ], 503);
        });

        // Falha ao contactar um serviço externo via Http::client() (ex: APIs de terceiros)
        $exceptions->render(function (\Illuminate\Http\Client\ConnectionException $e, $request) {
            if (! $request->is('api/*') && ! $request->expectsJson()) {
                return null;
            }

            return response()->json([
                'message' => 'Falha de ligação a um serviço externo. Tente novamente dentro de instantes.',
            ], 503);
        });
    })->create();