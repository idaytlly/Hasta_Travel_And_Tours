@extends('staff.layouts.app')

@section('title', 'Reports & Analytics - Staff Portal')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics</h1>
            <p class="text-gray-600 mt-1">Comprehensive rental performance insights</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button onclick="refreshReportData()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Refresh</span>
            </button>
            <button onclick="exportReport('pdf')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span class="hidden sm:inline">PDF</span>
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-800">Report Period</h3>
                <p class="text-gray-600 text-sm mt-1" id="current-period-display">Loading...</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <select id="date-range" class="w-full lg:w-auto px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white"
                        onchange="updateDateRange()">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                    <option value="custom">Custom Range</option>
                </select>
                
                <div class="hidden w-full lg:w-auto" id="custom-date-container">
                    <div class="flex flex-wrap gap-2">
                        <input type="date" id="date-from" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm min-w-[150px]">
                        <input type="date" id="date-to" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm min-w-[150px]">
                    </div>
                </div>

                <button onclick="generateReport()" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Apply</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="total-bookings">0</p>
                    <p class="text-xs text-gray-500" id="booking-change">Loading...</p>
                </div>
                <div class="bg-blue-50 p-2 rounded-lg">
                    <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="total-revenue">RM 0</p>
                    <p class="text-xs text-gray-500" id="revenue-change">Loading...</p>
                </div>
                <div class="bg-green-50 p-2 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Rentals</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="active-rentals">0</p>
                    <p class="text-xs text-gray-500">Currently active</p>
                </div>
                <div class="bg-purple-50 p-2 rounded-lg">
                    <i data-lucide="car" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">New Customers</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="new-customers">0</p>
                    <p class="text-xs text-gray-500">This period</p>
                </div>
                <div class="bg-orange-50 p-2 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <div>
                    <h3 class="font-semibold text-gray-800">Revenue Trend</h3>
                    <p class="text-sm text-gray-600">Monthly performance</p>
                </div>
                <select id="chart-period" class="px-3 py-1 border border-gray-300 rounded text-sm"
                        onchange="updateChartData()">
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="daily">Daily</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="revenue-chart"></canvas>
            </div>
        </div>

        <!-- Vehicle Utilization -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">Vehicle Utilization</h3>
                    <p class="text-sm text-gray-600">Top 5 vehicles</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="utilization-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Recent Bookings</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[500px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Booking ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="recent-bookings" class="divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Vehicles -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Popular Vehicles</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[500px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Vehicle</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Bookings</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody id="popular-vehicles" class="divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart, utilizationChart;
    let currentPeriod = 'month';

    // Fetch real data from database
    async function fetchReportData(period = 'month', startDate = null, endDate = null) {
        try {
            const params = new URLSearchParams({
                period: period,
                ...(startDate && { start_date: startDate }),
                ...(endDate && { end_date: endDate })
            });

            const response = await fetch(`/api/staff/reports/data?${params}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            });
            
            console.log('Fetching data from:', `/api/staff/reports/data?${params}`);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error response:', response.status, errorText);
                throw new Error(`Failed to fetch: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Received data:', data);
            return data;
        } catch (error) {
            console.error('Fetch error:', error);
            showToast('Error loading report data. Loading test data instead.', 'error');
            return getTestData();
        }
    }

    // Initialize dashboard
    async function initializeDashboard() {
        try {
            // Show loading state
            showLoadingState(true);
            
            const data = await fetchReportData(currentPeriod);
            
            if (data) {
                updateSummaryCards(data.summary);
                updateCharts(data.charts);
                updateTables(data.tables);
                updatePeriodDisplay();
            }
            
            showLoadingState(false);
        } catch (error) {
            console.error('Dashboard init error:', error);
            showLoadingState(false);
            showToast('Failed to load dashboard data', 'error');
        }
    }

    function showLoadingState(loading) {
        const elements = ['total-bookings', 'total-revenue', 'active-rentals', 'new-customers'];
        elements.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = loading ? '...' : element.textContent;
            }
        });
        
        // Update table placeholders
        if (loading) {
            document.getElementById('recent-bookings').innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex items-center justify-center gap-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-red-600"></div>
                            Loading bookings...
                        </div>
                    </td>
                </tr>
            `;
            
            document.getElementById('popular-vehicles').innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex items-center justify-center gap-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-red-600"></div>
                            Loading vehicles...
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    function updateSummaryCards(summary) {
        document.getElementById('total-bookings').textContent = summary.total_bookings?.toLocaleString() || '0';
        document.getElementById('total-revenue').textContent = `RM ${(summary.total_revenue || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        document.getElementById('active-rentals').textContent = summary.active_rentals || '0';
        document.getElementById('new-customers').textContent = summary.new_customers || '0';
        
        // Update change indicators
        const bookingChange = summary.booking_change || 0;
        const revenueChange = summary.revenue_change || 0;
        
        document.getElementById('booking-change').innerHTML = 
            bookingChange >= 0 
                ? `<span class="text-green-600">↑ ${bookingChange}% from last period</span>`
                : `<span class="text-red-600">↓ ${Math.abs(bookingChange)}% from last period</span>`;
        
        document.getElementById('revenue-change').innerHTML = 
            revenueChange >= 0 
                ? `<span class="text-green-600">↑ ${revenueChange}% from last period</span>`
                : `<span class="text-red-600">↓ ${Math.abs(revenueChange)}% from last period</span>`;
    }

    function updateCharts(chartData) {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
        if (revenueChart) revenueChart.destroy();
        
        const revenueLabels = chartData?.revenue?.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const revenueValues = chartData?.revenue?.data || [0, 0, 0, 0, 0, 0];
        
        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue (RM)',
                    data: revenueValues,
                    borderColor: '#DC2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#DC2626',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
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
                                return `Revenue: RM ${context.raw.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RM ' + value.toLocaleString();
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Utilization Chart
        const utilCtx = document.getElementById('utilization-chart').getContext('2d');
        if (utilizationChart) utilizationChart.destroy();
        
        const utilLabels = chartData?.utilization?.labels || ['Vehicle A', 'Vehicle B', 'Vehicle C', 'Vehicle D', 'Vehicle E'];
        const utilValues = chartData?.utilization?.data || [85, 72, 90, 65, 78];
        
        utilizationChart = new Chart(utilCtx, {
            type: 'bar',
            data: {
                labels: utilLabels,
                datasets: [{
                    label: 'Utilization %',
                    data: utilValues,
                    backgroundColor: '#3B82F6',
                    borderRadius: 4,
                    borderSkipped: false
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
                                return `Utilization: ${context.raw}%`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function updateTables(tableData) {
        // Recent Bookings
        const bookingsTable = document.getElementById('recent-bookings');
        const recentBookings = tableData?.recent_bookings || [];
        
        if (recentBookings.length === 0) {
            bookingsTable.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-400"></i>
                        <p class="text-sm">No bookings found for this period</p>
                    </td>
                </tr>
            `;
        } else {
            bookingsTable.innerHTML = recentBookings.map(booking => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">${booking.booking_id || 'N/A'}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${booking.customer_name || 'Unknown Customer'}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(booking.status)}">
                            ${booking.status || 'unknown'}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                        RM ${(booking.amount || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                </tr>
            `).join('');
        }

        // Popular Vehicles
        const vehiclesTable = document.getElementById('popular-vehicles');
        const popularVehicles = tableData?.popular_vehicles || [];
        
        if (popularVehicles.length === 0) {
            vehiclesTable.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <i data-lucide="car" class="w-8 h-8 mx-auto mb-2 text-gray-400"></i>
                        <p class="text-sm">No vehicle data available</p>
                    </td>
                </tr>
            `;
        } else {
            vehiclesTable.innerHTML = popularVehicles.map(vehicle => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">${vehicle.name || 'Unknown Vehicle'}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${vehicle.category || 'N/A'}</td>
                    <td class="px-4 py-3 text-sm font-medium">${vehicle.bookings || 0}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                        RM ${(vehicle.revenue || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                </tr>
            `).join('');
        }
        
        // Refresh icons if needed
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function getStatusClass(status) {
        const statusLower = (status || '').toLowerCase();
        const classes = {
            'confirmed': 'bg-blue-100 text-blue-800',
            'approved': 'bg-green-100 text-green-800',
            'active': 'bg-purple-100 text-purple-800',
            'completed': 'bg-gray-100 text-gray-800',
            'cancelled': 'bg-red-100 text-red-800',
            'pending': 'bg-yellow-100 text-yellow-800'
        };
        return classes[statusLower] || 'bg-gray-100 text-gray-800';
    }

    function updatePeriodDisplay() {
        const range = document.getElementById('date-range').value;
        const now = new Date();
        let displayText = '';
        
        switch(range) {
            case 'today':
                displayText = `Today (${formatDate(now)})`;
                break;
            case 'yesterday':
                const yesterday = new Date(now);
                yesterday.setDate(yesterday.getDate() - 1);
                displayText = `Yesterday (${formatDate(yesterday)})`;
                break;
            case 'week':
                const weekStart = new Date(now);
                weekStart.setDate(weekStart.getDate() - weekStart.getDay());
                displayText = `This Week (${formatDate(weekStart)} - ${formatDate(now)})`;
                break;
            case 'month':
                const monthName = now.toLocaleDateString('en-US', { month: 'long' });
                displayText = `This Month (${monthName} ${now.getFullYear()})`;
                break;
            case 'quarter':
                const quarter = Math.floor((now.getMonth() + 3) / 3);
                displayText = `Q${quarter} ${now.getFullYear()}`;
                break;
            case 'year':
                displayText = `This Year (${now.getFullYear()})`;
                break;
            default:
                const fromDate = document.getElementById('date-from').value;
                const toDate = document.getElementById('date-to').value;
                if (fromDate && toDate) {
                    displayText = `Custom (${formatDate(fromDate)} - ${formatDate(toDate)})`;
                } else {
                    displayText = 'Custom Period';
                }
        }
        
        document.getElementById('current-period-display').textContent = displayText;
    }

    function formatDate(dateInput) {
        const date = new Date(dateInput);
        if (isNaN(date.getTime())) return 'Invalid Date';
        
        return date.toLocaleDateString('en-MY', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function updateDateRange() {
        const range = document.getElementById('date-range').value;
        const customContainer = document.getElementById('custom-date-container');
        
        if (range === 'custom') {
            customContainer.classList.remove('hidden');
            
            // Set default dates for custom range (last 30 days)
            const toDate = new Date();
            const fromDate = new Date();
            fromDate.setDate(fromDate.getDate() - 30);
            
            document.getElementById('date-from').valueAsDate = fromDate;
            document.getElementById('date-to').valueAsDate = toDate;
        } else {
            customContainer.classList.add('hidden');
            updatePeriodDisplay();
        }
    }

    async function generateReport() {
        const range = document.getElementById('date-range').value;
        let startDate = null, endDate = null;
        
        if (range === 'custom') {
            startDate = document.getElementById('date-from').value;
            endDate = document.getElementById('date-to').value;
            
            if (!startDate || !endDate) {
                showToast('Please select both start and end dates', 'error');
                return;
            }
        }
        
        showToast('Loading report data...', 'info');
        currentPeriod = range;
        await initializeDashboard();
    }

    async function refreshReportData() {
        showToast('Refreshing data...', 'info');
        await initializeDashboard();
    }

    async function updateChartData() {
        const period = document.getElementById('chart-period').value;
        const data = await fetchReportData(period);
        
        if (data) {
            updateCharts(data.charts);
        }
    }

    // Replace the exportReport function with this:
    // Replace the exportReport function with:
async function exportReport(type) {
    try {
        showToast(`Preparing ${type.toUpperCase()} export...`, 'info');
        
        const range = document.getElementById('date-range').value;
        let startDate = null, endDate = null;
        
        if (range === 'custom') {
            startDate = document.getElementById('date-from').value;
            endDate = document.getElementById('date-to').value;
            
            if (!startDate || !endDate) {
                showToast('Please select both start and end dates', 'error');
                return;
            }
        }
        
        // Build query parameters
        const params = new URLSearchParams({
            period: range,
            type: 'summary'
        });
        
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);
        
        let url;
        if (type === 'pdf') {
            // Use the new route
            url = `/staff/reports/export/pdf?${params.toString()}`;
        } else if (type === 'excel') {
            url = `/staff/reports/export/excel?${params.toString()}`;
        } else {
            return;
        }
        
        // Create hidden download link
        const link = document.createElement('a');
        link.href = url;
        link.download = `report_${new Date().toISOString().split('T')[0]}.${type}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showToast(`${type.toUpperCase()} export started`, 'success');
        
    } catch (error) {
        console.error('Export error:', error);
        showToast('Failed to generate export', 'error');
    }
}

    // Add function to export customer invoice
    async function exportCustomerInvoice(customerId, bookingIds = []) {
        try {
            showToast('Generating invoice...', 'info');
            
            const params = new URLSearchParams();
            if (bookingIds.length > 0) {
                params.append('booking_ids', JSON.stringify(bookingIds));
            }
            
            const url = `/staff/reports/export/customer/${customerId}/invoice?${params.toString()}`;
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `invoice_${customerId}_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showToast('Invoice generated successfully', 'success');
        } catch (error) {
            console.error('Invoice generation error:', error);
            showToast('Failed to generate invoice', 'error');
        }
    }

    // Add function to export monthly report
    async function exportMonthlyReport(year, month) {
        try {
            showToast('Generating monthly report...', 'info');
            
            const url = `/staff/reports/export/monthly/${year}/${month}`;
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `monthly_report_${year}_${month}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showToast('Monthly report generated successfully', 'success');
        } catch (error) {
            console.error('Monthly report error:', error);
            showToast('Failed to generate monthly report', 'error');
        }
    }

    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-full ${type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' : type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200'}`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i data-lucide="${type === 'error' ? 'alert-circle' : type === 'success' ? 'check-circle' : 'info'}" class="w-4 h-4"></i>
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 10);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
        
        // Refresh icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Test data fallback
    function getTestData() {
        return {
            summary: {
                total_bookings: 156,
                total_revenue: 58420.75,
                active_rentals: 18,
                new_customers: 34,
                booking_change: 15.8,
                revenue_change: 22.3
            },
            tables: {
                recent_bookings: [
                    { booking_id: 'BK-2024-0123', customer_name: 'Ahmad bin Ali', status: 'active', amount: 520.50 },
                    { booking_id: 'BK-2024-0122', customer_name: 'Siti Nurhaliza', status: 'completed', amount: 320.00 },
                    { booking_id: 'BK-2024-0121', customer_name: 'John Lim', status: 'approved', amount: 680.00 },
                    { booking_id: 'BK-2024-0120', customer_name: 'Sarah Tan', status: 'pending', amount: 240.75 },
                    { booking_id: 'BK-2024-0119', customer_name: 'Raj Kumar', status: 'active', amount: 450.00 }
                ],
                popular_vehicles: [
                    { name: 'Perodua Myvi', category: 'Hatchback', bookings: 68, revenue: 28560 },
                    { name: 'Toyota Hilux', category: 'Pickup', bookings: 52, revenue: 31200 },
                    { name: 'Honda City', category: 'Sedan', bookings: 45, revenue: 20250 },
                    { name: 'Proton X70', category: 'SUV', bookings: 38, revenue: 19000 },
                    { name: 'Toyota Vellfire', category: 'MPV', bookings: 25, revenue: 17500 }
                ]
            },
            charts: {
                revenue: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    data: [8500, 9200, 10200, 9800, 11500, 12500]
                },
                utilization: {
                    labels: ['Myvi', 'Hilux', 'City', 'X70', 'Vellfire'],
                    data: [92, 85, 78, 82, 75]
                }
            }
        };
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        // Set default dates
        const now = new Date();
        const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
        const monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        
        document.getElementById('date-from').valueAsDate = monthStart;
        document.getElementById('date-to').valueAsDate = monthEnd;
        
        initializeDashboard();
        updatePeriodDisplay();
        
        // Refresh Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
@endsection