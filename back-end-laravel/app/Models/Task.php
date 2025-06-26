<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id', 'assigned_to', 'title', 'description', 'status', 'due_date'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function sprints()
    {
        return $this->belongsToMany(Sprint::class, 'sprint_task');
    }
}
