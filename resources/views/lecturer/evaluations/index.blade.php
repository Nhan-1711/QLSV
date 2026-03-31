@extends('layouts.lecturer')

@section('title', 'Đánh giá học phần')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Đánh giá Sinh viên</h1>
         <a href="{{ route('lecturer.evaluations.dashboard') }}" class="btn btn-secondary btn-sm shadow-sm rounded-pill px-3">
            <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i> Quay lại lớp
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
         <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Điểm Trung Bình (Overall)</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $stats['overall'] }} <small class="text-muted fs-6">/ 5</small></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
         <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Số lượng đánh giá</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $stats['count'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Stats -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết điểm thành phần</h6>
                </div>
                <div class="card-body">
                    <h4 class="small font-weight-bold">Chất lượng giảng dạy <span class="float-end">{{ $stats['teaching'] }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $stats['teaching']*20 }}%" aria-valuenow="{{ $stats['teaching'] }}" aria-valuemin="0" aria-valuemax="5"></div>
                    </div>
                    <h4 class="small font-weight-bold">Hỗ trợ sinh viên <span class="float-end">{{ $stats['support'] }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['support']*20 }}%" aria-valuenow="{{ $stats['support'] }}" aria-valuemin="0" aria-valuemax="5"></div>
                    </div>
                    <h4 class="small font-weight-bold">Tài liệu học tập <span class="float-end">{{ $stats['material'] }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar" role="progressbar" style="width: {{ $stats['material']*20 }}%" aria-valuenow="{{ $stats['material'] }}" aria-valuemin="0" aria-valuemax="5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Góp ý từ sinh viên</h6>
        </div>
        <div class="card-body">
            @forelse($evaluations as $evaluation)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between">
                         <h6 class="fw-bold">
                            @if($evaluation->is_anonymous)
                                <i class="fas fa-user-secret me-1"></i> Sinh viên ẩn danh
                            @else
                                <i class="fas fa-user me-1"></i> {{ $evaluation->student->user->name ?? 'Sinh viên' }}
                            @endif
                         </h6>
                         <span class="text-muted small">{{ $evaluation->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="mb-2">
                        @for($i=1; $i<=5; $i++)
                             <i class="fas fa-star {{ $i <= $evaluation->teaching_rating ? 'text-warning' : 'text-gray-300' }} small"></i>
                        @endfor
                        <span class="ms-2 badge bg-light text-dark border">Giảng dạy: {{ $evaluation->teaching_rating }}</span>
                    </div>
                    <p class="mb-0 text-dark fst-italic">"{{ $evaluation->content ?? 'Không có nhận xét chi tiết.' }}"</p>
                </div>
            @empty
                <div class="text-center py-4 text-muted">Chưa có đánh giá nào.</div>
            @endforelse
            
            {{ $evaluations->links() }}
        </div>
    </div>
</div>
@endsection