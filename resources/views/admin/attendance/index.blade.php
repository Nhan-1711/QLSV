@extends('layouts.admin')

@section('title', 'Điểm danh')
@section('header', 'Quản lý Điểm danh: ' . $courseClass->course->name . ' - ' . $courseClass->classroom)

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <a href="{{ route('admin.course_classes.show', $courseClass) }}" class="btn btn-secondary mb-3"><i
                    class="fas fa-arrow-left"></i> Quay lại lớp học</a>
            <a href="{{ route('admin.attendance.create_session', $courseClass) }}" class="btn btn-success mb-3"><i
                    class="fas fa-plus"></i> Tạo buổi điểm danh mới</a>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ngày</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sessions as $session)
                        <tr>
                            <td>{{ $session->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $session->status == 'open' ? 'success' : 'secondary' }}">
                                    {{ $session->status == 'open' ? 'Đang mở' : 'Đã đóng' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.attendance.show_session', ['courseClass' => $courseClass->id, 'session' => $session->id]) }}"
                                    class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Chi tiết / Điểm danh</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
