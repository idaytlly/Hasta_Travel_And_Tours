<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings for logged-in customers
     */
    public function index()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to view your bookings.');
        }

        $customer = Auth::guard('customer')->user();
        $bookings = $customer->bookings()
            ->with(['vehicle', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('info', 'Please login to make a booking.');
        }

        $vehicles = Vehicle::where('availability_status', 'available')->get();
        $vouchers = Voucher::where('voucherStatus', 'active')->get();
        
        return view('bookings.create', compact('vehicles', 'vouchers'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to make a booking.');
        }

        $customer = Auth::guard('customer')->user();
        
        $request->validate([
            'plate_no' => 'required|exists:vehicles,plate_no',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'special_requests' => 'nullable|string|max:500',
        ]);

        // Check vehicle availability
        $vehicle = Vehicle::findOrFail($request->plate_no);
        if ($vehicle->availability_status !== 'available') {
            return back()->with('error', 'Vehicle is not available for booking.')
                        ->withInput();
        }

        DB::beginTransaction();
        try {
            // Calculate price
            $pickupDateTime = Carbon::parse($request->pickup_date . ' ' . $request->pickup_time);
            $returnDateTime = Carbon::parse($request->return_date . ' ' . $request->return_time);
            $totalHours = ceil($pickupDateTime->diffInHours($returnDateTime));
            
            $hourlyRate = $vehicle->price_perHour;
            $totalPrice = $hourlyRate * $totalHours;
            
            // Apply voucher if valid
            if ($request->voucher_id) {
                $voucher = Voucher::findOrFail($request->voucher_id);
                if ($voucher->voucherStatus === 'active') {
                    $discount = $voucher->voucherAmount;
                    $totalPrice = max(0, $totalPrice - $discount);
                    $voucher->increment('used_count');
                }
            }

            // Generate booking ID
            $bookingId = 'BKG' . date('Ymd') . strtoupper(uniqid());

            // Create booking
            $booking = Booking::create([
                'booking_id' => $bookingId,
                'customer_id' => $customer->id,
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

            DB::commit();
            
            return redirect()->route('customer.bookings.show', $booking->booking_id)
                           ->with('success', 'Booking created successfully! Please make payment to confirm.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to view booking details.');
        }

        $customer = Auth::guard('customer')->user();
        $booking = $customer->bookings()
                          ->with(['vehicle', 'voucher', 'payments'])
                          ->findOrFail($id);
        
        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $id)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to cancel booking.');
        }

        $customer = Auth::guard('customer')->user();
        $booking = $customer->bookings()->findOrFail($id);
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Only pending or confirmed bookings can be cancelled.');
        }

        DB::beginTransaction();
        try {
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
     * Make payment for a booking
     */
    public function makePayment(Request $request, $id)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to make payment.');
        }

        $customer = Auth::guard('customer')->user();
        $booking = $customer->bookings()->findOrFail($id);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $booking->total_price,
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Generate payment ID
        $paymentId = 'PAY' . date('Ymd') . strtoupper(uniqid());
        
        $paymentData = [
            'payment_id' => $paymentId,
            'booking_id' => $booking->booking_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'payment_date' => now(),
        ];

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            $paymentData['payment_proof'] = $path;
        }

        Payment::create($paymentData);
        
        return back()->with('success', 'Payment submitted successfully! Please wait for verification.');
    }

    /**
     * Check vehicle availability for extension
     */
    public function checkAvailability(Request $request, $id)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $customer = Auth::guard('customer')->user();
        $booking = $customer->bookings()->findOrFail($id);
        
        // Return availability information
        return response()->json([
            'available' => $booking->vehicle->availability_status === 'available',
            'message' => $booking->vehicle->availability_status === 'available' 
                ? 'Vehicle is available for extension' 
                : 'Vehicle is not available',
        ]);
    }
}