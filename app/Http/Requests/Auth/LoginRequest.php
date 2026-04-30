<?php
// ============================================================
// FICHEIRO: app/Http/Requests/Auth/LoginRequest.php
// ============================================================
namespace App\Http\Requests\Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * LoginRequest
 * Valida os campos de autenticação antes de chegar ao controller.
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'O email é obrigatório.',
            'email.email'       => 'Insira um email válido.',
            'password.required' => 'A senha é obrigatória.',
        ];
    }
}