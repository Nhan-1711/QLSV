<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Grade;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GradeController extends Controller
{
    public function index(CourseClass $courseClass)
    {
        $courseClass->load(['students', 'grades']);

        // Ensure grade records exist for all students
        foreach ($courseClass->students as $student) {
            Grade::firstOrCreate(
                [
                    'course_class_id' => $courseClass->id,
                    'student_id' => $student->id,
                ],
                [
                    'attendance_score' => 0,
                    'midterm_score' => 0,
                    'final_score' => 0,
                    'total_score' => 0,
                    'status' => 'active',
                ]
            );
        }

        $grades = Grade::where('course_class_id', $courseClass->id)
            ->with('student')
            ->get();

        return view('admin.grades.index', compact('courseClass', 'grades'));
    }

    public function update(Request $request, CourseClass $courseClass)
    {
        $data = $request->grades; // Array of [student_id => [attendance, midterm, final]]

        foreach ($data as $studentId => $scores) {
            $attendance = $scores['attendance_score'] ?? 0;
            $midterm = $scores['midterm_score'] ?? 0;
            $final = $scores['final_score'] ?? 0;

            $grade = Grade::firstOrNew([
                'course_class_id' => $courseClass->id,
                'student_id' => $studentId,
            ]);

            $grade->attendance_score = $attendance;
            $grade->midterm_score = $midterm;
            $grade->final_score = $final;

            // Calculate Total & GPA
            $grade->calculateTotal();
            $grade->save();
        }

        return back()->with('success', 'Cập nhật điểm thành công');
    }

    public function export(CourseClass $courseClass)
    {
        return Excel::download(new \App\Exports\GradesExport($courseClass->id), 'bang_diem_' . $courseClass->course->code . '.xlsx');
    }

    public function import(Request $request, CourseClass $courseClass)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new \App\Imports\GradesImport($courseClass->id), $request->file('file'));

        return back()->with('success', 'Nhập điểm từ file Excel thành công!');
    }

    public function sendWarning(Grade $grade)
    {
        if ($grade->gpa >= 2.0) {
            return back()->with('error', 'Sinh viên này có GPA >= 2.0, không cần gửi cảnh báo.');
        }

        if (!$grade->student->email) {
            return back()->with('error', 'Sinh viên không có email.');
        }

        try {
            \Illuminate\Support\Facades\Mail::to($grade->student->email)->send(new \App\Mail\CourseWarningMail($grade));
            return back()->with('success', 'Đã gửi email cảnh báo cho sinh viên ' . $grade->student->full_name);
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi gửi mail: ' . $e->getMessage());
        }
    }
}
