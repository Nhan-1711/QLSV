<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Lecturer;
use App\Models\CourseClass;
use App\Models\Faculty;
use App\Models\Major;
use App\Models\Classes; // Lớp sinh hoạt

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $faculties_count = Faculty::count();
        $majors_count = Major::count();
        $admin_classes_count = Classes::count(); // Lớp sinh hoạt
        
        $lecturers_count = Lecturer::count();
        $course_classes_count = CourseClass::count(); // Lớp học phần

        // Activities - Limit 5 as requested
        $recent_activities = Activity::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'faculties_count', 
            'majors_count', 
            'admin_classes_count', 
            'lecturers_count', 
            'course_classes_count', 
            'recent_activities'
        ));
    }
}