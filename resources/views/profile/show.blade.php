<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Travel & Tours - My Profile</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #e53935;
            --primary-dark: #c62828;
            --text-dark: #333;
            --bg-body: #f4f7f6; /* Light gray background like the screenshot */
            --white: #fff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            padding-top: 100px;
        }

        /* --- NAVBAR --- */
.navbar-hasta {
    background: var(--white);
    /* Increase height to match your original design */
    min-height: 80px; 
    padding: 15px 0; 
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    position: fixed;
    top: 0; 
    width: 100%; 
    z-index: 1000;
}

.logo-image {
    /* Set this back to the larger size from your screenshot */
    height: 100px; 
    width: auto;
    max-width: 150px;
    object-fit: contain;
}

.nav-link-hasta {
    color: var(--text-dark) !important;
    font-weight: 500;
    padding: 10px 20px !important; /* Added more padding for a larger feel */
    transition: all 0.3s ease;
}

        /* --- PROFILE CONTAINER --- */
        .profile-main-card {
            max-width: 900px;
            margin: 0 auto 50px;
            background: var(--white);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .page-title {
            color: var(--primary);
            font-weight: 800;
            font-size: 2.2rem;
            text-align: center;
            margin-bottom: 5px;
        }

        .page-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 40px;
        }

        .section-title {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 25px;
            margin-top: 10px;
        }

        /* --- INFO DISPLAY BOXES --- */
        .info-label {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 8px;
            color: #000;
            display: block;
        }

        .info-box {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 15px;
            background-color: #fcfcfc;
            min-height: 48px;
            color: #444;
            margin-bottom: 20px;
        }

        .info-box.empty {
            color: #adb5bd;
            font-style: italic;
        }

        /* --- BUTTONS --- */
        .btn-edit-profile {
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-edit-profile:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
        }

        .btn-back {
            background: white;
            color: #6c757d;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            border: 1px solid #dee2e6;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #f8f9fa;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-hasta">
    <div class="container">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/hasta logo.png') }}" alt="Logo" class="logo-image">
        </a>
        <div class="collapse navbar-collapse justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="{{ route('cars.index') }}">Vehicles</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="{{ route('aboutus') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta" href="{{ route('contactus') }}">Contact</a></li>
                <li class="nav-item"><a class="nav-link nav-link-hasta active" href="{{ route('profile.show') }}">Profile</a></li>
            </ul>
        </div>
        <div class="d-flex align-items-center">
            <span class="me-3">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 20px;">
                   <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>

@php
    $address = $user->customer->address ?? '';
    $addressParts = explode(',', $address);
    $street = trim($addressParts[0] ?? '');
    $city = trim($addressParts[1] ?? '');
    $state = trim($addressParts[2] ?? '');
    $postcode = trim($addressParts[3] ?? '');
@endphp

<div class="container">
    <div class="profile-main-card">
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Your account information and details</p>

        <h2 class="section-title">Personal Information</h2>
        <div class="row">
            <div class="col-md-6">
                <label class="info-label">Full Name</label>
                <div class="info-box">{{ $user->name }}</div>
            </div>
            <div class="col-md-6">
                <label class="info-label">Identification Card</label>
                <div class="info-box {{ !$user->customer || !$user->customer->ic ? 'empty' : '' }}">
                    {{ $user->customer->ic ?? 'Not provided' }}
                </div>
            </div>
            <div class="col-md-6">
                <label class="info-label">Email Address</label>
                <div class="info-box">{{ $user->email }}</div>
            </div>
            <div class="col-md-6">
                <label class="info-label">Phone Number</label>
                <div class="info-box {{ !$user->phone ? 'empty' : '' }}">
                    {{ $user->phone ?? 'Not provided' }}
                </div>
            </div>
            <div class="col-md-6">
                <label class="info-label">Driver License</label>
                <div class="info-box {{ !$user->customer || !$user->customer->licenceNo ? 'empty' : '' }}">
                    {{ $user->customer->licenceNo ?? 'Not provided' }}
                </div>
            </div>
        </div>

        <hr class="my-4" style="opacity: 0.1;">

        <h2 class="section-title">Address Information</h2>
        <div class="row">
            <div class="col-md-12">
                <label class="info-label">Street Address</label>
                <div class="info-box {{ !$street ? 'empty' : '' }}">{{ $street ?: 'Not provided' }}</div>
            </div>
            <div class="col-md-4">
                <label class="info-label">City</label>
                <div class="info-box {{ !$city ? 'empty' : '' }}">{{ $city ?: 'Not provided' }}</div>
            </div>
            <div class="col-md-4">
                <label class="info-label">State</label>
                <div class="info-box {{ !$state ? 'empty' : '' }}">{{ $state ?: 'Not provided' }}</div>
            </div>
            <div class="col-md-4">
                <label class="info-label">Postcode</label>
                <div class="info-box {{ !$postcode ? 'empty' : '' }}">{{ $postcode ?: 'Not provided' }}</div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('home') }}" class="btn btn-back">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
            <a href="{{ route('profile.edit') }}" class="btn btn-edit-profile">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
        </div>
    </div>
</div>

</body>
</html>