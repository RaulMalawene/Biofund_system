<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Project
 *
 * Representa os projectos do FNDS/BIOFUND financiados pelo Banco Mundial.
 * Cada ocorrência registada no sistema DEVE estar associada a um projecto.
 * Cada utilizador (gestor/funcionário) DEVE estar associado a um ou mais projectos.
 *
 * @property int    $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property bool   $is_active
 */
class Project extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Um projecto pode ter vários utilizadores responsáveis.
     * Relação many-to-many via tabela pivot `user_projects`.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_projects')
                    ->withTimestamps();
    }

    /**
     * Ocorrências associadas a este projecto.
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra apenas projectos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}