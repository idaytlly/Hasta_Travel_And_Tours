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

        try {
            DB::beginTransaction();

            // 1. Get Vehicle
            $vehicle = Vehicle::where('plate_no', $validated['plate_no'])->firstOrFail();

            if ($vehicle->availability_status !== 'available') {
                return back()->with('error', 'This vehicle is no longer available.')->withInput();
            }

            // 2. Get Customer
            $customer = Auth::guard('customer')->user();

            // 3. FIX: Sanitize Dates (Take only first 10 chars YYYY-MM-DD)
            // This prevents "Double time specification" errors if the input has a time component
            $pickupDateOnly = substr($validated['pickup_date'], 0, 10);
            $returnDateOnly = substr($validated['return_date'], 0, 10);

            $pickupDateTime = Carbon::parse($pickupDateOnly . ' ' . $validated['pickup_time']);
            $returnDateTime = Carbon::parse($returnDateOnly . ' ' . $validated['return_time']);
            
            $totalHours = (int) ceil($pickupDateTime->diffInHours($returnDateTime));
            $totalPrice = $totalHours * $vehicle->price_perHour;

            // 4. Apply Voucher
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
                }
            }

            // 5. Format Locations
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

            $deliveryRequired = ($validated['pickup_location'] !== 'HASTA office') || 
                               ($validated['dropoff_location'] !== 'HASTA office');

            // 6. Handle Signature
            $signaturePath = null;
            if (!empty($validated['signature'])) {
                $signatureData = $validated['signature'];
                if (strpos($signatureData, 'base64,') !== false) {
                    $signatureData = explode('base64,', $signatureData)[1];
                }
                $fileName = 'signatures/' . uniqid() . '.png';
                Storage::disk('public')->put($fileName, base64_decode($signatureData));
                $signaturePath = $fileName;
            }

            // 7. Generate ID & Create Booking
            $bookingId = 'BKG-' . time() . '-' . rand(100, 999);

            $booking = Booking::create([
                'booking_id' => $bookingId,
                'customer_id' => $customer->customer_id,
                'plate_no' => $validated['plate_no'],
                'pickup_date' => $pickupDateOnly,          // <--- UPDATED: Use sanitized date
                'pickup_time' => $validated['pickup_time'],
                'pickup_location' => $validated['pickup_location'],
                'pickup_details' => $pickupDetails,
                'return_date' => $returnDateOnly,          // <--- UPDATED: Use sanitized date
                'return_time' => $validated['return_time'],
                'dropoff_location' => $validated['dropoff_location'],
                'dropoff_details' => $dropoffDetails,
                'delivery_required' => $deliveryRequired,
                'total_price' => $totalPrice,
                'booking_status' => 'pending',
                'voucher_id' => $voucherId,
                'signature' => $signaturePath,
            ]);

            $vehicle->update(['availability_status' => 'booked']);

            DB::commit();

            return redirect()->route('bookings.payment', $booking->booking_id)
                ->with('success', 'Booking created successfully! Please complete payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
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