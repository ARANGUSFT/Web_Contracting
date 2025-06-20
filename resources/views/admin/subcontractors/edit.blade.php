@extends('admin.layouts.superadmin')

@section('title', 'Edit Crew Manager')

@section('content')
<div class="container-fluid px-4">


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.subcontractors.update', $subcontractor->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="form-control" 
                                   value="{{ old('company_name', $subcontractor->company_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="{{ old('name', $subcontractor->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" 
                                   value="{{ old('last_name', $subcontractor->last_name) }}">
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="{{ old('email', $subcontractor->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control" 
                                   value="{{ old('phone', $subcontractor->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" id="state" name="state" class="form-control" 
                                   value="{{ old('state', $subcontractor->state) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" 
                                   placeholder="Leave blank to keep current password">
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mt-4 pt-2">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   class="form-check-input" role="switch" value="1"
                                   {{ old('is_active', $subcontractor->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active account</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('superadmin.subcontractors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Subcontractor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection