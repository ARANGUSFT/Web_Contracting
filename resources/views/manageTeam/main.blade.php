@extends('layouts.app')

@section('title', 'Manage Teams')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="text-primary m-0">
            <i class="bi bi-people-fill me-2"></i> Manage Teams
        </h1>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <a href="{{ route('teams.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Member
            </a>
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success text-center shadow-sm">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tabla de Miembros --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $team->name }}</td>
                                <td>{{ $team->email }}</td>
                                <td>
                                    <span class="badge bg-info text-dark px-2 py-1">{{ ucfirst($team->role) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $team->is_active ? 'bg-success' : 'bg-danger' }} px-2 py-1">
                                        {{ $team->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form id="deleteForm-{{ $team->id }}" 
                                          action="{{ route('teams.destroy', $team->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $team->id }}', '{{ $team->name }}')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="bi bi-info-circle me-1"></i> No team members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: `Delete ${name}?`,
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        });
    }
</script>

<style>
    .container {
        max-width: 1100px;
    }
    .card {
     0   border-radius: 12px;
    }
    .btn {
        transition: all 0.2s ease-in-out;
    }
    .btn:hover {
        transform: translateY(-2px);
    }
    .badge {
        font-size: 0.85rem;
    }
    table th, table td {
        vertical-align: middle !important;
    }
</style>
@endsection
