<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'status',
    ];

    // Project belongs to a User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Project has many Tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
