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
        background: linear-gradient(135deg, #ff3232ff 0%, #1e40af 100%);
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
        min-width: 150px;
        max-width: 100%;
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
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .action-panel h5 {
        color: #1e293b;
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

    /* Approval Info Card Enhancement */
    .approval-info-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 2px solid #86efac;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .approval-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .approval-icon {
        width: 48px;
        height: 48px;
        background: #22c55e;
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .approval-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #065f46;
        margin: 0;
    }

    .approval-subtitle {
        font-size: 0.875rem;
        color: #059669;
        margin: 0;
    }

    /* Custom Inspection Tabs */
    .nav-pills {
        background: #f8fafc;
        padding: 0.5rem;
        border-radius: 12px;
        gap: 0.5rem;
    }

    .nav-pills .nav-link {
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748b;
        border: 2px solid transparent;
        transition: all 0.2s;
        background: transparent;
    }

    .nav-pills .nav-link:hover {
        background: white;
        color: #2563eb;
        border-color: #e2e8f0;
    }

    .nav-pills .nav-link.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .nav-pills .nav-link .badge {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 6px;
    }

    /* Tab Content */
    .tab-content {
        padding: 1.5rem 0;
    }

    /* Inspection Details Grid */
    .inspection-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .inspection-detail-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.2s;
    }

    .inspection-detail-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .inspection-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .inspection-value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 600;
    }

    /* Notes and Damages Boxes */
    .notes-box {
        background: #f8fafc;
        border-left: 4px solid #2563eb;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .damage-box {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .box-title {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
        display: flex;
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

    /* Button Enhancements */
    .btn-success {
        background: #22c55e;
        border-color: #22c55e;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        transition: all 0.2s;
    }

    .btn-success:hover {
        background: #16a34a;
        border-color: #16a34a;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .btn-outline-danger {
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        transition: all 0.2s;
    }

    .btn-outline-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-value {
            min-width: 100px;
            text-align: left;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .inspection-details-grid {
            grid-template-columns: 1fr;
        }
        
        .nav-pills {
            flex-direction: column;
        }
        
        .nav-pills .nav-link {
            width: 100%;
            justify-content: center;
        }

        .approval-header {
            flex-direction: column;
            text-align: center;
        }
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
            @if($booking->car->image)
                @if(filter_var($booking->car->image, FILTER_VALIDATE_URL))
                    <img src="{{ $booking->car->image }}" 
                         alt="{{ $booking->car->brand }} {{ $booking->car->model }}" 
                         class="img-fluid rounded shadow"
                         style="max-height: 200px; width: auto; border: 3px solid rgba(255,255,255,0.3); object-fit: contain;">
                @else
                    <img src="{{ asset('storage/' . $booking->car->image) }}" 
                         alt="{{ $booking->car->brand }} {{ $booking->car->model }}" 
                         class="img-fluid rounded shadow"
                         style="max-height: 200px; width: auto; border: 3px solid rgba(255,255,255,0.3); object-fit: contain;">
                @endif
            @else
                <div class="d-flex flex-column align-items-center justify-content-center" 
                     style="height: 200px; background: rgba(255,255,255,0.1); border-radius: 12px; border: 3px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-car" style="font-size: 4rem; opacity: 0.5;"></i>
                    <p class="mt-3 mb-0" style="opacity: 0.7;">No Image Available</p>
                </div>
            @endif
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

        <!-- Approval Information -->
        @if($booking->status !== 'pending' && $booking->approved_by)
        <div class="detail-card approval-info-card">
            <div class="approval-header">
                <div class="approval-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h5 class="approval-title">Booking Approved</h5>
                    <p class="approval-subtitle">This booking has been reviewed and approved</p>
                </div>
            </div>
            
            <div class="inspection-details-grid">
                <div class="inspection-detail-item">
                    <div class="inspection-label">
                        <i class="fas fa-user-check"></i> Approved By
                    </div>
                    <div class="inspection-value">
                        {{ $booking->approvedBy->name ?? 'N/A' }}
                        @if($booking->approvedBy)
                            <span class="badge bg-info ms-2" style="font-size: 0.7rem;">
                                {{ ucfirst($booking->approvedBy->usertype) }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="inspection-detail-item">
                    <div class="inspection-label">
                        <i class="fas fa-calendar-check"></i> Approval Date
                    </div>
                    <div class="inspection-value">
                        {{ $booking->approved_at ? $booking->approved_at->format('d M Y, h:i A') : 'N/A' }}
                    </div>
                </div>
                
                @if($booking->approved_at)
                <div class="inspection-detail-item">
                    <div class="inspection-label">
                        <i class="fas fa-clock"></i> Time Elapsed
                    </div>
                    <div class="inspection-value">
                        {{ $booking->approved_at->diffForHumans() }}
                    </div>
                </div>
                @endif
                
                <div class="inspection-detail-item">
                    <div class="inspection-label">
                        <i class="fas fa-info-circle"></i> Current Status
                    </div>
                    <div class="inspection-value">
                        @php
                            $statusColors = [
                                'confirmed' => 'success',
                                'active' => 'primary',
                                'completed' => 'info',
                                'cancelled' => 'danger'
                            ];
                            $color = $statusColors[$booking->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

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

        <!-- Vehicle Inspection -->
        <div class="detail-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0" style="color: #2563eb;">
                    <i class="fas fa-clipboard-check me-2"></i>Vehicle Inspection
                </h5>
                
                @php
                    $allInspections = $booking->inspections;
                    $pickupInspection = $allInspections->first();
                    $returnInspection = $allInspections->count() > 1 ? $allInspections->last() : null;
                @endphp
                
                <!-- Overall Status Badge -->
                @if($pickupInspection && $returnInspection)
                    <span class="badge bg-success px-3 py-2">
                        <i class="fas fa-check-double me-1"></i>Both Inspections Completed
                    </span>
                @elseif($pickupInspection && !$returnInspection)
                    <span class="badge bg-warning px-3 py-2">
                        <i class="fas fa-hourglass-half me-1"></i>On-Going
                    </span>
                @else
                    <span class="badge bg-secondary px-3 py-2">
                        <i class="fas fa-clock me-1"></i>Inspection Pending
                    </span>
                @endif
            </div>

            <!-- Inspection Tabs -->
            <ul class="nav nav-pills mb-4" id="inspectionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active d-flex align-items-center gap-2" 
                            id="pickup-tab" 
                            data-bs-toggle="pill" 
                            data-bs-target="#pickup-inspection" 
                            type="button" 
                            role="tab">
                        <i class="fas fa-sign-out-alt"></i>
                        Pickup Inspection
                        @if($pickupInspection)
                            <span class="badge bg-success ms-2">
                                <i class="fas fa-check"></i>
                            </span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" 
                            id="return-tab" 
                            data-bs-toggle="pill" 
                            data-bs-target="#return-inspection" 
                            type="button" 
                            role="tab">
                        <i class="fas fa-sign-in-alt"></i>
                        Return Inspection
                        @if($returnInspection)
                            <span class="badge bg-success ms-2">
                                <i class="fas fa-check"></i>
                            </span>
                        @endif
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="inspectionTabsContent">
                <!-- Pickup Inspection Tab -->
                <div class="tab-pane fade show active" id="pickup-inspection" role="tabpanel">
                    @if($pickupInspection)
                        <!-- Display Completed Pickup Inspection -->
                        <div class="alert alert-success border-0" style="background: #d1fae5;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 1.5rem;"></i>
                                <div style="color: #065f46;">
                                    <strong>Pickup Inspection Completed</strong>
                                    <br>
                                    <small>{{ $pickupInspection->inspected_at->format('d M Y, h:i A') }} by {{ $pickupInspection->inspector_name }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Display Inspection Details -->
                        <div class="inspection-details-grid">
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-car-side"></i> Exterior
                                </div>
                                <div class="inspection-value">{{ $pickupInspection->exterior_condition }}</div>
                            </div>
                            
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-couch"></i> Interior
                                </div>
                                <div class="inspection-value">{{ $pickupInspection->interior_condition ?? 'N/A' }}</div>
                            </div>
                            
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-tachometer-alt"></i> Mileage
                                </div>
                                <div class="inspection-value">{{ number_format($pickupInspection->mileage_start ?? 0) }} km</div>
                            </div>
                            
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-gas-pump"></i> Fuel Level
                                </div>
                                <div class="inspection-value">{{ $pickupInspection->fuel_level }}</div>
                            </div>
                        </div>

                        @if($pickupInspection->damages)
                        <div class="damage-box">
                            <div class="box-title">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <span>Damages Noted</span>
                            </div>
                            <p class="mb-0" style="color: #92400e;">{{ $pickupInspection->damages }}</p>
                        </div>
                        @endif

                        @if($pickupInspection->additional_notes)
                        <div class="notes-box">
                            <div class="box-title">
                                <i class="fas fa-sticky-note text-primary"></i>
                                <span>Additional Notes</span>
                            </div>
                            <p class="mb-0" style="color: #475569;">{{ $pickupInspection->additional_notes }}</p>
                        </div>
                        @endif
                    @else
                        <!-- Pickup Inspection Form -->
                        <div class="alert alert-info border-0 mb-4" style="background: #dbeafe;">
                            <div class="d-flex align-items-start gap-3">
                                <i class="fas fa-info-circle" style="color: #2563eb; font-size: 1.25rem; margin-top: 2px;"></i>
                                <div style="color: #1e40af;">
                                    <strong>Pickup Inspection Required</strong>
                                    <p class="mb-0 mt-1 small">Complete this inspection before releasing the vehicle to the customer. Document the vehicle's condition at pickup.</p>
                                </div>
                            </div>
                        </div>

                        @include('staff.bookings.partials.return-inspection-form', ['booking' => $booking, 'type' => 'pickup'])
                    @endif
                </div>

                <!-- Return Inspection Tab -->
                <div class="tab-pane fade" id="return-inspection" role="tabpanel">
                    @if($returnInspection)
                        <!-- Display Completed Return Inspection -->
                        <div class="alert alert-success border-0" style="background: #d1fae5;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 1.5rem;"></i>
                                <div style="color: #065f46;">
                                    <strong>Return Inspection Completed</strong>
                                    <br>
                                    <small>{{ $returnInspection->inspected_at->format('d M Y, h:i A') }} by {{ $returnInspection->inspector_name }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Display Inspection Details -->
                        <div class="inspection-details-grid">
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-car-side"></i> Exterior
                                </div>
                                <div class="inspection-value">{{ $returnInspection->exterior_condition }}</div>
                            </div>
                            
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-couch"></i> Interior
                                </div>
                                <div class="inspection-value">{{ $returnInspection->interior_condition ?? 'N/A' }}</div>
                            </div>
                            
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-tachometer-alt"></i> Return Mileage
                                </div>
                                <div class="inspection-value">{{ number_format($returnInspection->mileage_return ?? 0) }} km</div>
                            </div>
                            
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-gas-pump"></i> Fuel Level
                                </div>
                                <div class="inspection-value">{{ $returnInspection->fuel_level }}</div>
                            </div>
                            
                            @if($returnInspection->cleanliness)
                            <div class="inspection-detail-item">
                                <div class="inspection-label">
                                    <i class="fas fa-sparkles"></i> Cleanliness
                                </div>
                                <div class="inspection-value">{{ $returnInspection->cleanliness }}</div>
                            </div>
                            @endif
                        </div>

                        @if($returnInspection->damages)
                        <div class="damage-box">
                            <div class="box-title">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <span>Damages Noted</span>
                            </div>
                            <p class="mb-0" style="color: #92400e;">{{ $returnInspection->damages }}</p>
                        </div>
                        @endif

                        @if($returnInspection->additional_notes)
                        <div class="notes-box">
                            <div class="box-title">
                                <i class="fas fa-sticky-note text-primary"></i>
                                <span>Additional Notes</span>
                            </div>
                            <p class="mb-0" style="color: #475569;">{{ $returnInspection->additional_notes }}</p>
                        </div>
                        @endif
                    @else
                        @if(!$pickupInspection)
                            <!-- Must complete pickup first -->
                            <div class="alert alert-warning border-0" style="background: #fef3c7;">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-exclamation-triangle" style="color: #d97706; font-size: 1.25rem; margin-top: 2px;"></i>
                                    <div style="color: #92400e;">
                                        <strong>Pickup Inspection Required First</strong>
                                        <p class="mb-0 mt-1 small">You must complete the pickup inspection before you can perform the return inspection.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Return Inspection Form -->
                            <div class="alert alert-warning border-0 mb-4" style="background: #fef3c7;">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-sign-in-alt" style="color: #d97706; font-size: 1.25rem; margin-top: 2px;"></i>
                                    <div style="color: #92400e;">
                                        <strong>Return Inspection Required</strong>
                                        <p class="mb-0 mt-1 small">Complete this inspection when the customer returns the vehicle. Compare the condition with the pickup inspection.</p>
                                    </div>
                                </div>
                            </div>

                            @include('staff.bookings.partials.return-inspection-form', ['booking' => $booking, 'type' => 'return'])
                        @endif
                    @endif
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

// Open rejection modal
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