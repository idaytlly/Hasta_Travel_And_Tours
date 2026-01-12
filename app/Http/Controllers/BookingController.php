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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Delivery fee constant
    const DELIVERY_FEE = 15.00;

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
     * Validate voucher code via AJAX
    */
    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);
        
        $code = strtoupper(trim($request->code));
        
        \Log::info('Validating voucher', ['code' => $code]);
        
        // Find the voucher using your actual column names
        $voucher = Voucher::where('voucherCode', $code)->first();
        
        // Check if voucher exists
        if (!$voucher) {
            \Log::info('Voucher not found', ['code' => $code]);
            return response()->json([
                'valid' => false,
                'message' => 'Invalid voucher code'
            ]);
        }
        
        // Check if voucher is active
        if ($voucher->voucherStatus !== 'active') {
            \Log::info('Voucher not active', ['code' => $code, 'status' => $voucher->voucherStatus]);
            return response()->json([
                'valid' => false,
                'message' => 'This voucher is no longer active'
            ]);
        }
        
        // Check expiry date (if expiryDate exists)
        if ($voucher->expiryDate) {
            $expiryDate = Carbon::parse($voucher->expiryDate);
            if (now()->gt($expiryDate)) {
                \Log::info('Voucher expired', ['code' => $code, 'expiry' => $voucher->expiryDate]);
                return response()->json([
                    'valid' => false,
                    'message' => 'This voucher has expired'
                ]);
            }
        }
        
        // Voucher is valid
        \Log::info('Voucher validated successfully', [
            'code' => $code,
            'discount' => $voucher->voucherAmount
        ]);
        
        return response()->json([
            'valid' => true,
            'discount' => $voucher->voucherAmount,
            'code' => $voucher->voucherCode
        ]);
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        Log::info('=== BOOKING STORE METHOD CALLED ===');
        
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
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

            // 2. Get Customer and verify their information
            $customer = Auth::guard('customer')->user();
            
            // Verify that the customer name and phone match the authenticated user
            if (trim($validated['customer_name']) !== trim($customer->name)) {
                return back()->with('error', 'Customer name does not match your profile.')->withInput();
            }
            
            if (trim($validated['customer_phone']) !== trim($customer->phone_no)) {
                return back()->with('error', 'Phone number does not match your profile.')->withInput();
            }
            
            // Check if customer profile is complete
            if (empty($customer->name) || empty($customer->phone_no)) {
                return redirect()->route('customer.profile.edit')
                    ->with('error', 'Please complete your profile before making a booking.');
            }

            // 3. FIX: Sanitize Dates (Take only first 10 chars YYYY-MM-DD)
            $pickupDateOnly = substr($validated['pickup_date'], 0, 10);
            $returnDateOnly = substr($validated['return_date'], 0, 10);

            $pickupDateTime = Carbon::parse($pickupDateOnly . ' ' . $validated['pickup_time']);
            $returnDateTime = Carbon::parse($returnDateOnly . ' ' . $validated['return_time']);
            
            // Validate that return is after pickup
            if ($returnDateTime->lte($pickupDateTime)) {
                return back()->with('error', 'Return date/time must be after pickup date/time.')->withInput();
            }
            
            $totalHours = (int) ceil($pickupDateTime->diffInHours($returnDateTime));
            
            // Validate minimum rental duration (e.g., at least 1 hour)
            if ($totalHours < 1) {
                return back()->with('error', 'Minimum rental duration is 1 hour.')->withInput();
            }
            
            // Calculate base price (rental only)
            $rentalPrice = $totalHours * $vehicle->price_perHour;

            // 4. Check if delivery is required
            $deliveryRequired = ($validated['pickup_location'] !== 'HASTA office') || 
                               ($validated['dropoff_location'] !== 'HASTA office');
            
            // Add delivery fee if required
            $deliveryFee = $deliveryRequired ? self::DELIVERY_FEE : 0;
            
            // Calculate subtotal (rental + delivery)
            $subtotal = $rentalPrice + $deliveryFee;
            
            // 5. Apply Voucher Discount
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
            
            // Calculate final total price
            $totalPrice = $subtotal - $discountAmount;

            // 6. Format Locations
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

            // 7. Handle Signature
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

            // 8. Generate ID & Create Booking
            $bookingId = 'BKG-' . time() . '-' . rand(100, 999);

            $booking = Booking::create([
                'booking_id' => $bookingId,
                'customer_id' => $customer->customer_id,
                'plate_no' => $validated['plate_no'],
                'pickup_date' => $pickupDateOnly,
                'pickup_time' => $validated['pickup_time'],
                'pickup_location' => $validated['pickup_location'],
                'pickup_details' => $pickupDetails,
                'return_date' => $returnDateOnly,
                'return_time' => $validated['return_time'],
                'dropoff_location' => $validated['dropoff_location'],
                'dropoff_details' => $dropoffDetails,
                'delivery_required' => $deliveryRequired,
                'total_price' => $totalPrice,
                'booking_status' => 'pending',
                'voucher_id' => $voucherId,
                'signature' => $signaturePath,
            ]);

            // Update vehicle availability
            $vehicle->update(['availability_status' => 'booked']);
            
            // Log the booking creation
            \Log::info('Booking created successfully', [
                'booking_id' => $bookingId,
                'customer_id' => $customer->customer_id,
                'rental_price' => $rentalPrice,
                'delivery_fee' => $deliveryFee,
                'discount_applied' => $discountAmount,
                'total_price' => $totalPrice
            ]);

            DB::commit();

            return redirect()->route('bookings.payment', $booking->booking_id)
                ->with('success', 'Booking created successfully! Please complete payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error creating booking: ' . $e->getMessage())->withInput();
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
        $booking = Booking::with(['vehicle', 'customer', 'voucher', 'payments'])
            ->where('booking_id', $id)
            ->firstOrFail();

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show inspection form for pickup or dropoff
     */
    public function inspection($id, $type)
    {
        $booking = Booking::with('vehicle', 'customer')->findOrFail($id);
        
        // Verify customer owns this booking
        if ($booking->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'Unauthorized access to this booking.');
        }
        
        // Validate inspection type
        if (!in_array($type, ['pickup', 'dropoff'])) {
            abort(404, 'Invalid inspection type.');
        }
        
        // Check if booking status allows inspection
        if ($type === 'pickup' && $booking->booking_status !== 'confirmed') {
            return redirect()->route('bookings.show', $id)
                ->with('error', 'Pick-up inspection can only be done for confirmed bookings.');
        }
        
        if ($type === 'dropoff' && $booking->booking_status !== 'active') {
            return redirect()->route('bookings.show', $id)
                ->with('error', 'Drop-off inspection can only be done for active rentals.');
        }
        
        // Check if inspection already exists
        $existingInspection = \App\Models\Inspection::where('booking_id', $id)
            ->where('inspection_type', $type)
            ->first();
        
        if ($existingInspection) {
            return redirect()->route('bookings.show', $id)
                ->with('error', ucfirst($type) . ' inspection has already been completed.');
        }
        
        $inspectionType = $type;
        return view('bookings.inspection', compact('booking', 'inspectionType'));
    }

    /**
     * Store inspection data
     */
    public function storeInspection(Request $request, $id, $type)
    {
        $booking = Booking::findOrFail($id);
        
        // Verify customer owns this booking
        if ($booking->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'Unauthorized access to this booking.');
        }
        
        // Validate inspection type
        if (!in_array($type, ['pickup', 'dropoff'])) {
            abort(404, 'Invalid inspection type.');
        }
        
        $validated = $request->validate([
            'photo_front' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'photo_back' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'photo_left' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'photo_right' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'photo_fuel' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'remarks' => 'required|string|min:10|max:1000',
            'signature' => 'required|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Generate inspection ID
            $inspectionId = 'INS-' . time() . '-' . rand(100, 999);
            
            // Handle 4 sides car photos
            $carPhotos = [];
            $photoTypes = ['front', 'back', 'left', 'right'];
            
            foreach ($photoTypes as $side) {
                $photoField = 'photo_' . $side;
                if ($request->hasFile($photoField)) {
                    $file = $request->file($photoField);
                    $fileName = 'inspections/' . $id . '_' . $type . '_' . $side . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public', $fileName);
                    $carPhotos[$side] = $fileName;
                }
            }
            
            // Handle fuel gauge photo
            $fuelPhotoPath = null;
            if ($request->hasFile('photo_fuel')) {
                $file = $request->file('photo_fuel');
                $fileName = 'inspections/' . $id . '_' . $type . '_fuel_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public', $fileName);
                $fuelPhotoPath = $fileName;
            }
            
            // Handle signature
            $signaturePath = null;
            if (!empty($validated['signature'])) {
                $signatureData = $validated['signature'];
                if (strpos($signatureData, 'base64,') !== false) {
                    $signatureData = explode('base64,', $signatureData)[1];
                }
                $fileName = 'inspections/signatures/' . $id . '_' . $type . '_' . time() . '.png';
                Storage::disk('public')->put($fileName, base64_decode($signatureData));
                $signaturePath = $fileName;
            }
            
            // Create inspection record
            \App\Models\Inspection::create([
                'inspection_id' => $inspectionId,
                'booking_id' => $id,
                'inspection_type' => $type,
                'inspection_date' => now()->toDateString(),
                'car_photos' => json_encode($carPhotos),
                'fuel_photo' => $fuelPhotoPath,
                'remarks' => $validated['remarks'],
                'signature' => $signaturePath,
                'inspection_status' => 'completed',
                'damage_notes' => $validated['remarks'],
                'inspected_by' => Auth::guard('customer')->id(),
                'inspected_at' => now(),
            ]);
            
            // Update booking status based on inspection type
            if ($type === 'pickup') {
                $booking->update(['booking_status' => 'active']);
                
                // Update vehicle status to in-use
                $booking->vehicle->update(['availability_status' => 'in-use']);
                
                $message = 'Pick-up inspection completed successfully! You can now use the vehicle.';
            } elseif ($type === 'dropoff') {
                $booking->update(['booking_status' => 'completed']);
                
                // Update vehicle status back to available
                $booking->vehicle->update(['availability_status' => 'available']);
                
                $message = 'Drop-off inspection completed successfully! Thank you for using our service.';
            }
            
            // Log the inspection
            \Log::info('Inspection completed', [
                'inspection_id' => $inspectionId,
                'booking_id' => $id,
                'type' => $type,
                'customer_id' => Auth::guard('customer')->id(),
                'photos_uploaded' => count($carPhotos) + 1
            ]);
            
            DB::commit();
            
            return redirect()->route('bookings.show', $id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Inspection Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'booking_id' => $id,
                'type' => $type
            ]);
            
            return back()->with('error', 'Error submitting inspection: ' . $e->getMessage())->withInput();
        }
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