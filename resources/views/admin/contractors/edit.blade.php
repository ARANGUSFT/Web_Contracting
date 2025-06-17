@extends('admin.layouts.superadmin')

@section('title', 'Edit Contractor: ' . $user->name)

@section('content')
<div class="container-fluid px-4">
    
    <ol class="breadcrumb bg-light px-3 py-2 rounded shadow-sm mb-4">
        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.users.contractors') }}">
                <i class="fas fa-arrow-left me-1"></i> Back to Contractors
            </a>
        </li>
    </ol>

    
    <form method="POST" action="{{ route('superadmin.contractors.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user me-1"></i>
                        Personal Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name*</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email*</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <div class="mt-2">
                                    <img id="photo_preview"
                                    src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('assets/img/default-profile.png') }}"
                                    style="max-height: 120px;" class="rounded shadow">
                                                       </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Preferred Language</label>
                                <select name="language" class="form-select">
                                    <option value="English" {{ $user->language == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Spanish" {{ $user->language == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-building me-1"></i>
                        Company Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Company Name*</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Years of Experience</label>
                                <input type="number" name="years_experience" value="{{ old('years_experience', $user->years_experience) }}" class="form-control" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-tools me-1"></i>
                        Services & Coverage
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Residential Roof Types -->
                            <div class="col-md-6">
                                <label class="form-label">Residential Roof Types</label>
                                <div class="bg-light p-3 rounded">
                                    @php
                                        $residential = old('residential_roof_types', $user->residential_roof_types ?? []);
                                        $residential = is_array($residential) ? $residential : json_decode($residential, true) ?? [];
                                    @endphp
                                    @foreach(['TPO', 'Low Slope', 'Tile', 'Wood Shakes', 'Asphalt Shingle', 'Metal'] as $roof)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="residential_roof_types[]" 
                                                   value="{{ $roof }}" id="res_{{ $roof }}" {{ in_array($roof, $residential) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="res_{{ $roof }}">{{ $roof }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Commercial Roof Types -->
                            <div class="col-md-6">
                                <label class="form-label">Commercial Roof Types</label>
                                <div class="bg-light p-3 rounded">
                                    @php
                                        $commercial = old('commercial_roof_types', $user->commercial_roof_types ?? []);
                                        $commercial = is_array($commercial) ? $commercial : json_decode($commercial, true) ?? [];
                                    @endphp
                                    @foreach(['EPDM', 'Asphalt Shingle', 'Low Slope', 'TPO', 'Tar & Gravel', 'Metal'] as $roof)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="commercial_roof_types[]" 
                                                   value="{{ $roof }}" id="com_{{ $roof }}" {{ in_array($roof, $commercial) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="com_{{ $roof }}">{{ $roof }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- States -->
                            <div class="col-md-6">
                                <label class="form-label">States You Can Work</label>
                                @php
                                    $states = old('states_you_can_work', $user->states_you_can_work ?? []);
                                    $states = is_array($states) ? $states : json_decode($states, true) ?? [];
                                @endphp
                                <select name="states_you_can_work[]" class="form-select" multiple>
                                    @foreach([
                                        'Texas', 'Florida', 'California', 'New York', 'Illinois',
                                        'Arizona', 'Nevada', 'Colorado', 'Georgia', 'North Carolina'
                                    ] as $state)
                                        <option value="{{ $state }}" {{ in_array($state, $states) ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple states</small>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="all_states" id="all_states" class="form-check-input" value="1"
                                        {{ old('all_states', $user->all_states ?? false) ? 'checked' : '' }}>
                                    <label for="all_states" class="form-check-label">I can work in all states</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-file-contract me-1"></i>
                        Company Documents
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload New Documents</label>
                            <input type="file" id="company_documents" name="company_documents[]" multiple class="form-control">
                            <small class="text-muted">Max file size: 5MB each. Accepted: PDF, JPG, PNG, DOC, XLS</small>
                        </div>

                        @if(!empty($user->company_documents) && is_array($user->company_documents))
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document</th>
                                            <th>Type</th>
                                            <th>Uploaded</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->company_documents as $index => $doc)
                                            @php
                                                $file = is_array($doc) ? $doc : ['file_name' => $doc, 'original_name' => basename($doc)];
                                                $filename = basename($file['file_name']);
                                                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                                $iconClass = $iconsByExtension[$extension] ?? $iconsByExtension['default'];
                                            @endphp
                                            <tr>
                                                <td>
                                                    <i class="bi {{ $iconClass }} me-2"></i>
                                                    <a href="{{ asset('storage/' . $file['file_name']) }}" target="_blank" class="text-decoration-none">
                                                        {{ $file['original_name'] }}
                                                    </a>
                                                </td>
                                                <td>{{ strtoupper($extension) }}</td>
                                                <td>{{ isset($file['uploaded_at']) ? \Carbon\Carbon::parse($file['uploaded_at'])->format('m/d/Y') : 'N/A' }}</td>
                                                <td class="text-end">
                                                    <a href="{{ asset('storage/' . $file['file_name']) }}" download class="btn btn-sm btn-outline-primary me-1">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteDocument({{ $index }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i> No documents uploaded yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body text-center d-flex flex-wrap justify-content-center gap-3">
    
                <a href="{{ route('superadmin.users.contractors') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-arrow-left me-2"></i> Cancel
                </a>
    
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i> Update Contractor
                </button>
            </div>
        </div>

    </form>



    <form id="toggleActiveForm" action="{{ route('superadmin.contractors.toggle-active', $user->id) }}" method="POST" class="text-center mb-5">
        @csrf
        @method('PATCH')
        <button type="button" class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }} px-4"
            onclick="confirmToggleActive()">
            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} me-1"></i>
            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
        </button>
    </form>
    

    

</div>



@endsection


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            min-height: 38px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
        }
    </style>



<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Initialize phone input
        const phoneInput = document.querySelector("#phone");
        if (phoneInput) {
            window.intlTelInput(phoneInput, {
                initialCountry: "auto",
                geoIpLookup: function(callback) {
                    fetch('https://ipinfo.io/json')
                        .then(response => response.json())
                        .then(data => callback(data.country))
                        .catch(() => callback('us'));
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
            });
        }

        // Initialize Select2 for states
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: "Select states",
                allowClear: true
            });

            // Toggle all states checkbox
            $('#all_states').change(function() {
                const statesSelect = $('select[name="states_you_can_work[]"]');
                if (this.checked) {
                    statesSelect.find('option').prop('selected', true);
                    statesSelect.trigger('change');
                }
                statesSelect.prop('disabled', this.checked);
            });

            // Initialize the disabled state on load
            if ($('#all_states').is(':checked')) {
                $('select[name="states_you_can_work[]"]').prop('disabled', true);
            }
        });

        // Image preview
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('photo_preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Document deletion confirmation
        function confirmDeleteDocument(index) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This document will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = "{{ route('superadmin.contractors.documents.delete', ['user' => $user->id, 'index' => 'INDEX']) }}"
                            .replace('INDEX', index);
                        form.style.display = 'none';

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = "{{ csrf_token() }}";
                        form.appendChild(csrf);

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(method);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }


    </script>

{{-- Alert is state contractors --}}
    <script>
        function confirmToggleActive() {
            Swal.fire({
                title: '{{ $user->is_active ? 'Deactivate Contractor?' : 'Activate Contractor?' }}',
                text: '{{ $user->is_active ? 'This contractor will be deactivated and will no longer have access.' : 'This contractor will be reactivated and gain access again.' }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '{{ $user->is_active ? '#d33' : '#198754' }}',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ $user->is_active ? 'Yes, deactivate' : 'Yes, activate' }}',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('toggleActiveForm').submit();
                }
            });
        }
    </script>

