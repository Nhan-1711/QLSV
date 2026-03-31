<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_code',
        'full_name',
        'gender',
        'dob',
        'phone',
        'email',
        'address',
        'class_id',
        'major_id',
        'faculty_id',
        'status',
        'avatar'
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function courseClasses()
    {
        return $this->belongsToMany(CourseClass::class, 'course_class_student')
            ->withPivot('enrolled_at', 'status')
            ->withTimestamps();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function calculateCumulativeGPA()
    {
        // Get all grades with GPA
        $grades = $this->grades()->whereNotNull('gpa')->with('courseClass.course')->get();

        if ($grades->isEmpty()) {
            return 0.0;
        }

        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($grades as $grade) {
            $course = $grade->courseClass->course;
            if ($course && $course->number_of_credits > 0) {
                $totalPoints += $grade->gpa * $course->number_of_credits;
                $totalCredits += $course->number_of_credits;
            }
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
    }

    public function checkAcademicStatus()
    {
        $gpa = $this->calculateCumulativeGPA();

        // Warning Threshold: GPA < 2.0
        if ($gpa < 2.0 && $gpa > 0) { // Only warn if they have a GPA (not 0.0 from no grades)
            // Send Email
            try {
                \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\AcademicWarningMail($this, $gpa));
            } catch (\Exception $e) {
                // Log error or ignore if mail fails
                \Illuminate\Support\Facades\Log::error("Failed to send warning email to {$this->email}: " . $e->getMessage());
            }
            return true; // Warned
        }
        return false;
    }
}
