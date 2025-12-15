<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        html, body { height: 100%; margin: 0; }

        /* HERO BACKGROUND */
        .hero-background {
            background: url('{{ asset("images/car-hero.jpg") }}') center / cover no-repeat fixed;
            min-height: 100vh;
            position: relative;
            color: #fff;
        }

        .hero-background::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1;
        }

        nav, .hero-content { position: relative; z-index: 2; }

        .hero-content {
            min-height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 1rem;
        }

        .navbar { background-color: rgba(2,2,3,0.95) !important; }
        .footer { background: rgba(0,0,0,0.85); color: #fff; text-align: center; padding: 10px 0; }

        .navbar-nav.flex-row { flex-direction: row !important; }
        @media (max-width: 991px) { .navbar-nav.flex-row { flex-direction: row !important; margin-top: 0.5rem; } }
    </style>
</head>
<body class="font-sans antialiased">

<div class="hero-background">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">

            <a class="navbar-brand fw-bold" href="{{ route('home') }}">Hasta Travel & Tours</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarColor01">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">

                <!-- LEFT MENU -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cars.index') }}">Cars</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                </ul>

                <!-- RIGHT SIDE: ROLE-BASED BUTTONS -->
                <ul class="navbar-nav ms-auto flex-row gap-2 mb-0">
                    @auth
                        @if(Auth::user()->role === 'staff')
                            <li class="nav-item"><a href="{{ route('staff.dashboard') }}" class="btn btn-warning btn-sm">Dashboard</a></li>
                            <li class="nav-item"><a href="{{ route('staff.cars') }}" class="btn btn-primary btn-sm">Manage Cars</a></li>
                            <li class="nav-item"><a href="{{ route('staff.bookings') }}" class="btn btn-success btn-sm">Bookings</a></li>
                        @elseif(Auth::user()->role === 'admin')
                            <li class="nav-item"><a href="{{ route('admin.home') }}" class="btn btn-warning btn-sm">Admin Home</a></li>
                        @else
                            <li class="nav-item"><a href="{{ route('dashboard') }}" class="btn btn-warning btn-sm">Dashboard</a></li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="btn btn-secondary btn-sm">Register</a></li>
                    @endauth
                </ul>

            </div>
        </div>
    </nav>

    <!-- HERO CONTENT -->
    <div class="hero-content">
        <h1 class="display-4 fw-bold mb-3">Drive Your Dream Car Today</h1>
        <p class="lead mb-4">Affordable • Reliable • Premium Experience</p>
        <a href="{{ route('cars.index') }}" class="btn btn-warning btn-lg px-5">Book Now</a>
    </div>

</div>

<!-- PAGE CONTENT -->
<main class="py-5">
    {{ $slot }}
</main>

<!-- FOOTER -->
<div class="footer">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours. All Rights Reserved.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts

</body>
</html>
