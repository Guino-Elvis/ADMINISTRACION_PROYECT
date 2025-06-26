<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $fillable = ['project_id', 'provider', 'url', 'branch'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function commits()
    {
        return $this->hasMany(Commit::class);
    }

    public function pushes()
    {
        return $this->hasMany(Push::class);
    }
}
