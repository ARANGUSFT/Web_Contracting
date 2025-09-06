@extends('layouts.app')
@section('content')

  <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #7f8c8d;
            --success-color: #27ae60;
            --info-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-shadow: 0 0.125rem 0.625rem rgba(0, 0, 0, 0.08);
            --border-color: #e0e0e0;
        }
        
        body {
            background-color: #f9fafb;
            padding: 20px 0 40px;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 25px;
            background-color: #fff;
        }
        
        .card-header {
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            background-color: #f8f9fa;
            color: var(--primary-color);
            padding: 15px 20px;
        }
        
        .btn {
            border-radius: 4px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1a2530;
            border-color: #1a2530;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        
        .btn-outline-secondary {
            border: 1px solid var(--secondary-color);
            color: var(--secondary-color);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }
        
        .form-control, .form-select, .form-check-input {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 10px 12px;
            transition: border-color 0.2s;
        }
        
        .form-control:focus, .form-select:focus, .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.1);
        }
        
        .file-list {
            margin-top: 15px;
        }
        
        .file-item {
            background-color: var(--light-bg);
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-color);
        }
        
        .team-member-check {
            margin-bottom: 12px;
        }
        
        .submit-btn {
            padding: 12px 24px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .error-border {
            border: 1px solid var(--danger-color) !important;
        }
        
        .error-message {
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .section-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .checkbox-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .form-check {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Job Details
            </a>
        </div>

        <form action="{{ route('jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> General Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Installation Date</label>
                            <input type="date" name="install_date_requested" 
                                value="{{ $job->install_date_requested ? \Carbon\Carbon::parse($job->install_date_requested)->format('Y-m-d') : '' }}" 
                                class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Company Name</label>
                            <div class="readonly-field form-control">
                                {{ $job->company_name }}
                                <input type="hidden" name="company_name" value="{{ $job->company_name }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Company Representative</label>
                            <input type="text" name="company_rep" value="{{ $job->company_rep }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Representative Phone</label>
                            <input type="text" name="company_rep_phone" value="{{ $job->company_rep_phone }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Representative Email</label>
                            <input type="email" name="company_rep_email" value="{{ $job->company_rep_email }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i> Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="customer_first_name" value="{{ $job->customer_first_name }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="customer_last_name" value="{{ $job->customer_last_name }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="customer_phone_number" value="{{ $job->customer_phone_number }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Address -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Job Address</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Project Name/Number</label>
                            <input type="text" name="job_number_name" value="{{ $job->job_number_name }}" class="form-control">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="job_address_street_address" value="{{ $job->job_address_street_address }}" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Street Address Line 2</label>
                            <input type="text" name="job_address_street_address_line_2" value="{{ $job->job_address_street_address_line_2 }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="job_address_city" value="{{ $job->job_address_city }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="job_address_state" value="{{ $job->job_address_state }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Zip Code</label>
                            <input type="text" name="job_address_zip_code" value="{{ $job->job_address_zip_code }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Material Ordered -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Material Ordered</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Roof Loaded</label>
                            <input type="text" name="material_roof_loaded" value="{{ $job->material_roof_loaded }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Starter Bundles</label>
                            <input type="text" name="starter_bundles_ordered" value="{{ $job->starter_bundles_ordered }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hip and Ridge</label>
                            <input type="text" name="hip_and_ridge_ordered" value="{{ $job->hip_and_ridge_ordered }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Field Shingles</label>
                            <input type="text" name="field_shingle_bundles_ordered" value="{{ $job->field_shingle_bundles_ordered }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modified Bitumen Cap Rolls</label>
                            <input type="text" name="modified_bitumen_cap_rolls_ordered" value="{{ $job->modified_bitumen_cap_rolls_ordered }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Delivery Date</label>
                            <input type="date" name="delivery_date" 
                                value="{{ $job->delivery_date ? \Carbon\Carbon::parse($job->delivery_date)->format('Y-m-d') : '' }}" 
                                class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inspections and Replacements -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i> Inspections and Replacements</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ([
                            'mid_roof_inspection' => 'Mid-Roof Inspection',
                            'siding_being_replaced' => 'Siding Being Replaced',
                            're_deck' => 'Re-Deck',
                            'skylights_replace' => 'Skylights Replacement',
                            'gutter_remove' => 'Gutter Removal',
                            'gutter_detached_and_reset' => 'Gutter Detached and Reset',
                            'satellite_remove' => 'Satellite Removal',
                            'satellite_goes_in_the_trash' => 'Satellite Goes in the Trash',
                            'open_soffit_ceiling' => 'Open Soffit Ceiling',
                            'detached_garage_roof' => 'Detached Garage Roof',
                            'detached_shed_roof' => 'Detached Shed Roof'
                        ] as $field => $label)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ $label }}</label>
                                <select name="{{ $field }}" class="form-select">
                                    <option value="" {{ $job->$field === null ? 'selected' : '' }}>Select</option>
                                    <option value="Yes" {{ $job->$field === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ $job->$field === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        @endforeach
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Asphalt Shingle Layers to Remove</label>
                            <input type="number" name="asphalt_shingle_layers_to_remove" value="{{ $job->asphalt_shingle_layers_to_remove }}" class="form-control" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-square me-2"></i> Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Special Instructions</label>
                        <textarea name="special_instructions" class="form-control" rows="4">{{ $job->special_instructions }}</textarea>
                    </div>
                    <div class="checkbox-group">
                        <div class="form-check">
                            <input type="checkbox" name="material_verification" value="1" {{ $job->material_verification ? 'checked' : '' }} class="form-check-input">
                            <label class="form-check-label">Material Verification</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="stop_work_request" value="1" {{ $job->stop_work_request ? 'checked' : '' }} class="form-check-input">
                            <label class="form-check-label">Stop Work Request</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="documentationattachment" value="1" {{ $job->documentationattachment ? 'checked' : '' }} class="form-check-input">
                            <label class="form-check-label">Documentation Attached</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i> Assign Team Members</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($teamMembers as $member)
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="assigned_team_members[]" 
                                           value="{{ $member->id }}"
                                           id="team_member_{{ $member->id }}"
                                           {{ $job->teamMembers->contains($member->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="team_member_{{ $member->id }}">
                                        {{ $member->name }}
                                        <small class="text-muted">({{ ucfirst(str_replace('_', ' ', $member->role)) }})</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Files -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-files me-2"></i> Files</h5>
                </div>
                <div class="card-body">
                    <!-- Aerial Measurement -->
                    <div class="mb-4">
                        <label class="form-label">Aerial Measurement</label>
                        <input type="file" name="aerial_measurement[]" multiple class="form-control mb-2">
                        @php
                            $aerialRaw = $job->aerial_measurement ?? '[]';
                            $aerialFiles = is_array($aerialRaw)
                                ? $aerialRaw
                                : (is_string($aerialRaw) ? json_decode($aerialRaw, true) : []);
                        @endphp
                        @if (!empty($aerialFiles) && is_array($aerialFiles))
                            <div class="file-list">
                                @foreach ($aerialFiles as $index => $fileData)
                                    @if (!empty($fileData['path']) && !empty($fileData['original_name']))
                                        <div class="file-item">
                                            <a href="{{ asset('storage/' . $fileData['path']) }}" target="_blank">{{ $fileData['original_name'] }}</a>
                                            <button type="button" class="btn btn-sm btn-danger delete-file-btn"
                                                    data-field="aerial_measurement"
                                                    data-index="{{ $index }}">
                                                Delete
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Material Order -->
                    <div class="mb-4">
                        <label class="form-label">Material Order</label>
                        <input type="file" name="material_order[]" multiple class="form-control mb-2">
                        @php
                            $materialRaw = $job->material_order ?? '[]';
                            $materialFiles = is_array($materialRaw)
                                ? $materialRaw
                                : (is_string($materialRaw) ? json_decode($materialRaw, true) : []);
                        @endphp
                        @if (!empty($materialFiles) && is_array($materialFiles))
                            <div class="file-list">
                                @foreach ($materialFiles as $index => $fileData)
                                    @if (!empty($fileData['path']) && !empty($fileData['original_name']))
                                        <div class="file-item">
                                            <a href="{{ asset('storage/' . $fileData['path']) }}" target="_blank">{{ $fileData['original_name'] }}</a>
                                            <button type="button" class="btn btn-sm btn-danger delete-file-btn"
                                                    data-field="material_order"
                                                    data-index="{{ $index }}">
                                                Delete
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="form-label">File Upload</label>
                        <input type="file" name="file_upload[]" multiple class="form-control mb-2">
                        @php
                            $uploadRaw = $job->file_upload ?? '[]';
                            $uploadFiles = is_array($uploadRaw)
                                ? $uploadRaw
                                : (is_string($uploadRaw) ? json_decode($uploadRaw, true) : []);
                        @endphp
                        @if (!empty($uploadFiles) && is_array($uploadFiles))
                            <div class="file-list">
                                @foreach ($uploadFiles as $index => $fileData)
                                    @if (!empty($fileData['path']) && !empty($fileData['original_name']))
                                        <div class="file-item">
                            <a href="{{ asset('storage/' . $fileData['path']) }}" target="_blank">{{ $fileData['original_name'] }}</a>
                            <button type="button"
                                    class="btn btn-sm btn-danger delete-file-btn"
                                    data-field="file_upload"
                                    data-index="{{ $index }}">
                                Delete
                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary submit-btn">Update Job Information</button>
            </div>
        </form>
    </div>

        
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-file-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const jobId = @json($job->id);
            const field = this.dataset.field;
            const index = this.dataset.index;
            const fileItem = this.closest('.file-item');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(`/jobs/${jobId}/files/${field}/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.error || 'Deletion failed') });
                    }
                    return response.json();
                })
                .then(data => {
                    fileItem.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'File has been deleted.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: err.message || 'Failed to delete the file.'
                    });
                });
            });
        });
    });

    // Validación del formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        let valid = true;
        const requiredFields = form.querySelectorAll('[required]');

        // Limpiar errores previos
        requiredFields.forEach(field => {
            field.classList.remove('error-border');
            const errorMessage = field.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.remove();
            }
        });

        // Validar campos
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('error-border');

                const errorMsg = document.createElement('div');
                errorMsg.textContent = 'This field is required.';
                errorMsg.classList.add('error-message');
                field.parentNode.insertBefore(errorMsg, field.nextSibling);
            }
        });

        if (!valid) {
            e.preventDefault();
            const firstError = form.querySelector('.error-border');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
});
</script>

    
@endsection