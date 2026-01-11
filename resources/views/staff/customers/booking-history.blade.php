{{-- resources/views/staff/customers/booking-history.blade.php --}}
@extends('staff.layouts.staff')

@section('title', 'Booking History - ' . $customer->name)
@section('page-title', 'Booking History')
@section('page-subtitle', $customer->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.customers.index') }}">Customers</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.customers.show', $customer->id) }}">{{ $customer->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Booking History</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Bookings for {{ $customer->name }}</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            <a href="{{ route('staff.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        <h4 class="text-primary">{{ $bookings->total() }}</h4>
                        <p class="text-muted mb-0">Total Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        @php
                            $completed = $bookings->where('booking_status', 'completed')->count();
                        @endphp
                        <h4 class="text-success">{{ $completed }}</h4>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        @php
                            $cancelled = $bookings->where('booking_status', 'cancelled')->count();
                        @endphp
                        <h4 class="text-danger">{{ $cancelled }}</h4>
                        <p class="text-muted mb-0">Cancelled</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        @php
                            $totalSpent = $bookings->where('booking_status', '!=', 'cancelled')->sum('total_price');
                        @endphp
                        <h4 class="text-warning">RM {{ number_format($totalSpent, 2) }}</h4>
                        <p class="text-muted mb-0">Total Spent</p>
                    </div>
                </div>
            </div>
        </div>

        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Vehicle</th>
                            <th>Pickup Date</th>
                            <th>Return Date</th>
                            <th>Duration</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            @php
                                $pickup = \Carbon\Carbon::parse($booking->pickup_date . ' ' . $booking->pickup_time);
                                $return = \Carbon\Carbon::parse($booking->return_date . ' ' . $booking->return_time);
                                $duration = $pickup->diffInHours($return);
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('staff.bookings.show', $booking->booking_id) }}" class="text-decoration-none fw-bold">
                                        {{ $booking->booking_id }}
                                    </a>
                                </td>
                                <td>
                                    <div>{{ $booking->vehicle->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $booking->plate_no }}</small>
                                </td>
                                <td>
                                    {{ $booking->pickup_date->format('M d, Y') }}
                                    <div class="text-muted">{{ $booking->pickup_time }}</div>
                                </td>
                                <td>
                                    {{ $booking->return_date->format('M d, Y') }}
                                    <div class="text-muted">{{ $booking->return_time }}</div>
                                </td>
                                <td>{{ $duration }} hours</td>
                                <td>
                                    <strong>RM {{ number_format($booking->total_price, 2) }}</strong>
                                    @if($booking->voucher_id)
                                        <div class="text-success small">
                                            <i class="fas fa-ticket-alt me-1"></i>Voucher applied
                                        </div>
                                    @endif
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
                                <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('staff.bookings.show', $booking->booking_id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($booking->booking_status == 'active')
                                            <a href="tel:{{ $customer->phone_no }}" 
                                               class="btn btn-sm btn-outline-success" title="Call Customer">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} entries
                </div>
                <div>
                    {{ $bookings->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5>No Booking History</h5>
                <p class="text-muted">This customer hasn't made any bookings yet.</p>
                <a href="{{ route('staff.customers.show', $customer->id) }}" class="btn btn-outline-primary mt-2">
                    <i class="fas fa-arrow-left me-2"></i> Back to Customer
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Bookings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('staff.customers.booking-history', $customer->id) }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Price</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('staff.customers.booking-history', $customer->id) }}" class="btn btn-outline-secondary">Clear Filters</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection