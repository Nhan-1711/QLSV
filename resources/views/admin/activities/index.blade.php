@extends('layouts.admin')

@section('title', 'Nhật ký hoạt động')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Nhật ký hoạt động</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Quay lại Dashboard</a>
    </div>

    <div class="card shadow border-0 rounded-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0">Người dùng</th>
                            <th class="px-4 py-3 border-0">Hành động</th>
                            <th class="px-4 py-3 border-0">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle me-3 d-flex align-items-center justify-content-center text-white" 
                                             style="width: 40px; height: 40px; background-color: {{ $activity->user->role == 'admin' ? '#ef4444' : ($activity->user->role == 'lecturer' ? '#3b82f6' : '#10b981') }}">
                                            <i class="fas {{ $activity->user->role == 'admin' ? 'fa-user-shield' : ($activity->user->role == 'lecturer' ? 'fa-chalkboard-teacher' : 'fa-user-graduate') }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                @if($activity->user->role == 'admin') Quản trị viên
                                                @elseif($activity->user->role == 'lecturer') Giảng viên
                                                @else Sinh viên @endif
                                            </div>
                                            <div class="small text-muted">{{ $activity->user->name ?? 'Unknown' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border mb-1">{{ ucfirst(str_replace('_', ' ', $activity->action)) }}</span>
                                    <div class="text-secondary small">{{ $activity->description }}</div>
                                </td>
                                <td class="px-4 py-3 text-muted small">
                                    {{ $activity->created_at->format('d/m/Y H:i') }}
                                    <br>
                                    {{ $activity->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">Không có dữ liệu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection