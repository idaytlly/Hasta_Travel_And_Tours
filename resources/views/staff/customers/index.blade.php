@extends('staff.layouts.app')

@section('title', 'Customer Management - Staff Portal')
@section('page-title', 'Customer Management')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Customer Database</h2>
            <p class="text-gray-600 mt-1">View customer information and rental history</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="refreshCustomers()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="total-customers">0</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Rentals</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="active-rentals">0</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="activity" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">New This Month</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="new-customers">0</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="user-plus" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">VIP Customers</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1" id="vip-customers">0</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="star" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" placeholder="Search customers..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onkeyup="filterCustomers()">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterCustomers()">
                    <option value="">All Customers</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="vip">VIP</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select id="sort-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterCustomers()">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name">Name (A-Z)</option>
                    <option value="bookings">Most Bookings</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Customer List</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">IC Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total Bookings</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total Spent</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="customers-table" class="divide-y divide-gray-200">
                    <!-- Loading state -->
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-200 text-center" id="loading-indicator">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 text-sm mt-2">Loading customers...</p>
        </div>
        
        <div class="p-4 border-t border-gray-200 hidden" id="no-results">
            <div class="text-center py-8">
                <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-500 text-lg">No customers found</p>
            </div>
        </div>
    </div>
</div>

<!-- Customer Details Modal -->
<div id="customer-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">Customer Details</h3>
            <button onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div id="customer-details-content" class="p-6">
            <!-- Content populated by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allCustomers = [];
    let filteredCustomers = [];

    // Fetch customers (mock data for demonstration)
    async function fetchCustomers() {
        try {
            const mockCustomers = generateMockCustomers();
            allCustomers = mockCustomers;
            filteredCustomers = [...allCustomers];
            updateCustomersDisplay();
            updateStats();
        } catch (error) {
            console.error('Error fetching customers:', error);
            showToast('Error loading customers', 'error');
        }
    }

    // Generate mock customers
    function generateMockCustomers() {
        const names = ['Ahmad bin Abdullah', 'Siti Nurhaliza', 'Lee Wei Ming', 'Kumar Selvam', 'Tan Mei Ling', 'Muhammad Hakim', 'Wong Li Hua', 'Rajesh Kumar', 'Farah Amira', 'Chen Wei'];
        const statuses = ['active', 'inactive', 'vip'];
        
        return Array.from({ length: 15 }, (_, i) => ({
            id: i + 1,
            name: names[i % names.length],
            email: `customer${i + 1}@email.com`,
            phone: `+60${Math.floor(Math.random() * 900000000 + 100000000)}`,
            ic_number: `${Math.floor(Math.random() * 900000 + 100000)}-${Math.floor(Math.random() * 90 + 10)}-${Math.floor(Math.random() * 9000 + 1000)}`,
            total_bookings: Math.floor(Math.random() * 20 + 1),
            total_spent: Math.floor(Math.random() * 10000 + 500),
            status: statuses[Math.floor(Math.random() * statuses.length)],
            joined_date: new Date(Date.now() - Math.random() * 365 * 24 * 60 * 60 * 1000).toISOString(),
            last_booking: new Date(Date.now() - Math.random() * 90 * 24 * 60 * 60 * 1000).toISOString(),
        }));
    }

    // Update customers display
    function updateCustomersDisplay() {
        const tbody = document.getElementById('customers-table');
        const loading = document.getElementById('loading-indicator');
        const noResults = document.getElementById('no-results');
        
        loading.style.display = 'none';
        
        if (filteredCustomers.length === 0) {
            tbody.innerHTML = '';
            noResults.classList.remove('hidden');
            return;
        }
        
        noResults.classList.add('hidden');
        
        tbody.innerHTML = filteredCustomers.map(customer => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <span class="text-red-600 font-semibold">${customer.name.charAt(0)}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">${customer.name}</p>
                            <p class="text-sm text-gray-500">${customer.email}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${customer.phone}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono text-sm text-gray-800">${customer.ic_number}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-semibold text-gray-800">${customer.total_bookings}</span>
                    <span class="text-sm text-gray-500"> bookings</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-semibold text-gray-800">${formatCurrency(customer.total_spent)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getStatusBadge(customer.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button onclick="viewCustomer(${customer.id})" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                            title="View Details">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </td>
            </tr>
        `).join('');
        
        lucide.createIcons();
    }

    // Update statistics
    function updateStats() {
        const thisMonth = new Date();
        thisMonth.setDate(1);
        
        document.getElementById('total-customers').textContent = allCustomers.length;
        document.getElementById('active-rentals').textContent = 
            Math.floor(allCustomers.length * 0.3); // Mock active rentals
        document.getElementById('new-customers').textContent = 
            allCustomers.filter(c => new Date(c.joined_date) >= thisMonth).length;
        document.getElementById('vip-customers').textContent = 
            allCustomers.filter(c => c.status === 'vip').length;
    }

    // Filter customers
    function filterCustomers() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const sortFilter = document.getElementById('sort-filter').value;
        
        // Filter
        filteredCustomers = allCustomers.filter(customer => {
            const matchesSearch = !searchTerm || 
                customer.name.toLowerCase().includes(searchTerm) ||
                customer.email.toLowerCase().includes(searchTerm) ||
                customer.phone.includes(searchTerm) ||
                customer.ic_number.includes(searchTerm);
            
            const matchesStatus = !statusFilter || customer.status === statusFilter;
            
            return matchesSearch && matchesStatus;
        });
        
        // Sort
        switch(sortFilter) {
            case 'newest':
                filteredCustomers.sort((a, b) => new Date(b.joined_date) - new Date(a.joined_date));
                break;
            case 'oldest':
                filteredCustomers.sort((a, b) => new Date(a.joined_date) - new Date(b.joined_date));
                break;
            case 'name':
                filteredCustomers.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'bookings':
                filteredCustomers.sort((a, b) => b.total_bookings - a.total_bookings);
                break;
        }
        
        updateCustomersDisplay();
    }

    // View customer details
    function viewCustomer(customerId) {
        const customer = allCustomers.find(c => c.id === customerId);
        if (!customer) return;
        
        showCustomerModal(customer);
    }

    // Show customer modal
    function showCustomerModal(customer) {
        const modal = document.getElementById('customer-modal');
        const content = document.getElementById('customer-details-content');
        
        // Generate mock booking history
        const bookingHistory = Array.from({ length: customer.total_bookings }, (_, i) => ({
            id: `BK${String(i + 1001).padStart(4, '0')}`,
            date: new Date(Date.now() - Math.random() * 180 * 24 * 60 * 60 * 1000).toISOString(),
            vehicle: ['Toyota Camry', 'Honda Civic', 'BMW 3 Series'][Math.floor(Math.random() * 3)],
            amount: Math.floor(Math.random() * 2000 + 300),
            status: ['completed', 'active', 'cancelled'][Math.floor(Math.random() * 3)],
        })).sort((a, b) => new Date(b.date) - new Date(a.date));
        
        content.innerHTML = `
            <div class="space-y-6">
                <!-- Customer Info Card -->
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-red-600 font-bold text-2xl">${customer.name.charAt(0)}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">${customer.name}</h4>
                                <p class="text-sm text-gray-600 mt-1">${customer.email}</p>
                            </div>
                            ${getStatusBadge(customer.status)}
                        </div>
                    </div>
                </div>

                <!-- Contact & Stats -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold text-gray-800 mb-3">Contact Information</h5>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium text-gray-800">${customer.phone}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">IC Number</p>
                                <p class="font-mono font-medium text-gray-800">${customer.ic_number}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Member Since</p>
                                <p class="font-medium text-gray-800">${formatDate(customer.joined_date)}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-gray-800 mb-3">Statistics</h5>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Total Bookings</p>
                                <p class="text-2xl font-bold text-gray-800">${customer.total_bookings}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Spent</p>
                                <p class="text-2xl font-bold text-red-600">${formatCurrency(customer.total_spent)}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Last Booking</p>
                                <p class="font-medium text-gray-800">${formatDate(customer.last_booking)}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking History -->
                <div>
                    <h5 class="font-semibold text-gray-800 mb-3">Booking History</h5>
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        ${bookingHistory.map(booking => `
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-800">${booking.id}</p>
                                    <p class="text-sm text-gray-600">${booking.vehicle} • ${formatDate(booking.date)}</p>
                                </div>
                                <div class="text-right">
                                    ${getStatusBadge(booking.status)}
                                    <p class="text-sm font-semibold text-gray-800 mt-1">${formatCurrency(booking.amount)}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button onclick="sendMessage('${customer.email}')" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <span>Send Email</span>
                    </button>
                    <button onclick="callCustomer('${customer.phone}')" 
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>Call Customer</span>
                    </button>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    // Close customer modal
    function closeCustomerModal() {
        document.getElementById('customer-modal').classList.add('hidden');
    }

    // Send message to customer
    function sendMessage(email) {
        showToast(`Opening email client for ${email}...`, 'info');
        setTimeout(() => {
            window.location.href = `mailto:${email}`;
        }, 1000);
    }

    // Call customer
    function callCustomer(phone) {
        showToast(`Initiating call to ${phone}...`, 'info');
        setTimeout(() => {
            window.location.href = `tel:${phone}`;
        }, 1000);
    }

    // Refresh customers
    async function refreshCustomers() {
        showToast('Refreshing customers...', 'info');
        await fetchCustomers();
        showToast('Customers refreshed successfully!', 'success');
    }

    // Get status badge
    function getStatusBadge(status) {
        const badges = {
            'active': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Active</span>',
            'inactive': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Inactive</span>',
            'vip': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">⭐ VIP</span>',
            'completed': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Completed</span>',
            'cancelled': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Cancelled</span>',
        };
        return badges[status.toLowerCase()] || '';
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        fetchCustomers();
        startRealTimeUpdates(fetchCustomers, 30000);
    });

    // Close modal on backdrop click
    document.getElementById('customer-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCustomerModal();
        }
    });
</script>
@endpush

@endsection