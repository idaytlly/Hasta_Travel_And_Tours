<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car; // Ensure this is correct for your Car Model location

class HomeController extends Controller
{
    /**
     * Display the public welcome/home page with featured cars.
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch up to 6 available cars from the 'cars' database table.
        // Assumes 'is_available' column exists and 'Car' model is set up.
        $cars = Car::where('is_available', true)
                   ->latest()
                   ->take(6)
                   ->get();
        
        // Pass the fetched car data to the 'welcome' view
        return view('welcome', compact('cars'));
    }

    /**
     * Display the authenticated customer dashboard.
     * * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Loads the resources/views/dashboard.blade.php view
        return view('dashboard');
    }
}