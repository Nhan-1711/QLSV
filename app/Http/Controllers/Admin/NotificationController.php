<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $sentNotifications = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('data->sender_id', auth()->id())
            ->where('data->sender_role', 'Admin')
            ->select('data', 'created_at', \Illuminate\Support\Facades\DB::raw('count(*) as receiver_count'))
            ->groupBy('data', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notifications.index', compact('sentNotifications'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:all_students,all_lecturers,specific_user',
            // Remove strict exists check for user_id since we now accept various identifiers
            'user_identifier' => 'required_if:recipient_type,specific_user|nullable|string',
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
            'Admin',
            $attachmentPath,
            $originalFileName,
            $sender->id
        );

        if ($request->recipient_type === 'all_students') {
            $users = User::where('role', 'student')->get();
            if ($users->isEmpty()) {
                return back()->with('error', 'Không tìm thấy sinh viên nào trong hệ thống.');
            }
            \Illuminate\Support\Facades\Log::info('Sending to all students. Count: ' . $users->count());
            Notification::send($users, $notification);
        } elseif ($request->recipient_type === 'all_lecturers') {
            $users = User::where('role', 'lecturer')->get();
            if ($users->isEmpty()) {
                return back()->with('error', 'Không tìm thấy giảng viên nào trong hệ thống.');
            }
            \Illuminate\Support\Facades\Log::info('Sending to all lecturers. Count: ' . $users->count());
            Notification::send($users, $notification);
        } elseif ($request->recipient_type === 'specific_user') {
            $identifier = $request->user_identifier;
            $user = null;

            // 1. Try by ID
            if (is_numeric($identifier)) {
                $user = User::find($identifier);
            }

            // 2. Try by Email
            if (!$user) {
                $user = User::where('email', $identifier)->first();
            }

            // 3. Try by Student Code
            if (!$user) {
                $student = \App\Models\Student::where('student_code', $identifier)->first();
                if ($student) {
                    $user = $student->user;
                }
            }

            // 4. Try by Lecturer Code
            if (!$user) {
                $lecturer = \App\Models\Lecturer::where('lecturer_code', $identifier)->first();
                if ($lecturer) {
                    $user = $lecturer->user;
                }
            }

            if ($user) {
                \Illuminate\Support\Facades\Log::info('Sending to user: ' . $user->id);
                $user->notify($notification);
            } else {
                \Illuminate\Support\Facades\Log::warning('User not found for identifier: ' . $identifier);
                return back()->with('error', 'Không tìm thấy người dùng (ID, Email, Mã SV hoặc Mã GV không tồn tại).')->withInput();
            }
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Thông báo đã được gửi thành công!');
    }
}
