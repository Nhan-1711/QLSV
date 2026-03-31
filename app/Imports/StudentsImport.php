<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\Classes;
use App\Models\Major;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if student code already exists
        if (Student::where('student_code', $row['ma_sv'])->exists()) {
            throw new \Exception("Sinh viên có mã " . $row['ma_sv'] . " đã tồn tại.");
        }

        // Check if email already exists in Users table
        if (User::where('email', $row['email'])->exists()) {
            throw new \Exception("Email " . $row['email'] . " đã được sử dụng.");
        }

        // Find related IDs (assuming names are provided, or we could require IDs)
        // For simplicity, let's assume the Excel provides codes or names that we try to match, 
        // OR we just take the ID if provided. 
        // A robust import would look up by code/name. 
        // Let's assume the Excel contains CODES for Class, Major, Faculty for accuracy.

        // Map headers from the image: "Lớp" -> lop, "Ngành" -> nganh, "Khoa" -> khoa
        $classCode = $row['lop'] ?? $row['ma_lop'] ?? null;
        $majorCode = $row['nganh'] ?? $row['ma_nganh'] ?? null;
        $facultyCode = $row['khoa'] ?? $row['ma_khoa'] ?? null;

        $class = Classes::where('code', $classCode)->first();
        $major = Major::where('code', $majorCode)->first();
        $faculty = Faculty::where('code', $facultyCode)->first();

        if (!$class) {
            throw new \Exception("Không tìm thấy Lớp có mã: " . $classCode);
        }
        if (!$major) {
            throw new \Exception("Không tìm thấy Ngành có mã: " . $majorCode);
        }
        if (!$faculty) {
            throw new \Exception("Không tìm thấy Khoa có mã: " . $facultyCode);
        }

        return DB::transaction(function () use ($row, $class, $major, $faculty) {
            // Create User
            $user = User::create([
                'name' => $row['ho_va_ten'],
                'email' => $row['email'],
                'username' => $row['ma_sv'],
                'password' => Hash::make($row['ma_sv']),
                'role' => 'student',
            ]);

            // Create Student
            return new Student([
                'user_id' => $user->id,
                'student_code' => $row['ma_sv'],
                'full_name' => $row['ho_va_ten'],
                'gender' => $row['gioi_tinh'] ?? 'other',
                'dob' => $this->transformDate($row['ngay_sinh'] ?? null),
                'phone' => $row['so_dien_thoai'] ?? null,
                'email' => $row['email'],
                'address' => $row['dia_chi'] ?? null,
                'class_id' => $class->id,
                'major_id' => $major->id,
                'faculty_id' => $faculty->id,
                'status' => $row['trang_thai'] ?? 'studying',
            ]);
        });
    }

    private function transformDate($value)
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
