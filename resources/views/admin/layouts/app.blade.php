<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة الإدارة') - أولاد عليوة</title>

    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #B3801F;
            --primary-dark: #8B5A1A;
            --primary-light: #D4A76A;
            --secondary-color: #2C3E50;
            --accent-color: #E67E22;
            --success-color: #27AE60;
            --warning-color: #F39C12;
            --danger-color: #E74C3C;
            --light-bg: #F8F9FA;
            --dark-bg: #2C3E50;
        }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 15px 20px;
            border-radius: 12px;
            margin: 6px 15px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .sidebar .nav-link:hover::before {
            left: 100%;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(-8px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .sidebar .nav-link i {
            width: 24px;
            margin-left: 12px;
            font-size: 1.1rem;
        }

        .main-content {
            padding: 25px;
            background: transparent;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            background: white;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            border: none;
            padding: 20px 25px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            color: white;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(179, 128, 31, 0.4);
            color: white;
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(179, 128, 31, 0.25);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: #212529;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
            color: #212529;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.4);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(23, 162, 184, 0.4);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(108, 117, 125, 0.4);
            color: white;
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: var(--primary-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(179, 128, 31, 0.4);
            color: white;
        }

        .btn-outline-success {
            background: transparent;
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: #28a745;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline-success:hover {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-color: #28a745;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-outline-warning {
            background: transparent;
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: #ffc107;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline-warning:hover {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border-color: #ffc107;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
            color: #212529;
        }

        .btn-outline-danger {
            background: transparent;
            border: 2px solid #dc3545;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: #dc3545;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.4);
            color: white;
        }

        .btn-outline-info {
            background: transparent;
            border: 2px solid #17a2b8;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: #17a2b8;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline-info:hover {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border-color: #17a2b8;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(23, 162, 184, 0.4);
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.875rem;
        }

        .btn-lg {
            padding: 16px 32px;
            font-size: 1.125rem;
        }

        .btn-xl {
            padding: 20px 40px;
            font-size: 1.25rem;
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 18px 15px;
            text-align: center;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(179, 128, 31, 0.05);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f8f9fa;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(179, 128, 31, 0.3);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .stats-card .icon {
            font-size: 3.5rem;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .stats-card .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stats-card .stats-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--primary-color) !important;
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #C0392B 100%);
            color: white;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(179, 128, 31, 0.25);
            transform: translateY(-1px);
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }

        .badge {
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: #212529;
        }

        .badge-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .badge-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .badge-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .badge-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }

        .badge-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #212529;
        }

        .badge-dark {
            background: linear-gradient(135deg, #343a40 0%, #212529 100%);
            color: white;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            color: var(--primary-color);
            font-weight: 600;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
        }

        .dropdown-menu {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background-color: rgba(179, 128, 31, 0.1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        .btn-group .btn {
            margin: 0 2px;
            border-radius: 8px !important;
        }

        .btn-group .btn:first-child {
            margin-right: 0;
        }

        .btn-group .btn:last-child {
            margin-left: 0;
        }

        .btn-group .btn i {
            font-size: 0.875rem;
        }

        .table .btn-sm {
            padding: 6px 10px;
            font-size: 0.8rem;
            min-width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .table .btn-group {
            gap: 4px;
        }

        .table .btn-group .btn {
            margin: 0;
            border-radius: 6px !important;
        }

        .table .btn-group .btn:hover {
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-5">
                        <h4 class="text-white fw-bold">أولاد عليوة</h4>
                        <small class="text-white-50">لوحة الإدارة</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i>
                                المستخدمين
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                <i class="fas fa-box"></i>
                                المنتجات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                <i class="fas fa-tags"></i>
                                الفئات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                الطلبات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.repair-orders.*') ? 'active' : '' }}" href="{{ route('admin.repair-orders.index') }}">
                                <i class="fas fa-tools"></i>
                                طلبات الإصلاح
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                                <i class="fas fa-images"></i>
                                البانرات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
                                <i class="fas fa-bell"></i>
                                الإشعارات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                <i class="fas fa-cog"></i>
                                الإعدادات
                            </a>
                        </li>
                    </ul>

                    <hr class="text-white-50 my-4">

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-white-50 w-100 text-start">
                                    <i class="fas fa-sign-out-alt"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded-3 shadow-sm mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle text-primary me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    @stack('scripts')
</body>

</html>