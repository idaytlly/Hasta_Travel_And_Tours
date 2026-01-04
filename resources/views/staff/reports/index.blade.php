@extends('layouts.staff')

@section('title', 'Reports')
@section('page-title', 'Reports Dashboard')

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

    .reports-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }

    .report-card {
        background: var(--white);
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }

    .report-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .report-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .stat-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        background: var(--light);
        color: var(--dark);
    }

    .stat-badge.success {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success-dark);
    }

    .stat-badge.warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .stat-badge.danger {
        background: rgba(220, 38, 38, 0.1);
        color: var(--primary-dark);
    }

    .stat-badge.info {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
    }

    .chart-container {
        height: 300px;
        margin-top: 1.5rem;
    }

    .filter-section {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border);
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 0.625rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--white);
        color: var(--dark);
        font-size: 0.875rem;
        min-width: 200px;
    }

    .filter-btn {
        padding: 0.625rem 1.5rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }

    .filter-btn:hover {
        background: var(--primary-dark);
    }

    .date-inputs {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .date-input {
        padding: 0.625rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: var(--light);
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
        color: var(--dark);
    }

    .data-table tbody tr:hover {
        background: var(--light);
    }

    .export-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .export-btn {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        border-radius: 6px;
        background: var(--white);
        color: var(--dark);
        font-size: 0.8125rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .export-btn:hover {
        background: var(--light);
        border-color: var(--primary);
        color: var(--primary);
    }

    .no-data {
        text-align: center;
        padding: 3rem;
        color: var(--gray);
        font-size: 0.875rem;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .metric-card {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border);
    }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .metric-label {
        font-size: 0.8125rem;
        color: var(--gray);
    }

    .metric-change {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    .metric-change.up {
        color: var(--success);
    }

    .metric-change.down {
        color: var(--primary);
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="reports-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2" style="font-size: 1.75rem; font-weight: 700;">Reports & Analytics</h1>
                <p style="opacity: 0.9;">Comprehensive insights into your rental business performance</p>
            </div>
            <div class="text-end">
                <div style="font-size: 0.875rem; opacity: 0.9;">{{ now()->format('F d, Y') }}</div>
                <div style="font-size: 1rem; font-weight: 600;">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" action="{{ route('staff.reports.index') }}" id="reportForm">
            <div class="filter-group">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--gray); margin-bottom: 0.5rem;">Report Type</label>
                    <select name="report_type" class="filter-select" onchange="updateForm()">
                        <option value="revenue" {{ $reportType == 'revenue' ? 'selected' : '' }}>Revenue Report</option>
                        <option value="bookings" {{ $reportType == 'bookings' ? 'selected' : '' }}>Bookings Report</option>
                        <option value="vehicles" {{ $reportType == 'vehicles' ? 'selected' : '' }}>Vehicles Report</option>
                        <option value="customers" {{ $reportType == 'customers' ? 'selected' : '' }}>Customers Report</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--gray); margin-bottom: 0.5rem;">Date Range</label>
                    <select name="date_range" class="filter-select" onchange="toggleCustomDates()">
                        <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $dateRange == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="last_7_days" {{ $dateRange == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="this_month" {{ $dateRange == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $dateRange == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="last_30_days" {{ $dateRange == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="this_year" {{ $dateRange == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>

                <div id="customDates" style="display: {{ $dateRange == 'custom' ? 'flex' : 'none' }}; gap: 0.5rem; align-items: flex-end;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; color: var(--gray); margin-bottom: 0.5rem;">Start Date</label>
                        <input type="date" name="start_date" class="date-input" value="{{ $dateRange == 'custom' ? $startDate->format('Y-m-d') : now()->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; color: var(--gray); margin-bottom: 0.5rem;">End Date</label>
                        <input type="date" name="end_date" class="date-input" value="{{ $dateRange == 'custom' ? $endDate->format('Y-m-d') : now()->format('Y-m-d') }}">
                    </div>
                </div>

                <div style="align-self: flex-end;">
                    <button type="submit" class="filter-btn">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>

        <div class="export-buttons">
            <button class="export-btn" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf"></i> Export as PDF
            </button>
            <button class="export-btn" onclick="exportReport('excel')">
                <i class="fas fa-file-excel"></i> Export as Excel
            </button>
            <button class="export-btn" onclick="exportReport('csv')">
                <i class="fas fa-file-csv"></i> Export as CSV
            </button>
        </div>
    </div>

    <!-- Report Content -->
    @if($reportType == 'revenue')
        <!-- Revenue Report -->
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title">Revenue Overview</h3>
                <span class="stat-badge success">Total: RM {{ number_format($reportData['total_revenue'] ?? 0, 2) }}</span>
            </div>

            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-value">RM {{ number_format($reportData['total_revenue'] ?? 0, 2) }}</div>
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-change up">
                        <i class="fas fa-arrow-up"></i> 12.5% from last period
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['booking_count'] ?? 0 }}</div>
                    <div class="metric-label">Total Bookings</div>
                    <div class="metric-change up">
                        <i class="fas fa-arrow-up"></i> 8.2% from last period
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['paid_count'] ?? 0 }}</div>
                    <div class="metric-label">Paid Bookings</div>
                    <div class="metric-change up">
                        <i class="fas fa-arrow-up"></i> 15.3% from last period
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">RM {{ number_format(($reportData['total_revenue'] ?? 0) / max(1, ($reportData['booking_count'] ?? 1)), 2) }}</div>
                    <div class="metric-label">Average Booking Value</div>
                    <div class="metric-change up">
                        <i class="fas fa-arrow-up"></i> 5.7% from last period
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>

            @if(isset($reportData['top_vehicles']) && $reportData['top_vehicles']->count() > 0)
            <div style="margin-top: 2rem;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--dark);">Top Revenue Generating Vehicles</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                            <th>Average/Booking</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['top_vehicles'] as $vehicle)
                        <tr>
                            <td>
                                @if($vehicle->car)
                                    {{ $vehicle->car->brand }} {{ $vehicle->car->model }} ({{ $vehicle->car->license_plate ?? 'N/A' }})
                                @else
                                    Vehicle #{{ $vehicle->car_id }}
                                @endif
                            </td>
                            <td>{{ $vehicle->bookings }}</td>
                            <td>RM {{ number_format($vehicle->revenue, 2) }}</td>
                            <td>RM {{ number_format($vehicle->revenue / max(1, $vehicle->bookings), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="no-data">
                <i class="fas fa-chart-line" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>No revenue data available for the selected period</p>
            </div>
            @endif
        </div>

    @elseif($reportType == 'bookings')
        <!-- Bookings Report -->
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title">Bookings Analysis</h3>
                <span class="stat-badge info">Total: {{ $reportData['total_bookings'] ?? 0 }} bookings</span>
            </div>

            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['total_bookings'] ?? 0 }}</div>
                    <div class="metric-label">Total Bookings</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['completed_bookings'] ?? 0 }}</div>
                    <div class="metric-label">Completed</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['cancelled_bookings'] ?? 0 }}</div>
                    <div class="metric-label">Cancelled</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['active_bookings'] ?? 0 }}</div>
                    <div class="metric-label">Active</div>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="bookingsChart"></canvas>
            </div>

            @if(isset($reportData['popular_vehicles']) && $reportData['popular_vehicles']->count() > 0)
            <div style="margin-top: 2rem;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--dark);">Most Popular Vehicles</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Bookings</th>
                            <th>Category</th>
                            <th>Daily Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['popular_vehicles'] as $vehicle)
                        <tr>
                            <td>
                                @if($vehicle->car)
                                    {{ $vehicle->car->brand }} {{ $vehicle->car->model }} ({{ $vehicle->car->license_plate ?? 'N/A' }})
                                @else
                                    Vehicle #{{ $vehicle->car_id }}
                                @endif
                            </td>
                            <td>{{ $vehicle->bookings }}</td>
                            <td>
                                @if($vehicle->car)
                                    <span class="stat-badge">{{ ucfirst($vehicle->car->category) }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($vehicle->car)
                                    RM {{ number_format($vehicle->car->daily_rate, 2) }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    @elseif($reportType == 'vehicles')
        <!-- Vehicles Report -->
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title">Fleet Analysis</h3>
                <span class="stat-badge warning">Total: {{ $reportData['total_vehicles'] ?? 0 }} vehicles</span>
            </div>

            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['total_vehicles'] ?? 0 }}</div>
                    <div class="metric-label">Total Vehicles</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['available_vehicles'] ?? 0 }}</div>
                    <div class="metric-label">Available</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['rented_vehicles'] ?? 0 }}</div>
                    <div class="metric-label">Currently Rented</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">
                        @if(isset($reportData['total_vehicles']) && $reportData['total_vehicles'] > 0)
                            {{ round(($reportData['available_vehicles'] ?? 0) / $reportData['total_vehicles'] * 100) }}%
                        @else
                            0%
                        @endif
                    </div>
                    <div class="metric-label">Availability Rate</div>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="vehiclesChart"></canvas>
            </div>

            @if(isset($reportData['utilization_vehicles']) && $reportData['utilization_vehicles']->count() > 0)
            <div style="margin-top: 2rem;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--dark);">Vehicle Utilization Rate</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Days Rented</th>
                            <th>Utilization Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['utilization_vehicles'] as $data)
                        <tr>
                            <td>{{ $data['vehicle']->brand }} {{ $data['vehicle']->model }}</td>
                            <td>{{ $data['days_rented'] }}</td>
                            <td>{{ $data['utilization_rate'] }}%</td>
                            <td>
                                <span class="stat-badge {{ $data['vehicle']->is_available ? 'success' : 'danger' }}">
                                    {{ $data['vehicle']->is_available ? 'Available' : 'Rented' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    @elseif($reportType == 'customers')
        <!-- Customers Report -->
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title">Customer Insights</h3>
                <span class="stat-badge purple">Total: {{ $reportData['total_customers'] ?? 0 }} customers</span>
            </div>

            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['total_customers'] ?? 0 }}</div>
                    <div class="metric-label">Total Customers</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $reportData['new_customers'] ?? 0 }}</div>
                    <div class="metric-label">New Customers</div>
                    <div class="metric-change up">
                        <i class="fas fa-arrow-up"></i> 24% from last period
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">
                        @if(isset($reportData['total_customers']) && $reportData['total_customers'] > 0)
                            {{ round(($reportData['new_customers'] ?? 0) / $reportData['total_customers'] * 100) }}%
                        @else
                            0%
                        @endif
                    </div>
                    <div class="metric-label">Growth Rate</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">
                        @if(isset($reportData['top_customers']) && $reportData['top_customers']->count() > 0)
                            RM {{ number_format($reportData['top_customers']->first()->total_spent ?? 0, 2) }}
                        @else
                            RM 0.00
                        @endif
                    </div>
                    <div class="metric-label">Top Customer Spend</div>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="customersChart"></canvas>
            </div>

            @if(isset($reportData['top_customers']) && $reportData['top_customers']->count() > 0)
            <div style="margin-top: 2rem;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--dark);">Top Customers</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Bookings</th>
                            <th>Total Spent</th>
                            <th>Average/Booking</th>
                            <th>Member Since</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['top_customers'] as $customer)
                        <tr>
                            <td>
                                @if($customer->user)
                                    {{ $customer->user->name }}
                                    <div style="font-size: 0.75rem; color: var(--gray);">{{ $customer->user->email }}</div>
                                @else
                                    Customer #{{ $customer->user_id }}
                                @endif
                            </td>
                            <td>{{ $customer->bookings }}</td>
                            <td>RM {{ number_format($customer->total_spent, 2) }}</td>
                            <td>RM {{ number_format($customer->total_spent / max(1, $customer->bookings), 2) }}</td>
                            <td>
                                @if($customer->user)
                                    {{ $customer->user->created_at->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleCustomDates() {
    const dateRange = document.querySelector('[name="date_range"]').value;
    const customDates = document.getElementById('customDates');
    customDates.style.display = dateRange === 'custom' ? 'flex' : 'none';
}

function updateForm() {
    document.getElementById('reportForm').submit();
}

function exportReport(type) {
    const form = document.getElementById('reportForm');
    const action = form.action;
    const formData = new FormData(form);
    
    // Create a new form for export
    const exportForm = document.createElement('form');
    exportForm.method = 'POST';
    exportForm.action = "{{ route('staff.reports.export') }}";
    
    // Add all existing form data
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        exportForm.appendChild(input);
    }
    
    // Add export type
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = type;
    exportForm.appendChild(typeInput);
    
    // Add CSRF token
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = "{{ csrf_token() }}";
    exportForm.appendChild(tokenInput);
    
    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all charts based on report type
    @if($reportType == 'revenue' && isset($reportData['revenue_by_month']))
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueLabels = {!! json_encode(array_keys($reportData['revenue_by_month'])) !!};
        const revenueData = {!! json_encode(array_values($reportData['revenue_by_month'])) !!};
        
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData,
                    backgroundColor: '#dc2626',
                    borderColor: '#b91c1c',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
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
                        ticks: {
                            callback: function(value) {
                                return 'RM' + (value / 1000).toFixed(0) + 'k';
                            }
                        }
                    }
                }
            }
        });
    @endif

    @if($reportType == 'bookings' && isset($reportData['bookings_by_month']))
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        const bookingsLabels = {!! json_encode(array_keys($reportData['bookings_by_month'])) !!};
        const bookingsData = {!! json_encode(array_values($reportData['bookings_by_month'])) !!};
        
        new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: bookingsLabels,
                datasets: [{
                    label: 'Bookings',
                    data: bookingsData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    @endif

    @if($reportType == 'vehicles' && isset($reportData['vehicles_by_category']))
        const vehiclesCtx = document.getElementById('vehiclesChart').getContext('2d');
        const vehiclesLabels = {!! json_encode(array_keys($reportData['vehicles_by_category'])) !!};
        const vehiclesData = {!! json_encode(array_values($reportData['vehicles_by_category'])) !!};
        
        new Chart(vehiclesCtx, {
            type: 'doughnut',
            data: {
                labels: vehiclesLabels,
                datasets: [{
                    data: vehiclesData,
                    backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b', '#8b5cf6'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    @endif

    @if($reportType == 'customers' && isset($reportData['customer_growth']))
        const customersCtx = document.getElementById('customersChart').getContext('2d');
        const customersLabels = {!! json_encode(array_keys($reportData['customer_growth'])) !!};
        const customersData = {!! json_encode(array_values($reportData['customer_growth'])) !!};
        
        new Chart(customersCtx, {
            type: 'bar',
            data: {
                labels: customersLabels,
                datasets: [{
                    label: 'New Customers',
                    data: customersData,
                    backgroundColor: '#8b5cf6',
                    borderColor: '#7c3aed',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    @endif
});
</script>
@endpush
@endsection