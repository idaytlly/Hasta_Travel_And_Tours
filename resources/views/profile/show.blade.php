<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - My Profile</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
        }

        /* NAVBAR */
        .navbar-hasta {
            background: var(--white);
            min-height: 70px;
            max-height: 80px;
            padding: 15px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-hasta.scrolled {
            padding: 10px 0;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
        }

        .logo-image {
            height: 120px;
            width: 120px;
            max-width: 150px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: 2px;
        }

        .logo-text:hover {
            color: var(--primary-dark);
        }

        .logo-text .star {
            color: var(--primary);
        }

        .nav-link-hasta {
            color: var(--text-dark) !important;
            font-weight: 500;
            padding: 10px 20px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin: 0 5px;
            position: relative;
            overflow: hidden;
        }

        .nav-link-hasta::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: var(--primary); 
            border-radius: 25px;
            opacity:0;
            transition: all 0.3s ease;
            z-index: -1; 
        }

        .nav-link-hasta:hover::after {
            opacity: 0.1; 
        }

        .nav-link-hasta:hover {
            color: var(--primary) !important;
        }

        .nav-link-hasta.active {
            color: var(--white) !important; 
        }
        .nav-link-hasta.active::after {
            opacity:1;
        }

        .btn-login {
            background: var(--primary);
            color: var(--white);
            padding: 12px 35px;
            border-radius: 30px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(229, 57, 53, 0.4);
        }

        .btn-outline-danger {
            transition: all 0.3s ease; 
        }

        .btn-outline-danger:hover {
            background-color: var(--primary); 
            color: #fff !important;            
            border-color: var(--primary);     
        }

        /* NOTIFICATION BANNER */
        .notification-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px 25px;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .notification-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .notification-content {
            position: relative;
            z-index: 1;
        }

        .notification-icon {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .notification-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .notification-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }
        
        /* FOOTER */
        .footer-hasta {
            background: var(--dark);
            color: var(--white);
            padding: 80px 0 30px;
            margin-top: 80px;
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

        /* PROFILE INFO BOX */
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .info-box:hover {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .info-label {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: var(--text-dark);
            font-size: 1rem;
            font-weight: 600;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .notification-banner {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-hasta">
    <div class="container">
        <a class="logo-text" href="{{ route('home') }}">
            <img src="{{ asset('images/hasta logo.png') }}" alt="HASTA Logo" class="logo-image">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('cars.index') }}">Vehicles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('aboutus') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('contactus') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta active" href="{{ route('profile.show') }}">Profile</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <span class="me-2">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <!-- NOTIFICATION BANNER -->
            <div class="notification-banner mb-4" id="notificationBanner">
                <div class="notification-content d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold">Booking Confirmed!</h6>
                            <small>Your vehicle pickup is scheduled for Jan 15, 2026 at 10:00 AM</small>
                        </div>
                    </div>
                    <button class="notification-close" onclick="document.getElementById('notificationBanner').style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- PROFILE HEADER CARD -->
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1" style="color:#e53935;">My Profile</h3>
                            <p class="text-muted mb-0">View and manage your account information</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-login rounded-pill px-4">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                        <!-- Notification Button -->
                        <a href="{{ route('profile.notifications') }}" class="btn btn-light position-relative px-4 rounded-pill">
                            <i class="fas fa-bell me-2 text-danger"></i>
                            Notifications
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>

                        <div class="d-flex gap-2">
                            <!-- Current Booking -->
                            <a href="{{ route('bookings.current') }}" class="btn btn-outline-danger rounded-pill px-4">
                                <i class="fas fa-car-side me-2"></i>
                                Current Booking
                            </a>

                            <!-- Booking History -->
                            <a href="{{ route('bookings.history') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="fas fa-clock me-2"></i>
                                Booking History
                            </a>
                        </div>

                    </div>
                </div>
            </div>


            <!-- PERSONAL INFORMATION -->
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color:#e53935;">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">Alia Athirah</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">Identification Card</div>
                                <div class="info-value">050924100200</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">aliaathirah005@gmail.com</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">0107944614</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">Driver License</div>
                                <div class="info-value">11111</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADDRESS INFORMATION -->
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color:#e53935;">
                        <i class="fas fa-map-marker-alt me-2"></i>Address Information
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="info-box">
                                <div class="info-label">Street Address</div>
                                <div class="info-value">841</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">City</div>
                                <div class="info-value">BAHAU</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">State</div>
                                <div class="info-value">NEGERI SEMBILAN</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">Postcode</div>
                                <div class="info-value">72100</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- FOOTER -->
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
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('cars.index') }}">Vehicles</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="{{ route('contactus') }}">Contact</a></li>
                    <li><a href="#">FAQ</a></li>
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

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-hasta');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.car-card, .feature-box, .testimonial-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    
</script>

</body>
</html>
