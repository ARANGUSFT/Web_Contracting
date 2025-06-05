@extends('layouts.app')
@section('content')

<form action="{{ route('jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data" class="row g-4">
    @csrf
    @method('PUT')

    {{-- Información General --}}
    <div class="col-12"><h3>General Information</h3></div>
    <div class="col-md-4">
        <label>Installation Date</label>
        <input type="date" name="install_date_requested" value="{{ $job->install_date_requested }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Company Name</label>
        <input type="text" name="company_name" value="{{ $job->company_name }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Company Representative</label>
        <input type="text" name="company_rep" value="{{ $job->company_rep }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Company Representative Phone</label>
        <input type="text" name="company_rep_phone" value="{{ $job->company_rep_phone }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Company Representative Email</label>
        <input type="email" name="company_rep_email" value="{{ $job->company_rep_email }}" class="form-control">
    </div>
    

    {{-- Customer Information --}}
    <div class="col-12"><h3>Customer Information</h3></div>
    <div class="col-md-4">
        <label>First Name</label>
        <input type="text" name="customer_first_name" value="{{ $job->customer_first_name }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Last Name</label>
        <input type="text" name="customer_last_name" value="{{ $job->customer_last_name }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Phone Number</label>
        <input type="text" name="customer_phone_number" value="{{ $job->customer_phone_number }}" class="form-control">
    </div>


    {{-- Job Address --}}
    <div class="col-12"><h3>Job Address</h3></div>
    <div class="col-md-4">
        <label>Project Name/Number</label>
        <input type="text" name="job_number_name" value="{{ $job->job_number_name }}" class="form-control">
    </div>
    <div class="col-md-8">
        <label>Street Address</label>
        <input type="text" name="job_address_street_address" value="{{ $job->job_address_street_address }}" class="form-control">
    </div>
    <div class="col-md-12">
        <label>Street Address Line 2</label>
        <input type="text" name="job_address_street_address_line_2" value="{{ $job->job_address_street_address_line_2 }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>City</label>
        <input type="text" name="job_address_city" value="{{ $job->job_address_city }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>State</label>
        <input type="text" name="job_address_state" value="{{ $job->job_address_state }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Zip Code</label>
        <input type="text" name="job_address_zip_code" value="{{ $job->job_address_zip_code }}" class="form-control">
    </div>

   {{-- Material Ordered --}}
    <div class="col-12"><h3>Material Ordered</h3></div>
    <div class="col-md-6">
        <label>Roof Loaded</label>
        <input type="text" name="material_roof_loaded" value="{{ $job->material_roof_loaded }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Starter Bundles</label>
        <input type="text" name="starter_bundles_ordered" value="{{ $job->starter_bundles_ordered }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Hip and Ridge</label>
        <input type="text" name="hip_and_ridge_ordered" value="{{ $job->hip_and_ridge_ordered }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Field Shingles</label>
        <input type="text" name="field_shingle_bundles_ordered" value="{{ $job->field_shingle_bundles_ordered }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Modified Bitumen Cap Rolls</label>
        <input type="text" name="modified_bitumen_cap_rolls_ordered" value="{{ $job->modified_bitumen_cap_rolls_ordered }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Delivery Date</label>
        <input type="date" name="delivery_date" value="{{ $job->delivery_date }}" class="form-control">
    </div>

    {{-- Inspections and Replacements --}}
    <div class="col-12"><h3>Inspections and Replacements</h3></div>
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
            <div class="col-md-6">
                <label>{{ $label }}</label>
                <select name="{{ $field }}" class="form-select">
                    <option value="" {{ $job->$field === null ? 'selected' : '' }}>Select</option>
                    <option value="Yes" {{ $job->$field === 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $job->$field === 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>
    @endforeach

    {{-- Campo numérico separado --}}
    <div class="col-md-6">
        <label>Asphalt Shingle Layers to Remove</label>
        <input type="number" name="asphalt_shingle_layers_to_remove" value="{{ $job->asphalt_shingle_layers_to_remove }}" class="form-control" min="0">
    </div>


   {{-- Additional Information --}}
    <div class="col-12"><h3>Additional Information</h3></div>
    <div class="col-md-12">
        <label>Special Instructions</label>
        <textarea name="special_instructions" class="form-control">{{ $job->special_instructions }}</textarea>
    </div>
    <div class="col-md-12">
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


    {{-- Files --}}
    <div class="col-12"><h3>Files</h3></div>

    {{-- Aerial Measurement --}}
    <div class="col-md-12 mb-3">
        <label>Aerial Measurement</label>
        <input type="file" name="aerial_measurement[]" multiple class="form-control mb-2">
        @php
            $aerialRaw = $job->aerial_measurement ?? '[]';
            $aerialFiles = is_array($aerialRaw)
                ? $aerialRaw
                : (is_string($aerialRaw) ? json_decode($aerialRaw, true) : []);
        @endphp
        @if (!empty($aerialFiles) && is_array($aerialFiles))
            <ul class="list-group mt-2">
                @foreach ($aerialFiles as $index => $fileData)
                    @if (!empty($fileData['path']) && !empty($fileData['original_name']))
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ asset('storage/' . $fileData['path']) }}" target="_blank">{{ $fileData['original_name'] }}</a>
                            <button type="button"
                                    class="btn btn-sm btn-danger delete-file-btn"
                                    data-field="aerial_measurement"
                                    data-index="{{ $index }}">
                                Delete
                            </button>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>


    {{-- Material Order --}}
    <div class="col-md-12 mb-3">
        <label>Material Order</label>
        <input type="file" name="material_order[]" multiple class="form-control mb-2">
        @php
            $materialRaw = $job->material_order ?? '[]';
            $materialFiles = is_array($materialRaw)
                ? $materialRaw
                : (is_string($materialRaw) ? json_decode($materialRaw, true) : []);
        @endphp
        @if (!empty($materialFiles) && is_array($materialFiles))
            <ul class="list-group mt-2">
                @foreach ($materialFiles as $index => $fileData)
                    @if (!empty($fileData['path']) && !empty($fileData['original_name']))
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ asset('storage/' . $fileData['path']) }}" target="_blank">{{ $fileData['original_name'] }}</a>
                            <button type="button"
                                    class="btn btn-sm btn-danger delete-file-btn"
                                    data-field="material_order"
                                    data-index="{{ $index }}">
                                Delete
                            </button>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
    
    <div class="mb-3">
        <label class="form-label fw-semibold">Assign Team Members</label>
        <div class="row">
            @foreach($teamMembers as $member)
                <div class="col-md-6">
                    <div class="form-check mb-2">
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
    
    
    


    {{-- File Upload --}}
    <div class="col-md-12 mb-3">
        <label>File Upload</label>
        <input type="file" name="file_upload[]" multiple class="form-control mb-2">
        @php
            $uploadRaw = $job->file_upload ?? '[]';
            $uploadFiles = is_array($uploadRaw)
                ? $uploadRaw
                : (is_string($uploadRaw) ? json_decode($uploadRaw, true) : []);
        @endphp
        @if (!empty($uploadFiles) && is_array($uploadFiles))
            <ul class="list-group mt-2">
                @foreach ($uploadFiles as $index => $fileData)
                    @if (!empty($fileData['path']) && !empty($fileData['original_name']))
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ asset('storage/' . $fileData['path']) }}" target="_blank">{{ $fileData['original_name'] }}</a>
                            <button type="button"
                                    class="btn btn-sm btn-danger delete-file-btn"
                                    data-field="file_upload"
                                    data-index="{{ $index }}">
                                Delete
                            </button>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>




    <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary btn-lg">Actualizar Información</button>
    </div>
