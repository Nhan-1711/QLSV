<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $query = Faculty::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }
        $faculties = $query->paginate(10);
        return view('admin.faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('admin.faculties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:faculties,code',
            'name' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        Faculty::create($request->all());
        return redirect()->route('admin.faculties.index')->with('success', 'Thêm khoa thành công');
    }

    public function edit(Faculty $faculty)
    {
        return view('admin.faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'code' => 'required|unique:faculties,code,' . $faculty->id,
            'name' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        $faculty->update($request->all());
        return redirect()->route('admin.faculties.index')->with('success', 'Cập nhật khoa thành công');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return redirect()->route('admin.faculties.index')->with('success', 'Xóa khoa thành công');
    }
}
