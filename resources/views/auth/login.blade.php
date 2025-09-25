<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Aplikasi Toko</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            :root {
                --primary-color: #6366f1;
                --secondary-color: #8b5cf6;
                --sidebar-bg: #ffffff;
                --sidebar-border: #e5e7eb;
                --sidebar-hover: #f3f4f6;
                --sidebar-active: #eef2ff;
                --text-primary: #1f2937;
                --text-secondary: #6b7280;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
                --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            }

            * {
                box-sizing: border-box;
            }

            body {
                background-color: #f9fafb;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                margin: 0;
                padding: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            /* Subtle background pattern */
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image:
                    radial-gradient(circle at 1px 1px, rgba(99, 102, 241, 0.1) 1px, transparent 0);
                background-size: 20px 20px;
                pointer-events: none;
                z-index: -1;
            }

            /* Floating shapes for visual interest */
            .floating-element {
                position: fixed;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                opacity: 0.1;
                animation: float 6s ease-in-out infinite;
                pointer-events: none;
                z-index: -1;
            }

            .floating-element:nth-child(1) {
                width: 80px;
                height: 80px;
                top: 10%;
                left: 10%;
                animation-delay: 0s;
            }

            .floating-element:nth-child(2) {
                width: 120px;
                height: 120px;
                top: 70%;
                right: 10%;
                animation-delay: -2s;
            }

            .floating-element:nth-child(3) {
                width: 60px;
                height: 60px;
                top: 30%;
                right: 30%;
                animation-delay: -4s;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-20px) rotate(180deg);
                }
            }

            /* Login container */
            .login-container {
                background: var(--sidebar-bg);
                border: 1px solid var(--sidebar-border);
                border-radius: 16px;
                box-shadow: var(--shadow-xl);
                padding: 3rem;
                width: 100%;
                max-width: 450px;
                position: relative;
                animation: slideUp 0.6s ease-out;
            }

            @keyframes slideUp {
                from {
                    transform: translateY(30px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* Header */
            .login-header {
                text-align: center;
                margin-bottom: 2.5rem;
            }

            .app-logo {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                box-shadow: var(--shadow-md);
            }

            .app-logo i {
                color: white;
                font-size: 1.75rem;
            }

            .login-title {
                font-size: 1.875rem;
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 0.5rem;
            }

            .login-subtitle {
                color: var(--text-secondary);
                font-size: 1rem;
                margin: 0;
            }

            /* Form styles */
            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-label {
                color: var(--text-primary);
                font-weight: 600;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
            }

            .form-label i {
                margin-right: 0.5rem;
                color: var(--primary-color);
                width: 16px;
            }

            .form-control {
                background: var(--sidebar-bg);
                border: 2px solid var(--sidebar-border);
                border-radius: 8px;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                transition: all 0.2s ease;
                color: var(--text-primary);
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                outline: none;
                background: white;
            }

            .form-control::placeholder {
                color: var(--text-secondary);
                opacity: 0.6;
            }

            /* Button */
            .btn-login {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                border: none;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                font-size: 1rem;
                padding: 0.875rem 1.5rem;
                width: 100%;
                transition: all 0.2s ease;
                box-shadow: var(--shadow-sm);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-top: 1rem;
            }

            .btn-login:hover {
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
                background: linear-gradient(135deg, #5b5fdb, #7c3aed);
            }

            .btn-login:active {
                transform: translateY(0);
            }

            .btn-login i {
                margin-right: 0.5rem;
            }

            /* Alert */
            .alert {
                background: #fef2f2;
                border: 1px solid #fecaca;
                border-radius: 8px;
                color: #dc2626;
                padding: 1rem;
                margin-bottom: 1.5rem;
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                animation: shake 0.4s ease-in-out;
            }

            .alert i {
                margin-right: 0.5rem;
                color: #ef4444;
            }

            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                25% {
                    transform: translateX(-4px);
                }

                75% {
                    transform: translateX(4px);
                }
            }

            /* Loading state */
            .btn-login.loading {
                pointer-events: none;
                opacity: 0.8;
            }

            .btn-login.loading .spinner {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            /* Footer */
            .login-footer {
                text-align: center;
                margin-top: 2rem;
                color: var(--text-secondary);
                font-size: 0.875rem;
            }

            /* Responsive */
            @media (max-width: 576px) {
                .login-container {
                    margin: 1rem;
                    padding: 2rem;
                }

                .login-title {
                    font-size: 1.5rem;
                }
            }

            /* Focus ring for accessibility */
            .form-control:focus-visible,
            .btn-login:focus-visible {
                outline: 2px solid var(--primary-color);
                outline-offset: 2px;
            }

            /* Input validation states */
            .form-control.is-invalid {
                border-color: #ef4444;
            }

            .form-control.is-valid {
                border-color: #10b981;
            }

            /* Smooth transitions for all interactive elements */
            * {
                transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            }
        </style>
    </head>

    <body>
        <!-- Floating elements for visual interest -->
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>

        <div class="login-container">
            <!-- Header -->
            <div class="login-header">
                <div class="app-logo">
                    <i class="bi bi-shop"></i>
                </div>
                <h1 class="login-title">Selamat Datang</h1>
                <p class="login-subtitle">Silakan masuk ke Aplikasi Toko</p>
            </div>

            <!-- Error Alert -->
            <div class="alert" style="display: none;" id="errorAlert">
                <i class="bi bi-exclamation-triangle"></i>
                <span id="errorMessage">Email atau password salah!</span>
            </div>

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i>
                        Email
                    </label>
                    <input type="email" name="email" class="form-control" id="email"
                        placeholder="nama@contoh.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i>
                        Password
                    </label>
                    <input type="password" name="password" class="form-control" id="password"
                        placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-login" id="loginButton">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk ke Aplikasi
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <p class="mb-0">© 2024 Aplikasi Toko. Semua hak dilindungi.</p>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Form handling
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const button = document.getElementById('loginButton');
                const icon = button.querySelector('i');

                // Show loading state
                button.classList.add('loading');
                icon.className = 'bi bi-arrow-clockwise spinner';
                button.innerHTML = '<i class="bi bi-arrow-clockwise spinner"></i>Memproses...';
                button.disabled = true;
            });

            // Demo error handling (replace with actual server-side logic)
            function showError(message) {
                const alert = document.getElementById('errorAlert');
                const messageSpan = document.getElementById('errorMessage');
                messageSpan.textContent = message;
                alert.style.display = 'flex';

                // Auto hide after 5 seconds
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 5000);
            }

            // Input validation feedback
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });

                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid', 'is-valid');
                });
            });

            // Hide error alert when user starts typing
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    document.getElementById('errorAlert').style.display = 'none';
                });
            });

            // Demo: Uncomment to test error display
            // setTimeout(() => showError('Email atau password salah!'), 2000);
        </script>
    </body>

</html>
