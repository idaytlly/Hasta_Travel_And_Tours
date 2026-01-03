@extends('layouts.staff')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --primary-light: rgba(220, 38, 38, 0.1);
        --success: #059669;
        --warning: #d97706;
        --info: #2563eb;
        --purple: #6366f1;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
    }

    .welcome-text {
        font-size: 0.875rem;
        color: var(--gray);
    }

    .date-badge {
        background: var(--light);
        border: 1px solid var(--border);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-light);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--primary);
        background: var(--primary-light);
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        background: var(--light);
    }

    .stat-change.positive {
        color: var(--success);
        background: rgba(5, 150, 105, 0.1);
    }

    .stat-change.negative {
        color: var(--primary);
        background: var(--primary-light);
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-btn {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.2s ease;
        text-decoration: none;
        color: var(--dark);
    }

    .action-btn:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--primary);
        background: var(--primary-light);
        margin: 0 auto 0.75rem;
    }

    .action-text {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .action-count {
        font-size: 0.75rem;
        color: var(--primary);
        font-weight: 600;
    }

    /* Content Cards */
    .content-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Chart Styles */
    .chart-container {
        height: 260px;
        position: relative;
    }

    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
    }

    .legend-color {
        width: 10px;
        height: 10px;
        border-radius: 2px;
    }

    .legend-label {
        color: var(--gray);
        font-weight: 500;
    }

    .legend-value {
        color: var(--dark);
        font-weight: 600;
    }

    /* Table Styles */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .data-table th {
        background: var(--light);
        color: var(--gray);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border);
        text-align: left;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
        color: var(--dark);
    }

    .data-table tbody tr:hover {
        background: var(--light);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-confirmed {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .status-pending {
        background: rgba(217, 119, 6, 0.1);
        color: var(--warning);
    }

    .status-active {
        background: rgba(37, 99, 235, 0.1);
        color: var(--info);
    }

    .status-completed {
        background: rgba(99, 102, 241, 0.1);
        color: var(--purple);
    }

    .status-cancelled {
        background: rgba(220, 38, 38, 0.1);
        color: var(--primary);
    }

    /* View Button */
    .view-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--border);
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--gray);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .view-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--gray);
    }

    .empty-state-icon {
        font-size: 2rem;
        color: var(--border);
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 0.875rem;
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Dashboard</h1>
            <p class="welcome-text mb-0">Welcome back, {{ Auth::user()->name }}. Here's your daily overview.</p>
        </div>
        <div class="date-badge">
            <i class="fas fa-calendar-alt me-2"></i>
            {{ now()->format('F d, Y') }}
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        @php
            $totalRevenue = \App\Models\Booking::where('payment_status', 'paid')->sum('total_price');
            $todayBookings = \App\Models\Booking::whereDate('created_at', today())->count();
            $totalBookings = \App\Models\Booking::count();
            $activeBookings = \App\Models\Booking::where('status', 'active')->count();
            
            $lastMonthRevenue = \App\Models\Booking::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->sum('total_price');
            $revenueChange = $lastMonthRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100) : 0;
        @endphp

        <!-- Revenue Card -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">RM {{ number_format($totalRevenue, 2) }}</div>
            <div class="stat-label">Total Revenue</div>
            <span class="stat-change {{ $revenueChange >= 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }} fa-xs"></i>
                {{ number_format(abs($revenueChange), 1) }}% from last month
            </span>
        </div>

        <!-- Today's Bookings -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-value">{{ $todayBookings }}</div>
            <div class="stat-label">Today's Bookings</div>
            <span class="stat-change positive">
                <i class="fas fa-calendar fa-xs"></i>
                Updated today
            </span>
        </div>

        <!-- Total Bookings -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-value">{{ $totalBookings }}</div>
            <div class="stat-label">Total Bookings</div>
            <span class="stat-change positive">
                <i class="fas fa-chart-line fa-xs"></i>
                All time
            </span>
        </div>

        <!-- Active Rentals -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-car"></i>
            </div>
            <div class="stat-value">{{ $activeBookings }}</div>
            <div class="stat-label">Active Rentals</div>
            <span class="stat-change {{ $activeBookings > 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-clock fa-xs"></i>
                Currently active
            </span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('staff.bookings.index', ['status' => 'pending']) }}" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="action-text">Pending Bookings</div>
            <div class="action-count">
                {{ \App\Models\Booking::where('status', 'pending')->count() }} pending
            </div>
        </a>
        <a href="{{ route('staff.cars.index') }}" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-car"></i>
            </div>
            <div class="action-text">Manage Vehicles</div>
            <div class="action-count">
                {{ \App\Models\Car::count() }} vehicles
            </div>
        </a>
        <a href="{{ route('staff.notifications.index') }}" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="action-text">Notifications</div>
            <div class="action-count">
                5 unread
            </div>
        </a>
    </div>

    <div class="row g-4">
        <!-- Booking Status Chart -->
        <div class="col-lg-6">
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">Booking Status Distribution</h2>
                </div>
                <div class="card-body">
                    @php
                        $confirmed = \App\Models\Booking::where('status', 'confirmed')->count();
                        $pending = \App\Models\Booking::where('status', 'pending')->count();
                        $active = \App\Models\Booking::where('status', 'active')->count();
                        $completed = \App\Models\Booking::where('status', 'completed')->count();
                        $cancelled = \App\Models\Booking::where('status', 'cancelled')->count();
                        $total = $totalBookings > 0 ? $totalBookings : 1;
                    @endphp
                    
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                    
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #059669;"></span>
                            <span class="legend-label">Confirmed:</span>
                            <span class="legend-value">{{ $confirmed }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #d97706;"></span>
                            <span class="legend-label">Pending:</span>
                            <span class="legend-value">{{ $pending }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #2563eb;"></span>
                            <span class="legend-label">Active:</span>
                            <span class="legend-value">{{ $active }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #6366f1;"></span>
                            <span class="legend-label">Completed:</span>
                            <span class="legend-value">{{ $completed }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #dc2626;"></span>
                            <span class="legend-label">Cancelled:</span>
                            <span class="legend-value">{{ $cancelled }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-6">
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">Recent Bookings</h2>
                    <a href="{{ route('staff.bookings.index') }}" class="view-btn">
                        View All
                        <i class="fas fa-arrow-right fa-xs"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $recentBookings = \App\Models\Booking::with(['car', 'user'])
                            ->orderBy('created_at', 'desc')
                            ->take(6)
                            ->get();
                    @endphp

                    @if($recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold" style="font-size: 0.75rem; color: var(--gray);">#{{ $booking->booking_reference }}</div>
                                                <div style="font-size: 0.75rem; color: var(--dark);">
                                                    RM {{ number_format($booking->total_price, 2) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold" style="font-size: 0.875rem;">{{ $booking->user->name ?? 'Guest' }}</div>
                                                <div style="font-size: 0.75rem; color: var(--gray);">
                                                    {{ $booking->car->brand ?? 'N/A' }} {{ $booking->car->model ?? '' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge status-{{ $booking->status }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('staff.bookings.show', $booking->id) }}" class="view-btn">
                                                    <i class="fas fa-eye fa-xs"></i>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="empty-state-text">No bookings yet</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Pending', 'Active', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [{{ $confirmed }}, {{ $pending }}, {{ $active }}, {{ $completed }}, {{ $cancelled }}],
                    backgroundColor: [
                        '#059669',
                        '#d97706',
                        '#2563eb',
                        '#6366f1',
                        '#dc2626'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleColor: '#f8fafc',
                        bodyColor: '#f8fafc',
                        borderColor: '#334155',
                        borderWidth: 1,
                        cornerRadius: 4,
                        callbacks: {
                            label: function(context) {
                                const total = {{ $total }};
                                const value = context.parsed;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection