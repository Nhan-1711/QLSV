<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Faculty;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $query = Major::with('faculty');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }
        if ($request->has('faculty_id') && $request->faculty_id != '') {
            $query->where('faculty_id', $request->faculty_id);
        }
        $majors = $query->paginate(10);
        $faculties = Faculty::all();
        return view('admin.majors.index', compact('majors', 'faculties'));
    }

    public function create()
    {
        $faculties = Faculty::where('status', 'active')->get();
        return view('admin.majors.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'code' => 'required|unique:majors,code',
            'name' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        Major::create($request->all());
        return redirect()->route('admin.majors.index')->with('success', 'Thêm ngành thành công');
    }

    public function edit(Major $major)
    {
        $faculties = Faculty::where('status', 'active')->get();
        return view('admin.majors.edit', compact('major', 'faculties'));
    }

    public function update(Request $request, Major $major)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'code' => 'required|unique:majors,code,' . $major->id,
            'name' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        $major->update($request->all());
        return redirect()->route('admin.majors.index')->with('success', 'Cập nhật ngành thành công');
    }

    public function destroy(Major $major)
    {
        $major->delete();
        return redirect()->route('admin.majors.index')->with('success', 'Xóa ngành thành công');
    }
}
