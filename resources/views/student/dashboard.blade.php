@extends('layouts.student')

@section('title', 'Dashboard Sinh viên')

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Xin chào, {{ $student->full_name }}!</h2>
                <p class="mb-0">Mã SV: <strong>{{ $student->student_code }}</strong> | Lớp:
                    {{ $student->class->name ?? 'N/A' }}</p>

                {{-- <!-- Completed Classes -->
                <div class="card shadow rounded-lg border-0 mb-4 mt-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-check-circle me-2"></i> Các học phần đã
                            hoàn thành</h6>
                    </div>
                    <div class="card-body p-0">
                        @if (isset($completedClasses) && $completedClasses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-0">Mã HP</th>
                                            <th class="px-4 py-3 border-0">Tên học phần</th>
                                            <th class="px-4 py-3 border-0">Giảng viên</th>
                                            <th class="px-4 py-3 border-0 text-center">Đánh giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($completedClasses as $class)
                                            <tr>
                                                <td class="px-4 fw-bold">{{ $class->course->code }}</td>
                                                <td class="px-4">{{ $class->course->name }} - {{ $class->name }}</td>
                                                <td class="px-4">{{ $class->lecturer->user->name ?? 'N/A' }}</td>
                                                <td class="px-4 text-center">
                                                    <a href="{{ route('student.evaluations.create', $class->id) }}"
                                                        class="btn btn-warning btn-sm shadow-sm">
                                                        <i class="fas fa-star text-white-50 me-1"></i> Đánh giá
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-clipboard-check fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">Chưa có lớp học nào hoàn thành.</p>
                            </div>
                        @endif
                    </div>
                </div> --}}
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="fas fa-user-graduate fa-4x" style="opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-day"></i>
                    <h5 class="mb-0">Lịch học hôm nay</h5>
                </div>
                <div class="card-body p-0">
                    @if ($todayClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Tiết</th>
                                        <th>Học phần</th>
                                        <th>Giảng viên</th>
                                        <th>Phòng</th>
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
                                            <td>{{ $class->lecturer->full_name }}</td>
                                            <td>{{ $class->classroom }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-mug-hot fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Hôm nay không có lịch học. Thư giãn nhé!</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-book"></i>
                    <h5 class="mb-0">Các lớp đang học</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mã HP</th>
                                    <th>Tên học phần</th>
                                    <th>Giảng viên</th>
                                    <th>Lịch học</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrolledClasses as $class)
                                    <tr>
                                        <td>{{ $class->course->code }}</td>
                                        <td>{{ $class->course->name }}</td>
                                        <td>{{ $class->lecturer->full_name }}</td>
                                        <td>Thứ {{ $class->day_of_week }}, Tiết
                                            {{ $class->period_from }}-{{ $class->period_to }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Academic Status Card -->
            <div class="card mb-4">
                <div class="card-header bg-info">
                    <i class="fas fa-graduation-cap"></i>
                    <h5 class="mb-0">Kết quả học tập</h5>
                </div>
                <div class="card-body text-center py-4">
                    @php
                        $gpa = $student->calculateCumulativeGPA();
                    @endphp
                    <div class="mb-3">
                        <span class="display-3 fw-bold {{ $gpa < 2.0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($gpa, 2) }}
                        </span>
                        <span class="text-muted fs-5">/ 4.0</span>
                    </div>
                    <p class="text-muted mb-0">Điểm trung bình tích lũy (GPA)</p>

                    @if ($gpa < 2.0 && $gpa > 0)
                        <div class="alert alert-danger mt-4 mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Cảnh báo học vụ!</strong><br>
                            GPA dưới 2.0. Vui lòng gặp cố vấn học tập.
                        </div>
                    @endif
                </div>
            </div>

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
                                    <a href="{{ route('student.notifications.index') }}"
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
