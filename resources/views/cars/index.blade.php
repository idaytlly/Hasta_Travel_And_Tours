<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Our vehicles</title>

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


        .brand-card { 
            width: 120px;      
            height: 80px;      
            background: #fff;  
            border-radius: 15px;
            border: 1px solid #eee;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin: auto; 
            overflow:hidden;
        }
        
        .brand-card img{
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            display:block;
        }

        .brand-card:hover { 
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .car-card { 
            border-radius: 1.5rem; 
            border: 1px solid #eee; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .car-card img { max-height: 200px; object-fit: contain; }
        .btn-category { 
            border: 2px solid #c62828;
            background :  #fff;
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
                        <a class="nav-link nav-link-hasta " href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hasta active" href="{{ route('cars.index') }}">vehicles</a>
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
                    <a href="{{ route('login') }}" class="btn btn-outline-danger" style="padding: 10px 25px; border-radius: 30px; border: 2px solid #e53935; color: #e53935;">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-login" style="background: #e53935; color: white; padding: 10px 25px; border-radius: 30px;">
                        Register
                    </a>
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
        </div>
    </nav>
    <!-- BRAND LOGOS -->
    <div class="container my-5">
        <div class="row justify-content-center g-4">
            @php
                $brandLogos = [
                    'toyota' => 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Toyota_EU.svg',
                    'hyundai' => 'https://upload.wikimedia.org/wikipedia/commons/4/44/Hyundai_Motor_Company_logo.svg',
                    'proton' => 'https://logos-world.net/wp-content/uploads/2022/12/Proton-Logo-500x281.png',
                    'perodua' => 'https://cdn.worldvectorlogo.com/logos/perodua.svg'
                ];
            @endphp
            @foreach($brandLogos as $name => $url)
            <div class="col-6 col-sm-3 col-md-2 text-center">
                <a href="{{ route('cars.index', ['brand' => $name]) }}" class="brand-card d-flex align-items-center justify-content-center">
                    <img src="{{ $url }}" alt="{{ ucfirst($name) }}" class="img-fluid" style="max-height:60px;">
                </a>
            </div>
            @endforeach
        </div>
    </div>

    @php
    $currentCarType = request('carType');
    @endphp

<div class="container mb-4 text-center">
    <div class="btn-group" role="group">
        <a href="{{ route('cars.index') }}" class="btn btn-category {{ is_null($currentCarType) ? 'active' : '' }}">
            All vehicles <i class="fas fa-car"></i>
        </a>
        <a href="{{ route('cars.index', ['carType' => 'Sedan']) }}" class="btn btn-category {{ $currentCarType === 'Sedan' ? 'active' : '' }}">
            Sedan
        </a>
        <a href="{{ route('cars.index', ['carType' => 'Hatchback']) }}" class="btn btn-category {{ $currentCarType === 'Hatchback' ? 'active' : '' }}">
            Hatchback
        </a>
        <a href="{{ route('cars.index', ['carType' => 'MPV']) }}" class="btn btn-category {{ $currentCarType === 'MPV' ? 'active' : '' }}">
            MPV
        </a>
        <a href="{{ route('cars.index', ['carType' => 'SUV']) }}" class="btn btn-category {{ $currentCarType === 'SUV' ? 'active' : '' }}">
            SUV
        </a>
    </div>
</div>


    <!-- CAR CARDS -->
    <div class="container mb-5">
        <div class="row g-4">
            @forelse($vehicle as $vehicle)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="car-card overflow-hidden">
                    <div class="bg-light text-center p-3">
                        <img src="{{ $vehicle->image }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}" class="img-fluid">
                    </div>
                    <div class="p-4">
                        <h5 class="fw-bold">{{ $vehicle->brand }} {{ $vehicle->model }} {{ $vehicle->year }}</h5>
                        <p class="text-muted">{{ $vehicle->transmission }}</p>
                        <div class="d-flex align-items-baseline mb-3">
                            <span class="text-danger fw-bold fs-4">RM{{ number_format($vehicle->daily_rate, 0) }}</span>
                            <span class="text-muted ms-2">per day</span>
                        </div>
                        <div class="mb-3 text-muted small d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark"><i class="fas fa-cog text-warning"></i> {{ $vehicle->transmission }}</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-gas-pump text-warning"></i> {{ $vehicle->fuel_type }}</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-snowflake text-warning"></i> {{ $vehicle->air_conditioner ? 'AC' : 'No AC' }}</span>
                        </div>
                        <a href="{{ route('cars.show', $vehicle->id) }}" class="btn btn-warning w-100 text-white fw-bold">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5 fw-bold">No vehicles found in this category.</p>
            </div>
            @endforelse
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
                            <span>hastatravelandtours@gmail.com</span>
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
                        <li><a href="{{ route('cars.index') }}">vehicles</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2 footer-links">
                    <h5>vehicles</h5>
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
