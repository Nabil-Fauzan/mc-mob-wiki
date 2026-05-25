<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemChangelog extends Model
{
    protected $fillable = [
        'version',
        'title',
        'ai_summary',
        'raw_commits',
    ];
}
