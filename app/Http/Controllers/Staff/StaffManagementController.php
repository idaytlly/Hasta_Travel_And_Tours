<?php
// app/Http/Controllers/Staff/StaffManagementController.php

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
     * Display a listing of staff members.
     */
    public function index(Request $request)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $query = Staff::query();
        
        // Search filter
        if ($request->has('search')) {
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
        
        $staff = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistics
        $stats = [
            'total' => Staff::count(),
            'admin' => Staff::where('role', 'admin')->count(),
            'staff' => Staff::where('role', 'staff')->count(),
            'runner' => Staff::where('role', 'runner')->count(),
            'active' => Staff::where('status', 'active')->count(),
            'inactive' => Staff::where('status', 'inactive')->count(),
        ];
        
        return view('staff.staff-management.index', compact('staff', 'stats'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }
        
        return view('staff.staff-management.create');
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:staff,email',
            'ic_number' => 'required|string|max:20|unique:staff,ic_number',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,staff,runner',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate staff ID
        $staffId = 'STF' . strtoupper(Str::random(3)) . date('ymd');
        
        // Generate random password
        $password = Str::random(8);

        try {
            $staff = Staff::create([
                'staff_id' => $staffId,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'ic_number' => $request->ic_number,
                'phone' => $request->phone,
                'role' => $request->role,
                'status' => $request->status,
                'created_by' => $currentStaff->id,
            ]);

            // Store the generated password in session to show to admin
            session()->flash('generated_password', $password);
            session()->flash('generated_staff_id', $staffId);

            return redirect()->route('staff.staff-management.show', $staff->id)
                ->with('success', 'Staff account created successfully!')
                ->with('credentials', [
                    'staff_id' => $staffId,
                    'password' => $password
                ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create staff account: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified staff member.
     */
    public function show($id)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $staff = Staff::findOrFail($id);
        
        return view('staff.staff-management.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit($id)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $staff = Staff::findOrFail($id);
        
        // Prevent editing own account (should use profile page instead)
        if ($staff->id === $currentStaff->id) {
            return redirect()->route('staff.staff-management.show', $id)
                ->with('warning', 'Please use your profile page to edit your own account.');
        }
        
        return view('staff.staff-management.edit', compact('staff'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, $id)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $staff = Staff::findOrFail($id);
        
        // Prevent editing own account
        if ($staff->id === $currentStaff->id) {
            return redirect()->route('staff.staff-management.show', $id)
                ->with('error', 'Cannot edit your own account from this page.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:staff,email,' . $id,
            'ic_number' => 'required|string|max:20|unique:staff,ic_number,' . $id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,staff,runner',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $staff->update([
                'name' => $request->name,
                'email' => $request->email,
                'ic_number' => $request->ic_number,
                'phone' => $request->phone,
                'role' => $request->role,
                'status' => $request->status,
                'updated_by' => $currentStaff->id,
            ]);

            return redirect()->route('staff.staff-management.show', $staff->id)
                ->with('success', 'Staff account updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update staff account: ' . $e->getMessage())
                ->withInput();
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

        $staff = Staff::findOrFail($id);
        
        // Generate new random password
        $newPassword = Str::random(8);
        
        try {
            $staff->update([
                'password' => Hash::make($newPassword),
                'password_changed_at' => now(),
                'updated_by' => $currentStaff->id,
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

        $staff = Staff::findOrFail($id);
        
        // Prevent deactivating own account
        if ($staff->id === $currentStaff->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot deactivate your own account.'
            ], 400);
        }

        try {
            $newStatus = $staff->status === 'active' ? 'inactive' : 'active';
            $staff->update([
                'status' => $newStatus,
                'updated_by' => $currentStaff->id,
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

    /**
     * Export staff list.
     */
    public function export(Request $request)
    {
        // Check if current user is admin
        $currentStaff = Auth::guard('staff')->user();
        if ($currentStaff->role !== 'admin') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $staff = Staff::orderBy('created_at', 'desc')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="staff-list-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($staff) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Staff ID',
                'Name',
                'Email',
                'IC Number',
                'Phone',
                'Role',
                'Status',
                'Created At',
                'Last Login'
            ]);
            
            // Data rows
            foreach ($staff as $member) {
                fputcsv($file, [
                    $member->staff_id,
                    $member->name,
                    $member->email,
                    $member->ic_number,
                    $member->phone,
                    ucfirst($member->role),
                    ucfirst($member->status),
                    $member->created_at->format('Y-m-d H:i:s'),
                    $member->last_login_at ? $member->last_login_at->format('Y-m-d H:i:s') : 'Never'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}