<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Hasta Travel & Tours</title>
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { font-family: Arial, sans-serif; }
    .footer { background-color: #000; color: white; text-align: center; padding: 10px 0; margin-top: 40px; }
    .navbar-logo { max-height: 60px; width: auto; }
    .card img { height: 200px; object-fit: cover; }
    .profile-header { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; }
    .profile-header img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
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
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-light">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

<!-- Profile Header -->
<div class="profile-header shadow-sm">
    <div>
        <h4>{{ $user->name }}</h4>
        <p class="mb-1">Email: {{ $user->email }}</p>
        <p class="mb-1">Phone: {{ $user->phone ?? '-' }}</p>

        @if($user->usertype === 'customer' || $user->usertype === 'user')
            <p class="mb-1">IC: {{ $user->ic ?? '-' }}</p>
            <p class="mb-1">
                Address: {{ $user->street ?? '-' }}, {{ $user->city ?? '-' }}, {{ $user->state ?? '-' }}, {{ $user->postcode ?? '-' }}
            </p>
            <p class="mb-0">Driver License: {{ $user->license_no ?? '-' }}</p>
        @endif
    </div>
    <div class="ms-auto">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
    </div>
</div>


  <!-- Your Bookings -->
  <h3 class="mt-4 mb-3">Your Bookings</h3>

  @if($bookings->count())
    <div class="row">
      @foreach($bookings as $booking)
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <img src="{{ $booking->car->image_url }}" class="card-img-top" alt="{{ $booking->car->name }}">
            <div class="card-body">
              <h5 class="card-title">{{ $booking->car->name }}</h5>
              <p class="card-text">
                <strong>Start:</strong> {{ $booking->start_date->format('d M Y') }}<br>
                <strong>End:</strong> {{ $booking->end_date->format('d M Y') }}<br>
                <strong>Status:</strong> {{ ucfirst($booking->status) }}
              </p>
              @if($booking->document_path)
                <p><a href="{{ asset('storage/' . $booking->document_path) }}" target="_blank">View Document</a></p>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <p>You have no bookings yet.</p>
  @endif
</div>

<!-- Footer -->
<div class="footer">
  <p class="mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours. All Rights Reserved.</p>
</div>

</body>
</html>
