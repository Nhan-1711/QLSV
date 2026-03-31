@extends('layouts.admin')

@section('title', 'Tạo buổi điểm danh')
@section('header', 'Tạo buổi điểm danh mới')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.attendance.store_session', $courseClass) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="session_date" class="form-label">Ngày điểm danh</label>
                    <input type="date" class="form-control @error('session_date') is-invalid @enderror" id="session_date"
                        name="session_date" value="{{ old('session_date', date('Y-m-d')) }}" required>
                    @error('session_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Giờ bắt đầu</label>
                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time"
                            name="start_time" value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">Giờ kết thúc</label>
                        <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time"
                            name="end_time" value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tạo</button>
                <a href="{{ route('admin.attendance.index', $courseClass) }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
@endsection
