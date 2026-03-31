<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Giảng viên - Quản lý sinh viên')</title>
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
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">SMS Lecturer</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            Xin chào, {{ Auth::user()->name ?? 'Giảng viên' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Hồ sơ cá nhân</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
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
                <a href="{{ route('lecturer.dashboard') }}"
                    class="{{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('lecturer.schedule') }}"
                    class="{{ request()->routeIs('lecturer.schedule') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Lịch dạy
                </a>
                <a href="{{ route('lecturer.classes.index') }}"
                    class="{{ request()->routeIs('lecturer.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Lớp học phần
                </a>
                <a href="{{ route('lecturer.notifications.index') }}"
                    class="{{ request()->routeIs('lecturer.notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i> Quản lý thông báo
                </a>
                <a href="{{ route('lecturer.statistics.index') }}"
                    class="{{ request()->routeIs('lecturer.statistics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Thống kê báo động
                </a>
                <a href="{{ route('lecturer.evaluations.dashboard') }}" 
                    class="{{ request()->routeIs('lecturer.evaluations.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i> Đánh giá SV
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
