@extends('layouts.staff')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    :root {
        --primary: #dc2626;
        --primary-dark: #b91c1c;
        --primary-light: rgba(220, 38, 38, 0.1);
        --success: #22c55e;
        --success-dark: #16a34a;
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

    /* Welcome Header */
    .welcome-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 40px rgba(220, 38, 38, 0.2);
        position: relative;
        overflow: hidden;
    }

    .welcome-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    .quick-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
    }

    .quick-stat-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .quick-stat-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .quick-stat-info h4 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .quick-stat-info p {
        font-size: 0.875rem;
        margin: 0;
        opacity: 0.9;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid transparent;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--warning));
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.25rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        position: relative;
    }

    .stat-icon::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 16px;
        background: inherit;
        opacity: 0.2;
        z-index: -1;
    }

    .stat-trend {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .trend-up {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success);
    }

    .trend-down {
        background: rgba(220, 38, 38, 0.1);
        color: var(--primary);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
        margin-bottom: 0.75rem;
    }

    .stat-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
        font-size: 0.8125rem;
        color: var(--gray);
    }

    .stat-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: gap 0.2s;
    }

    .stat-link:hover {
        gap: 0.5rem;
    }

    /* Chart Cards - Enhanced */
    .chart-card {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        border: 1px solid var(--border);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .chart-filters {
        display: flex;
        gap: 0.5rem;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        background: var(--light);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .revenue-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: var(--light);
        border-radius: 12px;
    }

    .summary-item {
        text-align: center;
    }

    .summary-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.8125rem;
        color: var(--gray);
        font-weight: 500;
    }

    .summary-change {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    /* Donut Chart Card */
    .donut-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
    }

    .donut-card::before {
        display: none;
    }

    .donut-stats {
        display: grid;
        gap: 1.25rem;
        margin-top: 1.5rem;
    }

    .donut-stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .donut-stat-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9375rem;
        font-weight: 500;
    }

    .donut-color-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .donut-stat-value {
        font-size: 1.25rem;
        font-weight: 700;
    }

    /* Vehicle Table - Modern */
    .vehicle-table {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
    }

    .table-header {
        padding: 2rem;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(135deg, var(--light) 0%, var(--white) 100%);
    }

    .table-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 0.5rem 0;
    }

    .table-subtitle {
        font-size: 0.875rem;
        color: var(--gray);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--light);
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .data-table tbody td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.9375rem;
        color: var(--dark);
    }

    .data-table tbody tr {
        transition: all 0.2s;
    }

    .data-table tbody tr:hover {
        background: var(--light);
        transform: scale(1.01);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .vehicle-name {
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .vehicle-icon {
        width: 40px;
        height: 40px;
        background: var(--light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-available {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success-dark);
    }

    .status-unavailable {
        background: rgba(220, 38, 38, 0.1);
        color: var(--primary-dark);
    }

    /* Activity Feed */
    .activity-card {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.125rem;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.8125rem;
        color: var(--gray);
    }

    /* Quick Actions */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-card {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid var(--border);
        text-decoration: none;
    }

    .action-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary);
        box-shadow: 0 8px 24px rgba(220, 38, 38, 0.15);
    }

    .action-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .action-title {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .action-subtitle {
        font-size: 0.8125rem;
        color: var(--gray);
    }

    @media (max-width: 768px) {
        .welcome-header {
            padding: 1.5rem;
        }

        .quick-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .revenue-summary {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid py-4">
    
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
    <!-- Quick Actions -->
    <div class="quick-actions-grid">
        <a href="{{ route('staff.cars.create') }}" class="action-card">
            <div class="action-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                <i class="fas fa-plus"></i>
            </div>
            <div class="action-title">Add Vehicle</div>
            <div class="action-subtitle">Add new car or motorcycle</div>
        </a>

        <a href="{{ route('staff.bookings.index') }}" class="action-card">
            <div class="action-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="action-title">View Bookings</div>
            <div class="action-subtitle">Manage all reservations</div>
        </a>

                <a href="{{ route('staff.cars.index') }}" class="action-card">
            <div class="action-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fas fa-car"></i>
            </div>
            <div class="action-title">Fleet Status</div>
            <div class="action-subtitle">Check vehicle availability</div>
        </a>

        <div class="action-card" style="opacity: 0.7; cursor: not-allowed;">
            <div class="action-icon" style="background: rgba(139, 92, 246, 0.1); color: var(--purple);">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="action-title">Reports</div>
            <div class="action-subtitle">Coming soon</div>
        </div>
    </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        @php
            $currentMonth = now();
            $lastMonth = now()->subMonth();
            
            $currentRevenue = \App\Models\Booking::where('payment_status', 'paid')
                ->whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentMonth->year)
                ->sum('total_price');
            
            $lastMonthRevenue = \App\Models\Booking::where('payment_status', 'paid')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->sum('total_price');
            
            $revenueChange = $lastMonthRevenue > 0 
                ? (($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100) 
                : 0;

            $totalBookings = \App\Models\Booking::whereMonth('created_at', $currentMonth->month)->count();
            $activeRentals = \App\Models\Booking::where('status', 'active')->count();
            $totalVehicles = \App\Models\Car::count();
            $availableVehicles = \App\Models\Car::where('is_available', 1)->count();
        @endphp

        <!-- Revenue Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #22c55e, #16a34a); color: white;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <span class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    {{ number_format(abs($revenueChange), 1) }}%
                </span>
            </div>
            <div class="stat-value">RM {{ number_format($currentRevenue, 2) }}</div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-footer">
                <span>vs last month</span>
                <a href="javascript:void(0)" class="stat-link" onclick="alert('Reports feature coming soon!')">
                    View details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <span class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    12.5%
                </span>
            </div>
            <div class="stat-value">{{ $totalBookings }}</div>
            <div class="stat-label">Total Bookings</div>
            <div class="stat-footer">
                <span>This month</span>
                <a href="{{ route('staff.bookings.index') }}" class="stat-link">
                    Manage <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Active Rentals Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                    <i class="fas fa-key"></i>
                </div>
                <span class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    8%
                </span>
            </div>
            <div class="stat-value">{{ $activeRentals }}</div>
            <div class="stat-label">Active Rentals</div>
            <div class="stat-footer">
                <span>Currently rented</span>
                <a href="{{ route('staff.bookings.index') }}?status=active" class="stat-link">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Available Vehicles Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white;">
                    <i class="fas fa-car"></i>
                </div>
                <span class="stat-trend trend-up">
                    <i class="fas fa-check"></i>
                    Ready
                </span>
            </div>
            <div class="stat-value">{{ $availableVehicles }}/{{ $totalVehicles }}</div>
            <div class="stat-label">Available Vehicles</div>
            <div class="stat-footer">
                <span>Fleet status</span>
                <a href="{{ route('staff.cars.index') }}" class="stat-link">
                    Manage fleet <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Revenue Overview</h3>
                        <p class="text-muted mb-0" style="font-size: 0.875rem;">Monthly performance tracking</p>
                    </div>
                    <div class="chart-filters">
                        <button class="filter-btn active">6M</button>
                        <button class="filter-btn">1Y</button>
                        <button class="filter-btn">All</button>
                    </div>
                </div>

                <div class="revenue-summary">
                    @php
                        $avgMonthlyRevenue = \App\Models\Booking::where('payment_status', 'paid')
                            ->whereYear('created_at', now()->year)
                            ->selectRaw('AVG(total_price) as avg')
                            ->value('avg') ?? 0;
                        
                        $todayRevenue = \App\Models\Booking::where('payment_status', 'paid')
                            ->whereDate('created_at', today())
                            ->sum('total_price');
                    @endphp

                    <div class="summary-item">
                        <div class="summary-value">RM {{ number_format($currentRevenue, 0) }}</div>
                        <div class="summary-label">This Month</div>
                        <div class="summary-change" style="color: var(--success);">
                            <i class="fas fa-arrow-up"></i> {{ number_format(abs($revenueChange), 1) }}%
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-value">RM {{ number_format($avgMonthlyRevenue, 0) }}</div>
                        <div class="summary-label">Average/Month</div>
                        <div class="summary-change" style="color: var(--info);">
                            <i class="fas fa-chart-line"></i> Stable
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-value">RM {{ number_format($todayRevenue, 0) }}</div>
                        <div class="summary-label">Today</div>
                        <div class="summary-change" style="color: var(--warning);">
                            <i class="fas fa-clock"></i> Live
                        </div>
                    </div>
                </div>

                <div style="height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistics Donut -->
        <div class="col-lg-4">
            <div class="chart-card donut-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Booking Statistics</h3>
                        <p class="mb-0" style="font-size: 0.875rem; opacity: 0.9;">All-time distribution</p>
                    </div>
                </div>

                @php
                    $totalBooked = \App\Models\Booking::whereIn('status', ['confirmed', 'active', 'completed'])->count();
                    $totalCancelled = \App\Models\Booking::where('status', 'cancelled')->count();
                    $totalPending = \App\Models\Booking::where('status', 'pending')->count();
                    $grandTotal = $totalBooked + $totalCancelled + $totalPending;
                    
                    $bookedPercent = $grandTotal > 0 ? round(($totalBooked / $grandTotal) * 100) : 0;
                    $cancelledPercent = $grandTotal > 0 ? round(($totalCancelled / $grandTotal) * 100) : 0;
                    $pendingPercent = $grandTotal > 0 ? round(($totalPending / $grandTotal) * 100) : 0;
                @endphp

                <div style="height: 200px; display: flex; align-items: center; justify-content: center; margin: 1rem 0;">
                    <canvas id="bookingChart"></canvas>
                </div>

                <div class="donut-stats">
                    <div class="donut-stat-item">
                        <div class="donut-stat-label">
                            <span class="donut-color-dot" style="background: #22c55e;"></span>
                            Confirmed
                        </div>
                        <div class="donut-stat-value">{{ $bookedPercent }}%</div>
                    </div>

                    <div class="donut-stat-item">
                        <div class="donut-stat-label">
                            <span class="donut-color-dot" style="background: #f59e0b;"></span>
                            Pending
                        </div>
                        <div class="donut-stat-value">{{ $pendingPercent }}%</div>
                    </div>

                    <div class="donut-stat-item">
                        <div class="donut-stat-label">
                            <span class="donut-color-dot" style="background: #ef4444;"></span>
                            Cancelled
                        </div>
                        <div class="donut-stat-value">{{ $cancelledPercent }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="row g-4 mt-2">
        <!-- Recent Vehicles -->
        <div class="col-lg-8">
            <div class="vehicle-table">
                <div class="table-header">
                    <div>
                        <h3 class="table-title">Vehicle Fleet</h3>
                        <p class="table-subtitle">Recent additions and status updates</p>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>VEHICLE</th>
                            <th>LICENSE PLATE</th>
                            <th>STATUS</th>
                            <th>DAILY RATE</th>
                            <th>ADDED</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentVehicles = \App\Models\Car::orderBy('created_at', 'desc')->take(5)->get();
                        @endphp
                        @forelse($recentVehicles as $vehicle)
                        <tr>
                            <td>
                                <div class="vehicle-name">
                                    <div class="vehicle-icon">
                                        <i class="fas fa-{{ $vehicle->category == 'motorcycle' ? 'motorcycle' : 'car' }}"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                                        <div style="font-size: 0.8125rem; color: var(--gray);">{{ $vehicle->year }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-weight: 600;">{{ $vehicle->license_plate ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $vehicle->is_available ? 'status-available' : 'status-unavailable' }}">
                                    {{ $vehicle->is_available ? 'Available' : 'Rented' }}
                                </span>
                            </td>
                            <td>
                                <strong>RM {{ number_format($vehicle->daily_rate, 0) }}</strong>/day
                            </td>
                            <td>
                                <span style="color: var(--gray);">{{ $vehicle->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: var(--gray);">
                                No vehicles added yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4">
            <div class="activity-card">
                <div class="chart-header" style="margin-bottom: 1.5rem;">
                    <div>
                        <h3 class="chart-title">Recent Activity</h3>
                        <p class="text-muted mb-0" style="font-size: 0.875rem;">Latest system updates</p>
                    </div>
                </div>

                @php
                    $recentBookings = \App\Models\Booking::with('user', 'car')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @forelse($recentBookings as $booking)
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New Booking #{{ $booking->booking_reference }}</div>
                        <div class="activity-time">
                            {{ $booking->user->name ?? 'Guest' }} • {{ $booking->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: var(--gray);">
                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>No recent activity</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @php
        // Get last 6 months revenue data
        $monthlyRevenue = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M');
            $revenue = \App\Models\Booking::where('payment_status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');
            $monthlyRevenue[] = $revenue;
        }
    @endphp

    // Revenue Chart - Enhanced
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const gradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(220, 38, 38, 0.2)');
    gradient.addColorStop(1, 'rgba(220, 38, 38, 0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($monthlyRevenue) !!},
                borderColor: '#dc2626',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#dc2626',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderColor: '#334155',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: RM ' + context.parsed.y.toLocaleString('en-MY', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return 'RM' + (value / 1000).toFixed(0) + 'k';
                        },
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                }
            }
        }
    });

    // Booking Statistics Chart
    const bookingCtx = document.getElementById('bookingChart').getContext('2d');
    new Chart(bookingCtx, {
        type: 'doughnut',
        data: {
            labels: ['Confirmed', 'Pending', 'Cancelled'],
            datasets: [{
                data: [{{ $bookedPercent }}, {{ $pendingPercent }}, {{ $cancelledPercent }}],
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderColor: '#334155',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            }
        }
    });
});
</script>
@endpush
@endsection