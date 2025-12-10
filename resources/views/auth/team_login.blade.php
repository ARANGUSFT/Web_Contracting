<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Contracting Alliance Inc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="login-container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-card p-4 shadow-lg">
            <div class="text-center">
                <img src="{{ asset('img/logo.png') }}" alt="Contracting Alliance Logo" class="logo">
                <h2 class="mt-3">Contracting Alliance Inc</h2>
                <p class="text-muted">Login to your account</p>
            </div>

            <!-- 🔹 Mensajes de Error Mejorados -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Please check the following errors:
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('team.login') }}" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-2"></i>Email Address
                    </label>
                    <div class="input-group">
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control" 
                               required 
                               placeholder="Enter your email"
                               value="{{ old('email') }}"
                               autocomplete="email">
                    </div>
                    <div class="invalid-feedback email-feedback">
                        Please enter a valid email address.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-key me-2"></i>Password
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control" 
                               required 
                               placeholder="Enter your password"
                               autocomplete="current-password">
                        <button type="button" class="btn btn-outline-secondary toggle-password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback password-feedback">
                        Password must be at least 6 characters long.
                    </div>
                </div>

                <!-- Remember Me Option -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 login-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>

       

            <!-- Support Info -->
            <div class="text-center mt-3">
                <p class="small text-muted mb-0">
                    Need help? Contact 
                    <a href="mailto:support@contractingallianceinc.com" class="text-decoration-none">support@contractingallianceinc.com</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.querySelector('.toggle-password');
            const loginBtn = document.querySelector('.login-btn');

            // Toggle password visibility
            togglePasswordBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });

            // Real-time validation
            emailInput.addEventListener('input', validateEmail);
            passwordInput.addEventListener('input', validatePassword);
            emailInput.addEventListener('blur', validateEmail);
            passwordInput.addEventListener('blur', validatePassword);

            function validateEmail() {
                const email = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email === '') {
                    setInvalid(emailInput, 'Please enter your email address.');
                    return false;
                } else if (!emailPattern.test(email)) {
                    setInvalid(emailInput, 'Please enter a valid email address.');
                    return false;
                } else {
                    setValid(emailInput);
                    return true;
                }
            }

            function validatePassword() {
                const password = passwordInput.value;
                
                if (password === '') {
                    setInvalid(passwordInput, 'Please enter your password.');
                    return false;
                } else if (password.length < 6) {
                    setInvalid(passwordInput, 'Password must be at least 6 characters long.');
                    return false;
                } else {
                    setValid(passwordInput);
                    return true;
                }
            }

            function setInvalid(input, message) {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                const feedback = input.parentElement.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = message;
                    feedback.style.display = 'block';
                }
            }

            function setValid(input) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                const feedback = input.parentElement.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            }

            // Form submission
            form.addEventListener('submit', function(e) {
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();
                
                if (!isEmailValid || !isPasswordValid) {
                    e.preventDefault();
                    
                    // Show error state on button
                    loginBtn.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Please check errors';
                    loginBtn.classList.remove('btn-primary');
                    loginBtn.classList.add('btn-danger');
                    
                    setTimeout(() => {
                        loginBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Login';
                        loginBtn.classList.remove('btn-danger');
                        loginBtn.classList.add('btn-primary');
                    }, 2000);
                    
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                } else {
                    // Add loading state
                    loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';
                    loginBtn.disabled = true;
                }
            });

            // Auto-focus email field
            emailInput.focus();
        });
    </script>

    <!-- 🔹 Estilos Personalizados Mejorados -->
    <style>
        /* 📌 Fondo con degradado sutil */
        .login-container {
            background: linear-gradient(to right, #007bff, #117e9c);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* 📌 Tarjeta de login con efectos de sombra y borde redondeado */
        .login-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        /* 📌 Animación de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 📌 Logo */
        .logo {
            width: 100px;
            margin-bottom: 10px;
        }

        /* 📌 Botón con efecto hover */
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: 0.3s ease-in-out;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-primary:disabled {
            background-color: #007bff;
            opacity: 0.7;
            transform: none;
        }

        /* 📌 Inputs con bordes redondeados y sombras suaves */
        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: 0.2s ease-in-out;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
            transform: translateY(-1px);
        }

        .form-control.is-valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
        }

        /* 📌 Enlace de "Forgot Password" */
        .text-center a {
            color: #007bff;
            font-weight: 500;
            transition: 0.2s ease-in-out;
        }

        .text-center a:hover {
            text-decoration: underline;
            color: #0056b3;
            transform: translateY(-1px);
        }

        /* 📌 Toggle Password Button */
        .toggle-password {
            border-radius: 0 8px 8px 0;
            border: 2px solid #e9ecef;
            border-left: none;
        }

        /* 📌 Alert Improvements */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* 📌 Invalid Feedback */
        .invalid-feedback {
            display: none;
            text-align: left;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        /* 📌 Responsive Design - Solo mejoras esenciales */
        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
                height: 100vh;
            }
            
            .login-card {
                padding: 30px 20px;
                max-width: 100%;
                margin: 0 10px;
            }
            
            .logo {
                width: 80px;
            }
            
            .btn-primary {
                padding: 10px;
            }
            
            .form-control {
                padding: 10px 12px;
            }
        }

        @media (max-width: 320px) {
            .login-card {
                padding: 25px 15px;
            }
            
            .logo {
                width: 70px;
            }
        }

        /* 📌 Asegurar que sea mobile-friendly */
        @media (max-height: 600px) {
            .login-container {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }
        }
    </style>
</body>
</html>