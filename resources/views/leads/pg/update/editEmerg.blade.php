@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('emergency.show', $emergency->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Details
        </a>
        
        <h3 class="text-primary m-0">Edit Emergency #{{ $emergency->id }}</h3>
    </div>

    <form method="POST" action="{{ route('emergency.update', $emergency->id) }}" enctype="multipart/form-data" class="bg-white rounded shadow-sm p-4">
        @csrf
        @method('PUT')

        {{-- General Info Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>General Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date Submitted</label>
                        <input
                            type="date"
                            name="date_submitted"
                            class="form-control rounded"
                            value="{{ old('date_submitted', optional($emergency->date_submitted)->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Type of Supplement</label>
                        <input type="text" name="type_of_supplement" class="form-control rounded" value="{{ old('type_of_supplement', $emergency->type_of_supplement) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Company Name</label>
                        <input type="text" name="company_name" class="form-control rounded" 
                            value="{{ old('company_name', $emergency->company_name) }}" 
                            readonly required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Email</label>
                        <input type="email" name="company_contact_email" class="form-control rounded" value="{{ old('company_contact_email', $emergency->company_contact_email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Job Number / Name</label>
                        <input type="text" name="job_number_name" class="form-control rounded" value="{{ old('job_number_name', $emergency->job_number_name) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Job Address</label>
                        <input type="text" name="job_address" class="form-control rounded" value="{{ old('job_address', $emergency->job_address) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Address Line 2</label>
                        <input type="text" name="job_address_line2" class="form-control rounded" value="{{ old('job_address_line2', $emergency->job_address_line2) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="job_city" class="form-control rounded" value="{{ old('job_city', $emergency->job_city) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" name="job_state" class="form-control rounded" value="{{ old('job_state', $emergency->job_state) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ZIP Code</label>
                        <input type="text" name="job_zip_code" class="form-control rounded" value="{{ old('job_zip_code', $emergency->job_zip_code) }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Switches --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-check2-circle me-2"></i>Acceptance</h5>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="terms_conditions" value="1" id="terms_conditions" {{ $emergency->terms_conditions ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="terms_conditions">Accept Terms</label>
                </div>

                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" name="requirements" value="1" id="requirements" {{ $emergency->requirements ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="requirements">Accept Requirements</label>
                </div>
            </div>
        </div>

        {{-- Team Members --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Assign Team Members</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($teamMembers as $member)
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="assigned_team_members[]"
                                    value="{{ $member->id }}"
                                    id="member_{{ $member->id }}"
                                    {{ $emergency->teamMembers->contains($member->id) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="member_{{ $member->id }}">
                                    {{ $member->name }} <span class="text-muted small">({{ ucfirst(str_replace('_', ' ', $member->role)) }})</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- File Uploads --}}
        <input type="hidden" name="files_to_delete_json" id="filesToDeleteInput">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-paperclip me-2"></i>File Attachments</h5>
            </div>
            <div class="card-body">
                {{-- Aerial Measurements --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Aerial Measurements</label>
                    <input type="file" name="aerial_measurement[]" multiple class="form-control rounded">
                    @if (!empty($emergency->aerial_measurement_path) && is_array($emergency->aerial_measurement_path))
                        <div class="mt-2">
                            @foreach ($emergency->aerial_measurement_path as $file)
                                @php
                                    if (is_string($file) && Str::startsWith($file, '{')) $file = json_decode($file, true);
                                    $filePath = is_array($file) ? ($file['path'] ?? '') : trim($file, '[]"');
                                    $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                @endphp
                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-1">
                                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-file-earmark me-1"></i> {{ $fileName }}
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteFile('{{ $filePath }}', {{ $emergency->id }}, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Contract Upload --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Contract Upload</label>
                    <input type="file" name="contract_upload[]" multiple class="form-control rounded">
                    @if (!empty($emergency->contract_upload_path) && is_array($emergency->contract_upload_path))
                        <div class="mt-2">
                            @foreach ($emergency->contract_upload_path as $file)
                                @php
                                    if (is_string($file) && Str::startsWith($file, '{')) $file = json_decode($file, true);
                                    $filePath = is_array($file) ? ($file['path'] ?? '') : trim($file, '[]"');
                                    $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                @endphp
                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-1">
                                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-file-earmark me-1"></i> {{ $fileName }}
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteFile('{{ $filePath }}', {{ $emergency->id }}, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- File Pictures / Attachments --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">File Pictures / Attachments</label>
                    <input type="file" name="file_picture_upload[]" multiple class="form-control rounded">
                    @if (!empty($emergency->file_picture_upload_path) && is_array($emergency->file_picture_upload_path))
                        <div class="mt-2">
                            @foreach ($emergency->file_picture_upload_path as $file)
                                @php
                                    if (is_string($file) && Str::startsWith($file, '{')) $file = json_decode($file, true);
                                    $filePath = is_array($file) ? ($file['path'] ?? '') : trim($file, '[]"');
                                    $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                @endphp
                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-1">
                                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-file-earmark me-1"></i> {{ $fileName }}
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteFile('{{ $filePath }}', {{ $emergency->id }}, this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                <i class="bi bi-save me-2"></i> Save Changes
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
                        btnElement.closest('div.d-flex').remove();
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