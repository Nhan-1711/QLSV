@extends('layouts.student')

@section('title', 'Kết quả học tập')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Bảng điểm cá nhân</h4>
                <div class="text-end">
                    <h5 class="mb-0">Điểm trung bình tích lũy (GPA)</h5>
                    <h2 class="display-6 {{ $cumulativeGPA < 2.0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($cumulativeGPA, 2) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên học phần</th>
                        <th>Số tín chỉ</th>
                        <th>Chuyên cần (10%)</th>
                        <th>Giữa kỳ (30%)</th>
                        <th>Cuối kỳ (60%)</th>
                        <th>Tổng kết</th>
                        <th>GPA (4.0)</th>
                        <th>Điểm chữ</th>
                        <th>Kết quả</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                        <tr>
                            <td>{{ $grade->courseClass->course->code }}</td>
                            <td>{{ $grade->courseClass->course->name }}</td>
                            <td>{{ $grade->courseClass->course->number_of_credits }}</td>
                            <td>{{ $grade->attendance_score }}</td>
                            <td>{{ $grade->midterm_score }}</td>
                            <td>{{ $grade->final_score }}</td>
                            <td><strong>{{ number_format($grade->total_score, 1) }}</strong></td>
                            <td><strong>{{ number_format($grade->gpa, 1) }}</strong></td>
                            <td><strong>{{ $grade->letter_grade }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $grade->total_score >= 4 ? 'success' : 'danger' }}">
                                    {{ $grade->total_score >= 4 ? 'Đạt' : 'Trượt' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
