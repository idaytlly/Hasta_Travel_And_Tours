@extends('layouts.app')
@section('title', $car->brand . ' ' . $car->model . ' - HASTA')

@push('styles')
<style>
    /* ... (All your existing CSS remains the same) ... */
    .car-detail-container {
        background: white;
        border-radius: 20px;
        margin: 40px auto;
        max-width: 1200px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .car-detail-header {
        background: #f8f8f8;
        padding: 40px;
    }
    .car-image-large {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        display: block;
        object-fit: contain;
    }
    .car-thumbnails {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
    }
    .car-thumbnail {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .car-thumbnail:hover {
        border-color: #d84444;
    }
    .car-detail-title {
        font-size: 32px;
        font-weight: 700;
        margin: 20px 0 10px;
    }
    .car-detail-price {
        font-size: 36px;
        color: #ff4444;
        font-weight: 700;
    }
    .car-detail-price-unit {
        font-size: 16px;
        color: #888;
    }
    .specifications-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin: 30px 0;
    }
    .spec-box {
        background: #f8f8f8;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }
    .spec-icon {
        font-size: 30px;
        margin-bottom: 10px;
    }
    .spec-label {
        font-size: 12px;
        color: #888;
        margin-bottom: 5px;
    }
    .spec-value {
        font-size: 18px;
        font-weight: 600;
    }
    .equipment-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin: 20px 0;
    }
    .equipment-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .equipment-item.available {
        color: #28a745;
    }
    .btn-book-now {
        background: #ff6b3d;
        color: white;
        border: none;
        padding: 15px 60px;
        border-radius: 10px;
        font-size: 18px;
        font-weight: 700;
        display: block;
        margin: 30px auto;
        text-align: center;
        text-decoration: none;
    }
    .btn-book-now:hover {
        background: #ff5722;
        color: white;
    }
    .other-cars-title {
        font-size: 28px;
        font-weight: 700;
        margin: 60px 0 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .view-all-link {
        font-size: 16px;
        color: #d84444;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="car-detail-container">
       <div class="car-detail-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="{{ $car->image }}" class="car-image-large" alt="{{ $car->brand }} {{ $car->model }}">
                
                <div class="car-thumbnails">
                    <div class="car-thumbnail-wrapper">
                        <img src="{{ $car->image }}" class="car-thumbnail" alt="View 1">
                        <span class="text-xs text-gray-400 block text-center">View 1</span>
                    </div>
                    <div class="car-thumbnail-wrapper">
                        <img src="{{ $car->image }}" class="car-thumbnail" alt="View 2">
                        <span class="text-xs text-gray-400 block text-center">View 2</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center text-md-start">
                <div class="car-detail-title">⚡ {{ $car->brand }} {{ $car->model }} {{ $car->year }}</div>
                <div class="car-detail-price">
                    RM{{ number_format($car->daily_rate, 0) }}
                    <span class="car-detail-price-unit">/ day</span>
                </div>
            </div>
        </div>
    </div>

        <div style="padding: 40px;">
            <h4 style="font-weight: 700; margin-bottom: 20px;">Specifications</h4>
            <div class="specifications-grid">
                <div class="spec-box">
                    <div class="spec-icon">⚙️</div>
                    <div class="spec-label">Gear Box</div>
                    <div class="spec-value">{{ $car->transmission }}</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">⛽</div>
                    <div class="spec-label">Fuel</div>
                    <div class="spec-value">{{ $car->fuel_type }}</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">🚪</div>
                    <div class="spec-label">Doors</div>
                    <div class="spec-value">4</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">❄️</div>
                    <div class="spec-label">Air Conditioner</div>
                    <div class="spec-value">{{ $car->air_conditioner ? 'Yes' : 'No' }}</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">👥</div>
                    <div class="spec-label">Seats</div>
                    <div class="spec-value">{{ $car->passengers }} Person</div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon">📏</div>
                    <div class="spec-label">License Plate</div>
                    <div class="spec-value">{{ $car->license_plate ?? 'N/A' }}</div>
                </div>
            </div>

            <h4 style="font-weight: 700; margin: 40px 0 20px;">Car Equipment</h4>
            <div class="equipment-grid">
                <div class="equipment-item available">
                    <i class="fas fa-check-circle"></i> ABS
                </div>
                <div class="equipment-item available">
                    <i class="fas fa-check-circle"></i> Air Bags
                </div>
                <div class="equipment-item available">
                    <i class="fas fa-check-circle"></i> Cruise Control
                </div>
                @if($car->air_conditioner)
                <div class="equipment-item available">
                    <i class="fas fa-check-circle"></i> Air Conditioner
                </div>
                @endif
            </div>

            <a href="{{ route('bookings.create', $car->id) }}" class="btn-book-now">
                Book Now
            </a>
        </div>
    </div>


</div>
@endsection