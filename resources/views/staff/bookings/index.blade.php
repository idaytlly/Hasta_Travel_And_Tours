@extends('staff.layouts.app')

@section('title', 'Bookings - Staff Portal')
@section('page-title', 'Manage Bookings')

@section('content')
<div class="space-y-6">
    
    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <div class="relative">
                    <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" placeholder="Search by booking ID, customer name, vehicle..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onkeyup="filterBookings()">
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterBookings()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="approved">Approved</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="verified">Verified</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <select id="type-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterBookings()">
                    <option value="">All Types</option>
                    <option value="self-pickup">Self Pickup</option>
                    <option value="delivery">Delivery</option>
                </select>
            </div>

            <!-- Verification Filter -->
            <div>
                <select id="verification-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterBookings()">
                    <option value="">All</option>
                    <option value="verified">Verified Only</option>
                    <option value="not_verified">Not Verified</option>
                </select>
            </div>
        </div>

        <!-- Quick Stats for Bookings -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mt-6 pt-6 border-t border-gray-200">
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-600" id="count-pending">0</p>
                <p class="text-xs text-gray-600 mt-1">Pending</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600" id="count-confirmed">0</p>
                <p class="text-xs text-gray-600 mt-1">Confirmed</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600" id="count-approved">0</p>
                <p class="text-xs text-gray-600 mt-1">Approved</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600" id="count-active">0</p>
                <p class="text-xs text-gray-600 mt-1">Active</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-600" id="count-completed">0</p>
                <p class="text-xs text-gray-600 mt-1">Completed</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600" id="count-verified">0</p>
                <p class="text-xs text-gray-600 mt-1">Verified</p>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pickup</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Return</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Verification</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="bookings-table-body" class="divide-y divide-gray-200">
                    <!-- Loading state -->
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="spinner"></div>
                            <p class="text-gray-500 mt-4">Loading bookings...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Booking Details Modal -->
<div id="booking-details-modal" class="hidden"></div>

