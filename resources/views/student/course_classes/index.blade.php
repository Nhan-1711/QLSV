@extends('layouts.student')

@section('title', 'Tất cả học phần')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4 text-gray-800 border-bottom pb-2">Danh sách Học phần của tôi</h1>
        
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="courseTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                    <i class="fas fa-book-reader me-2"></i> Lớp đang học
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                    <i class="fas fa-check-circle me-2"></i> Lớp đã hoàn thành
                </button>
            </li>
        </ul>

        <div class="tab-content" id="courseTabsContent">
            <!-- Active Classes Tab -->
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                @if($activeClasses->count() > 0)
                    <div class="row">
                        @foreach($activeClasses as $class)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow border-0 hover-shadow">
                                    <div class="card-header bg-primary text-white py-3">
                                        <h6 class="m-0 fw-bold">{{ $class->course->name }}</h6>
                                        <div class="small opacity-75">{{ $class->name }} | {{ $class->course->code }}</div>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2"><i class="fas fa-user-tie text-muted me-2" style="width:20px"></i> {{ $class->lecturer->user->name ?? 'TBA' }}</p>
                                        <p class="mb-2"><i class="fas fa-calendar-alt text-muted me-2" style="width:20px"></i> {{ $class->day_of_week }}, Tiết {{ $class->period_from }}-{{ $class->period_to }}</p>
                                        <p class="mb-0"><i class="fas fa-map-marker-alt text-muted me-2" style="width:20px"></i> {{ $class->classroom ?? 'TBA' }}</p>
                                    </div>
                                    <div class="card-footer bg-white text-end border-0 pb-3">
                                         @if(Route::has('student.course_classes.show'))
                                            <a href="{{ route('student.course_classes.show', $class->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                                <i class="fas fa-eye me-1"></i> Chi tiết
                                            </a>
                                         @else
                                            <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled>Chi tiết</button>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                     <div class="alert alert-info border-0 shadow-sm">
                        <i class="fas fa-info-circle me-2"></i> Bạn chưa đăng ký lớp học phần nào đang diễn ra.
                    </div>
                @endif
            </div>

            <!-- Completed Classes Tab -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                @if($completedClasses->count() > 0)
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-0">Mã HP</th>
                                            <th class="px-4 py-3 border-0">Tên học phần</th>
                                            <th class="px-4 py-3 border-0">Giảng viên</th>
                                            <th class="px-4 py-3 border-0 text-center">Trạng thái</th>
                                            <th class="px-4 py-3 border-0 text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($completedClasses as $class)
                                            <tr>
                                                <td class="px-4 fw-bold text-secondary">{{ $class->course->code }}</td>
                                                <td class="px-4">
                                                    <div>{{ $class->course->name }}</div>
                                                    <div class="small text-muted">{{ $class->name }}</div>
                                                </td>
                                                <td class="px-4">{{ $class->lecturer->user->name ?? "N/A" }}</td>
                                                <td class="px-4 text-center">
                                                    <span class="badge bg-success-soft text-success px-3 py-1 rounded-pill">Hoàn thành</span>
                                                </td>
                                                <td class="px-4 text-center">
                                                    <a href="{{ route('student.evaluations.create', $class->id) }}" class="btn btn-warning btn-sm shadow-sm rounded-pill px-3">
                                                        <i class="fas fa-star text-white-50 me-1"></i> Đánh giá
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                     <div class="alert alert-light border shadow-sm text-center py-5">
                        <i class="fas fa-university fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">Bạn chưa hoàn thành học phần nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-3px); transition: all 0.3s; }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1); }
</style>
@endsection