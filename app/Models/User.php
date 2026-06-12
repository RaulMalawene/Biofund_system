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

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
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

    protected $hidden = ['password', 'remember_token'];

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

    /** Província principal (retrocompatibilidade - primeira da lista) */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Províncias do utilizador (many-to-many via user_provinces).
     * Para funcionários: 1 registo. Para gestores: 1 ou mais.
     */
    public function provinces(): BelongsToMany
    {
        return $this->belongsToMany(Province::class, 'user_provinces')->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'user_projects')->withTimestamps();
    }

    public function submittedOccurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class, 'submitted_by_user_id');
    }

    public function assignedOccurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class, 'assigned_to');
    }

    public function reviewedOccurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class, 'reviewed_by');
    }

    public function statusChanges(): HasMany
    {
        return $this->hasMany(OccurrenceStatusHistory::class, 'changed_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // ─── Helpers ────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === RoleEnum::Admin;
    }
    public function isGestor(): bool
    {
        return $this->role === RoleEnum::Gestor;
    }
    public function isFuncionario(): bool
    {
        return $this->role === RoleEnum::Funcionario;
    }
    public function isObservador(): bool
    {
        return $this->role === RoleEnum::Observador;
    }
    public function canValidate(): bool
    {
        return $this->role->canValidate();
    }
    public function isNational(): bool
    {
        return $this->management_scope === 'national';
    }

    // ─── Scopes ─────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
    public function scopeReceivesUrgentAlerts($query)
    {
        return $query->where('receives_urgent_alerts', true)->where('is_active', true);
    }
    public function scopeReceivesGbvAlerts($query)
    {
        return $query->where('receives_gbv_alerts', true)->where('is_active', true);
    }
}
