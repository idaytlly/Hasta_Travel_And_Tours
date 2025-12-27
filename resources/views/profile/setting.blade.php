<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Hasta Travel & Tours</title>
    
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

        /* PROFILE FORM */
        .profile-card { background: var(--white); padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); max-width: 700px; margin: 0 auto; }
        .profile-card h2 { font-weight: 700; margin-bottom: 30px; color: var(--text-dark); text-align:center; }
        .profile-card .form-control { border-radius: 12px; padding: 12px 15px; border: 2px solid #eee; transition: all 0.3s ease; }
        .profile-card .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(229,57,53,0.1); }
        .btn-update { background: var(--primary); color: var(--white); border-radius: 12px; padding: 12px 30px; font-weight: 600; width: 100%; transition: all 0.3s ease; border:none; }
        .btn-update:hover { background: var(--primary-dark); color: var(--white); }

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
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="{{ route('cars.index') }}">Notifications</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="#">Vehicle Listing</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta active">Settings</a></li>
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

<!-- PROFILE UPDATE FORM -->
<section class="py-5" style="min-height:80vh;">
    <div class="container">
        <div class="profile-card">
            <h2>Update Your Profile</h2>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                </div>
                @if($user->usertype === 'customer' || $user->usertype === 'user')
                    <div class="mb-3"><label class="form-label">IC</label><input type="text" name="ic" class="form-control" value="{{ $user->ic }}"></div>
                    <div class="mb-3"><label class="form-label">License No</label><input type="text" name="license_no" class="form-control" value="{{ $user->license_no }}"></div>
                    <div class="mb-3"><label class="form-label">Street</label><input type="text" name="street" class="form-control" value="{{ $user->street }}"></div>
                    <div class="mb-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ $user->city }}"></div>
                    <div class="mb-3"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ $user->state }}"></div>
                    <div class="mb-3"><label class="form-label">Postcode</label><input type="text" name="postcode" class="form-control" value="{{ $user->postcode }}"></div>
                @endif
                <button type="submit" class="btn btn-update">Update Details</button>
            </form>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer-hasta">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="footer-brand">HASTA</div>
                <p class="footer-text">Your trusted partner for car rental services in Malaysia. Quality vehicles, affordable prices.</p>
                <div class="footer-contact">
                    <div class="footer-contact-item"><i class="fas fa-map-marker-alt"></i><span>Student Mall UTM, Skudai, 81300, Johor Bahru</span></div>
                    <div class="footer-contact-item"><i class="fas fa-envelope"></i><span>hastatravel@gmail.com</span></div>
                    <div class="footer-contact-item"><i class="fas fa-phone"></i><span>011-1090 0700</span></div>
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
        if (window.scrollY > 50) navbar.classList.add('scrolled'); else navbar.classList.remove('scrolled');
    });
</script>

</body>
</html>
