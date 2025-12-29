<?php

namespace App\Http\Controllers;

use App\Models\Car;
<<<<<<< Updated upstream
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarController extends Controller
{
    /**
     * Display listing of cars
     */
    public function index(Request $request): View
    {
        // Start with only available cars
        $query = Car::available();
=======
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    // Public car listing with filters
    //public function index(Request $request)
    /*{
        $query = Car::query();
>>>>>>> Stashed changes

        // Filter by brand if selected
        if ($request->filled('brand')) {
            $query->byBrand($request->brand);
        }

        // Filter by category/type if selected
        if ($request->filled('category')) {
            $query->where('category', $request->category); // Standard where check
        }

        $cars = $query->get();

<<<<<<< Updated upstream
        return view('cars.index', compact('cars'));
    }

    /**
     * Show car details
     */
    public function show($id): View
=======
        // Get cars with pagination
        $cars = $query->latest()->paginate(9);

        // Get unique brands for filter buttons
        $brands = Car::select('brand')->distinct()->pluck('brand');

        return view('cars.index', compact('cars', 'brands'));
    }*/
    // Public car listing with filters
public function index(Request $request)
{
    $query = Car::query();

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

    // Filter by carType
    if ($request->has('type') && $request->type != '' && $request->type != 'all') {
        $query->where('carType', $request->type);
    }

    // Filter by brand
    if ($request->has('brand') && $request->brand != '') {
        $query->where('brand', 'like', '%' . $request->brand . '%');
    }

    // Pagination
    $cars = $query->latest()->paginate(9);

    // Brands for filter
    $brands = Car::select('brand')->distinct()->pluck('brand');

    // ✅ CUSTOMER VIEW
    if (Auth::check() && in_array(Auth::user()->usertype, ['customer', 'user'])) {
        return view('cars.customer.index', compact('cars', 'brands'));
    }

    // ✅ GUEST VIEW
    return view('cars.guest.index', compact('cars', 'brands'));
}


    // Show single car details
    public function show($id)
>>>>>>> Stashed changes
    {
        $car = Car::findOrFail($id);
        
        // Get 3 other cars of the same brand, excluding current one
        $otherCars = Car::where('brand', $car->brand)
                        ->where('id', '!=', $id)
                        ->limit(3)
                        ->get();

        return view('cars.show', compact('car', 'otherCars'));
    }
}