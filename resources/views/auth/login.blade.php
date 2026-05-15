<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Web Engineering</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-dark: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --input-bg: rgba(255, 255, 255, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image: url('{{ asset('images/login_background.png') }}'); /* Fallback or dynamically loaded */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: var(--text-main);
        }

        /* Animated Background Elements */
        .bg-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(60px);
            animation: pulse 10s infinite alternate;
        }

        .glow-1 { top: -10%; left: -10%; }
        .glow-2 { bottom: -10%; right: -10%; animation-delay: -5s; }

        @keyframes pulse {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.2) translate(50px, 50px); }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            z-index: 10;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-box {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), #a855f7);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            color: var(--text-muted);
            width: 18px;
            height: 18px;
            pointer-events: none;
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.05);
        }

        .error-message {
            display: block;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .form-control:focus + i, 
        .form-group:focus-within label {
            color: var(--primary);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            font-size: 0.875rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--text-muted);
        }

        .remember-me input {
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .forgot-password:hover {
            opacity: 0.8;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.875rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.3);
            filter: brightness(1.1);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        /* Mobile Adjustments */
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-glow glow-1"></div>
    <div class="bg-glow glow-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="header">
                <div class="logo-box">
                    <i data-lucide="shield-check" color="white" size="32"></i>
                </div>
                <h1>Welcome Back</h1>
                <p>Please enter your details to sign in</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i data-lucide="mail"></i>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i data-lucide="lock"></i>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">
                    <span>Sign In</span>
                    <i data-lucide="arrow-right" size="18"></i>
                </button>
            </form>

            <div class="footer-text">
                Don't have an account? <a href="#">Contact Admin</a>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Background Image Support (using the generated one)
        // In a real Laravel app, we'd use asset() or move the image to public/
        // For demonstration, we'll assume it's set in CSS or dynamically
        document.body.style.backgroundImage = "url('{{ asset('images/login_background.png') }}')";
    </script>
</body>
</html>
