<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HASTA</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #ffb3ba 0%, #ffcccc 50%, #ffd9d9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: white;
            border-radius: 24px;
            padding: 50px 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo h1 {
            color: #ff6347;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            border: 3px solid #ff6347;
            padding: 8px 20px;
            display: inline-block;
            border-radius: 8px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            font-size: 30px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #000;
        }

        .welcome-text p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #000;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff6347;
        }

        .form-group input::placeholder {
            color: #999;
        }

        .auth-button {
            width: 100%;
            padding: 14px;
            background: #ff6347;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .auth-button:hover {
            background: #ff4500;
        }

        .login-text {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #333;
        }

        .login-text a {
            color: #4169e1;
            text-decoration: none;
            font-weight: 600;
        }

        .login-text a:hover {
            text-decoration: underline;
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
            .auth-container {
                padding: 40px 30px;
            }

            .welcome-text h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="logo">
        <h1>HASTA</h1>
    </div>

    <div class="welcome-text">
        <h2>Create Account</h2>
        <p>Please fill in your details</p>
    </div>

    @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" placeholder="Enter your full name"
                   value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email"
                   value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Identification Card (MyKad)</label>
            <input type="text" name="ic" maxlength="12"
                   placeholder="e.g. 010203040506"
                   value="{{ old('ic') }}" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone"
                   placeholder="e.g. 01XXXXXXXX"
                   value="{{ old('phone') }}" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password"
                   placeholder="Create a password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation"
                   placeholder="Confirm password" required>
        </div>

        <button type="submit" class="auth-button">Register</button>
    </form>

    <div class="login-text">
        Already have an account? <a href="{{ route('login') }}">Log In</a>
    </div>
</div>

</body>
</html>
