<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Cars - Hasta Travel & Tours</title>
    
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

        /* PAGE HEADER - REMOVED */

        /* SEARCH & FILTER BAR */
        .filter-bar {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            margin-top: 20px;
        }

        .search-input {
            border: 2px solid #eee;
            border-radius: 15px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.1);
        }

        .filter-btn {
            padding: 12px 30px;
            border-radius: 25px;
            border: 2px solid #eee;
            background: var(--white);
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .btn-search {
            background: var(--primary);
            color: var(--white);
            padding: 12px 40px;
            border-radius: 15px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* BRAND FILTERS */
        .brand-filters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 40px;
        }

        .brand-btn {
            width: 90px;
            height: 90px;
            background: var(--white);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 3px solid transparent;
        }

        .brand-btn:hover, .brand-btn.active {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(229, 57, 53, 0.2);
        }

        .brand-btn i {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .brand-btn span {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        /* CAR CARDS */
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
            height: 220px;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
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

        .car-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary);
            color: var(--white);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 5;
        }

        .car-badge.available {
            background: #4caf50;
        }

        .car-badge.rented {
            background: #9e9e9e;
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
            text-transform: capitalize;
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
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-book:hover {
            background: var(--primary-dark);
            color: var(--white);
        }

        .btn-book:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* PAGINATION */
        .pagination {
            justify-content: center;
            gap: 10px;
        }

        .pagination .page-link {
            border: 2px solid #eee;
            border-radius: 10px;
            color: var(--text-dark);
            font-weight: 600;
            padding: 10px 18px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
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

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 5rem;
            color: var(--text-muted);
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .brand-filters {
                gap: 10px;
            }

            .brand-btn {
                width: 70px;
                height: 70px;
            }
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
    
    <!-- SEARCH & FILTER BAR -->
    <div class="filter-bar">
        <form action="{{ route('cars.index') }}" method="GET">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 15px 0 0 15px; border: 2px solid #eee; border-right: 0;">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control search-input border-start-0" 
                               style="border-left: 0; padding-left: 0;"
                               name="search" 
                               placeholder="Search by brand, model, plate number..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="filter-btn {{ !request('type') ? 'active' : '' }}" onclick="filterByType('')">
                            All
                        </button>
                        <button type="button" class="filter-btn {{ request('type') == 'sedan' ? 'active' : '' }}" onclick="filterByType('sedan')">
                            Sedan
                        </button>
                        <button type="button" class="filter-btn {{ request('type') == 'hatchback' ? 'active' : '' }}" onclick="filterByType('hatchback')">
                            Hatchback
                        </button>
                        <button type="button" class="filter-btn {{ request('type') == 'mpv' ? 'active' : '' }}" onclick="filterByType('mpv')">
                            MPV
                        </button>
                        <button type="button" class="filter-btn {{ request('type') == 'suv' ? 'active' : '' }}" onclick="filterByType('suv')">
                            SUV
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-search w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- BRAND FILTERS -->
    <div class="brand-filters">
        <div class="brand-btn {{ !request('brand') ? 'active' : '' }}" onclick="filterByBrand('')">
            <i class="fas fa-car"></i>
            <span>All</span>
        </div>
        @if($brands && $brands->count() > 0)
            @foreach($brands as $brand)
                <div class="brand-btn {{ request('brand') == strtolower($brand) ? 'active' : '' }}" 
                     onclick="filterByBrand('{{ strtolower($brand) }}')">
                    <i class="fas fa-car-side"></i>
                    <span>{{ strtoupper(substr($brand, 0, 3)) }}</span>
                </div>
            @endforeach
        @endif
    </div>

    <!-- RESULTS COUNT -->
    <div class="mb-4">
        <h5 class="text-muted">
            <i class="fas fa-info-circle me-2"></i>
            Found <strong class="text-primary">{{ $cars->total() }}</strong> vehicles
        </h5>
    </div>

    <!-- CAR GRID -->
    @if($cars->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($cars as $car)
                <div class="col-md-6 col-lg-4">
                    <div class="car-card">
                        <div class="car-card-image">
                            @if($car->is_available)
                                <span class="car-badge available">
                                    <i class="fas fa-check-circle me-1"></i>Available
                                </span>
                            @else
                                <span class="car-badge rented">
                                    <i class="fas fa-ban me-1"></i>Rented
                                </span>
                            @endif
                            
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" 
                                     alt="{{ $car->brand }} {{ $car->model }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-car text-muted" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="car-card-body">
                            <h5 class="car-card-title">{{ $car->brand }} {{ $car->model }}</h5>
                            <p class="car-card-category">{{ $car->carType ?? 'Hatchback' }} • {{ $car->year }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="car-card-price">
                                    RM{{ number_format($car->daily_rate, 0) }} <span>/ day</span>
                                </div>
                            </div>

                            <div class="car-features">
                                <span class="car-feature">
                                    <i class="fas fa-cog"></i> {{ ucfirst($car->transmission ?? 'Auto') }}
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

                            @if($car->is_available)
                                <a href="{{ route('cars.show', $car->id) }}" class="btn-book">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            @else
                                <button class="btn-book" disabled>
                                    <i class="fas fa-ban me-2"></i>Currently Rented
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        @if($cars->hasPages())
            <div class="d-flex justify-content-center">
                {{ $cars->links() }}
            </div>
        @endif
    @else
        <!-- EMPTY STATE -->
        <div class="empty-state">
            <i class="fas fa-car"></i>
            <h3>No vehicles found</h3>
            <p>Try adjusting your search or filter criteria</p>
            <a href="{{ route('cars.index') }}" class="btn btn-login mt-3" style="padding: 15px 40px;">
                <i class="fas fa-redo me-2"></i>Reset Filters
            </a>
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
                    <li><a href="{{ route('cars.index', ['type' => 'sedan']) }}">Sedan</a></li>
                    <li><a href="{{ route('cars.index', ['type' => 'hatchback']) }}">Hatchback</a></li>
                    <li><a href="{{ route('cars.index', ['type' => 'mpv']) }}">MPV</a></li>
                    <li><a href="{{ route('cars.index', ['type' => 'suv']) }}">SUV</a></li>
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
    function filterByType(type) {
        const url = new URL(window.location.href);
        if (type) {
            url.searchParams.set('type', type);
        } else {
            url.searchParams.delete('type');
        }
        window.location.href = url.toString();
    }

    function filterByBrand(brand) {
        const url = new URL(window.location.href);
        if (brand) {
            url.searchParams.set('brand', brand);
        } else {
            url.searchParams.delete('brand');
        }
        window.location.href = url.toString();
    }

    // Smooth scroll animation on page load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.car-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
</script>

</body>
</html>