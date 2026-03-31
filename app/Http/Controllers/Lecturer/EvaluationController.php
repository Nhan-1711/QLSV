<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseClass;
use App\Models\CourseEvaluation;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index($classId)
    {
        $lecturer = Auth::user()->lecturer;
        $courseClass = CourseClass::findOrFail($classId);

        // Check ownership
        if ($courseClass->lecturer_id !== $lecturer->id) {
            abort(403, 'Unauthorized action.');
        }

        $evaluations = CourseEvaluation::where('course_class_id', $classId)
            ->latest()
            ->paginate(20);

        // Stats
        $stats = [
            'teaching' => round($evaluations->avg('teaching_rating'), 1),
            'support' => round($evaluations->avg('support_rating'), 1),
            'material' => round($evaluations->avg('material_rating'), 1),
            'count' => $evaluations->total(),
            'overall' => 0
        ];
        
        if ($stats['count'] > 0) {
            $stats['overall'] = round(($stats['teaching'] + $stats['support'] + $stats['material']) / 3, 1);
        }

        return view('lecturer.evaluations.index', compact('courseClass', 'evaluations', 'stats'));
    }

    public function dashboard()
    {
        $lecturer = \Illuminate\Support\Facades\Auth::user()->lecturer;
        $classes = \App\Models\CourseClass::where('lecturer_id', $lecturer->id)
            ->with('course')
            // Option: withCount('evaluations') if relationship exists
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('lecturer.evaluations.dashboard', compact('classes'));
    }
}