<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            ['code' => 'CNTT', 'name' => 'Công nghệ thông tin', 'description' => 'Khoa đào tạo về công nghệ phần mềm, mạng máy tính, và khoa học dữ liệu.'],
            ['code' => 'KT', 'name' => 'Kinh tế', 'description' => 'Khoa đào tạo về quản trị kinh doanh, kế toán, và tài chính ngân hàng.'],
            ['code' => 'NN', 'name' => 'Ngoại ngữ', 'description' => 'Khoa đào tạo về ngôn ngữ Anh, Trung, Nhật, Hàn.'],
            ['code' => 'DL', 'name' => 'Du lịch', 'description' => 'Khoa đào tạo về quản trị dịch vụ du lịch và lữ hành.'],
            ['code' => 'LUAT', 'name' => 'Luật', 'description' => 'Khoa đào tạo về luật kinh tế và luật dân sự.'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::firstOrCreate(['code' => $faculty['code']], $faculty);
        }
    }
}
