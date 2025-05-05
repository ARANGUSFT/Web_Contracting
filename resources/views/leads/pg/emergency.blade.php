@extends('layouts.app')

@section('content')


<div class="container py-5">
    <div class="card shadow-lg">

        <div class="card-header bg-primary text-white position-relative text-center">
            <a href="{{ route('leads.index') }}" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2">
                ← Back
            </a>
            <img src="https://www.jotform.com/uploads/fredysanchezc1980/form_files/IMG_7040.663336b07e6656.75204432.jpeg" alt="Form Logo" class="img-fluid mb-2" width="100">
            <h5 class="mb-0">CONTRACTING ALLIANCE</h5>
            <small>YOUR BUSINESS PARTNER FOR SUCCESS</small>
            <h6 class="mt-2">Supplement Request Form</h6>
        </div>
        
    
        <div class="card-body">
            <form action="{{ route('emergency.store') }}" method="POST" enctype="multipart/form-data" id="emergencyForm">
                @csrf

                <div class="row">

                    <div class="card-header bg-primary text-white fw-semibold fs-5">
                        New Job
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_submitted" class="form-label">Date Submitted *</label>
                        <input required type="date" class="form-control" id="date_submitted" name="date_submitted">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="type_of_supplement" class="form-label">Type of Supplement *</label>
                        <select required class="form-select" id="type_of_supplement" name="type_of_supplement">
                            <option value="" disabled selected>Select option</option>
                            <option value="Initial supplement">Initial supplement</option>
                            <option value="Final Supplement">Final Supplement</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="company_name" class="form-label">Company Name *</label>
                        <input readonly type="text" class="form-control" id="company_name" name="company_name" value="{{ $user->company_name ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="company_contact_email" class="form-label">Company Contact Email *</label>
                        <input required type="email" class="form-control" id="company_contact_email"
                            name="company_contact_email">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="job_number_name" class="form-label">Job Number / Name *</label>
                        <input required type="text" class="form-control" id="job_number_name" name="job_number_name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="job_address" class="form-label">Job Address *</label>
                        <input required type="text" class="form-control" id="job_address" name="job_address">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="job_address_line2" class="form-label">Street Address Line 2</label>
                        <input type="text" class="form-control" id="job_address_line2" name="job_address_line2">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="job_city" class="form-label">City *</label>
                        <input required type="text" class="form-control" id="job_city" name="job_city">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="job_state" class="form-label">State *</label>
                        <input required type="text" class="form-control" id="job_state" name="job_state">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="job_zip_code" class="form-label">Zip Code *</label>
                        <input required type="number" class="form-control" id="job_zip_code" name="job_zip_code">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input required type="checkbox" class="form-check-input" id="terms_conditions"
                        name="terms_conditions">
                    <label class="form-check-label" for="terms_conditions">
                        I understand it is my company's responsibility to submit the supplement.
                        Contracting Alliance will create document on your behalf.
                    </label>
                </div>

                <div class="form-check mb-4">
                    <input required type="checkbox" class="form-check-input" id="requirements" name="requirements">
                    <label class="form-check-label" for="requirements">
                        I understand that the speed and accuracy of the supplement is based on the information provided,
                        failure to provide pictures and/or contracts.
                    </label>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <label class="form-label">Aerial Measurement (PDF/JPG/PNG) *</label>
                                <input type="file" class="form-control" name="aerial_measurement[]" multiple
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    onchange="previewFiles(event, 'aerialPreview')">
                                <div id="aerialPreview" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <label class="form-label">Contract Upload (PDF/JPG/PNG) *</label>
                                <input type="file" class="form-control" name="contract_upload[]" multiple
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    onchange="previewFiles(event, 'contractPreview')">
                                <div id="contractPreview" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <label class="form-label">File Picture Upload (Opcional)</label>
                                <input type="file" class="form-control" name="file_picture_upload[]" multiple
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    onchange="previewFiles(event, 'picturePreview')">
                                <div id="picturePreview" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">Submit</button>
                </div>
            </form>
        </div>

    </div>
</div>



<style>
    
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
    const fileMap = {
        aerial_measurement: [],
        contract_upload: [],
        file_picture_upload: []
    };

    function previewFiles(event, previewId) {
        const inputName = event.target.name.replace('[]', '');
        const files = Array.from(event.target.files);
        fileMap[inputName] = fileMap[inputName].concat(files);
        updatePreview(inputName, previewId, event.target);
    }

    function removeFile(event, index, inputName, previewId) {
        event.preventDefault();
        fileMap[inputName].splice(index, 1);
        updatePreview(inputName, previewId);
    }

    function updatePreview(inputName, previewId, input = null) {
        const container = document.getElementById(previewId);
        container.innerHTML = '';

        fileMap[inputName].forEach((file, i) => {
            const div = document.createElement('div');
            div.className = 'preview-file';
            div.innerHTML = `
                <span>${file.name}</span>
                <button type="button" onclick="removeFile(event, ${i}, '${inputName}', '${previewId}')">✖</button>
            `;
            container.appendChild(div);
        });

        if (input) {
            const dt = new DataTransfer();
            fileMap[inputName].forEach(f => dt.items.add(f));
            input.files = dt.files;
        }
    }
</script>




    
@endsection
