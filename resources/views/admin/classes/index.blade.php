@extends('layouts.admin')

@section('title', 'Quản lý Lớp')
@section('header', 'Danh sách Lớp')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('admin.classes.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm..."
                        value="{{ request('search') }}">
                    <select name="major_id" class="form-select me-2">
                        <option value="">Tất cả Ngành</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" {{ request('major_id') == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Tìm</button>
                </form>
                <a href="{{ route('admin.classes.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm
                    mới</a>
            </div>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Mã Lớp</th>
                        <th>Tên Lớp</th>
                        <th>Khóa</th>
                        <th>Ngành</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                        <tr>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->code }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->course_year }}</td>
                            <td>{{ $class->major->name }}</td>
                            <td>
                                <span class="badge bg-{{ $class->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $class->status == 'active' ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
            {{ $classes->links() }}
        </div>
    </div>
@endsection
