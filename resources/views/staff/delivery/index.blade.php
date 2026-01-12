@extends('staff.layouts.app')

@section('title', 'Delivery & Pickup - Staff Portal')
@section('page-title', 'Delivery & Pickup Management')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Delivery Tasks</h1>
                <p class="text-red-100 mt-2">Manage vehicle deliveries and pickups</p>
            </div>
            <button onclick="refreshTasks()" class="px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition flex items-center gap-2">
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
                    <p class="text-sm text-gray-600">Today's Tasks</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="total-tasks">0</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="list" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1" id="pending-tasks">0</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="completed-tasks">0</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Commission Today</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="commission-today">RM 0</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Task Type</label>
                <select id="type-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterTasks()">
                    <option value="">All Tasks</option>
                    <option value="pickup">Pickup</option>
                    <option value="return">Return</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterTasks()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" placeholder="Search by booking ID..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onkeyup="filterTasks()">
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="grid grid-cols-1 gap-4" id="tasks-container">
        <!-- Loading state -->
        <div class="text-center py-12">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 text-sm mt-2">Loading tasks...</p>
        </div>
    </div>

    <!-- Commission Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Commission Summary</h3>
            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full">This Month</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Tasks</p>
                <p class="text-3xl font-bold text-gray-800 mt-2" id="month-total-tasks">0</p>
            </div>
            
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Completed Tasks</p>
                <p class="text-3xl font-bold text-green-600 mt-2" id="month-completed-tasks">0</p>
            </div>
            
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Commission</p>
                <p class="text-3xl font-bold text-purple-600 mt-2" id="month-commission">RM 0</p>
            </div>
        </div>
    </div>
</div>

