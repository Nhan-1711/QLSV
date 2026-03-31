<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseClass;
use App\Models\AttendanceSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        if (!$lecturer) {
            return redirect()->route('profile.edit')->with('error', 'Vui lòng cập nhật thông tin giảng viên.');
        }

        // Get active classes for this lecturer
        $activeClasses = CourseClass::where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->with('course')
            ->get();

        $notifications = auth()->user()->notifications()->latest()->take(3)->get();

        // Get today's schedule
        $today = now();
        $dayOfWeekMap = [
            0 => 'CN',
            1 => '2',
            2 => '3',
            3 => '4',
            4 => '5',
            5 => '6',
            6 => '7'
        ];
        $currentDay = $dayOfWeekMap[$today->dayOfWeek];

        $todayClasses = CourseClass::where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->where('day_of_week', $currentDay)
            ->orderBy('period_from')
            ->get();

        return view('lecturer.dashboard', compact('lecturer', 'activeClasses', 'todayClasses', 'notifications'));
    }

    public function schedule()
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        $classes = CourseClass::where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->orderBy('day_of_week')
            ->orderBy('period_from')
            ->get();

        return view('lecturer.schedule', compact('classes'));
    }

    public function classes()
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        $classes = CourseClass::where('lecturer_id', $lecturer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('lecturer.classes.index', compact('classes'));
    }

    /* public function show($id) DEPRECATED */ public function deprecated_show($id)
    {
        $lecturer = \Illuminate\Support\Facades\Auth::user()->lecturer;
        $courseClass = \App\Models\CourseClass::where('id', $id)
            ->where('lecturer_id', $lecturer->id)
            ->with(['course', 'students'])
            ->firstOrFail();

        return view('lecturer.classes.show', compact('courseClass'));
    }
}
