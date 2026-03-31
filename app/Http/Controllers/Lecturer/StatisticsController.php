<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseClass;
use App\Models\Student;
use App\Models\Grade;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Mail;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = auth()->user()->lecturer;
        if (!$lecturer) {
            return redirect()->route('home')->with('error', 'Bạn không phải là giảng viên.');
        }

        $classId = $request->input('class_id');

        // Get all active classes for this lecturer
        $classes = $lecturer->courseClasses()->where('status', 'active')->get();

        $selectedClass = null;
        $atRiskStudents = collect();

        if ($classId) {
            $selectedClass = $classes->find($classId);
        } else {
            // Default to first class or show all? 
            // If showing all, we need to iterate all classes. 
            // Let's allow "All Classes" mode if no ID, or force selection.
            // For better UX, let's load ALL at-risk students from ALL classes if no filter.
        }

        // Logic to find at-risk students
        // We iterate through relevant classes (either all or selected)
        $targetClasses = $classId ? collect([$selectedClass]) : $classes;

        foreach ($targetClasses as $class) {
            if (!$class) continue;

            $class->load(['students.grades', 'students.user', 'attendanceSessions']);

            // Total sessions so far (past or today)
            $totalSessions = $class->attendanceSessions()->where('session_date', '<=', now())->count();

            foreach ($class->students as $student) {
                $reasons = [];

                // 1. Check Grade < 5 (Course Average)
                $grade = $student->grades->where('course_class_id', $class->id)->first();
                $courseScore = $grade ? $grade->total_score : null; // 10-scale

                if ($courseScore !== null && $courseScore < 5.0) {
                    $reasons[] = "Điểm môn học thấp: " . $courseScore;
                }

                // 2. Check GPA < 2.0 (Cumulative)
                // Note: This calculateCumulativeGPA might be heavy if called many times. 
                // Optimized approach: calculate only if needed or rely on stored 'gpa' in grades if it represents cumulative (it doesn't).
                // We'll trust the model calculation for now.
                $gpa = $student->calculateCumulativeGPA();
                if ($gpa < 2.0) {
                    $reasons[] = "GPA tích lũy thấp: " . $gpa;
                }

                // 3. Check Attendance < 70%
                if ($totalSessions > 0) {
                    // Count present/late for this student in this class
                    $attended = \App\Models\Attendance::where('course_class_id', $class->id)
                        ->where('student_id', $student->id)
                        ->whereIn('status', ['present', 'late'])
                        ->count();

                    $percentage = ($attended / $totalSessions) * 100;
                    if ($percentage < 70) {
                        $reasons[] = "Chuyên cần thấp: " . round($percentage, 1) . "% (" . $attended . "/" . $totalSessions . ")";
                    }
                }

                if (!empty($reasons)) {
                    $student->risk_reasons = $reasons;
                    $student->course_class_name = $class->course->name . ' - ' . $class->name; // Attach class info
                    $student->course_class_id = $class->id;
                    $atRiskStudents->push($student);
                }
            }
        }

        // Pagination manually if list is huge? For now, simple collection.

        return view('lecturer.statistics.index', compact('classes', 'atRiskStudents', 'selectedClass'));
    }

    public function sendWarning(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'type' => 'required|in:email,notification',
            'message' => 'required|string',
            'class_id' => 'required' // To context
        ]);

        $student = Student::with('user')->findOrFail($request->student_id);

        if ($request->type === 'email') {
            if ($student->email) {
                try {
                    Mail::raw($request->message, function ($message) use ($student) {
                        $message->to($student->email)
                            ->subject('Cảnh báo học tập / Academic Warning');
                    });
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to send warning email: " . $e->getMessage());
                    // We don't fail the request, just log it.
                }
            }
        } elseif ($request->type === 'notification') {
            $notification = new GeneralNotification(
                'Cảnh báo học tập',
                $request->message,
                auth()->user()->name,
                'Giảng viên',
                null,
                null,
                auth()->user()->id
            );
            if ($student->user) {
                $student->user->notify($notification);
            }
        }

        return response()->json(['success' => true]);
    }
}
