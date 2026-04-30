<?php
// ============================================================
// FICHEIRO: app/Http/Requests/Occurrence/StoreExternalOccurrenceRequest.php
// ============================================================
namespace App\Http\Requests\Occurrence;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreExternalOccurrenceRequest
 *
 * Valida os dados do formulário público de submissão de ocorrências.
 * Não requer autenticação (authorize retorna true sempre).
 *
 * Pelo menos um contacto (email ou telefone) é recomendado para
 * que o reclamante possa receber o tracking_code e actualizações.
 */
class StoreExternalOccurrenceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Dados do reclamante
            'complainant_name'   => ['required', 'string', 'max:150'],
            'complainant_email'  => ['nullable', 'email', 'max:150'],
            'complainant_phone'  => ['nullable', 'string', 'max:30'],

            // Classificação
            'project_id'         => ['required', 'integer', 'exists:projects,id'],
            'category_id'        => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id'     => ['nullable', 'integer', 'exists:subcategories,id'],
            'occurrence_type_id' => ['required', 'integer', 'exists:occurrence_types,id'],

            // Localização
            'province_id'        => ['required', 'integer', 'exists:provinces,id'],
            'district_id'        => ['nullable', 'integer', 'exists:districts,id'],
            'location_detail'    => ['nullable', 'string', 'max:255'],

            // Conteúdo
            'subject'            => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string', 'min:20'],
            'occurrence_date'    => ['nullable', 'date', 'before_or_equal:today'],

            // Anexos (máx 5 ficheiros, 10MB cada)
            'attachments'        => ['nullable', 'array', 'max:5'],
            'attachments.*'      => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mp3'],
        ];
    }

    public function messages(): array
    {
        return [
            'complainant_name.required'   => 'O nome é obrigatório.',
            'project_id.required'         => 'Seleccione o projecto.',
            'project_id.exists'           => 'Projecto inválido.',
            'category_id.required'        => 'Seleccione a categoria.',
            'occurrence_type_id.required' => 'Seleccione o tipo de ocorrência.',
            'province_id.required'        => 'Seleccione a província.',
            'subject.required'            => 'O assunto é obrigatório.',
            'description.required'        => 'A descrição é obrigatória.',
            'description.min'             => 'A descrição deve ter pelo menos 20 caracteres.',
            'occurrence_date.before_or_equal' => 'A data da ocorrência não pode ser futura.',
            'attachments.max'             => 'Pode anexar no máximo 5 ficheiros.',
            'attachments.*.max'           => 'Cada ficheiro não pode ultrapassar 10MB.',
            'attachments.*.mimes'         => 'Formatos aceites: JPG, PNG, PDF, DOC, DOCX, MP4, MP3.',
        ];
    }
}