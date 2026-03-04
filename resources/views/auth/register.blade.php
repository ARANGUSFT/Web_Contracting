<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Contracting Alliance Inc.</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo2.png') }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #003366;        /* Deep blue */
            --primary-light: #1a4d80;
            --primary-dark: #002244;
            --accent-color: #359bd6;
            --light-bg: #f0f4f8;
            --border-radius: 12px;
            --box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            color: #333;
            padding-bottom: 2rem;
        }
        
        .registration-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .registration-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .registration-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .registration-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .btn-outline-light {
            border-color: rgba(255,255,255,0.5);
            color: white;
        }
        .btn-outline-light:hover {
            background-color: white;
            color: var(--primary-color);
            border-color: white;
        }
        
        .registration-body {
            padding: 2rem;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-light);
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #444;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 51, 102, 0.15);
        }
        
        .profile-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e9ecef;
            padding: 4px;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
            background: #fafbfc;
            cursor: pointer;
        }
        
        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }
        
        .file-upload-area.required-empty {
            border-color: #dc3545;
            background: #fff5f5;
        }
        
        .upload-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .uploaded-file {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
        }
        
        .uploaded-file-name {
            flex-grow: 1;
            margin-right: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--primary-light));
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
        }
        
        .btn-outline-secondary {
            border-color: #ced4da;
            color: #6c757d;
        }
        .btn-outline-secondary:hover {
            background-color: #e9ecef;
            color: var(--primary-dark);
        }
        
        .alert-danger {
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
        }
        
        .progress-bar {
            background-color: var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .registration-container {
                margin: 1rem;
            }
            .registration-header {
                padding: 1.5rem;
            }
            .registration-body {
                padding: 1.5rem;
            }
            .profile-preview-container {
                text-align: center;
                margin-top: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .registration-header h1 {
                font-size: 1.75rem;
            }
            .btn-primary {
                width: 100%;
            }
        }
        
        /* Step progress */
        .step-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        .step-progress:before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }
        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #adb5bd;
            position: relative;
            z-index: 2;
        }
        .step.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        .step.completed {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        .step-label {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 0.5rem;
            white-space: nowrap;
            font-size: 0.75rem;
            color: #6c757d;
        }
        .step-progress-container {
            position: relative;
            padding: 0 2rem;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
        }
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        /* Validation */
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #198754 !important;
        }
        .invalid-feedback {
            display: none;
            font-size: 0.875em;
            color: #dc3545;
        }
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
        .form-label .required::after {
            content: '*';
            color: #dc3545;
            margin-left: 4px;
        }
        
        /* Password strength */
        .password-strength {
            height: 4px;
            margin-top: 5px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .password-weak { background-color: #dc3545; width: 25%; }
        .password-fair { background-color: #fd7e14; width: 50%; }
        .password-good { background-color: #ffc107; width: 75%; }
        .password-strong { background-color: #198754; width: 100%; }
        
        .file-requirement-text {
            font-size: 0.875rem;
            color: #dc3545;
            margin-top: 0.5rem;
            display: none;
        }
        .file-requirement-text.show {
            display: block;
        }
        
        /* Password toggle */
        .password-input-group {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .password-toggle:hover {
            color: var(--primary-color);
            background-color: #f8f9fa;
        }
        .password-input {
            padding-right: 45px !important;
        }
        .password-criteria {
            font-size: 0.8rem;
            margin-top: 5px;
        }
        .criteria-item {
            display: flex;
            align-items: center;
            margin-bottom: 2px;
        }
        .criteria-icon {
            margin-right: 5px;
            font-size: 0.7rem;
        }
        .criteria-valid {
            color: #198754;
        }
        .criteria-invalid {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="registration-header">
            @if (Route::has('login'))
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('login') }}"
                   class="btn btn-outline-light btn-sm rounded-pill fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Log in
                </a>
            </div>
            @endif

            <h1 class="mt-1">Create Your Account</h1>
            <p class="mb-0">Join our community and start your journey with us</p>

            <div class="mt-3">
                <a href="#" class="text-white-50 text-decoration-underline small" data-bs-toggle="modal" data-bs-target="#privacyModal">
                    Privacy Policy
                </a>
            </div>
        </div>

        <div class="registration-body">
            <!-- Step Progress -->
            <div class="step-progress-container">
                <div class="step-progress">
                    <div class="step active" id="step1">1<span class="step-label">Personal</span></div>
                    <div class="step" id="step2">2<span class="step-label">Company</span></div>
                    <div class="step" id="step3">3<span class="step-label">Security</span></div>
                </div>
            </div>

            <!-- ===== FORMULARIO EXACTAMENTE COMO LO DISTE ===== -->
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm" class="needs-validation" novalidate>
                @csrf
                
                {{-- MOSTRAR ERRORES DE VALIDACIÓN --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please check the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- STEP 1: PERSONAL INFO -->
                <div class="step-content active" id="stepContent1">
                    <h5 class="section-title">Personal Information</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="required"></span></label>
                            <input type="text" name="name" class="form-control" required minlength="2">
                            <div class="invalid-feedback">Please enter your first name (minimum 2 characters).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" minlength="2">
                            <div class="invalid-feedback">Last name must be at least 2 characters long.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">+1</span>
                                <input id="phone" type="tel" name="phone" class="form-control" placeholder="(555) 123-4567" pattern="\(\d{3}\) \d{3}-\d{4}">
                            </div>
                            <div class="form-text">US phone number format (10 digits)</div>
                            <div class="invalid-feedback">Please enter a valid US phone number in format (555) 123-4567.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Preferred Language</label>
                            <select name="language" class="form-select">
                                <option value="English" selected>English</option>
                             
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="required"></span></label>
                            <input type="email" name="email" class="form-control" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Email <span class="required"></span></label>
                            <input type="email" name="email_confirmation" class="form-control" required>
                            <div class="invalid-feedback">Please confirm your email address.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <div class="invalid-feedback">Please select a valid image file (JPG, PNG, GIF).</div>
                        </div>
                        <div class="col-md-6 profile-preview-container">
                            <div class="mt-4">
                                <img id="photo_preview" src="{{ asset('assets/img/default-profile.png') }}" class="profile-preview shadow">
                            </div>
                        </div>
                    </div>
                    
                    <div class="navigation-buttons">
                        <div></div> <!-- Empty div for spacing -->
                        <button type="button" class="btn btn-primary" onclick="validateStep(1)">Next</button>
                    </div>
                </div>
                
                <!-- STEP 2: COMPANY INFO -->
                <div class="step-content" id="stepContent2">
                    <h5 class="section-title">Company Information</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" minlength="2">
                            <div class="invalid-feedback">Company name must be at least 2 characters long.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year Established</label>
                            <input type="number" name="years_experience" class="form-control" placeholder="e.g. 2020" min="1900" max="2099">
                            <div class="invalid-feedback">Please enter a valid year between 1900 and 2099.</div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label">Company Documents <span class="required"></span></label>
                        <div class="file-upload-area" id="fileUploadArea" onclick="document.getElementById('company_documents').click()">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h6>Drag & Drop or Click to Upload</h6>
                            <p class="text-muted">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                            <p class="text-muted"><strong>At least one document is required</strong></p>
                        </div>
                        <input type="file" id="company_documents" class="d-none" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        
                        <div id="uploaded_list" class="mt-3"></div>
                        <input type="file" id="hidden_documents" name="company_documents[]" multiple hidden required>
                        <div class="file-requirement-text" id="fileRequirementText">
                            <i class="bi bi-exclamation-circle"></i> Please upload at least one company document
                        </div>
                        <div class="invalid-feedback" id="file-error">Please select valid files (PDF, DOC, DOCX, JPG, PNG) under 10MB each.</div>
                    </div>
                    
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">Back</button>
                        <button type="button" class="btn btn-primary" onclick="validateStep(2)">Next</button>
                    </div>
                </div>
                
                <!-- STEP 3: PASSWORD -->
                <div class="step-content" id="stepContent3">
                    <h5 class="section-title">Create Your Password</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="required"></span></label>
                            <div class="password-input-group">
                                <input type="password" name="password" id="password" class="form-control password-input" required minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="passwordStrength"></div>
                            
                            <!-- Password criteria -->
                            <div class="password-criteria mt-2">
                                <div class="criteria-item">
                                    <span class="criteria-icon" id="lengthIcon">○</span>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="criteria-item">
                                    <span class="criteria-icon" id="lowercaseIcon">○</span>
                                    <span>One lowercase letter</span>
                                </div>
                                <div class="criteria-item">
                                    <span class="criteria-icon" id="uppercaseIcon">○</span>
                                    <span>One uppercase letter</span>
                                </div>
                                <div class="criteria-item">
                                    <span class="criteria-icon" id="numberIcon">○</span>
                                    <span>One number</span>
                                </div>
                                <div class="criteria-item">
                                    <span class="criteria-icon" id="specialIcon">○</span>
                                    <span>One special character</span>
                                </div>
                            </div>
                            
                            <div class="invalid-feedback">Password must be at least 8 characters with uppercase, lowercase, number and special character.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password <span class="required"></span></label>
                            <div class="password-input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control password-input" required>
                                <button type="button" class="password-toggle" id="togglePasswordConfirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Passwords do not match.</div>
                        </div>
                    </div>
                    
                    <div class="navigation-buttons mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">Back</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Register</button>
                    </div>
                </div>
            </form>
            <!-- ===== FIN DEL FORMULARIO ===== -->
        </div>
    </div>

   <!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">Privacy Policy and Terms of Use</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- CONTENIDO COMPLETO DE POLÍTICA DE PRIVACIDAD Y TÉRMINOS -->
               <h6 class="fw-bold">PRIVACY POLICY</h6>
                <p><strong>Last updated:</strong> {{ date('F Y') }}</p>

                <p>
                Contracting Alliance Inc. ("Company", "we", "our", or "us") operates a digital platform designed to facilitate coordination, management, validation, and operational support for contractors and subcontractors within the roofing and construction industry.
                </p>

                <p>
                By registering, accessing, or using our platform, you consent to the data practices described in this Privacy Policy.
                </p>

                <h6 class="mt-4 fw-semibold">1. INFORMATION WE COLLECT</h6>

                <p>We may collect the following categories of information:</p>

                <ul>
                <li><strong>Personal Information:</strong> First name, last name, email address, phone number, language preference, profile photo.</li>
                <li><strong>Company Information:</strong> Company name, years of experience, uploaded documents, insurance certificates, licenses.</li>
                <li><strong>Tax & Compliance Information:</strong> W9 forms, EIN, and related tax documentation where applicable.</li>
                <li><strong>Account Credentials:</strong> Encrypted password and authentication data.</li>
                <li><strong>Platform Activity:</strong> Login history, approval status, internal communications, uploaded files.</li>
                <li><strong>Technical Information:</strong> IP address, browser type, device information, access logs.</li>
                </ul>

                <h6 class="mt-4 fw-semibold">2. HOW WE USE YOUR INFORMATION</h6>

                <ul>
                <li>To verify identity and company credentials.</li>
                <li>To manually approve or reject account registrations.</li>
                <li>To manage contractor and subcontractor relationships.</li>
                <li>To maintain system security and prevent fraud.</li>
                <li>To comply with legal, regulatory, and tax obligations.</li>
                <li>To improve platform performance and services.</li>
                </ul>

                <p>We do not sell personal information.</p>

                <h6 class="mt-4 fw-semibold">3. DATA STORAGE AND SECURITY</h6>

                <p>
                Data is hosted through HostGator servers. We implement reasonable administrative, technical, and security safeguards including authentication controls, encrypted password storage, role-based access restrictions, and activity monitoring.
                </p>

                <p>
                However, no electronic transmission or storage system is completely secure, and we cannot guarantee absolute security.
                </p>

                <h6 class="mt-4 fw-semibold">4. CALIFORNIA RESIDENTS (CCPA NOTICE)</h6>

                <p>If you are a California resident, you have the right to:</p>

                <ul>
                <li>Request access to the personal information we collect.</li>
                <li>Request deletion of personal data (subject to legal exceptions).</li>
                <li>Know what categories of information are collected.</li>
                <li>Confirm that your data is not sold.</li>
                <li>Request non-discriminatory treatment for exercising your rights.</li>
                </ul>

                <p>
                To exercise your rights, contact us at:
                <strong>infocontractingalliance@gmail.com</strong>
                </p>

                <h6 class="mt-4 fw-semibold">5. DATA RETENTION</h6>

                <p>
                We retain information while accounts remain active and as necessary for legal compliance, dispute resolution, tax reporting, and legitimate business interests.
                </p>

                <h6 class="mt-4 fw-semibold">6. ACCOUNT APPROVAL</h6>

                <p>
                Contractor accounts require manual approval. Subcontractor accounts may be created by administrators or submitted for review. We reserve sole discretion to approve, deny, suspend, or terminate accounts.
                </p>

                <h6 class="mt-4 fw-semibold">7. CHILDREN'S PRIVACY</h6>

                <p>
                Our platform is not intended for individuals under the age of 18.
                </p>

                <h6 class="mt-4 fw-semibold">8. POLICY CHANGES</h6>

                <p>
                We may update this Privacy Policy at any time. Continued use of the platform constitutes acceptance of any changes.
                </p>

                <hr class="my-4">

                <h6 class="fw-bold">TERMS OF USE</h6>

                <p>
                By accessing or using this platform, you agree to be legally bound by the following Terms of Use.
                </p>

                <h6 class="mt-4 fw-semibold">1. PLATFORM PURPOSE</h6>

                <p>
                Contracting Alliance Inc. provides a digital coordination and management platform for contractors and subcontractors within the construction industry.
                </p>

                <p>
                The Company is not an employer, staffing agency, or labor broker unless explicitly stated in a separate written agreement.
                </p>

                <h6 class="mt-4 fw-semibold">2. INDEPENDENT CONTRACTOR STATUS</h6>

                <p>
                Users of the platform acknowledge and agree that all contractors and subcontractors operate as independent contractors. Nothing in this platform creates an employment relationship, joint venture, partnership, or agency relationship.
                </p>

                <h6 class="mt-4 fw-semibold">3. ACCOUNT RESPONSIBILITY</h6>

                <p>
                You are responsible for maintaining confidentiality of your login credentials and for all activities under your account.
                </p>

                <h6 class="mt-4 fw-semibold">4. ACCEPTABLE USE</h6>

                <p>You agree not to:</p>

                <ul>
                <li>Provide false or misleading information.</li>
                <li>Upload fraudulent documentation.</li>
                <li>Attempt unauthorized access to the system.</li>
                <li>Use the platform for unlawful purposes.</li>
                </ul>

                <h6 class="mt-4 fw-semibold">5. LIMITATION OF LIABILITY</h6>

                <p>
                To the fullest extent permitted by law, Contracting Alliance Inc. shall not be liable for indirect, incidental, consequential, punitive, or special damages, including lost profits, lost business opportunities, or disputes between contractors and subcontractors.
                </p>

                <h6 class="mt-4 fw-semibold">6. TERMINATION</h6>

                <p>
                We reserve the right to suspend or terminate accounts at our sole discretion for violations of these Terms.
                </p>

                <h6 class="mt-4 fw-semibold">7. GOVERNING LAW</h6>

                <p>
                These Terms shall be governed by and construed in accordance with the laws of the State of Florida, without regard to conflict of law principles.
                </p>

                <h6 class="mt-4 fw-semibold">8. CONTACT INFORMATION</h6>

                <p>
                Contracting Alliance Inc.<br>
                Email: <strong>infocontractingalliance@gmail.com</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
    // ============================================
    // REGISTRATION VALIDATION - VERSIÓN CORREGIDA
    // ============================================

    // --- Variables globales para archivos ---
    const companyInput = document.getElementById('company_documents');
    const uploadedList = document.getElementById('uploaded_list');
    const hiddenInput = document.getElementById('hidden_documents');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileRequirementText = document.getElementById('fileRequirementText');
    let uploadedFiles = [];

    // --- REGLAS DE VALIDACIÓN (SOLO LOS CAMPOS QUE EXISTEN) ---
    const validationRules = {
        step1: {
            name: { required: true, minLength: 2 },
            last_name: { required: false, minLength: 2 }, // No requerido, pero si tiene contenido debe tener ≥2
            phone: { required: false, pattern: /^\(\d{3}\) \d{3}-\d{4}$/ },
            email: { required: true, type: 'email' },
            email_confirmation: { required: true, match: 'email' }
            // language no tiene reglas
            // profile_photo no tiene reglas
        },
        step2: {
            company_name: { required: false, minLength: 2 },
            years_experience: { required: false, min: 1900, max: 2099 },
            // company_documents se valida aparte en validateStep
        },
        step3: {
            password: { required: true, minLength: 8, pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/ },
            password_confirmation: { required: true, match: 'password' }
        }
    };

    // --- SweetAlert2 helper ---
    const showAlert = (icon, title, text, confirmButtonText = 'OK') => {
        return Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: confirmButtonText,
            confirmButtonColor: '#1362ac' // Mantén el color que prefieras
        });
    };

    // --- Formato de teléfono USA ---
    function formatUSPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 10) value = value.substring(0, 10);
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/(\d{0,3})/, '($1');
        }
        input.value = value;
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function getCurrentStep() {
        const activeStep = document.querySelector('.step-content.active');
        return activeStep ? parseInt(activeStep.id.replace('stepContent', '')) : 1;
    }

    // --- Validación de campo individual ---
    function validateField(field) {
        const value = field.value ? field.value.trim() : '';
        const fieldName = field.name;
        const currentStep = getCurrentStep();
        const rules = validationRules[`step${currentStep}`]?.[fieldName];
        
        if (!rules) return true; // Campos sin reglas se consideran válidos

        // Requerido
        if (rules.required && !value) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }

        // Si el campo no es requerido y está vacío, se considera válido (sin marcar)
        if (!rules.required && !value) {
            field.classList.remove('is-invalid', 'is-valid');
            return true;
        }

        // Longitud mínima (solo si tiene valor)
        if (rules.minLength && value.length < rules.minLength) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }

        // Tipo email
        if (rules.type === 'email' && !isValidEmail(value)) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }

        // Patrón (regex)
        if (rules.pattern && !rules.pattern.test(value)) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }

        // Coincidir con otro campo (confirmación)
        if (rules.match) {
            const originalField = document.querySelector(`[name="${rules.match}"]`);
            if (originalField && originalField.value !== value) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                return false;
            }
        }

        // Rango numérico
        if (rules.min !== undefined && parseInt(value) < rules.min) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }
        if (rules.max !== undefined && parseInt(value) > rules.max) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            return false;
        }

        // Si pasó todo, marcar como válido
        field.classList.add('is-valid');
        field.classList.remove('is-invalid');
        return true;
    }

    // --- Validación de paso completo ---
    function validateStep(stepNumber) {
        const stepContent = document.getElementById(`stepContent${stepNumber}`);
        const inputs = stepContent.querySelectorAll('input, select, textarea');
        let isValid = true;
        let firstInvalid = null;

        // Limpiar estados previos
        inputs.forEach(input => input.classList.remove('is-invalid', 'is-valid'));

        // Validación especial para archivos en paso 2
        if (stepNumber === 2) {
            if (uploadedFiles.length === 0) {
                fileUploadArea?.classList.add('required-empty');
                fileRequirementText?.classList.add('show');
                showAlert('warning', 'Documents Required', 'Please upload at least one company document to continue.');
                return false;
            } else {
                fileUploadArea?.classList.remove('required-empty');
                fileRequirementText?.classList.remove('show');
            }
        }

        // Validar cada campo del paso
        inputs.forEach(input => {
            if (input.name !== 'company_documents') { // El input file oculto no se valida aquí
                if (!validateField(input)) {
                    isValid = false;
                    if (!firstInvalid) firstInvalid = input;
                }
            }
        });

        if (!isValid) {
            showAlert('warning', 'Validation Error', 'Please check all required fields and fix any errors before proceeding.');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        // Si todo bien, pasar al siguiente paso
        nextStep(stepNumber);
        return true;
    }

    // --- Navegación entre pasos ---
    function nextStep(currentStep) {
        document.getElementById('step' + currentStep)?.classList.remove('active');
        document.getElementById('step' + currentStep)?.classList.add('completed');
        document.getElementById('stepContent' + currentStep)?.classList.remove('active');
        showStep(currentStep + 1);
    }

    function prevStep(currentStep) {
        document.getElementById('step' + currentStep)?.classList.remove('active');
        document.getElementById('stepContent' + currentStep)?.classList.remove('active');
        showStep(currentStep - 1);
    }

    function showStep(stepNum) {
        document.getElementById('step' + stepNum)?.classList.add('active');
        document.getElementById('stepContent' + stepNum)?.classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // --- Password toggle ---
    function togglePasswordVisibility(inputId, toggleButtonId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(toggleButtonId);
        if (!passwordInput || !toggleButton) return;
        const icon = toggleButton.querySelector('i');
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('bi-eye', !isPassword);
        icon.classList.toggle('bi-eye-slash', isPassword);
    }

    // --- Fortaleza de contraseña ---
    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('passwordStrength');
        if (!strengthBar) return;
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        strengthBar.className = 'password-strength';
        if (password.length === 0) {
            strengthBar.style.width = '0%';
        } else if (strength <= 2) {
            strengthBar.classList.add('password-weak');
        } else if (strength === 3) {
            strengthBar.classList.add('password-fair');
        } else if (strength === 4) {
            strengthBar.classList.add('password-good');
        } else {
            strengthBar.classList.add('password-strong');
        }
    }

    function updatePasswordCriteria(password) {
        const criteria = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        Object.keys(criteria).forEach(key => {
            const icon = document.getElementById(`${key}Icon`);
            if (icon) {
                icon.textContent = criteria[key] ? '✓' : '○';
                icon.className = criteria[key] ? 'criteria-icon criteria-valid' : 'criteria-icon criteria-invalid';
            }
        });
    }

    // --- Previsualización de imagen ---
    window.previewImage = function(event) {
        const file = event.target.files[0];
        if (!file) return;
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            showAlert('error', 'Invalid File Type', 'Please select a valid image file (JPG, PNG, GIF).');
            event.target.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showAlert('error', 'File Too Large', 'Please select an image smaller than 5MB.');
            event.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('photo_preview');
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    };

    // --- Manejo de archivos (carga múltiple) ---
    if (companyInput) {
        companyInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (!files || files.length === 0) return;
            let hasValidFiles = false;
            for (let i = 0; i < files.length; i++) {
                if (files[i].size > 10 * 1024 * 1024) {
                    showAlert('warning', 'File Too Large', `"${files[i].name}" exceeds the 10MB size limit. Please choose a smaller file.`);
                    continue;
                }
                uploadedFiles.push(files[i]);
                hasValidFiles = true;
            }
            if (hasValidFiles) {
                updateHiddenInput();
                renderUploadedList();
                fileUploadArea?.classList.remove('required-empty');
                fileRequirementText?.classList.remove('show');
            }
            companyInput.value = '';
        });
    }

    window.removeFile = function(index) {
        uploadedFiles.splice(index, 1);
        updateHiddenInput();
        renderUploadedList();
        if (uploadedFiles.length === 0) {
            fileUploadArea?.classList.add('required-empty');
            fileRequirementText?.classList.add('show');
        }
    };

    function renderUploadedList() {
        if (!uploadedList) return;
        uploadedList.innerHTML = '';
        if (uploadedFiles.length === 0) {
            uploadedList.innerHTML = '<p class="text-muted">No files uploaded yet.</p>';
            return;
        }
        uploadedFiles.forEach((file, index) => {
            const item = document.createElement('div');
            item.classList.add('uploaded-file');
            item.innerHTML = `
                <div class="uploaded-file-name">
                    <i class="bi bi-file-earmark me-2"></i>${file.name}
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeFile(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            uploadedList.appendChild(item);
        });
    }

    function updateHiddenInput() {
        if (!hiddenInput) return;
        const dataTransfer = new DataTransfer();
        uploadedFiles.forEach(file => dataTransfer.items.add(file));
        hiddenInput.files = dataTransfer.files;
    }

    // --- Drag & drop ---
    if (fileUploadArea) {
        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.style.borderColor = 'var(--primary-color)';
            fileUploadArea.style.backgroundColor = '#e8eeff';
        });
        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.style.borderColor = '#dee2e6';
            fileUploadArea.style.backgroundColor = '#fafbfc';
        });
        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.style.borderColor = '#dee2e6';
            fileUploadArea.style.backgroundColor = '#fafbfc';
            const files = e.dataTransfer.files;
            if (files.length > 0 && companyInput) {
                companyInput.files = files;
                companyInput.dispatchEvent(new Event('change'));
            }
        });
    }

    // --- Inicialización al cargar DOM ---
    document.addEventListener('DOMContentLoaded', function() {
        // Formato de teléfono
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                formatUSPhoneNumber(e.target);
                validateField(e.target);
            });
        }

        // Toggle contraseñas
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            togglePasswordVisibility('password', 'togglePassword');
        });
        document.getElementById('togglePasswordConfirmation')?.addEventListener('click', function() {
            togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation');
        });

        // Validación en tiempo real
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', function() { validateField(this); });
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'is-valid');
                if (this.name === 'password') {
                    checkPasswordStrength(this.value);
                    updatePasswordCriteria(this.value);
                }
                if (this.name === 'password_confirmation' || this.name === 'email_confirmation') {
                    validateField(this);
                }
            });
        });

        // Control de fortaleza de contraseña
        const passwordField = document.getElementById('password');
        if (passwordField) {
            passwordField.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                updatePasswordCriteria(this.value);
            });
        }

        // Validación al enviar el formulario
        const form = document.getElementById('registrationForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Validar que haya archivos en step 2
                if (uploadedFiles.length === 0) {
                    e.preventDefault();
                    showAlert('error', 'Documents Required', 'Please upload at least one company document before submitting.');
                    showStep(2);
                    fileUploadArea?.classList.add('required-empty');
                    fileRequirementText?.classList.add('show');
                    fileUploadArea?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                // Validar todos los campos de todos los pasos
                let allValid = true;
                for (let step = 1; step <= 3; step++) {
                    const stepContent = document.getElementById(`stepContent${step}`);
                    if (stepContent) {
                        const inputs = stepContent.querySelectorAll('input, select, textarea');
                        inputs.forEach(input => {
                            if (input.name !== 'company_documents' && !validateField(input)) {
                                allValid = false;
                            }
                        });
                    }
                }

                if (!allValid) {
                    e.preventDefault();
                    showAlert('error', 'Form Incomplete', 'Please complete all required fields in all steps before submitting.');
                    // Ir al primer paso con errores
                    for (let step = 1; step <= 3; step++) {
                        const stepContent = document.getElementById(`stepContent${step}`);
                        const invalid = stepContent?.querySelector('.is-invalid');
                        if (invalid) {
                            showStep(step);
                            invalid.focus();
                            break;
                        }
                    }
                }
            });
        }

        // Renderizar lista de archivos si hay alguno
        renderUploadedList();
    });
</script>
</body>
</html>