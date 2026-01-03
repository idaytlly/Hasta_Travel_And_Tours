<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - HASTA Staff Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #dc2626;
            --primary-dark: #b91c1c;
            --primary-light: #fef2f2;
            --secondary: #64748b;
            --dark: #1e293b;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-light);
            padding-top: 85px;
            color: var(--dark);
        }

        /* ============================================
           MODERN NAVBAR STYLES
        ============================================ */
        .navbar-staff {
            background: var(--white);
            padding: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            border-bottom: 3px solid var(--primary);
            height: 85px;
        }

        .navbar-container {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 2rem;
        }

        /* Brand - Bigger & Modern */
        .brand-section {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 1.25rem;
            padding: 0.5rem 0;
        }

        .brand-logo {
            height: 60px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            transition: transform 0.2s;
        }

        .brand-logo:hover {
            transform: scale(1.05);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            padding-left: 1.25rem;
            border-left: 3px solid var(--primary);
        }

        .brand-name {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Page Title */
        .page-title-section {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 0 2rem;
        }

        .page-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        /* Navigation Icons - Modern Design */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-icon-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: var(--secondary);
            transition: all 0.2s ease;
            position: relative;
            text-decoration: none;
            cursor: pointer;
        }

        .nav-icon-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .nav-icon-btn.active {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .nav-icon-btn i {
            font-size: 1.125rem;
        }

        /* Modern Notification Badge */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: var(--white);
            font-size: 0.625rem;
            font-weight: 700;
            padding: 3px 6px;
            border-radius: 20px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        /* Divider */
        .nav-divider {
            width: 2px;
            height: 40px;
            background: var(--border-color);
            margin: 0 1rem;
        }

        /* Modern User Section */
        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
            border-radius: 50px;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
            max-width: 300px;
        }

        .user-section:hover {
            border-color: var(--primary);
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
            background: linear-gradient(135deg, #fee2e2 0%, #fef2f2 100%);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
            flex-shrink: 0;
        }

        .user-details {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
            flex: 1;
        }

        .user-greeting {
            font-size: 0.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--dark);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Modern Logout Button */
        .btn-logout {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            border: none;
            padding: 0.75rem 1.75rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #991b1b 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.4);
        }

        .btn-logout:active {
            transform: translateY(0);
        }

        /* Mobile Menu Toggle */
        .navbar-toggler {
            border: 2px solid var(--border-color);
            padding: 0.625rem;
            border-radius: 12px;
            background: var(--white);
            transition: all 0.2s;
        }

        .navbar-toggler:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* ============================================
           MOBILE RESPONSIVE
        ============================================ */
        @media (max-width: 991px) {
            body {
                padding-top: 75px;
            }

            .navbar-staff {
                height: 75px;
            }

            .navbar-container {
                padding: 0 1rem;
            }

            .page-title-section {
                display: none;
            }

            .brand-logo {
                height: 45px;
            }

            .brand-name {
                font-size: 1.5rem;
            }

            .brand-text {
                display: none;
            }

            .user-section {
                display: none;
            }

            .nav-divider {
                display: none;
            }

            .navbar-collapse {
                margin-top: 1rem;
                padding: 1rem 0;
                background: var(--white);
                border-top: 1px solid var(--border-color);
                border-radius: 0 0 12px 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .nav-actions {
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
            }

            .nav-icon-btn {
                width: 100%;
                justify-content: flex-start;
                padding: 0.875rem 1.25rem;
                border-radius: 12px;
                gap: 1rem;
            }

            .nav-icon-btn i {
                margin-right: 0;
                width: 24px;
            }

            .nav-icon-btn::after {
                content: attr(title);
                font-weight: 600;
                font-size: 0.938rem;
            }

            .btn-logout {
                width: 100%;
                justify-content: center;
                margin-top: 0.5rem;
            }

            /* Mobile User Info */
            .mobile-user-info {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem 1.25rem;
                background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
                border-radius: 12px;
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
            }

            .mobile-user-info .user-details {
                min-width: 0;
                flex: 1;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .mobile-user-info .user-name {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        }

        @media (min-width: 992px) {
            .mobile-user-info {
                display: none;
            }
        }

        /* ============================================
           UTILITY CLASSES
        ============================================ */
        .d-lg-flex {
            display: none !important;
        }

        @media (min-width: 992px) {
            .d-lg-flex {
                display: flex !important;
            }
        }
    </style>
</head>
<body>
    <!-- MODERN NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-staff">
        <div class="container-fluid navbar-container">
            <!-- Brand -->
            <a href="{{ route('staff.dashboard') }}" class="brand-section">
                <img src="{{ asset('images/hasta logo.png') }}" alt="HASTA Logo" class="brand-logo">
                <div class="brand-text">
                    <span class="brand-name">HASTA</span>
                    <span class="brand-subtitle">Staff Panel</span>
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Page Title (Desktop Only) -->
            <div class="page-title-section d-none d-lg-flex">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <!-- Navigation & Actions -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Mobile User Info -->
                <div class="mobile-user-info d-lg-none">
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="user-details">
                        <span class="user-greeting">Welcome back,</span>
                        <span class="user-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
                    </div>
                </div>

                <!-- Navigation Icons -->
                <div class="nav-actions ms-lg-auto">
                    <a href="{{ route('staff.dashboard') }}" 
                       class="nav-icon-btn {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" 
                       title="Dashboard">
                        <i class="fas fa-home"></i>
                    </a>

                    <a href="{{ route('staff.cars.index') }}" 
                       class="nav-icon-btn {{ request()->routeIs('staff.cars.*') ? 'active' : '' }}" 
                       title="Vehicles">
                        <i class="fas fa-car"></i>
                    </a>

                    <a href="{{ route('staff.bookings.index') }}" 
                       class="nav-icon-btn {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}" 
                       title="Bookings">
                        <i class="fas fa-calendar-check"></i>
                    </a>

                    <a href="{{ route('staff.notifications.index') }}" 
                       class="nav-icon-btn {{ request()->routeIs('staff.notifications.*') ? 'active' : '' }}" 
                       title="Notifications">
                        <i class="fas fa-bell"></i>
                        @php
                            try {
                                $unreadCount = Auth::user()->unreadNotifications()->count();
                            } catch (\Exception $e) {
                                $unreadCount = 0;
                            }
                        @endphp
                        @if($unreadCount > 0)
                            <span class="notification-badge">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('staff.settings.profile') }}" 
                       class="nav-icon-btn {{ request()->routeIs('staff.settings.*') ? 'active' : '' }}" 
                       title="Settings">
                        <i class="fas fa-cog"></i>
                    </a>

                    <!-- Divider (Desktop Only) -->
                    <div class="nav-divider d-none d-lg-block"></div>

                    <!-- User Info (Desktop Only) -->
                    <div class="user-section d-none d-lg-flex">
                        <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <div class="user-details">
                            <span class="user-greeting">Welcome back,</span>
                            <span class="user-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>