<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'mob_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mob()
    {
        return $this->belongsTo(Mob::class);
    }

    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }
}
