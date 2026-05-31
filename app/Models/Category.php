<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Category
 *
 * Representa as categorias de classificação das ocorrências.
 * É o primeiro nível de classificação (ex: Fauna, Flora, Poluição Hídrica).
 * Cada categoria pode ter várias subcategorias.
 *
 * @property int         $id
 * @property string      $code
 * @property string      $name
 * @property string|null $description
 * @property string|null $icon        - chave do ícone (fauna, flora, agua, fogo, pesca, lixo, ar, caca)
 * @property string|null $color       - cor hexadecimal (#52B788)
 * @property array|null  $tags        - array de strings para filtragem
 * @property bool        $is_active
 */
class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'color',
        'tags',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tags'      => 'array',
    ];

    // ─── Relationships ──────────────────────────────────────────

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}