<?php

namespace App\Models;

use App\Enums\AlertLevelEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * OccurrenceType
 *
 * Define o tipo de uma ocorrência (Reclamação, Consulta, Sugestão, Elogio).
 * Cada tipo tem um nível de alerta associado (normal, urgent, gbv)
 * e um prazo de resolução em dias (SLA).
 *
 * O SLA é usado pelo sistema para calcular a data limite (due_date)
 * de resolução de cada ocorrência no momento do registo.
 *
 * @property int            $id
 * @property string         $code
 * @property string         $name
 * @property AlertLevelEnum $alert_level
 * @property int            $sla_days
 * @property bool           $is_active
 */
class OccurrenceType extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'alert_level', 'sla_days', 'is_active'];

    protected $casts = [
        'alert_level' => AlertLevelEnum::class,
        'is_active'   => 'boolean',
        'sla_days'    => 'integer',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Ocorrências deste tipo.
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    // ─── Helpers ────────────────────────────────────────────────

    /**
     * Calcula a data limite de resolução (due_date) com base no SLA.
     * Retorna a data de hoje + sla_days.
     */
    public function calculateDueDate(): \Carbon\Carbon
    {
        return now()->addDays($this->sla_days);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra apenas tipos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}