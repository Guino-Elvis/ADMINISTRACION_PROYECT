<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
     protected $fillable = ['repository_id', 'user_id', 'push_id', 'pushed_at', 'commits'];

    protected $casts = [
        'commits' => 'array',
    ];

    public function repository() {
        return $this->belongsTo(Repository::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
