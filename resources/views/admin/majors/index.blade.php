@extends('layouts.admin')

@section('title', 'Quản lý Ngành')
@section('header', 'Danh sách Ngành')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('admin.majors.index') }}" method="GET" class="d-flex">
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
                <a href="{{ route('admin.majors.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm
                    mới</a>
            </div>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        
                        
                        <th>STT</th>
                        <th>Mã Ngành</th>
                        <th>Tên Ngành</th>
                        <th>Khoa</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($majors as $major)
                        <tr>
                            <td>{{ $major->id }}</td>
                            <td>{{ $major->code }}</td>
                            <td>{{ $major->name }}</td>
                            <td>{{ $major->faculty->name }}</td>
                            <td>
                                <span class="badge bg-{{ $major->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $major->status == 'active' ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.majors.edit', $major) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.majors.destroy', $major) }}" method="POST" class="d-inline"
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
            {{ $majors->links() }}
        </div>
    </div>
@endsection
