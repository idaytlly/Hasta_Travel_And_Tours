<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    // Check if user is staff or admin
    private function checkAuth()
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized');
        }
    }

    // Dashboard
    public function dashboard()
    {
        $this->checkAuth();
        $cars = Car::all();
        return view('staff.dashboard', compact('cars'));
    }

    // ========== CAR MANAGEMENT ==========
    
    public function cars()
    {
        $this->checkAuth();
        $cars = Car::all();
        return view('staff.cars.index', compact('cars'));
    }

    public function createCar()
    {
        $this->checkAuth();
        return view('staff.cars.create');
    }

    public function storeCar(Request $request)
    {
        $this->checkAuth();
        
        Car::create([
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'license_plate' => $request->plate_number,
            'category' => $request->category,
            'transmission' => $request->transmission,
            'fuel_type' => $request->fuel_type,
            'seats' => $request->seats,
            'passengers' => $request->seats,
            'air_conditioner' => $request->air_conditioner,
            'daily_rate' => $request->daily_rate,
            'status' => $request->status ?? 'available',
            'is_available' => ($request->status ?? 'available') === 'available',
            'image' => $request->image,
            'description' => $request->description,
        ]);
        
        return redirect()->route('staff.cars.index')->with('success', 'Car added successfully');
    }

    public function editCar($id)
    {
        $this->checkAuth();
        $car = Car::findOrFail($id);
        return view('staff.cars.edit', compact('car'));
    }

    public function updateCar(Request $request, $id)
    {
        $this->checkAuth();
        $car = Car::findOrFail($id);
        
        $car->update([
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'license_plate' => $request->plate_number,
            'category' => $request->category,
            'transmission' => $request->transmission,
            'fuel_type' => $request->fuel_type,
            'seats' => $request->seats,
            'passengers' => $request->seats,
            'air_conditioner' => $request->air_conditioner,
            'daily_rate' => $request->daily_rate,
            'status' => $request->status,
            'is_available' => $request->status === 'available',
            'image' => $request->image,
            'description' => $request->description,
        ]);
        
        return redirect()->route('staff.cars.index')->with('success', 'Car updated successfully');
    }

    public function destroyCar($id)
    {
        $this->checkAuth();
        $car = Car::findOrFail($id);
        $car->delete();
        return redirect()->route('staff.cars.index')->with('success', 'Car deleted successfully');
    }

    // ========== BOOKING MANAGEMENT ==========
    
    public function bookings(Request $request)
    {
        $this->checkAuth();
        
        $query = Booking::with('car', 'user');
        
        // Status filter from tabs
        if ($request->status) {
            if ($request->status == 'cancelled') {
                $query = Booking::onlyTrashed()->with(['car', 'user']);
                $query->where('status', 'cancelled');
                
                $days = $request->days ?? 'all';
                if ($days !== 'all') {
                    $query->where('deleted_at', '>=', now()->subDays((int)$days));
                }
            } else {
                $query->where('status', $request->status);
            }
        } else {
            // "All" tab - show bookings from last 7 days only
            $query->where('created_at', '>=', now()->subDays(7));
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->get();
        
        return view('staff.bookings.index', compact('bookings'));
    }

    public function showBooking($id)
    {
        $this->checkAuth();
        $booking = Booking::with(['car', 'user', 'inspections'])->findOrFail($id);
        return view('staff.bookings.show', compact('booking'));
    }

    public function approveBooking($id)
    {
        $this->checkAuth();
        
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);
        
        return redirect()->route('staff.bookings.index')->with('success', 'Booking approved successfully!');
    }

    // ========== INSPECTIONS ==========
    
    public function pickupInspection(Request $request, $id)
    {
        $this->checkAuth();
        
        $booking = Booking::findOrFail($id);
        
        $data = $request->all();
        $data['booking_id'] = $id;
        $data['type'] = 'pickup';
        $data['inspector_id'] = auth()->id();
        $data['inspected_at'] = now();
        
        // Add vehicle info
        $data['vehicle_brand'] = $booking->car->brand;
        $data['vehicle_model'] = $booking->car->model;
        $data['vehicle_year'] = $booking->car->year;
        $data['license_plate'] = $booking->car->license_plate;
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('inspections', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }
        
        Inspection::create($data);
        
        return back()->with('success', 'Pickup inspection completed successfully');
    }

    public function returnInspection(Request $request, $id)
    {
        $this->checkAuth();
        
        $booking = Booking::findOrFail($id);
        
        $data = $request->all();
        $data['booking_id'] = $id;
        $data['type'] = 'return';
        $data['inspector_id'] = auth()->id();
        $data['inspected_at'] = now();
        
        // Add vehicle info
        $data['vehicle_brand'] = $booking->car->brand;
        $data['vehicle_model'] = $booking->car->model;
        $data['vehicle_year'] = $booking->car->year;
        $data['license_plate'] = $booking->car->license_plate;
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('inspections', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }
        
        Inspection::create($data);
        
        // Update booking status to completed
        $booking->update(['status' => 'completed']);
        
        // Update car status back to available
        $car = Car::find($booking->car_id);
        if ($car) {
            $car->update(['status' => 'available', 'is_available' => true]);
        }
        
        return back()->with('success', 'Return inspection completed successfully');
    }

    // ========== NOTIFICATIONS ==========
    
    public function notifications()
    {
        $this->checkAuth();
        return view('staff.notifications.index');
    }
}