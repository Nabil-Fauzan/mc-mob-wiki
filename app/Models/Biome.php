<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biome extends Model
{
    protected $fillable = ['name', 'dimension_id', 'description', 'image'];

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function mobs()
    {
        return $this->hasMany(Mob::class);
    }
}
