@extends('layouts.admin')

@section('title', 'Thống kê hệ thống')

@section('content')
    <!-- Summary Stats Row -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-white"
                style="background: linear-gradient(135deg, #0d6efd, #0099ff);">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3">
                        <i class="fas fa-user-graduate fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase mb-1 opacity-75">Tổng sinh viên</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-white"
                style="background: linear-gradient(135deg, #198754, #20c997);">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3">
                        <i class="fas fa-chalkboard-teacher fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase mb-1 opacity-75">Tổng giảng viên</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ $totalLecturers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-white"
                style="background: linear-gradient(135deg, #ffc107, #ffca2c);">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3">
                        <i class="fas fa-book fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase mb-1 opacity-75">Học phần</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ $totalCourses }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-white"
                style="background: linear-gradient(135deg, #0dcaf0, #3dd5f3);">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3">
                        <i class="fas fa-chalkboard fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase mb-1 opacity-75">Lớp học phần</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ $totalClasses }}</h2>
                    <small class="opacity-75"><i class="fas fa-circle me-1" style="font-size: 8px;"></i>{{ $activeClasses }}
                        đang hoạt động</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Grid -->
    <div class="row g-4">
        <!-- Faculty & Major Analytics -->
        <div class="col-lg-6">
            <!-- Faculty Chart -->
            <div class="card border-0 shadow rounded-4 mb-4">
                <div
                    class="card-header bg-white border-0 py-3 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-building me-2"></i>Sinh viên theo Khoa</h5>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="facultyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Major Chart -->
            <div class="card border-0 shadow rounded-4">
                <div
                    class="card-header bg-white border-0 py-3 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-success"><i class="fas fa-graduation-cap me-2"></i>Sinh viên theo Ngành
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="majorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Charts -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div
                    class="card-header bg-white border-0 py-3 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-pie me-2 text-primary"></i>Phân bố Học lực
                    </h5>
                    <button class="btn btn-sm btn-outline-light text-muted" onclick="window.print()">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-sm-7">
                            <canvas id="academicExcellenceChart" class="w-100"></canvas>
                        </div>
                        <div class="col-sm-5">
                            <div class="list-group list-group-flush small">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-circle text-success me-2"></i>Xuất sắc</span>
                                    <span class="badge bg-success rounded-pill">{{ $performanceStats['excellent'] }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-circle text-primary me-2"></i>Giỏi</span>
                                    <span class="badge bg-primary rounded-pill">{{ $performanceStats['very_good'] }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-circle text-info me-2"></i>Khá</span>
                                    <span class="badge bg-info rounded-pill">{{ $performanceStats['good'] }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-circle text-warning me-2"></i>Trung bình</span>
                                    <span class="badge bg-warning rounded-pill">{{ $performanceStats['average'] }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-circle text-danger me-2"></i>Yếu</span>
                                    <span class="badge bg-danger rounded-pill">{{ $performanceStats['weak'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Honors Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header py-4 position-relative text-white"
                    style="background: linear-gradient(45deg, #1a237e, #283593);">
                    <div class="position-absolute top-0 end-0 opacity-10 p-3">
                        <i class="fas fa-trophy fa-6x text-warning"></i>
                    </div>
                    <div class="text-center position-relative z-index-1">
                        <h4 class="mb-1 fw-bold"><i class="fas fa-crown text-warning me-2"></i>Bảng Vinh Danh - Sinh Viên
                            Xuất Sắc</h4>
                        <p class="mb-0 opacity-75">Top 10 Sinh viên có GPA cao nhất theo Ngành</p>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Tabs -->
                    <div class="bg-light p-3 border-bottom overflow-auto">
                        <ul class="nav nav-pills flex-nowrap" id="majorTabs" role="tablist">
                            @foreach ($topStudentsByMajor as $majorName => $students)
                                <li class="nav-item me-2" role="presentation">
                                    <button
                                        class="nav-link {{ $loop->first ? 'active' : '' }} fw-semibold rounded-pill text-nowrap"
                                        id="tab-{{ Str::slug($majorName) }}" data-bs-toggle="pill"
                                        data-bs-target="#content-{{ Str::slug($majorName) }}" type="button"
                                        role="tab">
                                        {{ $majorName }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="majorTabsContent">
                        @foreach ($topStudentsByMajor as $majorName => $students)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="content-{{ Str::slug($majorName) }}" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 text-nowrap">
                                        <thead class="bg-white text-uppercase small text-muted">
                                            <tr>
                                                <th class="ps-4">Hạng</th>
                                                <th>Sinh viên</th>
                                                <th>Lớp</th>
                                                <th>Khoa</th>
                                                <th class="text-end pe-4">GPA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($students as $index => $student)
                                                @php
                                                    $rank = $index + 1;
                                                    $rowClass = '';
                                                    $icon = '<span class="fw-bold text-muted">#' . $rank . '</span>';

                                                    if ($rank == 1) {
                                                        $rowClass = 'bg-warning-subtle';
                                                        $icon =
                                                            '<i class="fas fa-medal text-warning fa-2x drop-shadow"></i>';
                                                    } elseif ($rank == 2) {
                                                        $icon = '<i class="fas fa-medal text-secondary fa-2x"></i>';
                                                    } elseif ($rank == 3) {
                                                        $icon = '<i class="fas fa-medal text-danger fa-2x"></i>';
                                                    }
                                                @endphp
                                                <tr class="transition-hover {{ $rowClass }}">
                                                    <td class="ps-4" style="width: 80px; text-align: center;">
                                                        {!! $icon !!}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) }}"
                                                                class="rounded-circle shadow-sm border border-2 border-white me-3"
                                                                width="48" height="48" style="object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-0 fw-bold text-dark">
                                                                    {{ $student->full_name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $student->student_code }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-light text-dark border">{{ $student->class->name }}</span>
                                                    </td>
                                                    <td>{{ $student->faculty->name }}</td>
                                                    <td class="text-end pe-4">
                                                        <span class="badge bg-success rounded-pill px-3 py-2 fs-6">
                                                            {{ $student->gpa }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <div class="text-muted opacity-50">
                                                            <i class="fas fa-user-graduate fa-3x mb-3"></i>
                                                            <p>Chưa có dữ liệu sinh viên cho ngành này.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Statistics Section -->
    <h1 class="h3 mb-2 text-gray-800 mt-5">Thống kê Đánh giá Học phần</h1>
    <p class="mb-4">Tổng quan về chất lượng giảng dạy dựa trên khảo sát sinh viên.</p>

    <!-- KPI Cards -->
    <div class="row">
        <!-- Avg Teaching -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Điểm Giảng dạy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $evalStats['avg_teaching'] }}/5.0</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Support -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Điểm Hỗ trợ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $evalStats['avg_support'] }}/5.0</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-headset fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Material -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Điểm Tài liệu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $evalStats['avg_material'] }}/5.0</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-book fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Evals -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Lượt đánh giá</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $evalStats['total'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-comments fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Biểu đồ so sánh tiêu chí</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="evalCriteriaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rankings -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top/Bottom Lớp Học Phần</h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Top 5 Đánh giá cao nhất</h6>
                    <ul class="list-group mb-4">
                        @forelse($topRatedClasses as $class)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $class->course->name }} - {{ $class->name }}
                                <span class="badge bg-success rounded-pill">{{ $class->avg_score }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Chưa có dữ liệu</li>
                        @endforelse
                    </ul>

                    <h6 class="font-weight-bold text-danger">Top 5 Đánh giá thấp nhất</h6>
                    <ul class="list-group">
                        @forelse($lowRatedClasses as $class)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $class->course->name }} - {{ $class->name }}
                                <span class="badge bg-danger rounded-pill">{{ $class->avg_score }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Chưa có dữ liệu</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Academic Performance Chart (Doughnut)
            var ctxAcademic = document.getElementById('academicExcellenceChart').getContext('2d');
            new Chart(ctxAcademic, {
                type: 'doughnut',
                data: {
                    labels: ['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu'],
                    datasets: [{
                        data: [
                            {{ $performanceStats['excellent'] }},
                            {{ $performanceStats['very_good'] }},
                            {{ $performanceStats['good'] }},
                            {{ $performanceStats['average'] }},
                            {{ $performanceStats['weak'] }}
                        ],
                        backgroundColor: ['#198754', '#0d6efd', '#0dcaf0', '#ffc107', '#dc3545'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Faculty Chart (Vertical Bar)
            var ctxFaculty = document.getElementById('facultyChart').getContext('2d');
            new Chart(ctxFaculty, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($studentsPerFaculty->pluck('name')) !!},
                    datasets: [{
                        label: 'Số lượng sinh viên',
                        data: {!! json_encode($studentsPerFaculty->pluck('students_count')) !!},
                        backgroundColor: '#0d6efd',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });

            // Major Chart (Horizontal Bar)
            var ctxMajor = document.getElementById('majorChart').getContext('2d');
            new Chart(ctxMajor, {
                type: 'bar',
                indexAxis: 'y',
                data: {
                    labels: {!! json_encode($studentsPerMajor->pluck('name')) !!},
                    datasets: [{
                        label: 'Số lượng sinh viên',
                        data: {!! json_encode($studentsPerMajor->pluck('students_count')) !!},
                        backgroundColor: '#20c997',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        });
    </script>
    <style>
        .drop-shadow {
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.2));
        }

        .transition-hover {
            transition: background-color 0.2s;
        }

        .transition-hover:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("evalCriteriaChart");
            if(ctx) {
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Giảng dạy", "Hỗ trợ", "Tài liệu"],
                        datasets: [{
                            label: "Điểm trung bình",
                            backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc"],
                            hoverBackgroundColor: ["#2e59d9", "#17a673", "#2c9faf"],
                            borderColor: "#4e73df",
                            data: [
                                {{ $evalStats['avg_teaching'] }}, 
                                {{ $evalStats['avg_support'] }}, 
                                {{ $evalStats['avg_material'] }}
                            ],
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                        scales: {
                            xAxes: [{ gridLines: { display: false, drawBorder: false }, maxBarThickness: 25 }],
                            yAxes: [{ ticks: { min: 0, max: 5, maxTicksLimit: 6, padding: 10 }, gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } }],
                        },
                        legend: { display: false },
                        tooltips: {
                            titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,
                            backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796",
                            borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15,
                            displayColors: false, caretPadding: 10,
                        },
                    }
                });
            }
        });
    </script>
@endpush
