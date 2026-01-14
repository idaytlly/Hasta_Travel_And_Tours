@extends('staff.layouts.app')

@section('title', 'Dashboard - Staff Portal')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, <span id="welcome-name">{{ Auth::guard('staff')->user()->name ?? 'Staff' }}</span>!</h1>
                <p class="text-red-100 mt-2">Here's what's happening with your rental business today.</p>
            </div>
            <button onclick="refreshDashboard()" class="px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Bookings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's Bookings</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="stat-today-bookings">0</p>
                    <p class="text-xs text-green-600 mt-2" id="stat-today-change">--</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="calendar" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Bookings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Bookings</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="stat-active-bookings">0</p>
                    <p class="text-xs text-blue-600 mt-2">Currently rented</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="activity" class="w-8 h-8 text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Approvals</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="stat-pending">0</p>
                    <p class="text-xs text-orange-600 mt-2">Needs attention</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="clock" class="w-8 h-8 text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Month Revenue -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">This Month Revenue</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="stat-revenue">RM 0</p>
                    <p class="text-xs text-green-600 mt-2" id="stat-revenue-change">--</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('staff.bookings') ?? '/staff/bookings' }}" 
                   class="w-full block px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition flex items-center gap-3">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span class="font-medium">View All Bookings</span>
                </a>
                
                <a href="{{ route('staff.vehicles.index') ?? '/staff/vehicles' }}" 
                   class="w-full block px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center gap-3">
                    <i data-lucide="car" class="w-5 h-5"></i>
                    <span class="font-medium">Manage Vehicles</span>
                </a>
                
                <a href="{{ route('staff.customers') ?? '/staff/customers' }}" 
                   class="w-full block px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition flex items-center gap-3">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-medium">View Customers</span>
                </a>
                
                <a href="{{ route('staff.reports') ?? '/staff/reports' }}" 
                   class="w-full block px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition flex items-center gap-3">
                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                    <span class="font-medium">Generate Reports</span>
                </a>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                <a href="{{ route('staff.bookings') ?? '/staff/bookings' }}" class="text-sm text-red-600 hover:text-red-700 font-medium">View All →</a>
            </div>
            <div id="recent-bookings" class="space-y-3">
                <!-- Loading state -->
                <div class="text-center py-8">
                    <div class="spinner mx-auto"></div>
                    <p class="text-gray-500 text-sm mt-2">Loading bookings...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule & Vehicle Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Schedule -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Today's Schedule</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full" id="schedule-count">0 tasks</span>
            </div>
            <div id="today-schedule" class="space-y-3">
                <div class="text-center py-8">
                    <div class="spinner mx-auto"></div>
                    <p class="text-gray-500 text-sm mt-2">Loading schedule...</p>
                </div>
            </div>
        </div>

        <!-- Vehicle Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Vehicle Status</h3>
                <button onclick="refreshVehicleStatus()" class="text-sm text-red-600 hover:text-red-700 font-medium">Refresh</button>
            </div>
            <div class="space-y-4" id="vehicle-status">
                <div class="text-center py-8">
                    <div class="spinner mx-auto"></div>
                    <p class="text-gray-500 text-sm mt-2">Loading vehicle status...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Fetch dashboard data from API
    async function fetchDashboardData() {
        try {
            const response = await fetch('/api/staff/dashboard/data', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            updateDashboard(data);
            return data;
            
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            showError('Failed to load dashboard data');
        }
    }

    // Update all dashboard components
    function updateDashboard(data) {
        if (!data || !data.success) {
            showError('Invalid data received');
            return;
        }
        
        // Update stats
        if (data.stats) updateStats(data.stats);
        
        // Update recent bookings
        if (data.recentBookings) updateRecentBookings(data.recentBookings);
        
        // Update today's schedule
        if (data.schedule) updateTodaySchedule(data.schedule);
        
        // Update vehicle status
        if (data.vehicleStatus) updateVehicleStatus(data.vehicleStatus);
        
        // Reinitialize Lucide icons
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // Update statistics cards
    function updateStats(stats) {
        document.getElementById('stat-today-bookings').textContent = stats.todayBookings || 0;
        document.getElementById('stat-active-bookings').textContent = stats.activeBookings || 0;
        document.getElementById('stat-pending').textContent = stats.pendingApprovals || 0;
        document.getElementById('stat-revenue').textContent = formatCurrency(stats.monthRevenue || 0);
        
        // Update change indicators
        const todayChange = stats.todayBookingsChange || 0;
        const todayChangeEl = document.getElementById('stat-today-change');
        if (todayChangeEl) {
            todayChangeEl.textContent = 
                `${todayChange > 0 ? '↑' : todayChange < 0 ? '↓' : ''} ${Math.abs(todayChange)}% from yesterday`;
            todayChangeEl.className = 
                `text-xs mt-2 ${todayChange >= 0 ? 'text-green-600' : 'text-red-600'}`;
        }
            
        const revenueChange = stats.revenueChange || 0;
        const revenueChangeEl = document.getElementById('stat-revenue-change');
        if (revenueChangeEl) {
            revenueChangeEl.textContent = 
                `${revenueChange > 0 ? '↑' : revenueChange < 0 ? '↓' : ''} ${Math.abs(revenueChange)}% from last month`;
            revenueChangeEl.className = 
                `text-xs mt-2 ${revenueChange >= 0 ? 'text-green-600' : 'text-red-600'}`;
        }
    }

    // Update recent bookings list
    function updateRecentBookings(bookings) {
        const container = document.getElementById('recent-bookings');
        if (!container) return;
        
        if (!bookings || bookings.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-2"></i>
                    <p class="text-gray-500">No recent bookings</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = bookings.slice(0, 5).map(booking => `
            <a href="/staff/bookings/${escapeHtml(booking.booking_code || booking.id)}" class="block">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="calendar" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">${escapeHtml(booking.booking_code || 'Booking')}</p>
                            <p class="text-sm text-gray-600">${escapeHtml(booking.customer_name || 'Customer')} • ${escapeHtml(booking.vehicle_name || 'Vehicle')}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        ${getStatusBadge(booking.status)}
                        <p class="text-sm text-gray-600 mt-1">${formatCurrency(booking.total_amount || 0)}</p>
                    </div>
                </div>
            </a>
        `).join('');
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // Update today's schedule
    function updateTodaySchedule(schedule) {
        const container = document.getElementById('today-schedule');
        const countElement = document.getElementById('schedule-count');
        
        if (!container) return;
        
        if (!schedule || schedule.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i data-lucide="check-circle" class="w-12 h-12 text-green-400 mx-auto mb-2"></i>
                    <p class="text-gray-500">No scheduled tasks for today</p>
                </div>
            `;
            if (countElement) countElement.textContent = '0 tasks';
            return;
        }
        
        if (countElement) countElement.textContent = `${schedule.length} task${schedule.length !== 1 ? 's' : ''}`;
        
        container.innerHTML = schedule.map(task => `
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 ${task.completed ? 'bg-green-500' : 'bg-orange-500'} rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">${escapeHtml(task.title)}</p>
                    <p class="text-sm text-gray-600">${escapeHtml(task.time)} • ${escapeHtml(task.type)}</p>
                </div>
            </div>
        `).join('');
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // Update vehicle status
    function updateVehicleStatus(vehicles) {
        const container = document.getElementById('vehicle-status');
        if (!container || !vehicles) return;
        
        const total = vehicles.total || 1;
        const available = vehicles.available || 0;
        const rented = vehicles.rented || 0;
        const maintenance = vehicles.maintenance || 0;
        
        container.innerHTML = `
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
                    <div>
                        <p class="font-semibold text-gray-800">${available}</p>
                        <p class="text-sm text-gray-600">Available</p>
                    </div>
                </div>
                <div class="text-2xl font-bold text-green-600">${((available/total)*100).toFixed(0)}%</div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <i data-lucide="activity" class="w-8 h-8 text-purple-600"></i>
                    <div>
                        <p class="font-semibold text-gray-800">${rented}</p>
                        <p class="text-sm text-gray-600">Currently Rented</p>
                    </div>
                </div>
                <div class="text-2xl font-bold text-purple-600">${((rented/total)*100).toFixed(0)}%</div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <i data-lucide="wrench" class="w-8 h-8 text-orange-600"></i>
                    <div>
                        <p class="font-semibold text-gray-800">${maintenance}</p>
                        <p class="text-sm text-gray-600">Under Maintenance</p>
                    </div>
                </div>
                <div class="text-2xl font-bold text-orange-600">${((maintenance/total)*100).toFixed(0)}%</div>
            </div>
        `;
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // Utility functions
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Pending</span>',
            'confirmed': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Confirmed</span>',
            'approved': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Approved</span>',
            'active': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Active</span>',
            'completed': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Cancelled</span>'
        };
        return badges[status?.toLowerCase()] || badges['pending'];
    }

    function formatCurrency(amount) {
        return 'RM ' + parseFloat(amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function showError(message) {
        const container = document.getElementById('recent-bookings');
        if (container) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i data-lucide="alert-circle" class="w-12 h-12 text-red-400 mx-auto mb-2"></i>
                    <p class="text-red-500">${escapeHtml(message)}</p>
                </div>
            `;
        }
    }

    // Refresh dashboard manually
    async function refreshDashboard() {
        await fetchDashboardData();
    }

    // Refresh vehicle status
    async function refreshVehicleStatus() {
        await fetchDashboardData();
    }

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', () => {
        // Initial load
        fetchDashboardData();
        
        // Auto-refresh every 30 seconds
        setInterval(fetchDashboardData, 30000);
    });
</script>

<style>
.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #dc2626;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@endsection