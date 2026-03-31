<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản lý sinh viên')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        /* Keep alert floating styles or move to custom.css */
        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            animation: slideIn 0.5s ease-out;
        }


        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-university me-2"></i>SMS Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random"
                                class="rounded-circle me-2" width="32" height="32">
                            {{ Auth::user()->name ?? 'Admin' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                        class="fas fa-user me-2"></i>Hồ sơ</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i
                                            class="fas fa-sign-out-alt me-2"></i>Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebarMenu">
            <div class="py-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.evaluations.index') }}"
                    class="nav-link {{ request()->routeIs('admin.evaluations.*') ? 'active' : '' }} d-block mb-2">
                    <i class="fas fa-fw fa-star me-2"></i>
                    Đánh giá
                </a>
                <div class="sidebar-heading">Quản lý đào tạo</div>
                <a href="{{ route('admin.faculties.index') }}"
                    class="{{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Khoa
                </a>
                <a href="{{ route('admin.majors.index') }}"
                    class="{{ request()->routeIs('admin.majors.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i> Ngành
                </a>
                <a href="{{ route('admin.classes.index') }}"
                    class="{{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Lớp sinh hoạt
                </a>
                <a href="{{ route('admin.courses.index') }}"
                    class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Học phần
                </a>
                <a href="{{ route('admin.course_classes.index') }}"
                    class="{{ request()->routeIs('admin.course_classes.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard"></i> Lớp học phần
                </a>

                <div class="sidebar-heading">Người dùng</div>
                <a href="{{ route('admin.students.index') }}"
                    class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i> Sinh viên
                </a>
                <a href="{{ route('admin.lecturers.index') }}"
                    class="{{ request()->routeIs('admin.lecturers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Giảng viên
                </a>

                <div class="sidebar-heading">Hệ thống</div>
                <a href="{{ route('admin.statistics.index') }}"
                    class="{{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Thống kê
                </a>
                <a href="{{ route('admin.notifications.index') }}"
                    class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i> Quản lý thông báo
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @if (session('success'))
                <div class="alert alert-success alert-floating alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-floating alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="animate-fade-in-up">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('modals')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert2 for Session Messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: "{{ session('error') }}",
                });
            @endif

            // Auto-hide alerts after 2 seconds
            const alerts = document.querySelectorAll('.alert-floating');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 2000);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
