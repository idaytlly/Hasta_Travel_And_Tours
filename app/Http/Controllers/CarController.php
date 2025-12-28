<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    // Public car listing with filters
    public function index(Request $request)
    {
        $query = Car::query();

        // Filter by availability (show all for now, or uncomment to show only available)
        // $query->where('is_available', true);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plateNo', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%");
            });
        }

        // Filter by carType if provided
        if ($request->has('type') && $request->type != '' && $request->type != 'all') {
            $query->where('carType', $request->type);
        }

        // Filter by brand if provided
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        // Get cars with pagination
        $cars = $query->latest()->paginate(9);

        // Get unique brands for filter buttons
        $brands = Car::select('brand')->distinct()->pluck('brand');

        return view('cars.index', compact('cars', 'brands'));
    }

    // Show single car details
    public function show($id)
    {
        $car = Car::findOrFail($id);
        return view('cars.show', compact('car'));
    }

    // Staff car management
    public function staffIndex()
    {
        $cars = Car::all();
        return view('staff.cars.index', compact('cars'));
    }

    public function create() 
    { 
        return view('staff.cars.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'plateNo' => 'required|string|max:255|unique:cars',
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'carType' => 'nullable|string',
            'transmission' => 'nullable|string',
            'daily_rate' => 'required|numeric',
            'image' => 'nullable|image|max:2048', // if uploading files
            'is_available' => 'boolean',
        ]);

        $data = $request->all();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('car_images', 'public');
            $data['image'] = $imagePath;
        }

        Car::create($data);
        return redirect()->route('staff.cars')->with('success', 'Car added successfully!');
    }

    public function edit($id)
    {
        $car = Car::findOrFail($id);
        return view('staff.cars.edit', compact('car'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'plateNo' => 'required|string|max:255|unique:cars,plateNo,' . $id,
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'carType' => 'nullable|string',
            'transmission' => 'nullable|string',
            'daily_rate' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        $car = Car::findOrFail($id);
        $data = $request->all();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('car_images', 'public');
            $data['image'] = $imagePath;
        }

        $car->update($data);
        return redirect()->route('staff.cars')->with('success', 'Car updated successfully!');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();
        return redirect()->route('staff.cars')->with('success', 'Car deleted successfully!');
    }
}