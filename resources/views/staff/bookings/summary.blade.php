@extends('layouts.staff')

@section('content')
<style>
    .detail-row {
        padding: 14px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .detail-label {
        font-weight: 600;
        color: #333;
    }
    
    .detail-value {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 6px;
        color: #555;
    }
</style>

<div class="container py-4" style="max-width: 800px;">
    <!-- Back Button -->
    <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow">
        <!-- Vehicle Display -->
        <div class="card-body text-center py-4" style="background: #f8f9fa; border-radius: 8px 8px 0 0;">
            <h3 class="fw-bold mb-3">{{ $booking->car->brand }} {{ $booking->car->model }} {{ $booking->car->year }}</h3>
            <img src="{{ $booking->car->image }}" alt="Vehicle" style="max-height: 250px;" class="img-fluid">
        </div>

        <!-- Booking Details -->
        <div class="card-body px-4 py-4">
            <h4 class="fw-bold mb-4">Booking Details</h4>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Booking Reference</div>
                    <div class="col-7">
                        <div class="detail-value fw-bold text-primary">{{ $booking->booking_reference }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Full Name</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->user->name }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Email Address</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->user->email }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Phone Number</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->user->phone ?? 'Not provided' }}</div>
                    </div>
                </div>
            </div>

            @if($booking->destination)
            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Destination</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->destination }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Pickup Location</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->pickup_location }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Return Location</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->dropoff_location }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Pickup Date & Time</div>
                    <div class="col-7">
                        <div class="detail-value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Return Date & Time</div>
                    <div class="col-7">
                        <div class="detail-value">{{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->return_time)->format('h:i A') }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Rental Duration (Days)</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->duration ?? \Carbon\Carbon::parse($booking->pickup_date)->diffInDays($booking->return_date) }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Car Type</div>
                    <div class="col-7">
                        <div class="detail-value">{{ strtoupper($booking->car->category) }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Car Model</div>
                    <div class="col-7">
                        <div class="detail-value">{{ strtoupper($booking->car->brand) }} {{ strtoupper($booking->car->model) }} {{ $booking->car->year }}</div>
                    </div>
                </div>
            </div>

            @if($booking->remarks)
            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Remarks</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->remarks }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Deposit</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->deposit_amount ?? 100 }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Price (RM)</div>
                    <div class="col-7">
                        <div class="detail-value">{{ number_format($booking->base_price ?? $booking->total_price, 2) }}</div>
                    </div>
                </div>
            </div>

            @if($booking->voucher)
            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Voucher</div>
                    <div class="col-7">
                        <div class="detail-value">{{ $booking->voucher }}</div>
                    </div>
                </div>
            </div>
            @else
            <div class="detail-row">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Voucher</div>
                    <div class="col-7">
                        <div class="detail-value">Not Applied</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="detail-row border-0">
                <div class="row align-items-center">
                    <div class="col-5 detail-label">Total Payment (RM)</div>
                    <div class="col-7">
                        <div class="detail-value fw-bold text-danger fs-5">{{ number_format($booking->total_price, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card-footer bg-white border-0 p-4">
            <div class="row g-3">
                <div class="col-6">
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-danger btn-lg w-100">
                        Reject
                    </a>
                </div>
                <div class="col-6">
                    <form action="{{ route('staff.bookings.approve', $booking->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-lg w-100" 
                                onclick="return confirm('Are you sure you want to approve this booking?')">
                            Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection