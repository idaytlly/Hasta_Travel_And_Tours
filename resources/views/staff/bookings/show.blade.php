@extends('layouts.staff')

@section('title', 'Booking Summary')
@section('page-title', 'Booking Summary')

@section('content')
<style>
    .summary-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .vehicle-showcase {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .vehicle-showcase::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(30deg);
    }
    
    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
    }
    
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 500;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .detail-value {
        font-weight: 500;
        color: #1e293b;
        text-align: right;
        background: #f8fafc;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        min-width: 200px;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid transparent;
    }
    
    .status-badge.status-pending {
        background: #fef3c7;
        color: #d97706;
        border-color: #fbbf24;
    }
    
    .status-badge.status-confirmed {
        background: #d1fae5;
        color: #059669;
        border-color: #34d399;
    }
    
    .status-badge.status-active {
        background: #dbeafe;
        color: #2563eb;
        border-color: #60a5fa;
    }
    
    .status-badge.status-completed {
        background: #e0e7ff;
        color: #6366f1;
        border-color: #818cf8;
    }
    
    .status-badge.status-cancelled {
        background: #fee2e2;
        color: #dc2626;
        border-color: #f87171;
    }
    
    .action-panel {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .payment-breakdown {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }
    
    .payment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
    }
    
    .payment-total {
        border-top: 2px solid #e2e8f0;
        margin-top: 1rem;
        padding-top: 1rem;
        font-size: 1.25rem;
        font-weight: 700;
    }
    
    .duration-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Modal Styles */
    .modal-header-reject {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 12px 12px 0 0;
    }
    
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .reject-reason-textarea {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.938rem;
        transition: all 0.2s;
    }
    
    .reject-reason-textarea:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        outline: none;
    }
    
    .quick-reason-btn {
        padding: 8px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: white;
        color: #64748b;
        font-size: 0.875rem;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .quick-reason-btn:hover {
        border-color: #ef4444;
        background: #fef2f2;
        color: #ef4444;
    }
    
    .btn-reject-confirm {
        background: #ef4444;
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-reject-confirm:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
</style>

<div class="container-fluid py-4">
    <div class="summary-container">
        <!-- Header with Back Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Bookings
            </a>
            <div class="text-end">
                <div class="badge bg-light text-dark px-3 py-2 rounded-pill">
                    <i class="fas fa-clock me-2 text-primary"></i>
                    Viewing Booking Summary
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert" 
                 style="border-radius: 10px; border: none; box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong class="me-2">Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Vehicle Showcase -->
        <div class="vehicle-showcase">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">
                        {{ $booking->car->brand }} {{ $booking->car->model }} {{ $booking->car->year }}
                    </h2>
                    <div class="mb-4">
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'status-pending', 'icon' => 'fa-clock', 'label' => 'Pending Review'],
                                'confirmed' => ['class' => 'status-confirmed', 'icon' => 'fa-check-circle', 'label' => 'Confirmed'],
                                'active' => ['class' => 'status-active', 'icon' => 'fa-car', 'label' => 'Active'],
                                'completed' => ['class' => 'status-completed', 'icon' => 'fa-flag-checkered', 'label' => 'Completed'],
                                'cancelled' => ['class' => 'status-cancelled', 'icon' => 'fa-times-circle', 'label' => 'Cancelled']
                            ];
                            $config = $statusConfig[$booking->status] ?? ['class' => 'status-pending', 'icon' => 'fa-question', 'label' => $booking->status];
                        @endphp
                        <span class="status-badge {{ $config['class'] }}">
                            <i class="fas {{ $config['icon'] }}"></i>
                            {{ $config['label'] }}
                        </span>
                    </div>
                    <div class="duration-badge">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $booking->duration ?? \Carbon\Carbon::parse($booking->pickup_date)->diffInDays($booking->return_date) }} Day Rental
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <img src="{{ $booking->car->image }}" 
                         alt="{{ $booking->car->brand }}" 
                         class="img-fluid rounded shadow"
                         style="max-height: 180px; border: 3px solid rgba(255,255,255,0.2);">
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="detail-card">
            <h5 class="fw-bold mb-4" style="color: #2563eb;">
                <i class="fas fa-user-circle me-2"></i>Customer Information
            </h5>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-hashtag text-muted"></i>
                        Booking Reference
                    </div>
                    <div class="detail-value fw-bold text-primary">
                        {{ $booking->booking_reference }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-user text-muted"></i>
                        Customer Name
                    </div>
                    <div class="detail-value">
                        {{ $booking->user->name ?? $booking->customer_name ?? 'Guest Customer' }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-envelope text-muted"></i>
                        Email Address
                    </div>
                    <div class="detail-value">
                        {{ $booking->user->email ?? $booking->customer_email ?? 'N/A' }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-phone text-muted"></i>
                        Phone Number
                    </div>
                    <div class="detail-value">
                        {{ $booking->user->phone ?? $booking->customer_phone ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Rental Details -->
        <div class="detail-card">
            <h5 class="fw-bold mb-4" style="color: #2563eb;">
                <i class="fas fa-road me-2"></i>Rental Details
            </h5>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-calendar-plus text-muted"></i>
                        Pickup Date & Time
                    </div>
                    <div class="detail-value fw-bold">
                        {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}, 
                        {{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-calendar-minus text-muted"></i>
                        Return Date & Time
                    </div>
                    <div class="detail-value fw-bold">
                        {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}, 
                        {{ \Carbon\Carbon::parse($booking->return_time)->format('h:i A') }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-map-marker-alt text-muted"></i>
                        Pickup Location
                    </div>
                    <div class="detail-value">
                        {{ $booking->pickup_location }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-map-marker text-muted"></i>
                        Return Location
                    </div>
                    <div class="detail-value">
                        {{ $booking->dropoff_location }}
                    </div>
                </div>
                
                @if($booking->destination)
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-location-arrow text-muted"></i>
                        Destination
                    </div>
                    <div class="detail-value">
                        {{ $booking->destination }}
                    </div>
                </div>
                @endif
                
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="fas fa-car text-muted"></i>
                        Vehicle Category
                    </div>
                    <div class="detail-value">
                        {{ ucfirst($booking->car->category ?? 'Standard') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="detail-card">
            <h5 class="fw-bold mb-4" style="color: #2563eb;">
                <i class="fas fa-receipt me-2"></i>Payment Breakdown
            </h5>
            
            <div class="payment-breakdown">
                <div class="payment-item">
                    <span class="text-muted">Base Price</span>
                    <span class="fw-bold">RM {{ number_format($booking->base_price ?? $booking->total_price, 2) }}</span>
                </div>
                
                @if($booking->discount_amount && $booking->discount_amount > 0)
                <div class="payment-item">
                    <span class="text-success">
                        <i class="fas fa-tag me-2"></i>Discount
                    </span>
                    <span class="fw-bold text-success">- RM {{ number_format($booking->discount_amount, 2) }}</span>
                </div>
                @endif
                
                @if($booking->voucher)
                <div class="payment-item">
                    <span class="text-success">
                        <i class="fas fa-ticket-alt me-2"></i>Voucher Applied
                    </span>
                    <span class="fw-bold text-success">{{ $booking->voucher }}</span>
                </div>
                @endif
                
                <div class="payment-item">
                    <span class="text-muted">Deposit Required</span>
                    <span class="fw-bold">RM {{ number_format($booking->deposit_amount ?? 100, 2) }}</span>
                </div>
                
                <div class="payment-item payment-total">
                    <span class="text-dark">Total Amount</span>
                    <span class="fs-4 fw-bold" style="color: #10b981;">
                        RM {{ number_format($booking->total_price, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if($booking->remarks)
        <div class="detail-card">
            <h5 class="fw-bold mb-4" style="color: #2563eb;">
                <i class="fas fa-sticky-note me-2"></i>Additional Information
            </h5>
            <div class="alert alert-light border" style="border-radius: 8px;">
                <div class="d-flex">
                    <i class="fas fa-info-circle me-3 mt-1" style="color: #2563eb;"></i>
                    <div>
                        <h6 class="fw-bold mb-2">Special Remarks</h6>
                        <p class="mb-0">{{ $booking->remarks }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Panel -->
        <div class="action-panel">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-tasks me-2"></i>Booking Actions
                    </h5>
                    <p class="text-muted mb-0 small mt-1">
                        Current status: <span class="fw-bold">{{ ucfirst($booking->status) }}</span>
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="d-grid gap-2">
                        @if($booking->status === 'pending')
                            <div class="d-flex gap-2">
                                <button type="button" 
                                        class="btn btn-outline-danger flex-grow-1" 
                                        onclick="openRejectModal({{ $booking->id }}, '{{ $booking->booking_reference }}')">
                                    <i class="fas fa-times me-2"></i>Reject
                                </button>
                                <form action="{{ route('staff.bookings.approve', $booking->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success w-100"
                                            onclick="return confirm('Are you sure you want to approve this booking?')">
                                        <i class="fas fa-check me-2"></i>Approve
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info mb-0 text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                This booking is already {{ $booking->status }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectBookingModal" tabindex="-1" aria-labelledby="rejectBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-reject border-0">
                <h5 class="modal-title d-flex align-items-center gap-2" id="rejectBookingModalLabel">
                    <i class="fas fa-times-circle"></i>
                    Reject Booking
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Use the correct route -->
                <form id="rejectBookingForm" method="POST" action="{{ route('staff.bookings.cancel', $booking->id) }}">
                    @csrf                    
                    <div class="alert alert-warning border-0 d-flex align-items-start gap-3 mb-4" style="background: #fef3c7; border-radius: 8px;">
                        <i class="fas fa-exclamation-triangle" style="color: #d97706; font-size: 1.25rem; margin-top: 2px;"></i>
                        <div style="color: #92400e;">
                            <strong>Important:</strong> This action will cancel the booking and notify the customer. Please provide a clear reason.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="rejection_reason" class="form-label fw-semibold mb-2">
                            <i class="fas fa-comment-alt me-2 text-muted"></i>Rejection Reason
                        </label>
                        
                        <!-- Quick Reason Buttons -->
                        <div class="mb-3">
                            <p class="small text-muted mb-2">Quick reasons:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="quick-reason-btn" onclick="setReason('Vehicle not available')">
                                    Vehicle not available
                                </button>
                                <button type="button" class="quick-reason-btn" onclick="setReason('Invalid payment information')">
                                    Invalid payment
                                </button>
                                <button type="button" class="quick-reason-btn" onclick="setReason('Customer did not meet requirements')">
                                    Requirements not met
                                </button>
                                <button type="button" class="quick-reason-btn" onclick="setReason('Booking details incomplete')">
                                    Incomplete details
                                </button>
                                <button type="button" class="quick-reason-btn" onclick="setReason('Other administrative reasons')">
                                    Other reasons
                                </button>
                            </div>
                        </div>
                        
                        <textarea 
                            class="form-control reject-reason-textarea" 
                            id="rejection_reason" 
                            name="cancellation_reason" 
                            rows="4" 
                            required
                            placeholder="Enter the reason for rejecting this booking..."></textarea>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            This reason will be sent to the customer via email and notification.
                        </small>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-reject-confirm">
                            <i class="fas fa-ban me-2"></i>Reject Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Set rejection reason from quick buttons
function setReason(reason) {
    document.getElementById('rejection_reason').value = reason;
}

// Open rejection modal - simplified version
function openRejectModal(bookingId, bookingReference) {
    document.getElementById('rejectBookingModalLabel').innerHTML = `
        <i class="fas fa-times-circle"></i>
        Reject Booking #${bookingReference}
    `;
    
    document.getElementById('rejection_reason').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('rejectBookingModal'));
    modal.show();
}

// Form submission handling
document.addEventListener('DOMContentLoaded', function() {
    const rejectForm = document.getElementById('rejectBookingForm');
    
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            const reason = document.getElementById('rejection_reason').value.trim();
            
            if (reason.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed reason (at least 10 characters).');
                return false;
            }
            
            if (!confirm('Are you sure you want to reject this booking? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        });
    }
});
</script>
@endsection