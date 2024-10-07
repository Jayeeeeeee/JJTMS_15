<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Add role_id
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // User belongs to a Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // User has many Projects (if Admin or Team Leader)
    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    // User has many Tasks
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
}
