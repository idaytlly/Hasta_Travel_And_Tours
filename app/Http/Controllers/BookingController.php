<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Voucher;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Show booking form
     */
    public function create($carId): View
    {
        $car = Car::findOrFail($carId);
        $vouchers = Voucher::where('is_active', true)->get(); 
        
        return view('bookings.create', compact('car', 'vouchers'));
    }

    /**
     * Validate voucher (AJAX)
     */
    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'base_price' => 'required|numeric'
        ]);

        $voucher = Voucher::where('code', $request->code)
                          ->where('is_active', true)
                          ->first();
        
        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired voucher code'
            ], 404);
        }

        // Check if voucher is valid using the model method
        if (method_exists($voucher, 'isValid') && !$voucher->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'This voucher has expired or reached maximum usage'
            ], 404);
        }
        
        $basePrice = $request->base_price;
        $discountAmount = round(($basePrice * $voucher->discount_percentage) / 100, 2);
        $finalPrice = $basePrice - $discountAmount;
        
        return response()->json([
            'valid' => true,
            'discount_percentage' => $voucher->discount_percentage,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'message' => "Voucher applied! {$voucher->discount_percentage}% discount"
        ]);
    }

    /**
     * Store booking (Main Method)
     */
    public function store(BookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $car = Car::findOrFail($request->car_id);

            // 1. Format dates for SQL
            $pickupDateStr = Carbon::parse($request->pickup_date)->toDateString();
            $returnDateStr = Carbon::parse($request->return_date)->toDateString();

            // 2. Check availability
            if (!$car->isAvailableForDates($pickupDateStr, $returnDateStr)) {
                return back()->with('error', 'Sorry, this car is already booked for the selected dates.')->withInput();
            }

            // 3. Calculate Duration
            $pickup = Carbon::parse($request->pickup_date);
            $return = Carbon::parse($request->return_date);
            $duration = $pickup->diffInDays($return) ?: 1; 

            // 4. Calculate Pricing
            $basePrice = $car->daily_rate * $duration;
            $discountAmount = 0;
            $voucherCode = null;

            // 5. Voucher Logic
            if ($request->filled('voucher')) {
                $voucher = Voucher::where('code', $request->voucher)
                                  ->where('is_active', true)
                                  ->first();
                
                if ($voucher && method_exists($voucher, 'isValid') && $voucher->isValid()) {
                    if (method_exists($voucher, 'calculateDiscount')) {
                        $discountAmount = $voucher->calculateDiscount($basePrice);
                    } else {
                        $discountAmount = round(($basePrice * $voucher->discount_percentage) / 100, 2);
                    }
                    $voucherCode = $voucher->code;
                }
            }

            $totalPrice = $basePrice - $discountAmount;
            $depositAmount = round($totalPrice * 0.1, 2);

            // 6. Create Booking
            $booking = Booking::create([
                'car_id'            => $car->id,
                'user_id'           => auth()->id(),
                'booking_reference' => Booking::generateBookingReference(),
                'customer_name'     => $request->customer_name ?? auth()->user()->name,
                'customer_email'    => $request->customer_email ?? auth()->user()->email,
                'customer_phone'    => $request->customer_phone ?? auth()->user()->phone,
                'customer_ic'       => $request->customer_ic ?? auth()->user()->ic,
                'pickup_location'   => $request->pickup_location,
                'dropoff_location'  => $request->dropoff_location,
                'destination'       => $request->destination,
                'pickup_date'       => $pickupDateStr,
                'pickup_time'       => $request->pickup_time,
                'return_date'       => $returnDateStr,
                'return_time'       => $request->return_time,
                'duration'          => $duration,
                'voucher'           => $voucherCode,
                'base_price'        => $basePrice,
                'discount_amount'   => $discountAmount,
                'total_price'       => $totalPrice,
                'deposit_amount'    => $depositAmount,
                'remarks'           => $request->remarks ?? null,
                'status'            => 'pending',
                'payment_status'    => 'unpaid',
            ]);
            
            $car->update(['status' => 'booked']); 

            // 🔔 NOTIFICATION: Notify all staff about new booking
            NotificationHelper::notifyAllStaff(
                'booking',
                'New Booking Received',
                "{$booking->customer_name} created booking #{$booking->booking_reference} for {$car->name}. Pickup: {$pickup->format('M d, Y')}",
                route('staff.bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'car_name' => $car->name,
                    'customer_name' => $booking->customer_name
                ]
            );

            DB::commit();

            return redirect()->route('payment.page', $booking->booking_reference)
                ->with('success', 'Booking created! Please complete payment to confirm.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Booking failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show user's bookings (Separating Active vs Trashed/History)
     */
    public function myBookings()
    {
        $activeBookings = Booking::with('car')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $historyBookings = Booking::onlyTrashed()
            ->with('car')
            ->where('user_id', auth()->id())
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('bookings.my-bookings', compact('activeBookings', 'historyBookings'));
    }

    /**
     * User/Customer Cancellation
     */
    public function cancel(Request $request, $reference)
    {
        $booking = Booking::where('booking_reference', $reference)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        if ($booking->car) {
            $booking->car->update(['status' => 'available', 'is_available' => true]);
        }

        $reason = $request->input('reason', 'Cancelled by customer');
        $booking->markAsCancelled($reason);

        // 🔔 NOTIFICATION: Notify all staff about customer cancellation
        NotificationHelper::notifyAllStaff(
            'booking',
            'Booking Cancelled by Customer',
            "{$booking->customer_name} cancelled booking #{$booking->booking_reference}. Reason: {$reason}",
            route('staff.bookings.show', $booking->booking_reference),
            [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'reason' => $reason
            ]
        );

        return redirect()->route('bookings.my-bookings')
                         ->with('success', 'Booking has been cancelled.');
    }

    /**
     * Staff/Admin: Manage all bookings with SoftDelete support
     */
    public function staffIndex(Request $request): View
{
    $query = Booking::with(['car', 'user'])
        ->when($request->status == 'cancelled', fn($q) => $q->onlyTrashed(), fn($q) => $q)
        ->when($request->filled('status') && $request->status != 'cancelled', fn($q) => $q->where('status', $request->status));

    $bookings = $query->orderBy('updated_at', 'desc')->get();

    // Pass unread notifications to fix Blade error
    $unreadNotifications = Auth::user()->unreadNotifications()->count();

    return view('staff.manage-bookings', compact('bookings', 'unreadNotifications'));
}


    /**
     * Staff: Process Cancellation from Modal
     */
    public function staffCancel(Request $request, $reference)
    {
        $booking = Booking::where('booking_reference', $reference)->firstOrFail();

        if ($booking->car) {
            $booking->car->update(['status' => 'available', 'is_available' => true]);
        }

        $reason = $request->reason;
        if ($request->filled('remarks')) {
            $reason .= ': ' . $request->remarks;
        }

        $booking->markAsCancelled($reason);

        // 🔔 NOTIFICATION: Notify customer about staff cancellation
        if ($booking->user) {
            NotificationHelper::createBookingNotification(
                $booking->user,
                'Booking Cancelled',
                "Your booking #{$booking->booking_reference} has been cancelled by staff. Reason: {$reason}",
                route('bookings.show', $booking->booking_reference),
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'reason' => $reason
                ]
            );
        }

        return redirect()->back()->with('success', "Booking #{$reference} has been cancelled.");
    }

    /**
     * Staff/Admin: Restore a cancelled booking
     */
    public function restore($reference)
    {
        $booking = Booking::withTrashed()->where('booking_reference', $reference)->firstOrFail();

        if ($booking->car && $booking->car->status !== 'available') {
            return redirect()->back()->with('error', 'Cannot restore. The car is currently rented by someone else.');
        }

        $booking->restore();
        $booking->update([
            'status' => 'pending', 
            'cancellation_reason' => null
        ]);

        if ($booking->car) {
            $booking->car->update(['status' => 'booked']);
        }

        // 🔔 NOTIFICATION: Notify customer about booking restoration
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

        return redirect()->back()->with('success', "Booking #{$reference} has been restored successfully.");
    }

    /**
     * Show booking details (Includes Trashed for History)
     */
    public function show($reference): View
    {
        $booking = Booking::withTrashed()
            ->with(['car', 'user', 'inspections'])
            ->where('booking_reference', $reference)
            ->firstOrFail();
            
        return view('bookings.show', compact('booking'));
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request, $reference)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', 
        ]);
        
        $booking = Booking::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }
        
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'duitnow_qr',
            'payment_proof' => $paymentProofPath,
            'paid_at' => now(),
        ]);

        if ($booking->car) {
            $booking->car->update(['status' => 'rented']);
        }

        // 🔔 NOTIFICATION: Notify all staff about payment completion
        NotificationHelper::notifyAllStaff(
            'booking',
            'Payment Received',
            "{$booking->customer_name} completed payment for booking #{$booking->booking_reference}. Amount: RM {$booking->total_price}",
            route('staff.bookings.show', $booking->booking_reference),
            [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'amount' => $booking->total_price,
                'payment_method' => 'duitnow_qr'
            ]
        );

        // 🔔 NOTIFICATION: Confirm payment to customer
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
        
        return redirect()->route('payment.receipt', $reference)
            ->with('success', 'Payment successful!');
    }

    /**
     * Payment Summary Page
     */
    public function processToPayment(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'pickup_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:pickup_date',
        ]);

        $car = Car::findOrFail($request->car_id);
        $pickup = Carbon::parse($request->pickup_date);
        $return = Carbon::parse($request->return_date);
        $duration = $pickup->diffInDays($return) ?: 1;

        $basePrice = $car->daily_rate * $duration;
        $discountAmount = 0;
        $voucherCode = null;

        if ($request->filled('voucher')) {
            $voucher = Voucher::where('code', $request->voucher)
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
        $bookingData['total_price'] = $basePrice - $discountAmount;
        $bookingData['voucher'] = $voucherCode;

        return view('bookings.payment_summary', [
            'car' => $car,
            'bookingData' => $bookingData
        ]);
    }

    public function paymentPage($reference)
    {
        $booking = Booking::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $reference)
                ->with('error', 'This booking cannot be paid.');
        }
        
        return view('payment', compact('booking'));
    }

    public function receipt($reference) {
        $booking = Booking::where('booking_reference', $reference)->where('user_id', auth()->id())->firstOrFail();
        return view('receipt', compact('booking'));
    }

    public function confirmation($reference): View {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        return view('bookings.confirmation', compact('booking'));
    }

    public function pending($reference) {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        if (auth()->id() !== $booking->user_id) abort(403);
        return view('bookings.pending', compact('booking'));
    }
}