<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId       = $this->route('user')?->id;
        $isObservador = $this->input('role') === 'observador';

        return [
            'name'                   => ['required', 'string', 'max:150'],
            'email'                  => ['required', 'email', "unique:users,email,{$userId}"],
            'phone'                  => ['nullable', 'string', 'max:30'],
            'role'                   => ['required', Rule::in(['admin', 'gestor', 'funcionario', 'observador'])],
            'management_scope'       => ['required', Rule::in(['national', 'provincial'])],
            // Províncias - array com 1 ou mais elementos
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
            'province_ids.required' => 'Seleccione pelo menos uma província.',
            'province_ids.min'      => 'Seleccione pelo menos uma província.',
            'project_ids.required'  => 'Seleccione pelo menos um projecto.',
            'project_ids.min'       => 'Seleccione pelo menos um projecto.',
        ];
    }
}