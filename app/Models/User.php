<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User
 *
 * Representa todos os utilizadores internos do sistema:
 *   - admin      → acesso total
 *   - gestor     → gere ocorrências da sua área, pode validar/rejeitar
 *   - funcionario → pode submeter ocorrências, não pode validar
 *
 * NOTA: O utilizador externo (público, sem login) NÃO tem registo
 * nesta tabela. Os seus dados ficam directamente na ocorrência.
 *
 * Um utilizador pode ter scope nacional (vê tudo) ou provincial
 * (vê apenas ocorrências da sua província).
 *
 * @property int      $id
 * @property string   $name
 * @property string   $email
 * @property string   $phone
 * @property RoleEnum $role
 * @property string   $management_scope  national|provincial
 * @property int|null $province_id
 * @property bool     $receives_urgent_alerts
 * @property bool     $receives_gbv_alerts
 * @property bool     $is_active
 * @property int|null $created_by
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'management_scope',
        'province_id',
        'receives_urgent_alerts',
        'receives_gbv_alerts',
        'is_active',
        'created_by',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role'                   => RoleEnum::class,
        'is_active'              => 'boolean',
        'receives_urgent_alerts' => 'boolean',
        'receives_gbv_alerts'    => 'boolean',
        'last_login_at'          => 'datetime',
        'email_verified_at'      => 'datetime',
        'password'               => 'hashed',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Província à qual o utilizador pertence (quando scope = provincial).
     * Null quando o scope é nacional.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Utilizador que criou esta conta (normalmente o admin).
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Utilizadores criados por este utilizador (admin cria gestores/funcionários).
     */
    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Projectos pelos quais este utilizador é responsável.
     * Relação many-to-many via tabela pivot `user_projects`.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'user_projects')
                    ->withTimestamps();
    }

    /**
     * Ocorrências submetidas por este utilizador (origin = internal).
     */
    public function submittedOccurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class, 'submitted_by_user_id');
    }

    /**
     * Ocorrências atribuídas a este utilizador para gestão.
     */
    public function assignedOccurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class, 'assigned_to');
    }

    /**
     * Ocorrências validadas/rejeitadas por este utilizador.
     */
    public function reviewedOccurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class, 'reviewed_by');
    }

    /**
     * Histórico de mudanças de estado feitas por este utilizador.
     */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(OccurrenceStatusHistory::class, 'changed_by');
    }

    /**
     * Logs de auditoria das acções deste utilizador.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // ─── Helpers de Role ────────────────────────────────────────

    /**
     * Verifica se o utilizador é administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === RoleEnum::Admin;
    }

    /**
     * Verifica se o utilizador é gestor.
     */
    public function isGestor(): bool
    {
        return $this->role === RoleEnum::Gestor;
    }

    /**
     * Verifica se o utilizador é funcionário.
     */
    public function isFuncionario(): bool
    {
        return $this->role === RoleEnum::Funcionario;
    }

    /**
     * Verifica se o utilizador pode validar ou rejeitar ocorrências.
     * Apenas admin e gestor têm esta permissão.
     */
    public function canValidate(): bool
    {
        return $this->role->canValidate();
    }

    /**
     * Verifica se o utilizador tem scope nacional
     * (vê ocorrências de todas as províncias).
     */
    public function isNational(): bool
    {
        return $this->management_scope === 'national';
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra apenas utilizadores activos.
     * Uso: User::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filtra por role.
     * Uso: User::byRole('gestor')->get()
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Filtra utilizadores que recebem alertas urgentes.
     */
    public function scopeReceivesUrgentAlerts($query)
    {
        return $query->where('receives_urgent_alerts', true)
                     ->where('is_active', true);
    }

    /**
     * Filtra utilizadores que recebem alertas GBV.
     */
    public function scopeReceivesGbvAlerts($query)
    {
        return $query->where('receives_gbv_alerts', true)
                     ->where('is_active', true);
    }
}

