<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - Car Rental</title>
    
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
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(229, 57, 53, 0.4);
        }

        /* HERO SECTION */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark) 0%, #16213e 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 80%;
            height: 200%;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-title span {
            color: var(--primary);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 40px;
            max-width: 500px;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: var(--primary);
            color: var(--white);
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary:hover {
            background: var(--primary-dark);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(229, 57, 53, 0.4);
        }

        .btn-hero-outline {
            background: transparent;
            color: var(--white);
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            border: 2px solid var(--white);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-outline:hover {
            background: var(--white);
            color: var(--dark);
        }

        .hero-image {
            position: relative;
            z-index: 2;
        }

        .hero-image img {
            max-width: 120%;
            filter: drop-shadow(0 20px 50px rgba(0,0,0,0.3));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 50px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .stat-label {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }

        /* SEARCH BOX */
        .search-section {
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        .search-box {
            background: var(--white);
            border-radius: 20px;
            padding: 30px 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .search-box .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .search-box .form-control,
        .search-box .form-select {
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .search-box .form-control:focus,
        .search-box .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.1);
        }

        .btn-search {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            background: var(--primary-dark);
            color: var(--white);
        }

        /* FEATURED CARS */
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

        .section-title p {
            color: var(--text-muted);
            font-size: 1.1rem;
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
            background: linear-gradient(135deg, var(--primary-light) 0%, #fce4ec 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .car-card-image img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            transition: transform 0.4s ease;
        }

        .car-card:hover .car-card-image img {
            transform: scale(1.1);
        }

        .car-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary);
            color: var(--white);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
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

        .btn-book {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-book:hover {
            background: var(--primary-dark);
            color: var(--white);
        }

        /* WHY CHOOSE US */
        .why-section {
            background: var(--dark);
            padding: 100px 0;
            margin-top: 80px;
        }

        .why-section .section-title h2,
        .why-section .section-title p {
            color: var(--white);
        }

        .feature-box {
            text-align: center;
            padding: 40px 30px;
            background: rgba(255,255,255,0.05);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .feature-box:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .feature-icon i {
            font-size: 2rem;
            color: var(--white);
        }

        .feature-box h4 {
            color: var(--white);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .feature-box p {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
        }

        /* TESTIMONIALS */
        .testimonial-card {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 5rem;
            color: var(--primary-light);
            font-family: Georgia, serif;
            line-height: 1;
        }

        .testimonial-text {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin-bottom: 25px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .testimonial-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .testimonial-author h5 {
            margin: 0;
            font-weight: 600;
        }

        .testimonial-author span {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .stars {
            color: #ffc107;
        }

        /* CTA SECTION */
        .car-card-image {
            background: #fff;  /* PURE WHITE */
        }


        .cta-section h2 {
            color: var(--white);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta-section p {
            color: rgba(255,255,255,0.9);
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .btn-cta {
            background: var(--white);
            color: var(--primary);
            padding: 15px 50px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-cta:hover {
            background: var(--dark);
            color: var(--white);
            transform: scale(1.05);
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
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-image {
                margin-top: 50px;
            }
            
            .hero-image img {
                max-width: 100%;
            }
            
            .hero-stats {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .search-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-hasta">
    <div class="container">
        <a class="logo-text" href="{{ route('home') }}">
            HASTA
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta active" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-hasta" href="{{ route('cars.index') }}">Vehicles</a>
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
                    <a href="{{ route('settings') }}" class="btn btn-login">
                        <i class="fas fa-user me-2"></i>Login
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Drive Your <span>Dream Car</span> Today!
                    </h1>
                    <p class="hero-subtitle">
                        We believe your rental car should enhance your trip, not just be a part of it. Experience premium car rental services with HASTA.
                    </p>
                    
                    <div class="hero-buttons">
                        <a href="{{ route('cars.index') }}" class="btn-hero-primary">
                            <i class="fas fa-car"></i> Browse Cars
                        </a>
                        <a href="#" class="btn-hero-outline">
                            <i class="fas fa-play-circle"></i> Watch Video
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Happy Customers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Premium Cars</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">10+</div>
                            <div class="stat-label">Years Experience</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <img src="{{ asset('images/hero-car.png') }}" alt="Rental Car">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SEARCH BOX -->
<section class="search-section">
    <div class="container">
        <div class="search-box">
            <form action="{{ route('cars.index') }}" method="GET">
                <div class="row g-4 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-map-marker-alt text-danger me-2"></i>Pick-up Location</label>
                        <select class="form-select" name="location">
                            <option>Johor Bahru</option>
                            <option>Kuala Lumpur</option>
                            <option>Penang</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-calendar text-danger me-2"></i>Pick-up Date</label>
                        <input type="date" class="form-control" name="pickup_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-calendar-check text-danger me-2"></i>Return Date</label>
                        <input type="date" class="form-control" name="return_date">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-search">
                            <i class="fas fa-search me-2"></i>Search Cars
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- FEATURED VEHICLES -->
<section class="py-5 mt-5">
    <div class="container">
        <div class="section-title">
            <h2>Featured <span class="accent">Vehicles</span></h2>
            <p>Explore our top-rated rental cars for your next adventure</p>
        </div>

        <div class="row g-4">
            @php
                $cars = [
                    (object)[
                        'id' => 1,
                        'brand' => 'Perodua',
                        'model' => 'Axia 2018',
                        'category' => 'Hatchback',
                        'daily_rate' => 120,
                        'transmission' => 'Auto',
                        'seats' => 5,
                        'image' => 'axia.jpg',
                        'is_new' => true
                    ],
                    (object)[
                        'id' => 2,
                        'brand' => 'Perodua',
                        'model' => 'Myvi 2020',
                        'category' => 'Hatchback',
                        'daily_rate' => 150,
                        'transmission' => 'Auto',
                        'seats' => 5,
                        'image' => 'myvi.jpg',
                        'is_new' => true
                    ],
                    (object)[
                        'id' => 3,
                        'brand' => 'Proton',
                        'model' => 'Saga 2019',
                        'category' => 'Sedan',
                        'daily_rate' => 100,
                        'transmission' => 'Auto',
                        'seats' => 5,
                        'image' => 'saga.jpg',
                        'is_new' => false
                    ],
                ];
            @endphp

            @foreach($cars as $car)
                <div class="col-md-6 col-lg-4">
                    <div class="car-card">
                        <div class="car-card-image">
                            @if($car->is_new)
                                <span class="car-badge"><i class="fas fa-star me-1"></i>NEW</span>
                            @endif
                            <img src="{{ asset('car_images/' . $car->image) }}" alt="{{ $car->model }}">
                        </div>
                        <div class="car-card-body">
                            <h5 class="car-card-title">{{ $car->brand }} {{ $car->model }}</h5>
                            <p class="car-card-category">{{ $car->category }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="car-card-price">
                                    RM{{ number_format($car->daily_rate, 0) }} <span>/ day</span>
                                </div>
                            </div>

                            <div class="car-features">
                                <span class="car-feature">
                                    <i class="fas fa-cog"></i> {{ $car->transmission }}
                                </span>
                                <span class="car-feature">
                                    <i class="fas fa-users"></i> {{ $car->seats }} Seats
                                </span>
                                <span class="car-feature">
                                    <i class="fas fa-snowflake"></i> AC
                                </span>
                                <span class="car-feature">
                                    <i class="fas fa-gas-pump"></i> Petrol
                                </span>
                            </div>

                            @guest
                                <a href="{{ route('register') }}" class="btn btn-book">
                                    <i class="fas fa-calendar-check me-2"></i>Book Now
                                </a>
                            @else
                                <a href="{{ route('bookings.create', $car->id) }}" class="btn btn-book">
                                    <i class="fas fa-calendar-check me-2"></i>Book Now
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            @endforeach

            <!--CAR CARDS - FILLED IMAGES-->
            <style>
              .car-card {
                  border-radius: 20px;
                  overflow: hidden;      /* VERY IMPORTANT */
                  height: 100%;
              }

              .car-card-image {
                  height: 220px;
                  width: 100%;
                  overflow: hidden;
                  position: relative;
                  background: #fff;
              }

              .car-badge {
                  position: absolute;
                  top: 15px;
                  left: 15px;
                  z-index: 5;         /* bring badge to top */
              }

              .car-card-image img {
                  width: 100%;
                  height: 100%;
                  object-fit: cover;     /* fills box perfectly */
                  display: block;        /* removes image spacing */
                  transform: scale(1); /* start zoomed IN */
                  transition: transform 0.4s ease;
              }

              .car-card:hover .car-card-image img {
                transform: scale(1.15);   /* zoom in on hover */
              }
            </style>

        </div>

        <div class="text-center mt-5">
            <a href="{{ route('cars.index') }}" class="btn-hero-primary">
                View All Vehicles <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why-section">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose <span class="accent">HASTA</span>?</h2>
            <p>We provide the best car rental experience in Malaysia</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h4>Best Prices</h4>
                    <p>Competitive rates with no hidden fees. Get the best value for your money.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-car-side"></i>
                    </div>
                    <h4>Quality Cars</h4>
                    <p>Well-maintained vehicles that are regularly serviced and cleaned.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>24/7 Support</h4>
                    <p>Round-the-clock customer service to assist you anytime.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Fully Insured</h4>
                    <p>All our vehicles come with comprehensive insurance coverage.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="py-5">
    <div class="container py-5">
        <div class="section-title">
            <h2>What Our <span class="accent">Customers</span> Say</h2>
            <p>Real reviews from real customers</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Excellent service! The car was clean and well-maintained. Will definitely rent again!"
                    </p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer">
                        <div>
                            <h5>Ahmad Rizal</h5>
                            <span>Business Traveler</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Very affordable prices and the booking process was super easy. Highly recommended!"
                    </p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Customer">
                        <div>
                            <h5>Sarah Lim</h5>
                            <span>Family Vacation</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="testimonial-text">
                        "Great experience from start to finish. The team is very professional and helpful."
                    </p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Customer">
                        <div>
                            <h5>Raj Kumar</h5>
                            <span>Weekend Trip</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Hit the Road?</h2>
        <p>Book your car today and enjoy the freedom of the open road!</p>
        @guest
            <a href="{{ route('register') }}" class="btn-cta">
                <i class="fas fa-user-plus me-2"></i>Get Started Now
            </a>
        @else
            <a href="{{ route('cars.index') }}" class="btn-cta">
                <i class="fas fa-car me-2"></i>Browse Cars
            </a>
        @endguest
    </div>
</section>

<!-- FOOTER -->
<footer class="footer-hasta">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="footer-brand">HA<span class="star">★</span>TA</div>
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
