<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hasta Car Rental</title>
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
            background: linear-gradient(135deg, #fe0f0bff 0%, #fd9898ff 50%, #ffecd2 100%);
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

        .logo-wrapper {
            margin-bottom: 10px;  
            text-align: center; 
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 8px;
        }

        .welcome-text h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 5px;
        }

        .welcome-text p {
            color: #666;
            font-size: 0.9rem;
            font-weight: 400;
        }
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #000;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input{
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: #e53935;
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.1);
        }


        .form-group input::placeholder {
            color: #aaa;
        }

        .auth-button {
            width: 100%;
            background: linear-gradient(135deg, #e53935 0%, #d32f2f 100%);
            color: white;
            padding: 18px 40px;
            border: none;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(229, 57, 53, 0.3);
        }

        .auth-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(229, 57, 53, 0.4);
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
        <div class="logo-wrapper">
            <img src="{{ asset('images/hasta logo.png') }}" alt="HASTA Logo" class="logo">
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
