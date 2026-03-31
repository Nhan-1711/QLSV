<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'lecturer_id',
        'classroom',
        'semester',
        'school_year',
        'day_of_week',
        'period_from',
        'period_to',
        'start_date',
        'end_date',
        'status',
        'is_grades_locked'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'period_from' => 'integer',
        'period_to' => 'integer',
        'is_grades_locked' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_class_student')
            ->withPivot('enrolled_at', 'status')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function evaluations()
    {
        return $this->hasMany(CourseEvaluation::class);
    }
}