<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Hasta Travel And Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <style>
        :root {
            --primary: #e53935;
            --primary-dark: #c62828;
            --primary-light: #ffebee;
            --dark: #1a1a2e;
            --text-dark: #333;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --white: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
            padding-top: 70px;
            padding-bottom: 60px; /* Height of footer */
        }

        /* MINIMALIST NAVBAR */
        .navbar-custom {
            background-color: #CB3737;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 0.75rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #e53935;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .navbar-brand:hover {
            color: #c62828;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-link-custom {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link-custom:hover {
            color: #500403ff;
        }

        .nav-link-custom::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #e53935;
            transition: width 0.3s ease;
        }

        .nav-link-custom:hover::after {
            width: 100%;
        }

        .nav-link-custom.active {
            color: #e53935;
        }

        .nav-link-custom.active::after {
            width: 100%;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f25c59ff 0%, #e06a6aff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 3px solid rgba(229, 57, 53, 0.2);
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(229, 57, 53, 0.3);
        }

        .profile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 57, 53, 0.4);
            border-color: rgba(229, 57, 53, 0.4);
        }

        .profile-btn i {
            font-size: 1.1rem;
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 60px;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow: hidden;
        }

        .dropdown-menu-custom.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header-custom {
            padding: 20px;
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .dropdown-header-custom .user-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .dropdown-header-custom .user-email {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .dropdown-item-custom {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .dropdown-item-custom:hover {
            background-color: #f8f9fa;
            border-left-color: #e53935;
            padding-left: 25px;
        }

        .dropdown-item-custom i {
            width: 20px;
            font-size: 1rem;
            color: #6c757d;
        }

        .dropdown-item-custom:hover i {
            color: #e53935;
        }

        .dropdown-divider-custom {
            height: 1px;
            background-color: #e9ecef;
            margin: 8px 0;
        }

        .dropdown-item-custom.logout {
            color: #dc3545;
        }

        .dropdown-item-custom.logout i {
            color: #dc3545;
        }

        .dropdown-item-custom.logout:hover {
            background-color: #fff5f5;
            border-left-color: #dc3545;
        }

        /* Mobile Toggle */
        .navbar-toggler {
            border: none;
            padding: 0;
            width: 30px;
            height: 30px;
            position: relative;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler span {
            display: block;
            width: 25px;
            height: 2px;
            background-color: #333;
            margin: 5px 0;
            transition: all 0.3s ease;
        }

        .welcome-msg {
            font-weight: 500;
            font-size: 0.95rem;
            color: white;
        }

        /* Main content area */
        main {
            flex: 1 0 auto;
        }

        /* MINIMALIST FOOTER */
        .footer-hasta {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1a1a2e;
            color: rgba(255,255,255,0.8);
            padding: 15px 0;
            border-top: 1px solid rgba(255,255,255,0.1);
            z-index: 999;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .footer-logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--white);
        }

        .footer-copyright {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
        }

        .footer-links-inline {
            display: flex;
            gap: 25px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .footer-links-inline a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .footer-links-inline a:hover {
            color: var(--primary);
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .footer-social a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .nav-links {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem 0;
            }

            .navbar-collapse {
                margin-top: 1rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 60px;
                padding-bottom: 120px; /* Increased for mobile */
            }

            .footer-hasta {
                padding: 12px 0;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .footer-left {
                flex-direction: column;
                gap: 8px;
            }

            .footer-links-inline {
                gap: 15px;
                font-size: 0.8rem;
            }

            .footer-social a {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
            }
        }
    </style>

    <!-- MINIMALIST NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
             {{-- Logo --}}
            <div class="navbar-brand">
                <img 
                    src="{{ asset('images/logo_hasta.jpeg') }}" 
                    alt="Hasta Travel & Tours" 
                    height="40"
                >
            </div>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Nav Links -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
                <div class="nav-links">
                    <a href="{{ route('customer.home') }}" class="nav-link-custom">Home</a>
                    <a href="{{ route('vehicles.index') }}" class="nav-link-custom">Fleet</a>
                    <a href="{{ route('customer.reward') }}" class="nav-link-custom">Rewards</a>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-dropdown d-none d-lg-block">
                <button class="profile-btn" onclick="toggleDropdown()">
                    <i class="fas fa-user"></i>
                </button>
                
                <div class="dropdown-menu-custom" id="profileDropdown">
                    <a href="{{ route('customer.profile') }}" class="dropdown-item-custom">
                        <i class="fas fa-user-circle"></i>
                        <span>My Profile</span>
                    </a>
                    
                    <a href="#" class="dropdown-item-custom">
                        <i class="fas fa-car"></i>
                        <span>My Bookings</span>
                    </a>
                    
                    <div class="dropdown-divider-custom"></div>
                    
                    <!-- LOGOUT FORM -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" 
                        onclick="event.preventDefault(); this.closest('form').submit();" 
                        class="dropdown-item-custom logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Log Out</span>
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Minimalist Fixed Footer --}}
    @unless (View::hasSection('noFooter'))
    <footer class="footer-hasta">
        <div class="container">
            <div class="footer-content">
                <!-- Social Links on LEFT -->
                <div class="footer-social">
                    <a href="http://wasap.my/601110900700/nakkeretasewa" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="http://t.me/infoHastaCarRentalUTM" title="Telegram">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a href="https://www.instagram.com/hastatraveltours?igsh=MXR0ZjYyM3c3Znpsdg==" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="http://youtube.com/watch?v=41Vedbjxn_s" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>

                <!-- Copyright on RIGHT -->
                <div class="footer-copyright">
                    © 2026 Hasta Travel & Tours. All Rights Reserved.
                </div>
            </div>
        </div>
    </footer>
    @endunless

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Profile dropdown toggle
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profileBtn = document.querySelector('.profile-btn');
            
            if (!profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Prevent dropdown from closing when clicking inside
        document.getElementById('profileDropdown')?.addEventListener('click', function(event) {
            if (!event.target.classList.contains('dropdown-item-custom') && 
                !event.target.closest('.dropdown-item-custom')) {
                event.stopPropagation();
            }
        });
    </script>
</body>
</html>