</form>



<style>
        form h3 {
            margin-top: 30px;
            margin-bottom: 15px;
            border-left: 5px solid #3498db;
            padding-left: 10px;
            color: #34495e;
            font-weight: 600;
        }
    
        form input[type="text"],
        form input[type="date"],
        form input[type="email"],
        form textarea,
        form input[type="file"] {
            margin-bottom: 20px;
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: #fafafa;
        }
    
        form input[type="text"]:focus,
        form input[type="date"]:focus,
        form input[type="email"]:focus,
        form textarea:focus,
        form input[type="file"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
            outline: none;
            background-color: #fff;
        }
    
        form input[type="checkbox"] {
            margin-right: 8px;
        }
    
        form label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }
    
        form button[type="submit"] {
            margin-top: 30px;
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #3498db, #2980b9);
            border: none;
            color: white;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
    
        form button[type="submit"]:hover {
            background: linear-gradient(45deg, #2980b9, #3498db);
            transform: translateY(-2px);
        }
    
        .error-border {
            border: 2px solid #e74c3c !important;
        }
    
        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: -10px;
            margin-bottom: 10px;
        }
</style>
    
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-file-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const jobId = @json($job->id);
                const field = this.dataset.field;
                const index = this.dataset.index;
                const listItem = this.closest('li');

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
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Deletion failed');
                        return response.json();
                    })
                    .then(data => {
                        listItem.remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'File has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Failed to delete the file.'
                        });
                    });
                });
            });
        });
    });
</script>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            let valid = true;
            const requiredFields = form.querySelectorAll('input[required], textarea[required]');

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
                    errorMsg.textContent = 'Este campo es obligatorio.';
                    errorMsg.classList.add('error-message');
                    field.parentNode.insertBefore(errorMsg, field.nextSibling);
                }
            });

            if (!valid) {
                e.preventDefault();
                window.scrollTo(0, form.offsetTop);
            }
        });
    });
</script>

    
@endsection