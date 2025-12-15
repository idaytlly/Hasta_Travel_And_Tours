<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class HomeController extends Controller
{
    // Welcome page
    public function index()
    {
        // Fetch cars from database
        $cars = Car::all();

        // Return the welcome view exactly as it is, no layout changes
        return view('welcome', compact('cars'));
    }

    // Dashboard page
    public function dashboard()
    {
        $bookings = auth()->user()->bookings()->with('car')->get();
        return view('dashboard', compact('bookings'));
    }
}
