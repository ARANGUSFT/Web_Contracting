@extends('layouts.app')

@section('title', 'Create Team Member')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Create Team Member</h1>
    <div class="card p-4 mb-4 shadow-sm">
        <h5 class="mb-3">Register a New Team Member</h5>
        <form id="registerForm" action="{{ route('teams.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
                <span class="error-message" id="nameError">Please enter a valid name.</span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
                <span class="error-message" id="emailError">Please enter a valid email.</span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <span class="error-message" id="passwordError">Password must be at least 6 characters.</span>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="company_admin">Company Admin</option>
                    <option value="manager">Manager</option>
                    <option value="sales">Sales</option>
                    <option value="guest">Guest</option>
                    <option value="project_manager">Project Manager</option>
                    <option value="crew">Crew</option>
                </select>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary mt-3 w-100">Submit</button>
        </form>
    </div>
    <a href="{{ route('teams.index') }}" class="btn btn-secondary mb-3">← Back to Teams</a>

</div>

<script>
    $(document).ready(function() {
        $("#registerForm").submit(function(event) {
            let valid = true;
            
            // Validación del nombre
            if ($("#name").val().trim() === "") {
                $("#nameError").show();
                valid = false;
            } else {
                $("#nameError").hide();
            }
            
            // Validación del email
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test($("#email").val())) {
                $("#emailError").show();
                valid = false;
            } else {
                $("#emailError").hide();
            }
            
            // Validación de la contraseña
            if ($("#password").val().length < 6) {
                $("#passwordError").show();
                valid = false;
            } else {
                $("#passwordError").hide();
            }
            
            if (!valid) {
                event.preventDefault();
            }
        });
    });
</script>

<style>
    .container {
        max-width: 900px;
    }
    .card {
        border-radius: 10px;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    .error-message {
        color: red;
        font-size: 0.9em;
        display: none;
    }
</style>

@endsection