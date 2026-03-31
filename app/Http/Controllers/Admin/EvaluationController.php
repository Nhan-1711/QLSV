<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseEvaluation;
use App\Models\CourseClass;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseEvaluation::with(['student.user', 'courseClass.course', 'courseClass.lecturer.user'])
            ->latest();

        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('course_class_id', $request->class_id);
        }

        $evaluations = $query->paginate(15)->appends($request->all());
        
        // Optimizing dropdown query: select only needed fields
        $classes = CourseClass::with('course')
            ->orderBy('created_at', 'desc')
            ->get();

         return view('admin.evaluations.index', compact('evaluations', 'classes'));
    }
}