<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hasta Travel & Tours</title>

    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f5f5f5;
        }
        .auth-card {
            max-width: 450px;
            margin: 60px auto;
        }
    </style>
</head>
<body>

<!-- Navbar (UNCHANGED) -->
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('car_images/logo.png') }}" style="max-height:60px">
        </a>
    </div>
</nav>

<!-- Login Card -->
<div class="card auth-card shadow">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Login</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>

            <!-- ✅ ONLY ADDITION (as requested) -->
            <div class="text-center mt-3">
                <span class="text-muted">Don’t have an account yet?</span>
                <a href="{{ route('register') }}" class="fw-semibold">
                    Register here
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<div class="text-center text-muted mt-4">
    &copy; {{ date('Y') }} Hasta Travel & Tours
</div>

</body>
</html>
