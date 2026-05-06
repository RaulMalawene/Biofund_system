<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RequireRole
 *
 * Middleware que restringe o acesso a rotas com base no role do utilizador.
 *
 * Uso nas rotas:
 *   ->middleware('role:admin')
 *   ->middleware('role:admin,gestor')
 *
 * O utilizador deve estar autenticado (via auth:sanctum) ANTES deste middleware.
 * Se não estiver autenticado, retorna 401.
 * Se não tiver o role necessário, retorna 403.
 */
class RequireRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'A sua conta está desactivada.'], 403);
        }

        if (!in_array($user->role->value, $roles)) {
            return response()->json([
                'message' => 'Não tem permissão para aceder a este recurso.',
            ], 403);
        }

        return $next($request);
    }
}