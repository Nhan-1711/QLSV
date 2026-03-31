<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\Major;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $cnpm = Major::where('code', 'CNPM')->first();
        $qtkd = Major::where('code', 'QTKD')->first();
        $nna = Major::where('code', 'NNA')->first();

        $classes = [
            ['major_id' => $cnpm->id, 'code' => 'D21CNPM01', 'name' => 'Đại học CNPM 01 - K21', 'course_year' => '2021-2025'],
            ['major_id' => $cnpm->id, 'code' => 'D21CNPM02', 'name' => 'Đại học CNPM 02 - K21', 'course_year' => '2021-2025'],
            ['major_id' => $cnpm->id, 'code' => 'D22CNPM01', 'name' => 'Đại học CNPM 01 - K22', 'course_year' => '2022-2026'],

            ['major_id' => $qtkd->id, 'code' => 'D21QTKD01', 'name' => 'Đại học QTKD 01 - K21', 'course_year' => '2021-2025'],
            ['major_id' => $qtkd->id, 'code' => 'D22QTKD01', 'name' => 'Đại học QTKD 01 - K22', 'course_year' => '2022-2026'],

            ['major_id' => $nna->id, 'code' => 'D21NNA01', 'name' => 'Đại học NNA 01 - K21', 'course_year' => '2021-2025'],
        ];

        foreach ($classes as $class) {
            Classes::firstOrCreate(['code' => $class['code']], $class);
        }
    }
}
