@extends('layouts.admin')

@section('title', 'Quản lý Sinh viên')
@section('header', 'Danh sách Sinh viên')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('admin.students.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm..."
                        value="{{ request('search') }}">
                    <select name="class_id" class="form-select me-2">
                        <option value="">Tất cả Lớp</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary ">Tìm</button>
                </form>
                <div class="d-flex">
                    <a href="{{ route('admin.students.export') }}" class="btn btn-success me-2">
                        <i class="fas fa-file-excel me-1"></i> Xuất Excel
                    </a>
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        <i class="fas fa-file-import me-1"></i> Nhập Excel
                    </button>
                    <a href="{{ route('admin.students.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Thêm
                        mới</a>
                </div>
            </div>

            <!-- Import Modal -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Nhập sinh viên từ Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Chọn file Excel (.xlsx, .xls)</label>
                                    <input type="file" class="form-control" id="file" name="file" required
                                        accept=".xlsx, .xls">
                                </div>
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i> File Excel cần có các cột:
                                        <strong>ma_sv</strong>, <strong>ho_va_ten</strong>, <strong>email</strong>,
                                        <strong>ma_lop</strong>, <strong>ma_nganh</strong>, <strong>ma_khoa</strong>.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Nhập dữ liệu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        {{-- <th>ID</th> --}}
                        <th>Mã SV</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>Ngành</th>
                        <th>Khoa</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            {{-- <td>{{ $student->id }}</td> --}}
                            <td style="{{ $student->student_code == 'SV0011' ? 'color: red; font-weight: bold;' : '' }}">
                                {{ $student->student_code }}
                            </td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->class->name }}</td>
                            <td>{{ $student->major->name }}</td>
                            <td>{{ $student->faculty->name }}</td>
                            <td>
                                <span class="badge bg-{{ $student->status == 'studying' ? 'success' : 'secondary' }}">
                                    {{ $student->status == 'studying' ? 'Đang học' : $student->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
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
            {{ $students->links() }}
        </div>
    </div>
@endsection
