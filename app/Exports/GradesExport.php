<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GradesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $courseClassId;

    public function __construct($courseClassId)
    {
        $this->courseClassId = $courseClassId;
    }

    public function collection()
    {
        return Grade::where('course_class_id', $this->courseClassId)
            ->with('student')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Mã SV',
            'Họ và Tên',
            'Điểm CC (10%)',
            'Điểm GK (30%)',
            'Điểm CK (60%)',
            'Tổng kết',
            'GPA (4.0)',
            'Điểm chữ',
        ];
    }

    public function map($grade): array
    {
        return [
            $grade->student->student_code,
            $grade->student->user->name,
            $grade->attendance_score,
            $grade->midterm_score,
            $grade->final_score,
            $grade->total_score,
            $grade->gpa,
            $grade->letter_grade,
        ];
    }
}
