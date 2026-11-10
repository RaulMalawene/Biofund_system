<?php

namespace App\Http\Requests\Occurrence;

use App\Enums\AlertLevelEnum;
use App\Enums\SubmissionChannelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

/**
 * StoreInternalOccurrenceRequest
 *
 * Valida o formulário de submissão interna de ocorrências.
 * Requer autenticação.
 *
 * Os campos complainant_* são opcionais - o funcionário pode registar
 * uma ocorrência em nome de um cidadão terceiro, fornecendo os dados
 * de contacto desse cidadão para efeitos de acompanhamento.
 */
class StoreInternalOccurrenceRequest extends FormRequest
{
    public const ORIGIN_OPTIONS = ['Individual', 'Comunitário', 'Grupo de trabalhadores', 'Anónimo', 'Outros'];

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Dados do reclamante (opcional - registo pode ser anónimo)
            'complainant_name'   => ['nullable', 'string', 'max:150'],
            'complainant_email'  => ['nullable', 'email', 'max:150'],
            'complainant_phone'  => ['nullable', 'string', 'max:30'],
            'complainant_gender' => ['nullable', 'string', 'in:masculino,feminino'],
            'complainant_age'    => ['nullable', 'string', 'max:20'],

            // Classificação
            'project_id'         => ['required', 'integer', 'exists:projects,id'],
            'category_id'        => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id'     => ['nullable', 'integer', 'exists:subcategories,id'],
            'occurrence_type_id' => ['nullable', 'integer', 'exists:occurrence_types,id'],

            // Localização
            'province_id'        => ['required', 'integer', 'exists:provinces,id'],
            'district_id'        => ['nullable', 'integer', 'exists:districts,id'],
            'location_detail'    => ['nullable', 'string', 'max:255'],

            // Conteúdo
            // Opções fixas do dropdown "Origem da ocorrência" (o mesmo texto que se grava).
            // Ocorrências antigas com texto livre não são afectadas: esta regra só se aplica a novos registos.
            'occurrence_origin'  => ['required', 'string', Rule::in(self::ORIGIN_OPTIONS)],
            'description'        => ['required', 'string', 'min:20'],
            'occurrence_date'    => ['nullable', 'date', 'before_or_equal:today'],

            // Campos exclusivos do registo interno
            'submission_channel' => ['required', new Enum(SubmissionChannelEnum::class)],
            'alert_type'         => ['required', new Enum(AlertLevelEnum::class)],

            // Anexos
            'attachments'        => ['nullable', 'array', 'max:5'],
            'attachments.*'      => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mp3'],
        ];
    }

    public function messages(): array
    {
        return [
            'complainant_email.email'         => 'Insira um email válido.',
            'project_id.required'             => 'Seleccione o projecto.',
            'category_id.required'            => 'Seleccione a categoria.',
            'province_id.required'            => 'Seleccione a província.',
            'occurrence_origin.required'      => 'A origem da ocorrência é obrigatória.',
            'occurrence_origin.in'            => 'Seleccione uma origem da ocorrência válida.',
            'description.required'            => 'A descrição é obrigatória.',
            'description.min'                 => 'A descrição deve ter pelo menos 20 caracteres.',
            'submission_channel.required'     => 'Seleccione o canal de submissão.',
            'submission_channel.*'            => 'Canal inválido.',
            'alert_type.required'             => 'Seleccione o nível de alerta.',
            'alert_type.*'                    => 'Nível de alerta inválido.',
            'attachments.max'                 => 'Máximo 5 anexos.',
            'attachments.*.max'               => 'Cada ficheiro não pode ultrapassar 10MB.',
        ];
    }
}