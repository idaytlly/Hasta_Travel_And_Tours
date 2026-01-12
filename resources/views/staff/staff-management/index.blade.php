@extends('staff.layouts.app')

@section('title', 'Staff Management - Staff Portal')
@section('page-title', 'Staff Management')

@section('content')
<!-- Admin Check -->
@if(Auth::guard('staff')->user()->role !== 'admin')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white border-l-4 border-red-500 rounded-lg shadow-lg p-8 text-center max-w-2xl mx-auto">
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="shield-alert" class="w-10 h-10 text-red-600"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Access Denied</h3>
        <p class="text-gray-600 mb-6">Only administrators can access the Staff Management portal.</p>
        <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-[#dc2626] hover:bg-[#b91c1c] text-white font-semibold rounded-lg transition">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>
@else

<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#dc2626] mb-1">Staff Directory</h1>
                <p class="text-gray-600">Manage your team access and permissions efficiently</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center px-6 py-3 bg-[#dc2626] hover:bg-[#b91c1c] text-white font-semibold rounded-lg shadow-md transition-colors">
                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                Add New Staff
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-[#dc2626] to-[#b91c1c] rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Total Personnel</p>
                    <p class="text-4xl font-bold" id="stat-total">0</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Active Staff</p>
                    <p class="text-4xl font-bold" id="stat-active">0</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-check" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium mb-1">Administrators</p>
                    <p class="text-4xl font-bold" id="stat-admin">0</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">This Month</p>
                    <p class="text-4xl font-bold" id="stat-new">0</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-plus-2" class="w-8 h-8"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Staff Members</h2>
            <div class="flex gap-2">
                <button onclick="toggleColumnVisibility()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                    <span id="toggle-text">Show IC</span>
                </button>
                <button onclick="fetchStaff()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ic-column hidden">IC Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="staff-table-body" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-red-50 rounded-full mb-3">
                                <i data-lucide="loader" class="w-6 h-6 text-[#dc2626] animate-spin"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Loading staff members...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewDetailsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-2xl overflow-hidden">
        <div class="bg-[#dc2626] px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Staff Details</h3>
            <button onclick="closeViewModal()" class="text-white hover:text-gray-200 transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="p-6" id="staff-details-content">
            <!-- Populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div id="editStaffModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-2xl overflow-hidden">
        <div class="bg-[#dc2626] px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Edit Staff Information</h3>
            <button onclick="closeEditModal()" class="text-white hover:text-gray-200 transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form id="editStaffForm" class="p-6 space-y-5">
            @csrf
            <input type="hidden" id="edit-staff-id">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input type="text" id="edit-name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="edit-email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">IC Number</label>
                    <input type="text" id="edit-ic" placeholder="XXXXXX-XX-XXXX" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                <input type="tel" id="edit-phone" placeholder="+60 XXX XXXXXX" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role Assignment</label>
                <select id="edit-role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
                    <option value="staff">Regular Staff</option>
                    <option value="runner">Runner / Driver</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" id="edit-save-btn" class="flex-1 px-6 py-3 bg-[#dc2626] hover:bg-[#b91c1c] text-white font-semibold rounded-lg shadow-md transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Staff Modal -->
<div id="addStaffModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-2xl overflow-hidden">
        <div class="bg-[#dc2626] px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Create Staff Account</h3>
            <button onclick="closeAddModal()" class="text-white hover:text-gray-200 transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form id="addStaffForm" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">IC Number</label>
                    <input type="text" name="ic_number" placeholder="XXXXXX-XX-XXXX" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone_no" placeholder="+60 XXX XXXXXX" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role Assignment</label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#dc2626] focus:border-[#dc2626] outline-none transition">
                    <option value="staff">Regular Staff</option>
                    <option value="runner">Runner / Driver</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div id="credentials-display" class="hidden p-5 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-green-900 font-bold mb-3">Account Created Successfully!</p>
                        <div class="space-y-2 bg-white rounded-lg p-3">
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Staff ID:</p>
                                <p class="text-lg font-mono font-bold text-gray-900" id="new-staff-id">-</p>
                            </div>
                            <div class="pt-2 border-t border-gray-200">
                                <p class="text-xs text-gray-500 font-medium">Temporary Password:</p>
                                <p class="text-lg font-mono font-bold text-gray-900" id="new-staff-pass">-</p>
                            </div>
                        </div>
                        <p class="text-green-700 text-xs mt-3 font-medium">⚠️ Save these credentials - they cannot be retrieved later!</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeAddModal()" class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" id="save-btn" class="flex-1 px-6 py-3 bg-[#dc2626] hover:bg-[#b91c1c] text-white font-semibold rounded-lg shadow-md transition-colors">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let allStaff = [];
let icColumnVisible = false;

// Fetch staff from API
async function fetchStaff() {
    try {
        showLoading();
        
        const response = await fetch("{{ route('api.staff.index') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        allStaff = await response.json();
        console.log('Loaded staff data:', allStaff);
        
        updateStaffDisplay();
        updateStats();
        
    } catch (error) {
        console.error('Error fetching staff:', error);
        showError('Failed to load staff: ' + error.message);
    }
}

function showLoading() {
    document.getElementById('staff-table-body').innerHTML = `
        <tr>
            <td colspan="7" class="px-6 py-12 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-red-50 rounded-full mb-3">
                    <i data-lucide="loader" class="w-6 h-6 text-[#dc2626] animate-spin"></i>
                </div>
                <p class="text-gray-500 font-medium">Loading staff members...</p>
            </td>
        </tr>
    `;
    lucide.createIcons();
}

function showError(message) {
    document.getElementById('staff-table-body').innerHTML = `
        <tr>
            <td colspan="7" class="px-6 py-12 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-3">
                    <i data-lucide="alert-circle" class="w-6 h-6 text-[#dc2626]"></i>
                </div>
                <p class="text-red-600 font-medium">Failed to load staff members</p>
                <p class="text-gray-500 text-sm mt-1">${message}</p>
                <button onclick="fetchStaff()" class="mt-4 inline-flex items-center px-4 py-2 bg-[#dc2626] hover:bg-[#b91c1c] text-white font-medium rounded-lg transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Try Again
                </button>
            </td>
        </tr>
    `;
    lucide.createIcons();
}

function updateStaffDisplay() {
    const tbody = document.getElementById('staff-table-body');
    
    if (!Array.isArray(allStaff) || allStaff.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-red-50 rounded-full mb-3">
                        <i data-lucide="users" class="w-6 h-6 text-red-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No staff members found</p>
                    <p class="text-gray-400 text-sm mt-1">Click "Add New Staff" to create your first staff member</p>
                </td>
            </tr>
        `;
        lucide.createIcons();
        return;
    }
    
    tbody.innerHTML = allStaff.map(staff => `
        <tr class="hover:bg-red-50 transition">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-[#dc2626] font-bold">
                        ${staff.name ? staff.name.charAt(0).toUpperCase() : '?'}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">${staff.name || 'N/A'}</p>
                        <p class="text-sm text-gray-500">${staff.email || 'No email'}</p>
                        <p class="text-xs text-gray-400 font-mono">${staff.staff_id || 'No ID'}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 ic-column hidden">
                <span class="font-mono text-sm text-gray-700">${staff.ic_number || 'N/A'}</span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                    <span class="text-sm text-gray-700">${staff.phone_no || 'Not set'}</span>
                </div>
            </td>
            <td class="px-6 py-4">
                ${getRoleBadge(staff.role)}
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-600">
                    ${formatDate(staff.created_at)}
                </div>
            </td>
            <td class="px-6 py-4">
                ${getStatusBadge(staff.status)}
            </td>
            <td class="px-6 py-4">
                <div class="flex justify-end gap-1">
                    <button onclick="viewStaffDetails('${staff.id}')" class="p-2 hover:bg-blue-100 rounded-lg transition" title="View Details">
                        <i data-lucide="eye" class="w-5 h-5 text-blue-600"></i>
                    </button>
                    <button onclick="editStaff('${staff.id}')" class="p-2 hover:bg-amber-100 rounded-lg transition" title="Edit">
                        <i data-lucide="edit" class="w-5 h-5 text-amber-600"></i>
                    </button>
                    <button onclick="toggleStaffStatus('${staff.id}')" class="p-2 hover:bg-purple-100 rounded-lg transition" title="${staff.status === 'active' ? 'Deactivate' : 'Activate'}">
                        <i data-lucide="${staff.status === 'active' ? 'user-x' : 'user-check'}" class="w-5 h-5 text-purple-600"></i>
                    </button>
                    <button onclick="resetPassword('${staff.id}')" class="p-2 hover:bg-green-100 rounded-lg transition" title="Reset Password">
                        <i data-lucide="key" class="w-5 h-5 text-green-600"></i>
                    </button>
                    <button onclick="deleteStaff('${staff.id}')" class="p-2 hover:bg-red-100 rounded-lg transition" title="Delete">
                        <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    lucide.createIcons();
}

function updateStats() {
    const total = Array.isArray(allStaff) ? allStaff.length : 0;
    const active = Array.isArray(allStaff) ? allStaff.filter(s => s.status === 'active').length : 0;
    const admin = Array.isArray(allStaff) ? allStaff.filter(s => s.role === 'admin').length : 0;
    
    const now = new Date();
    const thisMonth = Array.isArray(allStaff) ? allStaff.filter(s => {
        const created = new Date(s.created_at);
        return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
    }).length : 0;
    
    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-active').textContent = active;
    document.getElementById('stat-admin').textContent = admin;
    document.getElementById('stat-new').textContent = thisMonth;
}

function toggleColumnVisibility() {
    icColumnVisible = !icColumnVisible;
    const columns = document.querySelectorAll('.ic-column');
    const toggleText = document.getElementById('toggle-text');
    
    columns.forEach(col => {
        if (icColumnVisible) {
            col.classList.remove('hidden');
        } else {
            col.classList.add('hidden');
        }
    });
    
    toggleText.textContent = icColumnVisible ? 'Hide IC' : 'Show IC';
    lucide.createIcons();
}

function viewStaffDetails(staffId) {
    const staff = allStaff.find(s => s.id === staffId);
    if (!staff) return;
    
    const content = document.getElementById('staff-details-content');
    content.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-center gap-4 pb-6 border-b">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center text-[#dc2626] font-bold text-3xl">
                    ${staff.name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">${staff.name}</h3>
                    <p class="text-gray-600">${staff.email}</p>
                    <p class="text-sm text-gray-500 font-mono">${staff.staff_id}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">IC Number</p>
                    <p class="font-mono text-gray-900">${staff.ic_number || 'N/A'}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Phone Number</p>
                    <p class="text-gray-900">${staff.phone_no || 'Not set'}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Role</p>
                    <div class="mt-1">${getRoleBadge(staff.role)}</div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                    <div class="mt-1">${getStatusBadge(staff.status)}</div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Enrolled Date</p>
                    <p class="text-gray-900">${formatDate(staff.created_at)}</p>
                </div>
                                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Last Updated</p>
                    <p class="text-gray-900">${formatDate(staff.updated_at || staff.created_at)}</p>
                </div>
            </div>
            
            <div class="pt-6 border-t">
                <h4 class="font-bold text-gray-900 mb-3">Account Actions</h4>
                <div class="flex gap-3">
                    <button onclick="editStaff('${staff.id}'); closeViewModal()" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Information
                    </button>
                    <button onclick="resetPassword('${staff.id}'); closeViewModal()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <i data-lucide="key" class="w-4 h-4 mr-2"></i>
                        Reset Password
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('viewDetailsModal').classList.remove('hidden');
    lucide.createIcons();
}

function editStaff(staffId) {
    const staff = allStaff.find(s => s.id === staffId);
    if (!staff) return;
    
    document.getElementById('edit-staff-id').value = staff.id;
    document.getElementById('edit-name').value = staff.name || '';
    document.getElementById('edit-email').value = staff.email || '';
    document.getElementById('edit-ic').value = staff.ic_number || '';
    document.getElementById('edit-phone').value = staff.phone_no || '';
    document.getElementById('edit-role').value = staff.role || 'staff';
    
    document.getElementById('editStaffModal').classList.remove('hidden');
    lucide.createIcons();
}

async function deleteStaff(staffId) {
    const staff = allStaff.find(s => s.id == staffId);
    if (!staff) return;
    
    if (confirm(`Are you sure you want to permanently delete ${staff.name}?\n\nThis action cannot be undone!`)) {
        try {
            const response = await fetch(`/api/staff/staff/${staffId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                await fetchStaff();
                alert(`Staff ${staff.name} has been deleted successfully!`);
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to delete staff');
            }
        } catch (error) {
            alert('Error deleting staff: ' + error.message);
        }
    }
}

// Form Submission for Edit
document.getElementById('editStaffForm').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('edit-save-btn');
    btn.textContent = 'Saving...';
    btn.disabled = true;

    try {
        const staffId = document.getElementById('edit-staff-id').value;
        const formData = {
            name: document.getElementById('edit-name').value,
            email: document.getElementById('edit-email').value,
            ic_number: document.getElementById('edit-ic').value,
            phone_no: document.getElementById('edit-phone').value,
            role: document.getElementById('edit-role').value,
            _method: 'PUT'
        };
        
        const response = await fetch(`/api/staff/staff/${staffId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        
        if(response.ok) {
            alert('Staff information updated successfully!');
            closeEditModal();
            fetchStaff();
        } else {
            throw new Error(data.message || 'Failed to update staff');
        }
    } catch (error) {
        alert('Error: ' + error.message);
        btn.disabled = false;
        btn.textContent = 'Save Changes';
    }
};

// Toggle staff status
async function toggleStaffStatus(staffId) {
    const staff = allStaff.find(s => s.id == staffId);
    if (!staff) return;
    
    const action = staff.status === 'active' ? 'deactivate' : 'activate';
    const newStatus = staff.status === 'active' ? 'inactive' : 'active';
    
    if (confirm(`Are you sure you want to ${action} ${staff.name}?`)) {
        try {
            const response = await fetch(`/api/staff/staff/${staffId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            
            if (response.ok) {
                await fetchStaff();
                alert(`Staff ${action}d successfully!`);
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to update status');
            }
        } catch (error) {
            alert('Error updating status: ' + error.message);
        }
    }
}

// Reset password
async function resetPassword(staffId) {
    const staff = allStaff.find(s => s.id == staffId);
    if (!staff) return;
    
    if (confirm(`Reset password for ${staff.name}?\n\nA new temporary password will be generated.`)) {
        try {
            const response = await fetch(`/api/staff/staff/${staffId}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                alert(`New password for ${staff.staff_id}:\n\n${result.data.new_password}\n\n⚠️ Please save this password. It cannot be retrieved later.`);
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to reset password');
            }
        } catch (error) {
            alert('Error resetting password: ' + error.message);
        }
    }
}

// Modal Controls
function openAddModal() {
    document.getElementById('addStaffModal').classList.remove('hidden');
    document.getElementById('addStaffForm').reset();
    document.getElementById('credentials-display').classList.add('hidden');
    document.getElementById('save-btn').disabled = false;
    document.getElementById('save-btn').textContent = 'Create Account';
    lucide.createIcons();
}

function closeAddModal() {
    document.getElementById('addStaffModal').classList.add('hidden');
}

function closeViewModal() {
    document.getElementById('viewDetailsModal').classList.add('hidden');
}

function closeEditModal() {
    document.getElementById('editStaffModal').classList.add('hidden');
}

// Form Submission for Add Staff
document.getElementById('addStaffForm').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('save-btn');
    btn.textContent = 'Creating...';
    btn.disabled = true;

    try {
        const formData = new FormData(e.target);
        const response = await fetch("{{ route('api.staff.store') }}", {
            method: 'POST',
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if(data.success) {
            document.getElementById('new-staff-id').textContent = data.staff_id;
            document.getElementById('new-staff-pass').textContent = data.password;
            document.getElementById('credentials-display').classList.remove('hidden');
            btn.textContent = 'Created Successfully';
            lucide.createIcons();
            
            // Refresh staff list
            setTimeout(() => {
                fetchStaff();
                // Close modal after 3 seconds
                setTimeout(closeAddModal, 3000);
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to create staff');
        }
    } catch (error) {
        alert('Error: ' + error.message);
        btn.disabled = false;
        btn.textContent = 'Create Account';
    }
};

// Helper functions
function getRoleBadge(role) {
    const badges = {
        'admin': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-[#dc2626] border border-red-200">Admin</span>',
        'staff': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Staff</span>',
        'runner': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Runner</span>',
    };
    return badges[role] || '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">Unknown</span>';
}

function getStatusBadge(status) {
    const badges = {
        'active': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Active</span>',
        'inactive': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">Inactive</span>',
    };
    return badges[status] || '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">Unknown</span>';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchStaff();
    lucide.createIcons();
});

// Close modal on backdrop click
document.getElementById('addStaffModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});

document.getElementById('viewDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
});

document.getElementById('editStaffModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endpush

@endif
@endsection