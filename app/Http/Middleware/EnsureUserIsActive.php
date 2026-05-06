<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserIsActive
 *
 * Aplicado globalmente a todas as rotas auth:sanctum.
 * Rejeita utilizadores desactivados mesmo que o token ainda seja válido.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->is_active) {
            // Revoga todos os tokens do utilizador
            $user->tokens()->delete();

            return response()->json([
                'message' => 'A sua conta foi desactivada. Contacte o administrador.',
            ], 403);
        }

        return $next($request);
    }
}