<!-- Task Details Modal -->
<div id="task-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">Task Details</h3>
            <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div id="task-details-content" class="p-6">
            <!-- Content populated by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allTasks = [];
    let filteredTasks = [];

    // Fetch tasks (mock data)
    async function fetchTasks() {
        try {
            const mockTasks = generateMockTasks();
            allTasks = mockTasks;
            filteredTasks = [...allTasks];
            updateTasksDisplay();
            updateStats();
            updateCommissionSummary();
        } catch (error) {
            console.error('Error fetching tasks:', error);
            showToast('Error loading tasks', 'error');
        }
    }

    // Generate mock tasks
    function generateMockTasks() {
        const types = ['pickup', 'return'];
        const statuses = ['pending', 'in-progress', 'completed'];
        const customers = ['Ahmad Ali', 'Siti Aminah', 'Lee Wei', 'Kumar Singh'];
        const vehicles = ['Toyota Camry WP1234A', 'Honda Civic WP5678B', 'BMW 3 Series WP9012C'];
        const addresses = ['123 Jalan Bukit Bintang, KL', '456 Jalan Ampang, KL', '789 Jalan Tun Razak, KL'];
        
        return Array.from({ length: 10 }, (_, i) => ({
            id: i + 1,
            booking_code: `BK${String(i + 1001).padStart(4, '0')}`,
            type: types[Math.floor(Math.random() * types.length)],
            customer_name: customers[Math.floor(Math.random() * customers.length)],
            customer_phone: `+60${Math.floor(Math.random() * 900000000 + 100000000)}`,
            vehicle: vehicles[Math.floor(Math.random() * vehicles.length)],
            address: addresses[Math.floor(Math.random() * addresses.length)],
            scheduled_time: new Date(Date.now() + Math.random() * 12 * 60 * 60 * 1000).toISOString(),
            status: statuses[Math.floor(Math.random() * statuses.length)],
            commission: 25 + Math.floor(Math.random() * 50),
            notes: 'Please call customer 15 minutes before arrival',
        }));
    }

    // Update tasks display
    function updateTasksDisplay() {
        const container = document.getElementById('tasks-container');
        
        if (filteredTasks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-500 text-lg">No tasks found</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }
        
        container.innerHTML = filteredTasks.map(task => `
            <div class="bg-white rounded-lg shadow-sm border-l-4 ${getTaskBorderColor(task.status)} border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 ${getTaskIconBg(task.type)} rounded-lg">
                                <i data-lucide="${task.type === 'pickup' ? 'arrow-up-circle' : 'arrow-down-circle'}" 
                                   class="w-5 h-5 ${getTaskIconColor(task.type)}"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">${task.type === 'pickup' ? 'Vehicle Pickup' : 'Vehicle Return'}</h4>
                                <p class="text-sm text-gray-600">${task.booking_code}</p>
                            </div>
                            ${getStatusBadge(task.status)}
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Customer</p>
                                <p class="font-medium text-gray-800">${task.customer_name}</p>
                                <p class="text-sm text-gray-500">${task.customer_phone}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Vehicle</p>
                                <p class="font-medium text-gray-800">${task.vehicle}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                Address
                            </p>
                            <p class="font-medium text-gray-800 mt-1">${task.address}</p>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                    ${formatDateTime(task.scheduled_time)}
                                </span>
                                <span class="flex items-center gap-1 text-purple-600 font-semibold">
                                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                                    Commission: ${formatCurrency(task.commission)}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <button onclick="viewTask(${task.id})" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                title="View Details">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                        
                        ${task.status === 'pending' ? `
                            <button onclick="startTask(${task.id})" 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                    title="Start Task">
                                <i data-lucide="play" class="w-5 h-5"></i>
                            </button>
                        ` : ''}
                        
                        ${task.status === 'in-progress' ? `
                            <button onclick="completeTask(${task.id})" 
                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                    title="Complete Task">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    // Update statistics
    function updateStats() {
        const today = new Date().toDateString();
        const todayTasks = allTasks.filter(t => new Date(t.scheduled_time).toDateString() === today);
        
        document.getElementById('total-tasks').textContent = todayTasks.length;
        document.getElementById('pending-tasks').textContent = 
            todayTasks.filter(t => t.status === 'pending').length;
        document.getElementById('completed-tasks').textContent = 
            todayTasks.filter(t => t.status === 'completed').length;
        
        const commissionToday = todayTasks
            .filter(t => t.status === 'completed')
            .reduce((sum, t) => sum + t.commission, 0);
        document.getElementById('commission-today').textContent = formatCurrency(commissionToday);
    }

    // Update commission summary
    function updateCommissionSummary() {
        const thisMonth = new Date();
        thisMonth.setDate(1);
        const monthTasks = allTasks.filter(t => new Date(t.scheduled_time) >= thisMonth);
        
        document.getElementById('month-total-tasks').textContent = monthTasks.length;
        document.getElementById('month-completed-tasks').textContent = 
            monthTasks.filter(t => t.status === 'completed').length;
        
        const monthCommission = monthTasks
            .filter(t => t.status === 'completed')
            .reduce((sum, t) => sum + t.commission, 0);
        document.getElementById('month-commission').textContent = formatCurrency(monthCommission);
    }

    // Filter tasks
    function filterTasks() {
        const typeFilter = document.getElementById('type-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        
        filteredTasks = allTasks.filter(task => {
            const matchesType = !typeFilter || task.type === typeFilter;
            const matchesStatus = !statusFilter || task.status === statusFilter;
            const matchesSearch = !searchTerm || 
                task.booking_code.toLowerCase().includes(searchTerm) ||
                task.customer_name.toLowerCase().includes(searchTerm);
            
            return matchesType && matchesStatus && matchesSearch;
        });
        
        updateTasksDisplay();
    }

    // View task details
    function viewTask(taskId) {
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;
        
        showTaskModal(task);
    }

    // Show task modal
    function showTaskModal(task) {
        const modal = document.getElementById('task-modal');
        const content = document.getElementById('task-details-content');
        
        content.innerHTML = `
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Task Type</p>
                        <p class="font-bold text-gray-800 mt-1">${task.type === 'pickup' ? 'Vehicle Pickup' : 'Vehicle Return'}</p>
                    </div>
                    ${getStatusBadge(task.status)}
                </div>

                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Booking Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Booking Code</p>
                            <p class="font-mono font-bold text-gray-800">${task.booking_code}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Scheduled Time</p>
                            <p class="font-medium text-gray-800">${formatDateTime(task.scheduled_time)}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Customer Details</h4>
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-medium text-gray-800">${task.customer_name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="font-medium text-gray-800">${task.customer_phone}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="font-medium text-gray-800">${task.address}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Vehicle Information</h4>
                    <p class="font-medium text-gray-800">${task.vehicle}</p>
                </div>

                <div class="p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-gray-600">Commission</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">${formatCurrency(task.commission)}</p>
                </div>

                ${task.notes ? `
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Notes</p>
                        <p class="text-gray-800">${task.notes}</p>
                    </div>
                ` : ''}

                <div class="flex gap-3">
                    ${task.status === 'pending' ? `
                        <button onclick="startTaskFromModal(${task.id})" 
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Start Task
                        </button>
                    ` : ''}
                    ${task.status === 'in-progress' ? `
                        <button onclick="completeTaskFromModal(${task.id})" 
                                class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Complete Task
                        </button>
                    ` : ''}
                    <button onclick="openMaps('${task.address}')" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <i data-lucide="map" class="w-4 h-4"></i>
                        <span>Open in Maps</span>
                    </button>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    // Close task modal
    function closeTaskModal() {
        document.getElementById('task-modal').classList.add('hidden');
    }

    // Start task
    async function startTask(taskId) {
        if (confirm('Start this task?')) {
            const task = allTasks.find(t => t.id === taskId);
            task.status = 'in-progress';
            updateTasksDisplay();
            updateStats();
            showToast('Task started successfully!', 'success');
        }
    }

    async function startTaskFromModal(taskId) {
        await startTask(taskId);
        closeTaskModal();
    }

    // Complete task
    async function completeTask(taskId) {
        if (confirm('Mark this task as completed?')) {
            const task = allTasks.find(t => t.id === taskId);
            task.status = 'completed';
            updateTasksDisplay();
            updateStats();
            updateCommissionSummary();
            showToast(`Task completed! Commission earned: ${formatCurrency(task.commission)}`, 'success');
        }
    }

    async function completeTaskFromModal(taskId) {
        const task = allTasks.find(t => t.id === taskId);
        await completeTask(taskId);
        closeTaskModal();
    }

    // Open maps
    function openMaps(address) {
        const encoded = encodeURIComponent(address);
        window.open(`https://www.google.com/maps/search/?api=1&query=${encoded}`, '_blank');
    }

    // Refresh tasks
    async function refreshTasks() {
        showToast('Refreshing tasks...', 'info');
        await fetchTasks();
        showToast('Tasks refreshed successfully!', 'success');
    }

    // Get status badge
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Pending</span>',
            'in-progress': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">In Progress</span>',
            'completed': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Completed</span>',
        };
        return badges[status] || '';
    }

    // Helper functions
    function getTaskBorderColor(status) {
        const colors = {
            'pending': 'border-l-orange-500',
            'in-progress': 'border-l-blue-500',
            'completed': 'border-l-green-500',
        };
        return colors[status] || 'border-l-gray-500';
    }

    function getTaskIconBg(type) {
        return type === 'pickup' ? 'bg-green-100' : 'bg-purple-100';
    }

    function getTaskIconColor(type) {
        return type === 'pickup' ? 'text-green-600' : 'text-purple-600';
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        fetchTasks();
        startRealTimeUpdates(fetchTasks, 30000);
    });

    // Close modal on backdrop click
    document.getElementById('task-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTaskModal();
        }
    });
</script>
@endpush

@endsection