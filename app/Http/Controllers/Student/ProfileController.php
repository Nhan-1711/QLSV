<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        // Pass both to be safe, though view likely uses $student
        return view('student.profile', compact('user', 'student'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && $user->avatar !== 'avatars/default.png') {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new
            $path = $request->file('avatar')->store('avatars', 'public');

            // Update User table
            $user->update(['avatar' => $path]);

            // Upsync: also update Student table if it exists
            if ($user->student) {
                // If student has a different old avatar, delete it too? 
                // Usually they should be in sync. Let's just update.
                $user->student->update(['avatar' => $path]);
            }
        }

        Activity::log('profile_update', 'Sinh viên đã thay đổi ảnh đại diện');
        return back()->with('success', 'Cập nhật ảnh đại diện thành công');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        Activity::log('profile_update', 'Sinh viên đã thay đổi mật khẩu');
        return back()->with('success', 'Đổi mật khẩu thành công');
    }
}
