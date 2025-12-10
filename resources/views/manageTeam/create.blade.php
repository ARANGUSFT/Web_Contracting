@extends('layouts.app')

@section('title', 'Create Team Member')

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center">
            <i class="bi bi-person-plus-fill text-primary fs-2 me-3"></i>
            <div>
                <h1 class="text-primary m-0">Create Team Member</h1>
                <p class="text-muted m-0 small">Add a new member to your team</p>
            </div>
        </div>
        <a href="{{ route('teams.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Teams
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please fix the following errors:
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Create Form --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="bi bi-person-badge me-2"></i>Member Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="createForm" action="{{ route('teams.store') }}" method="POST">
                        @csrf
                        
                        {{-- Personal Information --}}
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
                                           id="name" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter full name"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">The member's full name as it should appear</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-telephone text-muted"></i>
                                    </span>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}" 
                                           placeholder="+1 (555) 123-4567">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Optional phone number</div>
                            </div>
                        </div>

                        {{-- Account Information --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-envelope text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" 
                                           placeholder="member@company.com"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Login credentials will be sent to this email</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Minimum 6 characters"
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
                        </div>

                        {{-- Role and Status --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person-gear text-muted"></i>
                                    </span>
                                    <select name="role" 
                                            id="role" 
                                            class="form-select @error('role') is-invalid @enderror" 
                                            required>
                                        <option value="">Select a role...</option>
                                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="project_manager" {{ old('role') == 'project_manager' ? 'selected' : '' }}>Project Manager</option>
                                        <option value="sales" {{ old('role') == 'sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="crew" {{ old('role') == 'crew' ? 'selected' : '' }}>Crew</option>
                                        <option value="guest" {{ old('role') == 'guest' ? 'selected' : '' }}>Guest</option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Define the member's access level and permissions</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="card bg-light border-0 p-3 h-100">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_active">
                                            <span class="status-label">Active Member</span>
                                        </label>
                                    </div>
                                    <div class="form-text mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <span class="status-description">Active members can access the system</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end flex-wrap">
                                    <a href="{{ route('teams.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-person-plus me-1"></i> Create Member
                                    </button>
                                </div>
                            </div>
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
                            <h6 class="mb-1 text-primary">About Team Members</h6>
                            <p class="mb-0 small">New members will receive an email with their login credentials. You can manage their access and permissions at any time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

    // Real-time validation for required fields
    const form = document.getElementById('createForm');
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

    // Validación específica para teléfono US
    const phoneInput = document.getElementById('phone');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Auto-completar +1 si el usuario empieza con números
            if (value.length === 1 && /[0-9]/.test(value)) {
                e.target.value = '+1 ' + value;
                return;
            }
            
            // Si ya tiene +1, aplicar validación específica
            if (value.startsWith('+1')) {
                // Remover caracteres no permitidos (solo números, espacios, paréntesis, guiones)
                let cleaned = value.replace(/[^\d\s\(\)\-+]/g, '');
                
                // Limitar a 17 caracteres máximo (incluyendo +1 y formato)
                if (cleaned.length > 17) {
                    cleaned = cleaned.substring(0, 17);
                }
                
                // Aplicar formato automático
                if (cleaned.length > 3) {
                    let numbersOnly = cleaned.replace(/\D/g, '');
                    
                    // Si tiene más de 11 dígitos (sin contar +1), truncar
                    if (numbersOnly.length > 11) {
                        numbersOnly = numbersOnly.substring(0, 11);
                    }
                    
                    // Reconstruir con formato
                    if (numbersOnly.length >= 2) {
                        let formatted = '+1';
                        if (numbersOnly.length > 2) {
                            formatted += ' (' + numbersOnly.substring(1, 4);
                        }
                        if (numbersOnly.length > 4) {
                            formatted += ') ' + numbersOnly.substring(4, 7);
                        }
                        if (numbersOnly.length > 7) {
                            formatted += '-' + numbersOnly.substring(7, 11);
                        }
                        e.target.value = formatted;
                    } else {
                        e.target.value = cleaned;
                    }
                } else {
                    e.target.value = cleaned;
                }
            } else if (value.length > 0) {
                // Si no empieza con +1 pero tiene contenido, forzar +1
                let numbersOnly = value.replace(/\D/g, '');
                if (numbersOnly.length > 0) {
                    e.target.value = '+1 ' + numbersOnly;
                }
            }
        });

        // Validación al perder el foco
        phoneInput.addEventListener('blur', function(e) {
            let value = e.target.value.trim();
            
            if (value && !value.startsWith('+1')) {
                // Mostrar error visual
                e.target.classList.add('is-invalid');
                
                // Crear mensaje de error si no existe
                let errorDiv = e.target.parentNode.parentNode.querySelector('.phone-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block phone-error';
                    errorDiv.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Los números de Estados Unidos deben comenzar con +1';
                    e.target.parentNode.parentNode.appendChild(errorDiv);
                }
            } else if (value) {
                // Validar longitud mínima
                let numbersOnly = value.replace(/\D/g, '');
                if (numbersOnly.length < 11) { // +1 + 10 dígitos
                    e.target.classList.add('is-invalid');
                    
                    let errorDiv = e.target.parentNode.parentNode.querySelector('.phone-error');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback d-block phone-error';
                        errorDiv.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Número incompleto. Debe tener 10 dígitos después del +1';
                        e.target.parentNode.parentNode.appendChild(errorDiv);
                    }
                } else {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                    
                    // Remover mensaje de error si existe
                    let errorDiv = e.target.parentNode.parentNode.querySelector('.phone-error');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                }
            } else {
                // Campo vacío - remover estados de validación
                e.target.classList.remove('is-invalid', 'is-valid');
                
                // Remover mensaje de error si existe
                let errorDiv = e.target.parentNode.parentNode.querySelector('.phone-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        });

        // Limpiar validación al enfocar
        phoneInput.addEventListener('focus', function(e) {
            e.target.classList.remove('is-invalid');
            
            // Remover mensaje de error temporal
            let errorDiv = e.target.parentNode.parentNode.querySelector('.phone-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        });
    }

    // Validación del formulario para teléfono
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validar campos requeridos
        inputs.forEach(input => {
            validateField(input);
            if (input.classList.contains('is-invalid')) {
                isValid = false;
            }
        });
        
        // Validación específica para teléfono
        if (phoneInput && phoneInput.value.trim()) {
            let value = phoneInput.value.trim();
            let numbersOnly = value.replace(/\D/g, '');
            
            // Debe empezar con +1 y tener 11 dígitos en total
            if (!value.startsWith('+1')) {
                phoneInput.classList.add('is-invalid');
                let errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Los números de Estados Unidos deben comenzar con +1';
                phoneInput.parentNode.parentNode.appendChild(errorDiv);
                isValid = false;
            } else if (numbersOnly.length !== 11) {
                phoneInput.classList.add('is-invalid');
                let errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>El número debe tener 10 dígitos después del +1';
                phoneInput.parentNode.parentNode.appendChild(errorDiv);
                isValid = false;
            }
        }
        
        if (!isValid) {
            event.preventDefault();
            // Scroll to first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Update status description based on toggle
    const statusToggle = document.getElementById('is_active');
    const statusLabel = document.querySelector('.status-label');
    const statusDescription = document.querySelector('.status-description');
    
    if (statusToggle && statusLabel && statusDescription) {
        statusToggle.addEventListener('change', function() {
            if (this.checked) {
                statusLabel.textContent = 'Active Member';
                statusDescription.textContent = 'Active members can access the system';
            } else {
                statusLabel.textContent = 'Inactive Member';
                statusDescription.textContent = 'Inactive members cannot access the system';
            }
        });
    }

    // Limpiar mensajes de error al cambiar valores
    form.addEventListener('input', function(e) {
        if (e.target.classList.contains('is-invalid')) {
            e.target.classList.remove('is-invalid');
            // Remover mensajes de error específicos del campo
            const errorDiv = e.target.parentNode.parentNode.querySelector('.invalid-feedback:not(.phone-error)');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    });

    // Inicializar validación de campos con valores existentes
    inputs.forEach(input => {
        if (input.value.trim()) {
            validateField(input);
        }
    });

    // Validar teléfono si ya tiene valor
    if (phoneInput && phoneInput.value.trim()) {
        phoneInput.dispatchEvent(new Event('blur'));
    }
});
</script>

<style>
.container {
    max-width: 1000px;
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

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
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

/* Status card */
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection