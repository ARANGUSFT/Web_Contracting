@extends('layouts.app')

@section('title', 'Manage Teams')

@section('content')

<div class="container">
    <h1 class="mb-4 text-center">Manage Teams</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- Botón para agregar un nuevo miembro --}}
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅ Back to Dashboard</a>
        <a href="{{ route('teams.create') }}" class="btn btn-primary">➕ Add Team Member</a>
    </div>

    {{-- Tabla de Miembros del Equipo --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teams as $team)
                    <tr>
                        <td class="fw-bold text-primary">{{ $team->name }}</td>
                        <td>{{ $team->email }}</td>
                        <td>
                            <span class="badge bg-info text-dark p-2">{{ ucfirst($team->role) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $team->is_active ? 'bg-success' : 'bg-danger' }} p-2">
                                {{ $team->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-sm btn-warning">✏ Edit</a>
                         
                            <form id="deleteForm" action="{{ route('teams.destroy', $team->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $team->name }}')">🗑 Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No team members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(event, name) {
        event.preventDefault();
        if (confirm(`Are you sure you want to delete ${name}?`)) {
            event.target.closest('form').submit();
        }
    }

    function confirmDelete(name) {
        Swal.fire({
            title: `Are you sure you want to delete ${name}?`,
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>

<style>
    .container {
        max-width: 1100px;
     
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .badge {
        font-size: 0.9rem;
        font-weight: bold;
        border-radius: 5px;
    }
    .btn-warning, .btn-danger {
        transition: transform 0.2s ease-in-out;
    }
    .btn-warning:hover, .btn-danger:hover {
        transform: scale(1.1);
    }
</style>



@endsection
