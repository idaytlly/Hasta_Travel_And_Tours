<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Hasta Travel & Tours</title>

    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .auth-card {
            max-width: 450px;
            margin: 60px auto;
        }
        .navbar-logo {
            max-height: 60px;
        }
    </style>
</head>
<body>

<!-- 🔁 NAVBAR (SAME AS WELCOME & LOGIN) -->
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" class="navbar-logo" alt="Logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-light">
                                Logout
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- REGISTER CARD -->
<div class="card auth-card shadow">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Create Account</h3>

        <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
    </div>

    <div class="mb-3">
    <label>Identification Card (MyKad)</label>
    <input type="text" name="ic" class="form-control" maxlength="12" placeholder="e.g. 010203040506" required value="{{ old('ic') }}">
    </div>

    <div class="mb-3">
    <label>Phone Number</label>
    <input type="text" name="phone" class="form-control" placeholder="e.g. 01XXXXXXXX" required value="{{ old('phone') }}">
    </div>


    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Register</button>

            <!-- ✅ ONLY ADDITION -->
            <div class="text-center mt-3">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="fw-semibold">
                    Login here
                </a>
            </div>
        </form>
    </div>
</div>

<!-- FOOTER -->
<div class="text-center text-muted mt-4">
    &copy; {{ date('Y') }} Hasta Travel & Tours
</div>

</body>
</html>
