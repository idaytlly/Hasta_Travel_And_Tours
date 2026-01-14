{{-- resources/views/staff/customers/index.blade.php --}}
@extends('staff.layouts.app')

@section('title', 'Customer Management - Staff Portal')
@section('page-title', 'Customer Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customer Management</h1>
            <p class="text-gray-600 mt-1">Manage customer profiles, bookings, and rental history</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="refreshCustomers()" class="px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2" id="total-customers">0</p>
                    <div class="flex items-center mt-1">
                        <span class="text-xs text-gray-500">Updated just now</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-xl">
                    <i data-lucide="users" class="w-7 h-7 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Rentals</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2" id="active-rentals">0</p>
                    <div class="flex items-center mt-1">
                        <i data-lucide="trending-up" class="w-4 h-4 text-green-500 mr-1"></i>
                        <span class="text-xs text-green-600 font-medium">+12% this week</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-3 rounded-xl">
                    <i data-lucide="activity" class="w-7 h-7 text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">New This Month</p>
                    <p class="text-3xl font-bold text-green-600 mt-2" id="new-customers">0</p>
                    <div class="flex items-center mt-1">
                        <i data-lucide="users" class="w-4 h-4 text-blue-500 mr-1"></i>
                        <span class="text-xs text-gray-500">New registrations</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-3 rounded-xl">
                    <i data-lucide="user-plus" class="w-7 h-7 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">VIP Customers</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2" id="vip-customers">0</p>
                    <div class="flex items-center mt-1">
                        <i data-lucide="crown" class="w-4 h-4 text-amber-500 mr-1"></i>
                        <span class="text-xs text-amber-600 font-medium">Top spenders</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-3 rounded-xl">
                    <i data-lucide="star" class="w-7 h-7 text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <!-- Search Box -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Search Customers</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" placeholder="Name, email, phone or IC..." 
                           class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                           onkeyup="filterCustomers()">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Filter</label>
                <div class="relative">
                    <i data-lucide="filter" class="w-4 h-4 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 z-10"></i>
                    <select id="status-filter" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent appearance-none bg-white transition-all duration-200"
                            onchange="filterCustomers()">
                        <option value="">All Customers</option>
                        <option value="active" class="py-2">Active</option>
                        <option value="inactive" class="py-2">Inactive</option>
                        <option value="vip" class="py-2">VIP</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
            
            <!-- Sort By -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                <div class="relative">
                    <i data-lucide="arrow-up-down" class="w-4 h-4 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select id="sort-filter" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent appearance-none bg-white transition-all duration-200"
                            onchange="filterCustomers()">
                        <option value="newest" class="py-2">Newest First</option>
                        <option value="oldest" class="py-2">Oldest First</option>
                        <option value="name" class="py-2">Name (A-Z)</option>
                        <option value="bookings" class="py-2">Most Bookings</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-end">
                <button onclick="exportCustomers()" class="w-full px-4 py-3 bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 border border-gray-200">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Export CSV</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Customer Directory</h3>
                <p class="text-sm text-gray-500 mt-1" id="customer-count">Loading customers...</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-8 w-px bg-gray-200"></div>
                <button onclick="refreshCustomers()" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                <span>Customer</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                                <span>Contact</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i data-lucide="id-card" class="w-4 h-4"></i>
                                <span>IC Number</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                <span>Total Bookings</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                                <span>Total Spent</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i data-lucide="activity" class="w-4 h-4"></i>
                                <span>Status</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="customers-table" class="divide-y divide-gray-100">
                    <!-- Content will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div class="p-8 border-t border-gray-100 text-center" id="loading-indicator">
            <div class="flex flex-col items-center justify-center">
                <div class="relative">
                    <div class="w-16 h-16 border-4 border-red-100 rounded-full"></div>
                    <div class="w-16 h-16 border-4 border-red-500 rounded-full animate-spin border-t-transparent absolute top-0"></div>
                </div>
                <p class="text-gray-600 font-medium mt-4">Loading customers...</p>
                <p class="text-gray-400 text-sm mt-1">Fetching customer data from database</p>
            </div>
        </div>
        
        <!-- No Results State -->
        <div class="p-8 border-t border-gray-100 text-center hidden" id="no-results">
            <div class="max-w-sm mx-auto">
                <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="users" class="w-10 h-10 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No customers found</h3>
                <p class="text-gray-500 mb-6">Try adjusting your search or filter to find what you're looking for.</p>
                <button onclick="clearFilters()" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                    Clear all filters
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Details Modal (Improved) -->
<div id="customer-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity duration-200">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-200 scale-95 opacity-0">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-100 p-6 flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center">
                    <i data-lucide="user" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Customer Details</h3>
                    <p class="text-sm text-gray-500">Full customer information and history</p>
                </div>
            </div>
            <button onclick="closeCustomerModal()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div id="customer-details-content" class="p-6">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Smooth scroll for modal */
    #customer-modal::-webkit-scrollbar {
        width: 6px;
    }
    
    #customer-modal::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #customer-modal::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }
    
    #customer-modal::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    
    /* Status badge improvements */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    /* Table row hover effect */
    #customers-table tr {
        transition: all 0.2s ease;
    }
    
    #customers-table tr:hover {
        background-color: #f9fafb;
    }
    
    /* Custom select arrow */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    let allCustomers = [];
    let filteredCustomers = [];

    // Fetch customers
    // Replace fetchCustomers function
