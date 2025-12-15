<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hasta Travel & Tours</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    html, body { height: 100%; margin:0; }
    .hero { 
        background: url('images/car-hero.jpg') center / cover no-repeat;
        height: 100vh; color: #fff; display:flex;
        justify-content:center; align-items:center; flex-direction:column; text-align:center;
        position:relative;
    }
    .hero::before {
        content:""; position:absolute; inset:0; background: rgba(0,0,0,0.6); z-index:1;
    }
    .hero-content { position:relative; z-index:2; }
</style>
</head>
<body>

<div class="hero">
    <div class="hero-content">
        <h1 class="display-4 mb-3">Drive Your Dream Car Today!</h1>
        <p class="lead mb-4">Affordable • Reliable • Premium Experience</p>
        @if (Route::has('login'))
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-lg px-4">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 me-2">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4">Register</a>
                @endif
            @endauth
        @endif
    </div>
</div>

</body>
</html>
