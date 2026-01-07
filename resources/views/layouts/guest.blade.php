<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Home') | Hasta Travel And Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <style>
        body {
            color: #1f2937;
        }

        .navbar-custom {
            background-color: #CB3737;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
        }

        .navbar-brand img {
            filter: none;
        }

        .btn-login {
            color: white;
            background-color: #962E0F;
            border: 1px solid #0000;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background-color: #f9fafb;
            border-color: #0000;
            color: #1f2937;
        }

        .btn-register {
            color: white;
            background-color: #F0785B;
            border: none;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-register:hover {
            background-color: #e06849;
            color: white;
        }

        footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        footer h6 {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        footer ul li {
            color: #4b5563;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
        }

        footer ul li:hover {
            color: #F0785B;
        }

        footer p {
            color: #6b7280;
            font-size: 0.9rem;
        }

        footer .text-center {
            color: #9ca3af;
            padding: 1.5rem 0;
        }
    </style>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            {{-- Logo --}}
            <div class="navbar-brand">
                <img 
                    src="{{ asset('images/logo_hasta.jpeg') }}" 
                    alt="Hasta Travel & Tours" 
                    height="40"
                >
            </div>

            {{-- Login / Register --}}
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn btn-register">Register</a>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="pt-5 mt-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-3 mb-4">
                    <h6>ABOUT</h6>
                    <ul class="list-unstyled">
                        <li>About Us</li>
                        <li>News</li>
                        <li>Blog</li>
                        <li>Careers</li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h6>SERVICES</h6>
                    <ul class="list-unstyled">
                        <li>Car Rental</li>
                        <li>Travel</li>
                        <li>Subscription</li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h6>CONTACT</h6>
                    <p class="mb-2">+60 3 6419 5001</p>
                    <p class="mb-0">hastatraveltours@gmail.com</p>
                </div>
            </div>

            <hr style="border-color: #e5e7eb;">
            <p class="text-center small mb-0">
                © 2016 - {{ date('Y') }} Hasta Travel And Tours. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>