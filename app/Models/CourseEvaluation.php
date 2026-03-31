<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'student_id',
        'teaching_rating',
        'support_rating',
        'material_rating',
        'content',
        'is_anonymous'
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}