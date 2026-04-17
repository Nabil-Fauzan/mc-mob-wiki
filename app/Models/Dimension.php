<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    protected $fillable = ['name', 'description', 'color_theme'];

    public function biomes()
    {
        return $this->hasMany(Biome::class);
    }
}
