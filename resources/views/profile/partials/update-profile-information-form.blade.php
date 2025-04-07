

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
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

        <div class="container py-5">
            <h3 class="text-center mb-5">Update Your Profile</h3>

            <!-- PERSONAL INFO -->
            <div class="mb-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-uppercase text-dark">Personal information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preferred Language</label>
                        <select name="language" class="form-select">
                            <option value="English" {{ $user->language == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Spanish" {{ $user->language == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                        </select>
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
                </div>
            </div>

            <!-- COMPANY INFO -->
            <div class="mb-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-uppercase text-dark">Company info</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Years of Experience</label>
                        <input type="text" name="years_experience" value="{{ old('years_experience', $user->years_experience) }}" class="form-control">
                    </div>

                    <!-- RESIDENTIAL -->
                    <div class="col-md-6">
                        <label class="form-label">Residential Roof Types</label>
                        <div class="row">
                            @php
                                $residential = old('residential_roof_types', $user->residential_roof_types ?? []);
                                $residential = is_array($residential) ? $residential : json_decode($residential, true) ?? [];
                            @endphp
                            @foreach(['TPO', 'Low Slope', 'Tile', 'Wood Shakes', 'Asphalt Shingle', 'Metal'] as $roof)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="residential_roof_types[]" value="{{ $roof }}" id="res_{{ $roof }}" {{ in_array($roof, $residential) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="res_{{ $roof }}">Roofing {{ $roof }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- COMMERCIAL -->
                    <div class="col-md-6">
                        <label class="form-label">Commercial Roof Types</label>
                        <div class="row">
                            @php
                                $commercial = old('commercial_roof_types', $user->commercial_roof_types ?? []);
                                $commercial = is_array($commercial) ? $commercial : json_decode($commercial, true) ?? [];
                            @endphp
                            @foreach(['EPDM', 'Asphalt Shingle', 'Low Slope', 'TPO', 'Tar & Gravel', 'Metal'] as $roof)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="commercial_roof_types[]" value="{{ $roof }}" id="com_{{ $roof }}" {{ in_array($roof, $commercial) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="com_{{ $roof }}">Commercial {{ $roof }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- STATES -->
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


            <!-- DOCUMENTS UPLOAD -->
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-uppercase text-dark fw-bold">Company Documents</h6>
                </div>
                <div class="card-body">
                    <label class="form-label" for="company_documents">Upload New Documents (optional)</label>
                    <input type="file" id="company_documents" name="company_documents[]" multiple class="form-control">
                </div>
            </div>


    



            <!-- SUBMIT -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Update</button>
            </div>
        </div>

    </form>
    

    @php
            $iconsByExtension = [
                'pdf' => 'bi-file-earmark-pdf text-danger',
                'xls' => 'bi-file-earmark-spreadsheet text-success',
                'xlsx' => 'bi-file-earmark-spreadsheet text-success',
                'doc' => 'bi-file-earmark-word text-primary',
                'docx' => 'bi-file-earmark-word text-primary',
                'jpg' => 'bi-file-earmark-image text-info',
                'jpeg' => 'bi-file-earmark-image text-info',
                'png' => 'bi-file-earmark-image text-info',
                'default' => 'bi-file-earmark text-secondary',
            ];
    @endphp

    @if(!empty($user->company_documents) && is_array($user->company_documents))
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-uppercase text-dark fw-bold">Uploaded Documents</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0">
                    <thead class="table-light">
                        <tr>
                            <th>Document</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->company_documents as $index => $doc)
                        @php
                            // Si es string viejo, convertirlo a estructura nueva con solo file_name
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
                            <td class="text-end">
                                <a href="{{ asset('storage/' . $file['file_name']) }}" download class="btn btn-sm btn-outline-primary me-2">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('company-documents.delete', $index) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-secondary mt-4">
            No documents uploaded yet.
        </div>
    @endif





    @push('scripts')
        <script>
            const input = document.querySelector("#phone");
            window.intlTelInput(input, {
                initialCountry: "auto",
                geoIpLookup: function(callback) {
                    fetch('https://ipinfo.io/json')
                        .then(response => response.json())
                        .then(data => callback(data.country))
                        .catch(() => callback('us'));
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
            });

            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function () {
                    const output = document.getElementById('photo_preview');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
    @endpush

