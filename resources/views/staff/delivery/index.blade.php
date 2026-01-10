@extends('staff.layouts.app')

@section('title', 'Delivery & Pickup - Staff Portal')
@section('page-title', 'Delivery & Pickup Tasks')

@section('content')
<div class="space-y-6">
    
    <!-- Runner Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-100">This Month's Commission</p>
                    <p class="text-3xl font-bold mt-2">RM <span id="commission-total">380.00</span></p>
                    <p class="text-xs text-red-100 mt-2">From <span id="completed-count">5</span> deliveries</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's Tasks</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="today-tasks">0</p>
                    <p class="text-xs text-blue-600 mt-2">Scheduled</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="calendar" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Tasks</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="pending-tasks">0</p>
                    <p class="text-xs text-orange-600 mt-2">Action needed</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="clock" class="w-8 h-8 text-orange-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">In Progress</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="in-progress-tasks">0</p>
                    <p class="text-xs text-green-600 mt-2">Ongoing</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="truck" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap gap-3">
            <button onclick="filterTasks('all')" id="filter-all" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                All Tasks
            </button>
            <button onclick="filterTasks('pending')" id="filter-pending" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                Pending
            </button>
            <button onclick="filterTasks('in-progress')" id="filter-in-progress" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                In Progress
            </button>
            <button onclick="filterTasks('completed')" id="filter-completed" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                Completed
            </button>
            <button onclick="filterTasks('delivery')" id="filter-delivery" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                Deliveries Only
            </button>
            <button onclick="filterTasks('pickup')" id="filter-pickup" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                Pickups Only
            </button>
        </div>
    </div>

    <!-- Delivery Tasks List -->
    <div id="tasks-container" class="space-y-4">
        <div class="text-center py-12">
            <div class="spinner"></div>
            <p class="text-gray-500 mt-4">Loading delivery tasks...</p>
        </div>
    </div>

</div>

<!-- Task Details Modal -->
<div id="task-details-modal" class="hidden"></div>

