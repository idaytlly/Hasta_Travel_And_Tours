<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function pickup(Request $request, $bookingId)
    {
        Inspection::create([
            'booking_id' => $bookingId,
            'type' => 'pickup',
            'status' => 'completed',
            'remarks' => $request->remarks
        ]);

        return back();
    }

    public function return(Request $request, $bookingId)
    {
        Inspection::create([
            'booking_id' => $bookingId,
            'type' => 'return',
            'status' => 'completed',
            'remarks' => $request->remarks
        ]);

        return back();
    }
}
