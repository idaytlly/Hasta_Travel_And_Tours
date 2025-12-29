<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle | Hasta Travel & Tours</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Reuse all styles from your index.blade.php */
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
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); overflow-x:hidden; padding-top: 80px; }

        /* NAVBAR */
        .navbar-hasta { background: var(--white); padding: 15px 0; box-shadow: 0 2px 20px rgba(0,0,0,0.08); position: fixed; top: 0; width: 100%; z-index: 1000; transition: all 0.3s ease; }
        .navbar-hasta.scrolled { padding: 10px 0; box-shadow: 0 5px 30px rgba(0,0,0,0.1); }
        .logo-text { font-size: 2rem; font-weight: 800; color: var(--primary); text-decoration: none; letter-spacing: 2px; }
        .logo-text:hover { color: var(--primary-dark); }
        .nav-link-hasta { color: var(--text-dark) !important; font-weight: 500; padding: 10px 20px !important; border-radius: 25px; transition: all 0.3s ease; margin:0 5px; }
        .nav-link-hasta:hover { color: var(--primary) !important; }
        .nav-link-hasta.active { background: var(--primary); color: var(--white) !important; }
        .btn-login { background: var(--primary); color: var(--white); padding: 12px 35px; border-radius: 30px; font-weight: 600; border:none; transition: all 0.3s ease; }
        .btn-login:hover { background: var(--primary-dark); color: var(--white); transform: translateY(-2px); box-shadow: 0 5px 20px rgba(229,57,53,0.4); }
       /* FOOTER */
        .footer-hasta { background: var(--dark); color: var(--white); padding: 80px 0 30px; }
        .footer-brand { font-size: 2rem; font-weight: 800; color: var(--white); margin-bottom: 20px; }
        .footer-brand .star { color: var(--primary); }
        .footer-text { color: rgba(255,255,255,0.7); margin-bottom: 25px; max-width: 300px; }
        .footer-contact { margin-bottom: 25px; }
        .footer-contact-item { display:flex; align-items:center; gap:15px; margin-bottom:15px; color: rgba(255,255,255,0.8); }
        .footer-contact-item i { width:40px; height:40px; background: var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; }
        .footer-links h5 { color: var(--white); font-weight:600; margin-bottom:25px; }
        .footer-links ul { list-style:none; padding:0; }
        .footer-links ul li { margin-bottom:12px; }
        .footer-links ul li a { color: rgba(255,255,255,0.7); text-decoration:none; transition: all 0.3s ease; }
        .footer-links ul li a:hover { color: var(--primary); padding-left:5px; }
        .footer-bottom { text-align:center; padding-top:30px; margin-top:50px; border-top:1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); }
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
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" >Notifications</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta active">Vehicle Listing</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="{{ route('profile.settings') }}">Settings</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-login"><i class="fas fa-user me-2"></i>Login</a>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-login"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>

<!-- VEHICLE LIST -->
<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Available Vehicles</h2>
            <p class="text-muted">Choose the perfect vehicle for your journey</p>
        </div>

        <div class="row g-4">

            @forelse($cars as $car)
                <div class="col-md-6 col-lg-4">
                    <div class="vehicle-card">

                        <img src="{{ asset('storage/' . $car->image) }}"
                             class="vehicle-img"
                             alt="{{ $car->model }}">

                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-0">{{ $car->brand }} {{ $car->model }}</h5>
                                <span class="badge-type">{{ strtoupper($car->type) }}</span>
                            </div>

                            <p class="text-muted mb-2">
                                <i class="fas fa-users me-1"></i> {{ $car->seats }} Seats
                                &nbsp; | &nbsp;
                                <i class="fas fa-cogs me-1"></i> {{ $car->transmission }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="price">RM {{ number_format($car->price_per_day, 2) }}/day</span>

                                <a href="{{ route('booking.create', $car->id) }}" class="btn btn-rent">
                                    <i class="fas fa-car me-1"></i> Rent Now
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No vehicles available at the moment.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
