<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

/**
 * PasswordResetController
 *
 * Gere o fluxo completo de recuperação e alteração de senha.
 *
 * Fluxo de recuperação:
 *   1. POST /api/auth/forgot-password  → envia email com link/token
 *   2. POST /api/auth/reset-password   → define nova senha com o token
 *
 * Alteração de senha (utilizador autenticado):
 *   3. POST /api/auth/change-password  → altera senha sem token
 */
class PasswordResetController extends Controller
{
    /**
     * Envia o email de recuperação de senha.
     * Gera um token único e envia um link para o email do utilizador.
     *
     * ROTA: POST /api/auth/forgot-password
     * ACESSO: Público (sem autenticação)
     *
     * Body:
     *   { "email": "gestor@mdr.biofund.org.mz" }
     *
     * Resposta (200) — sempre a mesma para não expor se o email existe:
     *   { "message": "Se o email existir, receberá instruções em breve." }
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Usa o sistema nativo de Password Reset do Laravel
        // Só envia se o email existir na base de dados
        Password::sendResetLink(['email' => $request->email]);

        // Resposta genérica por segurança (não revela se o email existe)
        return response()->json([
            'message' => 'Se o email estiver registado, receberá as instruções de recuperação em breve.',
        ], 200);
    }

    /**
     * Redefine a senha do utilizador usando o token recebido por email.
     *
     * ROTA: POST /api/auth/reset-password
     * ACESSO: Público (sem autenticação)
     *
     * Body:
     *   {
     *     "token": "abc123...",
     *     "email": "gestor@mdr.biofund.org.mz",
     *     "password": "NovaSenha@2024",
     *     "password_confirmation": "NovaSenha@2024"
     *   }
     *
     * Resposta de sucesso (200):
     *   { "message": "Senha redefinida com sucesso. Pode fazer login." }
     *
     * Resposta de erro (422):
     *   { "message": "Token inválido ou expirado." }
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Revoga todos os tokens activos por segurança
                $user->tokens()->delete();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        return response()->json([
            'message' => 'Senha redefinida com sucesso. Pode fazer login.',
        ], 200);
    }

    /**
     * Permite ao utilizador autenticado alterar a sua própria senha.
     * Requer a senha actual para confirmação de identidade.
     *
     * ROTA: POST /api/auth/change-password
     * ACESSO: Autenticado (qualquer role)
     *
     * Body:
     *   {
     *     "current_password": "SenhaActual@123",
     *     "password": "NovaSenha@2024",
     *     "password_confirmation": "NovaSenha@2024"
     *   }
     *
     * Resposta de sucesso (200):
     *   { "message": "Senha alterada com sucesso." }
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $user = $request->user();

        // Verifica se a senha actual está correcta
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'A senha actual está incorrecta.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoga todos os outros tokens (força novo login em outros dispositivos)
        $user->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();

        return response()->json([
            'message' => 'Senha alterada com sucesso.',
        ], 200);
    }
}