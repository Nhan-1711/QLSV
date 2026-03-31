@extends('layouts.admin')

@section('title', 'Sửa Lớp')
@section('header', 'Cập nhật Lớp')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.classes.update', $class) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="major_id" class="form-label">Ngành</label>
                    <select class="form-select @error('major_id') is-invalid @enderror" id="major_id" name="major_id"
                        required>
                        <option value="">Chọn Ngành</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}"
                                {{ old('major_id', $class->major_id) == $major->id ? 'selected' : '' }}>{{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('major_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="code" class="form-label">Mã Lớp</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                        name="code" value="{{ old('code', $class->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Tên Lớp</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $class->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="course_year" class="form-label">Khóa</label>
                    <input type="text" class="form-control @error('course_year') is-invalid @enderror" id="course_year"
                        name="course_year" value="{{ old('course_year', $class->course_year) }}" required>
                    @error('course_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description', $class->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" {{ $class->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ $class->status == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection
