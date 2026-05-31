<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Project
 *
 * @property int         $id
 * @property string      $code
 * @property string      $name
 * @property string|null $description
 * @property string|null $icon        - chave do ícone (nature|tree|water|fire|drop|person|leaf|fish)
 * @property bool        $is_active
 */
class Project extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'icon', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_projects')->withTimestamps();
    }

    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}