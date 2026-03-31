<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CourseClass;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $receivedNotifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10, ['*'], 'received_page');

        $sentNotifications = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('data->sender_id', $user->id)
            ->where('data->sender_role', 'Giảng viên')
            ->select('data', 'created_at', \Illuminate\Support\Facades\DB::raw('count(*) as receiver_count'))
            ->groupBy('data', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'sent_page');

        return view('lecturer.notifications.index', compact('receivedNotifications', 'sentNotifications'));
    }

    public function create()
    {
        $lecturer = auth()->user()->lecturer;
        $classes = $lecturer ? $lecturer->courseClasses()->where('status', 'active')->get() : collect();

        return view('lecturer.notifications.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',

            'message' => 'required|string',
            'recipient_type' => 'required|in:my_classes,specific_class,specific_student',
            'class_id' => 'required_if:recipient_type,specific_class|exists:course_classes,id',
            'student_code' => 'required_if:recipient_type,specific_student|nullable|string',
            'attachment' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $sender = auth()->user();

        $attachmentPath = null;
        $originalFileName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('attachments', 'public');
            $originalFileName = $file->getClientOriginalName();
        }

        $notification = new GeneralNotification(
            $request->title,
            $request->message,
            $sender->name,
            'Giảng viên',
            $attachmentPath,
            $originalFileName,
            $sender->id
        );

        if ($request->recipient_type === 'my_classes') {
            // Notify all students in all active classes taught by this lecturer
            $lecturer = $sender->lecturer;
            $classes = $lecturer->courseClasses()->where('status', 'active')->with('students.user')->get();

            $students = collect();
            foreach ($classes as $class) {
                foreach ($class->students as $student) {
                    if ($student->user) {
                        $students->push($student->user);
                    }
                }
            }
            $students = $students->unique('id');

            if ($students->isEmpty()) {
                return back()->with('error', 'Không tìm thấy sinh viên nÃ o trong các lớp của bạn.');
            }

            \Illuminate\Support\Facades\Log::info('Lecturer sending to my classes. Count: ' . $students->count());
            Notification::send($students, $notification);
        } elseif ($request->recipient_type === 'specific_class') {
            $class = CourseClass::with('students.user')->find($request->class_id);
            // Check ownership
            if ($class->lecturer_id !== $sender->lecturer->id) {
                return back()->with('error', 'Bạn không dạy lớp này.');
            }

            $students = collect();
            foreach ($class->students as $student) {
                if ($student->user) {
                    $students->push($student->user);
                }
            }

            if ($students->isEmpty()) {
                return back()->with('error', 'Lớp này chưa có sinh viên nào.');
            }

            \Illuminate\Support\Facades\Log::info('Lecturer sending to specific class ' . $class->id . '. Count: ' . $students->count());
            Notification::send($students, $notification);
        } elseif ($request->recipient_type === 'specific_student') {
            $code = trim($request->student_code);
            $student = \App\Models\Student::where('student_code', $code)->first();
            if ($student && $student->user) {
                \Illuminate\Support\Facades\Log::info('Lecturer sending to student: ' . $student->student_code);
                $student->user->notify($notification);
            } else {
                \Illuminate\Support\Facades\Log::warning('Student not found: ' . $code);
                return back()->with('error', 'Không tìm thấy sinh viên với mã: ' . $code);
            }
        }

        \App\Models\Activity::log('notification_create', 'Giảng viên đã đăng thông báo mới: ' . $request->title);
        return redirect()->route('lecturer.notifications.index')->with('success', 'Thông báo đã được gửi thành công!');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
}
