@extends('layouts.admin')

@section('title', 'Sửa Ngành')
@section('header', 'Cập nhật Ngành')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.majors.update', $major) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="faculty_id" class="form-label">Khoa</label>
                    <select class="form-select @error('faculty_id') is-invalid @enderror" id="faculty_id" name="faculty_id"
                        required>
                        <option value="">Chọn Khoa</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}"
                                {{ old('faculty_id', $major->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('faculty_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="code" class="form-label">Mã Ngành</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                        name="code" value="{{ old('code', $major->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Tên Ngành</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $major->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description', $major->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" {{ $major->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ $major->status == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection
