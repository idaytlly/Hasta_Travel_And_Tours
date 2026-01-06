<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    $path = $request->file('payment_proof')
                   ->store('payment_proofs', 'public');

    Payment::create([
        'payment_proof' => $path,
        'amount' => 340,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Payment uploaded');
}

}
