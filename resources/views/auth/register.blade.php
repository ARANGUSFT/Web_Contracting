<x-guest-layout>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
    <style>
        :root {
            --primary-color: #1362ac;
            --secondary-color: #359bd6;
            --accent-color: #099db7;
            --light-bg: #f8f9fa;
            --border-radius: 12px;
            --box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .registration-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .registration-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .registration-body {
            padding: 2rem;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
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
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
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
            background: #f8f9ff;
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
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .alert-danger {
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
        }
        
        .progress-bar {
            background-color: var(--primary-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .registration-container {
                margin: 1rem;
                border-radius: var(--border-radius);
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
            
            .registration-header p {
                font-size: 0.9rem;
            }
            
            .section-title {
                font-size: 1.25rem;
            }
            
            .btn-primary {
                width: 100%;
            }
        }
        
        /* Step progress indicator */
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

        
             {{-- Enlace que abre el modal de Privacy Policy --}}
        <div class="mt-3">
            <a href="#"
               class="text-white-50 text-decoration-underline small"
               data-bs-toggle="modal"
               data-bs-target="#privacyModal">
               Privacy Policy
            </a>
        </div>
    </div>

        
        <div class="registration-body">
            <!-- Step Progress Indicator -->
            <div class="step-progress-container">
                <div class="step-progress">
                    <div class="step active" id="step1">
                        1
                        <span class="step-label">Personal</span>
                    </div>
                    <div class="step" id="step2">
                        2
                        <span class="step-label">Company</span>
                    </div>
                    <div class="step" id="step3">
                        3
                        <span class="step-label">Security</span>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
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
                            <label class="form-label">First Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input id="phone" type="tel" name="phone" class="form-control">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Preferred Language</label>
                            <select name="language" class="form-select">
                                <option value="English" selected>English</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Email *</label>
                            <input type="email" name="email_confirmation" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                        </div>
                        <div class="col-md-6 profile-preview-container">
                            <div class="mt-4">
                                <img id="photo_preview" src="{{ asset('assets/img/default-profile.png') }}" class="profile-preview shadow">
                            </div>
                        </div>
                    </div>
                    
                    <div class="navigation-buttons">
                        <div></div> <!-- Empty div for spacing -->
                        <button type="button" class="btn btn-primary" onclick="nextStep(1)">Next</button>
                    </div>
                </div>
                
                <!-- STEP 2: COMPANY INFO -->
                <div class="step-content" id="stepContent2">
                    <h5 class="section-title">Company Information</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year Established</label>
                            <input type="number" name="years_experience" class="form-control" placeholder="e.g. 2020" min="1900" max="2099">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label">Company Documents</label>
                        <div class="file-upload-area" onclick="document.getElementById('company_documents').click()">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h6>Drag & Drop or Click to Upload</h6>
                            <p class="text-muted">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                        </div>
                        <input type="file" id="company_documents" class="d-none" multiple>
                        
                        <div id="uploaded_list" class="mt-3"></div>
                        <input type="file" id="hidden_documents" name="company_documents[]" multiple hidden>
                    </div>
                    
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">Back</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next</button>
                    </div>
                </div>
                
                <!-- STEP 3: PASSWORD -->
                <div class="step-content" id="stepContent3">
                    <h5 class="section-title">Create Your Password</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="form-text">Use at least 8 characters with a mix of letters, numbers & symbols</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="terms_agree" required>
                        <label class="form-check-label" for="terms_agree">
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <div class="navigation-buttons mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">Back</button>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal: Privacy Policy -->
    <!-- Modal de Política de Privacidad -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        {{-- Aquí puedes cargar contenido estático o dinámico --}}
                     <p>By using or accessing this website, you acknowledge and accept the Privacy Policy set forth below. If you do not agree to this policy, please do not use this website. Contracting Alliance may revise this policy at any time by updating this publication, and your use after such change means that you accept the modified terms. Please check this policy periodically for changes. This policy is intended to help you understand how Contracting Alliance collects, uses, and protects the information you provide on our website. This Privacy Policy does not apply to the use or disclosure of information that we collect or obtain through means other than this website.</p>

                        <p>
                            <strong>Personal Information.</strong> If you browse our website, you can generally do so anonymously without providing any personal information. However, there are cases where we collect personal data; you will know that this data is collected because you will have to fill out a form. 
                            <br><br>
                            We collect personally identifiable and transactional information when you: 
                            <br>(1) purchase products or services from our site; 
                            <br>(2) register for an account; 
                            <br>(3) request to receive additional information about our products and services; 
                            <br>(4) subscribe to our newsletter; 
                            <br>(5) send us a question. 
                            <br><br>
                            If you choose not to provide the information we request, you can still visit most of our website, but you may not be able to access certain options, offers, and services. 
                            <br><br>
                            In the event that you change your mind or wish to update or delete your personal information, we will do our best to correct, update, or delete the personal data you provide to us. You can do this by contacting us at the contact points specified in the contact section.
                        </p>

                        <p><strong>Orders.</strong> When you place an order for a product, we need to know your contact/shipping information, including email address. This information is necessary for us to process and complete your order and send you an order confirmation, as well as to notify you of the status of your order.</p>

                        <p><strong>Marketing Messages.</strong> When you purchase products from us online, request product information, or provide us with personal information, we may place you on our contact list. We may send you direct mail and emails related to our company, our products or services, special offers, or important matters. If you do not wish to receive these messages, you can use the opt-out method detailed in the message, reply to any emails indicating that you do not wish to receive communications in the future, or contact us at the specified points of contact.</p>

                        <p><strong>Projects for Customers.</strong> In some cases, Contracting Alliance will have been engaged to collect personal information on behalf of a customer, such as through a survey. In such cases, we will provide the information we collect to our client in accordance with their instructions. We are not responsible for the content of any survey (or other information provided by our client) or the use of information collected by our client (or the privacy practices of that client).</p>

                        <p><strong>Use and Sharing of Personal Data.</strong> We use the personal information that is collected through our website to better serve our customers and users, personalize your website experience, and improve the content of our website. Contracting Alliance will use the personal information you provide to us for marketing and promotional purposes only. We may share your personal information in the following ways: with a Contracting Alliance customer if that customer has engaged us to collect personal information on their behalf, such as through a survey; with external agents that we have contracted to help us provide a good or service that you have requested; or in the Special Cases detailed below. We do not rent or sell your personally identifiable information entered on this website to third parties. We may use personal information about you and your visits to our website to send you advertisements on our web pages or in emails related to Contracting Alliance or its products or services. We may share non-personal or aggregated statistical information about our users with advertisers, business partners, sponsors, and other third parties. This data is used to personalize the content and advertising on our website to provide a better experience for our users.</p>

                        <p><strong>Security.</strong> While we use reasonable efforts to safeguard the confidentiality of your information, we cannot guarantee that data will always remain secure due to transmission errors, outside events, third-party hacking, or other causes. We will comply with all privacy laws and make any legally required disclosures regarding breaches of the security, confidentiality, or integrity of personal data consistent with our ability to determine the scope of a breach and our obligations to law enforcement.</p>

                        <p><strong>Cookies, Web Analytics, and IP Tracking.</strong> Our web servers collect general data pertaining to each website user, including their IP address, domain name, referring web page, and the length of time spent and the pages accessed while visiting this website. Some of this information may be used to infer your geographic location. Web usage information is collected to help us manage and administer our website, improve the content of our website, and customize and improve the website user experience. Web analytic information is gathered using the following methods: (1) cookies, (2) conversion tracking, and (3) general detection and use of your internet protocol (IP) address or domain name.</p>

                        <p><strong>Cookies.</strong> A cookie is a small file stored on your computer by a website to give you a unique ID. We use cookies to track new visitors to this site and to recognize past users so that we may customize and personalize content. Cookies used by this site do not contain any personally identifiable information. If for any reason you don’t want to take advantage of cookies, you may set your browser to not accept them, although this may disable or render unusable some of the features of our site.</p>

                        <p><strong>Conversion Tracking.</strong> Search engines offer a feature called “conversion tracking,” which is a way to track clicks to sales from either search results or ads on search engines. Using either web beacons or visible images, depending upon the search engine, the search engine notes and saves information in a cookie with non-personal information such as time of day, browser type, browser language, and IP address with each query. Information is gathered in the aggregate, without unique personal data. Conversion tracking allows the search engine company and Contracting Alliance to track clicks to sales, including the number of clicks (“visits”) it takes before a purchase is made and permits us to measure the effectiveness of our search engine participation.</p>

                        <p><strong>Consent to Transfer.</strong> This website is operated in the United States. If you are located in the European Union, Canada, or elsewhere outside of the United States, please be aware that any information you provide to Contracting Alliance will be transferred to the United States. By using this website or providing us with your information, you consent to this transfer.</p>

                        <p><strong>Special Cases.</strong> Contracting Alliance reserves the right to disclose user information in special cases, when we have reason to believe that disclosing this information is necessary to identify, contact, or bring legal action against someone who may be causing injury to or interference with (either intentionally or unintentionally) our rights or property, other Contracting Alliance website users, or anyone else who could be harmed by such activities. We may disclose personal information without notice to you in response to a subpoena or when we believe in good faith that the law permits it or to respond to an emergency situation. In the event Contracting Alliance or its subsidiaries or affiliates or their assets are sold, merged, or otherwise involved in a corporate transaction, your personal information will likely be transferred as part of that transaction. We reserve the right to transfer your information without your consent in such a situation; provided that we will make reasonable efforts to see that your privacy preferences are honored by the transferee. Specific areas or pages of this website may include additional or different provisions relating to collection and disclosure of personal information. In the event of a conflict between such provisions and this Privacy Policy, such specific terms shall control.</p>

                        <p><strong>Policies for Children.</strong> Contracting Alliance does not knowingly collect or use any personal information from users under 18 years of age. No information should be submitted to this site by guests under 18 years of age, and guests under 18 years old are not allowed to register for our accounts, contests, newsletters, or activities.</p>

                        <p><strong>Linked Sites.</strong> Please be advised that our website contains links to third-party websites. The linked sites are not under our control, and we are not responsible for the contents or privacy practices of any linked site or any link on a linked site.</p>

                        <p><strong>Changes to This Policy.</strong> Contracting Alliance reserves the right to change or update this policy, or any other policy or practice, at any time, with reasonable notice to users of its website. Any changes or updates will be effective immediately upon posting to our website.</p>

                        <p><strong>Contact Information:</strong> CONTRACTING ALLIANCE INC — Attn: President.</p>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    
    <script>
        // Initialize phone input
        const phoneInput = document.querySelector("#phone");
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

        // Image preview function
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('photo_preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // File upload handling
        const companyInput = document.getElementById('company_documents');
        const uploadedList = document.getElementById('uploaded_list');
        const hiddenInput = document.getElementById('hidden_documents');
        let uploadedFiles = [];

        companyInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (!files || files.length === 0) return;
            
            for (let i = 0; i < files.length; i++) {
                if (files[i].size > 10 * 1024 * 1024) { // 10MB limit
                    alert('File ' + files[i].name + ' exceeds the 10MB size limit.');
                    continue;
                }
                uploadedFiles.push(files[i]);
            }
            
            updateHiddenInput();
            renderUploadedList();
            companyInput.value = ''; // Reset input
        });

        function removeFile(index) {
            uploadedFiles.splice(index, 1);
            updateHiddenInput();
            renderUploadedList();
        }

        function renderUploadedList() {
            uploadedList.innerHTML = '';
            
            if (uploadedFiles.length === 0) {
                uploadedList.innerHTML = '<p class="text-muted">No files uploaded yet.</p>';
                return;
            }
            
            uploadedFiles.forEach((file, index) => {
                const item = document.createElement('div');
                item.classList.add('uploaded-file');
                item.innerHTML = `
                    <div class="uploaded-file-name">${file.name}</div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeFile(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                uploadedList.appendChild(item);
            });
        }

        function updateHiddenInput() {
            const dataTransfer = new DataTransfer();
            uploadedFiles.forEach(file => dataTransfer.items.add(file));
            hiddenInput.files = dataTransfer.files;
        }

        // Multi-step form navigation
        function nextStep(currentStep) {
            // Basic validation before proceeding
            if (currentStep === 1) {
                const name = document.querySelector('input[name="name"]').value;
                const email = document.querySelector('input[name="email"]').value;
                const emailConfirmation = document.querySelector('input[name="email_confirmation"]').value;
                
                if (!name || !email || !emailConfirmation) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                if (email !== emailConfirmation) {
                    alert('Email addresses do not match');
                    return;
                }
            }
            
            document.getElementById('step' + currentStep).classList.remove('active');
            document.getElementById('step' + currentStep).classList.add('completed');
            document.getElementById('stepContent' + currentStep).classList.remove('active');
            
            const nextStepNum = currentStep + 1;
            document.getElementById('step' + nextStepNum).classList.add('active');
            document.getElementById('stepContent' + nextStepNum).classList.add('active');
        }

        function prevStep(currentStep) {
            document.getElementById('step' + currentStep).classList.remove('active');
            document.getElementById('stepContent' + currentStep).classList.remove('active');
            
            const prevStepNum = currentStep - 1;
            document.getElementById('step' + prevStepNum).classList.add('active');
            document.getElementById('stepContent' + prevStepNum).classList.add('active');
        }

        // Drag and drop for file upload
        const fileUploadArea = document.querySelector('.file-upload-area');
        
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
            if (files.length > 0) {
                companyInput.files = files;
                const event = new Event('change');
                companyInput.dispatchEvent(event);
            }
        });
    </script>
</body>
</html>
</x-guest-layout>
