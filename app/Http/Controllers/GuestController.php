<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class GuestController extends Controller
{
    public function home()
    {
        $vehicles = Vehicle::inRandomOrder()
                ->limit(3)
                ->get();

        return view('guest.home', compact('vehicles')); 
    }
}

?>