<?php

namespace App\Http\Controllers;

use App\Models\Car;
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

        // Filter by brand if selected
        if ($request->filled('brand')) {
            $query->byBrand($request->brand);
        }

        // Filter by category/type if selected
        if ($request->filled('category')) {
            $query->where('category', $request->category); // Standard where check
        }

        $cars = $query->get();

        return view('cars.index', compact('cars'));
    }

    /**
     * Show car details
     */
    public function show($id): View
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