<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management - HASTA Staff</title>

    <!-- Bootstrap CSS -->
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

        .car-card { 
            border-radius: 1.5rem; 
            border: 1px solid #eee; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .car-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        .car-card img { 
            max-height: 200px; 
            object-fit: contain; 
        }

        .btn-category { 
            border: 2px solid #c62828;
            background: #fff;
            color: #c62828;
            border-radius: 50px; 
            padding: 10px 20px;
            font-weight: bold; 
            transition: all 0.3s ease;
        }

        .btn-category.active, .btn-category:hover { 
            background-color: #c62828; 
            color: #fff; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #c62828; 
        }

        .floating-add-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 12px rgba(229, 57, 53, 0.4);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 100;
        }

        .floating-add-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(229, 57, 53, 0.6);
            color: white;
        }

        .alert-success {
            border-radius: 15px;
            border-left: 4px solid #28a745;
        }

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

        .btn-edit {
            background: #ffc107;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: #ffb300;
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: #c82333;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body style="padding-top:90px;">

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
                        <a class="nav-link nav-link-hasta active" href="{{ route('staff.cars.index') }}">Vehicle Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hasta" href="#">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hasta" href="#">Reports</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-danger" style="padding: 10px 25px; border-radius: 30px; border: 2px solid #e53935; color: #e53935;">
                        Login
                    </a>
                    @else
                    <span class="me-2">Welcome, <strong>Staff</strong></span>
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

    <div class="container my-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark mb-2">Vehicle Management</h2>
                <p class="text-muted">Manage your fleet of vehicles</p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Category Filters -->
        <div class="text-center mb-5">
            <div class="btn-group" role="group">
                <a href="{{ route('staff.cars.index') }}" class="btn btn-category {{ !request('type') ? 'active' : '' }}">
                    All Vehicles <i class="fas fa-car ms-1"></i>
                </a>
                <a href="{{ route('staff.cars.index', ['type' => 'sedan']) }}" class="btn btn-category {{ request('type') === 'sedan' ? 'active' : '' }}">
                    Sedan
                </a>
                <a href="{{ route('staff.cars.index', ['type' => 'hatchback']) }}" class="btn btn-category {{ request('type') === 'hatchback' ? 'active' : '' }}">
                    Hatchback
                </a>
                <a href="{{ route('staff.cars.index', ['type' => 'mpv']) }}" class="btn btn-category {{ request('type') === 'mpv' ? 'active' : '' }}">
                    MPV
                </a>
                <a href="{{ route('staff.cars.index', ['type' => 'suv']) }}" class="btn btn-category {{ request('type') === 'suv' ? 'active' : '' }}">
                    SUV
                </a>
            </div>
        </div>

        <!-- Car Cards -->
        <div class="row g-4">
            @forelse($cars as $car)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="car-card overflow-hidden bg-white">
                    <div class="bg-light text-center p-3">
                        @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="img-fluid">
                        @else
                        <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-car text-muted" style="font-size: 4rem;"></i>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h5 class="fw-bold">{{ $car->brand }} {{ $car->model }} {{ $car->year }}</h5>
                        <p class="text-muted text-capitalize">{{ $car->carType ?? 'Hatchback' }}</p>
                        <div class="d-flex align-items-baseline mb-3">
                            <span class="text-danger fw-bold fs-4">RM{{ number_format($car->daily_rate, 0) }}</span>
                            <span class="text-muted ms-2">per day</span>
                        </div>
                        <div class="mb-3 text-muted small d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark"><i class="fas fa-cog text-warning"></i> {{ ucfirst($car->transmission ?? 'Automat') }}</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-gas-pump text-warning"></i> {{ $car->fuel_type ?? 'RON 95' }}</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-snowflake text-warning"></i> {{ $car->air_conditioner ? 'AC' : 'No AC' }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('staff.cars.edit', $car->id) }}" class="btn btn-edit flex-grow-1">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('staff.cars.destroy', $car->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete {{ $car->brand }} {{ $car->model }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-car text-muted mb-3" style="font-size: 5rem;"></i>
                <h3 class="fw-bold text-muted mb-3">No vehicles found</h3>
                <p class="text-muted mb-4">Start by adding your first vehicle</p>
                <a href="{{ route('staff.cars.create') }}" class="btn btn-login">
                    <i class="fas fa-plus me-2"></i> Add New Vehicle
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Floating Add Button -->
    <a href="{{ route('staff.cars.create') }}" class="floating-add-btn" title="Add New Vehicle">
        <i class="fas fa-plus"></i>
    </a>

    <!-- FOOTER -->
    <footer id="footer-hasta" class="footer-hasta mt-5">
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
                        <li><a href="{{ route('staff.cars.index') }}">Vehicle Management</a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-hasta');
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });
    </script>

</body>
</html>