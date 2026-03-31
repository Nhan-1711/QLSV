<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch Majors
        $cnpm = \App\Models\Major::where('code', 'CNPM')->first();
        $ketoan = \App\Models\Major::where('code', 'KETOAN')->first();
        $nna = \App\Models\Major::where('code', 'NNA')->first();

        $courses = [
            // IT Courses -> CNPM
            ['code' => 'INT101', 'name' => 'Nhập môn Lập trình', 'major_id' => $cnpm?->id, 'number_of_credits' => 3, 'theory_hours' => 30, 'practice_hours' => 30],
            ['code' => 'INT102', 'name' => 'Cấu trúc dữ liệu và Giải thuật', 'major_id' => $cnpm?->id, 'number_of_credits' => 4, 'theory_hours' => 45, 'practice_hours' => 30],
            ['code' => 'INT103', 'name' => 'Cơ sở dữ liệu', 'major_id' => $cnpm?->id, 'number_of_credits' => 3, 'theory_hours' => 30, 'practice_hours' => 30],
            ['code' => 'INT104', 'name' => 'Lập trình Web', 'major_id' => $cnpm?->id, 'number_of_credits' => 4, 'theory_hours' => 30, 'practice_hours' => 60],
            ['code' => 'INT105', 'name' => 'Mạng máy tính', 'major_id' => $cnpm?->id, 'number_of_credits' => 3, 'theory_hours' => 30, 'practice_hours' => 30],

            // Economics Courses -> KETOAN
            ['code' => 'ECO101', 'name' => 'Kinh tế vi mô', 'major_id' => $ketoan?->id, 'number_of_credits' => 3, 'theory_hours' => 45, 'practice_hours' => 0],
            ['code' => 'ECO102', 'name' => 'Kinh tế vĩ mô', 'major_id' => $ketoan?->id, 'number_of_credits' => 3, 'theory_hours' => 45, 'practice_hours' => 0],
            ['code' => 'ECO103', 'name' => 'Nguyên lý kế toán', 'major_id' => $ketoan?->id, 'number_of_credits' => 3, 'theory_hours' => 30, 'practice_hours' => 30],

            // English Courses -> NNA
            ['code' => 'ENG101', 'name' => 'Tiếng Anh cơ bản 1', 'major_id' => $nna?->id, 'number_of_credits' => 3, 'theory_hours' => 30, 'practice_hours' => 30],
            ['code' => 'ENG102', 'name' => 'Tiếng Anh cơ bản 2', 'major_id' => $nna?->id, 'number_of_credits' => 3, 'theory_hours' => 30, 'practice_hours' => 30],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(['code' => $course['code']], $course);
        }
    }
}
