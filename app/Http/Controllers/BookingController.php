<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Voucher;
use App\Helpers\NotificationHelper;
// Add these at the top of your controller
use App\Notifications\TestNotification;
use App\Notifications\BookingApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
    // ====================================================================
    // CUSTOMER BOOKING METHODS
    // ====================================================================

    /**
     * Show booking form for a specific car
     */
    // In app/Http/Controllers/BookingController.php

    public function create($carId)
    {
        $car = Car::findOrFail($carId);

        $vouchers = Voucher::where('is_active', true)->get();

        return view('bookings.create', compact('car', 'vouchers'));
    }



    /**
     * Validate voucher via AJAX
     */
    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'base_price' => 'required|numeric|min:0'
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();
        
        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired voucher code'
            ], 404);
        }

        if (method_exists($voucher, 'isValid') && !$voucher->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'This voucher has expired or reached maximum usage'
            ], 422);
        }
        
        $basePrice = (float) $request->base_price;
        $discountAmount = round(($basePrice * $voucher->discount_percentage) / 100, 2);
        $finalPrice = max(0, $basePrice - $discountAmount);
        
        return response()->json([
            'valid' => true,
            'discount_percentage' => $voucher->discount_percentage,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'message' => "Voucher applied! {$voucher->discount_percentage}% discount"
        ]);
    }

    /**
     * Store new booking
     */
    public function store(BookingRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $car = Car::lockForUpdate()->findOrFail($request->car_id);

            // Validate dates
            $pickupDate = Carbon::parse($request->pickup_date)->toDateString();
            $returnDate = Carbon::parse($request->return_date)->toDateString();

            // Check if car is available for selected dates
            if (!$car->isAvailableForDates($pickupDate, $returnDate)) {
                DB::rollBack();
                return back()
                    ->with('error', 'Sorry, this vehicle is already booked for the selected dates.')
                    ->withInput();
            }

            // Calculate duration and pricing
            $pickup = Carbon::parse($request->pickup_date);
            $return = Carbon::parse($request->return_date);
            $duration = max(1, $pickup->diffInDays($return));

            $basePrice = $car->daily_rate * $duration;
            $discountAmount = 0;
            $voucherCode = null;

            // Apply voucher if provided
            if ($request->filled('voucher')) {
                $voucher = Voucher::where('code', strtoupper($request->voucher))
                    ->where('is_active', true)
                    ->first();
                
                if ($voucher && method_exists($voucher, 'isValid') && $voucher->isValid()) {
                    $discountAmount = method_exists($voucher, 'calculateDiscount')
                        ? $voucher->calculateDiscount($basePrice)
                        : round(($basePrice * $voucher->discount_percentage) / 100, 2);
                    
                    $voucherCode = $voucher->code;
                    
                    // Increment voucher usage
                    if (method_exists($voucher, 'incrementUsage')) {
                        $voucher->incrementUsage();
                    }
                }
            }

            $totalPrice = max(0, $basePrice - $discountAmount);
            $depositAmount = round($totalPrice * 0.1, 2);

            // Create booking
            $booking = Booking::create([
                'car_id' => $car->id,
                'user_id' => auth()->id(),
                'booking_reference' => Booking::generateBookingReference(),
                'customer_name' => $request->customer_name ?? auth()->user()->name,
                'customer_email' => $request->customer_email ?? auth()->user()->email,
                'customer_phone' => $request->customer_phone ?? auth()->user()->phone,
                'customer_ic' => $request->customer_ic ?? auth()->user()->ic,
                'pickup_location' => $request->pickup_location,
                'dropoff_location' => $request->dropoff_location,
                'destination' => $request->destination,
                'pickup_date' => $pickupDate,
                'pickup_time' => $request->pickup_time,
                'return_date' => $returnDate,
                'return_time' => $request->return_time,
                'duration' => $duration,
                'voucher' => $voucherCode,
                'base_price' => $basePrice,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'deposit_amount' => $depositAmount,
                'remarks' => $request->remarks,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);
            
            // Update car status
            $car->update([
                'status' => 'booked',
                'is_available' => false
            ]);

            // Notify staff about new booking
            NotificationHelper::notifyAllStaff(
                'booking',
                'New Booking Received',
                "{$booking->customer_name} created booking #{$booking->booking_reference} for {$car->name}. Pickup: {$pickup->format('M d, Y')}",
                route('staff.bookings.show', $booking->id),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'car_name' => $car->name,
                    'customer_name' => $booking->customer_name
                ]
            );

            DB::commit();

            return redirect()
                ->route('payment.page', $booking->booking_reference)
                ->with('success', 'Booking created successfully! Please complete payment to confirm.');

        } catch (\Exception $e) {
             dd($e->getMessage());
        }

        /*catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking store error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'car_id' => $request->car_id ?? null
            ]);
            
            return back()
                ->with('error', 'Booking failed. Please try again or contact support.')
                ->withInput();*/
        
    }

    /**
     * Show user's bookings (active and history)
     */
    public function myBookings(): View
    {
        $activeBookings = Booking::with(['car:id,name,brand,model,image'])
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $historyBookings = Booking::onlyTrashed()
            ->with(['car:id,name,brand,model,image'])
            ->where('user_id', auth()->id())
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('bookings.my-bookings', compact('activeBookings', 'historyBookings'));
    }
        /**
     * Show current (active) bookings
     */
    public function current()
    {
        $bookings = Booking::with('car')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['approved', 'ongoing'])
            ->orderBy('pickup_date', 'asc')
            ->get();

        return view('bookings.current', compact('bookings'));
    }

    /**
     * Show booking history (completed or cancelled)
     */
    public function history()
    {
        $bookings = Booking::with('car')
            ->where('users_id', auth()->id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('pickup_date', 'desc')
            ->get();

        return view('bookings.history', compact('bookings'));
    }


    /**
     * Customer cancels their booking
     */
public function cancel($reference)
{
    DB::beginTransaction();

    try {
        $booking = Booking::where('booking_reference', $reference)->firstOrFail();

        // logic cancellation
        $booking->status = 'cancelled';
        $booking->save();

        DB::commit();

        return redirect()
            ->route('bookings.my-bookings')
            ->with('success', 'Booking has been cancelled successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Booking cancellation error: ' . $e->getMessage());

        return back()->with('error', 'Failed to cancel booking. Please try again.');
    }
}


    /**
     * Show booking details
     */
    public function show($reference): View
    {
        $booking = Booking::withTrashed()
            ->with(['car', 'user', 'inspections'])
            ->where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show pending booking status
     */
    public function pending($reference): View
    {
        $booking = Booking::with('car')
            ->where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return view('bookings.pending', compact('booking'));
    }

    public function paymentSummary($reference)
    {
    $booking = Booking::where('booking_reference', $reference)
        ->where('user_id', auth()->id())
        ->with('car')
        ->firstOrFail();

    return view('payment.summary', compact('booking'));
    }

    // ====================================================================
    // STAFF BOOKING MANAGEMENT METHODS
    // ====================================================================

    /**
     * Staff: View all bookings with filters
     */
    public function staffIndex(Request $request): View
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        $query = Booking::with(['car:id,name,brand,model,image', 'user:id,name,email']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'cancelled') {
                $query->onlyTrashed()->where('status', 'cancelled');
                
                // Apply time range filter for cancelled bookings
                if ($request->filled('days')) {
                    $days = (int) $request->days;
                    $query->where('deleted_at', '>=', now()->subDays($days));
                }
            } else {
                $query->where('status', $request->status);
            }
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        return view('staff.bookings.index', compact('bookings'));
    }

    /**
     * Staff: View booking details
     */
    public function staffShow($id): View
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        $booking = Booking::withTrashed()
            ->with(['car', 'user', 'inspections'])
            ->findOrFail($id);
        
        return view('staff.bookings.show', compact('booking'));
    }

    /**
     * Staff: Approve pending booking
     */
    public function approve($id): RedirectResponse
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->status !== 'pending') {
                return back()->with('error', 'Only pending bookings can be approved.');
            }
            
            DB::beginTransaction();

            $booking->update(['status' => 'confirmed']);
            
            // Notify customer about approval using Laravel's notification system
            if ($booking->user) {
                // Send Laravel notification
                $booking->user->notify(new BookingApprovedNotification($booking));
                
                // Also keep your existing notification system
                NotificationHelper::createBookingNotification(
                    $booking->user,
                    'Booking Approved',
                    "Your booking #{$booking->booking_reference} has been approved by staff.",
                    route('bookings.show', $booking->booking_reference),
                    [
                        'booking_id' => $booking->id,
                        'booking_reference' => $booking->booking_reference
                    ]
                );
            }
            
            // Also send a test notification to staff/admin
            auth()->user()->notify(new TestNotification());

            DB::commit();
            
            return redirect()
                ->route('staff.bookings.index')
                ->with('success', "Booking #{$booking->booking_reference} has been approved successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking approval error: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to approve booking. Please try again.');
        }
    }

    /**
     * Staff: Cancel/Reject booking
     */
    public function staffCancel(Request $request, $id): RedirectResponse
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        try {
            $booking = Booking::findOrFail($id);
            
            DB::beginTransaction();

            $reason = $request->input('reason', 'Cancelled by staff');

            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason
            ]);
            
            // Soft delete the booking
            $booking->delete();
            
            // Make car available again
            if ($booking->car) {
                $booking->car->update([
                    'is_available' => true,
                    'status' => 'available'
                ]);
            }

            // Notify customer about cancellation
            if ($booking->user) {
                NotificationHelper::createBookingNotification(
                    $booking->user,
                    'Booking Cancelled',
                    "Your booking #{$booking->booking_reference} has been cancelled. Reason: {$reason}",
                    route('bookings.show', $booking->booking_reference),
                    [
                        'booking_id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'reason' => $reason
                    ]
                );
            }

            DB::commit();
            
            return redirect()
                ->route('staff.bookings.index')
                ->with('success', "Booking #{$booking->booking_reference} has been cancelled successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Staff booking cancellation error: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to cancel booking. Please try again.');
        }
    }

    /**
     * Staff: Restore cancelled booking
     */
    public function restore($reference): RedirectResponse
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        try {
            $booking = Booking::withTrashed()
                ->where('booking_reference', $reference)
                ->firstOrFail();

            // Check if car is available
            if ($booking->car && !$booking->car->is_available) {
                return back()->with('error', 'Cannot restore. The vehicle is currently not available.');
            }

            DB::beginTransaction();

            // Restore booking
            $booking->restore();
            $booking->update([
                'status' => 'pending',
                'cancellation_reason' => null
            ]);

            // Update car status
            if ($booking->car) {
                $booking->car->update([
                    'status' => 'booked',
                    'is_available' => false
                ]);
            }

            // Notify customer
            if ($booking->user) {
                NotificationHelper::createBookingNotification(
                    $booking->user,
                    'Booking Restored',
                    "Your booking #{$booking->booking_reference} has been restored. Please complete payment to confirm.",
                    route('bookings.show', $booking->booking_reference),
                    [
                        'booking_id' => $booking->id,
                        'booking_reference' => $booking->booking_reference
                    ]
                );
            }

            DB::commit();

            return back()->with('success', "Booking #{$reference} has been restored successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking restoration error: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to restore booking. Please try again.');
        }
    }

    // ====================================================================
    // PAYMENT METHODS
    // ====================================================================

    /**
     * Show payment page
     */
    public function paymentPage($reference): View
    {
        $booking = Booking::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->with('car')
            ->firstOrFail();
        
        if ($booking->status !== 'pending') {
            abort(403, 'This booking cannot be paid at this time.');
        }
        
        return view('payment', compact('booking'));
    }

    /**
     * Process payment submission
     */
    public function processPayment(Request $request, $reference): RedirectResponse
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        try {
            $booking = Booking::where('booking_reference', $reference)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            
            if ($booking->payment_status === 'paid') {
                return redirect()
                    ->route('payment.receipt', $reference)
                    ->with('info', 'Payment already processed for this booking.');
            }

            DB::beginTransaction();

            // Upload payment proof
            $paymentProofPath = $request->file('payment_proof')
                ->store('payment_proofs', 'public');
            
            // Update booking
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => $request->input('payment_method', 'duitnow_qr'),
                'payment_proof' => $paymentProofPath,
                'paid_at' => now(),
            ]);

            // Update car status
            if ($booking->car) {
                $booking->car->update(['status' => 'rented']);
            }

            // Notify staff about payment
            NotificationHelper::notifyAllStaff(
                'booking',
                'Payment Received',
                "{$booking->customer_name} completed payment for booking #{$booking->booking_reference}. Amount: RM {$booking->total_price}",
                route('staff.bookings.show', $booking->id),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'amount' => $booking->total_price,
                    'payment_method' => $booking->payment_method
                ]
            );

            // Confirm payment to customer
            NotificationHelper::createBookingNotification(
                auth()->user(),
                'Payment Confirmed',
                "Your payment for booking #{$booking->booking_reference} has been received. Total: RM {$booking->total_price}",
                route('payment.receipt', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'amount' => $booking->total_price
                ]
            );

            DB::commit();
            
            return redirect()
                ->route('payment.receipt', $reference)
                ->with('success', 'Payment submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Payment processing failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show payment receipt
     */
    public function receipt($reference): View
    {
        $booking = Booking::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->with('car')
            ->firstOrFail();
            
        return view('receipt', compact('booking'));
    }

    /**
     * Show payment summary before booking
     */
    public function processToPayment(Request $request): View
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:pickup_date',
        ]);

        $car = Car::findOrFail($request->car_id);
        $pickup = Carbon::parse($request->pickup_date);
        $return = Carbon::parse($request->return_date);
        $duration = max(1, $pickup->diffInDays($return));

        $basePrice = $car->daily_rate * $duration;
        $discountAmount = 0;
        $voucherCode = null;

        // Apply voucher if provided
        if ($request->filled('voucher')) {
            $voucher = Voucher::where('code', strtoupper($request->voucher))
                ->where('is_active', true)
                ->first();
            
            if ($voucher && method_exists($voucher, 'isValid') && $voucher->isValid()) {
                $discountAmount = round(($basePrice * $voucher->discount_percentage) / 100, 2);
                $voucherCode = $voucher->code;
            }
        }

        $bookingData = $request->all();
        $bookingData['duration'] = $duration;
        $bookingData['base_price'] = $basePrice;
        $bookingData['discount_amount'] = $discountAmount;
        $bookingData['total_price'] = max(0, $basePrice - $discountAmount);
        $bookingData['voucher'] = $voucherCode;

        return view('bookings.payment_summary', [
            'car' => $car,
            'bookingData' => $bookingData
        ]);
    }

    /**
     * Show booking confirmation
     */
    public function confirmation($reference): View
    {
        $booking = Booking::with('car')
            ->where('booking_reference', $reference)
            ->firstOrFail();
            
        return view('bookings.confirmation', compact('booking'));
    }
}