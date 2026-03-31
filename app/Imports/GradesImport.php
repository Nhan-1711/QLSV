<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GradesImport implements ToModel, WithHeadingRow
{
    protected $courseClassId;

    public function __construct($courseClassId)
    {
        $this->courseClassId = $courseClassId;
    }

    public function model(array $row)
    {
        // Find student by code
        $student = Student::where('student_code', $row['ma_sv'])->first();

        if (!$student) {
            return null; // Skip if student not found
        }

        // Find existing grade record
        $grade = Grade::where('course_class_id', $this->courseClassId)
            ->where('student_id', $student->id)
            ->first();

        if ($grade) {
            $grade->attendance_score = $row['diem_cc_10'] ?? $grade->attendance_score;
            $grade->midterm_score = $row['diem_gk_30'] ?? $grade->midterm_score;
            $grade->final_score = $row['diem_ck_60'] ?? $grade->final_score;

            // Recalculate total
            $grade->calculateTotal();
            $grade->save();
        }

        return $grade;
    }
}
