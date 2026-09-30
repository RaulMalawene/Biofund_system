<?php

namespace App\Http\Requests\Occurrence;

use Illuminate\Foundation\Http\FormRequest;

/**
 * AddOccurrenceFollowUpRequest
 *
 * Valida o pedido de seguimento (comentário + anexos) que o utilizador
 * que submeteu a ocorrência acrescenta depois de o Gestor/Admin já ter
 * agido sobre ela. A verificação de dono/permissão é feita em
 * OccurrenceService::submitFollowUp().
 */
class AddOccurrenceFollowUpRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'comment'        => ['required', 'string', 'min:10', 'max:2000'],
            'attachments'    => ['nullable', 'array', 'max:5'],
            'attachments.*'  => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mp3'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'O comentário é obrigatório.',
            'comment.min'      => 'O comentário deve ter pelo menos 10 caracteres.',
            'comment.max'      => 'O comentário não pode exceder 2000 caracteres.',
            'attachments.max'  => 'Máximo 5 anexos.',
            'attachments.*.max' => 'Cada ficheiro não pode ultrapassar 10MB.',
        ];
    }
}
