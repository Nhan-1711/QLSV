<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecturer::with('faculty');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('full_name', 'like', "%$search%")
                ->orWhere('lecturer_code', 'like', "%$search%");
        }
        if ($request->has('faculty_id') && $request->faculty_id != '') {
            $query->where('faculty_id', $request->faculty_id);
        }
        $lecturers = $query->paginate(10);
        $faculties = Faculty::where('status', 'active')->get();
        return view('admin.lecturers.index', compact('lecturers', 'faculties'));
    }

    public function create()
    {
        $faculties = Faculty::where('status', 'active')->get();
        return view('admin.lecturers.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lecturer_code' => 'required|unique:lecturers,lecturer_code',
            'full_name' => 'required',
            'email' => 'required|email|unique:lecturers,email|unique:users,email',
            'faculty_id' => 'required|exists:faculties,id',
            'status' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            // Create User
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'username' => $request->lecturer_code,
                'password' => Hash::make($request->lecturer_code), // Default password is lecturer code
                'role' => 'lecturer',
            ]);

            // Create Lecturer
            Lecturer::create([
                'user_id' => $user->id,
                'lecturer_code' => $request->lecturer_code,
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'email' => $request->email,
                'academic_title' => $request->academic_title,
                'degree' => $request->degree,
                'faculty_id' => $request->faculty_id,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.lecturers.index')->with('success', 'Thêm giảng viên thành công');
    }

    public function edit(Lecturer $lecturer)
    {
        $faculties = Faculty::where('status', 'active')->get();
        return view('admin.lecturers.edit', compact('lecturer', 'faculties'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $request->validate([
            'lecturer_code' => 'required|unique:lecturers,lecturer_code,' . $lecturer->id,
            'full_name' => 'required',
            'email' => 'required|email|unique:lecturers,email,' . $lecturer->id . '|unique:users,email,' . $lecturer->user_id,
            'faculty_id' => 'required|exists:faculties,id',
            'status' => 'required',
        ]);

        DB::transaction(function () use ($request, $lecturer) {
            // Update User
            if ($lecturer->user) {
                $lecturer->user->update([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'username' => $request->lecturer_code,
                ]);
            }

            // Update Lecturer
            $lecturer->update([
                'lecturer_code' => $request->lecturer_code,
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'email' => $request->email,
                'academic_title' => $request->academic_title,
                'degree' => $request->degree,
                'faculty_id' => $request->faculty_id,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.lecturers.index')->with('success', 'Cập nhật giảng viên thành công');
    }

    public function destroy(Lecturer $lecturer)
    {
        if ($lecturer->user) {
            $lecturer->user->delete();
        }
        $lecturer->delete();
        return redirect()->route('admin.lecturers.index')->with('success', 'Xóa giảng viên thành công');
    }
}
