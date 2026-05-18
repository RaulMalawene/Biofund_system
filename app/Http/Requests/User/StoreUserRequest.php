<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * StoreUserRequest
 *
 * Valida a criação de um novo utilizador interno pelo administrador.
 *
 * Províncias:
 *   - Funcionário: province_ids obrigatório com exactamente 1 elemento
 *   - Gestor:      province_ids obrigatório com 1 ou mais elementos (multi-província)
 *   - Admin:       province_ids opcional (pode ter scope nacional)
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                   => ['required', 'string', 'max:150'],
            'email'                  => ['required', 'email', 'unique:users,email'],
            'phone'                  => ['nullable', 'string', 'max:30'],
            'role'                   => ['required', Rule::in(['admin', 'gestor', 'funcionario'])],
            // Províncias — array (uma para funcionários, uma ou mais para gestores)
            'province_ids'           => ['required', 'array', 'min:1'],
            'province_ids.*'         => ['integer', 'exists:provinces,id'],
            // Projectos
            'project_ids'            => ['nullable', 'array'],
            'project_ids.*'          => ['integer', 'exists:projects,id'],
            // Alertas
            'receives_urgent_alerts' => ['boolean'],
            'receives_gbv_alerts'    => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'O nome é obrigatório.',
            'email.required'          => 'O email é obrigatório.',
            'email.unique'            => 'Este email já está registado.',
            'role.required'           => 'O perfil é obrigatório.',
            'role.in'                 => 'Perfil inválido.',
            'province_ids.required'   => 'Seleccione pelo menos uma província.',
            'province_ids.min'        => 'Seleccione pelo menos uma província.',
            'province_ids.*.exists'   => 'Província inválida.',
        ];
    }
}