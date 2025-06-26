<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relación 1 a * inversa
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users');
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function repositories()
    {
        return $this->hasMany(Repository::class);
    }
}
