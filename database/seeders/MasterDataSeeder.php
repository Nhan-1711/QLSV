<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Major;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Faculties
        $itFaculty = Faculty::create([
            'code' => 'CNTT',
            'name' => 'Công nghệ thông tin',
            'description' => 'Khoa đào tạo CNTT',
        ]);

        $ktFaculty = Faculty::create([
            'code' => 'KT',
            'name' => 'Kinh tế',
            'description' => 'Khoa đào tạo Kinh tế',
        ]);

        // Majors
        $seMajor = Major::create([
            'faculty_id' => $itFaculty->id,
            'code' => 'SE',
            'name' => 'Kỹ thuật phần mềm',
        ]);

        $baMajor = Major::create([
            'faculty_id' => $ktFaculty->id,
            'code' => 'BA',
            'name' => 'Quản trị kinh doanh',
        ]);

        // Classes
        Classes::create([
            'major_id' => $seMajor->id,
            'code' => 'SE1801',
            'name' => 'Lớp SE1801',
            'course_year' => 'K18',
        ]);

        Classes::create([
            'major_id' => $baMajor->id,
            'code' => 'BA1801',
            'name' => 'Lớp BA1801',
            'course_year' => 'K18',
        ]);

        // Courses
        Course::create([
            'code' => 'PRJ301',
            'name' => 'Java Web Application Development',
            'credits' => 3,
            'theory_hours' => 30,
            'practice_hours' => 15,
        ]);

        Course::create([
            'code' => 'SWP391',
            'name' => 'Software Development Project',
            'credits' => 3,
            'theory_hours' => 0,
            'practice_hours' => 45,
        ]);
    }
}
