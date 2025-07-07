@extends('admin.layouts.superadmin')

@section('title', 'Insurance Management')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-shield-alt text-primary me-2"></i>Insurance Management</h1>
        <div class="d-flex">
            <input type="text" id="searchInput" class="form-control form-control-sm me-2" placeholder="Search subcontractors..." style="width: 200px;">
            <button class="btn btn-sm btn-outline-secondary" id="filterBtn">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
    </div>

    @forelse($subcontractors as $sub)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="mb-1 text-dark">
                        <i class="fas fa-user-tie text-primary me-2"></i>
                        <strong>{{ $sub->name }} {{ $sub->last_name }}</strong>
                        <span class="text-muted">-</span> {{ $sub->company_name }}
                    </h5>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-envelope text-secondary me-1"></i> {{ $sub->email }}
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-phone text-secondary me-1"></i> {{ $sub->phone }}
                        </span>
                    </div>
                </div>
                
                @if($sub->insurances->isEmpty())
                <a href="{{ route('superadmin.subcontractors.insurances.create', $sub->id) }}" 
                   class="btn btn-sm btn-primary rounded-pill px-3">
                    <i class="fas fa-plus me-1"></i> Add Insurance
                </a>
                @endif
            </div>

            @if($sub->insurances->isNotEmpty())
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Insurance Files</th>
                                    <th>Expiration Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sub->insurances as $ins)
                                    @php
                                        $expiresAt = \Carbon\Carbon::parse($ins->expires_at);
                                        $isExpired = $expiresAt->isPast();
                                        $expiresSoon = !$isExpired && $expiresAt->diffInDays(now()) <= 30;
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            @if(is_array($ins->file))
                                                @foreach($ins->file as $f)
                                                    @if(isset($f['path']) && isset($f['original_name']))
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                <a href="{{ Storage::url($f['path']) }}" target="_blank" class="text-decoration-none">
                                                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                                                    <span class="text-primary">{{ $f['original_name'] }}</span>
                                                                </a>
                                                            </div>
                                                            <a href="{{ Storage::url($f['path']) }}" download="{{ $f['original_name'] }}" 
                                                               class="btn btn-sm btn-outline-secondary ms-2" title="Download">
                                                                <i class="fas fa-download fa-sm"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <a href="{{ Storage::url($ins->file) }}" target="_blank" class="text-decoration-none">
                                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                                            <span class="text-primary">{{ basename($ins->file) }}</span>
                                                        </a>
                                                    </div>
                                                    <a href="{{ Storage::url($ins->file) }}" download="{{ basename($ins->file) }}" 
                                                       class="btn btn-sm btn-outline-secondary ms-2" title="Download">
                                                        <i class="fas fa-download fa-sm"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="{{ $isExpired ? 'text-danger' : ($expiresSoon ? 'text-warning' : 'text-success') }}">
                                                {{ $expiresAt->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($isExpired)
                                                <span class="badge bg-danger bg-opacity-10 text-danger">Expired</span>
                                            @elseif($expiresSoon)
                                                <span class="badge bg-warning bg-opacity-10 text-warning">Expires Soon</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success">Valid</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $ins->notes ?? '' }}">
                                                {{ $ins->notes ?? '—' }}
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('superadmin.subcontractors.insurances.edit', [$sub->id, $ins->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary rounded-circle" 
                                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="fas fa-pencil-alt fa-sm"></i>
                                                </a>
                                                <form method="POST" action="{{ route('superadmin.subcontractors.insurances.destroy', [$sub->id, $ins->id]) }}"
                                                      class="d-inline delete-insurance-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger rounded-circle" 
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                        <i class="fas fa-trash-alt fa-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card-body text-center py-4">
                    <div class="py-3">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No insurance records found</h5>
                        <p class="text-muted small">Click "Add Insurance" to upload insurance documents</p>
                        <a href="{{ route('superadmin.subcontractors.insurances.create', $sub->id) }}" 
                           class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Add Insurance
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="alert alert-info d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            No subcontractors found in the system.
        </div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-insurance-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete Insurance Record?',
                text: "This action cannot be undone. Are you sure you want to proceed?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Search functionality (basic client-side filtering)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.card.mb-4');
            
            cards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                if (cardText.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});
</script>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-outline-primary, .btn-outline-danger {
        --bs-btn-hover-bg: transparent;
    }
    .rounded-circle {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection