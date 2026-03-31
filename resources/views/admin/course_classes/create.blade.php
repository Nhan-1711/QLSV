@extends('layouts.admin')

@section('title', 'Mở Lớp học phần')
@section('header', 'Mở Lớp học phần mới')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.course_classes.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="course_id" class="form-label">Học phần</label>
                        <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id"
                            required>
                            <option value="">Chọn Học phần</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})</option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lecturer_id" class="form-label">Giảng viên</label>
                        <select class="form-select @error('lecturer_id') is-invalid @enderror" id="lecturer_id"
                            name="lecturer_id" required>
                            <option value="">Chọn Giảng viên</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}"
                                    {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('lecturer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="classroom" class="form-label">Phòng học</label>
                        <input type="text" class="form-control @error('classroom') is-invalid @enderror" id="classroom"
                            name="classroom" value="{{ old('classroom') }}" required>
                        @error('classroom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="semester" class="form-label">Học kỳ</label>
                        <select class="form-select" id="semester" name="semester">
                            <option value="1">Học kỳ 1</option>
                            <option value="2">Học kỳ 2</option>
                            <option value="3">Học kỳ Hè</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="school_year" class="form-label">Năm học</label>
                        <input type="text" class="form-control" id="school_year" name="school_year"
                            value="{{ old('school_year', date('Y') . '-' . (date('Y') + 1)) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="day_of_week" class="form-label">Thứ</label>
                        <select class="form-select" id="day_of_week" name="day_of_week">
                            <option value="2">Thứ 2</option>
                            <option value="3">Thứ 3</option>
                            <option value="4">Thứ 4</option>
                            <option value="5">Thứ 5</option>
                            <option value="6">Thứ 6</option>
                            <option value="7">Thứ 7</option>
                            <option value="CN">Chủ nhật</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="period_from" class="form-label">Tiết bắt đầu</label>
                        <input type="number" class="form-control" id="period_from" name="period_from"
                            value="{{ old('period_from') }}" required min="1" max="15">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="period_to" class="form-label">Tiết kết thúc</label>
                        <input type="number" class="form-control" id="period_to" name="period_to"
                            value="{{ old('period_to') }}" required min="1" max="15">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ old('start_date') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ old('end_date') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" selected>Đang học</option>
                        <option value="completed">Đã kết thúc</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.course_classes.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection
