@extends('layouts.lecturer')

@section('title', 'Thống kê điểm danh - ' . $courseClass->course->name)
@section('header', 'Thống kê điểm danh: ' . $courseClass->course->name . ' - ' . $courseClass->name)

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Danh sách sinh viên</span>
            <a href="{{ route('lecturer.attendance.index', $courseClass) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                Tổng số buổi học đã kết thúc: <strong>{{ $totalSessions }}</strong>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Mã SV</th>
                            <th>Họ và tên</th>
                            <th class="text-center">Có mặt</th>
                            <th class="text-center">Đi muộn</th>
                            <th class="text-center">Vắng</th>
                            <th class="text-center">% Vắng</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                            <tr class="{{ $student->absent_count >= 3 ? 'table-danger' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->student_code }}</td>
                                <td>{{ $student->full_name }}</td>
                                <td class="text-center text-success fw-bold">{{ $student->present_count }}</td>
                                <td class="text-center text-warning fw-bold">{{ $student->late_count }}</td>
                                <td class="text-center text-danger fw-bold">{{ $student->absent_count }}</td>
                                <td class="text-center fw-bold">
                                    {{ number_format($student->absence_percentage, 1) }}%
                                </td>
                                <td class="text-center">
                                    @if ($student->absent_count >= 3)
                                        <span class="badge bg-danger">Cấm thi</span>
                                    @else
                                        <span class="badge bg-success">Đủ điều kiện</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($student->absent_count >= 3)
                                        <form
                                            action="{{ route('lecturer.attendance.send_ban_email', ['courseClass' => $courseClass->id, 'student' => $student->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn gửi email cấm thi cho sinh viên này?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-envelope"></i> Mail
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
