@extends('layouts.app')

@section('title', 'Edit Team Member')

@section('content')

<div class="container">
    <h1 class="mb-4 text-center">Edit Team Member</h1>
    
    <div class="card p-4 mb-4 shadow-sm">
        <h5 class="mb-3">Update Member Information</h5>
        <form action="{{ route('teams.update', $team->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $team->name }}" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $team->email }}" required>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="company_admin" {{ $team->role == 'company_admin' ? 'selected' : '' }}>Company Admin</option>
                    <option value="manager" {{ $team->role == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="sales" {{ $team->role == 'sales' ? 'selected' : '' }}>Sales</option>
                    <option value="guest" {{ $team->role == 'guest' ? 'selected' : '' }}>Guest</option>
                    <option value="project_manager" {{ $team->role == 'project_manager' ? 'selected' : '' }}>Project Manager</option>
                    <option value="crew" {{ $team->role == 'crew' ? 'selected' : '' }}>Crew</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">New Password (optional)</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Leave blank to keep the current password</small>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $team->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <button type="submit" class="btn btn-primary mt-3 w-100">Update</button>
        </form>
    </div>

    <a href="{{ route('teams.index') }}" class="btn btn-secondary">⬅ Back to Teams</a>
</div>

<style>
    .container {
        max-width: 900px;
     
    }
    .card {
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .form-control {
        border-radius: 8px;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: background 0.3s ease-in-out;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-secondary {
        margin-top: 10px;
    }
</style>


@endsection
