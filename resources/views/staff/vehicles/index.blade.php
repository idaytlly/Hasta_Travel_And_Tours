@extends('staff.layouts.app')

@section('title', 'Vehicle Management - Staff Portal')
@section('page-title', 'Vehicle Management')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Vehicle Fleet</h2>
            <p class="text-gray-600 mt-1">Manage your rental vehicle inventory</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="refreshVehicles()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Refresh</span>
            </button>
            <button onclick="showAddVehicleModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Add Vehicle</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="total-vehicles">0</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="car" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Available</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="available-vehicles">0</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Currently Rented</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="rented-vehicles">0</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="activity" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Maintenance</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1" id="maintenance-vehicles">0</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="wrench" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" placeholder="Search vehicles..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onkeyup="filterVehicles()">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterVehicles()">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select id="type-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterVehicles()">
                    <option value="">All Types</option>
                    <option value="sedan">Sedan</option>
                    <option value="suv">SUV</option>
                    <option value="mpv">MPV</option>
                    <option value="luxury">Luxury</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                <select id="transmission-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterVehicles()">
                    <option value="">All</option>
                    <option value="automatic">Automatic</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Vehicle Grid -->
    <div id="vehicles-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Loading state -->
        <div class="col-span-full text-center py-12">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 text-sm mt-2">Loading vehicles...</p>
        </div>
    </div>

    <!-- No Results -->
    <div id="no-results" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
        <p class="text-gray-500 text-lg">No vehicles found</p>
    </div>
</div>

