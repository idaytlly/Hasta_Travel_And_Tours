<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - Car Listings</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/lux/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Base styles */
        body {
            margin: 0;
            padding-top: 56px; 
            padding-bottom: 60px; 
        }
        
        /* HEADER/FOOTER STYLES */
        .navbar-black {
            background-color: #000 !important; 
        }
        .navbar-black .navbar-brand,
        .navbar-black .nav-link,
        .navbar-black .btn-outline-light {
            color: #fff !important;
        }
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #000; 
            color: white;
            text-align: center;
            padding: 10px 0;
            z-index: 1030;
        }

        /* CAR CARD STYLES */
        .car-card-img {
            height: 200px; 
            object-fit: cover;
            position: relative;
            overflow: hidden; 
        }
        .tag-new {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #dc3545; 
            color: white;
            padding: 5px 10px;
            font-size: 0.8em;
            font-weight: bold;
            z-index: 10;
            clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%); 
        }
        .card-body {
            padding: 15px;
            border-top: 1px solid #eee;
        }
        .card-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
            margin-top: 10px;
        }
        .detail-item {
            display: inline-block;
            margin-right: 15px;
            font-size: 0.9em;
            color: #6c757d;
        }
        .card-detail-icon {
            font-size: 1.1rem;
            margin-right: 5px;
            color: #0d6efd; 
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-black fixed-top"> 
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">Hasta Travel & Tours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('cars.index') }}">Car Listings</a></li> 
                <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
            @if (Route::has('login'))
                @auth
                    <li class="nav-item">
                        <a href="{{ route('profile.setting') }}" class="btn btn-warning me-2">Settings</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Log in</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                        </li>
                    @endif
                @endauth
            @endif
        </ul>


            <form class="d-flex ms-3">
                <input class="form-control me-sm-2" type="search" placeholder="Search Cars">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-5">
    
    <h1 class="text-center mb-5">Browse Our Available Fleet</h1>

    <h3 class="text-center mb-4">Category Filters</h3>
    <div class="d-flex justify-content-center mb-5 flex-wrap">
        <button class="btn btn-primary mx-2 my-1 active">
            <i class="fas fa-car-side"></i> All Vehicles
        </button>
        <button class="btn btn-outline-primary mx-2 my-1">
            <i class="fas fa-car"></i> Sedan
        </button>
        <button class="btn btn-outline-primary mx-2 my-1">
            <i class="fas fa-car-side"></i> Hatchback
        </button>
        <button class="btn btn-outline-primary mx-2 my-1">
            <i class="fas fa-shuttle-van"></i> MPV
        </button>
    </div>
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        
        @forelse ($cars as $car)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    
                    <div class="car-card-img">
                        @if ($car->year >= date('Y') - 1)
                            <span class="tag-new">NEW</span>
                        @endif
                        <img src="{{ asset('car_images/' . $car->image) }}" class="card-img-top car-card-img" alt="{{ $car->model }}">
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $car->brand }} {{ $car->model }}</h5>
                        <p class="card-text text-muted mb-2">{{ $car->year }} Model</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                             <p class="card-price mb-0">${{ number_format($car->daily_rate, 2) }} <small class="text-muted fs-6 fw-normal">/ day</small></p>
                            <small class="text-secondary">Reg Date: {{ $car->created_at ? $car->created_at->format('Y-m-d') : 'N/A' }}</small>
                        </div>

                        <div class="mb-3">
                            <span class="detail-item"><i class="card-detail-icon fas fa-snowflake"></i> AC</span>
                            <span class="detail-item"><i class="card-detail-icon fas fa-chair"></i> Seats: 4</span> 
                            <span class="detail-item"><i class="card-detail-icon fas fa-cogs"></i> {{ $car->transmission }}</span>
                        </div>

                        <a href="#" class="btn btn-primary mt-auto">Car Details & Booking</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    We currently have no cars available for rent.
                </div>
            </div>
        @endforelse
    </div>
</div>

<div class="footer">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours. All rights reserved.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>