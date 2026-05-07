<?php

namespace App\Http\Requests\Occurrence;

use App\Enums\AlertLevelEnum;
use App\Enums\SubmissionChannelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * StoreInternalOccurrenceRequest
 *
 * Valida o formulário de submissão interna de ocorrências.
 * Requer autenticação. Os campos complainant_* não são necessários
 * pois o utilizador já está identificado pelo token.
 */
class StoreInternalOccurrenceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'project_id'         => ['required', 'integer', 'exists:projects,id'],
            'category_id'        => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id'     => ['nullable', 'integer', 'exists:subcategories,id'],
            'occurrence_type_id' => ['required', 'integer', 'exists:occurrence_types,id'],
            'province_id'        => ['required', 'integer', 'exists:provinces,id'],
            'district_id'        => ['nullable', 'integer', 'exists:districts,id'],
            'location_detail'    => ['nullable', 'string', 'max:255'],
            'subject'            => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string', 'min:20'],
            'occurrence_date'    => ['nullable', 'date', 'before_or_equal:today'],

            // Campos exclusivos do registo interno
            'submission_channel' => ['required', new Enum(SubmissionChannelEnum::class)],
            'alert_type'         => ['required', new Enum(AlertLevelEnum::class)],

            'attachments'        => ['nullable', 'array', 'max:5'],
            'attachments.*'      => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mp3'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required'              => 'Seleccione o projecto.',
            'category_id.required'             => 'Seleccione a categoria.',
            'occurrence_type_id.required'      => 'Seleccione o tipo de ocorrência.',
            'province_id.required'             => 'Seleccione a província.',
            'subject.required'                 => 'O assunto é obrigatório.',
            'description.required'             => 'A descrição é obrigatória.',
            'description.min'                  => 'A descrição deve ter pelo menos 20 caracteres.',
            'submission_channel.required'      => 'Seleccione o canal de submissão.',
            'submission_channel.*'             => 'Canal inválido. Valores: green_line, email, phone, community_meeting.',
            'alert_type.required'              => 'Seleccione o tipo de alerta.',
            'alert_type.*'                     => 'Tipo de alerta inválido. Valores: normal, urgent, gbv.',
        ];
    }
}