<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    // Admin views all commissions
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $query = Commission::with(['staff', 'booking', 'approver'])
            ->orderBy('created_at', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $commissions = $query->paginate(20);
        
        $stats = [
            'pending' => Commission::where('status', 'pending')->count(),
            'approved' => Commission::where('status', 'approved')->count(),
            'rejected' => Commission::where('status', 'rejected')->count(),
            'total_pending_amount' => Commission::where('status', 'pending')->sum('amount'),
        ];
        
        return view('admin.commissions.index', compact('commissions', 'stats', 'status'));
    }

    // Approve commission
    public function approve(Request $request, $id)
    {
        $commission = Commission::findOrFail($id);
        
        $commission->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Commission approved successfully!');
    }

    // Reject commission
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10'
        ]);

        $commission = Commission::findOrFail($id);
        
        $commission->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Commission rejected.');
    }
}