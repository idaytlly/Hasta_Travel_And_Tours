<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VoucherController extends Controller
{
    /**
     * Display a listing of active vouchers
     * GET /api/vouchers
     */
    public function index(): JsonResponse
    {
        $vouchers = Voucher::active()->get();

        return response()->json([
            'success' => true,
            'data' => $vouchers->map(function($voucher) {
                return [
                    'code' => $voucher->code,
                    'description' => $voucher->description,
                    'type' => $voucher->type,
                    'value' => $voucher->value,
                    'validFrom' => $voucher->valid_from?->format('Y-m-d'),
                    'validUntil' => $voucher->valid_until?->format('Y-m-d'),
                    'usageLimit' => $voucher->usage_limit,
                    'usedCount' => $voucher->used_count,
                ];
            }),
        ]);
    }

    /**
     * Validate a voucher code
     * POST /api/vouchers/validate
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid voucher code',
            ], 404);
        }

        if (!$voucher->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher is expired or no longer valid',
            ], 422);
        }

        $discount = $voucher->calculateDiscount($request->amount);
        $finalAmount = $request->amount - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Voucher is valid',
            'data' => [
                'code' => $voucher->code,
                'description' => $voucher->description,
                'type' => $voucher->type,
                'discountAmount' => $discount,
                'finalAmount' => $finalAmount,
            ],
        ]);
    }
}
