<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Contracting Alliance</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --border-radius: 0.375rem;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        .privacy-link {
            transition: color 0.15s ease-in-out;
        }
        
        .privacy-link:hover {
            color: var(--primary-color) !important;
        }
        
        .modal-content {
            border: none;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }
        
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-container .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <x-guest-layout>
        <div class="login-container">
            <form method="POST" action="{{ route('login') }}" class="w-100">
                @csrf

                <div class="container py-5">
                    <!-- Header Section -->
                    <div class="text-center mb-5">
                        <h5 class="fw-bold text-primary">Welcome back</h5>
                        <p class="text-muted mb-3">Please log in to access your dashboard</p>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-person-plus me-1"></i> Create account
                            </a>
                        @endif
                    </div>

                    <!-- Login Card -->
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card login-card border-0 rounded">
                                <div class="card-body p-4 p-md-5">

                                    <!-- Status Messages -->
                                    @if (session('status'))
                                        <div class="alert alert-success mb-3" role="alert">
                                            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                                        </div>
                                    @endif

                                    <!-- Email Field -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input id="email" type="email" name="email" class="form-control" 
                                               value="{{ old('email') }}" required autofocus autocomplete="email"
                                               placeholder="Enter your email">
                                        @error('email')
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password Field -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password" type="password" name="password" class="form-control" 
                                               required autocomplete="current-password" placeholder="Enter your password">
                                        @error('password')
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Remember Me & Forgot Password -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                            <label class="form-check-label" for="remember_me">Remember me</label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">
                                                Forgot your password?
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid mb-3">
                                        <button type="submit" class="btn btn-primary py-2">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Log in
                                        </button>
                                    </div>

                                    <!-- Privacy Policy Link -->
                                    <div class="text-center mt-4">
                                        <a href="#" class="text-muted small text-decoration-none privacy-link" 
                                           data-bs-toggle="modal" data-bs-target="#privacyModal">
                                            <i class="bi bi-shield-check me-1"></i>Privacy Policy
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Privacy Policy Modal -->
        <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- El contenido de la política de privacidad se mantiene igual -->
                        <p>By using or accessing this website, you acknowledge and accept the Privacy Policy set forth below. If you do not agree to this policy, please do not use this website. Contracting Alliance may revise this policy at any time by updating this publication, and your use after such change means that you accept the modified terms. Please check this policy periodically for changes. This policy is intended to help you understand how Contracting Alliance collects, uses, and protects the information you provide on our website. This Privacy Policy does not apply to the use or disclosure of information that we collect or obtain through means other than this website.</p>

                        <!-- Resto del contenido de la política de privacidad -->
                        <!-- ... -->
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </x-guest-layout>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Custom JavaScript for enhanced UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus management for better accessibility
            const emailInput = document.getElementById('email');
            if (emailInput && !emailInput.value) {
                emailInput.focus();
            }
            
            // Add loading state to form submission
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Logging in...';
                    }
                });
            }
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>