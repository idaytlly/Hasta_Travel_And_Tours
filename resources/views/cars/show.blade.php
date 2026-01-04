<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - Car Details</title>
    
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
            padding-top: 100px;
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

        .logo-image {
            height: 120px;
            width: 120px;
            max-width: 150px;
            object-fit: contain;
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
            opacity: 0;
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
            opacity: 1;
        }

        /* MAIN CONTAINER */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* CAR HEADER CARD */
        .car-header-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .car-title-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .back-icon {
            width: 40px;
            height: 40px;
            background: var(--bg-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-icon:hover {
            background: var(--primary);
            color: white;
        }

        .car-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .car-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 30px;
        }

        .price-unit {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .car-image-section {
            text-align: center;
        }

        .car-main-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .car-thumbnails {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        .thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail:hover {
            border-color: var(--primary);
        }

        .nav-arrow {
            width: 35px;
            height: 35px;
            background: var(--bg-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-arrow:hover {
            background: var(--primary);
            color: white;
        }

        /* SPECS CARD */
        .specs-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 25px;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .spec-box {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .spec-box:hover {
            background: var(--primary-light);
            transform: translateY(-5px);
        }

        .spec-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .spec-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 5px;
        }

        .spec-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .equipment-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .equipment-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--bg-light);
            border-radius: 8px;
        }

        .check-icon {
            width: 30px;
            height: 30px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
        }

        .btn-book {
            background: var(--primary);
            color: white;
            padding: 15px 50px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .btn-book:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(229, 57, 53, 0.3);
        }

        /* OTHER CARS */
        .other-cars {
            margin-top: 50px;
        }

        .other-cars-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .other-cars-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .view-all:hover {
            color: var(--primary-dark);
        }

        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .car-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .car-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .car-card-body {
            padding: 20px;
        }

        .car-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .car-card-type {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .car-card-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .car-card-specs {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
            padding: 15px 0;
            border-top: 1px solid #eee;
        }

        .car-card-spec {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .car-card-spec i {
            color: var(--primary);
        }

        .btn-view-details {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: block;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view-details:hover {
            background: var(--primary-dark);
            color: white;
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

        @media (max-width: 768px) {
            .car-title {
                font-size: 1.5rem;
            }
            
            .car-price {
                font-size: 2rem;
            }
            
            .specs-grid {
                grid-template-columns: repeat(2, 1fr);
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
                    <a class="nav-link nav-link-hasta active" href="{{ route('cars.index') }}">Vehicles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('aboutus') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('contactus') }}">Contact</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('profile.show') }}">Profile</a>
                </li>
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-danger" style="padding: 10px 25px; border-radius: 30px;">Login</a>
                <a href="{{ route('register') }}" class="btn" style="background: #e53935; color: white; padding: 10px 25px; border-radius: 30px;">Register</a>
            @else
                <span class="me-2">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            @endguest
            </div>
        </div>
    </div>
</nav>

<!-- Main Container -->
<div class="main-container">
    <!-- Car Header Card -->
    <div class="car-header-card">
        <div class="car-title-section">
            <a href="{{ route('cars.index') }}" class="back-icon">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="car-title">{{ $car->full_name }}</h1>
        </div>
        
        <div class="car-price">
            RM{{ number_format($car->daily_rate, 0) }}
            <span class="price-unit">/ day</span>
        </div>

        <div class="car-image-section">
            <img src="{{ asset('images/' . $car->image) }}" alt="{{ $car->full_name }}" class="car-main-image">
            
            <div class="car-thumbnails">
                <div class="nav-arrow">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <img src="{{ asset('images/' . $car->image) }}" alt="View 1" class="thumbnail">
                <img src="{{ asset('images/' . $car->image) }}" alt="View 2" class="thumbnail">
                <div class="nav-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Specifications Card -->
    <div class="specs-card">
        <h2 class="section-title">Specifications</h2>
        
        <div class="specs-grid">
            <div class="spec-box">
                <div class="spec-icon">🔧</div>
                <div class="spec-label">Gear Box</div>
                <div class="spec-value">{{ $car->transmission }}</div>
            </div>
            <div class="spec-box">
                <div class="spec-icon">⛽</div>
                <div class="spec-label">Fuel</div>
                <div class="spec-value">{{ $car->fuel_type }}</div>
            </div>
            <div class="spec-box">
                <div class="spec-icon">🚪</div>
                <div class="spec-label">Doors</div>
                <div class="spec-value">4</div>
            </div>
            <div class="spec-box">
                <div class="spec-icon">❄️</div>
                <div class="spec-label">Air Conditioner</div>
                <div class="spec-value">{{ $car->air_conditioner ? 'Yes' : 'No' }}</div>
            </div>
            <div class="spec-box">
                <div class="spec-icon">👥</div>
                <div class="spec-label">Seats</div>
                <div class="spec-value">{{ $car->passengers }}</div>
            </div>
            <div class="spec-box">
                <div class="spec-icon">📏</div>
                <div class="spec-label">Distance</div>
                <div class="spec-value">500km</div>
            </div>
        </div>

        <!-- Car Equipment -->
        <h3 class="equipment-title">Car Equipment</h3>
        <div class="equipment-grid">
            <div class="equipment-item">
                <div class="check-icon">
                    <i class="fas fa-check"></i>
                </div>
                <span>ABS</span>
            </div>
            <div class="equipment-item">
                <div class="check-icon">
                    <i class="fas fa-check"></i>
                </div>
                <span>Air Bags</span>
            </div>
            <div class="equipment-item">
                <div class="check-icon">
                    <i class="fas fa-check"></i>
                </div>
                <span>Cruise Control</span>
            </div>
            <div class="equipment-item">
                <div class="check-icon">
                    <i class="fas fa-check"></i>
                </div>
                <span>Air Conditioner</span>
            </div>
            <div class="equipment-item">
                <div class="check-icon">
                    <i class="fas fa-check"></i>
                </div>
                <span>Power Steering</span>
            </div>
            <div class="equipment-item">
                <div class="check-icon">
                    <i class="fas fa-check"></i>
                </div>
                <span>Bluetooth</span>
            </div>
        </div>

        <a href="{{ route('bookings.create', $car->id) }}" class="btn-book">
            Book Now
        </a>
    </div>

    <!-- Other Cars Section -->
    <div class="other-cars">
        <div class="other-cars-header">
            <h2 class="other-cars-title">Other cars</h2>
            <a href="{{ route('cars.index') }}" class="view-all">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="cars-grid">
            @foreach($otherCars as $otherCar)
            <div class="car-card">
                <img src="{{ asset('images/' . $otherCar->image) }}" alt="{{ $otherCar->full_name }}" class="car-card-image">
                <div class="car-card-body">
                    <div class="car-card-title">{{ $otherCar->full_name }}</div>
                    <div class="car-card-type">{{ $otherCar->transmission }}</div>
                    <div class="car-card-price">
                        RM{{ number_format($otherCar->daily_rate, 0) }}
                        <span style="font-size: 14px; color: #888;">/ day</span>
                    </div>
                    <div class="car-card-specs">
                        <div class="car-card-spec">
                            <i class="fas fa-cog"></i> {{ $otherCar->transmission }}
                        </div>
                        <div class="car-card-spec">
                            <i class="fas fa-gas-pump"></i> {{ $otherCar->fuel_type }}
                        </div>
                        <div class="car-card-spec">
                            <i class="fas fa-snowflake"></i> Air Conditioner
                        </div>
                    </div>
                    <a href="{{ route('cars.show', $otherCar->id) }}" class="btn-view-details">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
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