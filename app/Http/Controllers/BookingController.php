<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
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
            ->where('customer_id', Auth::guard('customer')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create($plateNo)
    {
        $vehicle = Vehicle::where('plate_no', $plateNo)->firstOrFail();
        
        // Check if vehicle is available
        if ($vehicle->availability_status !== 'available') {
            return redirect()->route('vehicles.index')
                ->with('error', 'This vehicle is not available for booking.');
        }

        // Get available vouchers
        $vouchers = Voucher::where('voucherStatus', 'active')
            ->where('expiryDate', '>=', now())
            ->get();

        return view('bookings.create', compact('vehicle', 'vouchers'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'plate_no' => 'required|exists:vehicles,plate_no',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|date_format:H:i',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required|date_format:H:i',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'special_requests' => 'nullable|string|max:500',
            'terms' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Get the vehicle
            $vehicle = Vehicle::where('plate_no', $validated['plate_no'])->firstOrFail();

            // Check availability again
            if ($vehicle->availability_status !== 'available') {
                return back()->with('error', 'This vehicle is no longer available.')
                    ->withInput();
            }

            // Calculate hours
            $pickupDateTime = Carbon::parse($validated['pickup_date'] . ' ' . $validated['pickup_time']);
            $returnDateTime = Carbon::parse($validated['return_date'] . ' ' . $validated['return_time']);
            $totalHours = (int) ceil($pickupDateTime->diffInHours($returnDateTime));

            // Calculate pricing
            $totalPrice = $totalHours * $vehicle->price_perHour;

            // Apply voucher if provided
            if ($validated['voucher_id']) {
                $voucher = Voucher::find($validated['voucher_id']);
                if ($voucher && $voucher->status === 'active' && $voucher->expiry_date >= now()) {
                    $discount = ($totalPrice * $voucher->discount_percentage) / 100;
                    $totalPrice -= $discount;
                }
            }

            // Create the booking
            $booking = Booking::create([
                'booking_id' => 'BKG' . strtoupper(uniqid()),
                'plate_no' => $validated['plate_no'],
                'customer_id' => Auth::id(),
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'return_date' => $validated['return_date'],
                'return_time' => $validated['return_time'],
                'total_price' => $totalPrice,
                'booking_status' => 'pending',
                'special_requests' => $validated['special_requests'] ?? null,
                'voucher_id' => $validated['voucher_id'] ?? null,
            ]);

            // Update vehicle availability
            $vehicle->update(['availability_status' => 'booked']);

            DB::commit();

            return redirect()->route('bookings.show', $booking->booking_id)
                ->with('success', 'Booking created successfully! Your booking is pending approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        $booking = Booking::with(['vehicle', 'customer', 'voucher'])
            ->where('booking_id', $id)
            ->firstOrFail();

        // Check if user owns this booking
        if ($booking->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking.');
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel($id)
    {
        $booking = Booking::where('booking_id', $id)->firstOrFail();

        // Check if user owns this booking
        if ($booking->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if booking can be cancelled
        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update(['booking_status' => 'cancelled']);

            // Make vehicle available again
            $booking->vehicle->update(['availability_status' => 'available']);

            DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Booking cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'An error occurred while cancelling the booking.');
        }
    }
}