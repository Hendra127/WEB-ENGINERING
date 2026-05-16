<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Web Engineering</title>

    <!-- Google Fonts -->
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
            background-image: url("{{ asset('images/login_background.png') }}");
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

        .glow-1 {
            top: -10%;
            left: -10%;
        }

        .glow-2 {
            bottom: -10%;
            right: -10%;
            animation-delay: -5s;
        }

        @keyframes pulse {
            0% {
                transform: scale(1) translate(0, 0);
            }
            100% {
                transform: scale(1.2) translate(50px, 50px);
            }
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
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i,
        .input-wrapper svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        /* Fix Autofill background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: white;
            -webkit-box-shadow: 0 0 0px 1000px #1e293b inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            color: white;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }

        .form-control.is-invalid {
            border-color: #f87171;
        }

        .error-message {
            display: block;
            margin-top: 0.5rem;
            color: #f87171;
            font-size: 0.85rem;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            border: none;
            border-radius: 12px;
            padding: 0.9rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
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
                    <img src="{{ asset('images/logo_nustech.jpg') }}" alt="logo_nustech" />
                </div>

                <h1>WELCOME TO ENGINEERING NUSTECH</h1>
                <p>Please enter your details to sign in</p>

            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>

                    <div class="input-wrapper">
                        <i data-lucide="mail"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="name@company.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="input-wrapper">
                        <i data-lucide="lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    Sign In
                </button>

            </form>

            <div class="footer-text">
                Don't have an account?
                <a href="#">Contact Admin</a>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>