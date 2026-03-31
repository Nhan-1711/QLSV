<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $type = request('type', 'all');
        $notifications = $user->notifications();

        if ($type === 'admin') {
            $notifications->where('data->sender_role', 'Admin');
        } elseif ($type === 'lecturer') {
            $notifications->where('data->sender_role', 'Giảng viên');
        }

        $notifications = $notifications->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('student.notifications.index', compact('notifications', 'type'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
}
