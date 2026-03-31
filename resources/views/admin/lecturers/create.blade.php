@extends('layouts.admin')

@section('title', 'Thêm Giảng viên')
@section('header', 'Thêm mới Giảng viên')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.lecturers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="lecturer_code" class="form-label">Mã Giảng viên</label>
                        <input type="text" class="form-control @error('lecturer_code') is-invalid @enderror"
                            id="lecturer_code" name="lecturer_code" value="{{ old('lecturer_code') }}" required>
                        @error('lecturer_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Họ tên</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                            name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="dob" name="dob"
                            value="{{ old('dob') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Giới tính</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="academic_title" class="form-label">Học hàm</label>
                        <select class="form-select" id="academic_title" name="academic_title">
                            <option value="">Chọn học hàm</option>
                            <option value="ThS">Thạc sĩ</option>
                            <option value="TS">Tiến sĩ</option>
                            <option value="PGS">Phó Giáo sư</option>
                            <option value="GS">Giáo sư</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="degree" class="form-label">Học vị</label>
                        <select class="form-select" id="degree" name="degree">
                            <option value="">Chọn học vị</option>
                            <option value="Cử nhân">Cử nhân</option>
                            <option value="Kỹ sư">Kỹ sư</option>
                            <option value="Thạc sĩ">Thạc sĩ</option>
                            <option value="Tiến sĩ">Tiến sĩ</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="faculty_id" class="form-label">Khoa</label>
                    <select class="form-select @error('faculty_id') is-invalid @enderror" id="faculty_id" name="faculty_id"
                        required>
                        <option value="">Chọn Khoa</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('faculty_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="working" selected>Đang làm việc</option>
                        <option value="retired">Đã nghỉ hưu</option>
                        <option value="resigned">Đã nghỉ việc</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.lecturers.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection
