<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Category
 *
 * Representa as categorias de classificação das ocorrências.
 * É o primeiro nível de classificação (ex: Ambiental, Social, GBV).
 * Cada categoria pode ter várias subcategorias.
 *
 * @property int    $id
 * @property string $code
 * @property string $name
 * @property bool   $is_active
 */
class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Uma categoria tem várias subcategorias.
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    /**
     * Ocorrências classificadas com esta categoria.
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra apenas categorias activas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}