<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobContribution extends Model
{
    protected $fillable = ['mob_id', 'user_id', 'field', 'proposed_value', 'status', 'admin_id', 'ai_assessment', 'ai_trust_score'];

    public function mob()
    {
        return $this->belongsTo(Mob::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

