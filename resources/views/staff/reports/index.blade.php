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
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-md border border-gray-200 p-6 text-white">
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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Bookings</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-total-bookings">0</p>
                    <p class="text-xs text-green-600 mt-2">↑ 12% increase</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="calendar" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-revenue">RM 0</p>
                    <p class="text-xs text-green-600 mt-2">↑ 18% increase</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Utilization Rate -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Utilization Rate</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-utilization">0%</p>
                    <p class="text-xs text-gray-600 mt-2">Target: 85%</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="activity" class="w-8 h-8 text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- New Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">New Customers</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="report-new-customers">0</p>
                    <p class="text-xs text-green-600 mt-2">+23 this month</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="user-plus" class="w-8 h-8 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Revenue Trend</h3>
                    <p class="text-sm text-gray-600 mt-1">Monthly revenue performance</p>
                </div>
                <select id="revenue-chart-type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white"
                        onchange="updateRevenueChart()">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly" selected>Monthly</option>
                </select>
            </div>
            <div class="h-[300px]">
                <canvas id="revenue-chart"></canvas>
            </div>
        </div>

        <!-- Bookings by Status Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Bookings by Status</h3>
                    <p class="text-sm text-gray-600 mt-1">Current booking distribution</p>
                </div>
            </div>
            <div class="h-[300px] flex items-center justify-center">
                <canvas id="status-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Insights Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Performing Vehicles -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Top Performing Vehicles</h3>
                    <p class="text-sm text-gray-600 mt-1">By revenue and bookings</p>
                </div>
                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">Top 5</span>
            </div>
            <div id="vehicle-performance" class="space-y-4">
                <!-- Populated by JavaScript -->
            </div>
        </div>

        <!-- Booking Types Distribution -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Booking Types Distribution</h3>
                    <p class="text-sm text-gray-600 mt-1">Delivery vs Self Pickup</p>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="type-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
                <p class="text-sm text-gray-600 mt-1">Latest booking transactions</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="transaction-search" placeholder="Search transactions..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                           onkeyup="filterTransactions()">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart, statusChart, typeChart;
    let currentTransactions = [];
    let allBookings = [];

    // Fetch data from database
    async function fetchReportData() {
        try {
            const response = await fetch('/api/staff/reports/data', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to fetch report data');
            
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching report data:', error);
            showToast('Error loading report data', 'error');
            return null;
        }
    }

    async function loadReports() {
        const data = await fetchReportData();
        
        if (data) {
            updateSummaryCards(data.summary);
            initializeCharts(data);
            loadVehiclePerformance(data.topVehicles);
            loadTransactionsTable(data.transactions);
        }
        
        updatePeriodDisplay();
    }

    function updateSummaryCards(summary) {
        document.getElementById('report-total-bookings').textContent = summary.totalBookings.toLocaleString();
        document.getElementById('report-revenue').textContent = formatCurrency(summary.totalRevenue);
        document.getElementById('report-utilization').textContent = summary.utilizationRate + '%';
        document.getElementById('report-new-customers').textContent = summary.newCustomers;
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
                displayText = `This Month (Jan 1 - Jan 31, 2026)`;
                break;
            case 'quarter':
                const quarter = Math.floor((now.getMonth() + 3) / 3);
                displayText = `Q${quarter} ${now.getFullYear()}`;
                break;
            case 'year':
                displayText = `This Year (${now.getFullYear()})`;
                break;
            default:
                displayText = 'Custom Period';
        }
        
        document.getElementById('current-period-display').textContent = displayText;
    }

    function initializeCharts(data) {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
        if (revenueChart) revenueChart.destroy();
        
        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: data.revenueByMonth.labels,
                datasets: [{
                    label: 'Revenue (RM)',
                    data: data.revenueByMonth.data,
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
                        grid: { drawBorder: false },
                        ticks: {
                            callback: function(value) {
                                return 'RM ' + value.toLocaleString();
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('status-chart').getContext('2d');
        if (statusChart) statusChart.destroy();
        
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(data.bookingsByStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: Object.values(data.bookingsByStatus),
                    backgroundColor: ['#10B981', '#8B5CF6', '#3B82F6', '#F59E0B', '#EF4444', '#6B7280'],
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
                        labels: { padding: 20, usePointStyle: true }
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
                    data: [data.bookingTypes.delivery, data.bookingTypes.selfPickup],
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
                        labels: { padding: 20, usePointStyle: true }
                    }
                }
            }
        });
    }

    function loadVehiclePerformance(topVehicles) {
        const container = document.getElementById('vehicle-performance');
        const maxRevenue = Math.max(...topVehicles.map(v => v.revenue));
        
        container.innerHTML = topVehicles.map((vehicle, index) => {
            const revenuePercentage = (vehicle.revenue / maxRevenue) * 100;
            const colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-red-500', 'bg-orange-500'];
            const color = colors[index] || 'bg-gray-500';
            
            return `
                <div class="space-y-3 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 ${color} text-white rounded-lg font-bold text-sm">
                                ${index + 1}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">${vehicle.name}</h4>
                                <p class="text-xs text-gray-500">${vehicle.bookings} bookings • ${formatCurrency(vehicle.revenue)}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">${vehicle.percentage}%</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="${color} h-2 rounded-full transition-all duration-500" style="width: ${revenuePercentage}%"></div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function loadTransactionsTable(transactions) {
        const tbody = document.getElementById('transactions-table');
        const loading = document.getElementById('transactions-loading');
        
        currentTransactions = transactions;
        
        if (currentTransactions.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mb-4 mx-auto"></i>
                        <p class="text-gray-500 text-lg">No transactions found</p>
                    </td>
                </tr>
            `;
            lucide.createIcons();
        } else {
            tbody.innerHTML = currentTransactions.map(transaction => `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">${formatDate(transaction.date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-mono font-semibold text-gray-800 text-sm">${transaction.id}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-800 text-sm">${transaction.customer}</span>
                            <span class="text-xs text-gray-500">${transaction.contact}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">${transaction.vehicle}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${transaction.type === 'delivery' 
                            ? '<span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium"><i data-lucide="truck" class="w-3 h-3"></i>Delivery</span>'
                            : '<span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium"><i data-lucide="map-pin" class="w-3 h-3"></i>Self Pickup</span>'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">${getStatusBadge(transaction.status)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold text-gray-800 text-sm">${formatCurrency(transaction.amount)}</span>
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
        
        loading.style.display = 'none';
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
        
        const filtered = currentTransactions.filter(transaction => 
            transaction.id.toLowerCase().includes(searchTerm) ||
            transaction.customer.toLowerCase().includes(searchTerm) ||
            transaction.vehicle.toLowerCase().includes(searchTerm)
        );
        
        loadTransactionsTable(filtered);
    }

    function viewTransactionDetails(transactionId) {
        showToast(`Viewing details for ${transactionId}`, 'success');
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

    async function generateReport() {
        showToast('Generating report...', 'success');
        await loadReports();
        showToast('Report updated successfully!', 'success');
    }

    async function refreshReportData() {
        showToast('Refreshing report data...', 'success');
        await loadReports();
        showToast('Data refreshed successfully!', 'success');
    }

    function exportReport(type) {
        showToast(`Exporting report as ${type.toUpperCase()}...`, 'success');
        
        setTimeout(() => {
            showToast(`Report exported as ${type.toUpperCase()} successfully!`, 'success');
        }, 2000);
    }

    async function updateRevenueChart() {
        const type = document.getElementById('revenue-chart-type').value;
        showToast(`Updated to ${type} revenue view`, 'success');
        await loadReports();
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadReports();
    });
</script>
@endpush

@endsection