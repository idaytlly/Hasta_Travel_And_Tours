@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    padding-top: 70px; /* adjust based on your navbar height */
}

/* Beige Section - from header to end of card */
.beige-section {
    background-color: #F2ECEB;
    padding: 3rem 0 5rem 0;
}

.beige-section h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1f2937;
}

.beige-section p {
    font-size: 1rem;
    color: #6b7280;
}

/* Card */
.search-card {
    border: none;
    border-radius: 12px;
    background: #ffffff;
    padding: 24px;
    max-width: 1100px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

/* Form Labels & Inputs */
.form-label-custom {
    color: #6b7280;
    font-weight: 500;
    font-size: 0.875rem;
    margin-bottom: 8px;
}

.form-control-custom, .form-select {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 0.9rem;
}

.form-control-custom:focus, .form-select:focus {
    border-color: #F0785B;
    box-shadow: 0 0 0 3px rgba(240, 120, 91, 0.1);
}

/* Search Button */
.btn-search {
    background: #c62828;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px; /* make padding uniform so icon centers nicely */
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-search:hover {
    background: #f23333ff;
    color: white;
}

/* Checkbox */
.form-check-input:checked {
    background-color: #c62828;
    border-color: #c62828;
}

/* Car Cards */
.car-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 5px;
    padding: 0.8rem;
    transition: all 0.3s;
}

.car-card:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transform: translateY(-5px);
}

.car-card img {
    width: 100%;
    height: 130px;
    object-fit: contain;
    margin-bottom: 0.5rem;
}

.car-name {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.2rem;
}

.car-type {
    color: #6b7280;
    font-size: 0.75rem;
    margin-bottom: 0.5rem;
}

.car-price {
    color: #CB3737;
    font-weight: 700;
    font-size: 1.05rem;
}

.car-price small {
    font-size: 0.65rem;
    color: #9ca3af;
    font-weight: 300;
}

    .btn-view-details { 
            background: #d93025;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        width: 100%;
        text-align: center;
        display: inline-block;
        text-decoration: none;
        transition: all 0.2s ease; 
    }

    .btn-view-details:hover {
        background: #b71c1c;
        transform: translateY(-1px);
    }

    .btn-view-details:active {
        transform: scale(0.96);
    }

.section-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-weight: 700;
    font-size: 2rem;
}

</style>

<!-- Beige Section -->
<div class="beige-section">
    <div class="container">
        <!-- Heading -->
        <div class="text-center mb-4">
            <h1>Rent Car Now !</h1>
            <p>Book your ride easily and safely</p>
        </div>

        <!-- Search Card -->
        <div class="d-flex justify-content-center">
            <div class="search-card">
                <form>
                    <div class="row g-2 align-items-end">
                        <!-- Pick-up Location -->
                        <div class="col-md-3">
                            <label class="form-label-custom">Pick-up Location</label>
                            <select class="form-select form-control-custom">
                                <option selected disabled>Choose location</option>
                                <option>Hasta Office</option>
                                <option>Faculty</option>
                                <option>College</option>
                            </select>
                        </div>

                        <!-- Pick-up Date -->
                        <div class="col-md-2">
                            <label class="form-label-custom">Pick-up Date</label>
                            <input type="date" class="form-control form-control-custom">
                        </div>

                        <!-- Pick-up Time -->
                        <div class="col-md-2">
                            <label class="form-label-custom">Time</label>
                            <select class="form-select form-control-custom">
                                <option>09:00 AM</option>
                                <option>10:00 AM</option>
                                <option>11:00 AM</option>
                                <option>12:00 PM</option>
                                <option>01:00 PM</option>
                            </select>
                        </div>

                        <!-- Return Date -->
                        <div class="col-md-2">
                            <label class="form-label-custom">Return Date</label>
                            <input type="date" class="form-control form-control-custom">
                        </div>

                        <!-- Return Time -->
                        <div class="col-md-2">
                            <label class="form-label-custom">Time</label>
                            <select class="form-select form-control-custom">
                                <option>09:00 AM</option>
                                <option>10:00 AM</option>
                                <option>11:00 AM</option>
                                <option>12:00 PM</option>
                                <option>01:00 PM</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-search w-100 py-2">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>

                        <!-- Checkbox -->
                        <div class="col-md-12 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="returnLocation">
                                <label class="form-check-label text-muted" for="returnLocation" style="font-size: 0.875rem;">
                                    Return to another location
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Section Header -->
<div class="section-header" style="margin-top: 3rem;">
    <h2>Our Car Models</h2>
</div>
<div class="d-flex justify-content-center flex-wrap gap-5 mb-5">
@foreach ($vehicles as $vehicle)
    <div class="car-card" style="width: 350px;">
        <img src="{{ asset('storage/' . $vehicle->display_image) }}" alt="{{ $vehicle->name }}" 

        <h3 class="car-name">{{ $vehicle->name }}</h3>
        <p class="car-type">{{ $vehicle->vehicle_type }}</p>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="car-price">
                RM{{ number_format($vehicle->price_perHour, 2) }}
                <small>/hour</small>
            </div>
        </div>

        <!-- VIEW DETAILS BUTTON -->
        <a href="{{ route('vehicles.show', $vehicle->plate_no) }}"
           class="btn btn-view-details">
           View Details
        </a>
    </div>
@endforeach
</div>
</div>  

@endsection