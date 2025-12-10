@extends('layouts.app')

@section('title', 'Manage Teams')

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center">
            <i class="bi bi-people-fill text-primary fs-2 me-3"></i>
            <div>
                <h1 class="text-primary m-0">Manage Teams</h1>
                <p class="text-muted m-0 small">Total: {{ $teams->count() }} member(s)</p>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
            <a href="{{ route('teams.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Add Member
            </a>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters and Search --}}
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="searchInput" class="form-label small text-muted">Search members</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name, email or phone...">
                </div>
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label small text-muted">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="roleFilter" class="form-label small text-muted">Role</label>
                    <select class="form-select" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="company_admin">Company Admin</option>
                        <option value="manager">Manager</option>
                        <option value="project_manager">Project Manager</option>
                        <option value="sales">Sales</option>
                        <option value="crew">Crew</option>
                        <option value="guest">Guest</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Teams Table --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover" id="teamsTable">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Member</th>
                            <th>Contact Information</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                            <tr class="team-row" 
                                data-name="{{ strtolower($team->name) }}"
                                data-status="{{ $team->is_active ? 'active' : 'inactive' }}"
                                data-role="{{ strtolower($team->role) }}"
                                data-email="{{ strtolower($team->email) }}"
                                data-phone="{{ $team->phone ? strtolower($team->phone) : '' }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-primary">{{ $team->name }}</div>
                                            <small class="text-muted">Member since {{ $team->created_at->format('M Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-2">
                                        <i class="bi bi-envelope me-2 text-muted"></i>
                                        <a href="mailto:{{ $team->email }}" class="text-decoration-none">{{ $team->email }}</a>
                                    </div>
                                    <div>
                                        <i class="bi bi-telephone me-2 text-muted"></i>
                                        @if($team->phone)
                                            <a href="tel:{{ $team->phone }}" class="text-decoration-none">{{ $team->phone }}</a>
                                        @else
                                            <span class="text-muted fst-italic">No phone number</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $roleColors = [
                                            'company_admin' => 'bg-danger bg-opacity-25 text-danger-emphasis',
                                            'manager' => 'bg-warning bg-opacity-25 text-warning-emphasis',
                                            'project_manager' => 'bg-info bg-opacity-25 text-info-emphasis',
                                            'sales' => 'bg-success bg-opacity-25 text-success-emphasis',
                                            'crew' => 'bg-secondary bg-opacity-25 text-secondary-emphasis',
                                            'guest' => 'bg-light text-dark'
                                        ];
                                        $roleIcons = [
                                            'company_admin' => 'bi-shield-check',
                                            'manager' => 'bi-person-gear',
                                            'project_manager' => 'bi-clipboard-check',
                                            'sales' => 'bi-graph-up',
                                            'crew' => 'bi-people',
                                            'guest' => 'bi-person'
                                        ];
                                    @endphp
                                    <span class="badge {{ $roleColors[$team->role] ?? 'bg-light text-dark' }} px-3 py-2 rounded-pill">
                                        <i class="bi {{ $roleIcons[$team->role] ?? 'bi-person' }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $team->role)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $team->is_active ? 'bg-success bg-opacity-25 text-success-emphasis' : 'bg-danger bg-opacity-25 text-danger-emphasis' }} px-3 py-2 rounded-pill">
                                        <i class="bi bi-{{ $team->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                        {{ $team->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('teams.edit', $team->id) }}" 
                                           class="btn btn-outline-warning" 
                                           title="Edit Member">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger"
                                                onclick="confirmDelete('{{ $team->id }}', '{{ addslashes($team->name) }}')"
                                                title="Delete Member">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-people display-4 d-block mb-3"></i>
                                    <h5>No team members found</h5>
                                    <p class="mb-0">Get started by adding your first team member.</p>
                                    <a href="{{ route('teams.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-person-plus me-1"></i> Add First Member
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($teams->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $teams->firstItem() ?? 0 }} to {{ $teams->lastItem() ?? 0 }} of {{ $teams->total() }} entries
            </div>
            {{ $teams->links() }}
        </div>
    @endif
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        html: `You are about to delete <strong>${name}</strong>. This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger ms-2',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`deleteForm-${id}`).submit();
        }
    });
}

// Simple client-side filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const roleFilter = document.getElementById('roleFilter');
    const rows = document.querySelectorAll('.team-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const roleValue = roleFilter.value;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const phone = row.getAttribute('data-phone');
            const status = row.getAttribute('data-status');
            const role = row.getAttribute('data-role');

            const matchesSearch = name.includes(searchTerm) || 
                                email.includes(searchTerm) || 
                                phone.includes(searchTerm) ||
                                row.textContent.toLowerCase().includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            const matchesRole = !roleValue || role === roleValue;

            if (matchesSearch && matchesStatus && matchesRole) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    roleFilter.addEventListener('change', filterTable);

    // Phone number formatting and validation for create/edit forms
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove any non-digit characters except +, -, (, ), and spaces
            let value = e.target.value.replace(/[^\d\s\-\+\(\)]/g, '');
            
            // Limit to 20 characters
            if (value.length > 20) {
                value = value.substring(0, 20);
            }
            
            e.target.value = value;
            
            // Update character counter
            updatePhoneCounter(value.length);
        });
        
        phoneInput.addEventListener('blur', function(e) {
            formatPhoneNumber(e.target);
        });
        
        function formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');
            
            if (value.length === 0) return;
            
            // Simple formatting for different number lengths
            if (value.length === 10) {
                // US format: (555) 123-4567
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            } else if (value.length === 11 && value.startsWith('1')) {
                // US with country code: +1 (555) 123-4567
                value = value.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+$1 ($2) $3-$4');
            } else if (value.length > 11) {
                // International format with spaces
                value = value.replace(/(\d{3})(?=\d)/g, '$1 ');
            }
            
            input.value = value;
            updatePhoneCounter(value.length);
        }
        
        function updatePhoneCounter(length) {
            let counter = document.getElementById('phoneCounter');
            if (!counter) {
                counter = document.createElement('div');
                counter.id = 'phoneCounter';
                counter.className = 'form-text phone-counter';
                phoneInput.parentNode.appendChild(counter);
            }
            counter.textContent = `${length}/20 characters`;
            counter.style.color = length > 20 ? '#dc3545' : '#6c757d';
            counter.style.fontSize = '0.75rem';
            counter.style.marginTop = '0.25rem';
            
            // Also update input border color
            if (length > 20) {
                phoneInput.style.borderColor = '#dc3545';
            } else {
                phoneInput.style.borderColor = '';
            }
        }
        
        // Initialize counter if there's already a value
        if (phoneInput.value) {
            updatePhoneCounter(phoneInput.value.length);
        }
    }

    // Real-time validation for phone fields in forms
    const phoneFields = document.querySelectorAll('input[type="tel"]');
    phoneFields.forEach(field => {
        field.addEventListener('input', function() {
            const value = this.value;
            if (value.length > 20) {
                this.setCustomValidity('Phone number must not exceed 20 characters');
            } else {
                this.setCustomValidity('');
            }
        });
    });
});

// Additional function to validate phone before form submission
function validatePhoneBeforeSubmit(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(e) {
            const phoneInput = this.querySelector('input[name="phone"]');
            if (phoneInput && phoneInput.value.length > 20) {
                e.preventDefault();
                Swal.fire({
                    title: 'Invalid Phone Number',
                    text: 'Phone number must not exceed 20 characters.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                phoneInput.focus();
            }
        });
    }
}

// Initialize phone validation for create and edit forms
document.addEventListener('DOMContentLoaded', function() {
    validatePhoneBeforeSubmit('createForm');
    validatePhoneBeforeSubmit('editForm');
});
</script>

<style>
.container {
    max-width: 1200px;
}

.avatar-placeholder {
    font-size: 0.9rem;
}

.table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    border-radius: 0.375rem !important;
    margin: 0 1px;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem !important;
    border-bottom-left-radius: 0.375rem !important;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem !important;
    border-bottom-right-radius: 0.375rem !important;
}

.badge {
    font-weight: 500;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

/* Hover effects */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Contact links */
a[href^="mailto:"], a[href^="tel:"] {
    transition: color 0.2s ease-in-out;
}

a[href^="mailto:"]:hover, a[href^="tel:"]:hover {
    color: #0d6efd !important;
}
</style>

{{-- Hidden delete forms --}}
@foreach($teams as $team)
<form id="deleteForm-{{ $team->id }}" 
      action="{{ route('teams.destroy', $team->id) }}" 
      method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endforeach

@endsection