<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'session_date',
        'start_time',
        'end_time',
        'qr_code_token',
        'status',
        'note'
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
