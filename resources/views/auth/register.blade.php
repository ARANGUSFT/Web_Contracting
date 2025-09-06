<x-guest-layout>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf
    
        {{-- MOSTRAR ERRORES DE VALIDACIÓN --}}
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
                        <input type="email" name="email_confirmation" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preferred Language</label>
                        <select name="language" class="form-select">
                            <option value="English" selected>English</option>
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
                        <label class="form-label">Years of Creation</label>
                        <input type="text" name="years_experience" class="form-control" placeholder="e.g. 2020">
                    </div>
    
                 
                    
                </div>
            </div>
    
            <!-- Company Documents Upload -->
            <div class="mt-4">
                <label class="form-label" for="company_documents">Upload</label>
                <input type="file" id="company_documents" class="form-control">
                <div id="uploaded_list" class="mt-3"></div>
                <!-- Este input oculto contendrá todos los archivos para enviarlos a Laravel -->
                <input type="file" id="hidden_documents" name="company_documents[]" multiple hidden>
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
    
            <!-- SUBMIT -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Register</button>
            </div>
        </div>
    </form>
    



    
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
                const reader = new FileReader();
                reader.onload = function () {
                    const output = document.getElementById('photo_preview');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            const companyInput = document.getElementById('company_documents');
            const uploadedList = document.getElementById('uploaded_list');
            const hiddenInput = document.getElementById('hidden_documents');
            let uploadedFiles = [];

            companyInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                uploadedFiles.push(file);
                updateHiddenInput();
                renderUploadedList();
                companyInput.value = ''; // Reinicia el input
            });

            function removeFile(index) {
                uploadedFiles.splice(index, 1);
                updateHiddenInput();
                renderUploadedList();
            }

            function renderUploadedList() {
                uploadedList.innerHTML = '';
                uploadedFiles.forEach((file, index) => {
                    const item = document.createElement('div');
                    item.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'border', 'p-2', 'mb-2');
                    item.innerHTML = `
                        <span>${file.name}</span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFile(${index})">Eliminar</button>
                    `;
                    uploadedList.appendChild(item);
                });
            }

            function updateHiddenInput() {
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                hiddenInput.files = dataTransfer.files;
            }

        </script>
    @endpush

    {{-- Estilo Form --}}
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
