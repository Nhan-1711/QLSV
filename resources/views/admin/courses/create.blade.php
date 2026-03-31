@extends('layouts.admin')

@section('title', 'Thêm Học phần')
@section('header', 'Thêm mới Học phần')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label">Mã Học phần</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                        name="code" value="{{ old('code') }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Tên Học phần</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="number_of_credits" class="form-label">Số tín chỉ</label>
                        <input type="number" class="form-control @error('number_of_credits') is-invalid @enderror"
                            id="number_of_credits" name="number_of_credits" value="{{ old('number_of_credits') }}" required
                            min="1">
                        @error('number_of_credits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="theory_hours" class="form-label">Số tiết lý thuyết</label>
                        <input type="number" class="form-control" id="theory_hours" name="theory_hours"
                            value="{{ old('theory_hours', 0) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="practice_hours" class="form-label">Số tiết thực hành</label>
                        <input type="number" class="form-control" id="practice_hours" name="practice_hours"
                            value="{{ old('practice_hours', 0) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" selected>Hoạt động</option>
                        <option value="inactive">Ngừng hoạt động</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection
