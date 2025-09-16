<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Aplikasi CRUD Barang</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #a0aad8 0%, #18151b 100%);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                min-height: 100vh;
                position: relative;
                overflow: hidden;
            }

            body::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="80" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="60" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                pointer-events: none;
            }

            .login-container {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                padding: 40px;
                width: 100%;
                max-width: 450px;
                position: relative;
                animation: slideIn 0.8s ease-out;
            }

            @keyframes slideIn {
                from {
                    transform: translateY(-50px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .login-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .login-header h2 {
                color: white;
                font-weight: 700;
                font-size: 2.2rem;
                margin-bottom: 10px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .login-header p {
                color: rgba(255, 255, 255, 0.8);
                font-size: 1rem;
                margin: 0;
            }

            .form-floating {
                margin-bottom: 25px;
                position: relative;
            }

            .form-floating .form-control {
                background: rgba(255, 255, 255, 0.9);
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 12px;
                padding: 20px 15px 10px;
                font-size: 16px;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
            }

            .form-floating .form-control:focus {
                background: rgba(255, 255, 255, 0.95);
                border-color: #1b1d25;
                box-shadow: 0 0 0 0.2rem rgba(60, 63, 75, 0.25);
                transform: translateY(-2px);
            }

            .form-floating label {
                color: #666;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .input-group-text {
                background: rgba(255, 255, 255, 0.9);
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-right: none;
                border-radius: 12px 0 0 12px;
                padding: 20px 15px 10px;
            }

            .password-toggle {
                background: rgba(255, 255, 255, 0.9);
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-left: none;
                border-radius: 0 12px 12px 0;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .password-toggle:hover {
                background: rgba(255, 255, 255, 0.95);
            }

            .btn-login {
                background: linear-gradient(135deg, #20263d 0%, #575474 100%);
                border: none;
                border-radius: 12px;
                color: white;
                font-weight: 600;
                font-size: 1.1rem;
                padding: 15px;
                transition: all 0.3s ease;
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
                margin-top: 10px;
            }

            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
                background: linear-gradient(135deg, #b5b9e4 0%, #35235f 100%);
            }

            .btn-login:active {
                transform: translateY(0);
            }

            .alert {
                background: rgba(220, 53, 69, 0.9);
                border: none;
                border-radius: 12px;
                color: white;
                margin-bottom: 25px;
                padding: 15px;
                backdrop-filter: blur(10px);
                animation: shake 0.5s ease-in-out;
            }

            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                25% {
                    transform: translateX(-5px);
                }

                75% {
                    transform: translateX(5px);
                }
            }

            .floating-shapes {
                position: absolute;
                width: 100%;
                height: 100%;
                overflow: hidden;
                pointer-events: none;
            }

            .floating-shapes::before,
            .floating-shapes::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                animation: float 6s ease-in-out infinite;
            }

            .floating-shapes::before {
                width: 80px;
                height: 80px;
                top: 20%;
                left: 10%;
                animation-delay: -2s;
            }

            .floating-shapes::after {
                width: 60px;
                height: 60px;
                bottom: 20%;
                right: 10%;
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

            .logo-icon {
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                font-size: 24px;
                color: white;
                backdrop-filter: blur(10px);
            }

            @media (max-width: 576px) {
                .login-container {
                    margin: 20px;
                    padding: 30px;
                }

                .login-header h2 {
                    font-size: 1.8rem;
                }
            }
        </style>
    </head>

    <body>
        <div class="floating-shapes"></div>

        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="login-container">
                <div class="login-header">
                    <div class="logo-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h2>Selamat Datang</h2>
                    <p>Masuk ke Aplikasi CRUD Barang</p>
                </div>

                @if (session('error'))
                    <div class="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating">
                        <input type="email" name="email" class="form-control" id="email"
                            placeholder="nama@contoh.com" required autofocus>
                        <label for="email">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                    </div>

                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Password" required>
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Smooth form interactions
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentNode.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.parentNode.classList.remove('focused');
                    }
                });
            });

            // Form validation visual feedback
            document.querySelector('form').addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('.btn-login');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                submitBtn.disabled = true;
            });
        </script>
    </body>

</html>
