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
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle</label>
                <input type="text" id="vehicle-filter" placeholder="Filter by vehicle..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                       onkeyup="debouncedFilterBookings()">
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

    // Transform backend data to match frontend expectations
    function transformBookingData(bookings) {
        return bookings.map(booking => ({
            id: booking.booking_id,
            booking_code: booking.booking_id,
            customer_name: booking.customer?.name || 'N/A',
            customer_phone: booking.customer?.phone_no || 'N/A',
            customer_email: booking.customer?.email || 'N/A',
            customer_ic: booking.customer?.ic_no || booking.customer?.nric || 'N/A',
            vehicle_name: booking.vehicle?.name || 'N/A',
            vehicle_plate: booking.vehicle?.plate_no || 'N/A',
            vehicle_category: booking.vehicle?.category || 'Standard',
            start_date: booking.pickup_date,
            end_date: booking.return_date,
            status: booking.booking_status,
            total_amount: booking.total_price || 0,
            daily_rate: booking.vehicle?.price_perHour || 0,
            duration_days: calculateDays(booking.pickup_date, booking.return_date),
            delivery_fee: 0,
            is_urgent: false
        }));
    }

    function calculateDays(start, end) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays || 1;
    }

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
            console.log('Raw API response:', data); // Debug log
            
            if (data.success && data.bookings && Array.isArray(data.bookings)) {
                allBookings = transformBookingData(data.bookings);
                console.log('Transformed bookings:', allBookings); // Debug log
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

    function sortBookingsData() {
        allBookings.sort((a, b) => {
            let aVal = a[sortField];
            let bVal = b[sortField];
            
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

    function sortBookings(field) {
        if (sortField === field) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortField = field;
            sortDirection = 'asc';
        }
        
        sortBookingsData();
        filterBookings();
        
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

    let filterTimeout;
    function debouncedFilterBookings() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterBookings, 300);
    }

    function filterBookings() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const vehicleFilter = document.getElementById('vehicle-filter').value.toLowerCase();
        const dateFilter = document.getElementById('date-filter').value;
        
        filteredBookings = allBookings.filter(booking => {
            const matchesSearch = !searchTerm || 
                booking.booking_code.toLowerCase().includes(searchTerm) ||
                booking.customer_name.toLowerCase().includes(searchTerm) ||
                booking.vehicle_name.toLowerCase().includes(searchTerm) ||
                (booking.customer_phone && booking.customer_phone.includes(searchTerm));
            
            const matchesStatus = !statusFilter || booking.status === statusFilter;
            const matchesVehicle = !vehicleFilter || booking.vehicle_name.toLowerCase().includes(vehicleFilter);
            
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
            
            return matchesSearch && matchesStatus && matchesVehicle && matchesDate;
        });
        
        currentPage = 1;
        updateBookingsDisplay();
    }

    function getPaginatedBookings() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        return filteredBookings.slice(startIndex, endIndex);
    }

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
        
        resultsCount.textContent = `Showing ${filteredBookings.length} booking${filteredBookings.length !== 1 ? 's' : ''}`;
        
        tbody.innerHTML = paginatedBookings.map(booking => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono font-semibold text-gray-800">${escapeHtml(booking.booking_code)}</span>
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
                    <span class="text-gray-400">${booking.duration_days} day${booking.duration_days !== 1 ? 's' : ''}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getStatusBadge(booking.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-semibold text-gray-800">${formatCurrency(booking.total_amount)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-1">
                        <a href="/staff/bookings/${booking.id}" 
                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                           title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `).join('');
        
        updatePagination();
        pagination.classList.remove('hidden');
        
        lucide.createIcons();
    }

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

    function updatePagination() {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);
        
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(currentPage * itemsPerPage, filteredBookings.length);
        
        document.getElementById('page-start').textContent = start;
        document.getElementById('page-end').textContent = end;
        document.getElementById('total-count').textContent = filteredBookings.length;
        
        const controls = document.getElementById('pagination-controls');
        let html = '';
        
        if (currentPage > 1) {
            html += `
                <button onclick="changePage(${currentPage - 1})" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
            `;
        }
        
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

    function changePage(page) {
        currentPage = page;
        updateBookingsDisplay();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function exportBookings() {
        showToast('Preparing export...', 'info');
        // Implement export functionality
        console.log('Export bookings');
    }

    function refreshBookings() {
        showToast('Refreshing bookings...', 'info');
        fetchBookings();
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Pending</span>',
            'confirmed': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Confirmed</span>',
            'active': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Active</span>',
            'completed': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Cancelled</span>'
        };
        return badges[status?.toLowerCase()] || '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Unknown</span>';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-MY', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
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
        console.log(`[${type.toUpperCase()}] ${message}`);
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchBookings();
        setInterval(fetchBookings, 30000);
    });
</script>
@endpush

@endsection