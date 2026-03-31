<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseClass;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $courseClasses = CourseClass::all();

        foreach ($courseClasses as $class) {
            // Create 5 attendance sessions for each class
            for ($i = 0; $i < 5; $i++) {
                $sessionDate = Carbon::parse($class->start_date)->addWeeks($i);
                if ($sessionDate->isFuture()) continue;

                $session = AttendanceSession::create([
                    'course_class_id' => $class->id,
                    'session_date' => $sessionDate,
                    'start_time' => '07:00:00',
                    'end_time' => '11:00:00',
                    'qr_code_token' => Str::random(32),
                    'status' => 'closed',
                ]);

                // Mark attendance for enrolled students
                foreach ($class->students as $student) {
                    Attendance::create([
                        'course_class_id' => $class->id,
                        'student_id' => $student->id,
                        'attendance_session_id' => $session->id,
                        'status' => fake()->randomElement(['present', 'present', 'present', 'absent', 'late']),
                    ]);
                }
            }
        }
    }
}
