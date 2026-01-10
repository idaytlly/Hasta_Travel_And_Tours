<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\RentalRate;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
     /**
     * Display a listing of the bookings
     */
    public function index()
    {
        $bookings = Booking::with(['vehicle', 'customer'])
            ->where('matricNum', Auth::user()->matricNum)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create($plateNo)
    {
        $car = Vehicle::findOrFail($plateNo);
        
        // Check if car is available
        if (!$car->isAvailable()) {
            return redirect()->route('cars.index')
                ->with('error', 'This vehicle is not available for booking.');
        }

        // Get rental rates for this vehicle
        $rentalRates = RentalRate::where('plate_no', $plateNo)
            ->orderBy('hours')
            ->get();

        if ($rentalRates->isEmpty()) {
            return redirect()->route('cars.index')
                ->with('error', 'No rental rates available for this vehicle.');
        }

        return view('bookings.create', compact('car', 'rentalRates'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'plate_no' => 'required|exists:vehicles,plate_no',
            'pickup_date' => 'required|date|after:now',
            'return_date' => 'required|date|after:pickup_date',
            'matricNum' => 'required|string',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'delivery' => 'nullable|boolean',
            'terms' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Get the vehicle
            $vehicle = Vehicle::findOrFail($validated['plate_no']);

            // Check availability again
            if (!$vehicle->isAvailable()) {
                return back()->with('error', 'This vehicle is no longer available.')
                    ->withInput();
            }

            // Calculate hours
            $pickupDate = Carbon::parse($validated['pickup_date']);
            $returnDate = Carbon::parse($validated['return_date']);
            $totalHours = (int) ceil($pickupDate->diffInHours($returnDate));

            // Find applicable rental rate
            $applicableRate = RentalRate::findRateForHours($validated['plate_no'], $totalHours);
            
            if (!$applicableRate) {
                return back()->with('error', 'No rental rate available for this duration.')
                    ->withInput();
            }

            // Calculate pricing
            $rentalPrice = $applicableRate->rate_price;
            $deposit = 50.00;
            $deliveryCharge = $request->has('delivery') && $request->delivery ? 15.00 : 0.00;
            $totalPrice = $rentalPrice + $deposit + $deliveryCharge;

            // Apply voucher if provided
            $voucherDiscount = 0;
            if ($validated['voucher_id']) {
                $voucher = Voucher::find($validated['voucher_id']);
                if ($voucher && $voucher->isValid()) {
                    // Apply discount logic here (percentage or fixed)
                    // Example: 10% discount
                    $voucherDiscount = $rentalPrice * 0.10;
                    $totalPrice -= $voucherDiscount;
                }
            }

            // Create the booking
            $booking = Booking::create([
                'plate_no' => $validated['plate_no'],
                'matricNum' => $validated['matricNum'],
                'pickup_date' => $pickupDate,
                'return_date' => $returnDate,
                'total_hours' => $totalHours,
                'total_price' => $totalPrice,
                'deposit' => $deposit,
                'delivery_charge' => $deliveryCharge,
                'booking_status' => 'pending',
                'voucher_id' => $validated['voucher_id'] ?? null,
            ]);

            // Update vehicle availability
            $vehicle->update([
                'availability_status' => 'rented',
                'customer_id' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()->route('bookings.show', $booking->booking_id)
                ->with('success', 'Booking created successfully! Your booking is pending approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'An error occurred while creating the booking. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        $booking = Booking::with(['vehicle', 'customer', 'voucher'])
            ->findOrFail($id);

        // Check if user owns this booking
        if ($booking->matricNum !== Auth::user()->matricNum && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to booking.');
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Check if user owns this booking
        if ($booking->matricNum !== Auth::user()->matricNum) {
            abort(403, 'Unauthorized action.');
        }

        // Check if booking can be cancelled
        if (!$booking->isPending() && !$booking->isConfirmed()) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update(['booking_status' => 'cancelled']);

            // Make vehicle available again
            $booking->vehicle->update([
                'availability_status' => 'available',
                'customer_id' => null,
            ]);

            DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Booking cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'An error occurred while cancelling the booking.');
        }
    }

    /**
     * Admin: Confirm a booking
     */
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->isPending()) {
            return back()->with('error', 'Only pending bookings can be confirmed.');
        }

        $booking->update(['booking_status' => 'confirmed']);

        return back()->with('success', 'Booking confirmed successfully.');
    }

    /**
     * Admin: Complete a booking
     */
    public function complete($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->isConfirmed()) {
            return back()->with('error', 'Only confirmed bookings can be completed.');
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update(['booking_status' => 'completed']);

            // Make vehicle available again
            $booking->vehicle->update([
                'availability_status' => 'available',
                'customer_id' => null,
            ]);

            DB::commit();

            return back()->with('success', 'Booking completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'An error occurred while completing the booking.');
        }
    }
}
