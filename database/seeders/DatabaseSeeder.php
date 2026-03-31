<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            FacultySeeder::class,
            MajorSeeder::class,
            ClassSeeder::class,
            CourseSeeder::class,
            LecturerSeeder::class,
            StudentSeeder::class,
            CourseClassSeeder::class,
            AttendanceSeeder::class,
            GradeSeeder::class,
        ]);
    }
}
