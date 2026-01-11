@extends('staff.layouts.app')

@section('title', 'Staff Management - Staff Portal')
@section('page-title', 'Staff Management')

@section('content')
<div class="space-y-6">
    
    <!-- Admin Check - Hide page if not admin -->
    @if(Auth::guard('staff')->user()->role !== 'admin')
    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
        <i data-lucide="shield-alert" class="w-16 h-16 text-red-500 mx-auto mb-4"></i>
        <h3 class="text-xl font-bold text-red-800 mb-2">Access Denied</h3>
        <p class="text-red-600">Only administrators can access this page.</p>
    </div>
    @else

    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Staff Management</h2>
            <p class="text-gray-600 mt-1">Manage staff accounts and permissions</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="refreshStaff()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>Refresh</span>
            </button>
            <button onclick="showAddStaffModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Add Staff</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Staff</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="total-staff">0</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Admin</p>
                    <p class="text-2xl font-bold text-red-600 mt-1" id="admin-count">0</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <i data-lucide="shield" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Regular Staff</p>
                    <p class="text-2xl font-bold text-green-600 mt-1" id="staff-count">0</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="user" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Runners</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1" id="runner-count">0</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="truck" class="w-6 h-6 text-purple-600"></i>
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
                    <input type="text" id="search-input" placeholder="Search staff..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           onkeyup="filterStaff()">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select id="role-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterStaff()">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="runner">Runner</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        onchange="filterStaff()">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Staff List</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Staff ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">IC Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Joined Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="staff-table" class="divide-y divide-gray-200">
                    <!-- Loading state -->
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-200 text-center" id="loading-indicator">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 text-sm mt-2">Loading staff...</p>
        </div>
    </div>

    @endif
</div>

<!-- Add Staff Modal -->
<div id="staff-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">Add New Staff</h3>
            <button onclick="closeStaffModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form id="staff-form" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                <input type="text" id="staff-name" required
                       placeholder="Enter full name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">IC Number *</label>
                <input type="text" id="staff-ic" required
                       placeholder="123456-12-1234"
                       pattern="[0-9]{6}-[0-9]{2}-[0-9]{4}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Format: 123456-12-1234</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                <select id="staff-role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="runner">Runner</option>
                </select>
            </div>

            <!-- Generated credentials will show here -->
            <div id="generated-credentials" class="hidden p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm font-semibold text-green-800 mb-2">✓ Staff Account Created Successfully!</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Staff ID:</span>
                        <span class="font-mono font-bold text-gray-800" id="generated-id"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Password:</span>
                        <span class="font-mono font-bold text-gray-800" id="generated-password"></span>
                    </div>
                </div>
                <p class="text-xs text-green-700 mt-3">⚠️ Please save these credentials. The password cannot be retrieved later.</p>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" id="submit-btn" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Create Staff Account
                </button>
                <button type="button" onclick="closeStaffModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Staff Details Modal -->
