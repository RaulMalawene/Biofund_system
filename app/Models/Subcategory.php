<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Subcategory
 *
 * Representa uma subcategoria dentro de uma categoria principal.
 * É o segundo nível de classificação de uma ocorrência.
 * Exemplo: Categoria "Ambiental" → Subcategoria "Desflorestação"
 *
 * @property int    $id
 * @property int    $category_id
 * @property string $name
 * @property bool   $is_active
 */
class Subcategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_id', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Uma subcategoria pertence a uma categoria principal.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Ocorrências classificadas com esta subcategoria.
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra subcategorias activas de uma categoria específica.
     * Uso: Subcategory::activeByCategory(1)->get()
     */
    public function scopeActiveByCategory($query, int $categoryId)
    {
        return $query->where('is_active', true)
                     ->where('category_id', $categoryId);
    }
}