<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biome extends Model
{
    protected $fillable = ['name', 'dimension_id', 'parent_id', 'description', 'image'];

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function mobs()
    {
        return $this->belongsToMany(Mob::class)->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(Biome::class, 'parent_id');
    }

    public function subBiomes()
    {
        return $this->hasMany(Biome::class, 'parent_id');
    }
}
