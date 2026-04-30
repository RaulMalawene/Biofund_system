<?php

namespace App\Models;

use App\Enums\OccurrenceStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OccurrenceStatusHistory
 *
 * Regista CADA mudança de estado de uma ocorrência.
 * É o histórico auditável do ciclo de vida da ocorrência.
 *
 * Criado automaticamente pelo OccurrenceService sempre que
 * o campo `status` de uma ocorrência é alterado.
 *
 * Campos importantes:
 *   - comment       → visível ao reclamante (resposta pública)
 *   - internal_note → visível apenas aos utilizadores internos
 *   - changed_by    → quem fez a mudança (null = sistema automático)
 *
 * @property int                         $id
 * @property int                         $occurrence_id
 * @property OccurrenceStatusEnum|null   $from_status
 * @property OccurrenceStatusEnum        $to_status
 * @property int|null                    $changed_by
 * @property string|null                 $comment
 * @property string|null                 $internal_note
 * @property \Carbon\Carbon              $changed_at
 */
class OccurrenceStatusHistory extends Model
{
    protected $table = 'occurrence_status_history';

    protected $fillable = [
        'occurrence_id',
        'from_status',
        'to_status',
        'changed_by',
        'comment',
        'internal_note',
        'changed_at',
    ];

    protected $casts = [
        'from_status' => OccurrenceStatusEnum::class,
        'to_status'   => OccurrenceStatusEnum::class,
        'changed_at'  => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Ocorrência a que esta entrada de histórico pertence.
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(Occurrence::class);
    }

    /**
     * Utilizador que efectuou a mudança de estado.
     * Null quando a mudança foi feita pelo sistema (ex: fecho automático).
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}