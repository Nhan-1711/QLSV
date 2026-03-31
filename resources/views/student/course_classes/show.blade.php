@extends('layouts.student')

@section('title', 'Chi tiết học phần')

@section('content')
<div class="mb-3">
    <a href="{{ route('student.course_classes.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>

<div class="row">
    <!-- Class Info -->
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Thông tin lớp học</h6>
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ $courseClass->course->name }}</h5>
                <p class="text-muted small mb-3">{{ $courseClass->name }} | {{ $courseClass->course->code }}</p>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <i class="fas fa-user-tie text-muted me-2" style="width:20px"></i> 
                        <strong>Giảng viên:</strong> {{ $courseClass->lecturer->user->name ?? 'TBA' }}
                    </li>
                    <li class="list-group-item px-0">
                        <i class="fas fa-calendar-alt text-muted me-2" style="width:20px"></i> 
                        <strong>Lịch học:</strong> {{ $courseClass->day_of_week }}
                    </li>
                    <li class="list-group-item px-0">
                        <i class="fas fa-clock text-muted me-2" style="width:20px"></i> 
                        <strong>Ca học:</strong> Tiết {{ $courseClass->period_from }} - {{ $courseClass->period_to }}
                    </li>
                    <li class="list-group-item px-0">
                        <i class="fas fa-map-marker-alt text-muted me-2" style="width:20px"></i> 
                        <strong>Phòng:</strong> {{ $courseClass->classroom ?? 'TBA' }}
                    </li>
                    <li class="list-group-item px-0">
                        <i class="fas fa-info-circle text-muted me-2" style="width:20px"></i> 
                        <strong>Trạng thái:</strong> 
                        @if($courseClass->status == 'active')
                            <span class="badge bg-success">Đang học</span>
                        @else
                            <span class="badge bg-secondary">Kết thúc</span>
                        @endif
                    </li>
                </ul>
                
                @if($courseClass->status == 'finished')
                    <div class="mt-4 d-grid">
                        <a href="{{ route('student.evaluations.create', $courseClass->id) }}" class="btn btn-warning">
                            <i class="fas fa-star me-1"></i> Đánh giá học phần
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Attendance & Grades -->
    <div class="col-md-8 mb-4">
        <!-- Grades -->
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-success">Kết quả học tập</h6>
            </div>
            <div class="card-body">
                @if($grade)
                <div class="row text-center">
                    <div class="col-3">
                        <div class="small text-muted text-uppercase">Chuyên cần</div>
                        <div class="h4 fw-bold text-dark">{{ $grade->attendance_score ?? '-' }}</div>
                    </div>
                    <div class="col-3">
                        <div class="small text-muted text-uppercase">Giữa kỳ</div>
                        <div class="h4 fw-bold text-dark">{{ $grade->midterm_score ?? '-' }}</div>
                    </div>
                    <div class="col-3">
                        <div class="small text-muted text-uppercase">Cuối kỳ</div>
                        <div class="h4 fw-bold text-dark">{{ $grade->final_score ?? '-' }}</div>
                    </div>
                     <div class="col-3">
                        <div class="small text-muted text-uppercase">Tổng kết</div>
                        <div class="h4 fw-bold text-danger">{{ $grade->total_score ?? '-' }}</div>
                    </div>
                </div>
                @else
                    <p class="text-muted text-center mb-0">Chưa có điểm.</p>
                @endif
            </div>
        </div>

        <!-- Attendance History -->
        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-info">Lịch sử điểm danh</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 border-0">Ngày</th>
                                <th class="px-4 border-0 text-center">Trạng thái</th>
                                <th class="px-4 border-0">Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $atten)
                                <tr>
                                    <td class="px-4">{{ \Carbon\Carbon::parse($atten->attendanceSession->session_date)->format('d/m/Y') }}</td>
                                    <td class="px-4 text-center">
                                        @if($atten->status == 'present')
                                            <span class="badge bg-success-soft text-success">Có mặt</span>
                                        @elseif($atten->status == 'late')
                                            <span class="badge bg-warning-soft text-warning">Muộn</span>
                                        @elseif($atten->status == 'excused')
                                            <span class="badge bg-info-soft text-info">Có phép</span>
                                        @else
                                            <span class="badge bg-danger-soft text-danger">Vắng</span>
                                        @endif
                                    </td>
                                    <td class="px-4 text-muted small">{{ $atten->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Chưa có dữ liệu điểm danh.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.bg-success-soft { background-color: rgba(28, 200, 138, 0.1); }
.bg-warning-soft { background-color: rgba(246, 194, 62, 0.1); }
.bg-info-soft { background-color: rgba(54, 185, 204, 0.1); }
.bg-danger-soft { background-color: rgba(231, 74, 59, 0.1); }
</style>
@endsection