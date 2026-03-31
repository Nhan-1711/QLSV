@extends('layouts.admin')

@section('title', 'Quản lý Giảng viên')
@section('header', 'Danh sách Giảng viên')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('admin.lecturers.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm..."
                        value="{{ request('search') }}">
                    <select name="faculty_id" class="form-select me-2">
                        <option value="">Tất cả Khoa</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}"
                                {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Tìm</button>
                </form>
                <a href="{{ route('admin.lecturers.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm
                    mới</a>
            </div>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Mã GV</th>
                        <th>Họ tên</th>
                        <th>Học vị</th>
                        <th>Khoa</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lecturers as $lecturer)
                        <tr>
                            <td>{{ $lecturer->id }}</td>
                            <td>{{ $lecturer->lecturer_code }}</td>
                            <td>{{ $lecturer->full_name }}</td>
                            <td>{{ $lecturer->academic_title }} {{ $lecturer->degree }}</td>
                            <td>{{ $lecturer->faculty->name }}</td>
                            <td>
                                <span class="badge bg-{{ $lecturer->status == 'working' ? 'success' : 'secondary' }}">
                                    {{ $lecturer->status == 'working' ? 'Đang làm việc' : $lecturer->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.lecturers.edit', $lecturer) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.lecturers.destroy', $lecturer) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $lecturers->links() }}
        </div>
    </div>
@endsection
