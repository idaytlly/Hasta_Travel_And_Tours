<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Voucher;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['vehicle', 'customers', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
            // Add statistics 
            $stats = [ 
                'total' => Booking::count(), 
                'pending' => Booking::where('booking_status', 'pending')->count(), 
                'confirmed' => Booking::where('booking_status', 'confirmed')->count(),                
                'active' => Booking::where('booking_status', 'active')->count(), 
                'cancelled' => Booking::where('booking_status', 'cancelled')->count(),
                'completed' => Booking::where('booking_status', 'completed')->count(), ];

                return view('staff.bookings.index', compact('bookings', 'stats'));
    }

    public function create()
    {
        $vehicle = Vehicle::where('availability_status', 'available')->get();
        $customers = Customer::where('status', 'active')->get();
        $voucher = Voucher::where('voucherStatus', 'active')->get();
        
        return view('staff.bookings.create', compact('vehicles', 'customers', 'vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plate_no' => 'required|exists:vehicles,plate_no',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'special_requests' => 'nullable|string|max:500',
            'booking_status' => 'required|in:pending,confirmed,active,completed,cancelled',
        ]);

        $vehicle = Vehicle::findOrFail($request->plate_no);
        
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
                'customer_id' => $request->customer_id,
                'plate_no' => $request->plate_no,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'total_price' => $totalPrice,
                'voucher_id' => $request->voucher_id,
                'special_requests' => $request->special_requests,
                'booking_status' => $request->booking_status,
            ]);

            // Update vehicle status if not cancelled
            if ($request->booking_status !== 'cancelled') {
                $vehicle->update(['availability_status' => 'booked']);
            }

            DB::commit();
            
            return redirect()->route('staff.bookings.show', $booking->booking_id)
                           ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show($id)
    {
        $booking = Booking::with(['vehicle', 'customer', 'voucher', 'payments'])
                         ->findOrFail($id);
        
        return view('staff.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $vehicles = Vehicle::all();
        $customers = Customer::where('status', 'active')->get();
        $vouchers = Voucher::where('voucherStatus', 'active')->get();
        
        return view('staff.bookings.edit', compact('booking', 'vehicles', 'customers', 'vouchers'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plate_no' => 'required|exists:vehicles,plate_no',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'required',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
            'special_requests' => 'nullable|string|max:500',
            'booking_status' => 'required|in:pending,confirmed,active,completed,cancelled',
        ]);

        DB::beginTransaction();
        try {
            // If vehicle changed, update old vehicle status
            if ($booking->plate_no !== $request->plate_no) {
                $oldVehicle = Vehicle::find($booking->plate_no);
                if ($oldVehicle && $booking->booking_status !== 'cancelled' && $booking->booking_status !== 'completed') {
                    $oldVehicle->update(['availability_status' => 'available']);
                }
            }
            added commitsna

            $vehicle = Vehicle::findOrFail($request->plate_no);
            
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
                    
                    // Update voucher usage if changed
                    if ($booking->voucher_id !== $request->voucher_id) {
                        if ($booking->voucher_id) {
                            $oldVoucher = Voucher::find($booking->voucher_id);
                            if ($oldVoucher && $oldVoucher->used_count > 0) {
                                $oldVoucher->decrement('used_count');
                            }
                        }
                        $voucher->increment('used_count');
                    }
                }
            } elseif ($booking->voucher_id) {
                // Remove voucher
                $oldVoucher = Voucher::find($booking->voucher_id);
                if ($oldVoucher && $oldVoucher->used_count > 0) {
                    $oldVoucher->decrement('used_count');
                }
            }

            $booking->update([
                'customer_id' => $request->customer_id,
                'plate_no' => $request->plate_no,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'total_price' => $totalPrice,
                'voucher_id' => $request->voucher_id,
                'special_requests' => $request->special_requests,
                'booking_status' => $request->booking_status,
            ]);

            // Update vehicle status
            if (in_array($request->booking_status, ['pending', 'confirmed', 'active'])) {
                $vehicle->update(['availability_status' => 'booked']);
            } else {
                $vehicle->update(['availability_status' => 'available']);
            }

            DB::commit();
            
            return redirect()->route('staff.bookings.show', $booking->booking_id)
                           ->with('success', 'Booking updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update booking: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Make vehicle available again
            $vehicle = Vehicle::find($booking->plate_no);
            if ($vehicle) {
                $vehicle->update(['availability_status' => 'available']);
            }
            
            // Decrement voucher usage
            if ($booking->voucher_id) {
                $voucher = Voucher::find($booking->voucher_id);
                if ($voucher && $voucher->used_count > 0) {
                    $voucher->decrement('used_count');
                }
            }
            
            $booking->delete();
            
            DB::commit();
            
            return redirect()->route('staff.bookings.index')
                           ->with('success', 'Booking deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->booking_status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be approved.');
        }
        
        $booking->update(['booking_status' => 'confirmed']);
        
        return back()->with('success', 'Booking approved successfully!');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!in_array($booking->booking_status, ['pending', 'confirmed', 'active'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }
        
        DB::beginTransaction();
        try {
            $booking->update(['booking_status' => 'cancelled']);
            
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

    public function markActive($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->booking_status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked as active.');
        }
        
        $booking->update(['booking_status' => 'active']);
        
        return back()->with('success', 'Booking marked as active!');
    }

    public function markCompleted($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->booking_status !== 'active') {
            return back()->with('error', 'Only active bookings can be marked as completed.');
        }
        
        DB::beginTransaction();
        try {
            $booking->update(['booking_status' => 'completed']);
            
            // Make vehicle available again
            $vehicle = Vehicle::find($booking->plate_no);
            if ($vehicle) {
                $vehicle->update(['availability_status' => 'available']);
            }
            
            DB::commit();
            
            return back()->with('success', 'Booking marked as completed!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark booking as completed: ' . $e->getMessage());
        }
    }

    public function extend(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'new_return_date' => 'required|date|after:pickup_date',
            'new_return_time' => 'required',
        ]);
        
        if ($booking->booking_status !== 'active') {
            return back()->with('error', 'Only active bookings can be extended.');
        }
        
        DB::beginTransaction();
        try {
            // Calculate additional hours and price
            $oldReturnDateTime = Carbon::parse($booking->return_date . ' ' . $booking->return_time);
            $newReturnDateTime = Carbon::parse($request->new_return_date . ' ' . $request->new_return_time);
            
            if ($newReturnDateTime <= $oldReturnDateTime) {
                return back()->with('error', 'New return date/time must be after current return date/time.');
            }
            
            $additionalHours = ceil($oldReturnDateTime->diffInHours($newReturnDateTime));
            $hourlyRate = $booking->vehicle->price_perHour;
            $additionalPrice = $hourlyRate * $additionalHours;
            
            // Update booking
            $booking->update([
                'return_date' => $request->new_return_date,
                'return_time' => $request->new_return_time,
                'total_price' => $booking->total_price + $additionalPrice,
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Booking extended successfully! Additional charge: RM' . number_format($additionalPrice, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to extend booking: ' . $e->getMessage());
        }
    }

    public function lateReturns()
    {
        $lateBookings = Booking::where('booking_status', 'active')
            ->where('return_date', '<', Carbon::today())
            ->orWhere(function ($query) {
                $query->where('booking_status', 'active')
                      ->where('return_date', '=', Carbon::today())
                      ->where('return_time', '<', Carbon::now()->format('H:i:s'));
            })
            ->with(['vehicle', 'customer'])
            ->orderBy('return_date', 'asc')
            ->get();
        
        return view('staff.bookings.late-returns', compact('lateBookings'));
    }

    public function export(Request $request)
    {
        $query = Booking::with(['vehicle', 'customer']);
        
        // Apply filters
        if ($request->has('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }
        
        if ($request->has('status')) {
            $query->where('booking_status', $request->status);
        }
        
        $booking = $query->orderBy('created_at', 'desc')->get();
        
        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('staff.bookings.export-pdf', compact('bookings'));
            return $pdf->download('bookings-report-' . date('Y-m-d') . '.pdf');
        }
        
        // Default to CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings-report-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Booking ID',
                'Customer Name',
                'Vehicle',
                'Pickup Date',
                'Return Date',
                'Total Price',
                'Status',
                'Created At'
            ]);
            
            // Data rows
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_id,
                    $booking->customer->name ?? 'N/A',
                    $booking->vehicle->model . ' (' . $booking->vehicle->plate_no . ')',
                    $booking->pickup_date . ' ' . $booking->pickup_time,
                    $booking->return_date . ' ' . $booking->return_time,
                    'RM' . number_format($booking->total_price, 2),
                    ucfirst($booking->booking_status),
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}