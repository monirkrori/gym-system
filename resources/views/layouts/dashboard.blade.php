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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
   <style>
      :root {
          --bright-blue: #092161;
          --bright-red: #FF6347;
          --bright-yellow: #26C80E;
          --light-gray: #F0F0F0;
          --dark-gray: #333333;
          --text-dark: #212121;
          --text-light: #FFFFFF;
      }

      body {
          font-family: 'Cairo', sans-serif;
          background-color: #FFFFFF; /* White Background */
          color: var(--text-dark);
          line-height: 1.6;
          transition: all 0.3s ease;
      }

      .sidebar {
          background: linear-gradient(135deg, var(--bright-blue),#474748);
          min-height: 100vh;
          box-shadow: 4px 0 25px rgba(0, 0, 255, 0.3);
          border-left: 3px solid var(--bright-yellow);
          transition: all 0.3s ease;
      }

      .sidebar .nav-link {
          color: var(--text-light);
          padding: 0.8rem 1rem;
          margin-bottom: 0.2rem;
          border-radius: 8px;
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
          position: relative;
          overflow: hidden;
      }

      .sidebar .nav-link::before {
          content: '';
          position: absolute;
          top: 0;
          left: -100%;
          width: 100%;
          height: 100%;
          background: linear-gradient(120deg, transparent, var(--bright-yellow), transparent);
          transition: 0.5s;
      }

      .sidebar .nav-link:hover::before {
          left: 100%;
      }

      .sidebar .nav-link i {
          margin-left: 10px;
          color: var(--bright-yellow);
          opacity: 0.9;
      }

      .sidebar .nav-link:hover {
          background-color: rgba(38, 200, 14,0.2);
          color: var(--bright-yellow);
      }

      .sidebar .nav-link.active {
          background-color: var(--bright-yellow);
          color: var(--dark-gray);
          font-weight: bold;
      }

      .content-wrapper {
          background-color: var(--light-gray);
          border-radius: 15px;
          box-shadow: 0 10px 30px rgba(0, 0, 255, 0.2);
          border: 1px solid var(--bright-blue);
      }

      .navbar {
          background-color: var(--light-gray) !important;
          box-shadow: 0 2px 15px rgba(0, 0, 255, 0.2);
          border-bottom: 1px solid var(--bright-blue);
      }

      .stats-card {
          background-color: var(--light-gray);
          border-radius: 15px;
          box-shadow: 0 8px 25px rgba(0, 0, 255, 0.3);
          border: 1px solid var(--bright-blue);
          transition: all 0.3s ease;
          position: relative;
          overflow: hidden;
      }

      .stats-card::before {
          content: '';
          position: absolute;
          top: -50%;
          left: -50%;
          width: 200%;
          height: 200%;
          background: radial-gradient(circle, rgba(0, 0, 255, 0.3) 0%, transparent 70%);
          transform: rotate(45deg);
          z-index: 1;
      }

      .stats-card:hover {
          transform: translateY(-15px);
          box-shadow: 0 15px 35px rgba(0, 0, 255, 0.4);
      }

      .alert {
          border-radius: 10px;
          padding: 15px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          background-color: var(--light-gray);
          border: 1px solid var(--bright-yellow);
          color: var(--text-dark);
      }

      .alert-success {
          border-left: 5px solid var(--bright-blue);
      }

      .alert-danger {
          border-left: 5px solid var(--bright-red);
      }

      .dropdown-menu {
          background-color: var(--light-gray);
          border: 1px solid var(--bright-blue);
          box-shadow: 0 10px 25px rgba(0, 0, 255, 0.2);
      }

      .dropdown-item {
          color: var(--text-dark);
      }

      .dropdown-item:hover {
          background-color: var(--bright-blue);
          color: var(--text-light);
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
                <h4 class="text-white" style="color: var(--luxury-gold);">نادي الرياضة</h4>
            </div>

            <nav class="mt-2">
                <ul class="nav flex-column">
                    @can('mange_dashboard')
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i>
                                لوحة التحكم
                            </a>
                        </li>
                    @endcan

                    @can('mange')
                        <li class="nav-item">
                            <a href='/admin/memberships' class="nav-link">
                                <i class="bi bi-people"></i>
                                الأعضاء
                            </a>
                        </li>
                    @endcan

                    @can('mange-trainer')
                        <li class="nav-item">
                            <a href='/admin/trainers' class="nav-link">
                                <i class="bi bi-person-workspace"></i>
                                المدربين
                            </a>
                        </li>
                    @endcan

                    @can('mange-training')
                        <li class="nav-item">
                            <a href="/admin/sessions" class="nav-link">
                                <i class="bi bi-calendar-event"></i>
                                الجلسات التدريبية
                            </a>
                        </li>
                    @endcan

                    @can('view-equipment')
                        <li class="nav-item">
                            <a href="/admin/equipments" class="nav-link">
                                <i class="bi bi-bicycle"></i>
                                الأجهزة الرياضية
                            </a>
                        </li>
                    @endcan

                    @can('mange-memberships')
                        <li class="nav-item">
                            <a href="/admin/membership-plans" class="nav-link">
                                <i class="bi bi-card-checklist"></i>
                                خطط العضويات
                            </a>
                        </li>
                    @endcan

                    @can('mange-membership-packages')
                        <li class="nav-item">
                            <a href="/admin/membership-packages" class="nav-link">
                                <i class="bi bi-box-seam"></i>
                                الحزم التدريبية
                            </a>
                        </li>
                    @endcan

                    @can('mange-meal-plans')
                        <li class="nav-item">
                            <a href="/admin/meal-plans" class="nav-link">
                                <i class="bi bi-cup-hot"></i>
                                خطط الوجبات
                            </a>
                        </li>
                    @endcan
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <!-- User Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ auth()->user()->profile_picture ?? '#' }}"
                                         class="rounded-circle me-2 shadow-sm"
                                         alt="صورة المستخدم"
                                         width="40"
                                         height="40"
                                         style="object-fit: cover; border: 2px solid var(--luxury-gold);">
                                    <span class="fw-bold" style="color: var(--luxury-gold);">{{ auth()->user()->name }}</span>
                                </a>
                               <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                   @can('edit_profile')
                                       <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                           <i class="bi bi-person me-2" style="color: var(--luxury-gold);"></i>
                                           الملف الشخصي
                                       </a>
                                   @endcan

                                   @can('manage_users')
                                       <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                           <i class="bi bi-people me-2" style="color: var(--luxury-gold);"></i>
                                           إدارة المستخدمين
                                       </a>
                                   @endcan

                                   @can('manage_roles')
                                       <a class="dropdown-item" href="{{ route('admin.roles.index') }}">
                                           <i class="bi bi-shield-lock me-2" style="color: var(--luxury-gold);"></i>
                                           إدارة الأدوار والصلاحيات
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
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span>{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@stack('scripts')
</body>
</html>
