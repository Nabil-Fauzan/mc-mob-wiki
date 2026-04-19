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
    ];

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
