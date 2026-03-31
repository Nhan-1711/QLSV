<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseClass;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    public function run()
    {
        $courseClasses = CourseClass::all();

        foreach ($courseClasses as $class) {
            foreach ($class->students as $student) {
                $attendanceScore = rand(5, 10);
                $midtermScore = rand(4, 10);
                $finalScore = rand(4, 10);
                Grade::create([
                    'course_class_id' => $class->id,
                    'student_id' => $student->id,
                    'attendance_score' => $attendanceScore,
                    'midterm_score' => $midtermScore,
                    'final_score' => $finalScore,
                    'status' => 'active',
                ]);
            }
        }
    }
}
