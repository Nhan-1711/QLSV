@extends('layouts.admin')

@section('title', 'Sửa Sinh viên')
@section('header', 'Cập nhật Sinh viên')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_code" class="form-label">Mã Sinh viên</label>
                        <input type="text" class="form-control @error('student_code') is-invalid @enderror"
                            id="student_code" name="student_code" value="{{ old('student_code', $student->student_code) }}"
                            required>
                        @error('student_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Họ tên</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                            name="full_name" value="{{ old('full_name', $student->full_name) }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $student->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="{{ old('phone', $student->phone) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="dob" name="dob"
                            value="{{ old('dob', optional($student->dob)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Giới tính</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="Nam" {{ $student->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ $student->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ $student->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <textarea class="form-control" id="address" name="address">{{ old('address', $student->address) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="faculty_id" class="form-label">Khoa</label>
                        <select class="form-select @error('faculty_id') is-invalid @enderror" id="faculty_id"
                            name="faculty_id" required>
                            <option value="">Chọn Khoa</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}"
                                    {{ old('faculty_id', $student->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}</option>
                            @endforeach
                        </select>
                        @error('faculty_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="major_id" class="form-label">Ngành</label>
                        <select class="form-select @error('major_id') is-invalid @enderror" id="major_id" name="major_id"
                            required>
                            <option value="">Chọn Ngành</option>
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}"
                                    {{ old('major_id', $student->major_id) == $major->id ? 'selected' : '' }}>
                                    {{ $major->name }}</option>
                            @endforeach
                        </select>
                        @error('major_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="class_id" class="form-label">Lớp</label>
                        <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id"
                            required>
                            <option value="">Chọn Lớp</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="studying" {{ $student->status == 'studying' ? 'selected' : '' }}>Đang học</option>
                        <option value="reserved" {{ $student->status == 'reserved' ? 'selected' : '' }}>Bảo lưu</option>
                        <option value="dropped" {{ $student->status == 'dropped' ? 'selected' : '' }}>Thôi học</option>
                        <option value="graduated" {{ $student->status == 'graduated' ? 'selected' : '' }}>Đã tốt nghiệp
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection
