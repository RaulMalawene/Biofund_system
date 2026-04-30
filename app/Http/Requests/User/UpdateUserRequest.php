<?php
// ============================================================
// FICHEIRO: app/Http/Requests/User/UpdateUserRequest.php
// ============================================================
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UpdateUserRequest
 * Valida a edição de um utilizador existente pelo administrador.
 * Ignora o email único do próprio utilizador a ser editado.
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // Pega o ID do utilizador a ser editado via route model binding
        $userId = $this->route('user')?->id;

        return [
            'name'                   => ['required', 'string', 'max:150'],
            'email'                  => ['required', 'email', "unique:users,email,{$userId}"],
            'phone'                  => ['nullable', 'string', 'max:30'],
            'role'                   => ['required', Rule::in(['admin', 'gestor', 'funcionario'])],
            'management_scope'       => ['required', Rule::in(['national', 'provincial'])],
            'province_id'            => [
                Rule::requiredIf(fn() => $this->management_scope === 'provincial'),
                'nullable', 'integer', 'exists:provinces,id',
            ],
            'project_ids'            => ['nullable', 'array'],
            'project_ids.*'          => ['integer', 'exists:projects,id'],
            'receives_urgent_alerts' => ['boolean'],
            'receives_gbv_alerts'    => ['boolean'],
        ];
    }
}