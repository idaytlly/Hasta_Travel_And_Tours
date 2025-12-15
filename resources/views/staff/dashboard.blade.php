@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Staff Dashboard</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('staff.cars') }}" class="btn btn-primary w-100 p-4">
                Manage Cars
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('staff.bookings') }}" class="btn btn-success w-100 p-4">
                View Bookings
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('home') }}" class="btn btn-secondary w-100 p-4">
                Public Home
            </a>
        </div>
    </div>
</div>
@endsection
