<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(CourseClass $courseClass)
    {
        $sessions = $courseClass->attendanceSessions()->orderBy('session_date', 'desc')->get();
        return view('admin.attendance.index', compact('courseClass', 'sessions'));
    }

    public function createSession(CourseClass $courseClass)
    {
        return view('admin.attendance.create_session', compact('courseClass'));
    }

    public function storeSession(Request $request, CourseClass $courseClass)
    {
        $request->validate([
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $session = AttendanceSession::create([
            'course_class_id' => $courseClass->id,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'qr_code_token' => Str::random(32),
            'status' => 'open',
        ]);

        // Initialize attendance records for all students
        foreach ($courseClass->students as $student) {
            Attendance::create([
                'course_class_id' => $courseClass->id,
                'student_id' => $student->id,
                'attendance_session_id' => $session->id,
                'status' => 'absent', // Default to absent
            ]);
        }

        return redirect()->route('admin.attendance.index', $courseClass)->with('success', 'Tạo buổi điểm danh thành công');
    }

    public function showSession(CourseClass $courseClass, AttendanceSession $session)
    {
        $session->load(['attendances.student']);
        // Generate QR Code
        $qrCode = QrCode::size(200)->generate(route('attendance.checkin', ['token' => $session->qr_code_token]));

        return view('admin.attendance.show_session', compact('courseClass', 'session', 'qrCode'));
    }

    public function updateAttendance(Request $request, CourseClass $courseClass, AttendanceSession $session)
    {
        $data = $request->attendance; // Array of [student_id => status]

        foreach ($data as $studentId => $status) {
            Attendance::where('attendance_session_id', $session->id)
                ->where('student_id', $studentId)
                ->update(['status' => $status]);
        }

        return back()->with('success', 'Cập nhật điểm danh thành công');
    }

    public function checkin($token)
    {
        // Auth check handled by middleware

        $user = Auth::user();
        if ($user->role !== 'student') {
            return redirect('/')->with('error', 'Chỉ sinh viên mới có thể điểm danh.');
        }

        $student = $user->student;
        if (!$student) {
            return redirect('/')->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        $session = AttendanceSession::where('qr_code_token', $token)->first();

        if (!$session) {
            return redirect()->route('student.dashboard')->with('error', 'Mã QR không hợp lệ.');
        }

        if ($session->status !== 'open') {
            return redirect()->route('student.dashboard')->with('error', 'Buổi điểm danh đã đóng.');
        }

        // Check if student is in this class
        $isEnrolled = $session->courseClass->students()->where('student_id', $student->id)->exists();
        if (!$isEnrolled) {
            return redirect()->route('student.dashboard')->with('error', 'Bạn không thuộc lớp học phần này.');
        }

        // Mark as present
        Attendance::updateOrCreate(
            [
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ],
            [
                'course_class_id' => $session->course_class_id,
                'status' => 'present',
            ]
        );

        return redirect()->route('student.dashboard')->with('success', 'Điểm danh thành công!');
    }
}
