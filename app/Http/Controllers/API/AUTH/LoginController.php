<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * LoginController
 *
 * Gere a autenticação dos utilizadores internos do sistema MDR.
 * Usa Laravel Sanctum para emitir tokens de acesso.
 *
 * Apenas utilizadores internos (admin, gestor, funcionario)
 * fazem login. O utilizador externo (público) não tem conta.
 */
class LoginController extends Controller
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Autentica o utilizador e retorna o token de acesso.
     *
     * ROTA: POST /api/auth/login
     * ACESSO: Público (sem autenticação)
     *
     * Body esperado:
     *   {
     *     "email": "admin@mdr.biofund.org.mz",
     *     "password": "Admin@MDR2024"
     *   }
     *
     * Resposta de sucesso (200):
     *   {
     *     "token": "1|abc...",
     *     "user": { id, name, email, role, management_scope, ... },
     *     "message": "Login efectuado com sucesso."
     *   }
     *
     * Resposta de erro (401):
     *   { "message": "Credenciais inválidas." }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Procura o utilizador pelo email
        $user = User::where('email', $request->email)->first();

        // Verifica se o utilizador existe e a senha está correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        // Verifica se a conta está activa
        if (!$user->is_active) {
            return response()->json([
                'message' => 'A sua conta está desactivada. Contacte o administrador.',
            ], 403);
        }

        // Revoga todos os tokens anteriores (sessão única)
        $user->tokens()->delete();

        // Gera um novo token Sanctum com as abilities do role
        $token = $user->createToken(
            name: "mdr-{$user->role->value}-token",
            abilities: $this->getAbilitiesByRole($user->role->value)
        )->plainTextToken;

        // Actualiza o último login
        $user->update(['last_login_at' => now()]);

        // Regista o login na auditoria
        $this->auditService->logLogin($user);

        return response()->json([
            'token'   => $token,
            'user'    => [
                'id'               => $user->id,
                'name'             => $user->name,
                'email'            => $user->email,
                'role'             => $user->role->value,
                'role_label'       => $user->role->label(),
                'management_scope' => $user->management_scope,
                'province_id'      => $user->province_id,
                'province'         => $user->province?->name,
                'can_validate'     => $user->canValidate(),
            ],
            'message' => 'Login efectuado com sucesso.',
        ], 200);
    }

    /**
     * Termina a sessão do utilizador autenticado.
     * Revoga o token actual.
     *
     * ROTA: POST /api/auth/logout
     * ACESSO: Autenticado (qualquer role)
     *
     * Resposta (200):
     *   { "message": "Sessão terminada com sucesso." }
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        // Regista o logout na auditoria antes de revogar o token
        $this->auditService->logLogout($user);

        // Revoga apenas o token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sessão terminada com sucesso.',
        ], 200);
    }

    /**
     * Retorna os dados do utilizador actualmente autenticado.
     *
     * ROTA: GET /api/auth/me
     * ACESSO: Autenticado (qualquer role)
     *
     * Resposta (200):
     *   { "user": { id, name, email, role, ... } }
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('province', 'projects');

        return response()->json([
            'user' => [
                'id'                     => $user->id,
                'name'                   => $user->name,
                'email'                  => $user->email,
                'phone'                  => $user->phone,
                'role'                   => $user->role->value,
                'role_label'             => $user->role->label(),
                'management_scope'       => $user->management_scope,
                'province_id'            => $user->province_id,
                'province'               => $user->province?->name,
                'projects'               => $user->projects->map(fn($p) => [
                    'id'   => $p->id,
                    'name' => $p->name,
                ]),
                'receives_urgent_alerts' => $user->receives_urgent_alerts,
                'receives_gbv_alerts'    => $user->receives_gbv_alerts,
                'can_validate'           => $user->canValidate(),
                'last_login_at'          => $user->last_login_at?->format('d/m/Y H:i'),
            ],
        ], 200);
    }

    /**
     * Define as abilities do token Sanctum com base no role do utilizador.
     * Estas abilities podem ser verificadas nas Policies e middlewares.
     *
     * @param  string  $role
     * @return array
     */
    private function getAbilitiesByRole(string $role): array
    {
        return match($role) {
            'admin'       => ['*'],  // admin tem acesso total
            'gestor'      => ['occurrences:read', 'occurrences:write', 'occurrences:validate'],
            'funcionario' => ['occurrences:read', 'occurrences:write'],
            default       => [],
        };
    }
}