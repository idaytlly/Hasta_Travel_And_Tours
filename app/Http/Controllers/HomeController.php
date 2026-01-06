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

    // Dashboard page - redirect based on user type
    public function dashboard()
    {
        $user = auth()->user();
        
        if (in_array($user->usertype, ['staff', 'admin'])) {
            return redirect()->route('staff.dashboard');
        }
        
        return view('dashboard');
    }
}