async function fetchCustomers() {
    try {
        const response = await fetch('/api/staff/customers/data');
        if (!response.ok) throw new Error('Failed to fetch customers');
        
        allCustomers = await response.json();
        filteredCustomers = [...allCustomers];
        updateCustomersDisplay();
        updateStats();
    } catch (error) {
        console.error('Error fetching customers:', error);
        showToast('Error loading customers', 'error');
        // Fallback to mock data if API fails
        const mockCustomers = generateMockCustomers();
        allCustomers = mockCustomers;
        filteredCustomers = [...allCustomers];
        updateCustomersDisplay();
        updateStats();
    }
}

// Update filterCustomers to use API
async function filterCustomers() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value;
    const sortFilter = document.getElementById('sort-filter').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (statusFilter) params.append('status', statusFilter);
    if (sortFilter) params.append('sort', sortFilter);
    
    try {
        const response = await fetch(`/api/staff/customers/data?${params}`);
        if (!response.ok) throw new Error('Failed to filter customers');
        
        filteredCustomers = await response.json();
        updateCustomersDisplay();
    } catch (error) {
        console.error('Error filtering customers:', error);
        // Fallback to client-side filtering
        filteredCustomers = allCustomers.filter(customer => {
            const matchesSearch = !searchTerm || 
                customer.name.toLowerCase().includes(searchTerm) ||
                customer.email.toLowerCase().includes(searchTerm) ||
                customer.phone_no.includes(searchTerm) ||
                customer.ic_number.includes(searchTerm);
            
            const matchesStatus = !statusFilter || 
                (statusFilter === 'active' && customer.is_active) ||
                (statusFilter === 'inactive' && !customer.is_active) ||
                (statusFilter === 'vip' && customer.total_spent > 5000);
            
            return matchesSearch && matchesStatus;
        });
        
        // Client-side sorting
        switch(sortFilter) {
            case 'oldest':
                filteredCustomers.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                break;
            case 'name':
                filteredCustomers.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'bookings':
                filteredCustomers.sort((a, b) => (b.total_bookings || 0) - (a.total_bookings || 0));
                break;
            default:
                filteredCustomers.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
        
        updateCustomersDisplay();
    }
}

    // Generate mock customers (unchanged)
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

    // Update customers display with improved design
    function updateCustomersDisplay() {
        const tbody = document.getElementById('customers-table');
        const loading = document.getElementById('loading-indicator');
        const noResults = document.getElementById('no-results');
        const customerCount = document.getElementById('customer-count');
        
        loading.style.display = 'none';
        
        if (filteredCustomers.length === 0) {
            tbody.innerHTML = '';
            noResults.classList.remove('hidden');
            customerCount.textContent = '0 customers found';
            return;
        }
        
        noResults.classList.add('hidden');
        customerCount.textContent = `${filteredCustomers.length} customers found`;
        
        tbody.innerHTML = filteredCustomers.map(customer => `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center">
                            <span class="text-red-600 font-semibold text-lg">${customer.name.charAt(0)}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors">${customer.name}</p>
                            <p class="text-sm text-gray-500 truncate max-w-[180px]">${customer.email}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                        <span class="text-sm text-gray-700 font-medium">${customer.phone}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <code class="bg-gray-50 px-3 py-1.5 rounded-lg text-sm font-mono text-gray-700 border border-gray-200">
                        ${customer.ic_number}
                    </code>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <span class="text-blue-600 font-bold">${customer.total_bookings}</span>
                        </div>
                        <span class="text-sm text-gray-600">bookings</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900">${formatCurrency(customer.total_spent)}</span>
                        <i data-lucide="trending-up" class="w-4 h-4 text-green-500 ${Math.random() > 0.5 ? '' : 'hidden'}"></i>
                    </div>
                </td>
                <td class="px-6 py-4">
                    ${getEnhancedStatusBadge(customer.status)}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <button onclick="viewCustomer(${customer.id})" 
                                class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                        <a href="tel:${customer.phone}" 
                           class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                           title="Call Customer">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </a>
                        <a href="mailto:${customer.email}" 
                           class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                           title="Send Email">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `).join('');
        
        lucide.createIcons();
    }

    // Enhanced status badge
    function getEnhancedStatusBadge(status) {
        const badges = {
            'active': `
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 border border-green-200">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-green-700">Active</span>
                </div>
            `,
            'inactive': `
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 border border-gray-200">
                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    <span class="text-xs font-medium text-gray-700">Inactive</span>
                </div>
            `,
            'vip': `
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200">
                    <i data-lucide="crown" class="w-3 h-3 text-amber-600"></i>
                    <span class="text-xs font-medium text-amber-700">VIP</span>
                </div>
            `,
        };
        return badges[status.toLowerCase()] || '';
    }

    // Update statistics
    function updateStats() {
        const thisMonth = new Date();
        thisMonth.setDate(1);
        
        document.getElementById('total-customers').textContent = allCustomers.length.toLocaleString();
        document.getElementById('active-rentals').textContent = Math.floor(allCustomers.length * 0.3).toLocaleString();
        document.getElementById('new-customers').textContent = allCustomers.filter(c => new Date(c.joined_date) >= thisMonth).length.toLocaleString();
        document.getElementById('vip-customers').textContent = allCustomers.filter(c => c.status === 'vip').length.toLocaleString();
    }

    // Filter customers
    function filterCustomers() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const sortFilter = document.getElementById('sort-filter').value;
        
        filteredCustomers = allCustomers.filter(customer => {
            const matchesSearch = !searchTerm || 
                customer.name.toLowerCase().includes(searchTerm) ||
                customer.email.toLowerCase().includes(searchTerm) ||
                customer.phone.includes(searchTerm) ||
                customer.ic_number.includes(searchTerm);
            
            const matchesStatus = !statusFilter || customer.status === statusFilter;
            
            return matchesSearch && matchesStatus;
        });
        
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

    // Show customer modal with animation
    function showCustomerModal(customer) {
        const modal = document.getElementById('customer-modal');
        const modalContent = modal.querySelector('.bg-white');
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
                <!-- Profile Header -->
                <div class="flex items-start gap-4 p-5 bg-gradient-to-r from-gray-50 to-white rounded-2xl border border-gray-100">
                    <div class="w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <span class="text-red-600 font-bold text-3xl">${customer.name.charAt(0)}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-900">${customer.name}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-gray-600">${customer.email}</p>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <p class="text-gray-600">${customer.phone}</p>
                                </div>
                            </div>
                            ${getEnhancedStatusBadge(customer.status)}
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                <span class="text-sm text-gray-600">Joined ${formatDate(customer.joined_date)}</span>
                            </div>
                            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                <span class="text-sm text-gray-600">Last booking ${formatDate(customer.last_booking)}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">${customer.total_bookings}</p>
                        <div class="flex items-center mt-1">
                            <i data-lucide="trending-up" class="w-4 h-4 text-green-500"></i>
                            <span class="text-xs text-green-600 ml-1">+${Math.floor(Math.random() * 20)}%</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Total Spent</p>
                        <p class="text-2xl font-bold text-red-600">${formatCurrency(customer.total_spent)}</p>
                        <div class="flex items-center mt-1">
                            <i data-lucide="dollar-sign" class="w-4 h-4 text-amber-500"></i>
                            <span class="text-xs text-gray-600 ml-1">Average $${Math.floor(customer.total_spent/customer.total_bookings)}/booking</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Avg. Rating</p>
                        <div class="flex items-center gap-2">
                            <p class="text-2xl font-bold text-amber-600">4.${Math.floor(Math.random() * 9)}</p>
                            <div class="flex">
                                ${Array.from({length: 5}, (_, i) => `
                                    <i data-lucide="star" class="w-4 h-4 ${i < 4 ? 'text-amber-400 fill-amber-400' : 'text-gray-300'}"></i>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Loyalty Points</p>
                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-purple-600">${Math.floor(customer.total_spent/10)}</p>
                            <i data-lucide="award" class="w-5 h-5 text-purple-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="contact" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <h5 class="font-semibold text-gray-900">Contact Details</h5>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Phone Number</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                    <p class="font-medium text-gray-800">${customer.phone}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">IC Number</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="id-card" class="w-4 h-4 text-gray-400"></i>
                                    <code class="font-mono font-medium text-gray-800 bg-gray-50 px-3 py-1.5 rounded-lg">${customer.ic_number}</code>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Member Since</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                    <p class="font-medium text-gray-800">${formatDate(customer.joined_date)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-5 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="chart-bar" class="w-5 h-5 text-purple-600"></i>
                            </div>
                            <h5 class="font-semibold text-gray-900">Booking Analytics</h5>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Frequent Vehicle Type</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="car" class="w-4 h-4 text-gray-400"></i>
                                    <p class="font-medium text-gray-800">Sedan (${Math.floor(customer.total_bookings * 0.7)} bookings)</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Preferred Payment</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="credit-card" class="w-4 h-4 text-gray-400"></i>
                                    <p class="font-medium text-gray-800">Credit Card (${Math.floor(customer.total_bookings * 0.6)} payments)</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Average Duration</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    <p class="font-medium text-gray-800">${Math.floor(Math.random() * 5) + 2} days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white p-5 rounded-xl border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="calendar-check" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <h5 class="font-semibold text-gray-900">Recent Bookings</h5>
                        </div>
                        <span class="text-sm text-gray-500">Last ${Math.min(5, bookingHistory.length)} of ${customer.total_bookings} bookings</span>
                    </div>
                    <div class="space-y-3">
                        ${bookingHistory.slice(0, 5).map(booking => `
                            <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                                        <i data-lucide="car" class="w-5 h-5 text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 group-hover:text-red-600 transition-colors">${booking.id}</p>
                                        <p class="text-sm text-gray-600">${booking.vehicle}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                            <span class="text-xs text-gray-500">${formatDate(booking.date)}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    ${getBookingStatusBadge(booking.status)}
                                    <p class="text-lg font-bold text-gray-900 mt-2">${formatCurrency(booking.amount)}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button onclick="sendMessage('${customer.email}')" 
                            class="flex-1 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center justify-center gap-3 shadow-md hover:shadow-lg">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                        <div class="text-left">
                            <span class="font-semibold">Send Email</span>
                            <p class="text-xs opacity-90">${customer.email}</p>
                        </div>
                    </button>
                    <button onclick="callCustomer('${customer.phone}')" 
                            class="flex-1 px-6 py-3.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center justify-center gap-3 shadow-md hover:shadow-lg">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                        <div class="text-left">
                            <span class="font-semibold">Call Customer</span>
                            <p class="text-xs opacity-90">${customer.phone}</p>
                        </div>
                    </button>
                    <button onclick="viewCustomerHistory(${customer.id})" 
                            class="flex-1 px-6 py-3.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center justify-center gap-3 shadow-md hover:shadow-lg">
                        <i data-lucide="history" class="w-5 h-5"></i>
                        <div class="text-left">
                            <span class="font-semibold">View History</span>
                            <p class="text-xs opacity-90">Complete booking log</p>
                        </div>
                    </button>
                </div>
            </div>
        `;
        
        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        lucide.createIcons();
    }

    // Booking status badge for modal
    function getBookingStatusBadge(status) {
        const badges = {
            'completed': `
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 border border-green-200">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-xs font-medium text-green-700">Completed</span>
                </div>
            `,
            'active': `
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-blue-700">Active</span>
                </div>
            `,
            'cancelled': `
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-200">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <span class="text-xs font-medium text-red-700">Cancelled</span>
                </div>
            `,
        };
        return badges[status.toLowerCase()] || '';
    }

    // Close customer modal with animation
    function closeCustomerModal() {
        const modal = document.getElementById('customer-modal');
        const modalContent = modal.querySelector('.bg-white');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Clear all filters
    function clearFilters() {
        document.getElementById('search-input').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('sort-filter').value = 'newest';
        filterCustomers();
        showToast('Filters cleared', 'success');
    }

    // Export customers (mock function)
    function exportCustomers() {
        showToast('Preparing export file...', 'info');
        setTimeout(() => {
            showToast('Export completed successfully!', 'success');
        }, 1500);
    }

    // View customer history (mock function)
    function viewCustomerHistory(customerId) {
        showToast('Opening complete booking history...', 'info');
        setTimeout(() => {
            // In a real app, this would navigate to the booking history page
            console.log('Navigate to customer history for ID:', customerId);
        }, 1000);
    }

    // Helper functions (unchanged from original)
    function formatCurrency(amount) {
        return 'RM ' + amount.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-MY', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }

    function showToast(message, type = 'info') {
        // Implementation would depend on your toast library
        console.log(`[${type.toUpperCase()}] ${message}`);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        fetchCustomers();
        
        // Add animation to stats cards on load
        setTimeout(() => {
            document.querySelectorAll('.bg-white.rounded-xl').forEach((card, index) => {
                card.style.animationDelay = `${index * 100}ms`;
                card.classList.add('animate-fade-in-up');
            });
        }, 100);
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