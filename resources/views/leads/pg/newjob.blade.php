@extends('layouts.app')

@section('content')
<div class="container-lg py-5">
    <div class="max-w-6xl mx-auto bg-white shadow-xl rounded-3xl p-6">

        <div class="card-header bg-primary text-white text-center position-relative py-4 rounded-top">
            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm position-absolute top-0 end-0 m-3">
                &larr; Back
            </a>
            <img src="https://www.jotform.com/uploads/fredysanchezc1980/form_files/IMG_7040.663336b07e6656.75204432.jpeg" alt="Form Logo" class="img-fluid mb-2" width="100">
            <h5 class="mb-0">CONTRACTING ALLIANCE</h5>
            <small class="d-block">YOUR BUSINESS PARTNER FOR SUCCESS</small>
            <h6 class="mt-2">Job Request Form</h6>
        </div><br>


        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf


                <!-- Información General -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-semibold fs-5">
                        General information
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Install Date Requested *</label>
                                <input type="date" class="form-control" name="install_date_requested" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Company Name *</label>
                                <input readonly type="text" class="form-control bg-light" name="company_name" value="{{ $user->company_name }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Company Rep *</label>
                                <input type="text" class="form-control" name="company_rep" placeholder="Representative name" required>
                            </div>
                            

                            <div class="col-md-6">
                                <label class="form-label">Rep Phone Number *</label>
                                <input class="form-control" name="company_rep_phone" value="{{ $user->phone }}" required>
                            </div>

                            <input type="hidden" name="company_rep_email" value="{{ $user->email }}">
                        </div>
                    </div>
                </div>

                <!-- Información del Cliente -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-semibold fs-5">
                        Customer Information
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input class="form-control" name="customer_first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input class="form-control" name="customer_last_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input class="form-control" name="customer_phone_number" required>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Dirección del Trabajo -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-semibold fs-5">
                        Labor Directorate
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Job Number / Name *</label>
                                <input class="form-control" name="job_number_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Street Address *</label>
                                <input class="form-control" name="job_address_street_address" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">City *</label>
                                <input class="form-control" name="job_address_city" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State *</label>
                                <select class="form-select" name="job_address_state" required>
                                    <option value="" disabled selected>Select state</option>
                                    <option value="TX">Texas</option>
                                    <option value="FL">Florida</option>
                                    <option value="CA">California</option>
                                    <!-- Más estados -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Zip Code *</label>
                                <input class="form-control" type="number" name="job_address_zip_code" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles de Materiales -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-semibold fs-5">
                        Materials Details
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Material Roof Loaded *</label>
                                <select class="form-select" name="material_roof_loaded" required>
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Delivery Date</label>
                                <input type="date" class="form-control" name="delivery_date">
                            </div>
                            

                            <div class="col-md-6">
                                <label class="form-label">Starter Bundles Ordered</label>
                                <input class="form-control" type="number" name="starter_bundles_ordered" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hip and Ridge Ordered</label>
                                <input class="form-control" type="number" name="hip_and_ridge_ordered" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Field Shingle Bundles Ordered</label>
                                <input class="form-control" type="number" name="field_shingle_bundles_ordered" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modified Bitumen Cap Rolls Ordered</label>
                                <input class="form-control" type="number" name="modified_bitumen_cap_rolls_ordered" min="0">
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Inspecciones y Sustituciones -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-semibold fs-5">
                        Inspections and Substitutions
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Mid Roof Inspection</label>
                                <select class="form-select" name="mid_roof_inspection">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Siding Being Replaced</label>
                                <select class="form-select" name="siding_being_replaced">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Asphalt Shingle Layers to Remove</label>
                                <select class="form-select" name="asphalt_shingle_layers_to_remove">
                                    <option disabled selected>Select</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Re Deck</label>
                                <select class="form-select" name="re_deck">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Skylights Replace</label>
                                <select class="form-select" name="skylights_replace">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gutter Remove</label>
                                <select class="form-select" name="gutter_remove">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gutter Detached and Reset</label>
                                <select class="form-select" name="gutter_detached_and_reset">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Satellite Remove</label>
                                <select class="form-select" name="satellite_remove">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Satellite Goes in the Trash</label>
                                <select class="form-select" name="satellite_goes_in_the_trash">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Open Soffit Ceiling</label>
                                <select class="form-select" name="open_soffit_ceiling">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Detached Garage Roof</label>
                                <select class="form-select" name="detached_garage_roof">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Detached Shed Roof</label>
                                <select class="form-select" name="detached_shed_roof">
                                    <option disabled selected>Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expectativas y Notificaciones -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-primary text-white fw-semibold fs-5">
                            Expectations and Notifications
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label">Special Instructions</label>
                                    <textarea class="form-control" name="special_instructions" rows="3" placeholder="Any specific notes or expectations..."></textarea>
                                </div>

                                <!-- Material Verification -->
                                <div class="col-12">
                                    <input type="hidden" name="material_verification" value="0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="material_verification" id="material_verification" value="1">
                                        <label class="form-check-label" for="material_verification">
                                            I understand it is my company's responsibility to alert Contracting Alliance the night before construction if materials are not on site.
                                        </label>
                                    </div>
                                </div>

                                <!-- Stop Work Request -->
                                <div class="col-12">
                                    <input type="hidden" name="stop_work_request" value="0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="stop_work_request" id="stop_work_request" value="1">
                                        <label class="form-check-label" for="stop_work_request">
                                            Our company is obligated to notify Contracting Alliance by 4:00 PM Central Time on the day prior to any scheduled construction if the project is to be put on hold.
                                        </label>
                                    </div>
                                </div>

                                <!-- Documentation Attachment -->
                                <div class="col-12">
                                    <input type="hidden" name="documentationattachment" value="0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="documentationattachment" id="documentationattachment" value="1">
                                        <label class="form-check-label" for="documentationattachment">
                                            Aerial measurement, material order, and photos are required. If not included, this can delay your build.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

         





                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-semibold fs-5">
                        Attached files
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Aerial Measurement</label>
                                <input type="file" class="form-control" name="aerial_measurement" accept=".pdf,image/*">
                            </div>
                
                            <div class="col-md-6">
                                <label class="form-label">Material Order</label>
                                <input type="file" class="form-control" name="material_order" accept=".pdf,image/*">
                            </div>
                
                            <div class="col-md-6">
                                <label class="form-label">Other Files (Permit / SOL / etc)</label>
                                <input type="file" class="form-control" name="file_upload" accept=".pdf,image/*">
                            </div>
                        </div>
                    </div>
                </div>
                


                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">Submit Job Request</button>
                </div>


        </form>

    </div> 

