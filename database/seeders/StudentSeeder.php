<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $classes = Classes::all();

        if ($classes->isEmpty()) {
            return;
        }

        // Create 50 students
        for ($i = 1; $i <= 50; $i++) {
            $class = $classes->random();
            $studentCode = 'SV' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $email = 'sv' . $i . '@example.com';
            $name = $faker->name;

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'username' => $studentCode,
                ]
            );

            Student::firstOrCreate(
                ['student_code' => $studentCode],
                [
                    'user_id' => $user->id,
                    'full_name' => $name,
                    'email' => $email,
                    'class_id' => $class->id,
                    'major_id' => $class->major_id,
                    'faculty_id' => $class->major->faculty_id,
                    'gender' => $faker->randomElement(['male', 'female']),
                    'dob' => $faker->date('Y-m-d', '2005-01-01'),
                    'phone' => $faker->phoneNumber,
                    'address' => $faker->address,
                ]
            );
        }
    }
}
