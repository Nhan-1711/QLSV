<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Major;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = Classes::with('major');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }
        if ($request->has('major_id') && $request->major_id != '') {
            $query->where('major_id', $request->major_id);
        }
        $classes = $query->paginate(10);
        $majors = Major::all();
        return view('admin.classes.index', compact('classes', 'majors'));
    }

    public function create()
    {
        $majors = Major::where('status', 'active')->get();
        return view('admin.classes.create', compact('majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'major_id' => 'required|exists:majors,id',
            'code' => 'required|unique:classes,code',
            'name' => 'required',
            'course_year' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        Classes::create($request->all());
        return redirect()->route('admin.classes.index')->with('success', 'Thêm lớp thành công');
    }

    public function edit(Classes $class)
    {
        $majors = Major::where('status', 'active')->get();
        return view('admin.classes.edit', compact('class', 'majors'));
    }

    public function update(Request $request, Classes $class)
    {
        $request->validate([
            'major_id' => 'required|exists:majors,id',
            'code' => 'required|unique:classes,code,' . $class->id,
            'name' => 'required',
            'course_year' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        $class->update($request->all());
        return redirect()->route('admin.classes.index')->with('success', 'Cập nhật lớp thành công');
    }

    public function destroy(Classes $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Xóa lớp thành công');
    }
}
