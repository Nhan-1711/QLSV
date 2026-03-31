<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseClass;
use App\Models\CourseEvaluation;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function create($classId)
    {
        $student = Auth::user()->student;
        $courseClass = CourseClass::findOrFail($classId);



        // Check if already evaluated
        $existing = CourseEvaluation::where('course_class_id', $classId)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Bạn đã đánh giá học phần này rồi.');
        }

        // Check completion condition (e.g., has Final Score)
        $grade = \App\Models\Grade::where('course_class_id', $classId)
            ->where('student_id', $student->id)
            ->first();

        // Uncomment strict check if desired
        /*
        if (!$grade || $grade->final_score === null) {
            return back()->with('error', 'Bạn chưa hoàn thành học phần này (chưa có điểm tổng kết).');
        }
        */

        return view('student.evaluations.create', compact('courseClass'));
    }

    public function store(Request $request, $classId)
    {
        $request->validate([
            'teaching_rating' => 'required|integer|min:1|max:5',
            'support_rating' => 'required|integer|min:1|max:5',
            'material_rating' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|max:1000',
        ]);


        if ($request->content) {
            $blacklist = config('badwords.blacklist', []);
            $lowerContent = mb_strtolower($request->content, 'UTF-8');

            foreach ($blacklist as $word) {

                if (str_contains($lowerContent, $word)) {
                    return back()->withInput()->with('error', 'Nội dung đánh giá chứa từ ngữ không phù hợp: "' . $word . '". Vui lòng sử dụng ngôn từ lịch sự.');
                }
            }
        }

        $student = Auth::user()->student;

        CourseEvaluation::create([
            'course_class_id' => $classId,
            'student_id' => $student->id,
            'teaching_rating' => $request->teaching_rating,
            'support_rating' => $request->support_rating,
            'material_rating' => $request->material_rating,
            'content' => $request->content,
            'is_anonymous' => $request->has('is_anonymous')
        ]);


        $courseClass = \App\Models\CourseClass::with('course')->find($classId);
        $className = $courseClass->name ? ' - ' . $courseClass->name : '';
        Activity::log('evaluation_submit', 'Sinh viên đã gửi đánh giá cho lớp ' . $courseClass->course->name . $className);

        return redirect()->route('student.course_classes.index')->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    }
}
