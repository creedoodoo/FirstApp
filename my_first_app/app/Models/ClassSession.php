<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $table = 'class_sessions';

    protected $fillable = [
        'class_id',
        'session_date',
        'status',
        'is_makeup',
        'submitted_at',
        'edited_at',
        'edited_by',
    ];

    protected $casts = [
        'session_date' => 'date',
        'is_makeup'    => 'boolean',
        'submitted_at' => 'datetime',
        'edited_at'    => 'datetime',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class, 'class_session_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
