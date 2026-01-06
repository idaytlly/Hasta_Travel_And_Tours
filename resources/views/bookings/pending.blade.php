@extends('layouts.app')
@section('title', 'Booking Pending - HASTA')

@push('styles')
<style>
    .pending-container {
        max-width: 800px;
        margin: 60px auto;
        padding: 0 20px;
    }
    .pending-card {
        background: white;
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .pending-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #ffa726, #ff9800);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: pulse 2s infinite;
    }
    .pending-icon i {
        font-size: 60px;
        color: white;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .pending-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #333;
    }
    .pending-subtitle {
        font-size: 18px;
        color: #666;
        margin-bottom: 30px;
    }
    .booking-ref-box {
        background: #fff3e0;
        border: 2px dashed #ff9800;
        border-radius: 15px;
        padding: 25px;
        margin: 30px 0;
    }
    .booking-ref-label {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }
    .booking-ref-code {
        font-size: 32px;
        font-weight: 700;
        color: #ff6b3d;
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
    }
    .info-box {
        background: #f5f5f5;
        border-radius: 15px;
        padding: 25px;
        text-align: left;
        margin: 30px 0;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #666;
        font-weight: 500;
    }
    .info-value {
        color: #333;
        font-weight: 600;
    }
    .status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }
    .status-pending {
        background: #fff3e0;
        color: #ff9800;
    }
    .btn-action {
        padding: 15px 40px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        border: none;
        margin: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-view-bookings {
        background: #ff6b3d;
        color: white;
    }
    .btn-view-bookings:hover {
        background: #ff5722;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,107,61,0.3);
    }
    .btn-browse {
        background: white;
        color: #ff6b3d;
        border: 2px solid #ff6b3d;
    }
    .btn-browse:hover {
        background: #ff6b3d;
        color: white;
    }
    .timeline {
        margin: 40px 0;
        padding: 30px;
        background: #f9f9f9;
        border-radius: 15px;
    }
    .timeline-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: left;
    }
    .timeline-item {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        text-align: left;
    }
    .timeline-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .timeline-dot.active {
        background: #ff9800;
        color: white;
    }
    .timeline-dot.inactive {
        background: #e0e0e0;
        color: #999;
    }
    .timeline-content h5 {
        font-weight: 700;
        margin-bottom: 5px;
    }
    .timeline-content p {
        color: #666;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="pending-container">
    <div class="pending-card">
        <!-- Pending Icon -->
        <div class="pending-icon">
            <i class="fas fa-clock"></i>
        </div>

        <!-- Title -->
        <h1 class="pending-title">Booking Submitted Successfully!</h1>
        <p class="pending-subtitle">
            Your booking is pending admin approval. We'll notify you once it's confirmed.
        </p>

        <!-- Booking Reference -->
        <div class="booking-ref-box">
            <div class="booking-ref-label">Your Booking Reference</div>
            <div class="booking-ref-code">{{ $booking->booking_reference }}</div>
        </div>

        <!-- Status Badge -->
        <div class="mb-4">
            <span class="status-badge status-pending">
                <i class="fas fa-hourglass-half"></i> Pending Approval
            </span>
        </div>

        <!-- Booking Info -->
        <div class="info-box">
            <div class="info-item">
                <span class="info-label">Car</span>
                <span class="info-value">{{ $booking->car->full_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Pickup Date</span>
                <span class="info-value">{{ $booking->pickup_date->format('d M Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Return Date</span>
                <span class="info-value">{{ $booking->return_date->format('d M Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Duration</span>
                <span class="info-value">{{ $booking->duration }} days</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Amount</span>
                <span class="info-value" style="color: #ff6b3d; font-size: 20px;">
                    RM{{ number_format($booking->total_price, 2) }}
                </span>
            </div>
        </div>

        <!-- Timeline -->
        <div class="timeline">
            <div class="timeline-title">Booking Process</div>
            
            <div class="timeline-item">
                <div class="timeline-dot active">
                    <i class="fas fa-check"></i>
                </div>
                <div class="timeline-content">
                    <h5>Booking Submitted</h5>
                    <p>Your booking has been received</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot active">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="timeline-content">
                    <h5>Pending Approval</h5>
                    <p>Admin is reviewing your booking</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot inactive">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="timeline-content">
                    <h5>Approved</h5>
                    <p>Booking confirmed, ready for pickup</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4">
            <a href="{{ route('bookings.my-bookings') }}" class="btn btn-action btn-view-bookings">
                <i class="fas fa-list"></i> View My Bookings
            </a>
            <a href="{{ route('cars.index') }}" class="btn btn-action btn-browse">
                <i class="fas fa-car"></i> Browse More Cars
            </a>
        </div>

        <!-- Help Text -->
        <div class="mt-4 text-muted" style="font-size: 14px;">
            <i class="fas fa-info-circle"></i> 
            Need help? Contact us at <strong>hastarental@gmail.com</strong> or <strong>011-1098 0700</strong>
        </div>
    </div>
</div>
@endsection