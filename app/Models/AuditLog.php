<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AuditLog
 *
 * Regista todas as acções relevantes executadas no sistema.
 * Permite ao administrador rastrear quem fez o quê e quando.
 *
 * É populado automaticamente pelo AuditService em cada operação crítica:
 *   - Criação, edição e remoção de ocorrências
 *   - Mudanças de estado (validação/rejeição)
 *   - Criação e edição de utilizadores
 *   - Login e logout
 *   - Alterações de parametrização
 *
 * Os campos `old_values` e `new_values` guardam um snapshot JSON
 * dos dados antes e depois da operação, permitindo ver exactamente
 * o que foi alterado.
 *
 * @property int         $id
 * @property int|null    $user_id
 * @property string      $auditable_type   ex: App\Models\Occurrence
 * @property int         $auditable_id
 * @property string      $event            created|updated|deleted|status_changed|login|logout
 * @property array|null  $old_values
 * @property array|null  $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Carbon\Carbon $occurred_at
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'occurred_at',
    ];

    protected $casts = [
        'old_values'  => 'array',
        'new_values'  => 'array',
        'occurred_at' => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Utilizador que realizou a acção.
     * Null quando a acção foi executada pelo sistema (cron, automação).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra logs por tipo de evento.
     * Uso: AuditLog::byEvent('login')->get()
     */
    public function scopeByEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Filtra logs de uma entidade específica.
     * Uso: AuditLog::forModel(Occurrence::class, 5)->get()
     */
    public function scopeForModel($query, string $type, int $id)
    {
        return $query->where('auditable_type', $type)
                     ->where('auditable_id', $id);
    }
}