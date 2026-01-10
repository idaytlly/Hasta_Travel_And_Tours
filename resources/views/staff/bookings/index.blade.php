@extends('staff.layouts.staff')

@section('title', 'Bookings Management')
@section('page-title', 'Bookings Management')
@section('page-subtitle', 'Manage all vehicle bookings')

@section('page-header')
    <div class="row">
        <div class="col">
            <h1 class="h3 mb-0">All Bookings</h1>
            <p class="text-muted mb-0">View and manage vehicle bookings</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.bookings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> New Booking
            </a>
        </div>
    </div>
@endsection

@section('page-actions')
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-filter me-2"></i> Filter
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('staff.bookings.index') }}">All Bookings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('staff.bookings.index', ['status' => 'pending']) }}">Pending</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.bookings.index', ['status' => 'confirmed']) }}">Confirmed</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.bookings.index', ['status' => 'active']) }}">Active</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.bookings.index', ['status' => 'completed']) }}">Completed</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.bookings.index', ['status' => 'cancelled']) }}">Cancelled</a></li>
        </ul>
    </div>
@endsection

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-md-2 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $stats['total'] }}</h5>
                <p class="card-text text-muted">Total</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">{{ $stats['pending'] }}</h5>
                <p class="card-text text-muted">Pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $stats['confirmed'] }}</h5>
                <p class="card-text text-muted">Confirmed</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">{{ $stats['active'] }}</h5>
                <p class="card-text text-muted">Active</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">{{ $stats['completed'] }}</h5>
                <p class="card-text text-muted">Completed</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-danger">{{ $stats['cancelled'] }}</h5>
                <p class="card-text text-muted">Cancelled</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Booking List</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('staff.bookings.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Search bookings..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <strong>{{ $booking->booking_id }}</strong>
                            <br>
                            <small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <strong>{{ $booking->customer->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $booking->customer->phone_no }}</small>
                        </td>
                        <td>
                            {{ $booking->vehicle->name }}
                            <br>
                            <small class="text-muted">{{ $booking->vehicle->plate_no }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}
                            <br>
                            <small class="text-muted">{{ $booking->pickup_time }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}
                            <br>
                            <small class="text-muted">{{ $booking->return_time }}</small>
                        </td>
                        <td>
                            <strong>RM {{ number_format($booking->total_price, 2) }}</strong>
                        </td>
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
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('staff.bookings.show', $booking->booking_id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($booking->booking_status == 'pending')
                                <a href="{{ route('staff.bookings.approve', $booking->booking_id) }}" 
                                   class="btn btn-sm btn-outline-success"
                                   onclick="return confirm('Are you sure you want to approve this booking?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No bookings found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection