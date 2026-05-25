<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'criteria_type', 'criteria_value', 'reward_title'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot('unlocked_at')
                    ->withTimestamps();
    }
}

