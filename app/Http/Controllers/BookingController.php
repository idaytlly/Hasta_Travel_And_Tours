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
    public function create($carId): View
    {
        $car = Car::findOrFail($carId);
        $vouchers = Voucher::all(); 
        
        return view('bookings.create', compact('car', 'vouchers'));
    }

    public function store(BookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $car = Car::findOrFail($request->car_id);

            // 1. Format dates for SQL (Fixes the "Unknown column" issue in the check)
            $pickupDateStr = Carbon::parse($request->pickup_date)->toDateString();
            $returnDateStr = Carbon::parse($request->return_date)->toDateString();

            // 2. Check availability using actual DB column names via the Model scope
            if (!$car->isAvailableForDates($pickupDateStr, $returnDateStr)) {
                return back()->with('error', 'Sorry, this car is already booked for the selected dates.')->withInput();
            }

            // 3. Calculate Duration (Ensures at least 1 day for same-day rental)
            $pickup = Carbon::parse($request->pickup_date);
            $return = Carbon::parse($request->return_date);
            $duration = $pickup->diffInDays($return);
            if ($duration < 1) $duration = 1; 

            // 4. Calculate Pricing
            $basePrice = $car->daily_rate * $duration;
            $discountAmount = 0;
            $totalPrice = $basePrice;

            // 5. Voucher Logic
            if ($request->filled('voucher')) {
                $voucher = Voucher::where('code', $request->voucher)->first();
                if ($voucher && method_exists($voucher, 'isValid') && $voucher->isValid()) {
                    $discountAmount = $voucher->calculateDiscount($basePrice);
                    $totalPrice = $basePrice - $discountAmount;
                    $voucher->incrementUsage();
                }
            }

            // Deposit is usually 10% in Malaysia rentals
            $depositAmount = round($totalPrice * 0.1, 2);

            // 6. Create Booking
            $booking = Booking::create([
                'car_id'            => $car->id,
                'user_id'           => auth()->id(),
                'booking_reference' => Booking::generateBookingReference(),
                'customer_name'     => $request->customer_name ?? auth()->user()->name,
                'customer_email'    => $request->customer_email ?? auth()->user()->email,
                'customer_ic'       => $request->customer_ic ?? auth()->user()->ic,
                'customer_phone'    => $request->customer_phone ?? auth()->user()->phone,
                'pickup_location'   => $request->pickup_location,
                'dropoff_location'  => $request->dropoff_location,
                'destination'       => $request->destination,
                'pickup_date'       => $pickupDateStr,
                'pickup_time'       => $request->pickup_time,
                'return_date'       => $returnDateStr,
                'return_time'       => $request->return_time,
                'duration'          => $duration,
                'voucher'           => $request->voucher,
                'base_price'        => $basePrice,
                'discount_amount'   => $discountAmount,
                'total_price'       => $totalPrice,
                'deposit_amount'    => $depositAmount,
                'remarks'           => $request->remarks,
                'status'            => 'pending',
                'payment_status'    => 'unpaid',
            ]);

            DB::commit();

            return redirect()->route('bookings.pending', $booking->booking_reference)
                ->with('success', 'Booking submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // This will now catch the SQLSTATE errors and display them cleanly
            return back()->with('error', 'Booking failed: ' . $e->getMessage())->withInput();
        }
    }

    public function pending($reference)
    {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        
        if (auth()->id() !== $booking->user_id) {
            abort(403, 'Unauthorized access to this booking.');
        }
        
        return view('bookings.pending', compact('booking'));
    }

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

    public function confirmation($reference): View
    {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        return view('bookings.confirmation', compact('booking'));
    }

    public function show($reference): View
    {
        $booking = Booking::withTrashed()
            ->with('car')
            ->where('booking_reference', $reference)
            ->firstOrFail();
            
        return view('bookings.show', compact('booking'));
    }
}