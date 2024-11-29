{{-- resources/views/layouts/dashboard.blade.php --}}
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') | {{ config('app.name') }}</title>

    <!-- CSS Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #34495e;
            --background-light: #f4f6f7;
            --text-color: #2c3e50;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--background-light);
            color: var(--text-color);
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            min-height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 0.8rem 1rem;
            margin-bottom: 0.2rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(-5px);
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            font-weight: bold;
        }

        .content-wrapper {
            background-color: var(--background-light);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .stats-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .notification-dropdown {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
        }


    </style>

    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0 sidebar">
            <div class="py-4 text-center">
                <h4 class="text-white">نادي الرياضة</h4>
            </div>

            <nav class="mt-2">
                <ul class="nav flex-column">
                    @can('view_dashboard')
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-2"></i>
                                لوحة التحكم
                            </a>
                        </li>
                    @endcan

                    @can('view_members')
                        <li class="nav-item">
                            <a href='/admin/memberships' class="nav-link">
                                <i class="bi bi-people me-2"></i>
                                الأعضاء
                            </a>
                        </li>
                    @endcan

                    @can('view-trainer')
                        <li class="nav-item">
                            <a href='/admin/trainers' class="nav-link">
                                <i class="bi bi-person-workspace me-2"></i>
                                المدربين
                            </a>
                        </li>
                    @endcan

                    @can('view-training')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-calendar-event me-2"></i>
                                الجلسات التدريبية
                            </a>
                        </li>
                    @endcan

                    @can('view-equipment')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-bicycle me-2"></i>
                                الأجهزة الرياضية
                            </a>
                        </li>
                    @endcan

                    @can('view-memberships')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-card-checklist me-2"></i>
                                العضويات
                            </a>
                        </li>
                    @endcan

                    @can('view-meal-plans')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-cup-hot me-2"></i>
                                خطط الوجبات
                            </a>
                        </li>
                    @endcan

                    @can('view-reports')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-graph-up me-2"></i>
                                التقارير
                            </a>
                        </li>
                    @endcan
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <!-- Notifications (Commented out for now) -->
                        {{-- Notification dropdown code remains the same --}}

                        <!-- User Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ auth()->user()->profile_picture ?? '#' }}"
                                         class="rounded-circle me-2"
                                         alt="صورة المستخدم"
                                         width="32"
                                         height="32">
                                    <span>{{ auth()->user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('edit_profile')
                                        <a class="dropdown-item" href="{{route('admin.profile.edit')}}">
                                            <i class="bi bi-person me-2"></i>
                                            الملف الشخصي
                                        </a>
                                    @endcan

                                    @can('manage_users')
                                        <a class="dropdown-item" href="{{route('admin.users.index')}}">
                                            <i class="bi bi-people me-2"></i>
                                            إدارة المستخدمين
                                        </a>
                                    @endcan

                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- Dark Mode Toggle -->
<div class="dark-mode-toggle">
    <button id="darkModeToggle" class="btn btn-secondary">
        <i class="bi bi-moon"></i>
    </button>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // DOM Elements
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // Apply stored theme on page load
    const currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'dark') {
        enableDarkMode();
    } else {
        disableDarkMode();
    }

    // Event Listener for Toggle
    darkModeToggle.addEventListener('click', () => {
        if (body.classList.contains('dark-mode')) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    });

    // Enable Dark Mode
    function enableDarkMode() {
        body.classList.add('dark-mode');
        document.documentElement.style.setProperty('--background-light', '#1a1a2e');
        document.documentElement.style.setProperty('--text-color', '#e0e0e0');
        darkModeToggle.innerHTML = '<i class="bi bi-sun"></i>';
        localStorage.setItem('theme', 'dark'); // Save theme to local storage
    }

    // Disable Dark Mode
    function disableDarkMode() {
        body.classList.remove('dark-mode');
        document.documentElement.style.setProperty('--background-light', '#f4f6f7');
        document.documentElement.style.setProperty('--text-color', '#2c3e50');
        darkModeToggle.innerHTML = '<i class="bi bi-moon"></i>';
        localStorage.setItem('theme', 'light'); // Save theme to local storage
    }
</script>


@stack('scripts')
</body>
</html>
