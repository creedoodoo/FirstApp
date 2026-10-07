<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'section',
        'day_of_week',
        'start_time',
        'end_time',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'day_of_week' => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function sessions()
    {
        return $this->hasMany(ClassSession::class, 'class_id');
    }

    public function getDayNameAttribute(): string
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }

    public function getFormattedTimeAttribute(): string
    {
        $start = date('g:i A', strtotime($this->start_time));
        $end   = date('g:i A', strtotime($this->end_time));
        return "{$start} - {$end}";
    }
}
