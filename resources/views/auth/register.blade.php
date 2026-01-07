<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Hasta Travel And Tours</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f3c3a3ff 0%, #FF9792 50%, #FF5850 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
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
            border: 6px solid #000;
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
            font-size: 32px;
            font-weight: 600;
            color: #000;
            margin-bottom: 8px;
        }

        .welcome-text p {
            font-size: 14px;
            color: #666;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #000;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff5722;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 24px;
        }

        .terms-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .terms-checkbox label {
            font-size: 14px;
            color: #333;
            cursor: pointer;
            line-height: 1.5;
        }

        .terms-checkbox label a {
            color: #2563eb;
            text-decoration: none;
        }

        .terms-checkbox label a:hover {
            text-decoration: underline;
        }

        .register-button {
            width: 100%;
            padding: 16px;
            background: #EC592B;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-bottom: 24px;
        }

        .register-button:hover {
            background: #f4511e;
        }

        .login-link {
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .login-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: #1d4ed8;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 32px 24px;
            }

            .welcome-text h1 {
                font-size: 28px;
            }

            .profile-icon {
                width: 100px;
                height: 100px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" height="40">
        </div>

        <div class="profile-icon"></div>

        <div class="welcome-text">
            <h1>Create Account!</h1>
            <p>Please fill in your details to register.</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
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

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password_confirmation" 
                    placeholder="Confirm Password" 
                    required
                >
            </div>

            <div class="terms-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="register-button">Sign Up</button>

            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Log In</a>
            </div>
        </form>
    </div>
</body>
</html>