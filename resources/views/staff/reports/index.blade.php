
@extends('staff.layouts.app')

@section('title', 'Reports & Analytics - Staff Portal')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header with Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics</h1>
            <p class="text-gray-600 mt-1">Comprehensive insights into rental performance and trends</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="refreshReportData()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Refresh</span>
            </button>
            <button onclick="exportReport('pdf')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Export PDF</span>
            </button>
            <button onclick="exportReport('excel')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Export Excel</span>
            </button>
        </div>
    </div>

    <!-- Date Range Filter Card -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold">Report Period</h3>
                <p class="text-red-100 text-sm mt-1" id="current-period-display">This Month (Jan 1 - Jan 31, 2026)</p>
            </div>
            <div class="flex flex-wrap items-end gap-3">
                <div class="min-w-[180px]">
                    <label class="block text-sm font-medium text-red-100 mb-2">Date Range</label>
                    <select id="date-range" class="w-full px-4 py-2 bg-white bg-opacity-10 border border-red-300 rounded-lg focus:ring-2 focus:ring-white focus:border-transparent text-white"
                            onchange="updateDateRange()">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                
                <div class="min-w-[180px]" id="custom-from-container" style="display: none;">
                    <label class="block text-sm font-medium text-red-100 mb-2">From Date</label>
                    <input type="date" id="date-from" class="w-full px-4 py-2 bg-white bg-opacity-10 border border-red-300 rounded-lg focus:ring-2 focus:ring-white focus:border-transparent text-white">
                </div>
                
                <div class="min-w-[180px]" id="custom-to-container" style="display: none;">
                    <label class="block text-sm font-medium text-red-100 mb-2">To Date</label>
                    <input type="date" id="date-to" class="w-full px-4 py-2 bg-white bg-opacity-10 border border-red-300 rounded-lg focus:ring-2 focus:ring-white focus:border-transparent text-white">
                </div>

                <button onclick="generateReport()" class="px-6 py-2 bg-white text-red-600 font-semibold rounded-lg hover:bg-gray-100 transition flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Apply Filter</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Bookings -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-total-bookings">0</p>
                    <div class="flex items-center gap-1 mt-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-green-600"></i>
                        <span class="text-sm text-green-600 font-medium">12% increase</span>
                        <span class="text-gray-400 text-sm">vs last period</span>
                    </div>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl">
                    <i data-lucide="calendar" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-revenue">RM 0</p>
                    <div class="flex items-center gap-1 mt-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-green-600"></i>
                        <span class="text-sm text-green-600 font-medium">18% increase</span>
                        <span class="text-gray-400 text-sm">vs last period</span>
                    </div>
                </div>
                <div class="bg-green-50 p-3 rounded-xl">
                    <i data-lucide="dollar-sign" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Utilization Rate -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Utilization Rate</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-utilization">0%</p>
                    <div class="flex items-center gap-1 mt-2">
                        <i data-lucide="target" class="w-4 h-4 text-purple-600"></i>
                        <span class="text-sm text-gray-600">Target: 85%</span>
                        <div class="ml-2 w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 p-3 rounded-xl">
                    <i data-lucide="activity" class="w-8 h-8 text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- New Customers -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New Customers</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-new-customers">0</p>
                    <div class="flex items-center gap-1 mt-2">
                        <i data-lucide="users" class="w-4 h-4 text-orange-600"></i>
                        <span class="text-sm text-green-600 font-medium">+23 this month</span>
                    </div>
                </div>
                <div class="bg-orange-50 p-3 rounded-xl">
                    <i data-lucide="user-plus" class="w-8 h-8 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Revenue Trend</h3>
                    <p class="text-sm text-gray-600 mt-1">Monthly revenue performance</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-600 rounded-full"></div>
                        <span class="text-sm text-gray-600">Revenue</span>
                    </div>
                    <select id="revenue-chart-type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white"
                            onchange="updateRevenueChart()">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly" selected>Monthly</option>
                    </select>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="revenue-chart"></canvas>
            </div>
        </div>

        <!-- Bookings by Status Chart -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Bookings by Status</h3>
                    <p class="text-sm text-gray-600 mt-1">Current booking distribution</p>
                </div>
                <div class="text-sm text-gray-500" id="total-bookings-display">Total: 0 bookings</div>
            </div>
            <div class="h-[300px] flex items-center justify-center">
                <canvas id="status-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Insights Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Performing Vehicles -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Top Performing Vehicles</h3>
                    <p class="text-sm text-gray-600 mt-1">By revenue and bookings</p>
                </div>
                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full">Top 5</span>
            </div>
            <div id="vehicle-performance" class="space-y-4">
                <!-- Populated by JavaScript -->
            </div>
        </div>

        <!-- Booking Types Distribution -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Booking Types Distribution</h3>
                    <p class="text-sm text-gray-600 mt-1">Delivery vs Self Pickup</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-600 rounded-full"></div>
                        <span class="text-sm text-gray-600">Delivery</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                        <span class="text-sm text-gray-600">Self Pickup</span>
                    </div>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="type-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Transactions Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
                <p class="text-sm text-gray-600 mt-1">Latest booking transactions</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="transaction-search" placeholder="Search transactions..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                           onkeyup="filterTransactions()">
                </div>
                <button onclick="loadMoreTransactions()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm">
                    Load More
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Booking ID</th>
                        <th class="px6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="transactions-table" class="divide-y divide-gray-200">
                    <!-- Populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 text-center" id="transactions-loading">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 text-sm mt-2">Loading transactions...</p>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Enhanced report data with more realistic figures
    const reportData = {
        summary: {
            totalBookings: 156,
            totalRevenue: 15680,
            utilizationRate: 78,
            newCustomers: 23
        },
        revenueByMonth: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            data: [12500, 14200, 15800, 16500, 17200, 18500, 19800, 20500, 19200, 21500, 22400, 23500]
        },
        bookingsByStatus: {
            pending: 8,
            confirmed: 12,
            approved: 5,
            active: 15,
            completed: 127,
            cancelled: 3
        },
        topVehicles: [
            { name: 'Honda City', bookings: 42, revenue: 12500, color: 'bg-blue-500' },
            { name: 'Perodua Myvi', bookings: 38, revenue: 9500, color: 'bg-green-500' },
            { name: 'Toyota Vios', bookings: 35, revenue: 11200, color: 'bg-purple-500' },
            { name: 'Honda Civic', bookings: 28, revenue: 13500, color: 'bg-red-500' },
            { name: 'Perodua Axia', bookings: 25, revenue: 7500, color: 'bg-orange-500' }
        ],
        bookingTypes: {
            delivery: 68,
            selfPickup: 88
        },
        transactions: [
            { date: '2026-01-10', id: 'BK005', customer: 'David Chen', vehicle: 'Honda Civic', type: 'self-pickup', status: 'active', amount: 480, contact: '+60123456789' },
            { date: '2026-01-10', id: 'BK004', customer: 'Lisa Wong', vehicle: 'Perodua Axia', type: 'delivery', status: 'approved', amount: 360, contact: '+60198765432' },
            { date: '2026-01-09', id: 'BK003', customer: 'Michael Tan', vehicle: 'Toyota Vios', type: 'self-pickup', status: 'completed', amount: 120, contact: '+60167890123' },
            { date: '2026-01-09', id: 'BK002', customer: 'Sarah Lee', vehicle: 'Honda City', type: 'delivery', status: 'completed', amount: 750, contact: '+60134567890' },
            { date: '2026-01-08', id: 'BK001', customer: 'Ahmad Ibrahim', vehicle: 'Perodua Myvi', type: 'self-pickup', status: 'completed', amount: 280, contact: '+60145678901' },
            { date: '2026-01-08', id: 'BK006', customer: 'Emily Ng', vehicle: 'Toyota Camry', type: 'delivery', status: 'completed', amount: 620, contact: '+60156789012' },
            { date: '2026-01-07', id: 'BK007', customer: 'John Lim', vehicle: 'Mazda 3', type: 'self-pickup', status: 'completed', amount: 420, contact: '+60178901234' },
            { date: '2026-01-07', id: 'BK008', customer: 'Rachel Koh', vehicle: 'Nissan Almera', type: 'delivery', status: 'cancelled', amount: 340, contact: '+60189012345' }
        ]
    };

    let revenueChart, statusChart, typeChart;
    let currentTransactions = [];

    function loadReports() {
        updateSummaryCards();
        initializeCharts();
        loadVehiclePerformance();
        loadTransactionsTable();
        updatePeriodDisplay();
    }

    function updateSummaryCards() {
        document.getElementById('report-total-bookings').textContent = reportData.summary.totalBookings.toLocaleString();
        document.getElementById('report-revenue').textContent = formatCurrency(reportData.summary.totalRevenue);
        document.getElementById('report-utilization').textContent = reportData.summary.utilizationRate + '%';
        document.getElementById('report-new-customers').textContent = reportData.summary.newCustomers;
        
        const totalBookings = Object.values(reportData.bookingsByStatus).reduce((a, b) => a + b, 0);
        document.getElementById('total-bookings-display').textContent = `Total: ${totalBookings.toLocaleString()} bookings`;
    }

    function updatePeriodDisplay() {
        const range = document.getElementById('date-range').value;
        const now = new Date();
        let displayText = '';
        
        switch(range) {
            case 'today':
                displayText = `Today (${formatDate(now.toISOString())})`;
                break;
            case 'yesterday':
                const yesterday = new Date(now);
                yesterday.setDate(yesterday.getDate() - 1);
                displayText = `Yesterday (${formatDate(yesterday.toISOString())})`;
                break;
            case 'week':
                const weekStart = new Date(now);
                weekStart.setDate(weekStart.getDate() - weekStart.getDay());
                displayText = `This Week (${formatDate(weekStart.toISOString())} - ${formatDate(now.toISOString())})`;
                break;
            case 'month':
                const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                displayText = `This Month (${formatDate(monthStart.toISOString())} - ${formatDate(now.toISOString())})`;
                break;
            case 'quarter':
                const quarter = Math.floor((now.getMonth() + 3) / 3);
                const quarterStart = new Date(now.getFullYear(), (quarter - 1) * 3, 1);
                displayText = `Q${quarter} ${now.getFullYear()} (${formatDate(quarterStart.toISOString())} - ${formatDate(now.toISOString())})`;
                break;
            case 'year':
                const yearStart = new Date(now.getFullYear(), 0, 1);
                displayText = `This Year (${formatDate(yearStart.toISOString())} - ${formatDate(now.toISOString())})`;
                break;
            default:
                displayText = 'Custom Period';
        }
        
        document.getElementById('current-period-display').textContent = displayText;
    }

    function initializeCharts() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
        if (revenueChart) revenueChart.destroy();
        
        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: reportData.revenueByMonth.labels,
                datasets: [{
                    label: 'Revenue (RM)',
                    data: reportData.revenueByMonth.data,
                    borderColor: '#DC2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#DC2626',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
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
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return 'RM ' + value.toLocaleString();
                            }
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

        // Status Chart
        const statusCtx = document.getElementById('status-chart').getContext('2d');
        if (statusChart) statusChart.destroy();
        
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Active', 'Confirmed', 'Pending', 'Approved', 'Cancelled'],
                datasets: [{
                    data: [
                        reportData.bookingsByStatus.completed,
                        reportData.bookingsByStatus.active,
                        reportData.bookingsByStatus.confirmed,
                        reportData.bookingsByStatus.pending,
                        reportData.bookingsByStatus.approved,
                        reportData.bookingsByStatus.cancelled
                    ],
                    backgroundColor: [
                        '#10B981', // Green - Completed
                        '#8B5CF6', // Purple - Active
                        '#3B82F6', // Blue - Confirmed
                        '#F59E0B', // Orange - Pending
                        '#EF4444', // Red - Approved
                        '#6B7280'  // Gray - Cancelled
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Type Chart
        const typeCtx = document.getElementById('type-chart').getContext('2d');
        if (typeChart) typeChart.destroy();
        
        typeChart = new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Delivery', 'Self Pickup'],
                datasets: [{
                    data: [
                        reportData.bookingTypes.delivery,
                        reportData.bookingTypes.selfPickup
                    ],
                    backgroundColor: ['#DC2626', '#3B82F6'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    function loadVehiclePerformance() {
        const container = document.getElementById('vehicle-performance');
        const maxRevenue = Math.max(...reportData.topVehicles.map(v => v.revenue));
        
        container.innerHTML = reportData.topVehicles.map((vehicle, index) => {
            const revenuePercentage = (vehicle.revenue / maxRevenue) * 100;
            const bookingPercentage = (vehicle.bookings / Math.max(...reportData.topVehicles.map(v => v.bookings))) * 100;
            
            return `
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 ${vehicle.color} text-white rounded-lg font-bold">
                                ${index + 1}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">${vehicle.name}</h4>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        ${vehicle.bookings} bookings
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="dollar-sign" class="w-3 h-3"></i>
                                        ${formatCurrency(vehicle.revenue)}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-gray-800">${((vehicle.bookings / reportData.summary.totalBookings) * 100).toFixed(1)}%</span>
                            <p class="text-xs text-gray-500">of total bookings</p>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>Revenue Performance</span>
                            <span>${formatCurrency(vehicle.revenue)}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="${vehicle.color} h-2 rounded-full transition-all duration-500" style="width: ${revenuePercentage}%"></div>
                        </div>
                        
                        <div class="flex items-center justify-between text-xs text-gray-600 mt-2">
                            <span>Booking Frequency</span>
                            <span>${vehicle.bookings} bookings</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="${vehicle.color.replace('500', '300')} h-2 rounded-full transition-all duration-500" style="width: ${bookingPercentage}%"></div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function loadTransactionsTable() {
        const tbody = document.getElementById('transactions-table');
        const loading = document.getElementById('transactions-loading');
        
        currentTransactions = reportData.transactions;
        
        if (currentTransactions.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="receipt" class="w-16 h-16 text-gray-400 mb-4"></i>
                            <p class="text-gray-500 text-lg">No transactions found</p>
                            <p class="text-gray-400 text-sm mt-2">Try adjusting your search or filters</p>
                        </div>
                    </td>
                </tr>
            `;
            lucide.createIcons();
        } else {
            tbody.innerHTML = currentTransactions.map(transaction => `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">${formatDate(transaction.date)}</span>
                            <span class="text-xs text-gray-500">${formatDate(transaction.date, 'time')}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-mono font-semibold text-gray-800">${transaction.id}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-800">${transaction.customer}</span>
                            <span class="text-xs text-gray-500">${transaction.contact}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-gray-800">${transaction.vehicle}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${transaction.type === 'delivery' 
                            ? '<span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full"><i data-lucide="truck" class="w-3 h-3"></i>Delivery</span>'
                            : '<span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full"><i data-lucide="map-pin" class="w-3 h-3"></i>Self Pickup</span>'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${getStatusBadge(transaction.status)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-bold text-gray-800">${formatCurrency(transaction.amount)}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="viewTransactionDetails('${transaction.id}')" 
                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition"
                                title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
            
            lucide.createIcons();
        }
        
        loading.style.display = currentTransactions.length > 0 ? 'none' : 'block';
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Pending</span>',
            'confirmed': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Confirmed</span>',
            'approved': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Approved</span>',
            'active': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Active</span>',
            'completed': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Cancelled</span>'
        };
        return badges[status] || '';
    }

    function filterTransactions() {
        const searchTerm = document.getElementById('transaction-search').value.toLowerCase();
        
        const filtered = reportData.transactions.filter(transaction => 
            transaction.id.toLowerCase().includes(searchTerm) ||
            transaction.customer.toLowerCase().includes(searchTerm) ||
            transaction.vehicle.toLowerCase().includes(searchTerm) ||
            transaction.type.toLowerCase().includes(searchTerm) ||
            transaction.status.toLowerCase().includes(searchTerm)
        );
        
        currentTransactions = filtered;
        loadTransactionsTable();
    }

    function loadMoreTransactions() {
        showToast('Loading more transactions...', 'success');
        // In production, this would load more data from API
        setTimeout(() => {
            showToast('Additional transactions loaded', 'success');
        }, 1500);
    }

    function viewTransactionDetails(transactionId) {
        const transaction = reportData.transactions.find(t => t.id === transactionId);
        if (!transaction) return;
        
        showToast(`Viewing details for ${transactionId}`, 'success');
        // In production, this would open a detailed modal
    }

    function updateDateRange() {
        const range = document.getElementById('date-range').value;
        const fromContainer = document.getElementById('custom-from-container');
        const toContainer = document.getElementById('custom-to-container');
        
        if (range === 'custom') {
            fromContainer.style.display = 'block';
            toContainer.style.display = 'block';
        } else {
            fromContainer.style.display = 'none';
            toContainer.style.display = 'none';
            updatePeriodDisplay();
        }
    }

    function generateReport() {
        showToast('Generating report with selected filters...', 'success');
        
        // Simulate loading
        document.querySelectorAll('#transactions-table, #vehicle-performance').forEach(el => {
            el.innerHTML = '<div class="text-center py-8"><div class="spinner mx-auto"></div><p class="text-gray-500 mt-2">Loading...</p></div>';
        });
        
        setTimeout(() => {
            loadReports();
            showToast('Report updated successfully!', 'success');
        }, 1500);
    }

    function refreshReportData() {
        showToast('Refreshing report data...', 'success');
        
        // Simulate data refresh
        document.getElementById('transactions-loading').style.display = 'block';
        
        setTimeout(() => {
            loadReports();
            showToast('Data refreshed successfully!', 'success');
        }, 1000);
    }

    function exportReport(type) {
        showToast(`Exporting report as ${type.toUpperCase()}...`, 'success');
        
        // Simulate export process
        setTimeout(() => {
            showToast(`Report exported as ${type.toUpperCase()} successfully!`, 'success');
            
            // Trigger download
            const link = document.createElement('a');
            link.href = '#';
            link.download = `car-rental-report-${new Date().toISOString().split('T')[0]}.${type}`;
            link.click();
        }, 2000);
    }

    function updateRevenueChart() {
        const type = document.getElementById('revenue-chart-type').value;
        
        // Update chart data based on type
        let labels, data;
        switch(type) {
            case 'daily':
                labels = Array.from({length: 7}, (_, i) => `Day ${i + 1}`);
                data = Array.from({length: 7}, () => Math.floor(Math.random() * 3000) + 2000);
                break;
            case 'weekly':
                labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                data = [4500, 5200, 4800, 5500];
                break;
            case 'monthly':
                labels = reportData.revenueByMonth.labels;
                data = reportData.revenueByMonth.data;
                break;
        }
        
        revenueChart.data.labels = labels;
        revenueChart.data.datasets[0].data = data;
        revenueChart.update();
        
        showToast(`Updated to ${type} revenue view`, 'success');
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadReports();
    });
</script>
@endpush

@endsection
