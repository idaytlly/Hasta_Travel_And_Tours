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
                           onkeyup="debouncedFilterBookings()">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterBookings()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="payment_verified">Payment Verified</option>
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
                    <option value="upcoming">Upcoming</option>
                    <option value="past">Past Bookings</option>
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
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Booking List</h3>
                <p class="text-sm text-gray-500 mt-1" id="results-count">Showing 0 bookings</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="exportBookings()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2 text-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer" onclick="sortBookings('booking_code')">
                            <div class="flex items-center gap-1">
                                Booking ID
                                <i data-lucide="chevron-up-down" class="w-3 h-3"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer" onclick="sortBookings('customer_name')">
                            <div class="flex items-center gap-1">
                                Customer
                                <i data-lucide="chevron-up-down" class="w-3 h-3"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer" onclick="sortBookings('vehicle_name')">
                            <div class="flex items-center gap-1">
                                Vehicle
                                <i data-lucide="chevron-up-down" class="w-3 h-3"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer" onclick="sortBookings('start_date')">
                            <div class="flex items-center gap-1">
                                Date
                                <i data-lucide="chevron-up-down" class="w-3 h-3"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer" onclick="sortBookings('status')">
                            <div class="flex items-center gap-1">
                                Status
                                <i data-lucide="chevron-up-down" class="w-3 h-3"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer" onclick="sortBookings('total_amount')">
                            <div class="flex items-center gap-1">
                                Amount
                                <i data-lucide="chevron-up-down" class="w-3 h-3"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="bookings-table" class="divide-y divide-gray-200">
                    <!-- Loading state -->
                </tbody>
            </table>
        </div>
        
        <div class="p-8 text-center" id="loading-indicator">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 text-sm mt-2">Loading bookings...</p>
        </div>
        
        <div class="p-8 text-center hidden" id="no-results">
            <div class="inline-block p-4">
                <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-500 text-lg font-medium mb-2">No bookings found</p>
                <p class="text-gray-400 text-sm">Try adjusting your filters</p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-200 hidden" id="pagination-container">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600" id="pagination-info">
                    Showing <span id="page-start">0</span> to <span id="page-end">0</span> of <span id="total-count">0</span> bookings
                </div>
                <div class="flex items-center gap-2" id="pagination-controls">
                    <!-- Pagination buttons will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white p-6 border-b border-gray-200 flex items-center justify-between z-10">
            <h3 class="text-xl font-bold text-gray-800">Booking Details</h3>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div id="booking-details-content" class="p-6">
            <!-- Content populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Action Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
            <h3 id="confirmation-title" class="text-lg font-bold text-gray-800"></h3>
            <p id="confirmation-message" class="text-gray-600 mt-1"></p>
        </div>
        <div class="p-6 flex items-center justify-end gap-3">
            <button onclick="closeConfirmationModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                Cancel
            </button>
            <button id="confirm-action-btn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                Confirm
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #dc2626;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .sort-asc i { transform: rotate(180deg); }
    .sort-desc i { transform: rotate(0deg); }
</style>
@endpush

