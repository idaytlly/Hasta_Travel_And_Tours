<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StaffUserController extends Controller
{
    public function index()
    {
        $staffUsers = User::where('role', 'staff')->get();
        return view('staff.index', compact('staffUsers'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        User::create($validated);
        return redirect()->route('staff.index')->with('success', 'Staff user created');
    }

    public function edit($id)
    {
        $staffUser = User::findOrFail($id);
        return view('staff.edit', compact('staffUser'));
    }

    public function update(Request $request, $id)
    {
        $staffUser = User::findOrFail($id);
        $staffUser->update($request->validated());
        return redirect()->route('staff.index')->with('success', 'Staff user updated');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('staff.index')->with('success', 'Staff user deleted');
    }
}