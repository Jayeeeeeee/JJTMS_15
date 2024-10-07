<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'is_completed',
    ];

    // Task belongs to a Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Task is assigned to a User
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