@push('scripts')
<script>
    let allBookings = [];
    let filteredBookings = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let sortField = 'start_date';
    let sortDirection = 'desc';
    let currentAction = null;
    let currentBookingId = null;

    // Fetch bookings from API
    async function fetchBookings() {
        try {
            showLoadingState();
            
            const response = await fetch('/api/staff/bookings', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success && data.bookings && Array.isArray(data.bookings)) {
                allBookings = data.bookings;
                filteredBookings = [...allBookings];
                sortBookingsData();
                updateBookingsDisplay();
                updateStats();
            } else {
                throw new Error('Invalid data format received');
            }
            
        } catch (error) {
            console.error('Error fetching bookings:', error);
            showErrorState();
            showToast('Failed to load bookings. Please try again.', 'error');
        }
    }

    // Add this helper function if you don't have it
    function showLoadingState() {
        const loading = document.getElementById('loading-indicator');
        const noResults = document.getElementById('no-results');
        const tbody = document.getElementById('bookings-table');
        const pagination = document.getElementById('pagination-container');
        
        loading.style.display = 'block';
        noResults.classList.add('hidden');
        pagination.classList.add('hidden');
        tbody.innerHTML = '';
    }
    
    // Show error state
    function showErrorState() {
        const loading = document.getElementById('loading-indicator');
        const noResults = document.getElementById('no-results');
        const tbody = document.getElementById('bookings-table');
        const pagination = document.getElementById('pagination-container');
        
        loading.style.display = 'none';
        pagination.classList.add('hidden');
        tbody.innerHTML = '';
        
        noResults.innerHTML = `
            <div class="inline-block p-4">
                <i data-lucide="alert-circle" class="w-16 h-16 text-red-400 mx-auto mb-4"></i>
                <p class="text-red-500 text-lg font-medium mb-2">Failed to load bookings</p>
                <p class="text-gray-400 text-sm">Please check your connection and try refreshing the page</p>
            </div>
        `;
        
        noResults.classList.remove('hidden');
        lucide.createIcons();
    }


    // Sort bookings data
    function sortBookingsData() {
        allBookings.sort((a, b) => {
            let aVal = a[sortField];
            let bVal = b[sortField];
            
            // Handle different data types
            if (sortField.includes('date')) {
                aVal = new Date(aVal);
                bVal = new Date(bVal);
            }
            
            if (sortField === 'total_amount') {
                aVal = parseFloat(aVal);
                bVal = parseFloat(bVal);
            }
            
            if (sortDirection === 'asc') {
                return aVal > bVal ? 1 : -1;
            } else {
                return aVal < bVal ? 1 : -1;
            }
        });
    }

    // Sort bookings when header clicked
    function sortBookings(field) {
        if (sortField === field) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortField = field;
            sortDirection = 'asc';
        }
        
        sortBookingsData();
        filterBookings();
        
        // Update sort indicators
        document.querySelectorAll('thead th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
            const icon = th.querySelector('i');
            if (icon) {
                icon.style.transform = '';
            }
        });
        
        const currentTh = document.querySelector(`th[onclick*="${field}"]`);
        if (currentTh) {
            currentTh.classList.add(sortDirection === 'asc' ? 'sort-asc' : 'sort-desc');
            const icon = currentTh.querySelector('i');
            if (icon) {
                icon.style.transform = sortDirection === 'asc' ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        }
    }

    // Debounced filter for search input
    let filterTimeout;
    function debouncedFilterBookings() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterBookings, 300);
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
                booking.vehicle_name.toLowerCase().includes(searchTerm) ||
                (booking.customer_phone && booking.customer_phone.includes(searchTerm));
            
            const matchesStatus = !statusFilter || booking.status === statusFilter;
            const matchesType = !typeFilter || booking.pickup_type === typeFilter;
            
            let matchesDate = true;
            if (dateFilter) {
                const bookingDate = new Date(booking.start_date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (dateFilter === 'today') {
                    matchesDate = bookingDate.toDateString() === today.toDateString();
                } else if (dateFilter === 'week') {
                    const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    matchesDate = bookingDate >= weekAgo;
                } else if (dateFilter === 'month') {
                    matchesDate = bookingDate.getMonth() === today.getMonth() &&
                                 bookingDate.getFullYear() === today.getFullYear();
                } else if (dateFilter === 'upcoming') {
                    matchesDate = bookingDate >= today;
                } else if (dateFilter === 'past') {
                    matchesDate = bookingDate < today;
                }
            }
            
            return matchesSearch && matchesStatus && matchesType && matchesDate;
        });
        
        currentPage = 1;
        updateBookingsDisplay();
    }

    // Get paginated bookings
    function getPaginatedBookings() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        return filteredBookings.slice(startIndex, endIndex);
    }

    // Update bookings table
    function updateBookingsDisplay() {
        const tbody = document.getElementById('bookings-table');
        const loading = document.getElementById('loading-indicator');
        const noResults = document.getElementById('no-results');
        const pagination = document.getElementById('pagination-container');
        const resultsCount = document.getElementById('results-count');
        
        loading.style.display = 'none';
        
        if (filteredBookings.length === 0) {
            tbody.innerHTML = '';
            noResults.classList.remove('hidden');
            pagination.classList.add('hidden');
            resultsCount.textContent = 'No bookings found';
            return;
        }
        
        noResults.classList.add('hidden');
        
        const paginatedBookings = getPaginatedBookings();
        
        // Update results count
        resultsCount.textContent = `Showing ${filteredBookings.length} booking${filteredBookings.length !== 1 ? 's' : ''}`;
        
        // Update table
        tbody.innerHTML = paginatedBookings.map(booking => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono font-semibold text-gray-800">${escapeHtml(booking.booking_code)}</span>
                    ${booking.is_urgent ? '<span class="ml-2 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">URGENT</span>' : ''}
                </td>
                <td class="px-6 py-4">
                    <div>
                        <p class="font-medium text-gray-800">${escapeHtml(booking.customer_name)}</p>
                        <p class="text-sm text-gray-500">${escapeHtml(booking.customer_phone)}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">${escapeHtml(booking.vehicle_name)}</p>
                    <p class="text-sm text-gray-500">${escapeHtml(booking.vehicle_plate)}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${formatDate(booking.start_date)}<br>
                    <span class="text-gray-400">${booking.duration_days} days</span>
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
                    <div class="flex items-center gap-1">
                        <button onclick="viewBooking('${booking.id}')" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                        ${getActionButtons(booking)}
                    </div>
                </td>
            </tr>
        `).join('');
        
        // Update pagination
        updatePagination();
        pagination.classList.remove('hidden');
        
        lucide.createIcons();
    }

    // Get action buttons based on status and user role
    function getActionButtons(booking) {
        const userRole = '{{ Auth::guard("staff")->user()->role }}';
        let buttons = '';
        
        if (booking.status === 'pending') {
            buttons += `
                <button onclick="showConfirmation('approve', '${booking.id}')" 
                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                        title="Approve">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </button>
                <button onclick="showConfirmation('cancel', '${booking.id}')" 
                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                        title="Cancel">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;
        }
        
        if (booking.status === 'confirmed' && userRole === 'admin') {
            buttons += `
                <button onclick="showConfirmation('verifyPayment', '${booking.id}')" 
                        class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                        title="Verify Payment">
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                </button>
            `;
        }
        
        if (booking.status === 'payment_verified') {
            buttons += `
                <button onclick="showConfirmation('verifyBooking', '${booking.id}')" 
                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                        title="Complete Verification">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </button>
            `;
        }
        
        if (booking.status === 'active') {
            buttons += `
                <button onclick="showConfirmation('complete', '${booking.id}')" 
                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                        title="Mark as Complete">
                    <i data-lucide="check-square" class="w-4 h-4"></i>
                </button>
            `;
        }
        
        return buttons;
    }

    // Update statistics
    function updateStats() {
        const total = allBookings.length;
        const pending = allBookings.filter(b => b.status === 'pending').length;
        const active = allBookings.filter(b => b.status === 'active').length;
        const completed = allBookings.filter(b => b.status === 'completed').length;
        
        document.getElementById('total-bookings').textContent = total;
        document.getElementById('pending-bookings').textContent = pending;
        document.getElementById('active-bookings').textContent = active;
        document.getElementById('completed-bookings').textContent = completed;
    }

    // Update pagination
    function updatePagination() {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);
        
        // Update info
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(currentPage * itemsPerPage, filteredBookings.length);
        
        document.getElementById('page-start').textContent = start;
        document.getElementById('page-end').textContent = end;
        document.getElementById('total-count').textContent = filteredBookings.length;
        
        // Update controls
        const controls = document.getElementById('pagination-controls');
        let html = '';
        
        if (currentPage > 1) {
            html += `
                <button onclick="changePage(${currentPage - 1})" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
            `;
        }
        
        // Show page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `
                    <button onclick="changePage(${i})" 
                            class="w-10 h-10 ${i === currentPage ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-gray-100'} rounded-lg">
                        ${i}
                    </button>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<span class="px-2">...</span>`;
            }
        }
        
        if (currentPage < totalPages) {
            html += `
                <button onclick="changePage(${currentPage + 1})" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            `;
        }
        
        controls.innerHTML = html;
        lucide.createIcons();
    }

    // Change page
    function changePage(page) {
        currentPage = page;
        updateBookingsDisplay();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Show confirmation modal
    function showConfirmation(action, bookingId) {
        currentAction = action;
        currentBookingId = bookingId;
        
        const modal = document.getElementById('confirmation-modal');
        const title = document.getElementById('confirmation-title');
        const message = document.getElementById('confirmation-message');
        const button = document.getElementById('confirm-action-btn');
        
        const actions = {
            'approve': {
                title: 'Approve Booking',
                message: 'Are you sure you want to approve this booking?',
                buttonText: 'Approve',
                buttonClass: 'bg-green-600 hover:bg-green-700'
            },
            'cancel': {
                title: 'Cancel Booking',
                message: 'Are you sure you want to cancel this booking? This action cannot be undone.',
                buttonText: 'Cancel Booking',
                buttonClass: 'bg-red-600 hover:bg-red-700'
            },
            'verifyPayment': {
                title: 'Verify Payment',
                message: 'Verify that payment has been received for this booking?',
                buttonText: 'Verify Payment',
                buttonClass: 'bg-purple-600 hover:bg-purple-700'
            },
            'verifyBooking': {
                title: 'Complete Verification',
                message: 'Complete booking verification? The vehicle will be marked as ready for pickup.',
                buttonText: 'Complete Verification',
                buttonClass: 'bg-blue-600 hover:bg-blue-700'
            },
            'complete': {
                title: 'Mark as Complete',
                message: 'Mark this booking as completed? The vehicle will be returned to available status.',
                buttonText: 'Mark Complete',
                buttonClass: 'bg-green-600 hover:bg-green-700'
            }
        };
        
        const config = actions[action] || actions.cancel;
        
        title.textContent = config.title;
        message.textContent = config.message;
        button.textContent = config.buttonText;
        button.className = `px-4 py-2 ${config.buttonClass} text-white rounded-lg hover:${config.buttonClass.replace('600', '700')} transition`;
        
        modal.classList.remove('hidden');
    }

    // Close confirmation modal
    function closeConfirmationModal() {
        document.getElementById('confirmation-modal').classList.add('hidden');
        currentAction = null;
        currentBookingId = null;
    }

    // Confirm action from modal
    async function confirmAction() {
        closeConfirmationModal();
        
        const actions = {
            'approve': approveBooking,
            'cancel': cancelBooking,
            'verifyPayment': verifyPayment,
            'verifyBooking': verifyBooking,
            'complete': completeBooking
        };
        
        if (actions[currentAction] && currentBookingId) {
            await actions[currentAction](currentBookingId);
        }
    }

    // Set up confirmation button
    document.getElementById('confirm-action-btn').onclick = confirmAction;

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
                <!-- Header Info -->
                <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Booking Code</p>
                        <p class="font-mono font-bold text-gray-800 text-lg">${booking.booking_code}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Status</p>
                        <div class="mt-1">${getStatusBadge(booking.status)}</div>
                    </div>
                </div>

                <!-- Customer Section -->
                <div class="border border-gray-200 rounded-lg">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            Customer Information
                        </h4>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Name</label>
                            <p class="font-medium text-gray-800 mt-1">${escapeHtml(booking.customer_name)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Phone</label>
                            <p class="font-medium text-gray-800 mt-1">${escapeHtml(booking.customer_phone)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Email</label>
                            <p class="font-medium text-gray-800 mt-1">${escapeHtml(booking.customer_email)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">IC Number</label>
                            <p class="font-medium text-gray-800 mt-1">${escapeHtml(booking.customer_ic)}</p>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Section -->
                <div class="border border-gray-200 rounded-lg">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i data-lucide="car" class="w-4 h-4"></i>
                            Vehicle Information
                        </h4>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Vehicle</label>
                            <p class="font-medium text-gray-800 mt-1">${escapeHtml(booking.vehicle_name)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Plate Number</label>
                            <p class="font-medium text-gray-800 mt-1">${escapeHtml(booking.vehicle_plate)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Daily Rate</label>
                            <p class="font-medium text-gray-800 mt-1">${formatCurrency(booking.daily_rate)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Category</label>
                            <p class="font-medium text-gray-800 mt-1">${escapeHtml(booking.vehicle_category)}</p>
                        </div>
                    </div>
                </div>

                <!-- Rental Period -->
                <div class="border border-gray-200 rounded-lg">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            Rental Period
                        </h4>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Start Date & Time</label>
                            <p class="font-medium text-gray-800 mt-1">${formatDateTime(booking.start_date)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">End Date & Time</label>
                            <p class="font-medium text-gray-800 mt-1">${formatDateTime(booking.end_date)}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Duration</label>
                            <p class="font-medium text-gray-800 mt-1">${booking.duration_days} days</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Pickup Type</label>
                            <p class="font-medium text-gray-800 mt-1">
                                ${booking.pickup_type === 'delivery' 
                                    ? '<span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium"><i data-lucide="truck" class="w-3 h-3"></i>Delivery</span>'
                                    : '<span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium"><i data-lucide="map-pin" class="w-3 h-3"></i>Self Pickup</span>'}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="border border-gray-200 rounded-lg">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            Payment Details
                        </h4>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Daily Rate × ${booking.duration_days} days</span>
                                <span class="font-medium">${formatCurrency(booking.daily_rate * booking.duration_days)}</span>
                            </div>
                            ${booking.delivery_fee > 0 ? `
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Delivery Fee</span>
                                    <span class="font-medium">${formatCurrency(booking.delivery_fee)}</span>
                                </div>
                            ` : ''}
                            <div class="border-t pt-3 flex justify-between items-center">
                                <span class="font-semibold text-gray-800 text-lg">Total Amount</span>
                                <span class="font-bold text-red-600 text-xl">${formatCurrency(booking.total_amount)}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div id="booking-actions" class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                    ${generateActionButtons(booking)}
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    // Generate action buttons for modal
    function generateActionButtons(booking) {
        const userRole = '{{ Auth::guard("staff")->user()->role }}';
        let buttons = '';
        
        if (booking.status === 'pending') {
            buttons += `
                <button onclick="showConfirmation('approve', '${booking.id}')" 
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Approve Booking
                </button>
                <button onclick="showConfirmation('cancel', '${booking.id}')" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Cancel Booking
                </button>
            `;
        }
        
        if (booking.status === 'confirmed' && userRole === 'admin') {
            buttons += `
                <button onclick="showConfirmation('verifyPayment', '${booking.id}')" 
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2">
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                    Verify Payment (Admin Only)
                </button>
            `;
        }
        
        if (booking.status === 'payment_verified') {
            buttons += `
                <button onclick="showConfirmation('verifyBooking', '${booking.id}')" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Complete Booking Verification
                </button>
            `;
        }
        
        if (booking.status === 'active') {
            buttons += `
                <button onclick="showConfirmation('complete', '${booking.id}')" 
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                    <i data-lucide="check-square" class="w-4 h-4"></i>
                    Mark as Complete
                </button>
            `;
        }
        
        return buttons || '<p class="text-gray-500 text-sm">No actions available for this booking status.</p>';
    }

    // Close booking modal
    function closeBookingModal() {
        document.getElementById('booking-modal').classList.add('hidden');
    }

    // API actions
    async function approveBooking(bookingId) {
        try {
            await apiRequest(`/api/staff/bookings/${bookingId}/approve`, { method: 'POST' });
            showToast('Booking approved successfully!', 'success');
            await fetchBookings();
            closeBookingModal();
        } catch (error) {
            showToast('Failed to approve booking', 'error');
        }
    }

    async function verifyPayment(bookingId) {
        try {
            await apiRequest(`/api/staff/bookings/${bookingId}/verify-payment`, { method: 'POST' });
            showToast('Payment verified successfully! Booking can now be completed by staff.', 'success');
            await fetchBookings();
            closeBookingModal();
        } catch (error) {
            showToast('Failed to verify payment', 'error');
        }
    }

    async function verifyBooking(bookingId) {
        try {
            await apiRequest(`/api/staff/bookings/${bookingId}/verify`, { method: 'POST' });
            showToast('Booking verification completed successfully!', 'success');
            await fetchBookings();
            closeBookingModal();
        } catch (error) {
            showToast('Failed to verify booking', 'error');
        }
    }

    async function cancelBooking(bookingId) {
        try {
            await apiRequest(`/api/staff/bookings/${bookingId}/cancel`, { method: 'POST' });
            showToast('Booking cancelled successfully!', 'success');
            await fetchBookings();
            closeBookingModal();
        } catch (error) {
            showToast('Failed to cancel booking', 'error');
        }
    }

    async function completeBooking(bookingId) {
        try {
            await apiRequest(`/api/staff/bookings/${bookingId}/complete`, { method: 'POST' });
            showToast('Booking marked as completed successfully!', 'success');
            await fetchBookings();
            closeBookingModal();
        } catch (error) {
            showToast('Failed to complete booking', 'error');
        }
    }

    // Export bookings
    async function exportBookings() {
        showToast('Preparing export...', 'info');
        try {
            const response = await fetch('/api/staff/bookings/export');
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `bookings-export-${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                showToast('Export downloaded successfully!', 'success');
            } else {
                throw new Error('Export failed');
            }
        } catch (error) {
            showToast('Failed to export bookings', 'error');
        }
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
        return badges[status.toLowerCase()] || '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Unknown</span>';
    }

    // Utility functions (should be in a shared file)
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-MY', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-MY', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatCurrency(amount) {
        return 'RM ' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showToast(message, type = 'info') {
        // Implement toast notification
        console.log(`[${type.toUpperCase()}] ${message}`);
    }

    // Initialize with real-time updates
    document.addEventListener('DOMContentLoaded', () => {
        fetchBookings();
        
        // Start real-time updates (every 30 seconds)
        setInterval(fetchBookings, 30000);
    });

    // Close modal on backdrop click
    document.getElementById('booking-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });

    document.getElementById('confirmation-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmationModal();
        }
    });
</script>
@endpush

@endsection