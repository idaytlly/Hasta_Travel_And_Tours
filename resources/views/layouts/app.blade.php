<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Hasta Travel & Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        
        /* NAVBAR */
        .navbar-custom { 
            background: #d84444; 
            padding: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .navbar-custom .container { padding: 12px 20px; }
        .navbar-brand { 
            background: white; 
            color: #d84444 !important; 
            padding: 8px 20px; 
            font-weight: 700; 
            font-size: 1.5rem;
            border-radius: 4px;
            letter-spacing: 2px;
        }
        .nav-icon { 
            color: #d84444; 
            background: rgba(255,255,255,0.2);
            width: 50px; 
            height: 50px; 
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .nav-icon:hover, .nav-icon.active { 
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        .nav-icon i { font-size: 20px; color: white; }
        .btn-login { 
            background: #c73030; 
            color: white; 
            border: none;
            padding: 8px 30px;
            border-radius: 6px;
            font-weight: 600;
            margin-left: 10px;
        }
        .btn-login:hover { background: #b02828; }
        
        /* FOOTER */
        .footer { 
            background: #d84444; 
            color: white; 
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        .footer-icon { 
            width: 40px; 
            height: 40px; 
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .footer-icon i { font-size: 18px; }
        .footer h6 { font-weight: 700; margin-bottom: 10px; }
        .footer p, .footer a { 
            color: rgba(255,255,255,0.9); 
            text-decoration: none;
            font-size: 14px;
        }
        .footer a:hover { color: white; }
        .social-icons a { 
            color: white; 
            font-size: 20px; 
            margin-right: 15px;
            transition: all 0.3s;
        }
        .social-icons a:hover { transform: scale(1.2); }
    </style>
    @stack('styles')
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">HASTA</a>
            
            <div class="d-flex align-items-center">
                <a href="{{ route('home') }}" class="nav-icon {{ request()->routeIs('home') ? 'active' : '' }}" title="Home">
                    <i class="fas fa-home"></i>
                </a>
                <a href="#" class="nav-icon" title="Notifications">
                    <i class="fas fa-bell"></i>
                </a>
                <a href="#" class="nav-icon" title="Dashboard">
                    <i class="fas fa-th"></i>
                </a>
                <a href="{{ route('cars.index') }}" class="nav-icon {{ request()->routeIs('cars.*') ? 'active' : '' }}" title="Vehicle Listing">
                    <i class="fas fa-car"></i>
                </a>
                <a href="#" class="nav-icon" title="History">
                    <i class="fas fa-history"></i>
                </a>
                <a href="#" class="nav-icon" title="Settings">
                    <i class="fas fa-cog"></i>
                </a>
                
                @guest
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                @else
                    <div class="dropdown">
                        <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=d84444&color=fff" 
                                 class="rounded-circle" width="45" height="45">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <div class="footer-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h6>Address</h6>
                            <p>Student Mall UTM<br>Skudai, 81300, Johor Bahru</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <div class="footer-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h6>Email</h6>
                            <p>hastatravel@gmail.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <div class="footer-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <h6>Phone</h6>
                            <p>011-1090 0700</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <img src="{{ asset('logo-white.png') }}" height="40" alt="HASTA" class="mb-3">
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6>Useful Links</h6>
                    <p><a href="#">About us</a></p>
                    <p><a href="#">Contact us</a></p>
                    <p><a href="#">Gallery</a></p>
                    <p><a href="#">Blog</a></p>
                    <p><a href="#">F.A.Q</a></p>
                </div>
                <div class="col-md-3">
                    <h6>Vehicles</h6>
                    <p><a href="#">Sedan</a></p>
                    <p><a href="#">Hatchback</a></p>
                    <p><a href="#">MPV</a></p>
                    <p><a href="#">Minivan</a></p>
                    <p><a href="#">SUV</a></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>