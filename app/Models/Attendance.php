<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'student_id',
        'attendance_session_id',
        'status',
        'note',
    ];

    protected $casts = [
        // 'date' => 'date', // Removed
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }
}
