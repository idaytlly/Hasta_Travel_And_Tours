@extends('staff.layouts.app')

@section('title', 'Delivery & Pickup Management')
@section('page-title', 'Delivery & Pickup')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Delivery & Pickup Management</h2>
            <p class="text-gray-600 mt-1">Manage vehicle deliveries and pickups for today's bookings</p>
        </div>
        <button onclick="refreshTasks()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            <span>Refresh</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Tasks</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2" id="total-tasks">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2" id="pending-tasks">0</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">In Progress</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2" id="inprogress-tasks">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="loader" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-3xl font-bold text-green-600 mt-2" id="completed-tasks">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Tasks List (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="filter" class="w-5 h-5 text-red-600"></i>
                    Filters
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Task Type</label>
                        <select id="type-filter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" onchange="filterTasks()">
                            <option value="">All Tasks</option>
                            <option value="pickup">Pickup Only</option>
                            <option value="return">Return Only</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select id="status-filter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" onchange="filterTasks()">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="search-input" placeholder="Search by booking ID, customer, vehicle..." 
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   onkeyup="filterTasks()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i data-lucide="list" class="w-5 h-5 text-red-600"></i>
                        Active Tasks
                    </h3>
                    <span class="text-sm text-gray-500" id="task-count">0 tasks</span>
                </div>
                
                <div id="tasks-container" class="space-y-4">
                    <!-- Loading state -->
                    <div class="text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-red-600 mb-4"></div>
                        <p class="text-gray-500">Loading tasks...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Summary & Schedule (1/3 width) -->
        <div class="space-y-6">
            <!-- Commission Summary -->
            <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                        This Month
                    </h3>
                </div>
                
                <div class="text-center mb-6">
                    <p class="text-sm text-purple-100 mb-1">Total Commission</p>
                    <p class="text-4xl font-bold" id="month-commission">RM 0</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-3 text-center">
                        <p class="text-xs text-purple-100 mb-1">Total</p>
                        <p class="text-xl font-bold" id="month-total-tasks">0</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3 text-center">
                        <p class="text-xs text-purple-100 mb-1">Done</p>
                        <p class="text-xl font-bold" id="month-completed-tasks">0</p>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-red-600"></i>
                    Today's Schedule
                </h3>
                
                <div id="schedule-container" class="space-y-3">
                    <!-- Schedule items will be populated here -->
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-red-600"></i>
                    Quick Actions
                </h3>
                
                <div class="space-y-2">
                    <button onclick="exportTasks()" class="w-full px-4 py-2.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Export Tasks</span>
                    </button>
                    <button onclick="printReport()" class="w-full px-4 py-2.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center justify-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        <span>Print Report</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Details Modal -->
<div id="task-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold">Task Details</h3>
            <button onclick="closeTaskModal()" class="hover:bg-white hover:bg-opacity-20 p-2 rounded-lg transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <div id="task-details-content" class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
            <!-- Content populated by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allTasks = [];
    let filteredTasks = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        fetchTasks();
        lucide.createIcons();
    });

    // Fetch and generate mock tasks
    async function fetchTasks() {
        try {
            allTasks = generateMockTasks();
            filterTasks();
            updateStats();
            updateSchedule();
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    }

    function generateMockTasks() {
        // Mock data generation logic here
        const mockData = []; // Your existing mock data generation
        return mockData;
    }

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
        document.getElementById('task-count').textContent = `${filteredTasks.length} tasks`;
    }

    function updateTasksDisplay() {
        const container = document.getElementById('tasks-container');
        
        if (filteredTasks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-500">No tasks found</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        container.innerHTML = filteredTasks.map(task => createTaskCard(task)).join('');
        lucide.createIcons();
    }

    function createTaskCard(task) {
        return `
            <div class="border-l-4 ${task.status === 'pending' ? 'border-orange-500' : task.status === 'in-progress' ? 'border-blue-500' : 'border-green-500'} bg-gray-50 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">${task.type === 'pickup' ? '🚗 Pickup' : '🔄 Return'} - ${task.booking_code}</h4>
                        <p class="text-sm text-gray-600 mt-1">${task.customer_name}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                        task.status === 'pending' ? 'bg-orange-100 text-orange-700' :
                        task.status === 'in-progress' ? 'bg-blue-100 text-blue-700' :
                        'bg-green-100 text-green-700'
                    }">
                        ${task.status.replace('-', ' ')}
                    </span>
                </div>
                
                <div class="text-sm text-gray-600 space-y-1 mb-3">
                    <p>🚙 ${task.vehicle_model} (${task.vehicle_plate})</p>
                    <p>📍 ${task.address}</p>
                    <p>⏰ ${formatTime(task.scheduled_time)}</p>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-purple-600">Commission: RM ${task.commission}</span>
                    <button onclick="viewTask(${task.id})" class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>
        `;
    }

    function updateStats() {
        // Update statistics
        const today = new Date().toDateString();
        const todayTasks = allTasks.filter(t => new Date(t.scheduled_time).toDateString() === today);
        
        document.getElementById('total-tasks').textContent = todayTasks.length;
        document.getElementById('pending-tasks').textContent = todayTasks.filter(t => t.status === 'pending').length;
        document.getElementById('inprogress-tasks').textContent = todayTasks.filter(t => t.status === 'in-progress').length;
        document.getElementById('completed-tasks').textContent = todayTasks.filter(t => t.status === 'completed').length;
    }

    function updateSchedule() {
        // Update today's schedule
        const container = document.getElementById('schedule-container');
        // Implementation here
    }

    function formatTime(dateString) {
        return new Date(dateString).toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit' });
    }

    function viewTask(taskId) {
        // Show task details modal
        document.getElementById('task-modal').classList.remove('hidden');
    }

    function closeTaskModal() {
        document.getElementById('task-modal').classList.add('hidden');
    }

    function refreshTasks() {
        fetchTasks();
    }

    function exportTasks() {
        alert('Export functionality coming soon!');
    }

    function printReport() {
        window.print();
    }
</script>
@endpush

@endsection