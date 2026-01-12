{{-- resources/views/staff/vehicles/show.blade.php --}}
@extends('staff.layouts.app')

@section('title', 'Vehicle Details')
@section('page-title', 'Vehicle Details')
@section('page-subtitle', 'View detailed information about the vehicle')

@section('page-header')
    <div class="row">
        <div class="col">
            <h1 class="h3 mb-0">Vehicle Details</h1>
            <p class="text-muted mb-0">{{ $vehicle->name }} - {{ $vehicle->plate_no }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.vehicles.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
            </a>
            <a href="{{ route('staff.vehicles.edit', $vehicle->plate_no) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Edit
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Vehicle Images -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vehicle Images</h5>
            </div>
            <div class="card-body">
                @if($vehicle->images)
                    @php
                        $images = json_decode($vehicle->images, true);
                    @endphp
                    <div id="vehicleImages" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         class="d-block w-100 rounded" 
                                         alt="Vehicle Image {{ $index + 1 }}"
                                         style="max-height: 400px; object-fit: contain;">
                                </div>
                            @endforeach
                        </div>
                        @if(count($images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#vehicleImages" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#vehicleImages" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-car fa-5x text-muted mb-3"></i>
                        <p class="text-muted">No images available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Vehicle Details -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vehicle Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Plate Number:</th>
                        <td><strong>{{ $vehicle->plate_no }}</strong></td>
                    </tr>
                    <tr>
                        <th>Vehicle Name:</th>
                        <td>{{ $vehicle->name }}</td>
                    </tr>
                    <tr>
                        <th>Model:</th>
                        <td>{{ $vehicle->model }}</td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td>{{ $vehicle->vehicle_type }}</td>
                    </tr>
                    <tr>
                        <th>Transmission:</th>
                        <td>{{ $vehicle->transmission }}</td>
                    </tr>
                    <tr>
                        <th>Fuel Type:</th>
                        <td>{{ $vehicle->fuel_type }}</td>
                    </tr>
                    <tr>
                        <th>Seating Capacity:</th>
                        <td>{{ $vehicle->seating_capacity }} persons</td>
                    </tr>
                    <tr>
                        <th>Availability Status:</th>
                        <td>
                            @if($vehicle->availability_status == 'available')
                                <span class="badge bg-success">Available</span>
                            @elseif($vehicle->availability_status == 'booked')
                                <span class="badge bg-primary">Booked</span>
                            @else
                                <span class="badge bg-warning">Under Maintenance</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Hourly Rate:</th>
                        <td>RM {{ number_format($vehicle->price_perHour, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Daily Rate:</th>
                        <td>RM {{ number_format($vehicle->price_perDay, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Features -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Features</h5>
            </div>
            <div class="card-body">
                @if($vehicle->features)
                    @php
                        $features = json_decode($vehicle->features, true);
                    @endphp
                    <div class="row">
                        @foreach($features as $feature)
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check text-success me-2"></i> {{ $feature }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No features specified</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Description</h5>
            </div>
            <div class="card-body">
                @if($vehicle->description)
                    <p>{{ $vehicle->description }}</p>
                @else
                    <p class="text-muted">No description available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Notes -->
@if($vehicle->availability_status == 'maintenance' && $vehicle->maintenance_notes)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Maintenance Notes</h5>
            </div>
            <div class="card-body">
                <p>{{ $vehicle->maintenance_notes }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Bookings -->
@if($vehicle->bookings && $vehicle->bookings->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Bookings</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Pickup Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle->bookings->take(5) as $booking)
                            <tr>
                                <td>{{ $booking->booking_id }}</td>
                                <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                <td>{{ $booking->pickup_date->format('M d, Y') }}</td>
                                <td>{{ $booking->return_date->format('M d, Y') }}</td>
                                <td>
                                    @if($booking->booking_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($booking->booking_status == 'confirmed')
                                        <span class="badge bg-primary">Confirmed</span>
                                    @elseif($booking->booking_status == 'active')
                                        <span class="badge bg-info">Active</span>
                                    @elseif($booking->booking_status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection