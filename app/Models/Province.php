<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Province
 *
 * Representa as províncias de Moçambique.
 * É uma entidade de referência geográfica usada para:
 *   - Localizar a ocorrência registada
 *   - Definir a área de gestão dos utilizadores provinciais
 *   - Filtrar ocorrências por região nos relatórios
 *
 * @property int    $id
 * @property string $name
 * @property string $code
 * @property bool   $is_active
 */
class Province extends Model
{
    protected $fillable = ['name', 'code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Uma província tem vários distritos.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    /**
     * Utilizadores que têm esta província como área de gestão.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Ocorrências localizadas nesta província.
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra apenas províncias activas.
     * Uso: Province::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}