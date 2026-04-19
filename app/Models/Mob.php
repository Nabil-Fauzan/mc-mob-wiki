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
}
