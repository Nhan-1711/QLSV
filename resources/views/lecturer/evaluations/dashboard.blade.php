@extends('layouts.lecturer')

@section('title', 'Đánh giá từ sinh viên')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-2 text-gray-800 fw-bold">Đánh giá Học phần</h1>
        <p class="mb-4 text-muted">Chọn một lớp để xem chi tiết các phản hồi từ sinh viên.</p>
    </div>
</div>

<div class="row">
    @foreach($classes as $class)
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 hover-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            {{ $class->course->name }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $class->name }}
                        </div>
                        <div class="text-xs text-mute mt-2">
                            Mã HP: {{ $class->course->code }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
                <hr>
                <a href="{{ route('lecturer.evaluations.index', $class->id) }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-eye me-1"></i> Xem đánh giá
                </a>
            </div>
        </div>
    </div>
    @endforeach
    
    @if($classes->isEmpty())
    <div class="col-12">
        <div class="alert alert-info">
            Bạn chưa có lớp học phần nào được ghi nhận.
        </div>
    </div>
    @endif
</div>

<style>
.hover-card:hover {
    transform: translateY(-5px);
    transition: transform 0.2s;
    cursor: pointer;
}
</style>
@endsection