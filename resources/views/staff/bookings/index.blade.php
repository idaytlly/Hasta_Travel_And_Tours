@extends('layouts.staff')

@section('page-title')
    <i class="fas fa-calendar-check me-2"></i>Booking Management
@endsection

@section('content')
<style>
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .booking-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.2s ease;
        background: white;
    }
    
    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-color: #dee2e6;
    }
    
    .car-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .filter-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        position: relative;
        transition: all 0.2s;
    }
    
    .filter-tabs .nav-link.active {
        color: #667eea;
        background: transparent;
    }
    
    .filter-tabs .nav-link.active:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px 3px 0 0;
    }
    
    .action-btn {
        padding: 8px 16px;
        font-size: 0.875rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .booking-ref {
        font-family: 'SF Mono', Monaco, Consolas, monospace;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.875rem;
        color: #495057;
    }
    
    .amount-display {
        font-weight: 700;
        color: #2ecc71;
        font-size: 1.25rem;
    }
    
    .time-range-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .time-range-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: #f8f9fa;
        border-radius: 12px;
        border: 2px dashed #dee2e6;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #adb5bd;
        margin-bottom: 1.5rem;
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="h4 mb-1" style="color: #333; font-weight: 600;">
                        <i class="fas fa-calendar-check me-2" style="color: #667eea;"></i>
                        Booking Management
                    </h2>
                    <p class="text-muted mb-0">Monitor and manage all rental bookings</p>
                </div>
                <div class="text-end">
                    <div class="badge bg-light text-dark px-3 py-2 rounded-pill">
                        <i class="fas fa-sync-alt me-2" style="color: #667eea;"></i>
                        Real-time Updates
                    </div>
                </div>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $stats = [
                'pending' => [
                    'icon' => 'fas fa-clock',
                    'color' => '#ffc107',
                    'title' => 'Pending Review',
                    'count' => \App\Models\Booking::where('status', 'pending')->count(),
                    'bg' => 'warning'
                ],
                'confirmed' => [
                    'icon' => 'fas fa-check-circle',
                    'color' => '#28a745',
                    'title' => 'Confirmed',
                    'count' => \App\Models\Booking::where('status', 'confirmed')->count(),
                    'bg' => 'success'
                ],
                'active' => [
                    'icon' => 'fas fa-car',
                    'color' => '#007bff',
                    'title' => 'Active Rentals',
                    'count' => \App\Models\Booking::where('status', 'active')->count(),
                    'bg' => 'primary'
                ],
                'completed' => [
                    'icon' => 'fas fa-flag-checkered',
                    'color' => '#17a2b8',
                    'title' => 'Completed',
                    'count' => \App\Models\Booking::where('status', 'completed')->count(),
                    'bg' => 'info'
                ],
                'cancelled' => [
                    'icon' => 'fas fa-times-circle',
                    'color' => '#dc3545',
                    'title' => 'Cancelled',
                    'count' => \App\Models\Booking::where('status', 'cancelled')->count(),
                    'bg' => 'danger'
                ]
            ];
        @endphp
        
        @foreach($stats as $key => $stat)
            <div class="col-md-2 col-sm-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 class="mb-0" style="font-weight: 700;">{{ $stat['count'] }}</h3>
                            <p class="mb-0 opacity-75" style="font-size: 0.875rem;">{{ $stat['title'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info Alert -->
    <div class="alert alert-light border mb-4" style="border-radius: 10px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-3" style="color: #667eea; font-size: 1.25rem;"></i>
            <div class="flex-grow-1">
                @if(request('status') == 'cancelled')
                    <strong>Showing:</strong> Cancelled bookings from the selected time range
                @elseif(!request('status'))
                    <strong>Showing:</strong> All bookings from the last 7 days
                @else
                    <strong>Showing:</strong> All {{ ucfirst(request('status')) }} bookings
                @endif
            </div>
            <div class="text-muted" style="font-size: 0.875rem;">
                <i class="fas fa-clock me-1"></i>
                Updated just now
            </div>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="filter-tabs">
                <ul class="nav nav-tabs border-0">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index') }}">
                            <i class="fas fa-list me-1"></i> All Bookings
                            <span class="badge bg-secondary ms-2">{{ \App\Models\Booking::count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'pending']) }}">
                            <i class="fas fa-clock me-1"></i> Pending
                            <span class="badge bg-warning ms-2">{{ $stats['pending']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'confirmed' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'confirmed']) }}">
                            <i class="fas fa-check-circle me-1"></i> Confirmed
                            <span class="badge bg-success ms-2">{{ $stats['confirmed']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'active']) }}">
                            <i class="fas fa-car me-1"></i> Active
                            <span class="badge bg-primary ms-2">{{ $stats['active']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'completed']) }}">
                            <i class="fas fa-flag-checkered me-1"></i> Completed
                            <span class="badge bg-info ms-2">{{ $stats['completed']['count'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" 
                           href="{{ route('staff.bookings.index', ['status' => 'cancelled']) }}">
                            <i class="fas fa-times-circle me-1"></i> Cancelled
                            <span class="badge bg-danger ms-2">{{ $stats['cancelled']['count'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Time Range Filter (Only for Cancelled Tab) -->
    @if(request('status') == 'cancelled')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center flex-wrap">
                <label class="mb-0 me-3" style="font-weight: 500;">
                    <i class="fas fa-calendar-alt me-2"></i>Time Range:
                </label>
                <div class="d-flex gap-2">
                    <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 3]) }}" 
                       class="time-range-btn {{ request('days') == 3 ? 'active' : 'btn-outline-secondary' }}">
                        3 Days
                    </a>
                    <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 7]) }}" 
                       class="time-range-btn {{ request('days') == 7 || !request('days') ? 'active' : 'btn-outline-secondary' }}">
                        7 Days
                    </a>
                    <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 10]) }}" 
                       class="time-range-btn {{ request('days') == 10 ? 'active' : 'btn-outline-secondary' }}">
                        10 Days
                    </a>
                    <a href="{{ route('staff.bookings.index', ['status' => 'cancelled', 'days' => 30]) }}" 
                       class="time-range-btn {{ request('days') == 30 ? 'active' : 'btn-outline-secondary' }}">
                        30 Days
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bookings List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @php
                $query = \App\Models\Booking::with(['car', 'user']);
                
                if (request('status')) {
                    if (request('status') == 'cancelled') {
                        $query = \App\Models\Booking::onlyTrashed()->with(['car', 'user']);
                        $query->where('status', 'cancelled');
                        
                        $days = request('days', 'all');
                        if ($days !== 'all') {
                            $query->where('deleted_at', '>=', now()->subDays((int)$days));
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
                <div class="booking-card p-4 mb-3 mx-4 mt-4 {{ $loop->first ? '' : 'border-top' }}">
                    <div class="row align-items-center">
                        <!-- Booking Reference & Customer -->
                        <div class="col-lg-3 mb-3 mb-lg-0">
                            <div class="mb-2">
                                <span class="booking-ref">#{{ $booking->booking_reference }}</span>
                                <span class="badge bg-light text-dark ms-2">
                                    <i class="fas fa-hashtag me-1"></i>{{ $booking->id }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1" style="font-weight: 600;">
                                    <i class="fas fa-user me-2 text-muted"></i>
                                    {{ $booking->user ? $booking->user->name : 'Guest Customer' }}
                                </h6>
                                @if($booking->user)
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-envelope me-1"></i>{{ $booking->user->email }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="col-lg-2 mb-3 mb-lg-0">
                            @if($booking->car)
                                <div class="d-flex align-items-center">
                                    <img src="{{ $booking->car->image }}" 
                                         alt="{{ $booking->car->brand }}" 
                                         class="car-image me-3">
                                    <div>
                                        <h6 class="mb-0" style="font-weight: 600; font-size: 0.95rem;">
                                            {{ $booking->car->brand }} {{ $booking->car->model }}
                                        </h6>
                                        <p class="small text-muted mb-0">{{ $booking->car->year }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-danger small">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Vehicle Not Found
                                </div>
                            @endif
                        </div>

                        <!-- Dates -->
                        <div class="col-lg-2 mb-3 mb-lg-0">
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">
                                    <i class="fas fa-calendar-plus me-1"></i>Pickup
                                </small>
                                <div style="font-weight: 500;">
                                    {{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }}
                                </div>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">
                                    <i class="fas fa-calendar-minus me-1"></i>Return
                                </small>
                                <div style="font-weight: 500;">
                                    {{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-lg-2 mb-3 mb-lg-0">
                            <div class="amount-display">
                                RM{{ number_format($booking->total_price, 2) }}
                            </div>
                            <small class="text-muted">
                                {{ $booking->duration }} day{{ $booking->duration > 1 ? 's' : '' }}
                            </small>
                        </div>

                        <!-- Status & Actions -->
                        <div class="col-lg-3">
                            <div class="d-flex flex-column gap-2">
                                <!-- Status Badge -->
                                @php
                                    $statusColors = [
                                        'pending' => ['bg' => 'bg-warning', 'text' => 'Pending Review'],
                                        'confirmed' => ['bg' => 'bg-success', 'text' => 'Confirmed'],
                                        'active' => ['bg' => 'bg-primary', 'text' => 'Active'],
                                        'completed' => ['bg' => 'bg-info', 'text' => 'Completed'],
                                        'cancelled' => ['bg' => 'bg-danger', 'text' => 'Cancelled']
                                    ];
                                    $status = $statusColors[$booking->status] ?? ['bg' => 'bg-secondary', 'text' => $booking->status];
                                @endphp
                                
                                <div class="status-badge {{ $status['bg'] }} text-white mb-2">
                                    {{ $status['text'] }}
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.bookings.show', $booking->id) }}" 
                                       class="btn btn-outline-primary action-btn flex-grow-1">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('staff.bookings.confirm', $booking->id) }}" 
                                              method="POST" class="flex-grow-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success action-btn w-100" 
                                                    onclick="return confirm('Approve this booking?')">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state mx-4 my-5">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Bookings Found</h4>
                    <p class="text-muted mb-4">
                        @if(request('status') == 'cancelled')
                            There are no cancelled bookings in the selected time range.
                        @elseif(request('status'))
                            There are no {{ request('status') }} bookings at the moment.
                        @else
                            There are no bookings from the last 7 days.
                        @endif
                    </p>
                    <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination (if using paginate instead of get) -->
        @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
            <div class="card-footer border-top-0">
                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Last Updated -->
    <div class="text-center mt-4">
        <small class="text-muted">
            <i class="fas fa-clock me-1"></i>
            Data updated {{ now()->format('M d, Y g:i A') }}
        </small>
    </div>
</div>

<script>
    // Add smooth hover effects
    document.addEventListener('DOMContentLoaded', function() {
        const bookingCards = document.querySelectorAll('.booking-card');
        bookingCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection