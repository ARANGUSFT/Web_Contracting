@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <a  href="{{ route('leads.index') }}" class="btn btn-outline-secondary btn-retroceder">
        &#8592; Back
    </a>
    <h2 class="mb-4 text-center"><i class="bi bi-pencil-square"></i> Edit Lead</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-lg p-4">



        <form action="{{ route('leads.update', $lead->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT"> <!-- Simula PUT con POST -->

            <div class="container">
                    

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="m-0">Lead Information</h5>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="estado" value="1">
                    
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $lead->first_name) }}" required>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $lead->last_name) }}" required>
                                </div>
                    
                                <div class="col-md-12">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $lead->company_name) }}">
                                </div>
                    
                                <div class="col-md-12">
                                    <label for="cross_reference" class="form-label">Cross Reference</label>
                                    <input type="text" class="form-control" id="cross_reference" name="cross_reference" value="{{ old('cross_reference', $lead->cross_reference) }}">
                                </div>
                    
                                <div class="col-md-4">
                                    <label for="job_category" class="form-label">Job Category</label>
                                    <select class="form-select" id="job_category" name="job_category">
                                        <option value="">Select Category</option>
                                        <option value="Commercial" {{ old('job_category', $lead->job_category) == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                        <option value="Property Management" {{ old('job_category', $lead->job_category) == 'Property Management' ? 'selected' : '' }}>Property Management</option>
                                        <option value="Residential" {{ old('job_category', $lead->job_category) == 'Residential' ? 'selected' : '' }}>Residential</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-4">
                                    <label for="work_type" class="form-label">Work Type</label>
                                    <select class="form-select" id="work_type" name="work_type">
                                        <option value="">Select Work Type</option>
                                        <option value="Inspection" {{ old('work_type', $lead->work_type) == 'Inspection' ? 'selected' : '' }}>Inspection</option>
                                        <option value="Insurance" {{ old('work_type', $lead->work_type) == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                                        <option value="New" {{ old('work_type', $lead->work_type) == 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Repair" {{ old('work_type', $lead->work_type) == 'Repair' ? 'selected' : '' }}>Repair</option>
                                        <option value="Retail" {{ old('work_type', $lead->work_type) == 'Retail' ? 'selected' : '' }}>Retail</option>
                                        <option value="Service" {{ old('work_type', $lead->work_type) == 'Service' ? 'selected' : '' }}>Service</option>
                                        <option value="Warranty" {{ old('work_type', $lead->work_type) == 'Warranty' ? 'selected' : '' }}>Warranty</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-4">
                                    <label for="job_trades" class="form-label">Trade Type</label>
                                    <select class="form-select" id="job_trades" name="job_trades">
                                        <option value="">Select Trade Type</option>
                                        <option value="Gutters" {{ old('job_trades', $lead->job_trades ?? '') == 'Gutters' ? 'selected' : '' }}>Gutters</option>
                                        <option value="Roofing" {{ old('job_trades', $lead->job_trades ?? '') == 'Roofing' ? 'selected' : '' }}>Roofing</option>
                                        <option value="Siding" {{ old('job_trades', $lead->job_trades ?? '') == 'Siding' ? 'selected' : '' }}>Siding</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="m-0">Contact & Address Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="lead_source" class="form-label">Lead Source</label>
                                    <select class="form-select" id="lead_source" name="lead_source">
                                        <option value="" hidden></option>
                                        <option value="0" {{ old('lead_source', $lead->lead_source) == "0" ? 'selected' : '' }}>Canvasser</option>
                                        <option value="1" {{ old('lead_source', $lead->lead_source) == "1" ? 'selected' : '' }}>Direct Mailings</option>
                                        <option value="2" {{ old('lead_source', $lead->lead_source) == "2" ? 'selected' : '' }}>Door hanger</option>
                                        <option value="3" {{ old('lead_source', $lead->lead_source) == "3" ? 'selected' : '' }}>Door Knocking</option>
                                        <option value="4" {{ old('lead_source', $lead->lead_source) == "4" ? 'selected' : '' }}>Internet</option>
                                        <option value="5" {{ old('lead_source', $lead->lead_source) == "5" ? 'selected' : '' }}>K104</option>
                                        <option value="6" {{ old('lead_source', $lead->lead_source) == "6" ? 'selected' : '' }}>Newspaper</option>
                                        <option value="7" {{ old('lead_source', $lead->lead_source) == "7" ? 'selected' : '' }}>Other</option>
                                        <option value="8" {{ old('lead_source', $lead->lead_source) == "8" ? 'selected' : '' }}>Phonebook</option>
                                        <option value="9" {{ old('lead_source', $lead->lead_source) == "9" ? 'selected' : '' }}>Previous Customer</option>
                                        <option value="10" {{ old('lead_source', $lead->lead_source) == "10" ? 'selected' : '' }}>Radio</option>
                                        <option value="11" {{ old('lead_source', $lead->lead_source) == "11" ? 'selected' : '' }}>Referral</option>
                                        <option value="12" {{ old('lead_source', $lead->lead_source) == "12" ? 'selected' : '' }}>Telemarketing</option>
                                        <option value="13" {{ old('lead_source', $lead->lead_source) == "13" ? 'selected' : '' }}>Truck</option>
                                        <option value="14" {{ old('lead_source', $lead->lead_source) == "14" ? 'selected' : '' }}>Yard Sign</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="( ) ___-____" value="{{ old('phone', $lead->phone) }}" required>
                                </div>
                    
                                <div class="col-md-2">
                                    <label for="phone_ext" class="form-label">Ext</label>
                                    <input type="text" class="form-control" id="phone_ext" name="phone_ext" value="{{ old('phone_ext', $lead->phone_ext) }}">
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="phone_type" class="form-label">Type</label>
                                    <select id="phone_type" name="phone_type" class="form-select">
                                        <option disabled selected value="">Select Type</option>
                                        <option value="home" {{ old('phone_type', $lead->phone_type) == "home" ? 'selected' : '' }}>Home</option>
                                        <option value="mobile" {{ old('phone_type', $lead->phone_type) == "mobile" ? 'selected' : '' }}>Mobile</option>
                                        <option value="work" {{ old('phone_type', $lead->phone_type) == "work" ? 'selected' : '' }}>Work</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $lead->email) }}">
                                </div>
                    
                                <h6 class="mt-4">Location Address</h6>
                                <div class="col-md-6">
                                    <label for="street" class="form-label">Street <span class="text-danger">*</span></label>
                                    <input type="text" id="street" name="street" class="form-control" value="{{ old('street', $lead->street) }}" required>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="suite" class="form-label">Suite/Apt/Unit</label>
                                    <input type="text" id="suite" name="suite" class="form-control" value="{{ old('suite', $lead->suite) }}">
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" class="form-control" value="{{ old('city', $lead->city) }}" required>
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                    <select id="state" name="state" class="form-control" required>
                                        <option value="" disabled selected>Choose...</option>
                                        @foreach(['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'DC', 'WV', 'WI', 'WY'] as $state)
                                            <option value="{{ $state }}" {{ old('state', $lead->state) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="zip" class="form-label">Zip <span class="text-danger">*</span></label>
                                    <input type="text" id="zip" name="zip" class="form-control" value="{{ old('zip', $lead->zip) }}" required>
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" id="country" name="country" value="US"  class="form-control" value="{{ old('country', $lead->country ?? 'United States') }}">
                                </div>
                            </div>
                        </div>
                    </div><br>
            
                    <div class="card">
                        <div class="card-body">
                            
                        
            
            
                        <!-- Pestaña Documento -->
                        <div class="tab-pane fade show active p-3" id="location" role="tabpanel" aria-labelledby="location-tab">
                            <label class="small font-weight-bold">Location Photo:</label>
                            
                            <!-- Contenedor de "arrastrar y soltar" -->
                            <div class="drop-zone" id="drop-location" onclick="document.getElementById('location_photo').click();">
                                <span class="drop-zone-text">Drop photo here or click to select</span>
                                <input type="file" id="location_photo" name="location_photo" class="d-none" accept="image/*" onchange="previewImage(event)">
                            </div>
                            
                            <!-- Contenedor de la previsualización -->
                            <div class="preview-container">
                                @if($lead->location_photo) 
                                <img src="{{ asset('storage/' . $lead->location_photo) }}?t={{ time() }}" alt="Documento" class="preview-img">
                                <a href="{{ asset('storage/' . $lead->location_photo) }}" download class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <p>No document uploaded.</p>
                                @endif

                            </div>
                        </div>
            
                        <script>
                            function previewImage(event) {
                                var input = event.target;
                                
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
            
                                    reader.onload = function(e) {
                                        var output = document.getElementById('imagePreview');
                                        output.src = e.target.result;
                                        output.style.display = 'block';
                                    }
                                    
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
            
                            
                            
                    
                            <!-- Mailing Address -->
                            <div class="form-group">
                                <label class="small font-weight-bold">Mailing Address:</label>
                                <select id="mailing_address" name="mailing_address" class="form-control" onchange="toggleAddressForm(this, 'newAddressFieldsMailing')">
                                    <option value="sameM" {{ old('mailing_address', $lead->mailing_address) == 'sameM' ? 'selected' : '' }}>Same as Location</option>
                                    <option value="mailing" {{ old('mailing_address', $lead->mailing_address) == 'mailing' ? 'selected' : '' }}>Same as Mailing</option>
                                    <option value="new" {{ old('mailing_address', $lead->mailing_address) == 'new' ? 'selected' : '' }}>New Address</option>
                                </select>
            
                                <!-- Nueva dirección (oculta por defecto) -->
                                <div id="newAddressFieldsMailing" class="mt-3" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="streetmailing" class="small font-weight-bold">Street</label>
                                            <input type="text" id="streetmailing" name="streetmailing" class="form-control" placeholder="Enter street" value="{{ old('streetmailing', $lead->streetmailing) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="suitemailing" class="small font-weight-bold">Suite/Apt/Unit</label>
                                            <input type="text" id="suitemailing" name="suitemailing" class="form-control" placeholder="Enter suite" value="{{ old('suitemailing', $lead->suitemailing) }}">
                                        </div>
                                    </div>
            
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label for="citymailing" class="small font-weight-bold">City</label>
                                            <input type="text" id="citymailing" name="citymailing" class="form-control" placeholder="Enter city" value="{{ old('citymailing', $lead->citymailing) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="statebilling" class="small font-weight-bold">State</label>
                                            <select id="statebilling" name="statebilling" class="form-control">
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach(['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'DC', 'WV', 'WI', 'WY'] as $state)
                                                    <option value="{{ $state }}" {{ old('statebilling', $lead->statebilling) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="zipmailing" class="small font-weight-bold">Zip</label>
                                            <input type="text" id="zipmailing" name="zipmailing" class="form-control"  placeholder="Enter zip code" value="{{ old('zipmailing', $lead->zipmailing) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="countrymailing" class="small font-weight-bold">Country</label>
                                            <input type="text" id="countrymailing" name="countrymailing" class="form-control" value="{{ old('countrymailing', $lead->countrymailing) ?? 'US' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                    
            
                            <!-- Billing Address -->
                            <div class="form-group">
                                <label class="small font-weight-bold">Billing Address:</label>
                                <select id="billing_address" name="billing_address" class="form-control" onchange="toggleAddressForm(this, 'newAddressFieldsBilling')">
                                    <option value="sameB" {{ old('billing_address', $lead->billing_address) == 'sameB' ? 'selected' : '' }}>Same as Location</option>
                                    <option value="billing" {{ old('billing_address', $lead->billing_address) == 'billing' ? 'selected' : '' }}>Same as Billing</option>
                                    <option value="new" {{ old('billing_address', $lead->billing_address) == 'new' ? 'selected' : '' }}>New Address</option>
                                </select>
            
                                <!-- Nueva dirección de Facturación (oculta por defecto) -->
                                <div id="newAddressFieldsBilling" class="mt-3" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="streetbilling" class="small font-weight-bold">Street</label>
                                            <input type="text" id="streetbilling" name="streetbilling" class="form-control" placeholder="Enter street" value="{{ old('streetbilling', $lead->streetbilling) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="suitebilling" class="small font-weight-bold">Suite/Apt/Unit</label>
                                            <input type="text" id="suitebilling" name="suitebilling" class="form-control" placeholder="Enter suite" value="{{ old('suitebilling', $lead->suitebilling) }}">
                                        </div>
                                    </div>
            
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label for="citybilling" class="small font-weight-bold">City</label>
                                            <input type="text" id="citybilling" name="citybilling" class="form-control" placeholder="Enter city" value="{{ old('citybilling', $lead->citybilling) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="statebilling" class="small font-weight-bold">State</label>
                                            <select id="statebilling" name="statebilling" class="form-control">
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach(['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'DC', 'WV', 'WI', 'WY'] as $state)
                                                    <option value="{{ $state }}" {{ old('statebilling', $lead->statebilling) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="zip" class="form-label">Zip <span class="text-danger">*</span></label>
                                            <input type="text" id="zip" name="zip" class="form-control" value="{{ old('zip', $lead->zip) }}" required>
                                        </div>
                            
                                        <div class="col-md-3">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text" id="country" name="country" value="US"  class="form-control" value="{{ old('country', $lead->country ?? 'US') }}">
                                        </div>


                                        
                                    </div>
                                </div>
                            </div>
            
                
            
            
                        </div>
                    </div><br>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="m-0">Insurance Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="insurance_company" class="form-label">Insurance Company</label>
                                    <select class="form-control" id="insurance_company" name="insurance_company">
                                        <option value="" selected>Choose...</option>
                                        <option value="Allstate" {{ old('insurance_company', $lead->insurance_company) == 'Allstate' ? 'selected' : '' }}>Allstate</option>
                                        <option value="American Family" {{ old('insurance_company', $lead->insurance_company) == 'American Family' ? 'selected' : '' }}>American Family</option>
                                        <option value="Farm Bureau" {{ old('insurance_company', $lead->insurance_company) == 'Farm Bureau' ? 'selected' : '' }}>Farm Bureau</option>
                                        <option value="Farmers" {{ old('insurance_company', $lead->insurance_company) == 'Farmers' ? 'selected' : '' }}>Farmers</option>
                                        <option value="Liberty Mutual" {{ old('insurance_company', $lead->insurance_company) == 'Liberty Mutual' ? 'selected' : '' }}>Liberty Mutual</option>
                                        <option value="Nationwide" {{ old('insurance_company', $lead->insurance_company) == 'Nationwide' ? 'selected' : '' }}>Nationwide</option>
                                        <option value="Other" {{ old('insurance_company', $lead->insurance_company) == 'Other' ? 'selected' : '' }}>Other</option>
                                        <option value="Safeco" {{ old('insurance_company', $lead->insurance_company) == 'Safeco' ? 'selected' : '' }}>Safeco</option>
                                        <option value="State Farm" {{ old('insurance_company', $lead->insurance_company) == 'State Farm' ? 'selected' : '' }}>State Farm</option>
                                        <option value="Travelers" {{ old('insurance_company', $lead->insurance_company) == 'Travelers' ? 'selected' : '' }}>Travelers</option>
                                        <option value="USAA" {{ old('insurance_company', $lead->insurance_company) == 'USAA' ? 'selected' : '' }}>USAA</option>
                                    </select>
                                </div>
                    
                        
                    
                                <div class="col-md-6">
                                    <label for="damage_location" class="form-label">Damage Location</label>
                                    <input type="text" class="form-control" id="damage_location" name="damage_location" value="{{ old('damage_location', $lead->damage_location) }}">
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="date_of_loss" class="form-label">Date of Loss</label>
                                    <input type="date" class="form-control" id="date_of_loss" name="date_loss" value="{{ old('date_loss', !empty($lead->date_loss) ? \Carbon\Carbon::parse($lead->date_loss)->format('Y-m-d') : '') }}">
                                </div>
                    
                            
                    
                                <div class="col-md-4">
                                    <label for="claim_number" class="form-label">Claim Number</label>
                                    <input type="text" class="form-control" id="claim_number" name="claim_number" value="{{ old('claim_number', $lead->claim_number) }}">
                                </div>
                    
                            
                    
                                <h6 class="mt-4">Adjuster Information</h6>
                    
                                <div class="col-md-4">
                                    <label for="adjuster_phone" class="form-label">Adjuster Phone</label>
                                    <input type="tel" class="form-control" id="adjuster_phone" name="adjuster_phone" placeholder="( ) ___-____" value="{{ old('adjuster_phone', $lead->adjuster_phone) }}">
                                </div>
                    
                                <div class="col-md-2">
                                    <label for="adjuster_ext" class="form-label">Ext</label>
                                    <input type="text" class="form-control" id="adjuster_ext" name="adjuster_ext" value="{{ old('adjuster_ext', $lead->adjuster_ext) }}">
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="adjuster_phone_type" class="form-label">Phone Type</label>
                                    <select class="form-select" id="adjuster_phone_type" name="adjuster_phone_type">
                                        <option disabled selected>Choose...</option>
                                        <option value="home" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'home' ? 'selected' : '' }}>Home</option>
                                        <option value="mobile" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                        <option value="work" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'work' ? 'selected' : '' }}>Work</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="adjuster_fax" class="form-label">Adjuster Fax</label>
                                    <input type="tel" class="form-control" id="adjuster_fax" name="adjuster_fax" placeholder="( ) ___-____" value="{{ old('adjuster_fax', $lead->adjuster_fax) }}">
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="adjuster_email" class="form-label">Adjuster Email</label>
                                    <input type="email" class="form-control" id="adjuster_email" name="adjuster_email" value="{{ old('adjuster_email', $lead->adjuster_email) }}">
                                </div>
                    
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="notas" class="form-label">Notas</label>
                        <textarea id="notas" name="notas" class="form-control" rows="4" placeholder="Enter additional notes here...">{{ old('notas', $lead->notas) }}</textarea>
                    </div>
                    
                    
            
        


                    <!-- Submit Button -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>



            </div>



        </form>


        
    </div>
</div>


    {{-- estilos --}}
    <style>
        /* Estilo general de las pestañas */
        .nav-tabs {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-item {
            flex-grow: 1;
            text-align: center;
        }

        .nav-tabs .nav-link {
            border: none;
            background: none;
            font-weight: 600;
            padding: 12px 0;
            color: #007bff;
            border-bottom: 3px solid transparent;
            transition: 0.3s;
        }

        .nav-tabs .nav-link:hover {
            color: #0056b3;
        }

        .nav-tabs .nav-link.active {
            color: #0056b3;
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        /* Contenido de pestañas */
        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
            background-color: #fff;
        }

        /* Estilo del área de subida */
        .drop-zone {
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .drop-zone:hover {
            background-color: #e9f5ff;
        }

        .drop-zone-text {
            font-weight: 500;
            color: #007bff;
        }

        /* Input y botón */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        .form-control[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        /* Estilo del botón submit */
        button[type="submit"] {
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 5px;
        }

    </style>

    <style>
        .nav-tabs {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
            padding: 12px 0;
            border: none;
            color: #007bff;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            border-color: #007bff;
            color: #0056b3;
            background-color: #f1f8ff;
        }

        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
            background-color: #fff;
        }

        .form-label {
            font-weight: 600;
        }

        .preview-list {
            margin-top: 10px;
            padding-left: 0;
            list-style: none;
        }

        .preview-list li {
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 6px 10px;
            margin-bottom: 4px;
            border-radius: 4px;
        }

        .preview-list li span {
            flex: 1;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-weight: bold;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>

    <style>
        .preview-list {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        
        .preview-list li {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
    
            fileInputs.forEach(input => {
                input.addEventListener('change', function () {
                    const previewId = this.getAttribute('data-preview');
                    const previewList = document.getElementById(previewId);
    
                    // Limpiar vista previa anterior
                    previewList.innerHTML = '';
    
                    if (this.files.length > 0) {
                        const file = this.files[0];
                        const listItem = document.createElement('li');
    
                        const fileName = document.createElement('span');
                        fileName.textContent = file.name;
    
                        const removeBtn = document.createElement('button');
                        removeBtn.classList.add('remove-btn');
                        removeBtn.innerHTML = '&times;';
                        removeBtn.type = 'button';
                        removeBtn.onclick = () => {
                            this.value = ''; // Limpiar input
                            previewList.innerHTML = ''; // Limpiar preview
                        };
    
                        listItem.appendChild(fileName);
                        listItem.appendChild(removeBtn);
                        previewList.appendChild(listItem);
                    }
                });
            });
        });
    </script>





    <!-- Estilos New adress -->
    <style>
                .hidden-section {
                    display: none; /* Se oculta por defecto */
                    transition: all 0.3s ease-in-out;
                }
                .bg-highlight {
                    background-color: #f8f9fa; /* Color de fondo cuando se expande */
                    border: 2px solid #007bff; /* Borde azul para destacar */
                    padding: 15px;
                    border-radius: 5px;
                }
    </style>

    <!-- Script para mostrar/ocultar los campos de New Address -->
    <script>
        function toggleAddressForm(selectElement, addressDivId) {
            const addressDiv = document.getElementById(addressDivId);
            if (selectElement.value === "new") {
                addressDiv.style.display = "block";
                addressDiv.classList.add("bg-highlight");
            } else {
                addressDiv.style.display = "none";
                addressDiv.classList.remove("bg-highlight");
            }
        }
    </script>


@endsection
