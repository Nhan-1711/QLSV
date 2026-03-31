@extends('layouts.lecturer')

@section('title', 'Dashboard Giảng viên')

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Xin chào, {{ $lecturer->full_name }}!</h2>
                <p class="mb-0">Chúc bạn một ngày làm việc hiệu quả. Dưới đây là tổng quan lịch trình của bạn.</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="fas fa-chalkboard-teacher fa-4x" style="opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="stats-icon bg-primary-light text-white">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $todayClasses->count() }}</h3>
                    <p>Lớp hôm nay</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="stats-icon bg-success text-white">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $activeClasses->count() }}</h3>
                    <p>Lớp đang dạy</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="stats-icon bg-warning text-white">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $notifications->count() }}</h3>
                    <p>Thông báo mới</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clock"></i>
                    <h5 class="mb-0">Lịch dạy hôm nay</h5>
                </div>
                <div class="card-body p-0">
                    @if ($todayClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Tiết</th>
                                        <th>Lớp học phần</th>
                                        <th>Phòng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todayClasses as $class)
                                        <tr>
                                            <td><span class="badge bg-primary">{{ $class->period_from }} -
                                                    {{ $class->period_to }}</span></td>
                                            <td>
                                                <strong>{{ $class->course->name }}</strong><br>
                                                <small class="text-muted">{{ $class->course->code }}</small>
                                            </td>
                                            <td>{{ $class->classroom }}</td>
                                            <td>
                                                <a href="{{ route('lecturer.attendance.index', $class->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-clipboard-check"></i> Điểm danh
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-mug-hot fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Hôm nay không có lịch dạy. Thư giãn nhé!</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-book-open"></i>
                    <h5 class="mb-0">Các lớp đang phụ trách</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mã HP</th>
                                    <th>Tên học phần</th>
                                    <th>Lịch học</th>
                                    <th>Sĩ số</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activeClasses as $class)
                                    <tr>
                                        <td>{{ $class->course->code }}</td>
                                        <td>{{ $class->course->name }}</td>
                                        <td>Thứ {{ $class->day_of_week }}, Tiết
                                            {{ $class->period_from }}-{{ $class->period_to }}</td>
                                        <td><span class="badge bg-info">{{ $class->students->count() }} SV</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('lecturer.attendance.index', $class->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Điểm danh"><i
                                                        class="fas fa-clipboard-check"></i></a>
                                                <a href="{{ route('lecturer.grades.index', $class->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Nhập điểm"><i
                                                        class="fas fa-marker"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-bullhorn"></i>
                    <h5 class="mb-0">Thông báo</h5>
                </div>
                <div class="card-body">
                    @if ($notifications->isEmpty())
                        <div class="text-center py-5">
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/no-data-found-8867280-7265556.png"
                                alt="No Data" style="width: 150px; opacity: 0.7;">
                            <p class="text-muted mt-3">Chưa có thông báo mới.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($notifications as $notification)
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold">{{ $notification->data['title'] }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="mb-1 text-sm">{!! \Illuminate\Support\Str::limit(strip_tags($notification->data['message']), 100) !!}</div>
                                    <a href="{{ route('lecturer.notifications.index') }}"
                                        class="small text-decoration-none">Xem chi tiết</a>
                                    @if (!empty($notification->data['attachment_url']))
                                        <div class="mb-2">
                                            <a href="{{ $notification->data['attachment_url'] }}" target="_blank"
                                                class="text-primary text-decoration-none">
                                                <i class="fas fa-paperclip me-1"></i>
                                                {{ $notification->data['original_attachment_name'] ?? 'Tệp đính kèm' }}
                                            </a>
                                        </div>
                                    @endif
                                    <small class="text-muted">
                                        <i class="fas fa-user-circle me-1"></i>
                                        {{ $notification->data['sender_name'] }}
                                        <span
                                            class="badge bg-light text-dark border ms-1">{{ $notification->data['sender_role'] }}</span>
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
