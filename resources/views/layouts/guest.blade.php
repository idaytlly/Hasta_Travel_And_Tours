<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page | Hasta Travel And Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


    <style>
        .navbar-custom {
        background-color: #CB3737; /* merah gelap */
        padding-top: 1.0rem;       /* tambah ruang atas */
        padding-bottom: 1.0rem;    /* tambah ruang bawah */
        }

        .btn-login {
        color: white; /* text */
        background-color: #D3D3D3; /* kelabu cair */
        border: 1.2px solid #000; /* border hitam */
        font-weight: bold; /* bold text */
        }

        .btn-login:hover {
            background-color: #C0C0C0; /* kelabu lebih gelap on hover */
            color: #000;
        }

        .btn-register {
            color: white; /* text */
            background-color: #FF6347; /* tomato red */
            border: 1.2px solid #000; /* border hitam */
            font-weight: bold; /* bold text */
        }

        .btn-register:hover {
            background-color: #E5533D; /* tomato lebih gelap */
        }
    </style>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container">

        {{-- Logo --}}
        <div class="navbar-brand d-flex align-items-center">
            <img 
                src="{{ asset('images/logo_hasta.jpeg') }}" 
                alt="Hasta Travel & Tours" 
                height="40"
            >
        </div>

        {{-- Login / Register --}}
        <div class="ms-auto">
            <a href="{{ route('login') }}" class="btn btn-login me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
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
                    <h6 class="fw-bold">NEED A HELPING HAND?</h6>
                    <p class="mb-1">📞 +60 3 6419 5001</p>
                    <p class="mb-1">✉ hastatraveltours@gmail.com</p>
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
