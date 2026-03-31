<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }
        $courses = $query->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:courses,code',
            'name' => 'required',
            'number_of_credits' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        Course::create($request->all());
        return redirect()->route('admin.courses.index')->with('success', 'Thêm học phần thành công');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|unique:courses,code,' . $course->id,
            'name' => 'required',
            'number_of_credits' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $course->update($request->all());
        return redirect()->route('admin.courses.index')->with('success', 'Cập nhật học phần thành công');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Xóa học phần thành công');
    }
}
