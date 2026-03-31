@extends('layouts.lecturer')

@section('title', 'Thống kê điểm danh')

@section('header', 'Thống kê buổi học: ' . \Carbon\Carbon::parse($session->session_date)->format('d/m/Y'))

@section('content')
<style>
    .stats-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .stats-card .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stats-card.card-primary { border-left: 4px solid #4e73df; }
    .stats-card.card-primary .icon-wrapper { background: rgba(78, 115, 223, 0.1); color: #4e73df; }
    .stats-card.card-primary .text-value { color: #4e73df; }

    .stats-card.card-success { border-left: 4px solid #1cc88a; }
    .stats-card.card-success .icon-wrapper { background: rgba(28, 200, 138, 0.1); color: #1cc88a; }
    .stats-card.card-success .text-value { color: #1cc88a; }

    .stats-card.card-warning { border-left: 4px solid #f6c23e; }
    .stats-card.card-warning .icon-wrapper { background: rgba(246, 194, 62, 0.1); color: #f6c23e; }
    .stats-card.card-warning .text-value { color: #f6c23e; }

    .stats-card.card-info { border-left: 4px solid #36b9cc; }
    .stats-card.card-info .icon-wrapper { background: rgba(54, 185, 204, 0.1); color: #36b9cc; }
    .stats-card.card-info .text-value { color: #36b9cc; }

    .stats-card.card-danger { border-left: 4px solid #e74a3b; }
    .stats-card.card-danger .icon-wrapper { background: rgba(231, 74, 59, 0.1); color: #e74a3b; }
    .stats-card.card-danger .text-value { color: #e74a3b; }

    .text-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #858796;
        letter-spacing: 0.5px;
    }
    .text-value {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.2;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Chi tiết thống kê</h1>
        <a href="{{ route('lecturer.attendance.index', $courseClass->id) }}" class="btn btn-secondary btn-sm shadow-sm rounded-pill px-3">
            <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i> Quay lại
        </a>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4 g-3">
        <!-- Totals -->
        <div class="col-xl-2 col-md-6">
            <a href="{{ route('lecturer.attendance.session_statistics', ['courseClass' => $courseClass->id, 'session' => $session->id]) }}" class="text-decoration-none">
                <div class="card stats-card card-primary shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-label mb-1">Tổng sĩ số</div>
                                <div class="text-value">{{ $counts['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-wrapper">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Present -->
        <div class="col-xl-2 col-md-6">
            <a href="{{ route('lecturer.attendance.session_statistics', ['courseClass' => $courseClass->id, 'session' => $session->id, 'status' => 'present']) }}" class="text-decoration-none">
                <div class="card stats-card card-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-label mb-1">Có mặt</div>
                                <div class="text-value">{{ $counts['present'] }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-wrapper">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Late -->
        <div class="col-xl-2 col-md-6">
            <a href="{{ route('lecturer.attendance.session_statistics', ['courseClass' => $courseClass->id, 'session' => $session->id, 'status' => 'late']) }}" class="text-decoration-none">
                <div class="card stats-card card-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-label mb-1">Đi muộn</div>
                                <div class="text-value">{{ $counts['late'] }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-wrapper">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Excused -->
        <div class="col-xl-2 col-md-6">
            <a href="{{ route('lecturer.attendance.session_statistics', ['courseClass' => $courseClass->id, 'session' => $session->id, 'status' => 'excused']) }}" class="text-decoration-none">
                <div class="card stats-card card-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-label mb-1">Có phép</div>
                                <div class="text-value">{{ $counts['excused'] }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-wrapper">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Absent -->
        <div class="col-xl-2 col-md-6">
            <a href="{{ route('lecturer.attendance.session_statistics', ['courseClass' => $courseClass->id, 'session' => $session->id, 'status' => 'absent']) }}" class="text-decoration-none">
                <div class="card stats-card card-danger shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-label mb-1">Vắng</div>
                                <div class="text-value">{{ $counts['absent'] }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-wrapper">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách sinh viên {{ request('status') ? '('.ucfirst(request('status')).')' : '' }}</h6>
             <span class="text-muted small bg-light px-2 py-1 rounded">Buổi học: {{ \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">MSSV</th>
                            <th class="px-4 py-3 border-0">Sinh viên</th>
                            <th class="px-4 py-3 border-0">Trạng thái</th>
                            <th class="px-4 py-3 border-0">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="px-4 fw-bold text-secondary">{{ $attendance->student->student_code ?? 'N/A' }}</td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $attendance->student->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4">
                                    @if($attendance->status == 'present')
                                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fw-normal"><i class="fas fa-check-circle me-1"></i> Có mặt</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill fw-normal"><i class="fas fa-clock me-1"></i> Muộn</span>
                                    @elseif($attendance->status == 'excused')
                                        <span class="badge bg-info-soft text-info px-3 py-2 rounded-pill fw-normal"><i class="fas fa-envelope me-1"></i> Có phép</span>
                                    @else
                                        <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill fw-normal"><i class="fas fa-times-circle me-1"></i> Vắng</span>
                                    @endif
                                </td>
                                <td class="px-4 text-muted small fst-italic">{{ $attendance->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-gray-400 mb-2"><i class="fas fa-search fa-3x opacity-25"></i></div>
                                    <div class="text-muted">Không tìm thấy sinh viên nào trong danh sách này.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
             <!-- Soft Badge Styles -->
             <style>
                .bg-success-soft { background-color: rgba(28, 200, 138, 0.1); }
                .bg-warning-soft { background-color: rgba(246, 194, 62, 0.1); }
                .bg-info-soft { background-color: rgba(54, 185, 204, 0.1); }
                .bg-danger-soft { background-color: rgba(231, 74, 59, 0.1); }
             </style>
        </div>
    </div>
</div>
@endsection