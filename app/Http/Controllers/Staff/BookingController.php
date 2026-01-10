<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\Staff;

class BookingController extends Controller
{
    // ==================== DISPLAY METHODS ====================

    /**
     * Display all bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'vehicle', 'approvedBy', 'payments']);
        
        // Filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('booking_status', $request->status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('pickup_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('pickup_date', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone_no', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('vehicle', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('plate_no', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistics for filters
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('booking_status', 'pending')->count(),
            'confirmed' => Booking::where('booking_status', 'confirmed')->count(),
            'active' => Booking::where('booking_status', 'active')->count(),
            'completed' => Booking::where('booking_status', 'completed')->count(),
            'cancelled' => Booking::where('booking_status', 'cancelled')->count(),
        ];
        
        return view('staff.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Display booking details
     */
    public function show($id)
    {
        $booking = Booking::with([
            'customer', 
            'vehicle', 
            'voucher', 
            'approvedBy',
            'lateChargeApprovedBy',
            'payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);
        
        // Calculate if vehicle is currently available for extension
        $isAvailableForExtension = $this->checkVehicleAvailabilityForExtension($booking);
        
        // Calculate potential late charges if not returned yet
        $potentialLateInfo = $booking->calculateLateReturn();
        
        return view('staff.bookings.show', compact('booking', 'isAvailableForExtension', 'potentialLateInfo'));
    }

    /**
     * Show form to create new booking (staff can create booking for customers)
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $vehicles = Vehicle::where('availability_status', 'available')->get();
        $vouchers = Voucher::where('voucherStatus', 'active')
                          ->where(function($query) {
                              $query->whereNull('expiryDate')
                                    ->orWhere('expiryDate', '>=', now());
                          })
                          ->get();
        
        return view('staff.bookings.create', compact('customers', 'vehicles', 'vouchers'));
    }

    /**
     * Show form to edit booking
     */
    public function edit($id)
    {
        $booking = Booking::with(['customer', 'vehicle', 'voucher'])->findOrFail($id);
        
        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return redirect()->route('staff.bookings.show', $id)
                           ->with('error', 'Only pending or confirmed bookings can be edited.');
        }
        
        $customers = Customer::where('is_active', true)->get();
        $vehicles = Vehicle::where('availability_status', 'available')
                          ->orWhere('plate_no', $booking->plate_no)
                          ->get();
        $vouchers = Voucher::where('voucherStatus', 'active')
                          ->where(function($query) {
                              $query->whereNull('expiryDate')
                                    ->orWhere('expiryDate', '>=', now());
                          })
                          ->get();
        
        return view('staff.bookings.edit', compact('booking', 'customers', 'vehicles', 'vouchers'));
    }

    // ==================== ACTION METHODS ====================

    /**
     * Store new booking (staff creating for customer)
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plate_no' => 'required|exists:vehicle,plate_no',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required',
            'voucher_id' => 'nullable|exists:voucher,voucher_id',
            'special_requests' => 'nullable|string|max:500',
        ]);

        // Check vehicle availability
        $vehicle = Vehicle::findOrFail($request->plate_no);
        if ($vehicle->availability_status !== 'available') {
            return back()->with('error', 'Vehicle is not available for booking.')
                        ->withInput();
        }

        // Check for overlapping bookings
        $overlapping = Booking::where('plate_no', $request->plate_no)
            ->where('booking_status', '!=', 'cancelled')
            ->where(function($query) use ($request) {
                $query->whereBetween('pickup_date', [$request->pickup_date, $request->return_date])
                      ->orWhereBetween('return_date', [$request->pickup_date, $request->return_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('pickup_date', '<=', $request->pickup_date)
                            ->where('return_date', '>=', $request->return_date);
                      });
            })->exists();

        if ($overlapping) {
            return back()->with('error', 'Vehicle is already booked for the selected dates.')
                        ->withInput();
        }

        DB::beginTransaction();
        try {
            // Calculate duration and price
            $pickupDateTime = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
            $returnDateTime = Carbon::parse($request->return_date . ' ' . $request->return_time);
            $totalHours = ceil($pickupDateTime->diffInHours($returnDateTime));
            
            // Get hourly rate
            $hourlyRate = $vehicle->price_perHour;
            $totalPrice = $hourlyRate * $totalHours;
            
            // Apply voucher discount if any
            if ($request->voucher_id) {
                $voucher = Voucher::findOrFail($request->voucher_id);
                if ($voucher->voucherStatus === 'active' && 
                    (!$voucher->expiryDate || $voucher->expiryDate >= now())) {
                    
                    $discount = $voucher->voucherAmount;
                    $totalPrice = max(0, $totalPrice - $discount);
                    
                    // Increment voucher usage
                    $voucher->increment('used_count');
                }
            }

            // Generate unique booking ID
            $bookingId = 'BKG' . date('Ymd') . strtoupper(uniqid());

            // Create booking
            $booking = Booking::create([
                'booking_id' => $bookingId,
                'customer_id' => $request->customer_id,
                'plate_no' => $request->plate_no,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'total_price' => $totalPrice,
                'voucher_id' => $request->voucher_id,
                'special_requests' => $request->special_requests,
                'booking_status' => 'pending',
            ]);

            // Update vehicle status
            $vehicle->update(['availability_status' => 'booked']);

            // Auto-approve if staff is creating (optional)
            $staff = Auth::guard('staff')->user();
            if ($staff && in_array($staff->role, ['admin', 'manager'])) {
                $booking->update([
                    'booking_status' => 'confirmed',
                    'approved_by_staff' => $staff->staff_id,
                    'approved_at' => now(),
                ]);
            }

            DB::commit();
            
            return redirect()->route('staff.bookings.show', $booking->booking_id)
                           ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Update existing booking
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Only pending or confirmed bookings can be edited.');
        }

        $request->validate([
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required',
            'voucher_id' => 'nullable|exists:voucher,voucher_id',
            'special_requests' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // If vehicle changed, check availability
            if ($request->plate_no !== $booking->plate_no) {
                $newVehicle = Vehicle::findOrFail($request->plate_no);
                if ($newVehicle->availability_status !== 'available') {
                    return back()->with('error', 'Selected vehicle is not available.')
                                ->withInput();
                }
                
                // Check for overlapping bookings on new vehicle
                $overlapping = Booking::where('plate_no', $request->plate_no)
                    ->where('booking_status', '!=', 'cancelled')
                    ->where('booking_id', '!=', $id)
                    ->where(function($query) use ($request) {
                        $query->whereBetween('pickup_date', [$request->pickup_date, $request->return_date])
                              ->orWhereBetween('return_date', [$request->pickup_date, $request->return_date])
                              ->orWhere(function($q) use ($request) {
                                  $q->where('pickup_date', '<=', $request->pickup_date)
                                    ->where('return_date', '>=', $request->return_date);
                              });
                    })->exists();

                if ($overlapping) {
                    return back()->with('error', 'Selected vehicle is already booked for the selected dates.')
                                ->withInput();
                }
                
                // Release old vehicle
                $oldVehicle = Vehicle::find($booking->plate_no);
                if ($oldVehicle) {
                    $oldVehicle->update(['availability_status' => 'available']);
                }
                
                // Book new vehicle
                $newVehicle->update(['availability_status' => 'booked']);
            }

            // Calculate new price
            $vehicle = Vehicle::find($request->plate_no ?? $booking->plate_no);
            $pickupDateTime = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
            $returnDateTime = Carbon::parse($request->return_date . ' ' . $request->return_time);
            $totalHours = ceil($pickupDateTime->diffInHours($returnDateTime));
            
            $hourlyRate = $vehicle->price_perHour;
            $totalPrice = $hourlyRate * $totalHours;
            
            // Handle voucher changes
            if ($request->voucher_id !== $booking->voucher_id) {
                // Decrement old voucher usage
                if ($booking->voucher_id) {
                    $oldVoucher = Voucher::find($booking->voucher_id);
                    if ($oldVoucher && $oldVoucher->used_count > 0) {
                        $oldVoucher->decrement('used_count');
                    }
                }
                
                // Apply new voucher
                if ($request->voucher_id) {
                    $newVoucher = Voucher::findOrFail($request->voucher_id);
                    if ($newVoucher->voucherStatus === 'active' && 
                        (!$newVoucher->expiryDate || $newVoucher->expiryDate >= now())) {
                        
                        $discount = $newVoucher->voucherAmount;
                        $totalPrice = max(0, $totalPrice - $discount);
                        
                        // Increment new voucher usage
                        $newVoucher->increment('used_count');
                    }
                }
            }

            // Update booking
            $booking->update([
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'total_price' => $totalPrice,
                'plate_no' => $request->plate_no ?? $booking->plate_no,
                'voucher_id' => $request->voucher_id,
                'special_requests' => $request->special_requests,
            ]);

            DB::commit();
            
            return redirect()->route('staff.bookings.show', $booking->booking_id)
                           ->with('success', 'Booking updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update booking: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Approve booking
     */
    public function approve($id)
    {
        $staff = Auth::guard('staff')->user();
        $booking = Booking::with('payments')->findOrFail($id);

        if ($booking->booking_status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be approved.');
        }

        // Check if payment is verified
        $hasVerifiedPayment = $booking->payments()
            ->where('payment_status', 'paid')
            ->exists();

        if (!$hasVerifiedPayment) {
            return back()->with('error', 'Cannot approve booking. Payment not verified.');
        }

        $booking->update([
            'booking_status' => 'confirmed',
            'approved_by_staff' => $staff->staff_id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Booking approved successfully!');
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Only pending or confirmed bookings can be cancelled.');
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update([
                'booking_status' => 'cancelled',
                'special_requests' => $request->cancellation_reason . "\n\n" . ($booking->special_requests ?? ''),
            ]);

            // Make vehicle available again
            $vehicle = Vehicle::find($booking->plate_no);
            if ($vehicle) {
                $vehicle->update(['availability_status' => 'available']);
            }

            // Refund voucher if used
            if ($booking->voucher_id) {
                $voucher = Voucher::find($booking->voucher_id);
                if ($voucher && $voucher->used_count > 0) {
                    $voucher->decrement('used_count');
                }
            }

            DB::commit();
            
            return back()->with('success', 'Booking cancelled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    /**
     * Mark booking as active (customer picked up vehicle)
     */
    public function markAsActive($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->booking_status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked as active.');
        }

        if ($booking->pickup_date->isFuture()) {
            return back()->with('error', 'Cannot mark as active before pickup date.');
        }

        $booking->update(['booking_status' => 'active']);
        
        return back()->with('success', 'Booking marked as active (vehicle picked up).');
    }

    /**
     * Mark vehicle as returned (with late charge calculation)
     */
    public function markAsReturned(Request $request, $id)
    {
        $request->validate([
            'actual_return_date' => 'required|date',
            'actual_return_time' => 'required',
            'fuel_level' => 'required|in:empty,quarter,half,three_quarter,full',
            'damage_notes' => 'nullable|string|max:500',
            'photo_evidence' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $booking = Booking::findOrFail($id);
        
        if ($booking->booking_status !== 'active') {
            return back()->with('error', 'Only active bookings can be marked as returned.');
        }

        DB::beginTransaction();
        try {
            // Store actual return info
            $actualReturnDate = $request->actual_return_date;
            $actualReturnTime = $request->actual_return_time;
            
            // Calculate late return
            $lateInfo = $booking->calculateLateReturn();
            $lateCharge = 0;
            
            if ($lateInfo['is_late']) {
                $lateCharge = $booking->calculateLateCharge();
            }

            // Update booking
            $booking->update([
                'actual_return_date' => $actualReturnDate,
                'actual_return_time' => $actualReturnTime,
                'late_return_hours' => $lateInfo['late_hours'] ?? 0,
                'late_return_charge' => $lateCharge,
                'late_return_notes' => $request->damage_notes,
                'booking_status' => 'completed',
            ]);

            // Make vehicle available again
            $vehicle = Vehicle::find($booking->plate_no);
            if ($vehicle) {
                $vehicle->update(['availability_status' => 'available']);
            }

            // Create inspection record
            $inspectionId = 'INS' . date('Ymd') . strtoupper(uniqid());
            
            $inspectionData = [
                'inspection_id' => $inspectionId,
                'inspection_date' => now(),
                'fuel_level' => $request->fuel_level,
                'inspection_status' => 'passed',
                'damage_notes' => $request->damage_notes,
                'person_in_charge' => Auth::guard('staff')->user()->staff_id,
            ];
            
            if ($request->hasFile('photo_evidence')) {
                $path = $request->file('photo_evidence')->store('inspections', 'public');
                $inspectionData['photo_evidence'] = $path;
            }
            
            // Save inspection (assuming you have an Inspection model)
            // \App\Models\Inspection::create($inspectionData);

            // Create payment record for late charges if any
            if ($lateCharge > 0) {
                $paymentId = 'LATE' . strtoupper(uniqid());
                Payment::create([
                    'payment_id' => $paymentId,
                    'amount' => $lateCharge,
                    'payment_status' => 'pending',
                    'payment_method' => 'late_charge',
                    'booking_id' => $booking->booking_id,
                    'payment_date' => now(),
                ]);
            }

            DB::commit();
            
            if ($lateCharge > 0) {
                return back()->with('warning', 
                    "Vehicle returned successfully. Late: {$lateInfo['late_hours']} hours. Late charge: RM {$lateCharge}. Please approve late charges.");
            }
            
            return back()->with('success', 'Vehicle returned successfully. No late charges.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark as returned: ' . $e->getMessage());
        }
    }

    /**
     * Approve late charges
     */
    public function approveLateCharges(Request $request, $id)
    {
        $staff = Auth::guard('staff')->user();
        $booking = Booking::findOrFail($id);

        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        if ($booking->late_return_charge <= 0) {
            return back()->with('error', 'No late charges to approve.');
        }

        if ($booking->late_charge_approved_by) {
            return back()->with('error', 'Late charges already approved.');
        }

        $booking->update([
            'late_charge_approved_by' => $staff->staff_id,
            'late_charge_approved_at' => now(),
            'late_return_notes' => $request->approval_notes ?: $booking->late_return_notes,
        ]);

        return back()->with('success', "Late charges approved. Amount: RM {$booking->late_return_charge}");
    }

    /**
     * Extend booking duration
     */
    public function extendBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'new_return_date' => 'required|date|after:pickup_date',
            'new_return_time' => 'required',
            'extension_reason' => 'nullable|string|max:500',
        ]);

        if ($booking->booking_status !== 'active') {
            return back()->with('error', 'Only active bookings can be extended.');
        }

        // Check vehicle availability for extension period
        $isAvailable = $this->checkVehicleAvailabilityForExtension($booking, $request->new_return_date);
        if (!$isAvailable) {
            return back()->with('error', 'Vehicle is not available for the extended period.');
        }

        DB::beginTransaction();
        try {
            // Calculate additional hours and charge
            $oldReturnDateTime = Carbon::parse($booking->return_date->format('Y-m-d') . ' ' . $booking->return_time);
            $newReturnDateTime = Carbon::parse($request->new_return_date . ' ' . $request->new_return_time);
            
            $additionalHours = ceil($oldReturnDateTime->diffInHours($newReturnDateTime));
            $hourlyRate = $booking->vehicle->price_perHour;
            $additionalCharge = $additionalHours * $hourlyRate;

            // Update booking
            $booking->update([
                'return_date' => $request->new_return_date,
                'return_time' => $request->new_return_time,
                'total_price' => $booking->total_price + $additionalCharge,
                'special_requests' => ($booking->special_requests ?? '') . "\n\nExtension: " . $request->extension_reason,
            ]);

            // Create payment for extension
            $paymentId = 'EXT' . strtoupper(uniqid());
            Payment::create([
                'payment_id' => $paymentId,
                'amount' => $additionalCharge,
                'payment_status' => 'pending',
                'payment_method' => 'extension',
                'booking_id' => $booking->booking_id,
                'payment_date' => now(),
            ]);

            DB::commit();
            
            return back()->with('success', 
                "Booking extended successfully. Additional charge: RM {$additionalCharge} for {$additionalHours} hours.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to extend booking: ' . $e->getMessage());
        }
    }

    // ==================== UTILITY METHODS ====================

    /**
     * Check vehicle availability for booking extension
     */
    private function checkVehicleAvailabilityForExtension(Booking $booking, $newReturnDate = null)
    {
        $vehicle = $booking->vehicle;
        
        // Check for other bookings overlapping with extension period
        $overlapping = Booking::where('plate_no', $vehicle->plate_no)
            ->where('booking_id', '!=', $booking->booking_id)
            ->where('booking_status', '!=', 'cancelled')
            ->where(function($query) use ($booking, $newReturnDate) {
                $endDate = $newReturnDate ?: $booking->return_date;
                $query->whereBetween('pickup_date', [$booking->pickup_date, $endDate])
                      ->orWhereBetween('return_date', [$booking->pickup_date, $endDate])
                      ->orWhere(function($q) use ($booking, $endDate) {
                          $q->where('pickup_date', '<=', $booking->pickup_date)
                            ->where('return_date', '>=', $endDate);
                      });
            })->exists();

        return !$overlapping;
    }

    /**
     * View late returns
     */
    public function lateReturns()
    {
        $lateBookings = Booking::where('late_return_hours', '>', 0)
            ->with(['customer', 'vehicle', 'lateChargeApprovedBy'])
            ->orderBy('actual_return_date', 'desc')
            ->paginate(20);

        $stats = [
            'total_late' => Booking::where('late_return_hours', '>', 0)->count(),
            'total_charges' => Booking::where('late_return_hours', '>', 0)->sum('late_return_charge'),
            'pending_approval' => Booking::where('late_return_hours', '>', 0)
                ->whereNull('late_charge_approved_by')
                ->count(),
            'pending_payment' => Booking::where('late_return_hours', '>', 0)
                ->where('late_charge_paid', false)
                ->count(),
        ];

        return view('staff.bookings.late-returns', compact('lateBookings', 'stats'));
    }

    /**
     * Delete booking (soft delete if implemented)
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Only allow deletion of pending bookings
        if ($booking->booking_status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be deleted.');
        }

        DB::beginTransaction();
        try {
            // Make vehicle available
            $vehicle = Vehicle::find($booking->plate_no);
            if ($vehicle) {
                $vehicle->update(['availability_status' => 'available']);
            }
            
            // Refund voucher
            if ($booking->voucher_id) {
                $voucher = Voucher::find($booking->voucher_id);
                if ($voucher && $voucher->used_count > 0) {
                    $voucher->decrement('used_count');
                }
            }
            
            // Delete payments
            $booking->payments()->delete();
            
            // Delete booking
            $booking->delete();
            
            DB::commit();
            
            return redirect()->route('staff.bookings.index')
                           ->with('success', 'Booking deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    /**
     * Export bookings to CSV
     */
    public function export(Request $request)
    {
        $query = Booking::with(['customer', 'vehicle']);
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('pickup_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('pickup_date', '<=', $request->date_to);
        }
        
        $bookings = $query->orderBy('pickup_date', 'desc')->get();
        
        $fileName = 'bookings_export_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        
        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Booking ID',
                'Customer Name',
                'Customer Email',
                'Vehicle',
                'Plate No',
                'Pickup Date',
                'Pickup Time',
                'Return Date',
                'Return Time',
                'Total Price',
                'Late Charges',
                'Status',
                'Approved By',
                'Approved At',
                'Created At'
            ]);
            
            // Data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_id,
                    $booking->customer->name ?? 'N/A',
                    $booking->customer->email ?? 'N/A',
                    $booking->vehicle->name ?? 'N/A',
                    $booking->vehicle->plate_no ?? 'N/A',
                    $booking->pickup_date->format('Y-m-d'),
                    $booking->pickup_time,
                    $booking->return_date->format('Y-m-d'),
                    $booking->return_time,
                    $booking->total_price,
                    $booking->late_return_charge,
                    $booking->booking_status,
                    $booking->approvedBy->name ?? 'N/A',
                    $booking->approved_at ? $booking->approved_at->format('Y-m-d H:i:s') : 'N/A',
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}