<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Hasta Car Rental')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('guest.home') }}">HASTA</a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-outline-dark me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-dark">Register</a>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-light pt-5 mt-5">
        <div class="container">
            <div class="row">

                <div class="col-md-3">
                    <h6 class="fw-bold">DON'T BE A STRANGER</h6>
                    <ul class="list-unstyled">
                        <li>About</li>
                        <li>News</li>
                        <li>Blog</li>
                        <li>Careers</li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold">SERVICES</h6>
                    <ul class="list-unstyled">
                        <li>Car Rental</li>
                        <li>Limousine</li>
                        <li>Subscription</li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold">TOP CITIES</h6>
                    <ul class="list-unstyled">
                        <li>Kuala Lumpur</li>
                        <li>Johor</li>
                        <li>Penang</li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold">NEED A HELPING HAND?</h6>
                    <p class="mb-1">📞 +60 3 6419 5001</p>
                    <p class="mb-1">✉ support@wahdah.my</p>
                </div>

            </div>

            <hr>
            <p class="text-center small mb-0">
                © 2016 - {{ date('Y') }} HASTA. All Rights Reserved.
            </p>
        </div>
    </footer>

</body>
</html>
