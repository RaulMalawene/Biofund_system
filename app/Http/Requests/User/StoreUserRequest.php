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
 *   - Observador:  province_ids obrigatório com 1 ou mais elementos (multi-província)
 *   - Admin:       province_ids opcional (pode ter scope nacional)
 *
 * Projectos:
 *   - Observador:  project_ids obrigatório com 1 ou mais elementos (multi-projecto)
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $isObservador = $this->input('role') === 'observador';

        return [
            'name'                   => ['required', 'string', 'max:150'],
            'email'                  => ['required', 'email', 'unique:users,email'],
            'phone'                  => ['nullable', 'string', 'max:30'],
            'role'                   => ['required', Rule::in(['admin', 'gestor', 'funcionario', 'observador'])],
            // Províncias - array (uma para funcionários, uma ou mais para gestores/observadores)
            'province_ids'           => ['required', 'array', 'min:1'],
            'province_ids.*'         => ['integer', 'exists:provinces,id'],
            // Projectos - obrigatório (mínimo 1) para observadores
            'project_ids'            => $isObservador ? ['required', 'array', 'min:1'] : ['nullable', 'array'],
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
            'project_ids.required'    => 'Seleccione pelo menos um projecto.',
            'project_ids.min'         => 'Seleccione pelo menos um projecto.',
            'project_ids.*.exists'    => 'Projecto inválido.',
        ];
    }
}