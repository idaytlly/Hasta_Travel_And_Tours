@extends('layouts.guest')

@section('content')
<style>
    .booking-page { background-color: #f5f5f5; padding: 40px 0; min-height: 100vh; font-family: 'Poppins', sans-serif; }
    .back-circle { color: #000; font-size: 2.5rem; transition: transform 0.2s; text-decoration: none; }
    .back-circle:hover { transform: scale(1.1); color: #333; }
    
    .car-card { background: white; border-radius: 40px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); margin-bottom: 30px; text-align: center; }
    .car-title { font-size: 2.2rem; font-weight: 800; color: #000; margin-bottom: 15px; }
    .car-hero-img { max-width: 550px; width: 100%; height: auto; }
    
    .details-card { background: white; border-radius: 40px; padding: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .details-title { font-size: 2rem; font-weight: 700; text-align: center; margin-bottom: 45px; }
    
    .info-row { display: flex; align-items: center; margin-bottom: 15px; }
    .info-label { width: 320px; font-weight: 600; font-size: 1.1rem; color: #333; }
    .info-box { 
        flex-grow: 1; 
        background: #f0f0f0; 
        border: 1px solid #dcdcdc; 
        border-radius: 12px; 
        padding: 12px 25px; 
        color: #444;
        font-size: 1rem;
        min-height: 45px;
        display: flex;
        align-items: center;
    }
    .footer-button {
        display: inline-block;
        margin-top: 50px;
        padding: 14px 40px;
        background-color: #ff6b3d;
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 8px 20px rgba(255, 107, 61, 0.35);
    }

    .footer-button:hover {
        background-color: #e65a2b;
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(255, 107, 61, 0.45);
        color: #fff;
        text-decoration: none;
    }

    .footer-wrapper {
        text-align: center;
    }
</style>

<div class="booking-page">
    <div class="container" style="max-width: 950px;">
        <a href="{{ route('bookings.my-bookings') }}" class="back-circle">
            <i class="fas fa-arrow-alt-circle-left"></i>
        </a>

        <div class="car-card">
            <h1 class="car-title">{{ $booking->car->name }}</h1>
            <img src="{{ asset($booking->car->image) }}" class="car-hero-img" alt="{{ $booking->car->name }}">
        </div>

        <div class="details-card">
            <h2 class="details-title">Booking Details</h2>

            <div class="info-row">
                <span class="info-label">Full Name</span>
                <div class="info-box">{{ $booking->customer_name }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Identity Card Number</span>
                <div class="info-box">{{ $booking->user->ic ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Phone Number</span>
                <div class="info-box">{{ $booking->user->phone ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Email Address</span>
                <div class="info-box">{{ $booking->customer_email }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Destination</span>
                <div class="info-box">{{ $booking->destination ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Pickup Location</span>
                <div class="info-box">{{ $booking->pickup_location }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Return Location</span>
                <div class="info-box">{{ $booking->dropoff_location }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Pickup Date & Time</span>
                <div class="info-box">
                    {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}, {{ $booking->pickup_time }}
                </div>
            </div>

            <div class="info-row">
                <span class="info-label">Return Date & Time</span>
                <div class="info-box">
                    {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}, {{ $booking->return_time }}
                </div>
            </div>

            <div class="info-row">
                <span class="info-label">Rental Duration (Days)</span>
                <div class="info-box">{{ $booking->duration }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Car Type</span>
                <div class="info-box">{{ $booking->car->type ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Remarks</span>
                <div class="info-box">{{ $booking->remarks ?? 'No remarks' }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Deposit (RM)</span>
                <div class="info-box">{{ number_format($booking->deposit_amount, 2) }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Total Payment (RM)</span>
                <div class="info-box" style="font-weight: bold; color: #000; border: 1px solid #ff6b3d;">
                    {{ number_format($booking->total_price, 2) }}
                </div>
            </div>

            <div class="footer-wrapper">
                <a href="{{ route('bookings.my-bookings') }}" class="footer-button">
                    Back to My Bookings
                </a>
            </div>

        </div>
    </div>
</div>
@endsection