@push('scripts')
<script>
    // Sample delivery tasks data
    let allTasks = [
        {
            id: 'DT001',
            bookingId: 'BK002',
            type: 'delivery',
            customer: 'Sarah Lee',
            phone: '+60198765432',
            vehicle: 'Honda City',
            plateNumber: 'WXY 1234',
            address: 'Jalan Ampang, Kuala Lumpur',
            scheduledTime: '2026-01-10 14:00',
            status: 'pending',
            commission: 80,
            notes: 'Please call customer 30 mins before delivery'
        },
        {
            id: 'DT002',
            bookingId: 'BK004',
            type: 'delivery',
            customer: 'Lisa Wong',
            phone: '+60134567890',
            vehicle: 'Perodua Axia',
            plateNumber: 'ABC 5678',
            address: 'SS15, Subang Jaya, Selangor',
            scheduledTime: '2026-01-10 16:00',
            status: 'in-progress',
            commission: 70,
            notes: 'Customer at office until 5pm'
        },
        {
            id: 'DT003',
            bookingId: 'BK006',
            type: 'pickup',
            customer: 'Emily Ng',
            phone: '+60156789012',
            vehicle: 'Toyota Camry',
            plateNumber: 'DEF 9012',
            address: 'Section 14, Petaling Jaya',
            scheduledTime: '2026-01-11 11:00',
            status: 'pending',
            commission: 80,
            notes: 'Return from completed rental'
        },
        {
            id: 'DT004',
            bookingId: 'BK007',
            type: 'delivery',
            customer: 'Rachel Koh',
            phone: '+60189012345',
            vehicle: 'Nissan Almera',
            plateNumber: 'GHI 3456',
            address: 'Mont Kiara, Kuala Lumpur',
            scheduledTime: '2026-01-14 15:00',
            status: 'pending',
            commission: 90,
            notes: 'Condo - Call for access code'
        },
        {
            id: 'DT005',
            bookingId: 'BK003',
            type: 'pickup',
            customer: 'Ahmad Hassan',
            phone: '+60123456789',
            vehicle: 'Perodua Myvi',
            plateNumber: 'JKL 7890',
            address: 'Kepong, Kuala Lumpur',
            scheduledTime: '2026-01-09 18:00',
            status: 'completed',
            commission: 60,
            completedAt: '2026-01-09 17:45',
            notes: ''
        }
    ];

    let filteredTasks = [...allTasks];
    let currentFilter = 'all';

    function loadDeliveryTasks() {
        updateTaskStats();
        renderTasks();
    }

    function updateTaskStats() {
        const todayTasks = allTasks.filter(t => {
            const taskDate = new Date(t.scheduledTime).toDateString();
            const today = new Date().toDateString();
            return taskDate === today && t.status !== 'completed';
        }).length;

        const pendingTasks = allTasks.filter(t => t.status === 'pending').length;
        const inProgressTasks = allTasks.filter(t => t.status === 'in-progress').length;
        const completedTasks = allTasks.filter(t => t.status === 'completed').length;
        const totalCommission = allTasks
            .filter(t => t.status === 'completed')
            .reduce((sum, t) => sum + t.commission, 0);

        document.getElementById('today-tasks').textContent = todayTasks;
        document.getElementById('pending-tasks').textContent = pendingTasks;
        document.getElementById('in-progress-tasks').textContent = inProgressTasks;
        document.getElementById('completed-count').textContent = completedTasks;
        document.getElementById('commission-total').textContent = totalCommission.toFixed(2);
    }

    function renderTasks() {
        const container = document.getElementById('tasks-container');
        
        if (filteredTasks.length === 0) {
            container.innerHTML = `
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
                    <p class="text-gray-500 text-lg">No tasks found</p>
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        container.innerHTML = filteredTasks.map(task => `
            <div class="bg-white rounded-lg shadow-sm border-l-4 ${getTaskBorderColor(task.status)} border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="${getTaskIconBg(task.type)} p-2 rounded-lg">
                                <i data-lucide="${task.type === 'delivery' ? 'truck' : 'package'}" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">${task.type === 'delivery' ? 'Vehicle Delivery' : 'Vehicle Pickup'}</h3>
                                <p class="text-sm text-gray-500">${task.id} • ${task.bookingId}</p>
                            </div>
                            ${getTaskStatusBadge(task.status)}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                    <span class="font-semibold">${task.customer}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                    <a href="tel:${task.phone}" class="hover:text-red-600">${task.phone}</a>
                                </div>
                                <div class="flex items-start gap-2 text-gray-600">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-1"></i>
                                    <span class="flex-1">${task.address}</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i data-lucide="car" class="w-4 h-4 text-gray-400"></i>
                                    <span class="font-semibold">${task.vehicle}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                                    <span>${task.plateNumber}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    <span>${formatDateTime(task.scheduledTime)}</span>
                                </div>
                            </div>
                        </div>

                        ${task.notes ? `
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <i data-lucide="alert-circle" class="w-4 h-4 text-yellow-600 mt-0.5"></i>
                                    <p class="text-sm text-yellow-800">${task.notes}</p>
                                </div>
                            </div>
                        ` : ''}

                        ${task.status === 'completed' ? `
                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-green-800">Completed at ${formatDateTime(task.completedAt)}</span>
                                    <span class="font-semibold text-green-700">Commission: RM ${task.commission}</span>
                                </div>
                            </div>
                        ` : ''}
                    </div>

                    <div class="flex flex-col gap-2 ml-4">
                        ${task.status === 'pending' ? `
                            <button onclick="startTask('${task.id}')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm whitespace-nowrap">
                                <i data-lucide="play" class="w-4 h-4 inline mr-1"></i>
                                Start Task
                            </button>
                        ` : ''}
                        
                        ${task.status === 'in-progress' ? `
                            <button onclick="completeTask('${task.id}')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm whitespace-nowrap">
                                <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i>
                                Complete
                            </button>
                        ` : ''}
                        
                        <button onclick="viewTaskDetails('${task.id}')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm whitespace-nowrap">
                            <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                            Details
                        </button>
                        
                        <button onclick="openGoogleMaps('${task.address}')" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm whitespace-nowrap">
                            <i data-lucide="map" class="w-4 h-4 inline mr-1"></i>
                            Navigate
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    function getTaskBorderColor(status) {
        const colors = {
            'pending': 'border-orange-500',
            'in-progress': 'border-blue-500',
            'completed': 'border-green-500'
        };
        return colors[status] || 'border-gray-300';
    }

    function getTaskIconBg(type) {
        return type === 'delivery' ? 'bg-red-600' : 'bg-blue-600';
    }

    function getTaskStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm rounded-full font-medium">Pending</span>',
            'in-progress': '<span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm rounded-full font-medium">In Progress</span>',
            'completed': '<span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full font-medium">Completed</span>'
        };
        return badges[status] || '';
    }

    function filterTasks(filter) {
        currentFilter = filter;
        
        // Update button styles
        document.querySelectorAll('[id^="filter-"]').forEach(btn => {
            btn.className = 'px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm';
        });
        document.getElementById(`filter-${filter}`).className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium';

        // Filter tasks
        if (filter === 'all') {
            filteredTasks = [...allTasks];
        } else if (filter === 'delivery') {
            filteredTasks = allTasks.filter(t => t.type === 'delivery');
        } else if (filter === 'pickup') {
            filteredTasks = allTasks.filter(t => t.type === 'pickup');
        } else {
            filteredTasks = allTasks.filter(t => t.status === filter);
        }

        renderTasks();
    }

    function startTask(taskId) {
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;

        if (confirm(`Start ${task.type} task for ${task.customer}?`)) {
            task.status = 'in-progress';
            showToast(`Task ${taskId} started!`, 'success');
            loadDeliveryTasks();
        }
    }

    function completeTask(taskId) {
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;

        if (confirm(`Mark this ${task.type} as completed?\n\nYou will earn RM ${task.commission} commission.`)) {
            task.status = 'completed';
            task.completedAt = new Date().toISOString();
            showToast(`Task ${taskId} completed! Commission RM ${task.commission} earned.`, 'success');
            loadDeliveryTasks();
        }
    }

    function viewTaskDetails(taskId) {
        const task = allTasks.find(t => t.id === taskId);
        if (!task) return;

        const modal = document.getElementById('task-details-modal');
        modal.innerHTML = `
            <div class="modal-backdrop" onclick="closeTaskModal()">
                <div class="modal-content" onclick="event.stopPropagation()">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">Task Details</h3>
                            <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-800">${task.type === 'delivery' ? 'Vehicle Delivery' : 'Vehicle Pickup'}</h4>
                            ${getTaskStatusBadge(task.status)}
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Task ID</p>
                                <p class="font-semibold text-gray-800">${task.id}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Booking ID</p>
                                <p class="font-semibold text-gray-800">${task.bookingId}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Commission</p>
                                <p class="font-bold text-red-600">RM ${task.commission}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-3">
                            <h5 class="font-semibold text-gray-800">Customer Information</h5>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-gray-800">${task.customer}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                    <a href="tel:${task.phone}" class="text-red-600 hover:text-red-700">${task.phone}</a>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-1"></i>
                                    <span class="text-gray-800 flex-1">${task.address}</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-3">
                            <h5 class="font-semibold text-gray-800">Vehicle Information</h5>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="car" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-gray-800">${task.vehicle}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-gray-800">${task.plateNumber}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    <span class="text-gray-800">${formatDateTime(task.scheduledTime)}</span>
                                </div>
                            </div>
                        </div>

                        ${task.notes ? `
                            <div class="border-t border-gray-200 pt-4">
                                <h5 class="font-semibold text-gray-800 mb-2">Special Instructions</h5>
                                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800">${task.notes}</p>
                                </div>
                            </div>
                        ` : ''}
                    </div>

                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button onclick="openGoogleMaps('${task.address}')" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i data-lucide="map" class="w-4 h-4 inline mr-2"></i>
                            Navigate with Google Maps
                        </button>
                        <button onclick="closeTaskModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    function closeTaskModal() {
        document.getElementById('task-details-modal').innerHTML = '';
        document.getElementById('task-details-modal').classList.add('hidden');
    }

    function openGoogleMaps(address) {
        const encodedAddress = encodeURIComponent(address);
        window.open(`https://www.google.com/maps/search/?api=1&query=${encodedAddress}`, '_blank');
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadDeliveryTasks();
    });
</script>
@endpush

@endsection