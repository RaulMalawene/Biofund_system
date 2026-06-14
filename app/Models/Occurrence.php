<?php

namespace App\Models;

use App\Enums\AlertLevelEnum;
use App\Enums\OccurrenceStatusEnum;
use App\Enums\OriginEnum;
use App\Enums\SubmissionChannelEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Occurrence
 *
 * Entidade central do sistema MDR.
 * Representa uma ocorrência (reclamação, consulta, sugestão ou elogio)
 * registada por um cidadão externo ou por um utilizador interno.
 *
 * Origens possíveis (campo `origin`):
 *   - external → formulário público, sem login.
 *                Dados do reclamante guardados em complainant_*.
 *   - internal → submetida por funcionário, gestor ou admin autenticado.
 *                O ID do utilizador é guardado em submitted_by_user_id.
 *
 * Ciclo de vida (campo `status`):
 *   pending → in_review → resolved → closed
 *   pending → in_review → rejected
 *
 * O tracking_code é um código único público gerado pelo sistema
 * e entregue ao reclamante para acompanhar a sua ocorrência.
 *
 * @property int                    $id
 * @property string                 $tracking_code
 * @property OriginEnum             $origin
 * @property int|null               $submitted_by_user_id
 * @property string|null            $complainant_name
 * @property string|null            $complainant_email
 * @property string|null            $complainant_phone
 * @property int                    $project_id
 * @property int                    $category_id
 * @property int|null               $subcategory_id
 * @property int                    $occurrence_type_id
 * @property int                    $province_id
 * @property int|null               $district_id
 * @property string|null            $location_detail
 * @property string                 $subject
 * @property string                 $description
 * @property \Carbon\Carbon|null    $occurrence_date
 * @property OccurrenceStatusEnum   $status
 * @property int|null               $assigned_to
 * @property int|null               $reviewed_by
 * @property \Carbon\Carbon|null    $reviewed_at
 * @property \Carbon\Carbon|null    $due_date
 */
class Occurrence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tracking_code',
        'origin',
        'submitted_by_user_id',
        'complainant_name',
        'complainant_email',
        'complainant_phone',
        'complainant_gender',
        'complainant_age',
        'project_id',
        'category_id',
        'subcategory_id',
        'occurrence_type_id',
        'province_id',
        'district_id',
        'location_detail',
        'subject',
        'description',
        'occurrence_date',
        'status',
        'assigned_to',
        'reviewed_by',
        'reviewed_at',
        'due_date',
        'submission_channel',
        'alert_type',
    ];

    protected $casts = [
        'origin'             => OriginEnum::class,
        'status'             => OccurrenceStatusEnum::class,
        'occurrence_date'    => 'date',
        'reviewed_at'        => 'datetime',
        'due_date'           => 'date',
        'submission_channel' => SubmissionChannelEnum::class,
        'alert_type'         => AlertLevelEnum::class,
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Utilizador interno que submeteu a ocorrência (quando origin = internal).
     * Null quando a ocorrência veio do formulário público.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    /**
     * Gestor ou admin responsável pelo tratamento da ocorrência.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Utilizador que fez a revisão final (validou ou rejeitou).
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Projecto associado a esta ocorrência.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Categoria da ocorrência.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Subcategoria da ocorrência (opcional).
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Tipo da ocorrência (Reclamação, Consulta, Sugestão, Elogio).
     * Contém o nível de alerta e o SLA.
     */
    public function occurrenceType(): BelongsTo
    {
        return $this->belongsTo(OccurrenceType::class);
    }

    /**
     * Província onde ocorreu o evento.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Distrito onde ocorreu o evento (opcional).
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Histórico completo de mudanças de estado desta ocorrência.
     * Cada entrada regista quem mudou, de que estado, para que estado e com que comentário.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OccurrenceStatusHistory::class)
                    ->orderBy('changed_at', 'asc');
    }

    /**
     * Ficheiros anexados a esta ocorrência.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(OccurrenceAttachment::class);
    }

    /**
     * Log de notificações enviadas relacionadas com esta ocorrência.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    // ─── Helpers ────────────────────────────────────────────────

    /**
     * Verifica se a ocorrência está fora do prazo (SLA vencido).
     */
    public function isOverdue(): bool
    {
        return $this->due_date
            && now()->isAfter($this->due_date)
            && !in_array($this->status, [
                OccurrenceStatusEnum::Resolvido,
                OccurrenceStatusEnum::NaoValidado,
                OccurrenceStatusEnum::NaoResolvida,
            ]);
    }

    /**
     * Verifica se a ocorrência foi submetida externamente (sem login).
     */
    public function isExternal(): bool
    {
        return $this->origin === OriginEnum::External;
    }

    /**
     * Limite de dias úteis sem actualização de estado antes da ocorrência
     * ser marcada automaticamente como 'Não Resolvida' (occurrences:mark-unresolved).
     *
     * - Ocorrências externas (formulário público, sem campo "tipo de alerta"):
     *   sempre 5 dias úteis.
     * - Ocorrências internas (gestor/admin/funcionário): baseado no campo
     *   alert_type - 'urgent' => 3 dias úteis, restantes => 5 dias úteis.
     */
    public function statusUpdateBusinessDaysLimit(): int
    {
        if ($this->isExternal()) {
            return AlertLevelEnum::Normal->statusUpdateBusinessDaysLimit();
        }

        return $this->alert_type === AlertLevelEnum::Urgent
            ? AlertLevelEnum::Urgent->statusUpdateBusinessDaysLimit()
            : AlertLevelEnum::Normal->statusUpdateBusinessDaysLimit();
    }

    /**
     * Data-limite (em dias úteis) para actualização de estado, a partir do
     * registo da ocorrência. Usada no email de confirmação enviado ao reclamante.
     */
    public function statusUpdateDueDate(): \Carbon\Carbon
    {
        $date      = $this->created_at->copy();
        $remaining = $this->statusUpdateBusinessDaysLimit();

        while ($remaining > 0) {
            $date->addDay();
            if (!$date->isWeekend()) {
                $remaining--;
            }
        }

        return $date;
    }

    /**
     * Retorna o nome da pessoa afectada conforme digitado no formulário.
     */
    public function getComplainantNameAttribute(): ?string
    {
        return $this->attributes['complainant_name'] ?? null;
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra ocorrências por estado.
     * Uso: Occurrence::byStatus('pending')->get()
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filtra ocorrências de uma província específica.
     * Usado para restringir o que o gestor provincial vê.
     */
    public function scopeByProvince($query, int $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    /**
     * Filtra ocorrências atribuídas a um utilizador específico.
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Filtra ocorrências cujo prazo já passou (SLA vencido).
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
                     ->where('due_date', '<', now())
                     ->whereNotIn('status', ['resolvido', 'nao_validado', 'nao_resolvida']);
    }

    /**
     * Filtra por projecto.
     */
    public function scopeByProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }
}