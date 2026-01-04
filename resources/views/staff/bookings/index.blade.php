@extends('layouts.staff')

@section('page-title')
    <i class="fas fa-calendar-check me-2"></i>Booking Management
@endsection

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --primary-dark: #b91c1c;
        --primary-light: rgba(220, 38, 38, 0.1);
        --dark: #1e293b;
        --gray: #64748b;
        --gray-light: #e2e8f0;
        --light: #f8fafc;
        --success: #059669;
        --warning: #d97706;
        --info: #2563eb;
        --danger: #dc2626;
    }

    /* Status Badge */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .status-pending {
        background: rgba(217, 119, 6, 0.1);
        color: #d97706;
    }

    .status-confirmed {
        background: rgba(5, 150, 105, 0.1);
        color: #059669;
    }

    .status-active {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }

    .status-completed {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .status-cancelled {
        background: rgba(220, 38, 38, 0.1);
        color: #dc2626;
    }

    /* Booking Card */
    .booking-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        transition: all 0.2s ease;
        background: white;
        margin-bottom: 12px;
    }

    .booking-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.08);
    }

    .car-image {
        width: 70px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--gray-light);
    }

    /* Filter Tabs */
    .filter-tabs .nav-link {
        border: none;
        color: var(--gray);
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        position: relative;
        transition: all 0.2s;
        border-radius: 6px 6px 0 0;
        margin-right: 2px;
    }

    .filter-tabs .nav-link:hover {
        color: var(--primary);
        background: var(--primary-light);
    }

    .filter-tabs .nav-link.active {
        color: var(--primary);
        background: transparent;
        border-bottom: 3px solid var(--primary);
        font-weight: 600;
    }

    .filter-tabs .nav-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        margin-left: 4px;
    }

    /* Action Buttons */
    .action-btn {
        padding: 6px 12px;
        font-size: 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-view {
        border: 1px solid var(--gray-light);
        color: var(--gray);
        background: white;
    }

    .btn-view:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    .btn-approve {
        border: 1px solid var(--success);
        color: var(--success);
        background: white;
    }

    .btn-approve:hover {
        background: var(--success);
        color: white;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border: 1px solid var(--gray-light);
        border-radius: 10px;
        padding: 1.25rem;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-bottom: 0.75rem;
        color: var(--primary);
        background: var(--primary-light);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--gray);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Amount Display */
    .amount-display {
        font-weight: 700;
        color: var(--dark);
        font-size: 1.125rem;
    }

    /* Time Range Filter */
    .time-range-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        border: 1px solid var(--gray-light);
        color: var(--gray);
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .time-range-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .time-range-btn:hover:not(.active) {
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 2rem;
        text-align: center;
        background: var(--light);
        border-radius: 10px;
        border: 1px dashed var(--gray-light);
    }

    .empty-state-icon {
        font-size: 3rem;
        color: var(--gray-light);
        margin-bottom: 1rem;
    }

    /* Booking Reference */
    .booking-ref {
        font-family: 'SF Mono', Monaco, Consolas, monospace;
        background: var(--light);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        color: var(--dark);
        border: 1px solid var(--gray-light);
    }

    /* Page Header */
    .page-header {
        background: white;
        border: 1px solid var(--gray-light);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Table-like Layout */
    .booking-row {
        display: grid;
        grid-template-columns: 220px 160px 200px 120px 140px 140px;
        gap: 1rem;
        align-items: center;
        padding: 1rem 1.25rem;
    }

    @media (max-width: 1400px) {
        .booking-row {
            grid-template-columns: 200px 140px 180px 110px 130px 130px;
            gap: 0.75rem;
        }
    }

    @media (max-width: 1200px) {
        .booking-row {
            grid-template-columns: 180px 120px 160px 100px 120px 120px;
            gap: 0.75rem;
        }
    }

    @media (max-width: 992px) {
        .booking-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .booking-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .booking-label {
            font-weight: 600;
            color: var(--gray);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .booking-value {
            text-align: right;
        }
    }
</style>

<div class="container-fluid py-3 px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="h4 mb-2 fw-bold text-dark">
                    <i class="fas fa-calendar-check me-2" style="color: var(--primary);"></i>
                    Booking Management
                </h2>
                <p class="text-muted mb-0">Monitor and manage all rental bookings in real-time</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark px-3 py-2 border" style="border-radius: 6px;">
                    <i class="fas fa-sync-alt me-2" style="color: var(--primary);"></i>
                    Live Updates
                </span>
                <span class="text-muted small">
                    <i class="fas fa-clock me-1"></i>
                    {{ now()->format('h:i A') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" 
             style="border-radius: 8px; border: none; border-left: 4px solid var(--success);">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3" style="color: var(--success);"></i>
                <div class="flex-grow-1">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        @php
            $stats = [
                'pending' => [
                    'icon' => 'fas fa-clock',
                    'title' => 'Pending Review',
                    'count' => \App\Models\Booking::where('status', 'pending')->count(),
                    'color' => 'warning'
                ],
                'confirmed' => [
                    'icon' => 'fas fa-check-circle',
                    'title' => 'Confirmed',
                    'count' => \App\Models\Booking::where('status', 'confirmed')->count(),
                    'color' => 'success'
                ],
                'active' => [
                    'icon' => 'fas fa-car',
                    'title' => 'Active Rentals',
                    'count' => \App\Models\Booking::where('status', 'active')->count(),
                    'color' => 'primary'
                ],
                'completed' => [
                    'icon' => 'fas fa-flag-checkered',
                    'title' => 'Completed',
                    'count' => \App\Models\Booking::where('status', 'completed')->count(),
                    'color' => 'info'
                ],
                'cancelled' => [
                    'icon' => 'fas fa-times-circle',
                    'title' => 'Cancelled',
                    'count' => \App\Models\Booking::where('status', 'cancelled')->count(),
                    'color' => 'danger'
                ],
                'total' => [
                    'icon' => 'fas fa-clipboard-list',
                    'title' => 'Total Bookings',
                    'count' => \App\Models\Booking::count(),
                    'color' => 'dark'
                ]
            ];
        @endphp
        
        @foreach($stats as $key => $stat)
            <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stat['count'] }}</div>
                            <div class="stat-label">{{ $stat['title'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Status Filter Tabs -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-2">
            <div class="filter-tabs">
                <ul class="nav nav-tabs border-0">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index') }}">
                            <i class="fas fa-list me-1"></i> All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'pending']) }}">
                            <i class="fas fa-clock me-1"></i> Pending
                            <span class="badge bg-warning nav-badge">{{ $stats['pending']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'confirmed' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'confirmed']) }}">
                            <i class="fas fa-check-circle me-1"></i> Confirmed
                            <span class="badge bg-success nav-badge">{{ $stats['confirmed']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'active']) }}">
                            <i class="fas fa-car me-1"></i> Active
                            <span class="badge bg-primary nav-badge">{{ $stats['active']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'completed']) }}">
                            <i class="fas fa-flag-checkered me-1"></i> Completed
                            <span class="badge bg-info nav-badge">{{ $stats['completed']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'cancelled']) }}">
                            <i class="fas fa-times-circle me-1"></i> Cancelled
                            <span class="badge bg-danger nav-badge">{{ $stats['cancelled']['count'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Time Range Filter for Cancelled -->
    @if(request('status') == 'cancelled')
    <div class="mb-3">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="text-muted small" style="font-weight: 500;">
                <i class="fas fa-filter me-1"></i>Time Range:
            </span>
            <div class="d-flex gap-2">
                <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 3]) }}" 
                   class="time-range-btn {{ request('days') == 3 ? 'active' : '' }}">
                    3 Days
                </a>
                <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 7]) }}" 
                   class="time-range-btn {{ request('days') == 7 || !request('days') ? 'active' : '' }}">
                    7 Days
                </a>
                <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 10]) }}" 
                   class="time-range-btn {{ request('days') == 10 ? 'active' : '' }}">
                    10 Days
                </a>
                <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 30]) }}" 
                   class="time-range-btn {{ request('days') == 30 ? 'active' : '' }}">
                    30 Days
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Bookings Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <!-- Table Header -->
            <div class="booking-row bg-light border-bottom" style="grid-template-columns: 220px 160px 200px 120px 140px 140px;">
                <div class="fw-semibold small text-muted">CUSTOMER & REFERENCE</div>
                <div class="fw-semibold small text-muted">VEHICLE</div>
                <div class="fw-semibold small text-muted">DATES</div>
                <div class="fw-semibold small text-muted">AMOUNT</div>
                <div class="fw-semibold small text-muted">STATUS</div>
                <div class="fw-semibold small text-muted">ACTIONS</div>
            </div>

            @php
                $query = \App\Models\Booking::with(['car', 'user']);
                
                if (request('status')) {
                    if (request('status') == 'cancelled') {
                        $query = \App\Models\Booking::onlyTrashed()->with(['car', 'user']);
                        $query->where('status', 'cancelled');
                        
                        if (request('days')) {
                            $query->where('deleted_at', '>=', now()->subDays((int)request('days')));
                        }
                    } else {
                        $query->where('status', request('status'));
                    }
                } else {
                    $query->where('created_at', '>=', now()->subDays(7));
                }
                
                $bookings = $query->orderBy('created_at', 'desc')->get();
            @endphp

            @forelse($bookings as $booking)
                <div class="booking-card mx-3 mt-3">
                    <div class="booking-row">
                        <!-- Customer & Reference -->
                        <div class="booking-item">
                            <div class="d-none d-lg-block">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="booking-ref">#{{ $booking->booking_reference }}</span>
                                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">ID: {{ $booking->id }}</span>
                                </div>
                                <div>
                                    <div class="fw-semibold mb-1">{{ $booking->user ? $booking->user->name : 'Guest Customer' }}</div>
                                    @if($booking->user)
                                        <div class="text-muted small">{{ $booking->user->email }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-lg-none">
                                <div class="booking-label">Customer</div>
                                <div class="booking-value fw-semibold">{{ $booking->user ? $booking->user->name : 'Guest' }}</div>
                                <div class="booking-label mt-2">Reference</div>
                                <div class="booking-value">#{{ $booking->booking_reference }}</div>
                            </div>
                        </div>

                        <!-- Vehicle -->
                        <div class="booking-item">
                            @if($booking->car)
                                <div class="d-none d-lg-block">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $booking->car->image }}" 
                                             alt="{{ $booking->car->brand }}" 
                                             class="car-image">
                                        <div>
                                            <div class="fw-semibold small">{{ $booking->car->brand }} {{ $booking->car->model }}</div>
                                            <div class="text-muted small">{{ $booking->car->year }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-lg-none">
                                    <div class="booking-label">Vehicle</div>
                                    <div class="booking-value fw-semibold">{{ $booking->car->brand }} {{ $booking->car->model }}</div>
                                    <div class="text-muted small">{{ $booking->car->year }}</div>
                                </div>
                            @else
                                <div class="text-danger small">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Vehicle Not Found
                                </div>
                            @endif
                        </div>

                        <!-- Dates -->
                        <div class="booking-item">
                            <div class="d-none d-lg-block">
                                <div class="mb-2">
                                    <div class="text-muted small mb-1">Pickup</div>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}</div>
                                </div>
                                <div>
                                    <div class="text-muted small mb-1">Return</div>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="d-lg-none">
                                <div class="booking-label">Dates</div>
                                <div class="booking-value">
                                    <div>{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ $booking->duration }} days</div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="booking-item">
                            <div class="d-none d-lg-block">
                                <div class="amount-display">RM{{ number_format($booking->total_price, 2) }}</div>
                                <div class="text-muted small">{{ $booking->duration }} day{{ $booking->duration > 1 ? 's' : '' }}</div>
                            </div>
                            <div class="d-lg-none">
                                <div class="booking-label">Amount</div>
                                <div class="booking-value">
                                    <div class="amount-display">RM{{ number_format($booking->total_price, 2) }}</div>
                                    <div class="text-muted small">{{ $booking->duration }} days</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="booking-item">
                            <span class="status-badge status-{{ $booking->status }}">
                                <i class="fas fa-circle fa-xs"></i>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="booking-item">
                            <div class="d-flex gap-2">
                                <a href="{{ route('staff.bookings.show', $booking->id) }}" 
                                   class="btn-view action-btn">
                                    <i class="fas fa-eye"></i>
                                    <span class="d-none d-lg-inline">View</span>
                                </a>
                                
                                @if($booking->status === 'pending')
                                    <form action="{{ route('staff.bookings.approve', $booking->id) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-approve action-btn" 
                                                onclick="return confirm('Approve this booking?')">
                                            <i class="fas fa-check"></i>
                                            <span class="d-none d-lg-inline">Approve</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state mx-3 my-4">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h5 class="text-muted mb-3">No Bookings Found</h5>
                    <p class="text-muted mb-4">
                        @if(request('status') == 'cancelled')
                            There are no cancelled bookings in the selected time range.
                        @elseif(request('status'))
                            There are no {{ request('status') }} bookings at the moment.
                        @else
                            There are no bookings from the last 7 days.
                        @endif
                    </p>
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-redo me-2"></i> Refresh
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Updated Info -->
    <div class="text-center mt-4 mb-2">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Last updated {{ now()->format('g:i A') }} • 
            Showing {{ $bookings->count() }} booking{{ $bookings->count() !== 1 ? 's' : '' }}
        </small>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh page every 60 seconds
        setTimeout(function() {
            window.location.reload();
        }, 60000);
    });
</script>
@endsection