@extends('layouts.lecturer')

@section('title', 'Danh sách lớp học phần')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Danh sách lớp học phần</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã Lớp</th>
                        <th>Học phần</th>
                        <th>Thời gian</th>
                        <th>Phòng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                        <tr>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->course->name }} ({{ $class->course->code }})</td>
                            <td>Thứ {{ $class->day_of_week }}, Tiết {{ $class->period_from }}-{{ $class->period_to }}</td>
                            <td>{{ $class->classroom }}</td>
                            <td>
                                <span class="badge bg-{{ $class->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $class->status == 'active' ? 'Đang học' : 'Đã kết thúc' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('lecturer.attendance.index', $class->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-clipboard-check"></i> Điểm danh
                                </a>
                                <a href="{{ route('lecturer.grades.index', $class->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-marker"></i> Nhập điểm
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $classes->links() }}
        </div>
    </div>
@endsection
