<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username_hash',
        'password',
        'role',
        'is_approved',
        'trial_uses_left',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_approved'       => 'boolean',
            'trial_uses_left'   => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isApproved(): bool
    {
        return (bool) $this->is_approved;
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }
}
