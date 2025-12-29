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
        $vouchers = Voucher::active()->get();
        
        return view('bookings.create', compact('car', 'vouchers'));
    }

    /**
     * Store booking
     */
    public function store(BookingRequest $request)
    {
        try {
            DB::beginTransaction();

            $car = Car::findOrFail($request->car_id);

            // Check availability
            if (!$car->isAvailableForDates($request->pickup_date, $request->return_date)) {
                return back()->with('error', 'Car is not available for selected dates')->withInput();
            }

            $pickupDate = Carbon::parse($request->pickup_date);
            $returnDate = Carbon::parse($request->return_date);
            $duration = max(1, $pickupDate->diffInDays($returnDate));

            $basePrice = $car->calculatePrice($duration);
            $discountAmount = 0;
            $totalPrice = $basePrice;

            if ($request->voucher) {
                $voucher = Voucher::where('code', $request->voucher)->first();
                if ($voucher && $voucher->isValid()) {
                    $discountAmount = $voucher->calculateDiscount($basePrice);
                    $totalPrice = $basePrice - $discountAmount;
                    $voucher->incrementUsage();
                }
            }

            $depositAmount = round($totalPrice * 0.1, 2);

            $booking = Booking::create([
                'car_id' => $car->id,
                'user_id' => auth()->id(),
                'booking_reference' => Booking::generateBookingReference(),
                'customer_name' => $request->customer_name ?? auth()->user()->name,
                'customer_email' => $request->customer_email ?? auth()->user()->email,
                'customer_phone' => $request->customer_ic?? auth()->user()->ic,
                'customer_phone' => $request->customer_phone?? auth()->user()->phone,
                'pickup_location' => $request->pickup_location,
                'dropoff_location' => $request->dropoff_location,
                'destination' => $request->destination,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'duration' => $duration,
                'voucher' => $request->voucher,
                'base_price' => $basePrice,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'deposit_amount' => $depositAmount,
                'remarks' => $request->remarks,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'customer_ic' => auth()->user()->ic, 
                'customer_phone' => auth()->user()->phone,
            ]);

            DB::commit();

            return redirect()->route('bookings.pending', $booking->booking_reference)
                ->with('success', 'Booking submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show pending booking status
     */
    public function pending($reference)
    {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        
        if (auth()->id() !== $booking->user_id) {
            abort(403, 'Unauthorized');
        }
        
        return view('bookings.pending', compact('booking'));
    }

    /**
     * Consolidated Booking list (Soft Delete aware)
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
     * Cancel/Soft Delete Booking
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

    public function confirmation($reference): View
    {
        $booking = Booking::with('car')->where('booking_reference', $reference)->firstOrFail();
        return view('bookings.confirmation', compact('booking'));
    }

    /**
     * Show detailed booking view (Matches your new design)
     */
    public function show($reference): View
    {
        // Eager load 'car' to display the image and model details correctly
        // This also works for trashed items if you want to view cancelled booking details
        $booking = Booking::withTrashed()
            ->with('car')
            ->where('booking_reference', $reference)
            ->firstOrFail();
            
        return view('bookings.show', compact('booking'));
    }
}