@extends('layouts.admin')

@section('title', 'Quản lý Lớp học phần')
@section('header', 'Danh sách Lớp học phần')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('admin.course_classes.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Tìm</button>
                </form>
                <a href="{{ route('admin.course_classes.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Mở
                    lớp mới</a>
            </div>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Học phần</th>
                        <th>Giảng viên</th>
                        <th>Phòng</th>
                        <th>Lịch học</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courseClasses as $class)
                        <tr>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->course->name }} ({{ $class->course->code }})</td>
                            <td>{{ $class->lecturer->full_name }}</td>
                            <td>{{ $class->classroom }}</td>
                            <td>Thứ {{ $class->day_of_week }}, Tiết {{ $class->period_from }}-{{ $class->period_to }}</td>
                            <td>{{ \Carbon\Carbon::parse($class->start_date)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($class->end_date)->format('d/m/Y') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $class->status == 'active' ? 'success' : ($class->status == 'completed' ? 'primary' : 'secondary') }}">
                                    {{ $class->status == 'active' ? 'Đang học' : ($class->status == 'completed' ? 'Đã kết thúc' : 'Đã hủy') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.attendance.index', $class) }}" class="btn btn-sm btn-primary"
                                        title="Điểm danh"><i class="fas fa-clipboard-check"></i></a>
                                    <a href="{{ route('admin.grades.index', $class) }}" class="btn btn-sm btn-success"
                                        title="Nhập điểm"><i class="fas fa-marker"></i></a>
                                    <a href="{{ route('admin.course_classes.show', $class) }}" class="btn btn-sm btn-info"
                                        title="Chi tiết"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.course_classes.edit', $class) }}"
                                        class="btn btn-sm btn-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                </div>
                                <form action="{{ route('admin.course_classes.destroy', $class) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $courseClasses->links() }}
        </div>
    </div>
@endsection
