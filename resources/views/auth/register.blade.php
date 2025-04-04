<x-guest-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div class="container py-5">
            <div class="text-center mb-5">
                <h5 class="fw-bold text-primary">We are here to solve your doubts</h5>
                <p class="text-muted">If you have any questions with the registration, you can call us or write in our online chat. We will gladly help you successfully complete your application.</p>
            </div>

            <!-- PERSONAL INFO -->
            <div class="mb-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-uppercase text-dark">Personal information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input id="phone" type="tel" name="phone" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Email</label>
                        <input type="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preferred Language</label>
                        <select name="language" class="form-select">
                            <option value="English" selected>English</option>
                            <option value="Spanish">Spanish</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                        <div class="mt-2">
                            <img id="photo_preview" src="{{ asset('assets/img/default-profile.png') }}" style="max-height: 120px;" class="rounded shadow">
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
                        <input type="text" name="company_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Years of Experience</label>
                        <input type="text" name="years_experience" class="form-control" placeholder="e.g. 2020">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Residential Roof Types</label>
                        <div class="row">
                            @foreach(['TPO', 'Low Slope', 'Tile', 'Wood Shakes', 'Asphalt Shingle', 'Metal'] as $roof)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="residential_roof_types[]" value="{{ $roof }}" id="res_{{ $roof }}">
                                        <label class="form-check-label" for="res_{{ $roof }}">Roofing {{ $roof }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Commercial Roof Types</label>
                        <div class="row">
                            @foreach(['EPDM', 'Asphalt Shingle', 'Low Slope', 'TPO', 'Tar & Gravel', 'Metal'] as $roof)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="commercial_roof_types[]" value="{{ $roof }}" id="com_{{ $roof }}">
                                        <label class="form-check-label" for="com_{{ $roof }}">Commercial {{ $roof }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">States You Can Work</label>
                        <input type="text" name="states_you_can_work" class="form-control" placeholder="e.g. Florida, Texas">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="all_states" id="all_states" class="form-check-input" value="1">
                            <label for="all_states" class="form-check-label">I can work in all states</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-uppercase text-dark">Create password</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
            </div>


            <!-- COMPANY DOCUMENTS -->
            <div class="mb-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-uppercase text-dark">Company Documents</h6>

                <div id="dropzone" class="border border-dashed rounded bg-light p-4 text-center">
                    <p class="text-muted mb-1">Drag & drop your files here, or</p>
                    <label class="btn btn-outline-primary btn-sm mb-2">
                        Browse Files
                        <input type="file" name="company_documents[]" class="d-none" id="fileInput" multiple>
                    </label>

                    <!-- Vista previa -->
                    <div id="fileList" class="row row-cols-1 row-cols-md-2 g-2 mt-3 text-start"></div>
                </div>

                @if (!empty($user->company_documents))
                    <div class="mt-4">
                        <strong class="d-block mb-2">Existing Uploaded Files:</strong>
                        <ul class="list-group list-group-flush">
                            @foreach ($user->company_documents as $doc)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ asset('storage/' . $doc) }}" target="_blank">{{ basename($doc) }}</a>
                                    <i class="bi bi-file-earmark-text"></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>




            <!-- SUBMIT -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Register</button>
            </div>
        </div>
    </form>


    {{-- Vista previa Documentos Compania --}}
    @push('scripts')
        <script>
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('fileInput');
            const fileList = document.getElementById('fileList');
            const dataTransfer = new DataTransfer();
        
            function renderFileList() {
                fileList.innerHTML = '';
                Array.from(dataTransfer.files).forEach((file, index) => {
                    const col = document.createElement('div');
                    col.className = 'col';
        
                    col.innerHTML = `
                        <div class="border rounded d-flex justify-content-between align-items-center p-2 bg-white shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text me-2 fs-4 text-primary"></i>
                                <span class="text-truncate" style="max-width: 200px;" title="${file.name}">
                                    ${file.name}
                                </span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFile(${index})">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    `;
        
                    fileList.appendChild(col);
                });
            }
        
            function removeFile(index) {
                const newDT = new DataTransfer();
                Array.from(dataTransfer.files).forEach((file, i) => {
                    if (i !== index) {
                        newDT.items.add(file);
                    }
                });
                dataTransfer.items.clear();
                Array.from(newDT.files).forEach(f => dataTransfer.items.add(f));
                fileInput.files = dataTransfer.files;
                renderFileList();
            }
        
            dropzone.addEventListener('dragover', e => {
                e.preventDefault();
                dropzone.classList.add('border-primary');
            });
        
            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-primary');
            });
        
            dropzone.addEventListener('drop', e => {
                e.preventDefault();
                dropzone.classList.remove('border-primary');
                for (let i = 0; i < e.dataTransfer.files.length; i++) {
                    dataTransfer.items.add(e.dataTransfer.files[i]);
                }
                fileInput.files = dataTransfer.files;
                renderFileList();
            });
        
            fileInput.addEventListener('change', () => {
                for (let i = 0; i < fileInput.files.length; i++) {
                    dataTransfer.items.add(fileInput.files[i]);
                }
                fileInput.files = dataTransfer.files;
                renderFileList();
            });
        </script>
    @endpush
    
    {{-- Vista previa Imagen --}}
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
                const input = event.target;
                const preview = document.getElementById('photo_preview');
        
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush

    {{-- Estilo Formulario --}}
    @push('css')
        <style>
            h3, h5 {
                font-weight: 700;
            }

            .card {
                border-radius: 12px;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.07);
                border: none;
            }

            .card-header {
                font-size: 1rem;
                font-weight: 600;
                text-transform: uppercase;
            }

            .form-label {
                font-weight: 500;
                color: #333;
            }

            .form-check-label {
                font-weight: 400;
                font-size: 0.92rem;
            }

            .btn-primary {
                background: linear-gradient(to right, #004A99, #007BFF);
                border: none;
            }

            .btn-primary:hover {
                background: linear-gradient(to right, #003a77, #0056b3);
            }

            #photo_preview {
                max-height: 120px;
                object-fit: cover;
                border-radius: 8px;
                border: 2px solid #ccc;
                background: #f9f9f9;
                padding: 4px;
            }

            @media (max-width: 768px) {
                #photo_preview {
                    margin-top: 10px;
                }
            }
        </style>
    @endpush

    


    
</x-guest-layout>
