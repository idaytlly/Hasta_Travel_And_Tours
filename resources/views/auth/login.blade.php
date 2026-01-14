<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hasta Travel And Tours</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f3c3a3ff 0%, #FF9792 50%, #FF5850 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 24px;
            padding: 36px 28px;
            width: 100%;
            max-width: 360px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-text {
            background: #ff5722;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 24px;
            font-weight: bold;
            display: inline-block;
            letter-spacing: 2px;
        }

        .profile-icon {
            width: 120px;
            height: 120px;
            margin: 32px auto;
            background: white;
            border-radius: 50%;
            border: 5px solid #000;
            position: relative;
            overflow: hidden;
        }

        .profile-icon::before {
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
            background: #000;
            border-radius: 50%;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .profile-icon::after {
            content: '';
            position: absolute;
            width: 85px;
            height: 100px;
            background: #000;
            border-radius: 50%;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 8px;
        }

        .welcome-text h1 {
            font-size: 26px;
            font-weight: 600;
            color: #000;
            margin-bottom: 8px;
        }

        .welcome-text p {
            font-size: 13px;
            color: #666;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #000;
            margin-bottom: 8px;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 13px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff5722;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            font-size: 13px;
            color: #333;
            cursor: pointer;
        }

        .forgot-password {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #1d4ed8;
        }

        .login-button {
            width: 100%;
            background: linear-gradient(135deg, #e53935 0%, #d32f2f 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 30px;
            margin-bottom: 24px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(229, 57, 53, 0.3);        
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(229, 57, 53, 0.4);
        }

        .signup-link {
            text-align: center;
            font-size: 13px;
            color: #333;
        }

        .signup-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            color: #1d4ed8;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        @media (max-width: 400px) {
            .login-container {
                padding: 28px 20px;
                max-width: 320px;
            }

            .welcome-text h1 {
                font-size: 22px;
            }

            .profile-icon {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" height="40">
        </div>

        <div class="profile-icon"></div>

        <div class="welcome-text">
            <h1>Welcome Back!</h1>
            <p>Please enter your details.</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your Email" 
                    value="{{ old('email') }}"
                    required 
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter Password" 
                    required
                >
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="login-button">Log In</button>

            <div class="signup-link">
                Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
            </div>
        </form>
    </div>
</body>
</html>