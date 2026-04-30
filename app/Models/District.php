<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * District
 *
 * Representa os distritos de Moçambique, organizados por província.
 * Permite uma localização mais precisa das ocorrências.
 *
 * @property int    $id
 * @property int    $province_id
 * @property string $name
 * @property string $code
 * @property bool   $is_active
 */
class District extends Model
{
    protected $fillable = ['province_id', 'name', 'code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Um distrito pertence a uma província.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Ocorrências localizadas neste distrito.
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filtra apenas distritos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}