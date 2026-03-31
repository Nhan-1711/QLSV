<?php

namespace App\Http\Controllers\Lecturer;

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
    private function checkOwnership(CourseClass $courseClass)
    {
        if ($courseClass->lecturer_id !== Auth::user()->lecturer->id) {
            abort(403, 'Báº¡n khÃ´ng cÃ³ quyá»n truy cáº­p lá»›p há»c pháº§n nÃ y.');
        }
    }

    public function index(CourseClass $courseClass)
    {
        $this->checkOwnership($courseClass);
        $sessions = $courseClass->attendanceSessions()->orderBy('session_date', 'desc')->get();
        // Reuse Admin view or create a new one. Reusing for now but we need to ensure routes in view are dynamic or we pass a flag.
        // Actually, the admin view uses named routes like 'admin.attendance.create_session'.
        // We should probably duplicate the view to 'lecturer.attendance.index' and update routes.
        return view('lecturer.attendance.index', compact('courseClass', 'sessions'));
    }

    public function createSession(CourseClass $courseClass)
    {
        $this->checkOwnership($courseClass);
        return view('lecturer.attendance.create_session', compact('courseClass'));
    }

    public function storeSession(Request $request, CourseClass $courseClass)
    {
        $this->checkOwnership($courseClass);
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

        foreach ($courseClass->students as $student) {
            Attendance::create([
                'course_class_id' => $courseClass->id,
                'student_id' => $student->id,
                'attendance_session_id' => $session->id,
                'status' => 'absent',
            ]);
        }

        return redirect()->route('lecturer.attendance.index', $courseClass)->with('success', 'Táº¡o buá»•i Ä‘iá»ƒm danh thÃ nh cÃ´ng');
    }

    public function showSession(CourseClass $courseClass, AttendanceSession $session)
    {
        $this->checkOwnership($courseClass);
        $session->load(['attendances.student']);
        $qrCode = QrCode::size(200)->generate(route('attendance.checkin', ['token' => $session->qr_code_token]));

        return view('lecturer.attendance.show_session', compact('courseClass', 'session', 'qrCode'));
    }

    public function updateAttendance(Request $request, CourseClass $courseClass, AttendanceSession $session)
    {
        $this->checkOwnership($courseClass);
        $data = $request->attendance;

        foreach ($data as $studentId => $status) {
            Attendance::where('attendance_session_id', $session->id)
                ->where('student_id', $studentId)
                ->update(['status' => $status]);
        }

                \App\Models\Activity::log('attendance_check', 'Giảng viên đã cập nhật điểm danh cho lớp ' . $courseClass->course->name . ' - ' . $courseClass->name);
        return back()->with('success', 'Cáº­p nháº­t Ä‘iá»ƒm danh thÃ nh cÃ´ng');
    }


    public function getAttendanceData(CourseClass $courseClass, AttendanceSession $session)
    {
        $this->checkOwnership($courseClass);

        // Check if session should be closed
        $now = now();
        // session_date is cast to Carbon, so format it to Y-m-d string first
        $endTime = \Carbon\Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->end_time);

        if ($session->status == 'open' && $now->greaterThan($endTime)) {
            $session->status = 'closed';
            $session->save();
        }

        $attendances = $session->attendances()->select('student_id', 'status')->get();

        return response()->json([
            'attendances' => $attendances,
            'status' => $session->status,
            'is_expired' => $now->greaterThan($endTime),
        ]);
    }
    public function refreshQr(CourseClass $courseClass, AttendanceSession $session)
    {
        $this->checkOwnership($courseClass);

        if ($session->status !== 'open') {
            return response()->json(['error' => 'Session closed'], 400);
        }

        // Generate new token
        $newToken = Str::random(32);
        $session->save();

        // Generate new QR Code
        $qrCode = QrCode::size(200)->generate(route('attendance.checkin', ['token' => $newToken]));

        return response()->json([
            'qr_code' => (string) $qrCode,
        ]);
    }

    public function statistics(CourseClass $courseClass)
    {
        $students = $courseClass->students;
        $sessions = $courseClass->attendanceSessions()->where('status', 'closed')->get();
        $totalSessions = $sessions->count();

        foreach ($students as $student) {
            $attendances = \App\Models\Attendance::whereIn('attendance_session_id', $sessions->pluck('id'))
                ->where('student_id', $student->id)
                ->get();

            $student->present_count = $attendances->where('status', 'present')->count();
            $student->late_count = $attendances->where('status', 'late')->count();
            $student->absent_count = $attendances->where('status', 'absent')->count();

            // If no attendance record exists for a closed session, it counts as absent
            $recordedSessions = $attendances->count();
            $student->absent_count += ($totalSessions - $recordedSessions);

            $student->absence_percentage = $totalSessions > 0 ? ($student->absent_count / $totalSessions) * 100 : 0;
        }

        return view('lecturer.attendance.statistics', compact('courseClass', 'students', 'totalSessions'));
    }

    public function sendBanEmail(CourseClass $courseClass, \App\Models\Student $student)
    {
        $this->checkOwnership($courseClass);

        // Calculate absences again to be sure
        $sessions = $courseClass->attendanceSessions()->where('status', 'closed')->get();
        $totalSessions = $sessions->count();

        $attendances = \App\Models\Attendance::whereIn('attendance_session_id', $sessions->pluck('id'))
            ->where('student_id', $student->id)
            ->get();

        $recordedSessions = $attendances->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $absentCount += ($totalSessions - $recordedSessions);

        if ($absentCount < 3) {
            return back()->with('error', 'Sinh viÃªn nÃ y chÆ°a Ä‘á»§ Ä‘iá»u kiá»‡n cáº¥m thi (váº¯ng < 3 buá»•i).');
        }

        \Illuminate\Support\Facades\Mail::to($student->email)->send(new \App\Mail\ExamBanMail($student, $courseClass, $absentCount));

        return back()->with('success', 'ÄÃ£ gá»­i email thÃ´ng bÃ¡o cáº¥m thi cho sinh viÃªn ' . $student->full_name);
    }

    public function sessionStatistics(Request $request, CourseClass $courseClass, AttendanceSession $session)
    {
        $this->checkOwnership($courseClass);

        // Calculate counts
        $stats = $session->attendances()
            ->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Default values
        $counts = [
            'present' => $stats['present'] ?? 0,
            'late' => $stats['late'] ?? 0,
            'excused' => $stats['excused'] ?? 0,
            'absent' => $stats['absent'] ?? 0,
        ];
        $counts['total'] = array_sum($counts);

        // Get list with filter
        $query = $session->attendances()->with('student.user');
        
        if ($request->has('status') && in_array($request->status, ['present', 'late', 'excused', 'absent'])) {
            $query->where('status', $request->status);
        }

        $attendances = $query->get();

        return view('lecturer.attendance.session_statistics', compact('courseClass', 'session', 'counts', 'attendances'));
    }
}