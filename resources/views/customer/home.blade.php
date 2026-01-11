@extends('layouts.app')

@section('title', 'Home')

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
    border-radius: 10px;
    background: #ffffff;
    padding: 16px;
    max-width: 900px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
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
    background: #C27D72;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 32px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-search:hover {
    background: #e06849;
    color: white;
}

/* Checkbox */
.form-check-input:checked {
    background-color: #F0785B;
    border-color: #F0785B;
}

/* Car Cards */
.car-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 0.6rem;
    padding-top: 36px; /* space for the image to overflow */
    transition: all 0.25s;
}

    .car-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-5px);
    }

    /* Standardize car thumbnail size so all cards look identical */
    .car-card img {
        width: 140px;
        height: 100px;
        object-fit: contain;
        margin-bottom: 0.5rem;
        margin-top: -20px; /* lift image slightly above the card */
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

.car-name {
    font-weight: 700;
    font-size: 0.95rem;
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
    font-size: 1rem;
}

.car-price small {
    font-size: 0.65rem;
    color: #9ca3af;
    font-weight: 300;
}

.btn-view-details {
    background: #CB3737;
    color: white;
    border: none;
    padding: 6px;
    justify-content: right;
    font-size: 0.8rem;
    width: 100%;
    transition: all 0.2s;
}

.btn-view-details:hover {
    background: #CB3737;
    color: white;
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

/* Pagination / arrows size */
.pagination .page-link {
    padding: .25rem .5rem;
    font-size: 0.9rem;
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
                <form action="{{ route('vehicles.index') }}" method="GET">
                    <div class="row g-2 align-items-end">
                        <!-- Pick-up Location -->
                        <div class="col-md-3">
                            <label class="form-label-custom">Pick-up Location</label>
                            <select name="location" class="form-select form-control-custom">
                                <option selected disabled>Choose location</option>
                                <option>Hasta Office</option>
                                <option>Faculty</option>
                                <option>College</option>
                            </select>
                        </div>

                        <!-- Pick-up Date -->
                        <div class="col-md-2">
                            <label class="form-label-custom">Pick-up Date</label>
                            <input type="date" name="pickup_date" class="form-control form-control-custom">
                        </div>

                        <!-- Pick-up Time -->
                        <div class="col-md-2">
                            <label class="form-label-custom">Time</label>
                            <select name="pickup_time" class="form-select form-control-custom">
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
                            <input type="date" name="return_date" class="form-control form-control-custom">
                        </div>

                        <!-- Return Time -->
                        <div class="col-md-2">
                            <label class="form-label-custom">Time</label>
                            <select name="return_time" class="form-select form-control-custom">
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
                                <input class="form-check-input" type="checkbox" name="return_location" id="returnLocation">
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

<!-- Car Listings -->
    <div class="section-header" style="margin-top: 3rem;">
    <h2>Our Car Models</h2>
    </div>


    <!-- Car Listings -->
    <div class="d-flex justify-content-center flex-wrap gap-5 mb-5">
    <!-- Car 1 -->
    <div class="car-card" style="width: 350px;">
        <img src="{{ asset('car_images/axia.jpg') }}" alt="Car">
        <h3 class="car-name">Perodua Axia 1st Gen</h3>
        <p class="car-type">Hatchback</p>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="car-price">RM35.00 <small>per hour</small></div>
        </div>
        <button class="btn btn-view-details">View Details</button>
    </div>

    <!-- Car 2 -->
    <div class="car-card" style="width: 350px;">
        <img src="{{ asset('car_images/myvi.jpg') }}" alt="Car">
        <h3 class="car-name">Perodua Myvi 2nd Gen</h3>
        <p class="car-type">Hatchback</p>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="car-price">RM40.00 <small>per hour</small></div>
        </div>
        <button class="btn btn-view-details">View Details</button>
    </div>

    <!-- Car 3 -->
    <div class="car-card" style="width: 350px;">
        <img src="{{ asset('car_images/bezza1stgen.jpg') }}" alt="Car">
        <h3 class="car-name">Perodua Bezza 1st Gen</h3>
        <p class="car-type">Sedan</p>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="car-price">RM40.00 <small>per hour</small></div>
        </div>
        <button class="btn btn-view-details">View Details</button>
    </div>
</div>
</div>  

@endsection