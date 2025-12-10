@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-person-gear text-primary fs-2 me-3"></i>
            <div>
                <h1 class="text-primary m-0">Edit Profile</h1>
                <p class="text-muted m-0 small">Update your account information</p>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Profile Information Card --}}
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="bi bi-person-badge me-2"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route(Route::currentRouteName(), $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name', $user->name) }}" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="Enter your full name"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           name="email" 
                                           id="email"
                                           value="{{ old('email', $user->email) }}" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="your.email@example.com"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password Card --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="bi bi-shield-lock me-2"></i>Change Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route(str_replace('.edit', '.password.update', Route::currentRouteName())) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="current_password" class="form-label">
                                    Current Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           name="current_password" 
                                           id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           placeholder="Enter your current password"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-key text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           id="password"
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Enter new password"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Must be at least 6 characters long</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-key-fill text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation"
                                           class="form-control" 
                                           placeholder="Confirm new password"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="bi bi-arrow-repeat me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Help Card --}}
            <div class="card shadow-sm border-0 rounded-3 mt-4 bg-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-primary me-3 fs-5"></i>
                        <div>
                            <h6 class="mb-1 text-primary">Profile Management</h6>
                            <p class="mb-0 small">Keep your profile information up to date. For security reasons, you'll need to enter your current password to change it.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 900px;
}

.card {
    border-radius: 12px;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.input-group-text {
    transition: all 0.2s ease-in-out;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.toggle-password {
    border-left: 0;
}

/* Validation states */
.is-valid {
    border-color: #198754 !important;
}

.is-invalid {
    border-color: #dc3545 !important;
}

/* Smooth transitions */
.form-control, .form-select, .form-check-input {
    transition: all 0.2s ease-in-out;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const passwordInput = document.getElementById(target);
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
    });

    // Real-time validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        switch(field.type) {
            case 'email':
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailPattern.test(value);
                break;
            case 'password':
                isValid = value.length >= 6;
                break;
            default:
                isValid = value !== '';
        }
        
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }
    }

    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    if (password && passwordConfirmation) {
        passwordConfirmation.addEventListener('blur', function() {
            if (password.value !== passwordConfirmation.value && passwordConfirmation.value !== '') {
                passwordConfirmation.classList.add('is-invalid');
                passwordConfirmation.classList.remove('is-valid');
            } else if (passwordConfirmation.value !== '') {
                passwordConfirmation.classList.remove('is-invalid');
                passwordConfirmation.classList.add('is-valid');
            }
        });
    }
});
</script>
@endsection