<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasta Travel & Tours - Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  
  <style>
    body { font-family: Arial, sans-serif; }
    .footer { background-color: #000; color: white; text-align: center; padding: 10px 0; margin-top: 40px; }
    .hero { background: url('{{ asset("images/car-hero.jpg") }}') center/cover no-repeat; height: 400px;
      display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
      text-shadow: 2px 2px 6px black; color: white; margin-bottom: 50px; }
    .hero h1, .hero h4 { color: #fff !important; }
    .navbar-logo { max-height: 60px; width: auto; }
    .card img { height: 200px; object-fit: cover; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">
      <img src="{{ asset('logo.png') }}" class="navbar-logo" alt="Logo">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav ms-auto"> <!-- all nav items right-aligned -->
        <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>

        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="nav-link btn btn-link text-light">Logout</button>
            </form>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="hero">
  <h1>Drive Your Dream Car Today!</h1>
  <h4>We believe your rental car should enhance your trip, not just be a part of it.</h4>
</div>

<!-- Featured Vehicles -->
<div class="container">
  <h2 class="mb-4">Featured Vehicles</h2>
  <div class="row">
    @php
      $cars = [
        (object)['id'=>1,'name'=>'Honda Civic','price_per_day'=>120,'image_url'=>asset('car_images/civic.jpg')],
        (object)['id'=>2,'name'=>'Toyota Camry','price_per_day'=>150,'image_url'=>asset('car_images/camry.jpg')]
      ];
    @endphp

    @foreach($cars as $car)
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="{{ $car->image_url }}" class="card-img-top" alt="{{ $car->name }}">
          <div class="card-body">
            <h5 class="card-title">{{ $car->name }}</h5>
            <p class="card-text">${{ number_format($car->price_per_day, 2) }} / day</p>

            @guest
              <a href="{{ route('register') }}" class="btn btn-primary w-100">Book Now</a>
            @else
              <a href="{{ route('booking.create', $car->id) }}" class="btn btn-primary w-100">Book Now</a>
            @endguest
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p class="mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours. All Rights Reserved.</p>
</div>

</body>
</html>
