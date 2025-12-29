@extends('layouts.app')
@section('title', 'My Bookings - HASTA')

@push('styles')
<style>
    .my-bookings-container { padding: 40px 0; }
    .page-header { margin-bottom: 40px; }
    .page-title { font-size: 36px; font-weight: 700; margin-bottom: 10px; }
    
    .booking-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .booking-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f5f5f5;
    }
    .booking-ref { font-size: 20px; font-weight: 700; color: #333; }
    .booking-status { padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 14px; }
    
    .status-pending { background: #fff3e0; color: #ff9800; }
    .status-confirmed { background: #e8f5e9; color: #4caf50; }
    .status-approved { background: #e3f2fd; color: #2196f3; }
    .status-in_progress { background: #e1f5fe; color: #03a9f4; }
    .status-completed { background: #f3e5f5; color: #9c27b0; }
    .status-cancelled { background: #ffebee; color: #f44336; }

    .booking-body { display: grid; grid-template-columns: 200px 1fr; gap: 25px; align-items: center; }
    .car-image { width: 100%; height: 150px; object-fit: cover; border-radius: 10px; }
    .booking-details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
    .detail-label { font-size: 12px; color: #888; margin-bottom: 5px; }
    .detail-value { font-size: 16px; font-weight: 600; color: #333; }
    
    .booking-actions { 
        display: flex; gap: 10px; margin-top: 20px; padding-top: 15px; border-top: 2px solid #f5f5f5; 
    }
    .btn-view { padding: 10px 25px; background: #ff6b3d; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; }
    .btn-cancel { padding: 10px 25px; background: white; color: #f44336; border: 2px solid #f44336; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-cancel:hover { background: #f44336; color: white; }
    
    .history-card { opacity: 0.7; background: #f9f9f9; border: 1px dashed #ddd; box-shadow: none; transform: none !important; }
</style>
@endpush

@section('content')
<div class="container my-bookings-container">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <h1 class="page-title">My Bookings</h1>
        <p style="color: #666;">Track and manage your active rental bookings</p>
    </div>

    {{-- 1. ACTIVE BOOKINGS SECTION --}}
    @forelse($activeBookings as $booking)
    <div class="booking-card">
        <div class="booking-header">
            <div class="booking-ref">
                <i class="fas fa-ticket-alt"></i> {{ $booking->booking_reference }}
            </div>
            <span class="booking-status status-{{ $booking->status }}">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            </span>
        </div>

        <div class="booking-body">
            <img src="{{ $booking->car->image }}" alt="{{ $booking->car->name }}" class="car-image">
            <div class="booking-details">
                <div class="detail-item">
                    <span class="detail-label">Car</span>
                    <span class="detail-value">{{ $booking->car->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pickup Date</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value">{{ $booking->duration }} days</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Amount</span>
                    <span class="detail-value" style="color: #ff6b3d;">RM{{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="booking-actions">
            <a href="{{ route('bookings.show', $booking->booking_reference) }}" class="btn-view">View Details</a>
            
            @if($booking->status === 'pending')
            <form action="{{ route('bookings.cancel', $booking->booking_reference) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-cancel" onclick="return confirm('Are you sure?')">Cancel Booking</button>
            </form>
            @endif
        </div>
    </div>
    @empty
        <div class="text-center py-10">
            <p class="text-gray-500">No active bookings found. <a href="{{ route('cars.index') }}">Book a car now!</a></p>
        </div>
    @endforelse

    {{-- 2. CANCELLED/PAST HISTORY SECTION --}}
    @if($historyBookings->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-400 mb-6">Cancelled & Past History</h2>
            @foreach($historyBookings as $history)
            <div class="booking-card history-card">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-500 font-mono">#{{ $history->booking_reference }}</span>
                        <h4 class="font-bold text-gray-600">{{ $history->car->name }}</h4>
                    </div>
                    <div class="text-right">
                        <span class="booking-status status-cancelled">CANCELLED</span>
                        <p class="text-xs text-gray-400 mt-2">Processed on {{ $history->deleted_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection