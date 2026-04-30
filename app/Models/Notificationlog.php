<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * NotificationLog
 *
 * Regista todas as notificações enviadas pelo sistema.
 * Permite rastrear se o email/SMS foi enviado com sucesso
 * e reenviar em caso de falha.
 *
 * Eventos que disparam notificações:
 *   - occurrence_created    → enviado ao reclamante com o tracking_code
 *   - status_changed        → enviado ao reclamante quando o estado muda
 *   - occurrence_assigned   → enviado ao gestor quando recebe uma ocorrência
 *   - alert_urgent          → enviado a todos com receives_urgent_alerts = true
 *   - alert_gbv             → enviado a todos com receives_gbv_alerts = true
 *
 * @property int         $id
 * @property int         $occurrence_id
 * @property int|null    $user_id
 * @property string|null $recipient_email
 * @property string      $channel         email|sms|system
 * @property string      $event_type
 * @property string|null $message
 * @property string      $status          pending|sent|failed
 * @property \Carbon\Carbon|null $sent_at
 * @property string|null $error_message
 */
class NotificationLog extends Model
{
    protected $table = 'notifications_log';

    protected $fillable = [
        'occurrence_id',
        'user_id',
        'recipient_email',
        'channel',
        'event_type',
        'message',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Ocorrência que gerou esta notificação.
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(Occurrence::class);
    }

    /**
     * Utilizador interno que recebeu a notificação (se aplicável).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra notificações que falharam (para reenvio).
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}