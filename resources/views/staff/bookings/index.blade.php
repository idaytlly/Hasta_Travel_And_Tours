@extends('staff.layouts.app')

@section('title', 'Bookings Management - Staff Portal')
@section('page-title', 'Bookings Management')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">All Bookings</h2>
                <p class="text-sm text-gray-600 mt-1">Manage and track all customer bookings</p>
            </div>
            <button onclick="refreshBookings()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Refresh</span>
            </button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" placeholder="Search bookings..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onkeyup="filterBookings()">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterBookings()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="approved">Approved</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select id="type-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterBookings()">
                    <option value="">All Types</option>
                    <option value="delivery">Delivery</option>
                    <option value="self-pickup">Self Pickup</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select id="date-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterBookings()">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="total-bookings">0</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Approval</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1" id="pending-bookings">0</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Rentals</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="active-bookings">0</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="activity" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="completed-bookings">0</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Booking List</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="bookings-table" class="divide-y divide-gray-200">
                    <!-- Loading state -->
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-200 text-center" id="loading-indicator">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 text-sm mt-2">Loading bookings...</p>
        </div>
        
        <div class="p-4 border-t border-gray-200 hidden" id="no-results">
            <div class="text-center py-8">
                <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-500 text-lg">No bookings found</p>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">Booking Details</h3>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div id="booking-details-content" class="p-6">
            <!-- Content populated by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allBookings = [];
    let filteredBookings = [];

    // Fetch bookings from API
    async function fetchBookings() {
        try {
            const data = await apiRequest('/api/staff/bookings');
            allBookings = data.bookings || [];
            filteredBookings = [...allBookings];
            updateBookingsDisplay();
            updateStats();
            return data;
        } catch (error) {
            console.error('Error fetching bookings:', error);
            document.getElementById('loading-indicator').style.display = 'none';
            document.getElementById('no-results').classList.remove('hidden');
        }
    }

    // Update bookings table
    function updateBookingsDisplay() {
        const tbody = document.getElementById('bookings-table');
        const loading = document.getElementById('loading-indicator');
        const noResults = document.getElementById('no-results');
        
        loading.style.display = 'none';
        
        if (filteredBookings.length === 0) {
            tbody.innerHTML = '';
            noResults.classList.remove('hidden');
            return;
        }
        
        noResults.classList.add('hidden');
        
        tbody.innerHTML = filteredBookings.map(booking => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono font-semibold text-gray-800">${booking.booking_code}</span>
                </td>
                <td class="px-6 py-4">
                    <div>
                        <p class="font-medium text-gray-800">${booking.customer_name}</p>
                        <p class="text-sm text-gray-500">${booking.customer_phone}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">${booking.vehicle_name}</p>
                    <p class="text-sm text-gray-500">${booking.vehicle_plate}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${formatDate(booking.start_date)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${booking.pickup_type === 'delivery' 
                        ? '<span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium"><i data-lucide="truck" class="w-3 h-3"></i>Delivery</span>'
                        : '<span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium"><i data-lucide="map-pin" class="w-3 h-3"></i>Self Pickup</span>'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getStatusBadge(booking.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-semibold text-gray-800">${formatCurrency(booking.total_amount)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <button onclick="viewBooking('${booking.id}')" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                        ${booking.status === 'pending' ? `
                            <button onclick="approveBooking('${booking.id}')" 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                    title="Approve">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                            <button onclick="cancelBooking('${booking.id}')" 
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Cancel">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        ` : ''}
                        ${booking.status === 'confirmed' && '{{ Auth::guard("staff")->user()->role }}' === 'admin' ? `
                            <button onclick="verifyPayment('${booking.id}')" 
                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                    title="Verify Payment (Admin Only)">
                                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                            </button>
                        ` : ''}
                        ${booking.status === 'payment_verified' ? `
                            <button onclick="verifyBooking('${booking.id}')" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                    title="Verify Booking (Complete)">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
        
        lucide.createIcons();
    }

    // Update statistics
    function updateStats() {
        document.getElementById('total-bookings').textContent = allBookings.length;
        document.getElementById('pending-bookings').textContent = 
            allBookings.filter(b => b.status === 'pending').length;
        document.getElementById('active-bookings').textContent = 
            allBookings.filter(b => b.status === 'active').length;
        document.getElementById('completed-bookings').textContent = 
            allBookings.filter(b => b.status === 'completed').length;
    }

    // Filter bookings
    function filterBookings() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const typeFilter = document.getElementById('type-filter').value;
        const dateFilter = document.getElementById('date-filter').value;
        
        filteredBookings = allBookings.filter(booking => {
            const matchesSearch = !searchTerm || 
                booking.booking_code.toLowerCase().includes(searchTerm) ||
                booking.customer_name.toLowerCase().includes(searchTerm) ||
                booking.vehicle_name.toLowerCase().includes(searchTerm);
            
            const matchesStatus = !statusFilter || booking.status === statusFilter;
            const matchesType = !typeFilter || booking.pickup_type === typeFilter;
            
            let matchesDate = true;
            if (dateFilter) {
                const bookingDate = new Date(booking.start_date);
                const today = new Date();
                
                if (dateFilter === 'today') {
                    matchesDate = bookingDate.toDateString() === today.toDateString();
                } else if (dateFilter === 'week') {
                    const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    matchesDate = bookingDate >= weekAgo;
                } else if (dateFilter === 'month') {
                    matchesDate = bookingDate.getMonth() === today.getMonth() &&
                                 bookingDate.getFullYear() === today.getFullYear();
                }
            }
            
            return matchesSearch && matchesStatus && matchesType && matchesDate;
        });
        
        updateBookingsDisplay();
    }

    // View booking details
    async function viewBooking(bookingId) {
        try {
            const data = await apiRequest(`/api/staff/bookings/${bookingId}`);
            showBookingModal(data.booking);
        } catch (error) {
            showToast('Failed to load booking details', 'error');
        }
    }

    // Show booking modal
    function showBookingModal(booking) {
        const modal = document.getElementById('booking-modal');
        const content = document.getElementById('booking-details-content');
        
        content.innerHTML = `
            <div class="space-y-6">
                <!-- Status -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <div class="mt-1">${getStatusBadge(booking.status)}</div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Booking Code</p>
                        <p class="font-mono font-bold text-gray-800 mt-1">${booking.booking_code}</p>
                    </div>
                </div>

                <!-- Customer Info -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Customer Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-medium text-gray-800">${booking.customer_name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="font-medium text-gray-800">${booking.customer_phone}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-800">${booking.customer_email}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">IC Number</p>
                            <p class="font-medium text-gray-800">${booking.customer_ic}</p>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Info -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Vehicle Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Vehicle</p>
                            <p class="font-medium text-gray-800">${booking.vehicle_name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Plate Number</p>
                            <p class="font-medium text-gray-800">${booking.vehicle_plate}</p>
                        </div>
                    </div>
                </div>

                <!-- Rental Period -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Rental Period</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Start Date</p>
                            <p class="font-medium text-gray-800">${formatDateTime(booking.start_date)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">End Date</p>
                            <p class="font-medium text-gray-800">${formatDateTime(booking.end_date)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Duration</p>
                            <p class="font-medium text-gray-800">${booking.duration_days} days</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pickup Type</p>
                            <p class="font-medium text-gray-800">${booking.pickup_type === 'delivery' ? 'Delivery' : 'Self Pickup'}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Payment Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Daily Rate</span>
                            <span class="font-medium">${formatCurrency(booking.daily_rate)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium">${booking.duration_days} days</span>
                        </div>
                        ${booking.delivery_fee > 0 ? `
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span class="font-medium">${formatCurrency(booking.delivery_fee)}</span>
                            </div>
                        ` : ''}
                        <div class="border-t pt-2 flex justify-between">
                            <span class="font-semibold text-gray-800">Total Amount</span>
                            <span class="font-bold text-red-600 text-lg">${formatCurrency(booking.total_amount)}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                ${booking.status === 'pending' ? `
                    <div class="flex gap-3">
                        <button onclick="approveBookingFromModal('${booking.id}')" 
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Approve Booking
                        </button>
                        <button onclick="cancelBookingFromModal('${booking.id}')" 
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Cancel Booking
                        </button>
                    </div>
                ` : ''}
                ${booking.status === 'confirmed' && '{{ Auth::guard("staff")->user()->role }}' === 'admin' ? `
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-orange-800 font-medium mb-2">⚠️ Payment Verification Required</p>
                        <p class="text-sm text-orange-700">Only administrators can verify payments. After payment is verified, staff can complete the booking verification.</p>
                    </div>
                    <button onclick="verifyPaymentFromModal('${booking.id}')" 
                            class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Verify Payment (Admin Only)
                    </button>
                ` : ''}
                ${booking.status === 'confirmed' && '{{ Auth::guard("staff")->user()->role }}' !== 'admin' ? `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800 font-medium mb-2">ℹ️ Waiting for Payment Verification</p>
                        <p class="text-sm text-blue-700">An administrator needs to verify the payment before you can complete this booking.</p>
                    </div>
                ` : ''}
                ${booking.status === 'payment_verified' ? `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-green-800 font-medium mb-2">✓ Payment Verified by Admin</p>
                        <p class="text-sm text-green-700">You can now complete the booking verification.</p>
                    </div>
                    <button onclick="verifyBookingFromModal('${booking.id}')" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Complete Booking Verification
                    </button>
                ` : ''}
            </div>
        `;
        
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    // Close booking modal
    function closeBookingModal() {
        document.getElementById('booking-modal').classList.add('hidden');
    }

    // Approve booking
    async function approveBooking(bookingId) {
        if (confirm('Approve this booking?')) {
            try {
                await apiRequest(`/api/staff/bookings/${bookingId}/approve`, { method: 'POST' });
                showToast('Booking approved successfully!', 'success');
                await fetchBookings();
            } catch (error) {
                showToast('Failed to approve booking', 'error');
            }
        }
    }

    async function approveBookingFromModal(bookingId) {
        await approveBooking(bookingId);
        closeBookingModal();
    }

    // Verify payment (Admin only)
    async function verifyPayment(bookingId) {
        if (confirm('Verify that payment has been received for this booking?')) {
            try {
                await apiRequest(`/api/staff/bookings/${bookingId}/verify-payment`, { method: 'POST' });
                showToast('Payment verified successfully! Booking can now be completed by staff.', 'success');
                await fetchBookings();
            } catch (error) {
                showToast('Failed to verify payment', 'error');
            }
        }
    }

    async function verifyPaymentFromModal(bookingId) {
        await verifyPayment(bookingId);
        closeBookingModal();
    }

    // Verify booking (After payment verified)
    async function verifyBooking(bookingId) {
        if (confirm('Complete booking verification? The vehicle will be marked as ready for pickup.')) {
            try {
                await apiRequest(`/api/staff/bookings/${bookingId}/verify`, { method: 'POST' });
                showToast('Booking verification completed successfully!', 'success');
                await fetchBookings();
            } catch (error) {
                showToast('Failed to verify booking', 'error');
            }
        }
    }

    async function verifyBookingFromModal(bookingId) {
        await verifyBooking(bookingId);
        closeBookingModal();
    }

    // Cancel booking
    async function cancelBooking(bookingId) {
        if (confirm('Are you sure you want to cancel this booking?')) {
            try {
                await apiRequest(`/api/staff/bookings/${bookingId}/cancel`, { method: 'POST' });
                showToast('Booking cancelled successfully!', 'success');
                await fetchBookings();
            } catch (error) {
                showToast('Failed to cancel booking', 'error');
            }
        }
    }

    async function cancelBookingFromModal(bookingId) {
        await cancelBooking(bookingId);
        closeBookingModal();
    }

    // Refresh bookings
    async function refreshBookings() {
        showToast('Refreshing bookings...', 'info');
        await fetchBookings();
        showToast('Bookings refreshed successfully!', 'success');
    }

    // Get status badge
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Pending</span>',
            'confirmed': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Confirmed</span>',
            'payment_verified': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Payment Verified</span>',
            'approved': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Approved</span>',
            'active': '<span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">Active</span>',
            'completed': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Cancelled</span>'
        };
        return badges[status.toLowerCase()] || '';
    }

    // Initialize with real-time updates
    document.addEventListener('DOMContentLoaded', () => {
        fetchBookings();
        startRealTimeUpdates(fetchBookings, 30000);
    });

    // Close modal on backdrop click
    document.getElementById('booking-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });
</script>
@endpush

@endsection