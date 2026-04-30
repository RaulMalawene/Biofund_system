<?php
// ============================================================
// FICHEIRO: app/Http/Requests/Occurrence/UpdateOccurrenceStatusRequest.php
// ============================================================
namespace App\Http\Requests\Occurrence;

use App\Enums\OccurrenceStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UpdateOccurrenceStatusRequest
 *
 * Valida a mudança de estado de uma ocorrência.
 * O comentário é obrigatório quando o estado é resolved ou rejected
 * (validado aqui e também verificado no OccurrenceService).
 */
class UpdateOccurrenceStatusRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $validStatuses = array_column(OccurrenceStatusEnum::cases(), 'value');

        return [
            'status'        => ['required', 'string', Rule::in($validStatuses)],
            'comment'       => [
                // Obrigatório se o status for resolved ou rejected
                Rule::requiredIf(fn() =>
                    in_array($this->status, ['resolved', 'rejected'])
                ),
                'nullable', 'string', 'min:10', 'max:2000',
            ],
            'internal_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'  => 'O estado é obrigatório.',
            'status.in'        => 'Estado inválido.',
            'comment.required' => 'O comentário é obrigatório ao resolver ou rejeitar uma ocorrência.',
            'comment.min'      => 'O comentário deve ter pelo menos 10 caracteres.',
        ];
    }
}