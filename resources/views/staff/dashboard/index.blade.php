@extends('staff.layouts.app')

@section('title', 'Dashboard - Staff Portal')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, <span id="welcome-name">{{ Auth::guard('staff')->user()->name }}</span>!</h1>
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
                    <p class="text-xs text-green-600 mt-2" id="stat-today-change">Loading...</p>
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
                    <p class="text-xs text-green-600 mt-2" id="stat-revenue-change">Loading...</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="space-y-3">
        @if(Route::has('staff.bookings'))
            <button onclick="window.location.href='{{ route('staff.bookings') }}'" 
                    class="w-full px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition flex items-center gap-3">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span class="font-medium">View All Bookings</span>
            </button>
        @else
            <button onclick="window.location.href='/staff/bookings'" 
                    class="w-full px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition flex items-center gap-3">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span class="font-medium">View All Bookings</span>
            </button>
        @endif
        
        <button onclick="window.location.href='{{ route('staff.vehicles.index') }}'" 
                class="w-full px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center gap-3">
            <i data-lucide="car" class="w-5 h-5"></i>
            <span class="font-medium">Manage Vehicles</span>
        </button>
        
        @if(Route::has('staff.customers'))
            <button onclick="window.location.href='{{ route('staff.customers') }}'" 
                    class="w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition flex items-center gap-3">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium">View Customers</span>
            </button>
        @else
            <button onclick="window.location.href='/staff/customers'" 
                    class="w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition flex items-center gap-3">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium">View Customers</span>
            </button>
        @endif
        
        @if(Route::has('staff.reports'))
            <button onclick="window.location.href='{{ route('staff.reports') }}'" 
                    class="w-full px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition flex items-center gap-3">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                <span class="font-medium">Generate Reports</span>
            </button>
        @else
            <button onclick="window.location.href='/staff/reports'" 
                    class="w-full px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition flex items-center gap-3">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                <span class="font-medium">Generate Reports</span>
            </button>
        @endif
    </div>
</div>

<!-- Recent Bookings -->
<div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
        @if(Route::has('staff.bookings'))
            <a href="{{ route('staff.bookings') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">View All →</a>
        @else
            <a href="/staff/bookings" class="text-sm text-red-600 hover:text-red-700 font-medium">View All →</a>
        @endif
    </div>
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                <a href="{{ route('staff.bookings') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">View All →</a>
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
                <!-- Populated by JavaScript -->
            </div>
        </div>

        <!-- Vehicle Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Vehicle Status</h3>
                <button onclick="refreshVehicleStatus()" class="text-sm text-red-600 hover:text-red-700 font-medium">Refresh</button>
            </div>
            <div class="space-y-4" id="vehicle-status">
                <!-- Populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let dashboardData = null;

    // Fetch dashboard data from API
    async function fetchDashboardData() {
        try {
            const data = await apiRequest('/api/staff/dashboard/data');
            dashboardData = data;
            updateDashboard(data);
            return data;
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
        }
    }

    // Update all dashboard components
    function updateDashboard(data) {
        if (!data) return;
        
        // Update stats
        updateStats(data.stats);
        
        // Update recent bookings
        updateRecentBookings(data.recentBookings);
        
        // Update today's schedule
        updateTodaySchedule(data.schedule);
        
        // Update vehicle status
        updateVehicleStatus(data.vehicleStatus);
        
        lucide.createIcons();
    }

    // Update statistics cards
    function updateStats(stats) {
        document.getElementById('stat-today-bookings').textContent = stats.todayBookings || 0;
        document.getElementById('stat-active-bookings').textContent = stats.activeBookings || 0;
        document.getElementById('stat-pending').textContent = stats.pendingApprovals || 0;
        document.getElementById('stat-revenue').textContent = formatCurrency(stats.monthRevenue || 0);
        
        // Update change indicators
        const todayChange = stats.todayBookingsChange || 0;
        document.getElementById('stat-today-change').textContent = 
            `${todayChange > 0 ? '↑' : todayChange < 0 ? '↓' : ''} ${Math.abs(todayChange)}% from yesterday`;
        document.getElementById('stat-today-change').className = 
            `text-xs mt-2 ${todayChange >= 0 ? 'text-green-600' : 'text-red-600'}`;
            
        const revenueChange = stats.revenueChange || 0;
        document.getElementById('stat-revenue-change').textContent = 
            `${revenueChange > 0 ? '↑' : revenueChange < 0 ? '↓' : ''} ${Math.abs(revenueChange)}% from last month`;
        document.getElementById('stat-revenue-change').className = 
            `text-xs mt-2 ${revenueChange >= 0 ? 'text-green-600' : 'text-red-600'}`;
    }

    // Update recent bookings list
    function updateRecentBookings(bookings) {
        const container = document.getElementById('recent-bookings');
        
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
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">${booking.booking_code}</p>
                        <p class="text-sm text-gray-600">${booking.customer_name} • ${booking.vehicle_name}</p>
                    </div>
                </div>
                <div class="text-right">
                    ${getStatusBadge(booking.status)}
                    <p class="text-sm text-gray-600 mt-1">${formatCurrency(booking.total_amount)}</p>
                </div>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    // Update today's schedule
    function updateTodaySchedule(schedule) {
        const container = document.getElementById('today-schedule');
        const countElement = document.getElementById('schedule-count');
        
        if (!schedule || schedule.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i data-lucide="check-circle" class="w-12 h-12 text-green-400 mx-auto mb-2"></i>
                    <p class="text-gray-500">No scheduled tasks for today</p>
                </div>
            `;
            countElement.textContent = '0 tasks';
            return;
        }
        
        countElement.textContent = `${schedule.length} tasks`;
        
        container.innerHTML = schedule.map(task => `
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 ${task.completed ? 'bg-green-500' : 'bg-orange-500'} rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">${task.title}</p>
                    <p class="text-sm text-gray-600">${task.time} • ${task.type}</p>
                </div>
                ${!task.completed ? `
                    <button onclick="completeTask('${task.id}')" 
                            class="text-green-600 hover:text-green-700">
                        <i data-lucide="check" class="w-5 h-5"></i>
                    </button>
                ` : `
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                `}
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    // Update vehicle status
    function updateVehicleStatus(vehicles) {
        const container = document.getElementById('vehicle-status');
        
        if (!vehicles) return;
        
        const total = vehicles.total || 0;
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
        
        lucide.createIcons();
    }

    // Get status badge HTML
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Pending</span>',
            'confirmed': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Confirmed</span>',
            'approved': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Approved</span>',
            'active': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Active</span>',
            'completed': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Cancelled</span>'
        };
        return badges[status.toLowerCase()] || '';
    }

    // Refresh dashboard manually
    async function refreshDashboard() {
        showToast('Refreshing dashboard...', 'info');
        await fetchDashboardData();
        showToast('Dashboard refreshed successfully!', 'success');
    }

    // Refresh vehicle status
    async function refreshVehicleStatus() {
        showToast('Refreshing vehicle status...', 'info');
        await fetchDashboardData();
        showToast('Vehicle status updated!', 'success');
    }

    // Complete a task
    async function completeTask(taskId) {
        if (confirm('Mark this task as completed?')) {
            showToast('Task marked as completed!', 'success');
            await fetchDashboardData();
        }
    }

    // Initialize dashboard with real-time updates
    document.addEventListener('DOMContentLoaded', () => {
        // Initial load
        fetchDashboardData();
        
        // Start real-time updates (every 30 seconds)
        startRealTimeUpdates(fetchDashboardData, 30000);
    });
</script>
@endpush

@endsection