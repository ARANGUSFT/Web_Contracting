@extends('admin.layouts.superadmin')

@section('title', 'Edit Crew')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4">Edit Crew</h1>

    <form method="POST" action="{{ route('superadmin.crew.update', $crew) }}">
        @csrf @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Crew Name *</label>
                <input name="name" class="form-control" value="{{ old('name', $crew->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Company *</label>
                <input name="company" class="form-control" value="{{ old('company', $crew->company) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input name="email" type="email" class="form-control" value="{{ old('email', $crew->email) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input name="phone" class="form-control" value="{{ old('phone', $crew->phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">States</label>
                <input name="states" class="form-control" value="{{ old('states', $crew->states) }}">
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('superadmin.crew.index') }}" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Crew</button>
        </div>
    </form>
</div>
@endsection
