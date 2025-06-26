<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    protected $fillable = ['repository_id', 'user_id', 'commit_hash', 'message', 'committed_at'];

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
