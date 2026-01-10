<?php
// app/Http/Controllers/Staff/VoucherController.php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();
        
        // Filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('voucherStatus', $request->status);
        }
        
        if ($request->has('type') && $request->type != 'all') {
            $query->where('voucherType', $request->type);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('voucher_id', 'LIKE', "%{$search}%")
                  ->orWhere('voucherCode', 'LIKE', "%{$search}%");
            });
        }
        
        $vouchers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Voucher::count(),
            'active' => Voucher::where('voucherStatus', 'active')->count(),
            'expired' => Voucher::where('voucherStatus', 'expired')->count(),
            'used' => Voucher::sum('used_count'),
        ];
        
        return view('staff.vouchers.index', compact('vouchers', 'stats'));
    }

    public function create()
    {
        return view('staff.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucherCode' => 'required|string|max:20|unique:voucher,voucherCode',
            'voucherAmount' => 'required|numeric|min:0',
            'voucherType' => 'required|in:fixed,percentage',
            'expiryDate' => 'nullable|date|after:today',
            'max_usage' => 'nullable|integer|min:1',
            'minimum_spend' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        // If percentage type, amount should be between 0-100
        if ($request->voucherType === 'percentage' && ($request->voucherAmount < 0 || $request->voucherAmount > 100)) {
            return back()->with('error', 'Percentage discount must be between 0 and 100.')
                        ->withInput();
        }

        // Generate voucher ID
        $voucherId = 'VCH' . date('Ymd') . strtoupper(uniqid());

        $voucher = Voucher::create([
            'voucher_id' => $voucherId,
            'voucherCode' => strtoupper($request->voucherCode),
            'voucherAmount' => $request->voucherAmount,
            'voucherType' => $request->voucherType,
            'voucherStatus' => 'active',
            'expiryDate' => $request->expiryDate,
            'max_usage' => $request->max_usage,
            'minimum_spend' => $request->minimum_spend,
            'description' => $request->description,
            'used_count' => 0,
        ]);

        return redirect()->route('staff.vouchers.index')
                       ->with('success', 'Voucher created successfully!');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('staff.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        
        $request->validate([
            'voucherCode' => 'required|string|max:20|unique:voucher,voucherCode,' . $id . ',voucher_id',
            'voucherAmount' => 'required|numeric|min:0',
            'voucherType' => 'required|in:fixed,percentage',
            'expiryDate' => 'nullable|date',
            'max_usage' => 'nullable|integer|min:1',
            'minimum_spend' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'voucherStatus' => 'required|in:active,expired,inactive',
        ]);

        // If percentage type, amount should be between 0-100
        if ($request->voucherType === 'percentage' && ($request->voucherAmount < 0 || $request->voucherAmount > 100)) {
            return back()->with('error', 'Percentage discount must be between 0 and 100.')
                        ->withInput();
        }

        $voucher->update([
            'voucherCode' => strtoupper($request->voucherCode),
            'voucherAmount' => $request->voucherAmount,
            'voucherType' => $request->voucherType,
            'expiryDate' => $request->expiryDate,
            'max_usage' => $request->max_usage,
            'minimum_spend' => $request->minimum_spend,
            'description' => $request->description,
            'voucherStatus' => $request->voucherStatus,
        ]);

        return redirect()->route('staff.vouchers.index')
                       ->with('success', 'Voucher updated successfully!');
    }

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        
        $newStatus = $voucher->voucherStatus === 'active' ? 'inactive' : 'active';
        
        $voucher->update([
            'voucherStatus' => $newStatus
        ]);
        
        return back()->with('success', "Voucher {$newStatus} successfully!");
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        
        // Check if voucher has been used
        if ($voucher->used_count > 0) {
            return back()->with('error', 'Cannot delete voucher that has been used.');
        }
        
        $voucher->delete();
        
        return redirect()->route('staff.vouchers.index')
                       ->with('success', 'Voucher deleted successfully!');
    }
}