<div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">Staff Details</h3>
            <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div id="staff-details-content" class="p-6">
            <!-- Content populated by JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allStaff = [];
    let filteredStaff = [];

    // Fetch staff members
    async function fetchStaff() {
        try {
            const mockStaff = generateMockStaff();
            allStaff = mockStaff;
            filteredStaff = [...allStaff];
            updateStaffDisplay();
            updateStats();
        } catch (error) {
            console.error('Error fetching staff:', error);
            showToast('Error loading staff', 'error');
        }
    }

    // Generate mock staff
    function generateMockStaff() {
        const names = ['Ahmad Fauzi', 'Siti Nurhaliza', 'Lee Chong Wei', 'Kumar Selvam', 'Tan Mei Ling'];
        const roles = ['admin', 'staff', 'runner'];
        
        return Array.from({ length: 8 }, (_, i) => ({
            id: i + 1,
            staff_id: `STF${String(i + 1001).padStart(4, '0')}`,
            name: names[Math.floor(Math.random() * names.length)],
            ic_number: `${Math.floor(Math.random() * 900000 + 100000)}-${Math.floor(Math.random() * 90 + 10)}-${Math.floor(Math.random() * 9000 + 1000)}`,
            role: roles[Math.floor(Math.random() * roles.length)],
            status: Math.random() > 0.2 ? 'active' : 'inactive',
            joined_date: new Date(Date.now() - Math.random() * 365 * 24 * 60 * 60 * 1000).toISOString(),
        }));
    }

    // Update staff display
    function updateStaffDisplay() {
        const tbody = document.getElementById('staff-table');
        const loading = document.getElementById('loading-indicator');
        
        loading.style.display = 'none';
        
        if (filteredStaff.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-500 text-lg">No staff found</p>
                    </td>
                </tr>
            `;
            lucide.createIcons();
            return;
        }
        
        tbody.innerHTML = filteredStaff.map(staff => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono font-semibold text-gray-800">${staff.staff_id}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <span class="text-red-600 font-semibold">${staff.name.charAt(0)}</span>
                        </div>
                        <p class="font-medium text-gray-800">${staff.name}</p>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono text-sm text-gray-800">${staff.ic_number}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getRoleBadge(staff.role)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getStatusBadge(staff.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${formatDate(staff.joined_date)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <button onclick="viewStaff(${staff.id})" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                        <button onclick="toggleStaffStatus(${staff.id})" 
                                class="p-2 ${staff.status === 'active' ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50'} rounded-lg transition"
                                title="${staff.status === 'active' ? 'Deactivate' : 'Activate'}">
                            <i data-lucide="${staff.status === 'active' ? 'user-x' : 'user-check'}" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        lucide.createIcons();
    }

    // Update statistics
    function updateStats() {
        document.getElementById('total-staff').textContent = allStaff.length;
        document.getElementById('admin-count').textContent = allStaff.filter(s => s.role === 'admin').length;
        document.getElementById('staff-count').textContent = allStaff.filter(s => s.role === 'staff').length;
        document.getElementById('runner-count').textContent = allStaff.filter(s => s.role === 'runner').length;
    }

    // Filter staff
    function filterStaff() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const roleFilter = document.getElementById('role-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        
        filteredStaff = allStaff.filter(staff => {
            const matchesSearch = !searchTerm || 
                staff.name.toLowerCase().includes(searchTerm) ||
                staff.staff_id.toLowerCase().includes(searchTerm) ||
                staff.ic_number.includes(searchTerm);
            
            const matchesRole = !roleFilter || staff.role === roleFilter;
            const matchesStatus = !statusFilter || staff.status === statusFilter;
            
            return matchesSearch && matchesRole && matchesStatus;
        });
        
        updateStaffDisplay();
    }

    // Show add staff modal
    function showAddStaffModal() {
        document.getElementById('staff-form').reset();
        document.getElementById('generated-credentials').classList.add('hidden');
        document.getElementById('submit-btn').disabled = false;
        document.getElementById('staff-modal').classList.remove('hidden');
    }

    // Close staff modal
    function closeStaffModal() {
        document.getElementById('staff-modal').classList.add('hidden');
    }

    // Generate staff ID
    function generateStaffId() {
        const maxId = allStaff.length > 0 
            ? Math.max(...allStaff.map(s => parseInt(s.staff_id.substring(3)))) 
            : 1000;
        return `STF${String(maxId + 1).padStart(4, '0')}`;
    }

    // Generate random password
    function generatePassword() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let password = '';
        for (let i = 0; i < 8; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }

    // Handle form submission
    document.getElementById('staff-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const name = document.getElementById('staff-name').value;
        const ic = document.getElementById('staff-ic').value;
        const role = document.getElementById('staff-role').value;
        
        // Validate IC format
        const icPattern = /^[0-9]{6}-[0-9]{2}-[0-9]{4}$/;
        if (!icPattern.test(ic)) {
            showToast('Invalid IC number format', 'error');
            return;
        }
        
        // Generate credentials
        const staffId = generateStaffId();
        const password = generatePassword();
        
        // Create new staff
        const newStaff = {
            id: allStaff.length + 1,
            staff_id: staffId,
            name: name,
            ic_number: ic,
            role: role,
            status: 'active',
            joined_date: new Date().toISOString(),
            password: password, // In production, this should be hashed
        };
        
        allStaff.push(newStaff);
        filteredStaff = [...allStaff];
        
        // Show generated credentials
        document.getElementById('generated-id').textContent = staffId;
        document.getElementById('generated-password').textContent = password;
        document.getElementById('generated-credentials').classList.remove('hidden');
        document.getElementById('submit-btn').disabled = true;
        
        updateStaffDisplay();
        updateStats();
        showToast('Staff account created successfully!', 'success');
        
        // In production, send this data to the server
        // await apiRequest('/api/staff/create', {
        //     method: 'POST',
        //     body: JSON.stringify(newStaff)
        // });
    });

    // View staff details
    function viewStaff(staffId) {
        const staff = allStaff.find(s => s.id === staffId);
        if (!staff) return;
        
        showStaffDetails(staff);
    }

    // Show staff details modal
    function showStaffDetails(staff) {
        const modal = document.getElementById('details-modal');
        const content = document.getElementById('staff-details-content');
        
        content.innerHTML = `
            <div class="space-y-6">
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-red-600 font-bold text-2xl">${staff.name.charAt(0)}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">${staff.name}</h4>
                                <p class="text-sm text-gray-600 mt-1">${staff.staff_id}</p>
                            </div>
                            <div class="text-right">
                                ${getRoleBadge(staff.role)}
                                <div class="mt-2">${getStatusBadge(staff.status)}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h5 class="font-semibold text-gray-800 mb-3">Personal Information</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">IC Number</p>
                            <p class="font-mono font-medium text-gray-800">${staff.ic_number}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Joined Date</p>
                            <p class="font-medium text-gray-800">${formatDate(staff.joined_date)}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="resetPassword(${staff.id})" 
                            class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition flex items-center justify-center gap-2">
                        <i data-lucide="key" class="w-4 h-4"></i>
                        <span>Reset Password</span>
                    </button>
                    <button onclick="toggleStaffStatus(${staff.id})" 
                            class="flex-1 px-4 py-2 ${staff.status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'} text-white rounded-lg transition flex items-center justify-center gap-2">
                        <i data-lucide="${staff.status === 'active' ? 'user-x' : 'user-check'}" class="w-4 h-4"></i>
                        <span>${staff.status === 'active' ? 'Deactivate' : 'Activate'}</span>
                    </button>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
        lucide.createIcons();
    }

    // Close details modal
    function closeDetailsModal() {
        document.getElementById('details-modal').classList.add('hidden');
    }

    // Toggle staff status
    function toggleStaffStatus(staffId) {
        const staff = allStaff.find(s => s.id === staffId);
        if (!staff) return;
        
        const action = staff.status === 'active' ? 'deactivate' : 'activate';
        if (confirm(`Are you sure you want to ${action} this staff member?`)) {
            staff.status = staff.status === 'active' ? 'inactive' : 'active';
            updateStaffDisplay();
            closeDetailsModal();
            showToast(`Staff ${action}d successfully!`, 'success');
        }
    }

    // Reset password
    function resetPassword(staffId) {
        const staff = allStaff.find(s => s.id === staffId);
        if (!staff) return;
        
        if (confirm(`Reset password for ${staff.name}?`)) {
            const newPassword = generatePassword();
            staff.password = newPassword;
            
            alert(`New password for ${staff.staff_id}:\n\n${newPassword}\n\nPlease save this password. It cannot be retrieved later.`);
            showToast('Password reset successfully!', 'success');
        }
    }

    // Refresh staff
    async function refreshStaff() {
        showToast('Refreshing staff list...', 'info');
        await fetchStaff();
        showToast('Staff list refreshed!', 'success');
    }

    // Get role badge
    function getRoleBadge(role) {
        const badges = {
            'admin': '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Admin</span>',
            'staff': '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Staff</span>',
            'runner': '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Runner</span>',
        };
        return badges[role] || '';
    }

    // Get status badge
    function getStatusBadge(status) {
        const badges = {
            'active': '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Active</span>',
            'inactive': '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Inactive</span>',
        };
        return badges[status] || '';
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        fetchStaff();
        startRealTimeUpdates(fetchStaff, 30000);
    });

    // Close modals on backdrop click
    document.getElementById('staff-modal').addEventListener('click', function(e) {
        if (e.target === this) closeStaffModal();
    });
    document.getElementById('details-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailsModal();
    });
</script>
@endpush

@endsection