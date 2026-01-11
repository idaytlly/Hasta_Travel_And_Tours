@extends('layouts.app')

@section('title', 'Booking Details')

@section('noFooter', true)

@section('content')

    @include('components.booking-timeline', ['bookingStatus' => $booking->booking_status, 'booking' => $booking])
    
    <div class="booking-details-container">
        <!-- Your existing booking details -->
    </div>

<style>
    body { 
        padding-top: 70px;
        background: #f9fafb;
    }
    
    .booking-details-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .back-arrow {
        display: inline-flex;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffd6d6;
        color: #d93025;
        font-weight: 900;
        font-size: 18px;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 10px rgba(217,48,37,0.25);
        transition: all 0.2s ease;
        text-decoration: none;
        margin-bottom: 20px;
    }
    
    .back-arrow:hover {
        transform: translateX(-2px) scale(1.1);
        background: #ffcaca;
        box-shadow: 0 6px 14px rgba(217,48,37,0.35);
    }
    
    .page-header {
        margin-bottom: 24px;
    }
    
    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #111;
        margin-bottom: 8px;
    }
    
    .page-header p {
        color: #6b7280;
        font-size: 14px;
    }
    
    .booking-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .status-header {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .booking-id {
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
    }
    
    .booking-id span {
        color: #d93025;
        font-weight: 700;
    }
    
    .status-badge {
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 2px solid #f59e0b;
    }
    
    .status-confirmed {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #10b981;
    }
    
    .status-completed {
        background: #e5e7eb;
        color: #374151;
        border: 2px solid #6b7280;
    }
    
    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
        border: 2px solid #ef4444;
    }
    
    .card-content {
        padding: 24px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #111;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .car-details-section {
        background: #f7f7f7;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 20px;
        align-items: center;
    }
    
    .car-details-section img {
        width: 200px;
        height: auto;
        border-radius: 8px;
    }
    
    .car-info h3 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .car-meta {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    
    .info-card {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        position: relative;
    }
    
    .info-card.pickup {
        border-left: 4px solid #10b981;
    }
    
    .info-card.return {
        border-left: 4px solid #ef4444;
    }
    
    .info-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 12px;
    }
    
    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    
    .info-detail {
        font-size: 14px;
        color: #6b7280;
    }
    
    .customer-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .customer-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    
    .customer-field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
        text-transform: uppercase;
    }
    
    .customer-field p {
        font-size: 16px;
        font-weight: 600;
        color: #111;
    }
    
    .price-summary {
        background: #fef3c7;
        border: 2px solid #fbbf24;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        color: #374151;
        font-size: 15px;
    }
    
    .price-row.total {
        border-top: 2px solid #fbbf24;
        margin-top: 8px;
        padding-top: 16px;
        font-size: 20px;
        font-weight: 700;
    }
    
    .price-row.total .amount {
        color: #d93025;
        font-size: 24px;
    }
    
    .price-row .label {
        font-weight: 600;
    }
    
    .price-row.discount {
        color: #059669;
    }
    
    .payment-section {
        border-top: 2px solid #e5e7eb;
        padding-top: 20px;
    }
    
    .payment-status {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 16px;
    }
    
    .payment-paid {
        background: #d1fae5;
        color: #065f46;
    }
    
    .payment-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
    
    .btn {
        flex: 1;
        padding: 14px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary {
        background: #d93025;
        color: white;
    }
    
    .btn-primary:hover {
        background: #b71c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(217,48,37,0.3);
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
    }
    
    .signature-preview {
        margin-top: 16px;
        padding: 16px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }
    
    .signature-preview img {
        max-width: 300px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
    }
    
    @media (max-width: 768px) {
        .info-grid,
        .customer-row {
            grid-template-columns: 1fr;
        }
        
        .car-details-section {
            flex-direction: column;
            text-align: center;
        }
        
        .car-details-section img {
            width: 100%;
            max-width: 300px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="booking-details-container">
    <a href="{{ route('bookings.index') }}" class="back-arrow" title="Back to Bookings">←</a>
    
    @if(session('success'))
        <div style="margin-bottom: 20px; padding: 16px; background: #d1fae5; border: 2px solid #10b981; color: #065f46; border-radius: 8px; font-weight: 600;">
            ✓ {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="margin-bottom: 20px; padding: 16px; background: #fee2e2; border: 2px solid #ef4444; color: #991b1b; border-radius: 8px; font-weight: 600;">
            ✗ {{ session('error') }}
        </div>
    @endif
    
    @if(session('info'))
        <div style="margin-bottom: 20px; padding: 16px; background: #dbeafe; border: 2px solid #3b82f6; color: #1e40af; border-radius: 8px; font-weight: 600;">
            ℹ️ {{ session('info') }}
        </div>
    @endif
    
    <div class="page-header">
        <h1>Booking Details</h1>
        <p>View complete information about your booking</p>
    </div>
    
    <div class="booking-card">
        <div class="status-header">
            <div class="booking-id">
                Booking ID: <span>{{ $booking->booking_id }}</span>
            </div>
            <span class="status-badge status-{{ $booking->booking_status }}">
                {{ ucfirst($booking->booking_status) }}
            </span>
        </div>
        
        <div class="card-content">
            <!-- Vehicle Information -->
            <h2 class="section-title">
                🚗 Vehicle Information
            </h2>
            <div class="car-details-section">
                <img src="{{ $booking->vehicle->image_url ?? asset('car_images/axia.jpg') }}" alt="{{ $booking->vehicle->name }}">
                <div class="car-info">
                    <h3>{{ $booking->vehicle->name }}</h3>
                    <p class="car-meta">License Plate: <strong style="color: #d93025;">{{ $booking->plate_no }}</strong></p>
                    <p class="car-meta">Year: {{ $booking->vehicle->year }} | Color: {{ $booking->vehicle->color }}</p>
                    <p class="car-meta">Passengers: {{ $booking->vehicle->passengers }}</p>
                    <p class="car-meta" style="margin-top: 8px;">Rate: <strong>RM {{ number_format($booking->vehicle->price_perHour, 2) }}/hour</strong></p>
                </div>
            </div>
            
            <!-- Customer Information -->
            <h2 class="section-title">
                👤 Customer Information
            </h2>
            <div class="customer-info">
                <div class="customer-row">
                    <div class="customer-field">
                        <label>Full Name</label>
                        <p>{{ $booking->customer->name }}</p>
                    </div>
                    <div class="customer-field">
                        <label>Phone Number</label>
                        <p>{{ $booking->customer->phone_no }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Rental Details -->
            <h2 class="section-title">
                📅 Rental Details
            </h2>
            <div class="info-grid">
                <div class="info-card pickup">
                    <div class="info-label">
                        📍 Pickup
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($booking->pickup_date)->format('l, F j, Y') }}
                    </div>
                    <div class="info-detail">
                        {{ \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') }}
                    </div>
                    <div class="info-detail" style="margin-top: 8px; font-weight: 600; color: #111;">
                        {{ $booking->pickup_details }}
                    </div>
                </div>
                
                <div class="info-card return">
                    <div class="info-label">
                        📍 Return
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($booking->return_date)->format('l, F j, Y') }}
                    </div>
                    <div class="info-detail">
                        {{ \Carbon\Carbon::parse($booking->return_time)->format('g:i A') }}
                    </div>
                    <div class="info-detail" style="margin-top: 8px; font-weight: 600; color: #111;">
                        {{ $booking->dropoff_details }}
                    </div>
                </div>
            </div>
            
            @php
                $pickup = \Carbon\Carbon::parse($booking->pickup_date . ' ' . $booking->pickup_time);
                $return = \Carbon\Carbon::parse($booking->return_date . ' ' . $booking->return_time);
                $hours = ceil($pickup->diffInHours($return));
            @endphp
            
            <!-- Price Summary -->
            <h2 class="section-title">
                💰 Price Summary
            </h2>
            <div class="price-summary">
                <div class="price-row">
                    <span class="label">Rental Duration:</span>
                    <span>{{ $hours }} hour{{ $hours != 1 ? 's' : '' }}</span>
                </div>
                <div class="price-row">
                    <span class="label">Price per Hour:</span>
                    <span>RM {{ number_format($booking->vehicle->price_perHour, 2) }}</span>
                </div>
                <div class="price-row">
                    <span class="label">Subtotal:</span>
                    <span>RM {{ number_format($hours * $booking->vehicle->price_perHour, 2) }}</span>
                </div>
                @if($booking->voucher)
                    <div class="price-row discount">
                        <span class="label">Discount ({{ $booking->voucher->voucherAmount }}%):</span>
                        <span>- RM {{ number_format(($hours * $booking->vehicle->price_perHour * $booking->voucher->voucherAmount) / 100, 2) }}</span>
                    </div>
                @endif
                @if($booking->delivery_required)
                    <div class="price-row">
                        <span class="label">Delivery Service:</span>
                        <span>Included</span>
                    </div>
                @endif
                <div class="price-row total">
                    <span class="label">Total Amount:</span>
                    <span class="amount">RM {{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
            
            <!-- Payment Information -->
            @if($booking->payments->isNotEmpty())
                <div class="payment-section">
                    <h2 class="section-title">
                        💳 Payment Information
                    </h2>
                    @php $payment = $booking->payments->first(); @endphp
                    <div style="margin-bottom: 16px;">
                        <span class="payment-status payment-{{ $payment->payment_status }}">
                            Payment Status: {{ ucfirst($payment->payment_status) }}
                        </span>
                    </div>
                    <div class="customer-row" style="margin-bottom: 12px;">
                        <div class="customer-field">
                            <label>Payment Method</label>
                            <p>{{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
                        </div>
                        <div class="customer-field">
                            <label>Payment Date</label>
                            <p>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('F j, Y g:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                    @if($payment->payment_proof)
                        <div class="customer-field">
                            <label>Payment Proof</label>
                            <p>
                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" style="color: #d93025; font-weight: 600; text-decoration: underline;">
                                    View Payment Proof →
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Digital Signature -->
            @if($booking->signature)
                <div style="border-top: 2px solid #e5e7eb; padding-top: 20px; margin-top: 24px;">
                    <h2 class="section-title">
                        ✍️ Digital Signature
                    </h2>
                    <div class="signature-preview">
                        <img src="{{ asset('storage/' . $booking->signature) }}" alt="Digital Signature">
                    </div>
                </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                @if($booking->booking_status === 'pending' && $booking->payments->isEmpty())
                    <a href="{{ route('bookings.payment', $booking->booking_id) }}" class="btn btn-primary">
                        Complete Payment
                    </a>
                @endif
                
                @if(in_array($booking->booking_status, ['pending', 'confirmed']))
                    <form action="{{ route('bookings.cancel', $booking->booking_id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger" style="width: 100%;">
                            Cancel Booking
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                    Back to Bookings
                </a>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 16px; color: #6b7280; font-size: 13px;">
        <p>Booking created on {{ \Carbon\Carbon::parse($booking->created_at)->format('F j, Y g:i A') }}</p>
    </div>
</div>
@endsection