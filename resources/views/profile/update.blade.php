<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Form Edit --}}
            @if(!session('success'))
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white text-center border-0 py-4">
                    <h3 class="fw-bold" style="color:#e53935;">Edit Profile</h3>
                    <p class="text-muted mb-0">Update your account information</p>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            {{-- Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                            </div>

                            {{-- Add other fields similarly --}}
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('home') }}" class="btn btn-outline-danger rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-login rounded-pill px-4">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Profile Card Appears After Submit --}}
            @if(session('success'))
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white text-center border-0 py-4">
                    <h3 class="fw-bold" style="color:#e53935;">Profile Updated</h3>
                    <p class="text-muted mb-0">Here is your current information</p>
                </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Name</strong>
                            <p>{{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email</strong>
                            <p>{{ auth()->user()->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Phone</strong>
                            <p>{{ auth()->user()->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>IC Number</strong>
                            <p>{{ auth()->user()->ic ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Address 1</strong>
                            <p>{{ auth()->user()->street ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Address 2</strong>
                            <p>{{ auth()->user()->street2 ?? '-' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Postcode</strong>
                            <p>{{ auth()->user()->postcode ?? '-' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>City</strong>
                            <p>{{ auth()->user()->city ?? '-' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>State</strong>
                            <p>{{ auth()->user()->state ?? '-' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>Country</strong>
                            <p>{{ auth()->user()->country ?? 'Malaysia' }}</p>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-login px-4 rounded-pill">
                            Edit Again
                        </a>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
