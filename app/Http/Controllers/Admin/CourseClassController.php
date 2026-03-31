<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseClass::with(['course', 'lecturer']);
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('course', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%");
            });
        }
        $courseClasses = $query->paginate(10);
        return view('admin.course_classes.index', compact('courseClasses'));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        $lecturers = Lecturer::where('status', 'working')->get();
        return view('admin.course_classes.create', compact('courses', 'lecturers'));
    }

    private function checkConflict($field, $value, $day, $start, $end, $semester, $year, $excludeId = null)
    {
        $query = CourseClass::where($field, $value)
            ->where('day_of_week', $day)
            ->where('semester', $semester)
            ->where('school_year', $year)
            ->where('status', 'active')
            ->where(function ($q) use ($start, $end) {
                $q->where(function ($q2) use ($start, $end) {
                    $q2->where('period_from', '<=', $end)
                        ->where('period_to', '>=', $start);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'classroom' => 'required',
            'semester' => 'required',
            'school_year' => 'required',
            'day_of_week' => 'required',
            'period_from' => 'required|integer',
            'period_to' => 'required|integer|gte:period_from',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        // Check Lecturer Conflict
        if ($this->checkConflict('lecturer_id', $request->lecturer_id, $request->day_of_week, $request->period_from, $request->period_to, $request->semester, $request->school_year)) {
            return back()->withInput()->withErrors(['lecturer_id' => 'Giảng viên đã có lịch dạy vào thời gian này.']);
        }

        // Check Classroom Conflict
        if ($this->checkConflict('classroom', $request->classroom, $request->day_of_week, $request->period_from, $request->period_to, $request->semester, $request->school_year)) {
            return back()->withInput()->withErrors(['classroom' => 'Phòng học đã có lớp học vào thời gian này.']);
        }

        CourseClass::create($request->all());
        return redirect()->route('admin.course_classes.index')->with('success', 'Mở lớp học phần thành công');
    }

    public function show(CourseClass $courseClass)
    {
        $courseClass->load(['course', 'lecturer', 'students.class']);
        return view('admin.course_classes.show', compact('courseClass'));
    }

    public function edit(CourseClass $courseClass)
    {
        $courses = Course::where('status', 'active')->get();
        $lecturers = Lecturer::where('status', 'working')->get();
        return view('admin.course_classes.edit', compact('courseClass', 'courses', 'lecturers'));
    }

    public function update(Request $request, CourseClass $courseClass)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'classroom' => 'required',
            'semester' => 'required',
            'school_year' => 'required',
            'day_of_week' => 'required',
            'period_from' => 'required|integer',
            'period_to' => 'required|integer|gte:period_from',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        // Check Lecturer Conflict
        if ($this->checkConflict('lecturer_id', $request->lecturer_id, $request->day_of_week, $request->period_from, $request->period_to, $request->semester, $request->school_year, $courseClass->id)) {
            return back()->withInput()->withErrors(['lecturer_id' => 'Giảng viên đã có lịch dạy vào thời gian này.']);
        }

        // Check Classroom Conflict
        if ($this->checkConflict('classroom', $request->classroom, $request->day_of_week, $request->period_from, $request->period_to, $request->semester, $request->school_year, $courseClass->id)) {
            return back()->withInput()->withErrors(['classroom' => 'Phòng học đã có lớp học vào thời gian này.']);
        }

        $courseClass->update($request->all());
        return redirect()->route('admin.course_classes.index')->with('success', 'Cập nhật lớp học phần thành công');
    }

    public function destroy(CourseClass $courseClass)
    {
        $courseClass->delete();
        return redirect()->route('admin.course_classes.index')->with('success', 'Xóa lớp học phần thành công');
    }

    public function addStudent(Request $request, CourseClass $courseClass)
    {
        $request->validate([
            'student_code' => 'required|exists:students,student_code',
        ]);

        $student = Student::where('student_code', $request->student_code)->first();

        // Check if student is already enrolled
        if ($courseClass->students()->where('student_id', $student->id)->exists()) {
            return back()->with('error', 'Sinh viên này đã có trong lớp');
        }

        // Check Student Schedule Conflict
        // Find any active class this student is enrolled in that overlaps with the current class
        $conflict = $student->courseClasses()
            ->wherePivot('status', 'enrolled')
            ->where('course_classes.status', 'active')
            ->where('semester', $courseClass->semester)
            ->where('school_year', $courseClass->school_year)
            ->where('day_of_week', $courseClass->day_of_week)
            ->where(function ($q) use ($courseClass) {
                $q->where('period_from', '<=', $courseClass->period_to)
                    ->where('period_to', '>=', $courseClass->period_from);
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Sinh viên bị trùng lịch học với một lớp khác.');
        }

        $courseClass->students()->attach($student->id, ['enrolled_at' => now(), 'status' => 'enrolled']);
        return back()->with('success', 'Thêm sinh viên thành công');
    }

    public function removeStudent(CourseClass $courseClass, Student $student)
    {
        $courseClass->students()->detach($student->id);
        return back()->with('success', 'Xóa sinh viên khỏi lớp thành công');
    }
}
