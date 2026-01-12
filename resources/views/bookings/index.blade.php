@extends('layouts.app')

@section('title', 'My Bookings')

@section('noFooter', true)

@section('content')
<style>
    body {
        padding-top: 70px;
        background: #f9fafb;
    }
    
    .bookings-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
    
    .page-header-content h1 {
        font-size: 32px;
        font-weight: 700;
        color: #111;
        margin-bottom: 8px;
    }
    
    .page-header-content p {
        font-size: 15px;
        color: #6b7280;
    }
    
    .btn-new-booking {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #d93025;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(217,48,37,0.25);
    }
    
    .btn-new-booking:hover {
        background: #b71c1c;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(217,48,37,0.35);
    }
    
    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    
    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
    }
    
    .empty-state h2 {
        font-size: 24px;
        font-weight: 700;
        color: #111;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 15px;
        color: #6b7280;
        margin-bottom: 24px;
    }
    
    .booking-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        padding: 24px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }
    
    .booking-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .booking-vehicle {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .vehicle-icon {
        font-size: 24px;
    }
    
    .vehicle-info h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    
    .vehicle-info p {
        font-size: 13px;
        color: #6b7280;
    }
    
    .booking-status-info {
        text-align: right;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 2px solid #fbbf24;
    }
    
    .status-confirmed {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #10b981;
    }
    
    .status-active {
        background: #dbeafe;
        color: #1e40af;
        border: 2px solid #3b82f6;
    }
    
    .status-completed {
        background: #e5e7eb;
        color: #374151;
        border: 2px solid #9ca3af;
    }
    
    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
        border: 2px solid #ef4444;
    }
    
    .booking-date-text {
        font-size: 12px;
        color: #9ca3af;
    }
    
    .booking-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 20px;
    }
    
    .detail-card {
        border-left: 4px solid;
        padding-left: 16px;
    }
    
    .detail-card.pickup {
        border-color: #10b981;
    }
    
    .detail-card.return {
        border-color: #ef4444;
    }
    
    .detail-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .detail-date {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    
    .detail-time {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 4px;
    }
    
    .detail-location {
        font-size: 13px;
        color: #4b5563;
    }
    
    .booking-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }
    
    .booking-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 13px;
        color: #6b7280;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .booking-price {
        text-align: right;
    }
    
    .price-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
    }
    
    .price-amount {
        font-size: 24px;
        font-weight: 700;
        color: #d93025;
    }
    
    .booking-actions {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 8px;
    }
    
    .btn-view {
        flex: 1;
        background: #d93025;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-view:hover {
        background: #b71c1c;
    }
    
    .btn-cancel {
        background: #6b7280;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-cancel:hover {
        background: #4b5563;
    }
    
    .btn-pay {
        flex: 1;
        background: #10b981;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .btn-pay:hover {
        background: #059669;
    }
    
    .pagination-wrapper {
        margin-top: 32px;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: start;
            gap: 16px;
        }
        
        .booking-details-grid {
            grid-template-columns: 1fr;
        }
        
        .booking-header {
            flex-direction: column;
            gap: 12px;
        }
        
        .booking-status-info {
            text-align: left;
        }
        
        .booking-footer {
            flex-direction: column;
            align-items: start;
            gap: 12px;
        }
        
        .booking-price {
            text-align: left;
        }
        
        .booking-actions {
            width: 100%;
        }
    }
</style>

<div class="bookings-container">
    <div class="page-header">
        <div class="page-header-content">
            <h1>My Bookings</h1>
            <p>View and manage all your car rental bookings</p>
        </div>
        <a href="{{ route('vehicles.index') }}" class="btn-new-booking">
            <span style="font-size: 18px;">+</span>
            New Booking
        </a>
    </div>
    
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
    
    @if($bookings->isEmpty())
        <div class="empty-state">
            <h2>No Bookings Yet</h2>
            <p>Start by creating your first car rental booking</p>
            <a href="{{ route('vehicles.index') }}" class="btn-new-booking">
                Browse Vehicles
            </a>
        </div>
    @else
        @foreach($bookings as $booking)
            <div class="booking-card">
                <div class="booking-header">
                    <div class="booking-vehicle">
                        <div class="vehicle-info">
                            <h3>{{ $booking->vehicle->name }}</h3>
                            <p>{{ $booking->plate_no }}</p>
                        </div>
                    </div>
                    <div class="booking-status-info">
                        <span class="status-badge status-{{ $booking->booking_status }}">
                            {{ ucfirst($booking->booking_status) }}
                        </span>
                        <div class="booking-date-text">
                            Booked on {{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                
                <div class="booking-details-grid">
                    <div class="detail-card pickup">
                        <div class="detail-label">
                            Pickup
                        </div>
                        <div class="detail-date">
                            {{ \Carbon\Carbon::parse($booking->pickup_date)->format('l, F j, Y') }}
                        </div>
                        <div class="detail-time">
                            {{ \Carbon\Carbon::parse($booking->pickup_time)->format('g:i A') }}
                        </div>
                        <div class="detail-location">
                            {{ $booking->pickup_details ?? $booking->pickup_location }}
                        </div>
                    </div>
                    
                    <div class="detail-card return">
                        <div class="detail-label">
                            Return
                        </div>
                        <div class="detail-date">
                            {{ \Carbon\Carbon::parse($booking->return_date)->format('l, F j, Y') }}
                        </div>
                        <div class="detail-time">
                            {{ \Carbon\Carbon::parse($booking->return_time)->format('g:i A') }}
                        </div>
                        <div class="detail-location">
                            {{ $booking->dropoff_details ?? $booking->dropoff_location }}
                        </div>
                    </div>
                </div>
                
                <div class="booking-footer">
                    <div class="booking-meta">
                        <div class="meta-item">
                            <span>🕐</span>
                            @php
                                $pickup = \Carbon\Carbon::parse($booking->pickup_date . ' ' . $booking->pickup_time);
                                $return = \Carbon\Carbon::parse($booking->return_date . ' ' . $booking->return_time);
                                $hours = ceil($pickup->diffInHours($return));
                            @endphp
                            <span>{{ $hours }} hour{{ $hours != 1 ? 's' : '' }}</span>
                        </div>
                        <div class="meta-item">
                            <span>👤</span>
                            <span>{{ $booking->customer->name }}</span>
                        </div>
                    </div>
                    <div class="booking-price">
                        <div class="price-label">Total Amount</div>
                        <div class="price-amount">RM {{ number_format($booking->total_price, 2) }}</div>
                    </div>
                </div>
                
                <div class="booking-actions">
                    @if($booking->booking_status === 'pending' && $booking->payments->isEmpty())
                        <a href="{{ route('bookings.payment', $booking->booking_id) }}" class="btn-pay">
                            Complete Payment
                        </a>
                    @endif
                    
                    <a href="{{ route('bookings.show', $booking->booking_id) }}" class="btn-view">
                        View Details
                    </a>
                    
                    @if(in_array($booking->booking_status, ['pending', 'confirmed']))
                        <form action="{{ route('bookings.cancel', $booking->booking_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            <button type="submit" class="btn-cancel">Cancel</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        
        @if($bookings->hasPages())
            <div class="pagination-wrapper">
                {{ $bookings->links() }}
            </div>
        @endif
    @endif
</div>
@endsection