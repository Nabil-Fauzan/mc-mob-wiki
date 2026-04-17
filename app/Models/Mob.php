<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mob extends Model
{
    protected $fillable = [
        'name',
        'image',
        'category_id',
        'biome_id',
        'health',
        'damage',
        'drops',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function biome()
    {
        return $this->belongsTo(Biome::class);
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
