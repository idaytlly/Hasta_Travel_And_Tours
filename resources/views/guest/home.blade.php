@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
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

@endsection