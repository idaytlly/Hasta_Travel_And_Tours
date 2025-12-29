<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $car->brand }} {{ $car->model }} - Hasta Travel & Tours</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

    // Smooth scroll animation on page load
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.details-card, .car-card');
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            padding-top: 80px;
        }

        /* NAVBAR */
        .navbar-hasta {
            background: var(--white);
            padding: 15px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: 2px;
        }

        .nav-link-hasta {
            color: var(--text-dark) !important;
            font-weight: 500;
            padding: 10px 20px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-link-hasta:hover {
            color: var(--primary) !important;
        }

        .nav-link-hasta.active {
            background: var(--primary);
            color: var(--white) !important;
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
            transform: translateY(-2px);
        }

        /* BACK BUTTON */
        .back-button {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            color: var(--primary);
            gap: 15px;
        }

        /* CAR DETAILS CARD */
        .details-card {
            background: var(--white);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        /* IMAGE GALLERY */
        .main-image-container {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }

        .main-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-gallery {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 5px;
        }

        .thumbnail {
            width: 100px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .thumbnail:hover, .thumbnail.active {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        /* CAR INFO */
        .car-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .car-category {
            color: var(--text-muted);
            font-size: 1.1rem;
            text-transform: capitalize;
        }

        .price-box {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            color: var(--white);
        }

        .price-amount {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }

        .price-period {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        /* SPECIFICATIONS */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .spec-box {
            background: var(--bg-light);
            border-radius: 15px;
            padding: 25px 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .spec-box:hover {
            background: var(--primary-light);
            transform: translateY(-5px);
        }

        .spec-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .spec-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .spec-value {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* EQUIPMENT LIST */
        .equipment-section h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 25px;
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .equipment-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: var(--bg-light);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .equipment-item:hover {
            background: var(--primary-light);
        }

        .check-icon {
            width: 30px;
            height: 30px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 0.85rem;
        }

        .equipment-item span {
            font-weight: 500;
            color: var(--text-dark);
        }

        /* BOOK NOW BUTTON */
        .btn-book-now {
            background: var(--primary);
            color: var(--white);
            padding: 18px 50px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.2rem;
            border: none;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .btn-book-now:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(229, 57, 53, 0.4);
        }

        /* OTHER CARS SECTION */
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .section-title .accent {
            color: var(--primary);
        }

        .car-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            height: 100%;
        }

        .car-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.15);
        }

        .car-card-image {
            height: 200px;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .car-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .car-card:hover .car-card-image img {
            transform: scale(1.1);
        }

        .car-card-body {
            padding: 25px;
        }

        .car-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .car-card-category {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .car-card-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }

        .car-card-price span {
            font-size: 1rem;
            font-weight: 400;
            color: var(--text-muted);
        }

        .car-features {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            padding: 15px 0;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
        }

        .car-feature {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .car-feature i {
            color: var(--primary);
        }

        .btn-view-details {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-view-details:hover {
            background: var(--primary-dark);
            color: var(--white);
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
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-hasta">
    <div class="container">
        <a class="logo-text" href="{{ route('home') }}">HASTA</a>
        
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
                    <a class="nav-link nav-link-hasta" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="#">Contact</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-login">
                        <i class="fas fa-user me-2"></i>Login
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-login">
                        <i class="fas fa-user me-2"></i>Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container py-5">
    
    <!-- Back Button -->
    <a href="{{ route('cars.index') }}" class="back-button mb-4">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Listings</span>
    </a>

    <!-- Car Details Card -->
    <div class="details-card">
        <div class="row">
            
            <!-- Left Column: Images -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <!-- Main Image -->
                <div class="main-image-container">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             id="mainImage">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                            <i class="fas fa-car text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery -->
                <div class="thumbnail-gallery">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" 
                             alt="View 1" 
                             class="thumbnail active"
                             onclick="changeImage(this.src)">
                        <img src="{{ asset('storage/' . $car->image) }}" 
                             alt="View 2" 
                             class="thumbnail"
                             onclick="changeImage(this.src)">
                        <img src="{{ asset('storage/' . $car->image) }}" 
                             alt="View 3" 
                             class="thumbnail"
                             onclick="changeImage(this.src)">
                    @endif
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="col-lg-6">
                
                <!-- Title and Category -->
                <div class="car-title">
                    <i class="fas fa-circle-arrow-left text-primary"></i>
                    {{ $car->brand }} {{ $car->model }} {{ $car->year }}
                </div>
                <p class="car-category">{{ $car->carType ?? 'Hatchback' }}</p>

                <!-- Price Box -->
                <div class="price-box mb-4">
                    <div class="price-amount">RM{{ number_format($car->daily_rate, 0) }}</div>
                    <div class="price-period">per day</div>
                </div>

                <!-- Specifications -->
                <h3 class="h5 fw-bold mb-3">Specifications</h3>
                <div class="specs-grid">
                    <div class="spec-box">
                        <div class="spec-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="spec-label">Gear Box</div>
                        <div class="spec-value">{{ ucfirst($car->transmission ?? 'Automat') }}</div>
                    </div>

                    <div class="spec-box">
                        <div class="spec-icon">
                            <i class="fas fa-gas-pump"></i>
                        </div>
                        <div class="spec-label">Fuel</div>
                        <div class="spec-value">Petrol</div>
                    </div>

                    <div class="spec-box">
                        <div class="spec-icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="spec-label">Doors</div>
                        <div class="spec-value">4</div>
                    </div>

                    <div class="spec-box">
                        <div class="spec-icon">
                            <i class="fas fa-snowflake"></i>
                        </div>
                        <div class="spec-label">Air Conditioner</div>
                        <div class="spec-value">Yes</div>
                    </div>

                    <div class="spec-box">
                        <div class="spec-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="spec-label">Seats</div>
                        <div class="spec-value">5</div>
                    </div>

                    <div class="spec-box">
                        <div class="spec-icon">
                            <i class="fas fa-road"></i>
                        </div>
                        <div class="spec-label">Distance</div>
                        <div class="spec-value">500km</div>
                    </div>
                </div>

                <!-- Car Equipment -->
                <div class="equipment-section mt-4">
                    <h3>Car Equipment</h3>
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
                            <span>Central Locking</span>
                        </div>
                    </div>
                </div>

                <!-- Book Now Button -->
                @if($car->is_available)
                    <a href="{{ route('booking.create', $car->id) }}" class="btn btn-book-now">
                        <i class="fas fa-calendar-check me-2"></i>Book Now
                    </a>
                @else
                    <button class="btn btn-book-now" disabled style="opacity: 0.6; cursor: not-allowed;">
                        <i class="fas fa-ban me-2"></i>Currently Rented
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Other Cars Section -->
    @if(isset($otherCars) && $otherCars->count() > 0)
    <div class="mt-5 pt-5">
        <div class="section-title">
            <h2>Other <span class="accent">Cars</span></h2>
            <p>Explore more vehicles in our collection</p>
        </div>

        <div class="row g-4">
            @foreach($otherCars as $otherCar)
                <div class="col-md-6 col-lg-4">
                    <div class="car-card">
                        <div class="car-card-image">
                            @if($otherCar->image)
                                <img src="{{ asset('storage/' . $otherCar->image) }}" 
                                     alt="{{ $otherCar->brand }} {{ $otherCar->model }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-car text-muted" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="car-card-body">
                            <h5 class="car-card-title">{{ $otherCar->brand }} {{ $otherCar->model }} {{ $otherCar->year }}</h5>
                            <p class="car-card-category">{{ $otherCar->carType ?? 'Hatchback' }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="car-card-price">
                                    RM{{ number_format($otherCar->daily_rate, 0) }} <span>/ day</span>
                                </div>
                            </div>

                            <div class="car-features">
                                <span class="car-feature">
                                    <i class="fas fa-cog"></i> {{ ucfirst($otherCar->transmission ?? 'Auto') }}
                                </span>
                                <span class="car-feature">
                                    <i class="fas fa-users"></i> 5 Seats
                                </span>
                                <span class="car-feature">
                                    <i class="fas fa-snowflake"></i> AC
                                </span>
                                <span class="car-feature">
                                    <i class="fas fa-gas-pump"></i> Petrol
                                </span>
                            </div>

                            <a href="{{ route('cars.show', $otherCar->id) }}" class="btn-view-details">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('cars.index') }}" class="btn btn-login" style="padding: 15px 50px; font-size: 1rem;">
                View All Vehicles <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
    @endif
</div>

<!-- FOOTER -->
<footer class="footer-hasta">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="footer-brand">HASTA</div>
                <p class="footer-text">
                    Your trusted partner for car rental services in Malaysia. Quality vehicles, affordable prices.
                </p>
                
                <div class="footer-contact mb-4">
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Student Mall UTM, Skudai, 81300, Johor Bahru</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>hastatravel@gmail.com</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <span>011-1090 0700</span>
                    </div>
                </div>

                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2 offset-lg-1 footer-links">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('cars.index') }}">Vehicles</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
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
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
        
        // Update active thumbnail
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach(thumb => {
            thumb.classList.remove('active');
            if (thumb.src === src) {
                thumb.classList.add('active');
            }
        });
    }