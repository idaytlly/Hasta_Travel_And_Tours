<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - Car Rental</title>
    
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
                justify-content: center;
                padding-right: 0;
                margin-top: 40px;
            }
            
            .hero-image img {
                max-width: 320px;
                transform: scale(1);
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
                        <a class="nav-link nav-link-hasta " href="{{ route('cars.index') }}">Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hasta" href="#footer-hasta">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hasta" href="#footer-hasta">Contact</a>
                    </li>

                    @auth
                    <li class="nav-item">
                        <a class="nav-link nav-link-hasta active" href="{{ route('profile.edit') }}">Profile</a>
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
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white text-center border-0 py-4">
                    <h3 class="fw-bold" style="color:#e53935;">Edit Profile</h3> <!-- Hasta red -->
                    <p class="text-muted mb-0">Update your account information</p>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            {{-- Full Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- IC Number --}}
                            <div class="col-md-6">
                                <label for="ic_number" class="form-label fw-semibold">IC Number</label>
                                <input type="text" class="form-control @error('ic') is-invalid @enderror" id="ic" name="ic" value="{{ old('ic', auth()->user()->ic) }}">
                                @error('ic')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Driver License --}}
                            <div class="col-md-6">
                                <label for="license" class="form-label fw-semibold">Driver License</label>
                                <input type="text" class="form-control @error('license_no') is-invalid @enderror" id="license_no" name="license_no" value="{{ old('license_no', auth()->user()->license_no) }}">
                                @error('license_no')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Street Address --}}
                            <div class="col-md-6">
                                <label for="street" class="form-label fw-semibold">Street Address</label>
                                <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street', auth()->user()->street) }}">
                                @error('street')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Postcode --}}
                            <div class="col-md-4">
                                <label for="postcode" class="form-label fw-semibold">Postcode</label>
                                <input type="text" class="form-control @error('postcode') is-invalid @enderror" id="postcode" name="postcode" value="{{ old('postcode', auth()->user()->postcode) }}">
                                @error('postcode')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-4">
                                <label for="city" class="form-label fw-semibold">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', auth()->user()->city) }}">
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- State --}}
                            <div class="col-md-4">
                                <label for="state" class="form-label fw-semibold">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', auth()->user()->state) }}">
                                @error('state')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('home') }}" class="btn btn-outline-danger rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-login rounded-pill px-4">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

