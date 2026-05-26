<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mob extends Model
{
    protected $fillable = [
        'name',
        'image',
        'category_id',
        'health',
        'damage',
        'drops',
        'description',
        'spawning_conditions',
        'health_easy',
        'health_normal',
        'health_hard',
        'damage_easy',
        'damage_normal',
        'damage_hard',
        'melee_attack',
        'ranged_attack',
        'xp_reward',
    ];

    public function loot()
    {
        return $this->hasMany(MobDrop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function biomes()
    {
        return $this->belongsToMany(Biome::class)->withTimestamps();
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function contributions()
    {
        return $this->hasMany(MobContribution::class);
    }

    public function revisions()
    {
        return $this->hasMany(MobRevision::class);
    }

    public function pinnedBy()
    {
        return $this->belongsToMany(User::class, 'user_pinned_mobs')
                    ->withPivot('slot_index')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeInBiome($query, $biomeId)
    {
        return $query->whereHas('biomes', function ($q) use ($biomeId) {
            $q->where('biomes.id', $biomeId);
        });
    }
}
