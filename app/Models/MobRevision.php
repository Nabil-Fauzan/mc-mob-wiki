<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobRevision extends Model
{
    protected $fillable = ['mob_id', 'user_id', 'field', 'old_value', 'new_value'];

    public function mob()
    {
        return $this->belongsTo(Mob::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

