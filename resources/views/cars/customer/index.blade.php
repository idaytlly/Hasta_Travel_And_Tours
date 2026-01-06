@extends('layouts.guest')

@section('content')
<!-- VEHICLE LIST -->
<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Available Vehicles</h2>
            <p class="text-muted">Choose the perfect vehicle for your journey</p>
        </div>

        <div class="row g-4">

            @forelse($cars as $car)
                <div class="col-md-6 col-lg-4">
                    <div class="vehicle-card">

                        <img src="{{ asset('storage/' . $car->image) }}"
                             class="vehicle-img"
                             alt="{{ $car->model }}">

                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-0">{{ $car->brand }} {{ $car->model }}</h5>
                                <span class="badge-type">{{ strtoupper($car->type) }}</span>
                            </div>

                            <p class="text-muted mb-2">
                                <i class="fas fa-users me-1"></i> {{ $car->seats }} Seats
                                &nbsp; | &nbsp;
                                <i class="fas fa-cogs me-1"></i> {{ $car->transmission }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="price">RM {{ number_format($car->price_per_day, 2) }}/day</span>

                                <a href="{{ route('booking.create', $car->id) }}" class="btn btn-rent">
                                    <i class="fas fa-car me-1"></i> Rent Now
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No vehicles available at the moment.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>

@endsection
