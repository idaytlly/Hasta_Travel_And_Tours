@extends('layouts.staff')
<<<<<<< Updated upstream

@section('title', 'Booking Management')
=======
@@section('title', 'Booking Management')
>>>>>>> Stashed changes
@section('page-title', 'Booking Management')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --primary-dark: #b91c1c;
        --primary-light: rgba(220, 38, 38, 0.1);
        --success: #22c55e;
        --warning: #f59e0b;
        --info: #3b82f6;
        --purple: #8b5cf6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f1f5f9;
        --white: #ffffff;
        --border: #e2e8f0;
    }

    body {
        background: var(--light) !important;
    }

    /* Header */
    .page-header-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 40px rgba(220, 38, 38, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .header-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .header-subtitle {
        opacity: 0.9;
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }

    /* Search Bar */
    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.2s;
        background: white;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        font-size: 1rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
    }

    /* Filter Card */
    .filter-card {
        background: var(--white);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        scrollbar-width: thin;
    }

    .filter-tabs::-webkit-scrollbar {
        height: 4px;
    }

    .filter-tabs::-webkit-scrollbar-track {
        background: var(--light);
        border-radius: 4px;
    }

    .filter-tabs::-webkit-scrollbar-thumb {
        background: var(--gray);
        border-radius: 4px;
    }

    .filter-tab {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        border: 2px solid var(--border);
        background: var(--white);
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .filter-tab:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
        transform: translateY(-2px);
    }

    .filter-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .tab-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
    }

    .filter-tab.active .tab-badge {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Booking Card - Improved */
    .booking-list {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .booking-item {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        transition: all 0.2s;
    }

    .booking-item:last-child {
        border-bottom: none;
    }

    .booking-item:hover {
        background: var(--light);
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .booking-ref-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, var(--primary-light) 0%, rgba(220, 38, 38, 0.05) 100%);
        border: 1px solid var(--primary);
        border-radius: 8px;
        font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--primary);
    }

    .booking-content {
        display: grid;
        grid-template-columns: 2fr 2fr 1.5fr 1.5fr auto;
        gap: 2rem;
        align-items: center;
    }

    /* Customer Section */
    .customer-section {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .customer-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .customer-details {
        flex: 1;
        min-width: 0;
    }

    .customer-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .customer-email {
        font-size: 0.8125rem;
        color: var(--gray);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Vehicle Section - Improved */
    .vehicle-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .vehicle-image-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .vehicle-image {
        width: 80px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid var(--border);
        transition: all 0.2s;
    }

    .vehicle-image:hover {
        transform: scale(1.05);
        border-color: var(--primary);
    }

    .vehicle-placeholder {
        width: 80px;
        height: 60px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--light) 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray);
        font-size: 1.5rem;
        border: 2px solid var(--border);
    }

    .vehicle-details {
        flex: 1;
        min-width: 0;
    }

    .vehicle-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .vehicle-year {
        font-size: 0.8125rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    /* Rental Info */
    .rental-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .info-icon {
        color: var(--gray);
        width: 16px;
        text-align: center;
    }

    .info-label {
        color: var(--gray);
        min-width: 50px;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark);
    }

    /* Price Section */
    .price-section {
        text-align: center;
    }

    .price-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .price-duration {
        font-size: 0.8125rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .status-pending { background: rgba(245, 158, 11, 0.1); color: #d97706; }
    .status-pending .status-dot { background: #d97706; }

    .status-confirmed { background: rgba(34, 197, 94, 0.1); color: #16a34a; }
    .status-confirmed .status-dot { background: #16a34a; }

    .status-active { background: rgba(59, 130, 246, 0.1); color: #1d4ed8; }
    .status-active .status-dot { background: #1d4ed8; }

    .status-completed { background: rgba(139, 92, 246, 0.1); color: #7c3aed; }
    .status-completed .status-dot { background: #7c3aed; }

    .status-cancelled { background: rgba(220, 38, 38, 0.1); color: #b91c1c; }
    .status-cancelled .status-dot { background: #b91c1c; }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.625rem 1rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        border: 2px solid;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-view {
        background: var(--white);
        color: var(--info);
        border-color: var(--info);
    }

    .btn-view:hover {
        background: var(--info);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-approve {
        background: var(--success);
        color: white;
        border-color: var(--success);
    }

    .btn-approve:hover {
        background: #16a34a;
        border-color: #16a34a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--light);
        border-radius: 16px;
        margin: 2rem 0;
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--gray);
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }

    .empty-text {
        color: var(--gray);
        margin-bottom: 2rem;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .booking-content {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .action-buttons {
            flex-direction: row;
        }
    }

    @media (max-width: 768px) {
        .page-header-card {
            padding: 1.5rem;
        }

        .header-title {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .vehicle-image, .vehicle-placeholder {
            width: 60px;
            height: 45px;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>

<div class="container-fluid py-4">
    
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-calendar-check me-2"></i>Booking Management
                </h1>
                <p class="header-subtitle mb-0">Monitor and manage all rental reservations</p>
            </div>
            <div class="text-end">
                <div style="font-size: 0.875rem; opacity: 0.9;">
                    <i class="fas fa-clock me-1"></i>
                    {{ now()->format('g:i A') }}
                </div>
                <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.25rem;">
                    {{ now()->format('l, M d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" 
             style="border-radius: 12px; border-left: 4px solid var(--success); background: rgba(34, 197, 94, 0.1); border: none; border-left: 4px solid var(--success);">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3" style="color: var(--success); font-size: 1.5rem;"></i>
                <div class="flex-grow-1">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
        @php
            $stats = [
                'pending' => [
                    'icon' => 'fas fa-clock',
                    'title' => 'Pending',
                    'count' => \App\Models\Booking::where('status', 'pending')->count(),
                    'color' => '#f59e0b'
                ],
                'confirmed' => [
                    'icon' => 'fas fa-check-circle',
                    'title' => 'Confirmed',
                    'count' => \App\Models\Booking::where('status', 'confirmed')->count(),
                    'color' => '#22c55e'
                ],
                'active' => [
                    'icon' => 'fas fa-car',
                    'title' => 'Active',
                    'count' => \App\Models\Booking::where('status', 'active')->count(),
                    'color' => '#3b82f6'
                ],
                'completed' => [
                    'icon' => 'fas fa-flag-checkered',
                    'title' => 'Completed',
                    'count' => \App\Models\Booking::where('status', 'completed')->count(),
                    'color' => '#8b5cf6'
                ],
                'cancelled' => [
                    'icon' => 'fas fa-times-circle',
                    'title' => 'Cancelled',
                    'count' => \App\Models\Booking::where('status', 'cancelled')->count(),
                    'color' => '#dc2626'
                ],
                'total' => [
                    'icon' => 'fas fa-clipboard-list',
                    'title' => 'Total',
                    'count' => \App\Models\Booking::count(),
                    'color' => '#1e293b'
                ]
            ];
        @endphp
        
        @foreach($stats as $key => $stat)
            <div class="stat-card" onclick="window.location.href='{{ $key === 'total' ? route('staff.bookings.index') : route('staff.bookings.index', ['status' => $key]) }}'">
                <div class="stat-icon" style="background: {{ $stat['color'] }}1a; color: {{ $stat['color'] }};">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
                <div class="stat-value">{{ $stat['count'] }}</div>
                <div class="stat-label">{{ $stat['title'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search by booking reference, customer name, or email...">
        </div>

        <div class="filter-tabs">
            <a href="{{ route('staff.bookings.index') }}" 
               class="filter-tab {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                All Bookings
            </a>
            <a href="{{ route('staff.bookings.index', ['status' => 'pending']) }}" 
               class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                Pending
                @if($stats['pending']['count'] > 0)
                    <span class="tab-badge">{{ $stats['pending']['count'] }}</span>
                @endif
            </a>
            <a href="{{ route('staff.bookings.index', ['status' => 'confirmed']) }}" 
               class="filter-tab {{ request('status') == 'confirmed' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                Confirmed
                @if($stats['confirmed']['count'] > 0)
                    <span class="tab-badge">{{ $stats['confirmed']['count'] }}</span>
                @endif
            </a>
            <a href="{{ route('staff.bookings.index', ['status' => 'active']) }}" 
               class="filter-tab {{ request('status') == 'active' ? 'active' : '' }}">
                <i class="fas fa-car"></i>
                Active
                @if($stats['active']['count'] > 0)
                    <span class="tab-badge">{{ $stats['active']['count'] }}</span>
                @endif
            </a>
            <a href="{{ route('staff.bookings.index', ['status' => 'completed']) }}" 
               class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
                <i class="fas fa-flag-checkered"></i>
                Completed
                @if($stats['completed']['count'] > 0)
                    <span class="tab-badge">{{ $stats['completed']['count'] }}</span>
                @endif
            </a>
            <a href="{{ route('staff.bookings.index', ['status' => 'cancelled']) }}" 
               class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i>
                Cancelled
                @if($stats['cancelled']['count'] > 0)
                    <span class="tab-badge">{{ $stats['cancelled']['count'] }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Bookings List -->
    @php
        $query = \App\Models\Booking::with(['car', 'user', 'approvedBy']);
        
        if (request('status')) {
            if (request('status') == 'cancelled') {
                $query = \App\Models\Booking::onlyTrashed()->with(['car', 'user', 'approvedBy']);
                $query->where('status', 'cancelled');
            } else {
                $query->where('status', request('status'));
            }
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->get();
    @endphp

    <div class="booking-list" id="bookingList">
        @forelse($bookings as $booking)
            <div class="booking-item" data-search="{{ strtolower($booking->booking_reference . ' ' . ($booking->user ? $booking->user->name : '') . ' ' . ($booking->user ? $booking->user->email : '')) }}">
                <!-- Booking Header -->
                <div class="booking-header">
                    <span class="booking-ref-badge">
                        <i class="fas fa-hashtag"></i>
                        {{ $booking->booking_reference }}
                    </span>
                    <span class="status-badge status-{{ $booking->status }}">
                        <span class="status-dot"></span>
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <!-- Booking Content -->
                <div class="booking-content">
                    <!-- Customer Info -->
                    <div class="customer-section">
                        <div class="customer-avatar">
                            {{ strtoupper(substr($booking->user ? $booking->user->name : 'G', 0, 1)) }}
                        </div>
                        <div class="customer-details">
                            <div class="customer-name">{{ $booking->user ? $booking->user->name : 'Guest Customer' }}</div>
                            @if($booking->user)
                                <div class="customer-email">{{ $booking->user->email }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="vehicle-section">
                        @if($booking->car)
                            <div class="vehicle-image-wrapper">
                                @if($booking->car->image)
                                    @if(filter_var($booking->car->image, FILTER_VALIDATE_URL))
                                        <img src="{{ $booking->car->image }}" 
                                             alt="{{ $booking->car->brand }}" 
                                             class="vehicle-image">
                                    @else
                                        <img src="{{ asset('storage/' . $booking->car->image) }}" 
                                             alt="{{ $booking->car->brand }}" 
                                             class="vehicle-image">
                                    @endif
                                @else
                                    <div class="vehicle-placeholder">
                                        <i class="fas fa-car"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="vehicle-details">
                                <div class="vehicle-name">{{ $booking->car->brand }} {{ $booking->car->model }}</div>
                                <div class="vehicle-year">
                                    <i class="far fa-calendar"></i>
                                    {{ $booking->car->year }}
                                </div>
                            </div>
                        @else
                            <div class="text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Vehicle Not Found
                            </div>
                        @endif
                    </div>

                    <!-- Rental Info -->
                    <div class="rental-info">
                        <div class="info-row">
                            <i class="fas fa-sign-out-alt info-icon"></i>
                            <span class="info-label">Pickup:</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-sign-in-alt info-icon"></i>
                            <span class="info-label">Return:</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="price-section">
                        <div class="price-amount">RM {{ number_format($booking->total_price, 2) }}</div>
                        <div class="price-duration">
                            <i class="fas fa-calendar-day"></i>
                            {{ $booking->duration }} day{{ $booking->duration > 1 ? 's' : '' }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="action-buttons">
                        <a href="{{ route('staff.bookings.show', $booking->id) }}" 
                           class="btn-action btn-view">
                            <i class="fas fa-eye"></i>
                            View Details
                        </a>
                        
                        @if($booking->status === 'pending')
                            <form action="{{ route('staff.bookings.approve', $booking->id) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Approve this booking?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-approve w-100">
                                    <i class="fas fa-check"></i>
                                    Approve
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="empty-title">No Bookings Found</h3>
                <p class="empty-text">
                    @if(request('status'))
                        There are no {{ request('status') }} bookings at the moment.
                    @else
                        No bookings have been made yet.
                    @endif
                </p>
                <a href="{{ route('staff.bookings.index') }}" class="btn btn-primary">
                    <i class="fas fa-redo me-2"></i>Refresh
                </a>
            </div>
        @endforelse
    </div>

    <!-- Footer Info -->
    @if($bookings->count() > 0)
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Showing <span id="visibleCount">{{ $bookings->count() }}</span> of {{ $bookings->count() }} booking{{ $bookings->count() !== 1 ? 's' : '' }} • 
                Last updated {{ now()->format('g:i A') }}
            </small>
        </div>
    @endif
</div>

<script>
// Real-time search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const bookingItems = document.querySelectorAll('.booking-item');
    let visibleCount = 0;

    bookingItems.forEach(item => {
        const searchData = item.getAttribute('data-search');
        if (searchData.includes(searchTerm)) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Update visible count
    document.getElementById('visibleCount').textContent = visibleCount;

    // Show/hide empty state
    const bookingList = document.getElementById('bookingList');
    if (visibleCount === 0 && searchTerm !== '') {
        if (!document.getElementById('searchEmptyState')) {
            const emptyState = document.createElement('div');
            emptyState.id = 'searchEmptyState';
            emptyState.className = 'empty-state';
            emptyState.innerHTML = `
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="empty-title">No Results Found</h3>
                <p class="empty-text">Try adjusting your search terms</p>
            `;
            bookingList.appendChild(emptyState);
        }
    } else {
        const emptyState = document.getElementById('searchEmptyState');
        if (emptyState) {
            emptyState.remove();
        }
    }
<<<<<<< Updated upstream
=======

visibleCount;

    // Show/hide empty state
    const bookingList = document.getElementById('bookingList');
    if (visibleCount === 0 && searchTerm !== '') {
        if (!document.getElementById('searchEmptyState')) {
            const emptyState = document.createElement('div');
            emptyState.id = 'searchEmptyState';
            emptyState.className = 'empty-state';
            emptyState.innerHTML = `
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="empty-title">No Results Found</h3>
                <p class="empty-text">Try adjusting your search terms</p>
            `;
            bookingList.appendChild(emptyState);
        }
    } else {
        const emptyState = document.getElementById('searchEmptyState');
        if (emptyState) {
            emptyState.remove();
        }
    }
>>>>>>> Stashed changes
});
</script>
@endsection