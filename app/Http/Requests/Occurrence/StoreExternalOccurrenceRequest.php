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
 * O reclamante pode submeter anonimamente - nome, email e telefone
 * são todos opcionais, mas pelo menos um contacto é recomendado
 * para receber o tracking_code e actualizações de estado.
 */
class StoreExternalOccurrenceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Dados do reclamante - todos opcionais (submissão anónima permitida)
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
            'subject'            => ['nullable', 'string', 'max:255'],
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
            'project_id.required'             => 'Seleccione o projecto.',
            'project_id.exists'               => 'Projecto inválido.',
            'category_id.required'            => 'Seleccione a categoria.',
            'province_id.required'            => 'Seleccione a província.',
            'description.required'            => 'A descrição é obrigatória.',
            'description.min'                 => 'A descrição deve ter pelo menos 20 caracteres.',
            'occurrence_date.before_or_equal' => 'A data da ocorrência não pode ser futura.',
            'attachments.max'                 => 'Pode anexar no máximo 5 ficheiros.',
            'attachments.*.max'               => 'Cada ficheiro não pode ultrapassar 10MB.',
            'attachments.*.mimes'             => 'Formatos aceites: JPG, PNG, PDF, DOC, DOCX, MP4, MP3.',
        ];
    }
}