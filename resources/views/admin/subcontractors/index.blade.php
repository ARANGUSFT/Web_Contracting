@extends('admin.layouts.superadmin')
@section('title', 'Subcontractors')

@section('actions')
    <div class="flex space-x-2">
        <a href="{{ route('superadmin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
  
    </div>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
 
        <div>
            <a href="{{ route('superadmin.subcontractors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New
            </a>
        </div>
    </div>

    <!-- Filtro mejorado -->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form action="{{ route('superadmin.subcontractors.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="state" class="form-select">
                            <option value="">All States</option>
                            @foreach($states as $state)
                                <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('superadmin.subcontractors.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>State</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subcontractors as $sub)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-light rounded-circle text-primary">
                                                {{ substr($sub->name, 0, 1) }}{{ substr($sub->last_name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $sub->name }} {{ $sub->last_name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $sub->company_name }}</td>
                                <td><a href="mailto:{{ $sub->email }}" class="text-primary">{{ $sub->email }}</a></td>
                                <td><a href="tel:{{ $sub->phone }}" class="text-primary">{{ $sub->phone }}</a></td>
                                <td>{{ $sub->state }}</td>
                                <td>
                                    @if($sub->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('superadmin.subcontractors.edit', $sub->id) }}" 
                                           class="btn btn-outline-secondary" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                        class="btn btn-outline-danger"
                                        onclick="confirmDeleteSubcontractor({{ $sub->id }})"
                                        data-bs-toggle="tooltip" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <form id="delete-form-{{ $sub->id }}"
                                      action="{{ route('superadmin.subcontractors.destroy', $sub->id) }}"
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-users-slash text-muted fa-2x mb-2"></i>
                                        <h5 class="text-muted">No subcontractors found</h5>
                                        <p class="text-muted">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación con parámetros de filtrado -->
            @if($subcontractors->hasPages())
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $subcontractors->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


<script>
    // Activar tooltips
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip()
    });
    
    // Opcional: Submit automático al cambiar filtros (excepto búsqueda)
    $('select[name="status"], select[name="state"]').change(function() {
        if($(this).val() !== '') {
            $(this).closest('form').submit();
        }
    });
</script>


<script>
    function confirmDeleteSubcontractor(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This subcontractor will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>