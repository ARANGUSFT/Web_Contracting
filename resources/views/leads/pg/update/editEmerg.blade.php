@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('emergency.show', $emergency->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Details
        </a>
        
        <h3 class="text-primary m-0">Edit Emergency #{{ $emergency->id }}</h3>
    </div>

    <form method="POST" action="{{ route('emergency.update', $emergency->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- General Info --}}
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Date Submitted</label>
                <input type="date" name="date_submitted" class="form-control" value="{{ old('date_submitted', $emergency->date_submitted) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Type of Supplement</label>
                <input type="text" name="type_of_supplement" class="form-control" value="{{ old('type_of_supplement', $emergency->type_of_supplement) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $emergency->company_name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Contact Email</label>
                <input type="email" name="company_contact_email" class="form-control" value="{{ old('company_contact_email', $emergency->company_contact_email) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Job Number / Name</label>
                <input type="text" name="job_number_name" class="form-control" value="{{ old('job_number_name', $emergency->job_number_name) }}" required>
            </div>

            <div class="col-12">
                <label class="form-label">Job Address</label>
                <input type="text" name="job_address" class="form-control" value="{{ old('job_address', $emergency->job_address) }}" required>
            </div>

            <div class="col-12">
                <label class="form-label">Address Line 2</label>
                <input type="text" name="job_address_line2" class="form-control" value="{{ old('job_address_line2', $emergency->job_address_line2) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="job_city" class="form-control" value="{{ old('job_city', $emergency->job_city) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">State</label>
                <input type="text" name="job_state" class="form-control" value="{{ old('job_state', $emergency->job_state) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">ZIP Code</label>
                <input type="text" name="job_zip_code" class="form-control" value="{{ old('job_zip_code', $emergency->job_zip_code) }}" required>
            </div>
        </div>

        {{-- Switches --}}
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" name="terms_conditions" value="1" {{ $emergency->terms_conditions ? 'checked' : '' }}>
            <label class="form-check-label">Accept Terms</label>
        </div>

        <div class="form-check form-switch mt-2 mb-4">
            <input class="form-check-input" type="checkbox" name="requirements" value="1" {{ $emergency->requirements ? 'checked' : '' }}>
            <label class="form-check-label">Accept Requirements</label>
        </div>

        {{-- Team Members --}}
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-person-check-fill me-2"></i> Assign Team Members
            </div>
            <div class="card-body row">
                @foreach($teamMembers as $member)
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="assigned_team_members[]"
                                value="{{ $member->id }}"
                                id="member_{{ $member->id }}"
                                {{ $emergency->teamMembers->contains($member->id) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="member_{{ $member->id }}">
                                {{ $member->name }} <span class="text-muted">({{ ucfirst(str_replace('_', ' ', $member->role)) }})</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <hr>
        

    {{-- File Uploads --}}
     
    {{-- Campo oculto para archivos a eliminar --}}
    <input type="hidden" name="files_to_delete_json" id="filesToDeleteInput">

   {{-- Aerial Measurements --}}
<div class="mb-3">
    <label>Aerial Measurements</label>
    <input type="file" name="aerial_measurement[]" multiple class="form-control">
    @if (!empty($emergency->aerial_measurement_path) && is_array($emergency->aerial_measurement_path))
        <ul class="list-group mt-2">
            @foreach ($emergency->aerial_measurement_path as $file)
                @php
                    if (is_string($file) && Str::startsWith($file, '{')) $file = json_decode($file, true);
                    $filePath = is_array($file) ? ($file['path'] ?? '') : trim($file, '[]"');
                    $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank">{{ $fileName }}</a>
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            onclick="deleteFile('{{ $filePath }}', {{ $emergency->id }}, this)">
                        Delete
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>

{{-- Contract Upload --}}
<div class="mb-3">
    <label>Contract Upload</label>
    <input type="file" name="contract_upload[]" multiple class="form-control">
    @if (!empty($emergency->contract_upload_path) && is_array($emergency->contract_upload_path))
        <ul class="list-group mt-2">
            @foreach ($emergency->contract_upload_path as $file)
                @php
                    if (is_string($file) && Str::startsWith($file, '{')) $file = json_decode($file, true);
                    $filePath = is_array($file) ? ($file['path'] ?? '') : trim($file, '[]"');
                    $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank">{{ $fileName }}</a>
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            onclick="deleteFile('{{ $filePath }}', {{ $emergency->id }}, this)">
                        Delete
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>

{{-- File Pictures / Attachments --}}
<div class="mb-3">
    <label>File Pictures / Attachments</label>
    <input type="file" name="file_picture_upload[]" multiple class="form-control">
    @if (!empty($emergency->file_picture_upload_path) && is_array($emergency->file_picture_upload_path))
        <ul class="list-group mt-2">
            @foreach ($emergency->file_picture_upload_path as $file)
                @php
                    if (is_string($file) && Str::startsWith($file, '{')) $file = json_decode($file, true);
                    $filePath = is_array($file) ? ($file['path'] ?? '') : trim($file, '[]"');
                    $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank">{{ $fileName }}</a>
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            onclick="deleteFile('{{ $filePath }}', {{ $emergency->id }}, this)">
                        Delete
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>


        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    function deleteFile(filePath, emergencyId, btnElement) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This file will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('emergency.file.delete') }}", {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        file_path: filePath,
                        emergency_id: emergencyId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success');
                        btnElement.closest('li').remove();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Something went wrong.', 'error'));
            }
        });
    }
    </script>
    
@endsection
