@extends('admin.layouts.superadmin')
@section('title', 'Create Subcontractor')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4">Create Crew Manager</h1>

    <form method="POST" action="{{ route('superadmin.subcontractors.store') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">First Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Company Name *</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">State *</label>
                <input type="text" name="state" value="{{ old('state') }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            

            <div class="col-md-6 d-flex align-items-center">
                <div class="form-check mt-4">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                    <label for="is_active" class="form-check-label">Active account</label>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('superadmin.subcontractors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save
            </button>
        </div>
    </form>
</div>
@endsection
