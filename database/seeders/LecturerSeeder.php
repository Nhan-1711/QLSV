<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

class LecturerSeeder extends Seeder
{
    public function run(): void
    {
        $cntt = Faculty::where('code', 'CNTT')->first();
        $kt = Faculty::where('code', 'KT')->first();

        $lecturers = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'password' => 'password',
                'lecturer_code' => 'GV001',
                'faculty_id' => $cntt->id,
                'phone' => '0901234567',
                'degree' => 'Tiến sĩ',
                'academic_title' => 'Giảng viên chính',
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'password' => 'password',
                'lecturer_code' => 'GV002',
                'faculty_id' => $cntt->id,
                'phone' => '0901234568',
                'degree' => 'Thạc sĩ',
                'academic_title' => 'Giảng viên',
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'password' => 'password',
                'lecturer_code' => 'GV003',
                'faculty_id' => $kt->id,
                'phone' => '0901234569',
                'degree' => 'Thạc sĩ',
                'academic_title' => 'Giảng viên',
            ],
        ];

        foreach ($lecturers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => 'lecturer',
                    'username' => $data['lecturer_code'],
                ]
            );

            Lecturer::firstOrCreate(
                ['lecturer_code' => $data['lecturer_code']],
                [
                    'user_id' => $user->id,
                    'full_name' => $data['name'],
                    'email' => $data['email'],
                    'faculty_id' => $data['faculty_id'],
                    'phone' => $data['phone'],
                    'degree' => $data['degree'],
                    'academic_title' => $data['academic_title'],
                    'gender' => 'male', // Default
                    'dob' => '1980-01-01', // Default
                ]
            );
        }
    }
}
