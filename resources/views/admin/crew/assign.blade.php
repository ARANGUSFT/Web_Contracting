@extends('admin.layouts.superadmin')
@section('title', 'Assign Subcontractors to Crew')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4">Assign Subcontractors to "{{ $crew->name }}"</h1>

    <form method="POST" action="{{ route('superadmin.crew.assign.store', $crew->id) }}">
        @csrf

        <div class="mb-4">
            <label class="form-label">Select Subcontractors</label>
            <select name="subcontractors[]" class="form-select" multiple style="height: 300px;">
                @foreach ($subcontractors as $sub)
                    <option value="{{ $sub->id }}" {{ $crew->subcontractors->contains($sub->id) ? 'selected' : '' }}>
                        {{ $sub->name }} {{ $sub->last_name }} - {{ $sub->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('superadmin.crew.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Assign
            </button>
        </div>
    </form>
</div>
@endsection
