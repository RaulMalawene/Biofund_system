<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * StoreUserRequest
 *
 * Valida a criação de um novo utilizador interno pelo administrador.
 * A PROVÍNCIA é sempre obrigatória — todos os utilizadores têm scope provincial.
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
            // Província é sempre obrigatória
            'province_id'            => ['required', 'integer', 'exists:provinces,id'],
            'project_ids'            => ['nullable', 'array'],
            'project_ids.*'          => ['integer', 'exists:projects,id'],
            'receives_urgent_alerts' => ['boolean'],
            'receives_gbv_alerts'    => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'O nome é obrigatório.',
            'email.required'       => 'O email é obrigatório.',
            'email.unique'         => 'Este email já está em uso.',
            'role.required'        => 'O perfil é obrigatório.',
            'role.in'              => 'Perfil inválido. Use: admin, gestor ou funcionario.',
            'province_id.required' => 'A província é obrigatória.',
            'province_id.exists'   => 'Província inválida.',
        ];
    }
}