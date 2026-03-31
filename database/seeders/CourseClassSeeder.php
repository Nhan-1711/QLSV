<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\CourseClass;
use Carbon\Carbon;

class CourseClassSeeder extends Seeder
{
    public function run()
    {
        $courses = Course::all();
        $lecturers = Lecturer::all();
        $students = Student::with('class')->get();

        if ($courses->isEmpty() || $lecturers->isEmpty() || $students->isEmpty()) {
            return;
        }

        // Tracking schedules: [id => [day => [period => true]]]
        $lecturerSchedule = [];
        $classroomSchedule = []; // [room_name => [day => [period => true]]]
        $studentSchedule = [];

        $days = ['2', '3', '4', '5', '6', '7']; // Mon-Sat
        $rooms = [];
        for ($r = 101; $r <= 110; $r++) $rooms[] = "P$r"; // Limit rooms to force some contention/logic

        foreach ($courses as $course) {
            // Create 2 classes for each course
            for ($i = 1; $i <= 2; $i++) {
                $lecturer = $lecturers->random();
                $startDate = Carbon::now()->subMonths(rand(0, 4));
                $endDate = $startDate->copy()->addMonths(4);
                $duration = max(2, min(5, $course->number_of_credits));

                // Try to find a valid slot
                $attempts = 0;
                $slotFound = false;
                $selectedDay = null;
                $selectedPeriodFrom = null;
                $selectedPeriodTo = null;
                $selectedRoom = null;

                while ($attempts < 50 && !$slotFound) {
                    $attempts++;
                    $day = $days[array_rand($days)];
                    $session = rand(0, 1) ? 'morning' : 'afternoon';

                    if ($session === 'morning') {
                        $maxStart = 5 - $duration + 1;
                        $periodFrom = rand(1, $maxStart);
                    } else {
                        $maxStart = 10 - $duration + 1;
                        $periodFrom = rand(6, $maxStart);
                    }
                    $periodTo = $periodFrom + $duration - 1;

                    // Check Lecturer Availability
                    if ($this->isBusy($lecturerSchedule, $lecturer->id, $day, $periodFrom, $periodTo)) {
                        continue;
                    }

                    // Find a free room
                    $room = null;
                    shuffle($rooms);
                    foreach ($rooms as $r) {
                        if (!$this->isBusy($classroomSchedule, $r, $day, $periodFrom, $periodTo)) {
                            $room = $r;
                            break;
                        }
                    }

                    if ($room) {
                        $slotFound = true;
                        $selectedDay = $day;
                        $selectedPeriodFrom = $periodFrom;
                        $selectedPeriodTo = $periodTo;
                        $selectedRoom = $room;
                    }
                }

                if ($slotFound) {
                    // Mark schedules as busy
                    $this->markBusy($lecturerSchedule, $lecturer->id, $selectedDay, $selectedPeriodFrom, $selectedPeriodTo);
                    $this->markBusy($classroomSchedule, $selectedRoom, $selectedDay, $selectedPeriodFrom, $selectedPeriodTo);

                    $courseClass = CourseClass::create([
                        'course_id' => $course->id,
                        'lecturer_id' => $lecturer->id,
                        'classroom' => $selectedRoom,
                        'semester' => 1,
                        'school_year' => '2025-2026',
                        'day_of_week' => $selectedDay,
                        'period_from' => $selectedPeriodFrom,
                        'period_to' => $selectedPeriodTo,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'status' => $endDate->isPast() ? 'completed' : 'active',
                    ]);

                    // Enroll Students
                    $enrolledCount = 0;
                    $targetEnrollment = rand(10, 20);

                    // Filter students belonging to the same major as the course
                    $eligibleStudents = $students->filter(function ($student) use ($course) {
                        return $student->class && $student->class->major_id == $course->major_id;
                    });

                    // Shuffle eligible students to randomize enrollment
                    $shuffledStudents = $eligibleStudents->shuffle();

                    foreach ($shuffledStudents as $student) {
                        if ($enrolledCount >= $targetEnrollment) break;

                        if (!$this->isBusy($studentSchedule, $student->id, $selectedDay, $selectedPeriodFrom, $selectedPeriodTo)) {
                            $courseClass->students()->attach($student->id, ['enrolled_at' => now(), 'status' => 'enrolled']);
                            $this->markBusy($studentSchedule, $student->id, $selectedDay, $selectedPeriodFrom, $selectedPeriodTo);
                            $enrolledCount++;
                        }
                    }
                }
            }
        }
    }

    private function isBusy($schedule, $id, $day, $start, $end)
    {
        if (!isset($schedule[$id][$day])) return false;
        for ($p = $start; $p <= $end; $p++) {
            if (isset($schedule[$id][$day][$p])) return true;
        }
        return false;
    }

    private function markBusy(&$schedule, $id, $day, $start, $end)
    {
        for ($p = $start; $p <= $end; $p++) {
            $schedule[$id][$day][$p] = true;
        }
    }
}
