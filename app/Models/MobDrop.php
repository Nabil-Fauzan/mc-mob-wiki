<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobDrop extends Model
{
    protected $fillable = [
        'mob_id',
        'item_name',
        'quantity',
        'chance',
        'rarity',
        'icon',
    ];

    public function mob()
    {
        return $this->belongsTo(Mob::class);
    }
}
