<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Voucher;
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
        $vouchers = Voucher::all(); 
        
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
            $duration = $pickup->diffInDays($return);
            if ($duration < 1) $duration = 1; 

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
                    // Calculate discount
                    if (method_exists($voucher, 'calculateDiscount')) {
                        $discountAmount = $voucher->calculateDiscount($basePrice);
                    } else {
                        $discountAmount = round(($basePrice * $voucher->discount_percentage) / 100, 2);
                    }
                    
                    $voucherCode = $voucher->code;
                    
                    // Increment usage
                    if (method_exists($voucher, 'incrementUsage')) {
                        $voucher->incrementUsage();
                    }
                }
            }

            // Calculate final total price
            $totalPrice = $basePrice - $discountAmount;

            // Deposit is 10%
            $depositAmount = round($totalPrice * 0.1, 2);

            // 6. Create Booking
            $booking = Booking::create([
                'car_id'            => $car->id,
                'user_id'           => auth()->id(),
                'booking_reference' => Booking::generateBookingReference(),
                'customer_name'     => $request->customer_name ?? auth()->user()->name,
                'customer_email'    => $request->customer_email ?? auth()->user()->email,
                'customer_ic'       => $request->customer_ic ?? (auth()->user()->ic ?? null),
                'customer_phone'    => $request->customer_phone ?? (auth()->user()->phone ?? null),
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

            DB::commit();

            return redirect()->route('bookings.pending', $booking->booking_reference)
                ->with('success', 'Booking submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Booking failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show pending booking page
     */
    public function pending($reference)
    {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        
        if (auth()->id() !== $booking->user_id) {
            abort(403, 'Unauthorized access to this booking.');
        }
        
        return view('bookings.pending', compact('booking'));
    }

    /**
     * Show user's bookings
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
     * Cancel booking
     */
    public function cancel($reference)
    {
        $booking = Booking::where('booking_reference', $reference)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        $booking->update(['status' => 'cancelled']);
        $booking->delete(); 

        return redirect()->route('bookings.my-bookings')
                         ->with('success', 'Booking has been cancelled.');
    }

    /**
     * Show booking confirmation
     */
    public function confirmation($reference): View
    {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        return view('bookings.confirmation', compact('booking'));
    }

    /**
     * Show booking details
     */
    public function show($reference): View
    {
        $booking = Booking::withTrashed()
            ->with('car')
            ->where('booking_reference', $reference)
            ->firstOrFail();
            
        return view('bookings.show', compact('booking'));
    }

    /**
     * Staff: Manage all bookings
     */
    public function staffIndex()
    {
        $bookings = Booking::with('car', 'user')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('staff.manage-bookings', compact('bookings'));
    }

    /**
     * Process to payment summary
     */
    public function processToPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'pickup_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:pickup_date',
        ]);

        $car = Car::findOrFail($request->car_id);

        // Recalculate server-side for security
        $pickup = Carbon::parse($request->pickup_date);
        $return = Carbon::parse($request->return_date);
        $duration = $pickup->diffInDays($return) ?: 1;

        $basePrice = $car->daily_rate * $duration;
        $discountAmount = 0;
        $voucherCode = null;

        // Voucher Logic
        if ($request->filled('voucher')) {
            $voucher = Voucher::where('code', $request->voucher)
                              ->where('is_active', true)
                              ->first();
            
            if ($voucher && method_exists($voucher, 'isValid') && $voucher->isValid()) {
                $discountAmount = round(($basePrice * $voucher->discount_percentage) / 100, 2);
                $voucherCode = $voucher->code;
            }
        }

        $finalTotal = $basePrice - $discountAmount;

        // Prepare data for the view
        $bookingData = $request->all();
        $bookingData['duration'] = $duration;
        $bookingData['base_price'] = $basePrice;
        $bookingData['discount_amount'] = $discountAmount;
        $bookingData['total_price'] = $finalTotal;
        $bookingData['voucher'] = $voucherCode;

        return view('bookings.payment_summary', [
            'car' => $car,
            'bookingData' => $bookingData
        ]);
    }

    /**
     * Show payment page
     */
    public function paymentPage($reference)
    {
        $booking = Booking::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $reference)
                ->with('error', 'This booking cannot be paid.');
        }
        
        return view('bookings.payment', compact('booking'));
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request, $reference)
    {
        $request->validate([
            'payment_method' => 'required|in:credit_card,debit_card,online_banking,ewallet',
            'cardholder_name' => 'required_if:payment_method,credit_card,debit_card',
            'card_number' => 'required_if:payment_method,credit_card,debit_card',
            'expiry_date' => 'required_if:payment_method,credit_card,debit_card',
            'cvv' => 'required_if:payment_method,credit_card,debit_card',
        ]);
        
        $booking = Booking::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        // Update booking status to paid
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
        ]);
        
        return redirect()->route('bookings.receipt', $reference)
            ->with('success', 'Payment successful!');
    }

    /**
     * Show receipt
     */
    public function receipt($reference)
    {
        $booking = Booking::where('booking_reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        return view('bookings.receipt', compact('booking'));
    }
}