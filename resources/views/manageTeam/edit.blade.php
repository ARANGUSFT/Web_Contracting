@extends('layouts.app')

@section('title', 'Edit Team Member')

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center">
            <i class="bi bi-person-gear text-primary fs-2 me-3"></i>
            <div>
                <h1 class="text-primary m-0">Edit Team Member</h1>
                <p class="text-muted m-0 small">Update member information and permissions</p>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Edit Form --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-primary">{{ $team->name }}</h5>
                            <p class="text-muted mb-0 small">Member since {{ $team->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('teams.update', $team->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
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
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $team->name) }}" 
                                           placeholder="Enter full name"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-telephone text-muted"></i>
                                    </span>
                                    <input type="tel" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $team->phone) }}" 
                                           placeholder="+1 (555) 123-4567">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
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
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $team->email) }}" 
                                           placeholder="member@company.com"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Leave blank to keep current">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Minimum 6 characters. Leave empty to maintain current password.
                                </div>
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
                                            class="form-select @error('role') is-invalid @enderror" 
                                            required>
                                        <option value="">Select a role...</option>
                                        <option value="manager" {{ old('role', $team->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="project_manager" {{ old('role', $team->role) == 'project_manager' ? 'selected' : '' }}>Project Manager</option>
                                        <option value="sales" {{ old('role', $team->role) == 'sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="crew" {{ old('role', $team->role) == 'crew' ? 'selected' : '' }}>Crew</option>
                                        <option value="guest" {{ old('role', $team->role) == 'guest' ? 'selected' : '' }}>Guest</option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Status</label>
                                <div class="card bg-light border-0 p-3 h-100">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1" 
                                               {{ old('is_active', $team->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_active">
                                            <span class="status-label">
                                                {{ $team->is_active ? 'Active' : 'Inactive' }} Member
                                            </span>
                                        </label>
                                    </div>
                                    <div class="form-text mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <span class="status-description">
                                            {{ $team->is_active ? 'Active members can access the system' : 'Inactive members cannot access the system' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Member Details --}}
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body py-3">
                                        <div class="row text-center">
                                            <div class="col-md-4 border-end">
                                                <small class="text-muted d-block">Member ID</small>
                                                <strong class="text-primary">#{{ $team->id }}</strong>
                                            </div>
                                            <div class="col-md-4 border-end">
                                                <small class="text-muted d-block">Last Updated</small>
                                                <strong>{{ $team->updated_at->format('M d, Y') }}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Account Status</small>
                                                <span class="badge {{ $team->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $team->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-between flex-wrap">
                                    <div>
                                        <a href="{{ route('teams.index') }}" class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmReset()">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Reset Changes
                                        </button>
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-check-circle me-1"></i> Update Member
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="card shadow-sm border-danger rounded-3 mt-4">
                <div class="card-header bg-danger bg-opacity-10 text-danger border-bottom-0">
                    <h6 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="mb-1">Delete Team Member</h6>
                            <p class="mb-0 text-muted small">
                                Once deleted, this member will be permanently removed from the system.
                            </p>
                        </div>
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="bi bi-trash me-1"></i> Delete Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const passwordInput = document.querySelector(`[name="${target}"]`);
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

    // Update status description based on toggle
    const statusToggle = document.getElementById('is_active');
    const statusLabel = document.querySelector('.status-label');
    const statusDescription = document.querySelector('.status-description');
    
    statusToggle.addEventListener('change', function() {
        if (this.checked) {
            statusLabel.textContent = 'Active Member';
            statusDescription.textContent = 'Active members can access the system';
        } else {
            statusLabel.textContent = 'Inactive Member';
            statusDescription.textContent = 'Inactive members cannot access the system';
        }
    });

    // Real-time validation
    const form = document.getElementById('editForm');
    const requiredInputs = form.querySelectorAll('input[required]');
    
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
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
});

function confirmReset() {
    Swal.fire({
        title: 'Reset Changes?',
        text: 'All unsaved changes will be lost.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, reset',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('editForm').reset();
            // Restore original values for select and checkbox
            const originalRole = '{{ $team->role }}';
            const originalIsActive = {{ $team->is_active ? 'true' : 'false' }};
            
            document.querySelector('[name="role"]').value = originalRole;
            document.getElementById('is_active').checked = originalIsActive;
            
            // Update status display
            const statusToggle = document.getElementById('is_active');
            statusToggle.dispatchEvent(new Event('change'));
            
            Swal.fire('Reset!', 'Changes have been reset.', 'success');
        }
    });
}

function confirmDelete() {
    Swal.fire({
        title: 'Delete Member?',
        html: `You are about to delete <strong>{{ $team->name }}</strong>. This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("teams.destroy", $team->id) }}';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
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

/* Danger zone specific styles */
.card.border-danger {
    border: 1px solid #dc3545 !important;
}

/* Smooth transitions */
.form-control, .form-select, .form-check-input {
    transition: all 0.2s ease-in-out;
}

.avatar-placeholder {
    font-size: 1.1rem;
}
</style>
@endsection