<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Hasta Travel And Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <style>
        :root {
            --primary: #e53935;
            --primary-dark: #c62828;
            --primary-light: #ffebee;
            --dark: #1a1a2e;
            --text-dark: #333;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --white: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
            padding-top: 110px;
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
            background-color: #e6623aff;
            border-color: #0000;
            color: #1f2937;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .btn-register {
            color: #c62828;
            background-color: #ffffffff;
            border: none;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-register:hover {
            background-color: #ffebee;
            color:#c62828;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        main {
            flex: 1 0 auto;
            /* give room at the bottom so page content doesn't sit flush against the footer */
            padding-bottom: 100px;
        }


        .footer-hasta {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--dark);
            color: rgba(255,255,255,0.9);
            padding: 10px 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            box-shadow: 0 -8px 24px rgba(0,0,0,0.12);
            z-index: 999;

            /* 👇 default hidden */
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.3s ease;
        }

        /* 👇 only show when at bottom */
        .footer-hasta.show {
            transform: translateY(0);
            opacity: 1;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .footer-logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--white);
        }

        .footer-copyright {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
        }

        .footer-links-inline {
            display: flex;
            gap: 25px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .footer-links-inline a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .footer-links-inline a:hover {
            color: var(--primary);
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .footer-social a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

         /* RESPONSIVE */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-image {
                justify-content: center;
                padding-right: 0;
                margin-top: 40px;
            }
            
            .hero-image img {
                max-width: 320px;
                transform: scale(1);
            }
            
            .hero-stats {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .search-box {
                padding: 20px;
            }

            .footer-hasta {
                padding: 12px 0;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .footer-left {
                flex-direction: column;
                gap: 8px;
            }

            .footer-links-inline {
                gap: 15px;
                font-size: 0.8rem;
            }

            .footer-social a {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
            }
        }

    </style>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        {{-- Logo --}}
        <div class="navbar-brand">
            <img 
                src="{{ asset('images/logo_hasta.jpeg') }}" 
                alt="Hasta Travel & Tours" 
                height="35"
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
    <footer class="footer-hasta">
        <div class="container">
            <div class="footer-content">
                <!-- Social Links on LEFT -->
                <div class="footer-social">
                    <a href="http://wasap.my/601110900700/nakkeretasewa" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="http://t.me/infoHastaCarRentalUTM" title="Telegram">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a href="https://www.instagram.com/hastatraveltours?igsh=MXR0ZjYyM3c3Znpsdg==" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="http://youtube.com/watch?v=41Vedbjxn_s" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>

                <!-- Copyright on RIGHT -->
                <div class="footer-copyright">
                    © 2026 Hasta Travel & Tours. All Rights Reserved.
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            const footer = document.querySelector('.footer-hasta');
            if (!footer) return;

            const THRESHOLD = 2; // tolerance px

            function isAtBottom() {
                return window.innerHeight + window.scrollY >= document.body.offsetHeight - THRESHOLD;
            }

            function toggleFooter() {
                if (isAtBottom()) {
                    footer.classList.add('show');
                } else {
                    footer.classList.remove('show');
                }
            }

            window.addEventListener('load', toggleFooter);
            window.addEventListener('scroll', toggleFooter);
            window.addEventListener('resize', toggleFooter);
        })();
    </script>

</body>
</html>