@push('scripts')
<script>
    // Updated bookings data with verification info
    let allBookings = [
        { 
            id: 'BK001', 
            customer: 'Ahmad Ibrahim', 
            phone: '+60123456789', 
            vehicle: 'Perodua Myvi', 
            pickup: '2026-01-10 10:00', 
            return: '2026-01-12 10:00', 
            status: 'pending', 
            total: 280, 
            type: 'self-pickup', 
            deposit: 100,
            verified: false,
            verified_by: null,
            verified_at: null
        },
        { 
            id: 'BK002', 
            customer: 'Sarah Lee', 
            phone: '+60198765432', 
            vehicle: 'Honda City', 
            pickup: '2026-01-10 14:00', 
            return: '2026-01-15 14:00', 
            status: 'confirmed', 
            total: 750, 
            type: 'delivery', 
            deliveryAddress: 'Jalan Ampang, KL', 
            deposit: 200,
            verified: true,
            verified_by: 'STAFF001',
            verified_at: '2026-01-10 10:30:00'
        },
        { 
            id: 'BK003', 
            customer: 'Michael Tan', 
            phone: '+60167890123', 
            vehicle: 'Toyota Vios', 
            pickup: '2026-01-11 09:00', 
            return: '2026-01-11 18:00', 
            status: 'pending', 
            total: 120, 
            type: 'self-pickup', 
            deposit: 50,
            verified: false,
            verified_by: null,
            verified_at: null
        },
        { 
            id: 'BK004', 
            customer: 'Lisa Wong', 
            phone: '+60134567890', 
            vehicle: 'Perodua Axia', 
            pickup: '2026-01-10 16:00', 
            return: '2026-01-13 16:00', 
            status: 'approved', 
            total: 360, 
            type: 'delivery', 
            deliveryAddress: 'Subang Jaya', 
            deposit: 100,
            verified: true,
            verified_by: 'ADMIN001',
            verified_at: '2026-01-10 11:45:00'
        },
        { 
            id: 'BK005', 
            customer: 'David Chen', 
            phone: '+60145678901', 
            vehicle: 'Honda Civic', 
            pickup: '2026-01-12 08:00', 
            return: '2026-01-14 08:00', 
            status: 'active', 
            total: 480, 
            type: 'self-pickup', 
            deposit: 150,
            verified: false,
            verified_by: null,
            verified_at: null
        }
    ];

    let filteredBookings = [...allBookings];

    function loadBookings() {
        updateBookingCounts();
        renderBookingsTable();
    }

    function updateBookingCounts() {
        const counts = {
            pending: 0,
            confirmed: 0,
            approved: 0,
            active: 0,
            completed: 0,
            verified: 0
        };

        allBookings.forEach(booking => {
            if (counts.hasOwnProperty(booking.status)) {
                counts[booking.status]++;
            }
            if (booking.verified) {
                counts.verified++;
            }
        });

        document.getElementById('count-pending').textContent = counts.pending;
        document.getElementById('count-confirmed').textContent = counts.confirmed;
        document.getElementById('count-approved').textContent = counts.approved;
        document.getElementById('count-active').textContent = counts.active;
        document.getElementById('count-completed').textContent = counts.completed;
        document.getElementById('count-verified').textContent = counts.verified;
    }

    function renderBookingsTable() {
        const tbody = document.getElementById('bookings-table-body');
        
        if (filteredBookings.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                        <p>No bookings found</p>
                    </td>
                </tr>
            `;
            lucide.createIcons();
            return;
        }

        tbody.innerHTML = filteredBookings.map(booking => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-semibold text-gray-800">${booking.id}</span>
                </td>
                <td class="px-6 py-4">
                    <div>
                        <p class="font-medium text-gray-800">${booking.customer}</p>
                        <p class="text-sm text-gray-500">${booking.phone}</p>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-gray-800">${booking.vehicle}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-600">${formatDateTime(booking.pickup)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-600">${formatDateTime(booking.return)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getStatusBadge(booking.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getVerificationBadge(booking)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-semibold text-gray-800">${formatCurrency(booking.total)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <button onclick="viewBookingDetails('${booking.id}')" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                        ${booking.status === 'pending' ? `
                            <button onclick="approveBooking('${booking.id}')" 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                    title="Approve">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                            </button>
                        ` : ''}
                        ${!booking.verified ? `
                            <button onclick="showVerificationModal('${booking.id}')" 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                    title="Verify Booking">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
        
        lucide.createIcons();
    }

    function filterBookings() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const typeFilter = document.getElementById('type-filter').value;
        const verificationFilter = document.getElementById('verification-filter').value;

        filteredBookings = allBookings.filter(booking => {
            const matchesSearch = !searchTerm || 
                booking.id.toLowerCase().includes(searchTerm) ||
                booking.customer.toLowerCase().includes(searchTerm) ||
                booking.vehicle.toLowerCase().includes(searchTerm);
            
            const matchesStatus = !statusFilter || booking.status === statusFilter;
            const matchesType = !typeFilter || booking.type === typeFilter;
            
            const matchesVerification = !verificationFilter || 
                (verificationFilter === 'verified' && booking.verified) ||
                (verificationFilter === 'not_verified' && !booking.verified);

            return matchesSearch && matchesStatus && matchesType && matchesVerification;
        });

        renderBookingsTable();
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Pending</span>',
            'confirmed': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Confirmed</span>',
            'approved': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Approved</span>',
            'active': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Active</span>',
            'completed': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Cancelled</span>',
            'verified': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Verified</span>'
        };
        return badges[status] || '';
    }

    function getVerificationBadge(booking) {
        if (booking.verified) {
            return `
                <div class="text-center">
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium inline-block mb-1">
                        ✓ Verified
                    </span>
                    <p class="text-xs text-gray-500">by ${booking.verified_by}</p>
                    <p class="text-xs text-gray-400">${formatDateTime(booking.verified_at)}</p>
                </div>
            `;
        } else {
            return `
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full font-medium">
                    Pending Verification
                </span>
            `;
        }
    }

    function showVerificationModal(bookingId) {
        const booking = allBookings.find(b => b.id === bookingId);
        if (!booking) return;

        const modal = document.getElementById('booking-details-modal');
        modal.innerHTML = `
            <div class="modal-backdrop" onclick="closeBookingModal()">
                <div class="modal-content max-w-md" onclick="event.stopPropagation()">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">Verify Booking</h3>
                            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Booking ID: <span class="font-semibold">${booking.id}</span></p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-blue-800 font-medium">Verification Required</p>
                                    <p class="text-xs text-blue-600 mt-1">Enter your staff credentials to verify this booking.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Staff ID</label>
                                <div class="relative">
                                    <i data-lucide="user" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" 
                                           id="verify_staff_id"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                                           placeholder="STAFF001"
                                           value="{{ session('staff_id') }}"
                                           required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Password</label>
                                <div class="relative">
                                    <i data-lucide="lock" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="password" 
                                           id="verify_password"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                                           placeholder="6-digit password"
                                           maxlength="6"
                                           required>
                                </div>
                            </div>
                            
                            <div id="verification-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm"></div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button onclick="closeBookingModal()" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </button>
                        <button onclick="verifyBooking('${booking.id}')" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i data-lucide="shield-check" class="w-5 h-5 inline mr-2"></i>
                            Verify Booking
                        </button>
                    </div>
                </div>
            </div>
        `;
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    async function verifyBooking(bookingId) {
    const staffId = document.getElementById('verify_staff_id').value;
    const password = document.getElementById('verify_password').value;
    const errorDiv = document.getElementById('verification-error');
    
    // Clear previous errors
    errorDiv.classList.add('hidden');
    
    if (!staffId || !password) {
        errorDiv.textContent = 'Please enter both staff ID and password';
        errorDiv.classList.remove('hidden');
        return;
    }
    
    try {
        const response = await fetch(`/api/bookings/${bookingId}/verify`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                staff_id: staffId,
                password: password
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            
            // Update the booking in our data
            const bookingIndex = allBookings.findIndex(b => b.id === bookingId);
            if (bookingIndex !== -1) {
                allBookings[bookingIndex].verified = true;
                allBookings[bookingIndex].verified_by = staffId;
                allBookings[bookingIndex].verified_at = new Date().toISOString();
                allBookings[bookingIndex].status = 'verified';
            }
            
            // Update UI
            closeBookingModal();
            loadBookings();
        } else {
            errorDiv.textContent = result.message;
            errorDiv.classList.remove('hidden');
        }
    } catch (error) {
        errorDiv.textContent = 'Network error. Please try again.';
        errorDiv.classList.remove('hidden');
        console.error('Verification error:', error);
    }
}

    // Mock API function (replace with real API call)
    async function mockVerifyBooking(bookingId, staffId, password) {
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Mock validation
        const validStaffIds = ['STAFF001', 'STAFF002', 'RUNNER001', 'ADMIN001'];
        const validPassword = '123456'; // Example password
        
        if (!validStaffIds.includes(staffId) || password !== validPassword) {
            return {
                success: false,
                message: 'Invalid staff credentials'
            };
        }
        
        return {
            success: true,
            message: `Booking ${bookingId} verified successfully!`,
            booking_id: bookingId,
            verified_by: staffId,
            verified_at: new Date().toISOString()
        };
    }

    function viewBookingDetails(bookingId) {
        const booking = allBookings.find(b => b.id === bookingId);
        if (!booking) return;

        const modal = document.getElementById('booking-details-modal');
        modal.innerHTML = `
            <div class="modal-backdrop" onclick="closeBookingModal()">
                <div class="modal-content" onclick="event.stopPropagation()">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">Booking Details</h3>
                            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Booking Info -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-gray-800">Booking Information</h4>
                                ${getStatusBadge(booking.status)}
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Booking ID</p>
                                    <p class="font-semibold text-gray-800">${booking.id}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Type</p>
                                    <p class="font-semibold text-gray-800">${booking.type === 'delivery' ? 'Delivery' : 'Self Pickup'}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Status -->
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-800 mb-4">Verification Status</h4>
                            ${getVerificationBadge(booking)}
                        </div>

                        <!-- Customer Info -->
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-800 mb-4">Customer Information</h4>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-gray-800">${booking.customer}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-gray-800">${booking.phone}</span>
                                </div>
                                ${booking.deliveryAddress ? `
                                    <div class="flex items-start gap-2">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-1"></i>
                                        <span class="text-gray-800">${booking.deliveryAddress}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-800 mb-4">Vehicle Information</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-semibold text-gray-800">${booking.vehicle}</p>
                                <div class="grid grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Pickup</p>
                                        <p class="text-sm font-semibold text-gray-800">${formatDateTime(booking.pickup)}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Return</p>
                                        <p class="text-sm font-semibold text-gray-800">${formatDateTime(booking.return)}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-800 mb-4">Payment Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Deposit</span>
                                    <span class="font-semibold text-gray-800">${formatCurrency(booking.deposit)}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Amount</span>
                                    <span class="font-semibold text-gray-800">${formatCurrency(booking.total)}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="font-semibold text-gray-800">Balance Due</span>
                                    <span class="font-bold text-red-600">${formatCurrency(booking.total - booking.deposit)}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        ${booking.status === 'pending' ? `
                            <button onclick="approveBooking('${booking.id}')" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                <i data-lucide="check-circle" class="w-4 h-4 inline mr-2"></i>
                                Approve Booking
                            </button>
                        ` : ''}
                        ${!booking.verified ? `
                            <button onclick="showVerificationModal('${booking.id}')" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i data-lucide="shield-check" class="w-4 h-4 inline mr-2"></i>
                                Verify Booking
                            </button>
                        ` : ''}
                        <button onclick="closeBookingModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    function closeBookingModal() {
        document.getElementById('booking-details-modal').innerHTML = '';
        document.getElementById('booking-details-modal').classList.add('hidden');
    }

    function approveBooking(bookingId) {
        const booking = allBookings.find(b => b.id === bookingId);
        if (!booking) return;

        if (confirm(`Approve booking ${bookingId} for ${booking.customer}?`)) {
            booking.status = 'approved';
            showToast(`Booking ${bookingId} approved successfully!`, 'success');
            loadBookings();
            closeBookingModal();
        }
    }

    // Check URL for filters
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const bookingId = urlParams.get('id');

        if (status) {
            document.getElementById('status-filter').value = status;
            filterBookings();
        }

        if (bookingId) {
            setTimeout(() => viewBookingDetails(bookingId), 500);
        }

        loadBookings();
    });
</script>
@endpush

@endsection