</div>

{{-- Estilos adicionales --}}
<style>
    .form-control,
    .form-select {
        border-radius: 0.5rem;
        font-size: 0.95rem;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
    }
    .form-label {
        font-weight: 600;
    }
    .form-check-input {
        margin-right: 0.5rem;
    }
    .form-check-label {
        font-size: 0.95rem;
    }

    .card-header {
        background-color: #0d6efd;
        color: white;
        padding: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .card-header img {
        border-radius: 50%;
        margin-bottom: 1rem;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .preview-file {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f1f3f5;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 8px 12px;
        margin-bottom: 6px;
        font-size: 0.95rem;
        transition: background-color 0.2s ease;
    }

    .preview-file:hover {
        background-color: #e2e6ea;
    }

    .preview-file span {
        flex-grow: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .preview-file button {
        background-color: transparent;
        border: none;
        color: #dc3545;
        font-size: 1rem;
        padding: 0 8px;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .preview-file button:hover {
        color: #a71d2a;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', () => {
                const label = input.previousElementSibling;
                const fileName = input.files.length > 0 ? input.files[0].name : 'No file selected';
                label.innerHTML += ` <small class="text-muted d-block">${fileName}</small>`;
            });
        });

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const requiredFields = form.querySelectorAll('[required]');
                let firstInvalid = null;
                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        if (!firstInvalid) firstInvalid = field;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                if (firstInvalid) {
                    e.preventDefault();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    });
</script>
    
@endsection