<!-- Add/Edit Vehicle Modal -->
<div id="vehicle-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800" id="modal-title">Add New Vehicle</h3>
            <button onclick="closeVehicleModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form id="vehicle-form" class="p-6 space-y-4">
            <input type="hidden" id="vehicle-id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <input type="text" id="brand" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                    <input type="text" id="model" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Plate Number</label>
                    <input type="text" id="plate-number" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <input type="number" id="year" required min="2000" max="2026"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select id="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Select Type</option>
                        <option value="sedan">Sedan</option>
                        <option value="suv">SUV</option>
                        <option value="mpv">MPV</option>
                        <option value="luxury">Luxury</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select id="transmission" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Select Transmission</option>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seats</label>
                    <input type="number" id="seats" required min="2" max="9"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Daily Rate (RM)</label>
                    <input type="number" id="daily-rate" required min="50" step="10"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Save Vehicle
                </button>
                <button type="button" onclick="closeVehicleModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let allVehicles = [];
    let filteredVehicles = [];
    let editingVehicleId = null;

    // Fetch vehicles (mock data for demonstration)
    async function fetchVehicles() {
        try {
            // Simulate API call with mock data
            const mockVehicles = generateMockVehicles();
            allVehicles = mockVehicles;
            filteredVehicles = [...allVehicles];
            updateVehiclesDisplay();
            updateStats();
        } catch (error) {
            console.error('Error fetching vehicles:', error);
            showToast('Error loading vehicles', 'error');
        }
    }

    // Generate mock vehicles
    function generateMockVehicles() {
        const brands = ['Toyota', 'Honda', 'BMW', 'Mercedes', 'Mazda', 'Nissan'];
        const models = ['Camry', 'Accord', '3 Series', 'C-Class', 'CX-5', 'X-Trail'];
        const types = ['sedan', 'suv', 'mpv', 'luxury'];
        const statuses = ['available', 'rented', 'maintenance'];
        
        return Array.from({ length: 12 }, (_, i) => ({
            id: i + 1,
            brand: brands[Math.floor(Math.random() * brands.length)],
            model: models[Math.floor(Math.random() * models.length)],
            plate_number: `WP${Math.floor(Math.random() * 9000 + 1000)}${String.fromCharCode(65 + Math.floor(Math.random() * 26))}`,
            year: 2020 + Math.floor(Math.random() * 6),
            type: types[Math.floor(Math.random() * types.length)],
            transmission: Math.random() > 0.5 ? 'automatic' : 'manual',
            seats: [4, 5, 7][Math.floor(Math.random() * 3)],
            daily_rate: Math.floor(Math.random() * 200 + 100),
            status: statuses[Math.floor(Math.random() * statuses.length)],
            image: `https://via.placeholder.com/400x250?text=${brands[Math.floor(Math.random() * brands.length)]}+Vehicle`,
        }));
    }

    // Update vehicles display
    function updateVehiclesDisplay() {
        const grid = document.getElementById('vehicles-grid');
        const noResults = document.getElementById('no-results');
        
        if (filteredVehicles.length === 0) {
            grid.innerHTML = '';
            noResults.classList.remove('hidden');
            return;
        }
        
        noResults.classList.add('hidden');
        
        grid.innerHTML = filteredVehicles.map(vehicle => `
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div class="relative">
                    <img src="${vehicle.image}" alt="${vehicle.brand} ${vehicle.model}" class="w-full h-48 object-cover">
                    <div class="absolute top-2 right-2">
                        ${getStatusBadge(vehicle.status)}
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-800">${vehicle.brand} ${vehicle.model}</h3>
                    <p class="text-sm text-gray-600 mt-1">${vehicle.plate_number} • ${vehicle.year}</p>
                    
                    <div class="mt-3 flex items-center gap-4 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            ${vehicle.seats} Seats
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            ${vehicle.transmission}
                        </span>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Daily Rate</p>
                            <p class="text-xl font-bold text-red-600">${formatCurrency(vehicle.daily_rate)}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="editVehicle(${vehicle.id})" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                    title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteVehicle(${vehicle.id})" 
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        lucide.createIcons();
    }

    // Update statistics
    function updateStats() {
        document.getElementById('total-vehicles').textContent = allVehicles.length;
        document.getElementById('available-vehicles').textContent = 
            allVehicles.filter(v => v.status === 'available').length;
        document.getElementById('rented-vehicles').textContent = 
            allVehicles.filter(v => v.status === 'rented').length;
        document.getElementById('maintenance-vehicles').textContent = 
            allVehicles.filter(v => v.status === 'maintenance').length;
    }

    // Filter vehicles
    function filterVehicles() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const typeFilter = document.getElementById('type-filter').value;
        const transmissionFilter = document.getElementById('transmission-filter').value;
        
        filteredVehicles = allVehicles.filter(vehicle => {
            const matchesSearch = !searchTerm || 
                vehicle.brand.toLowerCase().includes(searchTerm) ||
                vehicle.model.toLowerCase().includes(searchTerm) ||
                vehicle.plate_number.toLowerCase().includes(searchTerm);
            
            const matchesStatus = !statusFilter || vehicle.status === statusFilter;
            const matchesType = !typeFilter || vehicle.type === typeFilter;
            const matchesTransmission = !transmissionFilter || vehicle.transmission === transmissionFilter;
            
            return matchesSearch && matchesStatus && matchesType && matchesTransmission;
        });
        
        updateVehiclesDisplay();
    }

    // Show add vehicle modal
    function showAddVehicleModal() {
        editingVehicleId = null;
        document.getElementById('modal-title').textContent = 'Add New Vehicle';
        document.getElementById('vehicle-form').reset();
        document.getElementById('vehicle-id').value = '';
        document.getElementById('vehicle-modal').classList.remove('hidden');
    }

    // Edit vehicle
    function editVehicle(vehicleId) {
        const vehicle = allVehicles.find(v => v.id === vehicleId);
        if (!vehicle) return;
        
        editingVehicleId = vehicleId;
        document.getElementById('modal-title').textContent = 'Edit Vehicle';
        document.getElementById('vehicle-id').value = vehicle.id;
        document.getElementById('brand').value = vehicle.brand;
        document.getElementById('model').value = vehicle.model;
        document.getElementById('plate-number').value = vehicle.plate_number;
        document.getElementById('year').value = vehicle.year;
        document.getElementById('type').value = vehicle.type;
        document.getElementById('transmission').value = vehicle.transmission;
        document.getElementById('seats').value = vehicle.seats;
        document.getElementById('daily-rate').value = vehicle.daily_rate;
        document.getElementById('status').value = vehicle.status;
        
        document.getElementById('vehicle-modal').classList.remove('hidden');
    }

    // Close vehicle modal
    function closeVehicleModal() {
        document.getElementById('vehicle-modal').classList.add('hidden');
    }

    // Handle form submission
    document.getElementById('vehicle-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const vehicleData = {
            brand: document.getElementById('brand').value,
            model: document.getElementById('model').value,
            plate_number: document.getElementById('plate-number').value,
            year: parseInt(document.getElementById('year').value),
            type: document.getElementById('type').value,
            transmission: document.getElementById('transmission').value,
            seats: parseInt(document.getElementById('seats').value),
            daily_rate: parseFloat(document.getElementById('daily-rate').value),
            status: document.getElementById('status').value,
        };
        
        if (editingVehicleId) {
            // Update existing vehicle
            const index = allVehicles.findIndex(v => v.id === editingVehicleId);
            allVehicles[index] = { ...allVehicles[index], ...vehicleData };
            showToast('Vehicle updated successfully!', 'success');
        } else {
            // Add new vehicle
            const newVehicle = {
                id: allVehicles.length + 1,
                ...vehicleData,
                image: `https://via.placeholder.com/400x250?text=${vehicleData.brand}+Vehicle`,
            };
            allVehicles.push(newVehicle);
            showToast('Vehicle added successfully!', 'success');
        }
        
        filteredVehicles = [...allVehicles];
        updateVehiclesDisplay();
        updateStats();
        closeVehicleModal();
    });

    // Delete vehicle
    async function deleteVehicle(vehicleId) {
        if (confirm('Are you sure you want to delete this vehicle?')) {
            allVehicles = allVehicles.filter(v => v.id !== vehicleId);
            filteredVehicles = [...allVehicles];
            updateVehiclesDisplay();
            updateStats();
            showToast('Vehicle deleted successfully!', 'success');
        }
    }

    // Refresh vehicles
    async function refreshVehicles() {
        showToast('Refreshing vehicles...', 'info');
        await fetchVehicles();
        showToast('Vehicles refreshed successfully!', 'success');
    }

    // Get status badge
    function getStatusBadge(status) {
        const badges = {
            'available': '<span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full font-medium">Available</span>',
            'rented': '<span class="px-2 py-1 bg-purple-500 text-white text-xs rounded-full font-medium">Rented</span>',
            'maintenance': '<span class="px-2 py-1 bg-orange-500 text-white text-xs rounded-full font-medium">Maintenance</span>',
        };
        return badges[status] || '';
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        fetchVehicles();
        startRealTimeUpdates(fetchVehicles, 30000);
    });

    // Close modal on backdrop click
    document.getElementById('vehicle-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeVehicleModal();
        }
    });
</script>
@endpush

@endsection