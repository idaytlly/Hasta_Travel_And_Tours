<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | Hasta Travel And Tours</title>
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

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
            padding-top: 70px;
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

        .profile-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
        }

        .profile-btn:hover {
            background-color: #e53935;
            color: white;
        }

        .profile-btn i {
            font-size: 1.1rem;
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

        /* FOOTER */
        .footer-hasta {
            background: var(--dark);
            color: var(--white);
            padding: 80px 0 30px;
        }

        .footer-brand {
            font-size: 2rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 20px;
        }

        .footer-brand .star {
            color: var(--primary);
        }

        .footer-text {
            color: rgba(255,255,255,0.7);
            margin-bottom: 25px;
            max-width: 300px;
        }

        .footer-contact {
            margin-bottom: 25px;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            color: rgba(255,255,255,0.8);
        }

        .footer-contact-item i {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-links h5 {
            color: var(--white);
            font-weight: 600;
            margin-bottom: 25px;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links ul li {
            margin-bottom: 12px;
        }

        .footer-links ul li a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links ul li a:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 50px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
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
                    <a href="#" class="nav-link-custom">Fleet</a>
                </div>
            </div>

            <!-- Profile Icon -->
            <a href="#" class="profile-btn d-none d-lg-flex">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer id="footer-hasta" class="footer-hasta">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="footer-brand">HASTA</div>
                    <p class="footer-text">
                        Your trusted partner for car rental services in Malaysia. Quality vehicles, affordable prices.
                    </p>
                    
                    <div class="footer-contact">
                        <div class="footer-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Student Mall UTM, Skudai, 81300, Johor Bahru</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>hastatraveltours@gmail.com</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-phone"></i>
                            <span>011-1090 0700</span>
                        </div>
                    </div>

                    <div class="social-links">
                        <a href="http://wasap.my/601110900700/nakkeretasewa"><i class="fab fa-whatsapp"></i></a>
                        <a href="http://t.me/infoHastaCarRentalUTM"><i class="fab fa-telegram"></i></a>
                        <a href="http://youtube.com/watch?v=41Vedbjxn_s"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.instagram.com/hastatraveltours?igsh=MXR0ZjYyM3c3Znpsdg=="><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="col-6 col-lg-2 offset-lg-1 footer-links">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Vehicles</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2 footer-links">
                    <h5>Vehicles</h5>
                    <ul>
                        <li><a href="#">Sedan</a></li>
                        <li><a href="#">Hatchback</a></li>
                        <li><a href="#">MPV</a></li>
                        <li><a href="#">SUV</a></li>
                        <li><a href="#">Luxury</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 footer-links">
                    <h5>Newsletter</h5>
                    <p class="text-white-50 mb-3">Subscribe for updates and special offers</p>
                    <form class="d-flex gap-2">
                        <input type="email" class="form-control" placeholder="Your email" style="border-radius: 10px;">
                        <button type="submit" class="btn" style="background: var(--primary); color: white; border-radius: 10px;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>