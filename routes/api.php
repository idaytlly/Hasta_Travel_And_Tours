<?php

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Events\BookingUpdated;

// Your existing verify route
Route::post('/bookings/{booking}/verify', function (Request $request, Booking $booking) {
    $request->validate([
        'staff_id' => 'required|string',
        'password' => 'required|string',
    ]);
    
    $result = $booking->verify($request->staff_id, $request->password);
    
    return response()->json($result);
});

// NEW: Add these routes for the bookings management page
Route::middleware(['auth:staff'])->prefix('staff')->group(function () {
    
    // IMPORTANT: Export route MUST come BEFORE the {id} route
    Route::get('/bookings/export', function (Request $request) {
        try {
            $query = Booking::with(['vehicle', 'customer']);
            
            if ($request->has('status')) {
                $query->where('booking_status', $request->status);
            }
            
            $bookings = $query->orderBy('created_at', 'desc')->get();

            $filename = 'bookings-export-' . date('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, [
                    'Booking ID', 'Customer Name', 'Phone', 'Email', 'Vehicle',
                    'Plate Number', 'Pickup Date', 'Pickup Time', 'Return Date',
                    'Return Time', 'Delivery Required', 'Total Price (RM)',
                    'Status', 'Special Requests', 'Created At'
                ]);

                foreach ($bookings as $booking) {
                    fputcsv($file, [
                        $booking->booking_id,
                        $booking->customer->name ?? 'N/A',
                        $booking->customer->phone ?? 'N/A',
                        $booking->customer->email ?? 'N/A',
                        ($booking->vehicle->brand ?? '') . ' ' . ($booking->vehicle->model ?? 'N/A'),
                        $booking->plate_no,
                        $booking->pickup_date,
                        $booking->pickup_time,
                        $booking->return_date,
                        $booking->return_time,
                        $booking->delivery_required ? 'Yes' : 'No',
                        number_format($booking->total_price, 2),
                        $booking->booking_status,
                        $booking->special_requests ?? '',
                        $booking->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export bookings'
            ], 500);
        }
    });
    
    // Get all bookings
    Route::get('/bookings', function () {
        try {
            $bookings = Booking::with(['vehicle', 'customer', 'payments'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($booking) {
                    $pickup = \Carbon\Carbon::parse($booking->pickup_date . ' ' . $booking->pickup_time);
                    $return = \Carbon\Carbon::parse($booking->return_date . ' ' . $booking->return_time);
                    $durationDays = ceil($pickup->diffInHours($return) / 24);
                    
                    return [
                        'id' => $booking->booking_id,
                        'booking_code' => $booking->booking_id,
                        'customer_name' => $booking->customer->name ?? 'N/A',
                        'customer_phone' => $booking->customer->phone ?? 'N/A',
                        'customer_email' => $booking->customer->email ?? 'N/A',
                        'customer_ic' => $booking->customer->ic ?? 'N/A',
                        'vehicle_name' => ($booking->vehicle->brand ?? '') . ' ' . ($booking->vehicle->model ?? 'N/A'),
                        'vehicle_plate' => $booking->plate_no,
                        'vehicle_category' => $booking->vehicle->category ?? 'N/A',
                        'start_date' => $booking->pickup_date . ' ' . $booking->pickup_time,
                        'end_date' => $booking->return_date . ' ' . $booking->return_time,
                        'duration_days' => $durationDays,
                        'pickup_type' => $booking->delivery_required ? 'delivery' : 'self-pickup',
                        'delivery_address' => $booking->pickup_location ?? null,
                        'daily_rate' => (float) ($booking->vehicle->price_perHour ?? 0),
                        'delivery_fee' => 0,
                        'total_amount' => (float) $booking->total_price,
                        'status' => $booking->booking_status,
                        'is_urgent' => false,
                        'special_requests' => $booking->special_requests,
                        'created_at' => $booking->created_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'bookings' => $bookings
            ]);
        } catch (\Exception $e) {
            \Log::error('Bookings API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    });
    
    // Get single booking
    Route::get('/bookings/{id}', function ($id) {
        try {
            $booking = Booking::with(['vehicle', 'customer', 'payments', 'voucher'])
                ->where('booking_id', $id)
                ->firstOrFail();

            $pickup = \Carbon\Carbon::parse($booking->pickup_date . ' ' . $booking->pickup_time);
            $return = \Carbon\Carbon::parse($booking->return_date . ' ' . $booking->return_time);
            $durationDays = ceil($pickup->diffInHours($return) / 24);

            $bookingData = [
                'id' => $booking->booking_id,
                'booking_code' => $booking->booking_id,
                'customer_name' => $booking->customer->name ?? 'N/A',
                'customer_phone' => $booking->customer->phone ?? 'N/A',
                'customer_email' => $booking->customer->email ?? 'N/A',
                'customer_ic' => $booking->customer->ic ?? 'N/A',
                'vehicle_name' => ($booking->vehicle->brand ?? '') . ' ' . ($booking->vehicle->model ?? 'N/A'),
                'vehicle_plate' => $booking->plate_no,
                'vehicle_category' => $booking->vehicle->category ?? 'N/A',
                'start_date' => $booking->pickup_date . ' ' . $booking->pickup_time,
                'end_date' => $booking->return_date . ' ' . $booking->return_time,
                'duration_days' => $durationDays,
                'pickup_type' => $booking->delivery_required ? 'delivery' : 'self-pickup',
                'delivery_address' => $booking->pickup_location ?? null,
                'daily_rate' => (float) ($booking->vehicle->price_perHour ?? 0),
                'delivery_fee' => 0,
                'total_amount' => (float) $booking->total_price,
                'status' => $booking->booking_status,
                'special_requests' => $booking->special_requests,
                'payment_status' => $booking->payments->first()->payment_status ?? 'pending',
                'created_at' => $booking->created_at,
            ];

            return response()->json([
                'success' => true,
                'booking' => $bookingData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    });
    
    // Approve booking
    Route::post('/bookings/{id}/approve', function ($id) {
        try {
            $booking = Booking::where('booking_id', $id)->firstOrFail();
            
            if ($booking->booking_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending bookings can be approved'
                ], 400);
            }

            $booking->update(['booking_status' => 'confirmed']);

            try {
                broadcast(new BookingUpdated($booking, 'approved'))->toOthers();
            } catch (\Exception $e) {
                \Log::error('Approve broadcast failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve booking'
            ], 500);
        }
    });
    
    // Verify payment (Admin only)
    Route::post('/bookings/{id}/verify-payment', function ($id) {
        try {
            $booking = Booking::where('booking_id', $id)->firstOrFail();
            
            if ($booking->booking_status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only confirmed bookings can have payment verified'
                ], 400);
            }

            $booking->update(['booking_status' => 'payment_verified']);

            $payment = $booking->payments()->first();
            if ($payment) {
                $payment->update(['payment_status' => 'verified']);
            }

            try {
                broadcast(new BookingUpdated($booking, 'payment_verified'))->toOthers();
            } catch (\Exception $e) {
                \Log::error('Payment verification broadcast failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment'
            ], 500);
        }
    });
    
    // Complete verification (mark as active)
    Route::post('/bookings/{id}/verify', function ($id) {
        try {
            $booking = Booking::where('booking_id', $id)->firstOrFail();
            
            if ($booking->booking_status !== 'payment_verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only payment verified bookings can be marked as active'
                ], 400);
            }

            $booking->update(['booking_status' => 'active']);

            try {
                broadcast(new BookingUpdated($booking, 'marked_active'))->toOthers();
            } catch (\Exception $e) {
                \Log::error('Verification broadcast failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking verification completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify booking'
            ], 500);
        }
    });
    
    // Cancel booking
    Route::post('/bookings/{id}/cancel', function ($id) {
        try {
            $booking = Booking::where('booking_id', $id)->firstOrFail();
            
            if (in_array($booking->booking_status, ['completed', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be cancelled'
                ], 400);
            }

            DB::beginTransaction();
            
            $booking->update(['booking_status' => 'cancelled']);
            
            if ($booking->vehicle) {
                $booking->vehicle->update(['availability_status' => 'available']);
            }
            
            if ($booking->voucher_id && $booking->voucher) {
                if ($booking->voucher->used_count > 0) {
                    $booking->voucher->decrement('used_count');
                }
            }
            
            DB::commit();

            try {
                broadcast(new BookingUpdated($booking, 'cancelled'))->toOthers();
            } catch (\Exception $e) {
                \Log::error('Cancel broadcast failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking'
            ], 500);
        }
    });
    
    // Complete booking
    Route::post('/bookings/{id}/complete', function ($id) {
        try {
            $booking = Booking::where('booking_id', $id)->firstOrFail();
            
            if ($booking->booking_status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only active bookings can be completed'
                ], 400);
            }

            DB::beginTransaction();
            
            $booking->update(['booking_status' => 'completed']);
            
            if ($booking->vehicle) {
                $booking->vehicle->update(['availability_status' => 'available']);
            }
            
            DB::commit();

            try {
                broadcast(new BookingUpdated($booking, 'completed'))->toOthers();
            } catch (\Exception $e) {
                \Log::error('Complete broadcast failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking marked as completed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete booking'
            ], 500);
        }
    });
});