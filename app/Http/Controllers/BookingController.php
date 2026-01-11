<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
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
        
        if ($vehicle->availability_status !== 'available') {
            return redirect()->route('vehicles.index')
                ->with('error', 'This vehicle is not available for booking.');
        }

        return view('bookings.create', compact('vehicle'));
    }

    /**
 * Store a newly created booking
 */
public function store(Request $request)
{
    \Log::info('=== BOOKING STORE METHOD CALLED ===');
    \Log::info('Request data:', $request->all());
    
    // Validate the request
    $validated = $request->validate([
        'plate_no' => 'required|exists:vehicle,plate_no',
        'pickup_date' => 'required|date|after_or_equal:today',
        'pickup_time' => 'required|date_format:H:i',
        'return_date' => 'required|date|after_or_equal:pickup_date',
        'return_time' => 'required|date_format:H:i',
        'pickup_location' => 'required|string',
        'pickup_college' => 'nullable|string|max:255',
        'pickup_faculty' => 'nullable|string|max:255',
        'dropoff_location' => 'required|string',
        'dropoff_college' => 'nullable|string|max:255',
        'dropoff_faculty' => 'nullable|string|max:255',
        'voucher_code' => 'nullable|string',
        'signature' => 'required|string',
    ]);

    \Log::info('Validation passed');

    try {
        DB::beginTransaction();

        // Get the vehicle
        $vehicle = Vehicle::where('plate_no', $validated['plate_no'])->firstOrFail();
        \Log::info('Vehicle found:', ['plate_no' => $vehicle->plate_no, 'name' => $vehicle->name]);

        // Check availability
        if ($vehicle->availability_status !== 'available') {
            \Log::warning('Vehicle not available', ['plate_no' => $vehicle->plate_no, 'status' => $vehicle->availability_status]);
            return back()->with('error', 'This vehicle is no longer available.')
                ->withInput();
        }

        // Get customer from authenticated user
        $customer = Auth::guard('customer')->user();
        \Log::info('Customer:', ['id' => $customer->customer_id, 'name' => $customer->name]);

        // Generate booking ID
        $bookingId = $this->generateBookingId();
        \Log::info('Generated booking ID:', ['booking_id' => $bookingId]);

        // Calculate hours and pricing
        $pickupDateTime = Carbon::parse($validated['pickup_date'] . ' ' . $validated['pickup_time']);
        $returnDateTime = Carbon::parse($validated['return_date'] . ' ' . $validated['return_time']);
        $totalHours = (int) ceil($pickupDateTime->diffInHours($returnDateTime));
        $totalPrice = $totalHours * $vehicle->price_perHour;

        \Log::info('Price calculation:', [
            'hours' => $totalHours,
            'price_per_hour' => $vehicle->price_perHour,
            'subtotal' => $totalPrice
        ]);

        // Apply voucher if provided
        $voucherId = null;
        if (!empty($validated['voucher_code'])) {
            $voucher = Voucher::where('voucher_code', strtoupper($validated['voucher_code']))
                ->where('voucherStatus', 'active')
                ->where('expiryDate', '>=', now())
                ->first();
            
            if ($voucher) {
                $discount = ($totalPrice * $voucher->voucherAmount) / 100;
                $totalPrice -= $discount;
                $voucherId = $voucher->voucher_id;
                \Log::info('Voucher applied:', ['code' => $voucher->voucher_code, 'discount' => $discount]);
            }
        }

        // Determine pickup and dropoff details
        $pickupDetails = $this->formatLocationDetails(
            $validated['pickup_location'],
            $validated['pickup_college'] ?? null,
            $validated['pickup_faculty'] ?? null
        );

        $dropoffDetails = $this->formatLocationDetails(
            $validated['dropoff_location'],
            $validated['dropoff_college'] ?? null,
            $validated['dropoff_faculty'] ?? null
        );

        // Check if delivery is required
        $deliveryRequired = ($validated['pickup_location'] !== 'HASTA office') || 
                           ($validated['dropoff_location'] !== 'HASTA office');

        // Save signature image
        $signaturePath = null;
        if (!empty($validated['signature'])) {
            try {
                $signatureData = $validated['signature'];
                // Remove data:image/png;base64, prefix if present
                if (strpos($signatureData, 'base64,') !== false) {
                    $signatureData = explode('base64,', $signatureData)[1];
                }
                $signatureData = base64_decode($signatureData);
                
                $fileName = 'signatures/' . $bookingId . '.png';
                Storage::disk('public')->put($fileName, $signatureData);
                $signaturePath = $fileName;
                \Log::info('Signature saved:', ['path' => $signaturePath]);
            } catch (\Exception $e) {
                \Log::error('Failed to save signature: ' . $e->getMessage());
            }
        }

        // Create the booking
        $booking = Booking::create([
            'booking_id' => $bookingId,  // ADD THIS LINE!
            'customer_id' => $customer->customer_id,
            'plate_no' => $validated['plate_no'],
            'pickup_date' => $validated['pickup_date'],
            'pickup_time' => $validated['pickup_time'],
            'pickup_location' => $validated['pickup_location'],
            'pickup_details' => $pickupDetails,
            'return_date' => $validated['return_date'],
            'return_time' => $validated['return_time'],
            'dropoff_location' => $validated['dropoff_location'],
            'dropoff_details' => $dropoffDetails,
            'delivery_required' => $deliveryRequired,
            'total_price' => $totalPrice,
            'booking_status' => 'pending',
            'voucher_id' => $voucherId,
            'signature' => $signaturePath,
        ]);

        \Log::info('Booking created successfully:', ['booking_id' => $booking->booking_id]);

        // Update vehicle availability
        $vehicle->update(['availability_status' => 'booked']);

        DB::commit();

        // Redirect to payment page
        return redirect()->route('bookings.payment', $booking->booking_id)
            ->with('success', 'Booking created successfully! Please complete payment.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Booking creation failed: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()->with('error', 'An error occurred: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Generate a unique booking ID
 */
private function generateBookingId()
{
    // Get the latest booking ID
    $latestBooking = Booking::orderBy('created_at', 'desc')->first();
    
    if (!$latestBooking) {
        return 'BK00001';
    }
    
    // Extract the number from the last booking ID (e.g., BK00001 -> 1)
    $lastNumber = (int) substr($latestBooking->booking_id, 2);
    $newNumber = $lastNumber + 1;
    
    // Format as BK00001, BK00002, etc.
    return 'BK' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
}

    /**
     * Show payment page for a booking
     */
    public function payment($bookingId)
    {
        $booking = Booking::with(['vehicle', 'customer', 'voucher'])
            ->where('booking_id', $bookingId)
            ->firstOrFail();

        return view('bookings.payment', compact('booking'));
    }

    /**
     * Process payment submission
     */
    public function storePayment(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'payment_proof' => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120', // 5MB max
            'payment_method' => 'nullable|string',
        ]);

        try {
            $booking = Booking::findOrFail($bookingId);

            // Upload payment proof
            $file = $request->file('payment_proof');
            $fileName = 'payment_proofs/' . $booking->booking_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $fileName);

            // Create payment record
            Payment::create([
                'booking_id' => $booking->booking_id,
                'amount' => $booking->total_price,
                'payment_method' => $validated['payment_method'] ?? 'online',
                'payment_status' => 'pending',
                'payment_date' => now(),
                'payment_proof' => $fileName,
                'deposit' => 0,
                'remaining_payment' => $booking->total_price,
            ]);

            return redirect()->route('bookings.show', $booking->booking_id)
                ->with('success', 'Payment proof uploaded successfully! Your booking is pending approval.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        $booking = Booking::with(['vehicle', 'customer', 'voucher', 'payments'])
            ->where('booking_id', $id)
            ->firstOrFail();

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel($id)
    {
        $booking = Booking::where('booking_id', $id)->firstOrFail();

        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update(['booking_status' => 'cancelled']);
            
            // Make vehicle available again
            $booking->vehicle->update(['availability_status' => 'available']);

            // Update payment status if exists
            $booking->payments()->where('payment_status', 'pending')->update([
                'payment_status' => 'refunded'
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
     * Helper method to format location details
     */
    private function formatLocationDetails($location, $college = null, $faculty = null)
    {
        if ($location === 'HASTA office') {
            return 'HASTA Office';
        } elseif ($location === 'Residential College' && $college) {
            return $college;
        } elseif ($location === 'Faculty' && $faculty) {
            return $faculty;
        }
        return $location;
    }
}