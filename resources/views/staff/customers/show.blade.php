{{-- resources/views/staff/customers/show.blade.php --}}
@extends('staff.layouts.staff')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')
@section('page-subtitle', 'View customer information and history')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.customers.index') }}">Customers</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $customer->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="row">
    <!-- Customer Information -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-light rounded-circle text-primary" style="width: 80px; height: 80px; line-height: 80px; font-size: 32px;">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4>{{ $customer->name }}</h4>
                    <p class="text-muted">{{ $customer->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($customer->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                        
                        @if($customer->verification_status == 'verified')
                            <span class="badge bg-info">Verified</span>
                        @elseif($customer->verification_status == 'pending')
                            <span class="badge bg-warning">Pending Verification</span>
                        @else
                            <span class="badge bg-secondary">Not Verified</span>
                        @endif
                    </div>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Phone:</th>
                        <td>{{ $customer->phone_no }}</td>
                    </tr>
                    @if($customer->license_no)
                    <tr>
                        <th>License No:</th>
                        <td>{{ $customer->license_no }}</td>
                    </tr>
                    @endif
                    @if($customer->license_expiry)
                    <tr>
                        <th>License Expiry:</th>
                        <td>{{ \Carbon\Carbon::parse($customer->license_expiry)->format('M d, Y') }}</td>
                    </tr>
                    @endif
                    @if($customer->date_of_birth)
                    <tr>
                        <th>Date of Birth:</th>
                        <td>{{ \Carbon\Carbon::parse($customer->date_of_birth)->format('M d, Y') }}</td>
                    </tr>
                    @endif
                    @if($customer->gender)
                    <tr>
                        <th>Gender:</th>
                        <td>{{ ucfirst($customer->gender) }}</td>
                    </tr>
                    @endif
                    @if($customer->address)
                    <tr>
                        <th>Address:</th>
                        <td>{{ $customer->address }}</td>
                    </tr>
                    @endif
                    @if($customer->emergency_contact)
                    <tr>
                        <th>Emergency Contact:</th>
                        <td>{{ $customer->emergency_contact }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Member Since:</th>
                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $customer->updated_at->format('M d, Y') }}</td>
                    </tr>
                </table>
                
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $customer->email }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i> Send Email
                    </a>
                    <a href="tel:{{ $customer->phone_no }}" class="btn btn-outline-success">
                        <i class="fas fa-phone me-2"></i> Call Customer
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistics Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h3 class="text-primary">{{ $customerStats['total_bookings'] }}</h3>
                        <p class="text-muted mb-0">Total Bookings</p>
                    </div>
                    <div class="col-6 mb-3">
                        <h3 class="text-success">{{ $customerStats['completed_bookings'] }}</h3>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-danger">{{ $customerStats['cancelled_bookings'] }}</h3>
                        <p class="text-muted mb-0">Cancelled</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-warning">RM {{ number_format($customerStats['total_spent'], 2) }}</h3>
                        <p class="text-muted mb-0">Total Spent</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking History -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Booking History</h5>
                <a href="{{ route('staff.customers.booking-history', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Vehicle</th>
                                    <th>Pickup Date</th>
                                    <th>Return Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>
                                        <a href="{{ route('staff.bookings.show', $booking->booking_id) }}" class="text-decoration-none">
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
                                    <td>RM {{ number_format($booking->total_price, 2) }}</td>
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
                                        <a href="{{ route('staff.bookings.show', $booking->booking_id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>No Booking History</h5>
                        <p class="text-muted">This customer hasn't made any bookings yet.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Payment History -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payment History</h5>
                <a href="{{ route('staff.customers.payment-history', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @if($recentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_id }}</td>
                                    <td>
                                        <a href="{{ route('staff.bookings.show', $payment->booking_id) }}" class="text-decoration-none">
                                            {{ $payment->booking->booking_id ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $payment->payment_date->format('M d, Y') }}
                                        <div class="text-muted">{{ $payment->payment_date->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                    </td>
                                    <td>RM {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @if($payment->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($payment->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($payment->payment_status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Total Payments</h6>
                                    @php
                                        $totalPaid = $recentPayments->where('payment_status', 'paid')->sum('amount');
                                    @endphp
                                    <h3 class="text-success">RM {{ number_format($totalPaid, 2) }}</h3>
                                    <small class="text-muted">Paid amount</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Pending Payments</h6>
                                    @php
                                        $pendingCount = $recentPayments->where('payment_status', 'pending')->count();
                                    @endphp
                                    <h3 class="text-warning">{{ $pendingCount }}</h3>
                                    <small class="text-muted">Awaiting payment</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <h5>No Payment History</h5>
                        <p class="text-muted">No payments recorded for this customer.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection