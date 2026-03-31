<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\GradesExport;
use App\Imports\GradesImport;
use Maatwebsite\Excel\Facades\Excel;

class GradeController extends Controller
{
    private function checkOwnership(CourseClass $courseClass)
    {
        if ($courseClass->lecturer_id !== Auth::user()->lecturer->id) {
            abort(403, 'Bạn không có quyền truy cập lớp học phần này.');
        }
    }

    public function index(CourseClass $courseClass)
    {
        $this->checkOwnership($courseClass);
        $courseClass->load(['students', 'grades']);

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

        return view('lecturer.grades.index', compact('courseClass', 'grades'));
    }

    public function update(Request $request, CourseClass $courseClass)
    {
        $this->checkOwnership($courseClass);
        $data = $request->grades;

        foreach ($data as $studentId => $scores) {
            $grade = Grade::updateOrCreate(
                [
                    'course_class_id' => $courseClass->id,
                    'student_id' => $studentId,
                ],
                [
                    'attendance_score' => $scores['attendance_score'] ?? 0,
                    'midterm_score' => $scores['midterm_score'] ?? 0,
                    'final_score' => $scores['final_score'] ?? 0,
                ]
            );
            // Trigger calculation explicitly if needed, though boot() handles saving
            // $grade->save(); 
        }

        \App\Models\Activity::log('grade_update', 'Giảng viên đã cập nhật điểm số cho lớp ' . $courseClass->course->name . ' - ' . $courseClass->name);
        return back()->with('success', 'Cập nhật điểm thành công');
    }
    public function export(CourseClass $courseClass)
    {
        $this->checkOwnership($courseClass);
        return Excel::download(new GradesExport($courseClass->id), 'bang_diem_' . $courseClass->course->code . '.xlsx');
    }

    public function import(Request $request, CourseClass $courseClass)
    {
        $this->checkOwnership($courseClass);
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new GradesImport($courseClass->id), $request->file('file'));

        return back()->with('success', 'Nhập điểm từ file Excel thành công!');
    }

    public function sendWarning(Grade $grade)
    {
        // Check ownership via course class
        $this->checkOwnership($grade->courseClass);

        if ($grade->gpa >= 2.0) {
            return back()->with('error', 'Sinh viên này có GPA >= 2.0, không cần gửi cảnh báo.');
        }

        if (!$grade->student->email) {
            return back()->with('error', 'Sinh viên không có email.');
        }

        try {
            \Illuminate\Support\Facades\Mail::to($grade->student->email)->send(new \App\Mail\CourseWarningMail($grade));
            return back()->with('success', 'Đã gửi email cảnh báo cho sinh viên ' . $grade->student->full_name);
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi gửi mail: ' . $e->getMessage());
        }
    }

    public function lock(CourseClass $courseClass)
    {
        $courseClass->update(['is_grades_locked' => true]);
        return back()->with('success', 'Đã chốt bảng điểm thành công. Không thể chỉnh sửa thêm.');
    }
}
