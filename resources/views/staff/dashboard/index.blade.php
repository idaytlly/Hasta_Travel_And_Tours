@extends('staff.layouts.app')

@section('title', 'Dashboard - Staff Portal')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Bookings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's Bookings</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="stat-today-bookings">0</p>
                    <p class="text-xs text-green-600 mt-2">↑ 3 from yesterday</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="calendar" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
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

        <!-- Active Rentals -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Rentals</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="stat-active">0</p>
                    <p class="text-xs text-blue-600 mt-2">Currently ongoing</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="car" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Revenue (This Month) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Revenue (Month)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="stat-revenue">RM 0</p>
                    <p class="text-xs text-green-600 mt-2">↑ 12% from last month</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-8 h-8 text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Runner Commission Card (Only visible for runners) -->
    <div id="runner-commission-card" class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-lg p-6 text-white hidden">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Your Commission (This Month)</h3>
                <p class="text-4xl font-bold mt-2">RM <span id="runner-commission">380.00</span></p>
                <p class="text-sm text-red-100 mt-2">5 deliveries completed • 2 pending</p>
            </div>
            <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                <i data-lucide="trending-up" class="w-12 h-12"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                <a href="{{ route('staff.bookings.index') }}" class="text-sm text-red-600 hover:text-red-700 flex items-center gap-1">
                    View All
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div id="recent-bookings-list" class="divide-y divide-gray-200">
                <!-- Loading state -->
                <div class="p-8 text-center">
                    <div class="spinner"></div>
                    <p class="text-gray-500 mt-4">Loading bookings...</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Alerts -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button onclick="openNewRentalModal()" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        <span>New Rental</span>
                    </button>
                    <button onclick="viewPendingApprovals()" class="w-full px-4 py-3 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Approve Bookings</span>
                    </button>
                    <button onclick="window.location.href='{{ route('staff.vehicles.index') }}'" class="w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition flex items-center justify-center gap-2">
                        <i data-lucide="car" class="w-5 h-5"></i>
                        <span>Manage Vehicles</span>
                    </button>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Schedule</h3>
                <div id="today-schedule" class="space-y-3">
                    <div class="text-center text-gray-500 py-4">
                        <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                        <p class="text-sm">No scheduled tasks</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Status</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Available</span>
                        <span class="text-sm font-semibold text-green-600" id="vehicles-available">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Rented</span>
                        <span class="text-sm font-semibold text-blue-600" id="vehicles-rented">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Maintenance</span>
                        <span class="text-sm font-semibold text-orange-600" id="vehicles-maintenance">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Sample data for demonstration
    const dashboardData = {
        stats: {
            todayBookings: 8,
            pendingApprovals: 3,
            activeRentals: 24,
            totalRevenue: 15680,
            availableVehicles: 12,
            rentedVehicles: 18,
            maintenanceVehicles: 2
        },
        recentBookings: [
            {
                id: 'BK001',
                customer: 'Ahmad Ibrahim',
                vehicle: 'Perodua Myvi',
                pickup: '2026-01-10 10:00',
                return: '2026-01-12 10:00',
                status: 'pending',
                total: 280,
                type: 'self-pickup'
            },
            {
                id: 'BK002',
                customer: 'Sarah Lee',
                vehicle: 'Honda City',
                pickup: '2026-01-10 14:00',
                return: '2026-01-15 14:00',
                status: 'confirmed',
                total: 750,
                type: 'delivery'
            },
            {
                id: 'BK003',
                customer: 'Michael Tan',
                vehicle: 'Toyota Vios',
                pickup: '2026-01-11 09:00',
                return: '2026-01-11 18:00',
                status: 'pending',
                total: 120,
                type: 'self-pickup'
            },
            {
                id: 'BK004',
                customer: 'Lisa Wong',
                vehicle: 'Perodua Axia',
                pickup: '2026-01-10 16:00',
                return: '2026-01-13 16:00',
                status: 'approved',
                total: 360,
                type: 'delivery'
            },
            {
                id: 'BK005',
                customer: 'David Chen',
                vehicle: 'Honda Civic',
                pickup: '2026-01-12 08:00',
                return: '2026-01-14 08:00',
                status: 'active',
                total: 480,
                type: 'self-pickup'
            }
        ],
        todaySchedule: [
            {
                time: '10:00 AM',
                task: 'Vehicle Pickup - Ahmad Ibrahim',
                type: 'pickup',
                vehicle: 'Perodua Myvi'
            },
            {
                time: '02:00 PM',
                task: 'Delivery - Sarah Lee',
                type: 'delivery',
                vehicle: 'Honda City',
                location: 'Jalan Ampang, KL'
            }
        ]
    };

    // Load dashboard data
    function loadDashboardData() {
        // Update stats
        document.getElementById('stat-today-bookings').textContent = dashboardData.stats.todayBookings;
        document.getElementById('stat-pending').textContent = dashboardData.stats.pendingApprovals;
        document.getElementById('stat-active').textContent = dashboardData.stats.activeRentals;
        document.getElementById('stat-revenue').textContent = formatCurrency(dashboardData.stats.totalRevenue);
        
        // Update vehicle status
        document.getElementById('vehicles-available').textContent = dashboardData.stats.availableVehicles;
        document.getElementById('vehicles-rented').textContent = dashboardData.stats.rentedVehicles;
        document.getElementById('vehicles-maintenance').textContent = dashboardData.stats.maintenanceVehicles;

        // Load recent bookings
        loadRecentBookings();
        
        // Load today's schedule
        loadTodaySchedule();

        // Show runner commission if role is runner
        const staffRole = localStorage.getItem('staff_role') || 'staff';
        if (staffRole === 'runner') {
            document.getElementById('runner-commission-card').classList.remove('hidden');
        }
    }

    function loadRecentBookings() {
        const container = document.getElementById('recent-bookings-list');
        
        if (dashboardData.recentBookings.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                    <p>No recent bookings</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        container.innerHTML = dashboardData.recentBookings.map(booking => `
            <div class="p-4 hover:bg-gray-50 cursor-pointer" onclick="viewBookingDetails('${booking.id}')">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-semibold text-gray-800">${booking.customer}</h4>
                            ${getStatusBadge(booking.status)}
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            <i data-lucide="car" class="w-4 h-4 inline"></i>
                            ${booking.vehicle}
                        </p>
                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                            <span>
                                <i data-lucide="calendar" class="w-3 h-3 inline"></i>
                                ${formatDateTime(booking.pickup)}
                            </span>
                            <span>
                                <i data-lucide="${booking.type === 'delivery' ? 'truck' : 'map-pin'}" class="w-3 h-3 inline"></i>
                                ${booking.type === 'delivery' ? 'Delivery' : 'Self Pickup'}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">${formatCurrency(booking.total)}</p>
                        <p class="text-xs text-gray-500 mt-1">${booking.id}</p>
                    </div>
                </div>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    function loadTodaySchedule() {
        const container = document.getElementById('today-schedule');
        
        if (dashboardData.todaySchedule.length === 0) {
            container.innerHTML = `
                <div class="text-center text-gray-500 py-4">
                    <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                    <p class="text-sm">No scheduled tasks</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        container.innerHTML = dashboardData.todaySchedule.map(item => `
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0 ${item.type === 'delivery' ? 'bg-red-100' : 'bg-blue-100'} p-2 rounded-lg">
                    <i data-lucide="${item.type === 'delivery' ? 'truck' : 'calendar-check'}" 
                       class="w-4 h-4 ${item.type === 'delivery' ? 'text-red-600' : 'text-blue-600'}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">${item.time}</p>
                    <p class="text-sm text-gray-600 mt-1">${item.task}</p>
                    ${item.location ? `<p class="text-xs text-gray-500 mt-1">${item.location}</p>` : ''}
                </div>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">Pending</span>',
            'confirmed': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Confirmed</span>',
            'approved': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Approved</span>',
            'active': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Active</span>',
            'completed': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Cancelled</span>'
        };
        return badges[status] || '';
    }

    function viewBookingDetails(bookingId) {
        window.location.href = `{{ url('/staff/bookings') }}?id=${bookingId}`;
    }

    function viewPendingApprovals() {
        window.location.href = `{{ route('staff.bookings.index') }}?status=pending`;
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', () => {
        loadDashboardData();
        
        // Refresh data every 30 seconds
        setInterval(loadDashboardData, 30000);
    });
</script>
@endpush

@endsection