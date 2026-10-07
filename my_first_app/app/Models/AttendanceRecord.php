<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendance_records';

    protected $fillable = [
        'class_session_id',
        'student_id',
        'status',
        'time_in',
        'left_early',
        'left_at',
        'remarks',
        'marked_at',
        'edited_at',
        'edited_by',
    ];

    protected $casts = [
        'time_in'    => 'datetime',
        'left_early' => 'boolean',
        'left_at'    => 'datetime',
        'marked_at'  => 'datetime',
        'edited_at'  => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'class_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function isUnmarked(): bool
    {
        return is_null($this->status);
    }
}
