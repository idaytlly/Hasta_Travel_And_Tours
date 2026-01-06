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

        .login-container {
            background: white;
            border-radius: 30px;
            padding: 50px 45px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            /*display: flex;
            flex-direction: column;
            align-items: center;*/
        }
        .logo-user-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;  
            margin-bottom: 20px;  
        }

        .logo-wrapper {
            margin-bottom: 10px;   
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .user-icon {
            width: 300px;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit:cover;       
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

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #e53935;
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.1);
        }


        .form-group input::placeholder {
            color: #aaa;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
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
            accent-color: #e53935;
        }

        .remember-me label {
            font-size: 14px;
            color: #333;
            cursor: pointer;
            font-weight: 400;
        }

        .forgot-password {
            color: #1976d2;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #1565c0;
            text-decoration: underline;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(229, 57, 53, 0.4);
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 0 5px 20px rgba(229, 57, 53, 0.4);
        }

        .signup-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #333;
        }

        .signup-link a {
            color: #1976d2;
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #1565c0;
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

        /* RESPONSIVE */
        @media (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
            }

            .logo {
                font-size: 1.1rem;
                padding: 5px 16px;
                letter-spacing: 2px;
            }
            .user-icon {
                width: 120px;
                height: 120px;
            }

            .welcome-text h1 {
                font-size: 1.7rem;
            }

            .btn-login {
                padding: 16px 30px;
                font-size: 1rem;
            }
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .forgot-password {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo-user-wrapper">
            <div class="logo-wrapper">
                <img src="{{ asset('images/hasta logo.png') }}" alt="HASTA Logo" class="logo">
            </div>
            <div class="user-icon">
                <img src="{{ asset('images/user.png') }}" alt="User" class="user-avatar">        
            </div>
        </div>

        <div class="welcome-text">
            <h1>Welcome Back!</h1>
            <p>Please enter your details.</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
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

            <button type="submit" class="btn-login">Log In</button>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
        </div>
    </div>

</body>
</html>