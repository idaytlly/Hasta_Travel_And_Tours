@extends('staff.layouts.staff')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')
@section('page-subtitle', 'View and manage booking information')

@section('page-header')
    <div class="row">
        <div class="col">
            <h1 class="h3 mb-0">Booking: {{ $booking->booking_id }}</h1>
            <p class="text-muted mb-0">Created: {{ $booking->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Booking Details -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Booking Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="text-muted">Status</label>
                        <div>
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
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted">Total Price</label>
                        <h4>RM {{ number_format($booking->total_price, 2) }}</h4>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted">Approved By</label>
                        <div>
                            @if($booking->approvedByStaff)
                                <strong>{{ $booking->approvedByStaff->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $booking->approved_at->format('M d, Y H:i') }}</small>
                            @else
                                <span class="text-muted">Not approved yet</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted">Customer Information</label>
                        <div class="border rounded p-3">
                            <h6>{{ $booking->customer->name }}</h6>
                            <p class="mb-1">
                                <i class="fas fa-envelope me-2 text-muted"></i>
                                {{ $booking->customer->email }}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-phone me-2 text-muted"></i>
                                {{ $booking->customer->phone_no }}
                            </p>
                            @if($booking->customer->license_no)
                            <p class="mb-0">
                                <i class="fas fa-id-card me-2 text-muted"></i>
                                {{ $booking->customer->license_no }}
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted">Vehicle Information</label>
                        <div class="border rounded p-3">
                            <h6>{{ $booking->vehicle->name }}</h6>
                            <p class="mb-1">
                                <i class="fas fa-car me-2 text-muted"></i>
                                {{ $booking->vehicle->plate_no }}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-tachometer-alt me-2 text-muted"></i>
                                RM {{ number_format($booking->vehicle->price_perHour, 2) }}/hour
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-gas-pump me-2 text-muted"></i>
                                {{ ucfirst($booking->vehicle->fuel_type) }} • {{ ucfirst($booking->vehicle->transmission) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted">Pickup Details</label>
                        <div class="border rounded p-3">
                            <p class="mb-1">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                {{ \Carbon\Carbon::parse($booking->pickup_date)->format('l, M d, Y') }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                {{ $booking->pickup_time }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted">Return Details</label>
                        <div class="border rounded p-3">
                            <p class="mb-1">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                {{ \Carbon\Carbon::parse($booking->return_date)->format('l, M d, Y') }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                {{ $booking->return_time }}
                            </p>
                        </div>
                    </div>
                </div>

                @if($booking->voucher_id)
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="text-muted">Voucher Applied</label>
                        <div class="border rounded p-3">
                            <p class="mb-0">
                                <i class="fas fa-ticket-alt me-2 text-muted"></i>
                                {{ $booking->voucher->voucherCode }} - 
                                @if($booking->voucher->voucherType == 'percentage')
                                    {{ $booking->voucher->voucherAmount }}% Discount
                                @else
                                    RM {{ number_format($booking->voucher->voucherAmount, 2) }} Discount
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if($booking->special_requests)
                <div class="row">
                    <div class="col-12">
                        <label class="text-muted">Special Requests</label>
                        <div class="border rounded p-3">
                            <p class="mb-0">{{ $booking->special_requests }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions & Payments -->
    <div class="col-md-4 mb-4">
        <!-- Actions Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Booking Actions</h5>
            </div>
            <div class="card-body">
                @if($booking->booking_status == 'pending')
                <form method="POST" action="{{ route('staff.bookings.approve', $booking->booking_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-check me-2"></i> Approve Booking
                    </button>
                </form>
                @endif

                @if($booking->booking_status == 'confirmed')
                <form method="POST" action="{{ route('staff.bookings.mark-active', $booking->booking_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-info w-100 mb-2">
                        <i class="fas fa-play me-2"></i> Mark as Active (Pickup)
                    </button>
                </form>
                @endif

                @if($booking->booking_status == 'active')
                <form method="POST" action="{{ route('staff.bookings.mark-completed', $booking->booking_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-check-circle me-2"></i> Mark as Completed (Return)
                    </button>
                </form>
                @endif

                @if(in_array($booking->booking_status, ['pending', 'confirmed']))
                <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="fas fa-edit me-2"></i> Edit Booking
                </button>
                
                <button type="button" class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="fas fa-times me-2"></i> Cancel Booking
                </button>
                @endif

                @if($booking->booking_status == 'pending')
                <form method="POST" action="{{ route('staff.bookings.destroy', $booking->booking_id) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this booking?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-2"></i> Delete Booking
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Payments Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payments</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($booking->payments->count() > 0)
                <div class="list-group">
                    @foreach($booking->payments as $payment)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">RM {{ number_format($payment->amount, 2) }}</h6>
                                <small class="text-muted">
                                    {{ ucfirst($payment->payment_method) }}
                                    @if($payment->verified_at)
                                        • Verified {{ $payment->verified_at->format('M d') }}
                                    @endif
                                </small>
                            </div>
                            <span class="badge bg-{{ $payment->payment_status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </div>
                        @if($payment->payment_notes)
                        <small class="text-muted mt-1 d-block">{{ $payment->payment_notes }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center mb-0">No payments recorded</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('staff.bookings.update', $booking->booking_id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Edit form fields would go here -->
                    <p>Edit form coming soon...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('staff.bookings.cancel', $booking->booking_id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action cannot be undone. The vehicle will be made available for other bookings.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('staff.payments.record') }}">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (RM)</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="">Select method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="payment_notes" name="payment_notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection