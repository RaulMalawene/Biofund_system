<?php

namespace App\Http\Requests\Occurrence;

use App\Enums\OccurrenceStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UpdateOccurrenceStatusRequest
 *
 * Valida a mudança de estado de uma ocorrência.
 *
 * Regra central:
 *   - O campo 'comment' (justificação) é OBRIGATÓRIO quando o estado
 *     é 'resolved' ou 'rejected'. Sem justificação não é possível
 *     validar nem rejeitar uma ocorrência.
 */
class UpdateOccurrenceStatusRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        // Converter string vazia em null para que 'nullable' + 'min:10' não falhe
        // em transições que não obrigam comentário (ex: validar, iniciar resolução)
        if ($this->comment === '') {
            $this->merge(['comment' => null]);
        }
    }

    public function rules(): array
    {
        $validStatuses = array_column(OccurrenceStatusEnum::cases(), 'value');

        return [
            'status' => ['required', 'string', Rule::in($validStatuses)],

            // Justificação obrigatória ao não validar ou ao marcar como resolvido
            'comment' => [
                Rule::requiredIf(fn() => in_array($this->status, ['nao_validado', 'resolvido'])),
                'nullable',
                'string',
                'min:10',
                'max:2000',
            ],

            // Nota interna (visível apenas a gestores/admin, não ao reclamante)
            'internal_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'  => 'O estado é obrigatório.',
            'status.in'        => 'Estado inválido.',
            'comment.required' => 'A justificação é obrigatória ao não validar ou ao resolver uma ocorrência.',
            'comment.min'      => 'A justificação deve ter pelo menos 10 caracteres.',
            'comment.max'      => 'A justificação não pode exceder 2000 caracteres.',
        ];
    }
}