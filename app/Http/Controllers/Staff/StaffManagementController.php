<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Staff;
use Illuminate\Support\Str;

class StaffManagementController extends Controller
{
    /**
     * Display a listing of staff members (API endpoint for JSON response).
     */
    public function index(Request $request)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        $query = Staff::query();
        
        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('staff_id', 'LIKE', "%{$search}%")
                  ->orWhere('ic_number', 'LIKE', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Order by created_at instead of id
        $staff = $query->orderBy('created_at', 'desc')->get();
        
        // Statistics
        $stats = [
            'total' => Staff::count(),
            'admin' => Staff::where('role', 'admin')->count(),
            'staff' => Staff::where('role', 'staff')->count(),
            'runner' => Staff::where('role', 'runner')->count(),
            'active' => Staff::where('status', 'active')->count(),
            'inactive' => Staff::where('status', 'inactive')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $staff,
            'stats' => $stats
        ]);
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:staff,email',
            'ic_number' => 'required|string|max:20|unique:staff,ic_number',
            'role' => 'required|in:admin,staff,runner',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Auto-generate Incremental Staff ID based on created_at
        $lastStaff = Staff::orderBy('created_at', 'desc')->first();
        $nextNumber = 1;
        
        if ($lastStaff) {
            // Extract number from last staff_id (e.g., STF-001 -> 1)
            $lastNumber = (int) str_replace('STF-', '', $lastStaff->staff_id);
            $nextNumber = $lastNumber + 1;
        }
        
        $staffId = 'STF-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        // Check if staff_id already exists (in case of concurrent requests)
        while (Staff::where('staff_id', $staffId)->exists()) {
            $nextNumber++;
            $staffId = 'STF-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }
        
        // Generate Random Password
        $password = Str::random(10);

        try {
            $staff = Staff::create([
                'staff_id' => $staffId,
                'name' => $request->name,
                'email' => $request->email,
                'ic_number' => $request->ic_number,
                'role' => $request->role,
                'password' => Hash::make($password),
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'staff_id' => $staffId,
                'password' => $password,
                'message' => 'Staff created successfully',
                'data' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password for a staff member.
     */
    public function resetPassword(Request $request, $id)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        $staff = Staff::find($id);
        
        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'Staff member not found.'
            ], 404);
        }
        
        // Generate new random password
        $newPassword = Str::random(10);
        
        try {
            $staff->update([
                'password' => Hash::make($newPassword),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully!',
                'data' => [
                    'new_password' => $newPassword,
                    'staff_id' => $staff->staff_id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle staff status (active/inactive).
     */
    public function toggleStatus(Request $request, $id)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        $staff = Staff::find($id);
        
        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'Staff member not found.'
            ], 404);
        }
        
        // Prevent deactivating own account
        if ($staff->id === $currentStaff->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot deactivate your own account.'
            ], 400);
        }

        try {
            $newStatus = $request->input('status', $staff->status === 'active' ? 'inactive' : 'active');
            $staff->update([
                'status' => $newStatus,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Staff status updated successfully!',
                'data' => [
                    'new_status' => $newStatus,
                    'status_text' => ucfirst($newStatus)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get staff statistics for dashboard.
     */
    public function getStats()
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $stats = [
            'total' => Staff::count(),
            'admin' => Staff::where('role', 'admin')->count(),
            'staff' => Staff::where('role', 'staff')->count(),
            'runner' => Staff::where('role', 'runner')->count(),
            'active' => Staff::where('status', 'active')->count(),
            'inactive' => Staff::where('status', 'inactive')->count(),
            'new_today' => Staff::whereDate('created_at', today())->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}