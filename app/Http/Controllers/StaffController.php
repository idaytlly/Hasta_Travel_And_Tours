<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members
     */
    public function index()
    {
        try {
            $staff = Staff::select([
                    'staff_id',  // Changed from 'id' 
                    'name', 
                    'email',
                    'ic_number', 
                    'phone_no',
                    'role', 
                    'is_active',  // Changed from 'status'
                    'created_at'
                ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($s) {
                    return [
                        'id' => $s->staff_id,  // Map staff_id to id for frontend compatibility
                        'staff_id' => $s->staff_id,
                        'name' => $s->name,
                        'email' => $s->email,
                        'ic_number' => $s->ic_number,
                        'phone_no' => $s->phone_no,
                        'role' => $s->role,
                        'status' => $s->is_active ? 'active' : 'inactive',  // Convert boolean to status string
                        'created_at' => $s->created_at
                    ];
                });
            
            return response()->json($staff);
        } catch (\Exception $e) {
            \Log::error('Staff fetch error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch staff: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created staff member
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:staff,email',
                'ic_number' => 'required|string|regex:/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/|unique:staff,ic_number',
                'phone_no' => 'nullable|string',
                'role' => 'required|in:admin,staff,runner'
            ]);

            // Generate staff ID
            $lastStaff = Staff::orderBy('created_at', 'desc')->first();
            $staffIdNumber = 1;
            
            if ($lastStaff) {
                // Extract number from last staff_id (e.g., STF-001 -> 1)
                $lastNumber = (int) str_replace(['STF-', 'STF'], '', $lastStaff->staff_id);
                $staffIdNumber = $lastNumber + 1;
            }
            
            $staffId = 'STF-' . str_pad($staffIdNumber, 3, '0', STR_PAD_LEFT);
            
            // Check if staff_id already exists
            while (Staff::where('staff_id', $staffId)->exists()) {
                $staffIdNumber++;
                $staffId = 'STF-' . str_pad($staffIdNumber, 3, '0', STR_PAD_LEFT);
            }

            // Generate random password
            $password = Str::random(10);

            // Create staff
            $staff = Staff::create([
                'staff_id' => $staffId,
                'name' => $request->name,
                'email' => $request->email,
                'ic_number' => $request->ic_number,
                'phone_no' => $request->phone_no,
                'role' => $request->role,
                'password' => Hash::make($password),
                'is_active' => true  // Changed from 'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'staff_id' => $staffId,
                'password' => $password,
                'message' => 'Staff created successfully',
                'data' => [
                    'id' => $staff->staff_id,
                    'staff_id' => $staff->staff_id,
                    'name' => $staff->name,
                    'email' => $staff->email,
                    'ic_number' => $staff->ic_number,
                    'phone_no' => $staff->phone_no,
                    'role' => $staff->role,
                    'status' => 'active',
                    'created_at' => $staff->created_at
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Staff creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to create staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update staff status
     */
    public function updateStatus(Request $request, $staff_id)  // Changed parameter name
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive'
            ]);

            $staff = Staff::where('staff_id', $staff_id)->first();  // Changed from find()
            
            if (!$staff) {
                return response()->json([
                    'success' => false,
                    'error' => 'Staff member not found'
                ], 404);
            }

            // Prevent deactivating yourself
            if ($staff->staff_id === auth()->guard('staff')->user()->staff_id && $request->status === 'inactive') {
                return response()->json([
                    'success' => false,
                    'error' => 'You cannot deactivate your own account'
                ], 403);
            }

            // Convert status string to boolean
            $staff->is_active = ($request->status === 'active');
            $staff->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => [
                    'new_status' => $request->status,
                    'status_text' => ucfirst($request->status)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset staff password
     */
    public function resetPassword($staff_id)  // Changed parameter name
    {
        try {
            $staff = Staff::where('staff_id', $staff_id)->first();  // Changed from find()
            
            if (!$staff) {
                return response()->json([
                    'success' => false,
                    'error' => 'Staff member not found'
                ], 404);
            }

            // Generate new password
            $newPassword = Str::random(10);
            $staff->password = Hash::make($newPassword);
            $staff->save();

            return response()->json([
                'success' => true,
                'password' => $newPassword,
                'message' => 'Password reset successfully',
                'data' => [
                    'new_password' => $newPassword,
                    'staff_id' => $staff->staff_id
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Update staff information
 */
public function update(Request $request, $staff_id)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'ic_number' => 'required|string',
            'phone_no' => 'nullable|string',
            'role' => 'required|in:admin,staff,runner'
        ]);

        $staff = Staff::where('staff_id', $staff_id)->first();
        
        if (!$staff) {
            return response()->json([
                'success' => false,
                'error' => 'Staff member not found'
            ], 404);
        }

        // Check if email is being changed and if it's unique
        if ($staff->email !== $request->email) {
            $emailExists = Staff::where('email', $request->email)
                               ->where('staff_id', '!=', $staff_id)
                               ->exists();
            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'error' => 'Email address already in use'
                ], 422);
            }
        }

        // Check if IC number is being changed and if it's unique
        if ($staff->ic_number !== $request->ic_number) {
            $icExists = Staff::where('ic_number', $request->ic_number)
                            ->where('staff_id', '!=', $staff_id)
                            ->exists();
            if ($icExists) {
                return response()->json([
                    'success' => false,
                    'error' => 'IC number already in use'
                ], 422);
            }
        }

        // Update staff information
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->ic_number = $request->ic_number;
        $staff->phone_no = $request->phone_no;
        $staff->role = $request->role;
        $staff->save();

        return response()->json([
            'success' => true,
            'message' => 'Staff information updated successfully',
            'data' => [
                'id' => $staff->staff_id,
                'staff_id' => $staff->staff_id,
                'name' => $staff->name,
                'email' => $staff->email,
                'ic_number' => $staff->ic_number,
                'phone_no' => $staff->phone_no,
                'role' => $staff->role,
                'status' => $staff->is_active ? 'active' : 'inactive',
                'created_at' => $staff->created_at,
                'updated_at' => $staff->updated_at
            ]
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'error' => 'Validation failed',
            'messages' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Staff update error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to update staff: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Delete staff member
 */
public function destroy($staff_id)
{
    try {
        $staff = Staff::where('staff_id', $staff_id)->first();
        
        if (!$staff) {
            return response()->json([
                'success' => false,
                'error' => 'Staff member not found'
            ], 404);
        }

        // Prevent deleting yourself
        if ($staff->staff_id === auth()->guard('staff')->user()->staff_id) {
            return response()->json([
                'success' => false,
                'error' => 'You cannot delete your own account'
            ], 403);
        }

        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff member deleted successfully'
        ]);
    } catch (\Exception $e) {
        \Log::error('Staff deletion error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to delete staff: ' . $e->getMessage()
        ], 500);
    }
}
}