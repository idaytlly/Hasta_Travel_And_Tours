<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Models\Payment;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingController extends Controller
{
    const DELIVERY_FEE = 15.00;

    /**
     * Display bookings for logged-in customer
     */
    public function index()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to view your bookings.');
        }

        $customer = Auth::guard('customer')->user();
        $bookings = $customer->bookings()
            ->with(['vehicle', 'payments', 'voucher'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show booking creation form
     */
    public function create($plate_no)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('info', 'Please login to make a booking.');
        }

        $vehicle = Vehicle::where('plate_no', $plate_no)->firstOrFail();

        $vehicles = Vehicle::where('availability_status', 'available')->get();
        $vouchers = Voucher::where('voucherStatus', 'active')->get();

        return view('bookings.create', compact('vehicle', 'vehicles', 'vouchers'));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        Log::info('=== BOOKING STORE METHOD CALLED ===');
        
        $validated = $request->validate([
            'plate_no' => 'required|exists:vehicle,plate_no',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|date_format:H:i',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'return_time' => 'required|date_format:H:i',
            'voucher_code' => 'nullable|string',
            'pickup_location' => 'required|string',
            'dropoff_location' => 'required|string',
            'pickup_college' => 'nullable|string|max:255',
            'pickup_faculty' => 'nullable|string|max:255',
            'dropoff_college' => 'nullable|string|max:255',
            'dropoff_faculty' => 'nullable|string|max:255',
            'signature' => 'required|string',
        ]);

        $customer = Auth::guard('customer')->user();
        DB::beginTransaction();
        try {
            // Vehicle
            $vehicle = Vehicle::where('plate_no', $validated['plate_no'])->firstOrFail();
            if ($vehicle->availability_status !== 'available') {
                return back()->with('error', 'Vehicle is not available for booking.')->withInput();
            }

            // Pickup/return datetime
            $pickupDateTime = Carbon::parse($validated['pickup_date'] . ' ' . $validated['pickup_time']);
            $returnDateTime = Carbon::parse($validated['return_date'] . ' ' . $validated['return_time']);
            if ($returnDateTime->lte($pickupDateTime)) {
                return back()->with('error', 'Return must be after pickup.')->withInput();
            }
            $totalHours = ceil($pickupDateTime->diffInHours($returnDateTime));
            $rentalPrice = $totalHours * $vehicle->price_perHour;

            // Delivery fee
            $deliveryRequired = ($validated['pickup_location'] !== 'HASTA office') || ($validated['dropoff_location'] !== 'HASTA office');
            $deliveryFee = $deliveryRequired ? self::DELIVERY_FEE : 0;

            // Voucher
            $voucherId = null;
            $discountAmount = 0;

            if (!empty($validated['voucher_code'])) {
                $voucher = Voucher::where('voucherCode', strtoupper($validated['voucher_code']))
                    ->where('voucherStatus', 'active')
                    ->where('is_used', false)
                    ->where('expiryDate', '>=', now())
                    ->first();
                if ($voucher) {
                    // Check if voucher belongs to this customer
                    if ($voucher->customer_id && $voucher->customer_id !== $customer->customer_id) {
                        return back()->with('error', 'This voucher code is not valid for your account.')->withInput();
                    }
                    
                    // Calculate discount based on RENTAL PRICE ONLY
                    $discountAmount = ($rentalPrice * $voucher->voucherAmount) / 100;
                    $voucherId = $voucher->voucher_id;
                    
                    // Mark voucher as used immediately
                    $voucher->markAsUsed();
                } else {
                    return back()->with('error', 'Invalid or expired voucher code.')->withInput();
                }
            }

            $totalPrice = $rentalPrice + $deliveryFee - $discountAmount;

            // Locations
            $pickupDetails = $this->formatLocationDetails($validated['pickup_location'], $validated['pickup_college'] ?? null, $validated['pickup_faculty'] ?? null);
            $dropoffDetails = $this->formatLocationDetails($validated['dropoff_location'], $validated['dropoff_college'] ?? null, $validated['dropoff_faculty'] ?? null);

            // Signature
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

            // Create booking
            $bookingId = 'BKG-' . time() . '-' . rand(100, 999);
            $booking = Booking::create([
                'booking_id' => $bookingId,
                'customer_id' => $customer->customer_id,
                'plate_no' => $vehicle->plate_no,
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

            $vehicle->update(['availability_status' => 'booked']);

            DB::commit();
            return redirect()->route('bookings.payment', $booking->booking_id)
                ->with('success', 'Booking created successfully! Please complete payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating booking: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show payment page for a booking
     */
    public function payment($bookingId)
    {
        $booking = Booking::with(['vehicle', 'customers', 'voucher'])
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
            'payment_proof' => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120',
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
            
            // ⭐ Award stamp if booking >= 7 hours
            $this->awardStamps($booking);

            return redirect()->route('bookings.show', $booking->booking_id)
                ->with('success', 'Payment proof uploaded successfully! Your booking is pending approval.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    /**
     * Award stamps after booking completion 
     */
    private function awardStamps($booking)
    {
        try {
            // Don't award if already awarded
            if ($booking->stamp_awarded) {
                return;
            }
            
            // Calculate booking hours
            $totalHours = $booking->calculateTotalHours();
            
            // Only award stamp if booking is 7+ hours
            if ($totalHours >= 7) {
                // Update booking to mark stamp as awarded
                $booking->update([
                    'stamps_earned' => 1,
                    'stamp_awarded' => true,
                ]);
                
                // Check if customer reached a milestone
                $totalStamps = Booking::where('customer_id', $booking->customer_id)
                    ->where('stamp_awarded', true)
                    ->sum('stamps_earned');
                
                $this->checkAndGenerateVoucher($booking->customer_id, $totalStamps);
                
                \Log::info('Stamp awarded', [
                    'customer_id' => $booking->customer_id,
                    'booking_id' => $booking->booking_id,
                    'total_stamps' => $totalStamps,
                    'booking_hours' => $totalHours
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error awarding stamp: ' . $e->getMessage());
        }
    }

    /**
     * Generate voucher when customer reaches milestones (3, 9, 12 stamps)
     */
    private function checkAndGenerateVoucher($customerId, $totalStamps)
    {
        $milestones = [
            3 => 20,  // 3 stamps = 20% discount
            9 => 30,  // 9 stamps = 30% discount
            12 => 50, // 12 stamps = 50% discount
        ];
        
        foreach ($milestones as $stampCount => $discountPercent) {
            if ($totalStamps == $stampCount) {
                // Check if voucher already exists for this milestone
                $existingVoucher = Voucher::where('customer_id', $customerId)
                    ->where('stamp_milestone', $stampCount)
                    ->first();
                
                if (!$existingVoucher) {
                    // Generate unique voucher code
                    $voucherCode = 'STAMP' . $stampCount . '-' . strtoupper(substr(md5($customerId . time()), 0, 6));
                    
                    Voucher::create([
                        'voucher_id' => 'VOU-' . time() . '-' . rand(100, 999),
                        'customer_id' => $customerId,
                        'voucherCode' => $voucherCode,
                        'voucherAmount' => $discountPercent,
                        'stamp_milestone' => $stampCount,
                        'used_count' => 0,
                        'is_used' => false,
                        'expiryDate' => now()->addMonths(6), // Valid for 6 months
                        'voucherStatus' => 'active',
                    ]);
                    
                    \Log::info('Voucher generated', [
                        'customer_id' => $customerId,
                        'voucher_code' => $voucherCode,
                        'discount' => $discountPercent . '%',
                        'milestone' => $stampCount
                    ]);
                }
            }
        }
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login.');
        }

        $customer = Auth::guard('customer')->user();
        $booking = $customer->bookings()
            ->with(['vehicle', 'voucher', 'payments'])
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login.');
        }

        $customer = Auth::guard('customer')->user();
        $booking = $customer->bookings()->findOrFail($id);

        $request->validate(['cancellation_reason' => 'required|string|max:500']);

        if (!in_array($booking->booking_status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Only pending or confirmed bookings can be cancelled.');
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'booking_status' => 'cancelled',
                'special_requests' => $request->cancellation_reason . "\n\n" . ($booking->special_requests ?? '')
            ]);

            // Vehicle back to available
            $vehicle = Vehicle::find($booking->plate_no);
            if ($vehicle) $vehicle->update(['availability_status' => 'available']);

            // Refund voucher
            if ($booking->voucher_id) {
                $voucher = Voucher::find($booking->voucher_id);
                if ($voucher && $voucher->used_count > 0) $voucher->decrement('used_count');
            }

            DB::commit();
            return back()->with('success', 'Booking cancelled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    /**
     * Format locations
     */
    private function formatLocationDetails($location, $college = null, $faculty = null)
    {
        if ($location === 'HASTA office') return 'HASTA Office';
        if ($location === 'Residential College' && $college) return $college;
        if ($location === 'Faculty' && $faculty) return $faculty;
        return $location;
    }
}
