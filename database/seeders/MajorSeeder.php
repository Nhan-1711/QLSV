<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;
use App\Models\Faculty;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $cntt = Faculty::where('code', 'CNTT')->first();
        $kt = Faculty::where('code', 'KT')->first();
        $nn = Faculty::where('code', 'NN')->first();
        $dl = Faculty::where('code', 'DL')->first();
        $luat = Faculty::where('code', 'LUAT')->first();

        $majors = [
            ['faculty_id' => $cntt->id, 'code' => 'CNPM', 'name' => 'Công nghệ phần mềm'],
            ['faculty_id' => $cntt->id, 'code' => 'HTTT', 'name' => 'Hệ thống thông tin'],
            ['faculty_id' => $cntt->id, 'code' => 'KHMT', 'name' => 'Khoa học máy tính'],

            ['faculty_id' => $kt->id, 'code' => 'QTKD', 'name' => 'Quản trị kinh doanh'],
            ['faculty_id' => $kt->id, 'code' => 'KETOAN', 'name' => 'Kế toán'],
            ['faculty_id' => $kt->id, 'code' => 'TCNH', 'name' => 'Tài chính ngân hàng'],

            ['faculty_id' => $nn->id, 'code' => 'NNA', 'name' => 'Ngôn ngữ Anh'],
            ['faculty_id' => $nn->id, 'code' => 'NNT', 'name' => 'Ngôn ngữ Trung'],

            ['faculty_id' => $dl->id, 'code' => 'QTDL', 'name' => 'Quản trị du lịch'],

            ['faculty_id' => $luat->id, 'code' => 'LKT', 'name' => 'Luật kinh tế'],
        ];

        foreach ($majors as $major) {
            Major::firstOrCreate(['code' => $major['code']], $major);
        }
    }
}
