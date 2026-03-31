<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseClassController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        // Fetch all classes with relationships
        $activeClasses = $student->courseClasses()
            ->wherePivot('status', 'enrolled')
            ->where('course_classes.status', 'active')
            ->with('course', 'lecturer')
            ->orderBy('day_of_week') // Sort by schedule
            ->get();

        $completedClasses = $student->courseClasses()
            ->wherePivot('status', 'enrolled')
            ->where(function($q) { $q->where('course_classes.status', 'finished')->orWhere('end_date', '<', now()); })
            ->with('course', 'lecturer')
            ->orderBy('end_date', 'desc')
            ->get();

        return view('student.course_classes.index', compact('activeClasses', 'completedClasses'));
    }

    public function show($id)
    {
        $student = Auth::user()->student;
        $courseClass = $student->courseClasses()
            ->where('course_classes.id', $id)
            ->with(['course', 'lecturer.user'])
            ->firstOrFail();

        // Get attendance history
        $attendances = \App\Models\Attendance::where('course_class_id', $id)
            ->where('student_id', $student->id)
            ->with('attendanceSession')
            ->orderBy('created_at', 'desc') // Approximation, ideally join session date
            ->get();
            
        // Get Grade if exists
        $grade = \App\Models\Grade::where('course_class_id', $id)
            ->where('student_id', $student->id)
            ->first();

        return view('student.course_classes.show', compact('courseClass', 'attendances', 'grade'));
    }
}