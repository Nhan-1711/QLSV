@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Modern Soft UI Dashboard */
    :root {
        --primary-soft: #eef2ff;
        --success-soft: #ecfdf5;
        --warning-soft: #fffbeb;
        --danger-soft: #fef2f2;
        --info-soft: #f0f9ff;
    }

    .dashboard-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.25s ease;
        height: 100%;
        position: relative;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
    }

    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .bg-icon-primary { background-color: var(--primary-soft); color: #4f46e5; }
    .bg-icon-success { background-color: var(--success-soft); color: #10b981; }
    .bg-icon-warning { background-color: var(--warning-soft); color: #f59e0b; }
    .bg-icon-danger { background-color: var(--danger-soft); color: #ef4444; }
    .bg-icon-info { background-color: var(--info-soft); color: #0ea5e9; }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-desc {
        font-size: 0.875rem;
        color: #94a3b8;
    }

    .stat-link {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        color: #cbd5e1;
        transition: color 0.2s;
    }
    .stat-card:hover .stat-link {
        color: #4f46e5;
    }

    /* Activity Feed */
    .activity-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .activity-header {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .activity-header h5 {
        font-size: 1rem;
        font-weight: 700;
        color: #334155;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .activity-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .activity-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: flex-start;
        transition: background-color 0.2s;
    }
    .activity-item:hover {
        background-color: #f8fafc;
    }
    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f1f5f9;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: #94a3b8;
        white-space: nowrap;
    }
</style>

<div class="container-fluid dashboard-container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Dashboard</h1>
            <p class="text-secondary mb-0">Tổng quan tình hình hoạt động của hệ thống</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                <i class="far fa-calendar-alt me-2 text-primary"></i> {{ date('d/m/Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <!-- Faculty -->
        <div class="col-xl-4 col-md-6">
            <div class="stat-card">
                <a href="{{ route('admin.faculties.index') }}" class="stat-link"><i class="fas fa-arrow-right"></i></a>
                <div class="stat-icon-wrapper bg-icon-info">
                    <i class="fas fa-university"></i>
                </div>
                <div>
                    <div class="stat-label">Khoa / Viện</div>
                    <div class="stat-value">{{ number_format($faculties_count ?? 0) }}</div>
                    <div class="stat-desc">Đơn vị quản lý đào tạo</div>
                </div>
            </div>
        </div>

        <!-- Major -->
        <div class="col-xl-4 col-md-6">
            <div class="stat-card">
                <a href="{{ route('admin.majors.index') }}" class="stat-link"><i class="fas fa-arrow-right"></i></a>
                <div class="stat-icon-wrapper bg-icon-warning">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div>
                    <div class="stat-label">Chuyên ngành</div>
                    <div class="stat-value">{{ number_format($majors_count ?? 0) }}</div>
                    <div class="stat-desc">Ngành học đang đào tạo</div>
                </div>
            </div>
        </div>

        <!-- Classes -->
        <div class="col-xl-4 col-md-6">
            <div class="stat-card">
                <a href="{{ route('admin.classes.index') }}" class="stat-link"><i class="fas fa-arrow-right"></i></a>
                <div class="stat-icon-wrapper bg-icon-success">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-label">Lớp hành chính</div>
                    <div class="stat-value">{{ number_format($admin_classes_count ?? 0) }}</div>
                    <div class="stat-desc">Quản lý sinh viên</div>
                </div>
            </div>
        </div>

        <!-- Lecturers -->
        <div class="col-xl-6 col-md-6">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon-wrapper bg-icon-primary mb-0 me-4">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="stat-label">Giảng viên cơ hữu</div>
                    <div class="stat-value">{{ number_format($lecturers_count ?? 0) }}</div>
                    <div class="stat-desc">Đang công tác tại trường</div>
                </div>
                <a href="{{ route('admin.lecturers.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Quản lý</a>
            </div>
        </div>

        <!-- Course Classes -->
        <div class="col-xl-6 col-md-6">
             <div class="stat-card d-flex align-items-center">
                <div class="stat-icon-wrapper bg-icon-danger mb-0 me-4">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="stat-label">Lớp học phần</div>
                    <div class="stat-value">{{ number_format($course_classes_count ?? 0) }}</div>
                    <div class="stat-desc">Đang mở trong học kỳ này</div>
                </div>
                <a href="{{ route('admin.course_classes.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">Chi tiết</a>
            </div>
        </div>
    </div>

    <!-- Activity Section -->
    <div class="row">
        <div class="col-12">
            <div class="activity-card">
                <div class="activity-header">
                    <h5><i class="fas fa-stream text-primary me-2"></i> Nhật ký hoạt động</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-light text-primary me-2 fw-bold" style="background: #eef2ff; border: none;">Xem tất cả</a>
                        <button class="btn btn-sm btn-light text-secondary" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="activity-list">
                         @forelse($recent_activities as $activity)
                            <li class="activity-item">
                                <div class="activity-avatar">
                                     @if($activity->action == 'profile_update') 
                                        <i class="fas fa-user-cog text-info"></i> 
                                     @elseif($activity->action == 'attendance_check') 
                                        <i class="fas fa-clipboard-check text-success"></i>
                                     @elseif($activity->action == 'grade_update') 
                                        <i class="fas fa-star text-warning"></i>
                                     @elseif($activity->action == 'notification_create') 
                                        <i class="fas fa-bell text-danger"></i>
                                     @else
                                        <i class="fas fa-history text-muted"></i>
                                     @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-bold text-dark">{{ $activity->user->name ?? 'Người dùng' }}</span>
                                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-0 text-secondary small">{{ $activity->description }}</p>
                                </div>
                            </li>
                         @empty
                            <li class="text-center py-5 text-muted">
                                <i class="far fa-folder-open fa-2x mb-3 opacity-25"></i>
                                <p class="mb-0">Chưa có dữ liệu mới.</p>
                            </li>
                         @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection