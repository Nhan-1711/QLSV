<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with(['class', 'major', 'faculty'])->get();
    }

    public function headings(): array
    {
        return [
            'Mã SV',
            'Họ và Tên',
            'Email',
            'Giới tính',
            'Ngày sinh',
            'Số điện thoại',
            'Địa chỉ',
            'Lớp',
            'Ngành',
            'Khoa',
            'Trạng thái',
        ];
    }

    public function map($student): array
    {
        return [
            $student->student_code,
            $student->full_name,
            $student->email,
            $student->gender,
            $student->dob,
            $student->phone,
            $student->address,
            $student->class ? $student->class->code : '', // Export Code
            $student->major ? $student->major->code : '', // Export Code
            $student->faculty ? $student->faculty->code : '', // Export Code
            $student->status,
        ];
    }
}
