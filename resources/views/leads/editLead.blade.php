@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="mb-4 text-center"><i class="bi bi-pencil-square"></i> Edit Lead</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-lg p-4">



        <form action="{{ route('leads.update', $lead->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT"> <!-- Simula PUT con POST -->

            <div class="container">

        

          



            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title m-0">Lead Information</h5>
                </div>
            
                <div class="card-body">
                    
                    <div class="row">
                        <!-- Campo oculto para asegurar que el estado siempre sea 1 -->
                        <input type="hidden" name="estado" value="1">
                        
                        <!-- Primera fila con First Name y Last Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="small font-weight-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $lead->first_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="small font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $lead->last_name) }}" required>
                            </div>
                        </div>
                    </div>
            
                    <!-- Segunda fila con Company Name y Cross Reference -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="company_name" class="small font-weight-bold">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $lead->company_name) }}">
                            </div>
                        </div>
                    </div>
            
                    <!-- Tercera fila con Cross Reference -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cross_reference" class="small font-weight-bold">Cross Reference</label>
                                <input type="text" class="form-control" id="cross_reference" name="cross_reference" value="{{ old('cross_reference', $lead->cross_reference) }}">
                            </div>
                        </div>
                    </div>
            
                    <!-- Última fila con Job Category, Work Type y Trade Type -->
                    <div class="row">
                        <!-- Job Category -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="job_category" class="small font-weight-bold">Job Category</label>
                                <select class="form-control" id="job_category" name="job_category">
                                    <option value="">Select Category</option>
                                    <option value="Commercial" {{ old('job_category', $lead->job_category) == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="Property Management" {{ old('job_category', $lead->job_category) == 'Property Management' ? 'selected' : '' }}>Property Management</option>
                                    <option value="Residential" {{ old('job_category', $lead->job_category) == 'Residential' ? 'selected' : '' }}>Residential</option>
                                </select>
                            </div>
                        </div>
            
                        <!-- Work Type -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="work_type" class="small font-weight-bold">Work Type</label>
                                <select class="form-control" id="work_type" name="work_type">
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
                        </div>

                        <!-- Trade Type -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="job_trades" class="small font-weight-bold">Trade Type</label>
                                <select class="form-control" id="job_trades" name="job_trades">
                                    <option value="">Select Trade Type</option>
                                    <option value="Gutters" {{ old('job_trades', $lead->job_trades ?? '') == 'Gutters' ? 'selected' : '' }}>Gutters</option>
                                    <option value="Roofing" {{ old('job_trades', $lead->job_trades ?? '') == 'Roofing' ? 'selected' : '' }}>Roofing</option>
                                    <option value="Siding" {{ old('job_trades', $lead->job_trades ?? '') == 'Siding' ? 'selected' : '' }}>Siding</option>
                                </select>   
                            </div>
                        </div>




                    </div>
                </div>
            </div><br>
            
        
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <!-- Lead Source -->
                        <div class="form-group">
                            <label for="lead_source" class="small font-weight-bold">Lead Source</label>
                            <select class="form-control" id="lead_source" name="lead_source">
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
            
                        <!-- Phone Section -->
                        <div class="form-group">
                            <label class="small font-weight-bold">Phone</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="( ) ___-____" 
                                           value="{{ old('phone', $lead->phone) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="phone_ext" name="phone_ext" placeholder="Ext"
                                           value="{{ old('phone_ext', $lead->phone_ext) }}">
                                </div>
                                <div class="col-md-3">
                                    <select id="phone_type" name="phone_type" class="form-control">
                                        <option disabled selected value="">Type</option>
                                        <option value="home" {{ old('phone_type', $lead->phone_type) == "home" ? 'selected' : '' }}>Home</option>
                                        <option value="mobile" {{ old('phone_type', $lead->phone_type) == "mobile" ? 'selected' : '' }}>Mobile</option>
                                        <option value="work" {{ old('phone_type', $lead->phone_type) == "work" ? 'selected' : '' }}>Work</option>
                                    </select>
                                </div>
                            </div>
                        </div>
            
                        <!-- Email Section -->
                        <div class="form-group">
                            <label class="small font-weight-bold">Email</label>
                            <div class="row">
                                <div class="col-md-9">
                                    <input type="email" id="email" name="email" class="form-control" 
                                           value="{{ old('email', $lead->email) }}">
                                </div>
                            </div>
                        </div><br>
            
                        <!-- Location Address -->
                        <h6 class="font-weight-bold mt-4">Location Address:</h6>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="street" class="small font-weight-bold">Street <span class="text-danger">*</span></label>
                                    <input type="text" id="street" name="street" class="form-control" 
                                           value="{{ old('street', $lead->street) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="suite" class="small font-weight-bold">Suite/Apt/Unit</label>
                                    <input type="text" id="suite" name="suite" class="form-control" 
                                           value="{{ old('suite', $lead->suite) }}">
                                </div>
                            </div>
                        </div>
            
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="city" class="small font-weight-bold">City <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" class="form-control" 
                                           value="{{ old('city', $lead->city) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="state" class="small font-weight-bold">State <span class="text-danger">*</span></label>
                                    <select id="state" name="state" class="form-control" required>
                                        <option value="" disabled selected>Choose...</option>
                                        @foreach(['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'DC', 'WV', 'WI', 'WY'] as $state)
                                            <option value="{{ $state }}" {{ old('state', $lead->state) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="zip" class="small font-weight-bold">Zip <span class="text-danger">*</span></label>
                                    <input type="text" id="zip" name="zip" class="form-control" 
                                           value="{{ old('zip', $lead->zip) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="country" class="small font-weight-bold">Country</label>
                                    <input type="text" id="country" name="country" class="form-control"
                                           value="{{ old('country', $lead->country ?? 'United States') }}">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div><br>
            

            {{-- New --}}
            <div class="card">
                <div class="card-body">
            
                    <!-- Location Photo -->
                    <div class="document-section">
                        <h5 class="section-title" data-bs-toggle="collapse" data-bs-target="#collapseLead">
                           Location Photo:
                        </h5>
                        <div id="collapseLead" class="collapse show">
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
                            <label>Update Document:</label>
                            <input type="file" id="location_photo" name="location_photo" class="form-control">

                        </div>
                    </div>

            
                    <!-- Mailing Address -->
                    <div class="form-group">
                        <label class="small font-weight-bold">Mailing Address:</label>
                        <select id="mailing_address" name="mailing_address" class="form-control" onchange="toggleAddressForm(this, 'newAddressFieldsMailing')">
                            <option value="sameM" {{ old('mailing_address', $lead->mailing_address) == 'sameM' ? 'selected' : '' }}>Same as Location</option>
                            <option value="mailing" {{ old('mailing_address', $lead->mailing_address) == 'mailing' ? 'selected' : '' }}>Same as Mailing</option>
                            <option value="new" {{ old('mailing_address', $lead->mailing_address) == 'new' ? 'selected' : '' }}>New Address</option>
                        </select>
            
                        <!-- Nueva dirección (visible solo si se selecciona "new") -->
                        <div id="newAddressFieldsMailing" class="mt-3" style="display: {{ old('mailing_address', $lead->mailing_address) == 'new' ? 'block' : 'none' }};">
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
                                    <label for="statemailing" class="small font-weight-bold">State</label>
                                    <select id="statemailing" name="statemailing" class="form-control">
                                        <option value="" disabled selected>Choose...</option>
                                        @foreach(['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'DC', 'WV', 'WI', 'WY'] as $state)
                                            <option value="{{ $state }}" {{ old('statemailing', $lead->statemailing) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="zipmailing" class="small font-weight-bold">Zip</label>
                                    <input type="text" id="zipmailing" name="zipmailing" class="form-control" placeholder="Enter zip code" value="{{ old('zipmailing', $lead->zipmailing) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="countrymailing" class="small font-weight-bold">Country</label>
                                    <input type="text" id="countrymailing" name="countrymailing" class="form-control" value="{{ old('countrymailing', $lead->countrymailing) ?? 'United States' }}">
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
            
                        <!-- Nueva dirección de Facturación -->
                        <div id="newAddressFieldsBilling" class="mt-3" style="display: {{ old('billing_address', $lead->billing_address) == 'new' ? 'block' : 'none' }};">
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
                            </div>
                        </div>
                    </div>
            
                </div>
            </div><br>
            

            {{-- New --}}
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title m-0">Insurance Information</h5>
                </div>
                <div class="card-body">
                   <!-- Insurance Company -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="insurance_company" class="small font-weight-bold">Insurance Company</label>
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
                        </div>
                    </div>

            
                    <!-- Damage Location & Date of Loss -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="damage_location" class="small font-weight-bold">Damage Location</label>
                                <input type="text" class="form-control" id="damage_location" name="damage_location" 
                                       value="{{ old('damage_location', $lead->damage_location) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_loss" class="small font-weight-bold">Date of Loss</label>
                                <input type="date" class="form-control" id="date_loss" name="date_loss"
                                value="{{ old('date_loss', !empty($lead->date_loss) ? \Carbon\Carbon::parse($lead->date_loss)->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="claim_number" class="small font-weight-bold">Claim Number</label>
                                <input type="text" class="form-control" id="claim_number" name="claim_number" 
                                       value="{{ old('claim_number', $lead->claim_number) }}">
                            </div>
                        </div>
                        
                    </div>
            
                
            
                    <!-- Adjuster Information -->
                    <h6 class="font-weight-bold mt-4">Adjuster Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="adjuster_phone" class="small font-weight-bold">Adjuster Phone</label>
                                <input type="tel" class="form-control" id="adjuster_phone" name="adjuster_phone" 
                                       placeholder="( ) ___-____" value="{{ old('adjuster_phone', $lead->adjuster_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="adjuster_ext" class="small font-weight-bold">Adjuster Ext</label>
                                <input type="text" class="form-control" id="adjuster_ext" name="adjuster_ext" 
                                       value="{{ old('adjuster_ext', $lead->adjuster_ext) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="adjuster_phone_type" class="small font-weight-bold">Type</label>
                                <select class="form-control" id="adjuster_phone_type" name="adjuster_phone_type">
                                    <option disabled selected value="">Choose...</option>
                                    <option value="home" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'home' ? 'selected' : '' }}>Home</option>
                                    <option value="mobile" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                    <option value="work" {{ old('adjuster_phone_type', $lead->adjuster_phone_type) == 'work' ? 'selected' : '' }}>Work</option>
                                </select>
                            </div>
                        </div>
                    </div>
            
                    <!-- Adjuster Fax & Email -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adjuster_fax" class="small font-weight-bold">Adjuster Fax</label>
                                <input type="tel" class="form-control" id="adjuster_fax" name="adjuster_fax" 
                                       placeholder="( ) ___-____" value="{{ old('adjuster_fax', $lead->adjuster_fax) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adjuster_email" class="small font-weight-bold">Adjuster Email</label>
                                <input type="email" class="form-control" id="adjuster_email" name="adjuster_email" 
                                       value="{{ old('adjuster_email', $lead->adjuster_email) }}">
                            </div>
                        </div>
                    </div>
            
                    <!-- Meeting & Approval -->
                    <div class="row">
                  
                  
                    </div>
                </div>
            </div><br>
            
        

            <!-- Notas -->
            <div class="form-group">
            <label for="notas">Notas</label>
            <textarea id="notas" name="notas" class="form-control">{{ old('notas', $lead->notas) }}</textarea>
            </div>



            <!-- Botón de Envío -->
            <div class="form-group mt-3">
                <input type="submit" value="Save" class="btn btn-primary">
            </div>



        </form>


        
    </div>
</div>



 <!-- Estilos -->
 <style>
    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    .drop-zone:hover {
        background-color: #f9f9f9;
    }
    .drop-zone-active {
        border-color: #007bff;
        background-color: #e8f0ff;
    }
    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .preview-container img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .preview-item {
        position: relative;
        display: inline-block;
    }
    .remove-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border: none;
        font-size: 14px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
    }
</style>

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

{{-- Estilo document --}}
<style>
                /* Contenedor General */
    .tab-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Sección de cada documento */
    .document-section {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
    }

    .document-section h5 {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    /* Drop Zone */
    .drop-zone {
        width: 100%;
        height: 100px;
        border: 2px dashed #aaa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background-color: #f8f9fa;
        text-align: center;
    }

    .drop-zone:hover {
        background-color: #e9ecef;
    }

    .drop-zone-text {
        font-size: 14px;
        color: #777;
    }

    /* Ocultar input real */
    .drop-zone-input {
        display: none;
    }

    /* Vista Previa de Imagen */
    .preview-container {
        margin-top: 10px;
    }

    .preview-container img {
        max-width: 120px;
        border-radius: 5px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }
    .btn-outline-primary {
        font-size: 14px;
        padding: 5px 10px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }

    .btn-outline-primary i {
        margin-right: 5px;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }


</style>


<!-- Script para Vista Previa y Eliminación -->
<script>
        document.querySelectorAll(".drop-zone").forEach((dropZone) => {
            const inputElement = dropZone.querySelector(".drop-zone-input");
            const previewContainer = document.getElementById("preview-" + dropZone.id.split("-")[1]);

            dropZone.addEventListener("click", () => inputElement.click());

            dropZone.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropZone.classList.add("drop-zone-active");
            });

            dropZone.addEventListener("dragleave", () => {
                dropZone.classList.remove("drop-zone-active");
            });

            dropZone.addEventListener("drop", (e) => {
                e.preventDefault();
                dropZone.classList.remove("drop-zone-active");

                if (e.dataTransfer.files.length) {
                    handleFiles([...e.dataTransfer.files], previewContainer);
                    inputElement.files = e.dataTransfer.files; // Asigna los archivos al input
                }
            });

            inputElement.addEventListener("change", (e) => {
                handleFiles([...e.target.files], previewContainer);
                inputElement.value = ""; // Soluciona el problema de doble selección
            });
        });

        function handleFiles(files, previewContainer) {
            files.forEach((file) => {
                if (file.type.startsWith("image/")) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const previewItem = document.createElement("div");
                        previewItem.classList.add("preview-item");

                        const img = document.createElement("img");
                        img.src = e.target.result;

                        const removeBtn = document.createElement("button");
                        removeBtn.classList.add("remove-btn");
                        removeBtn.innerText = "×";

                        removeBtn.onclick = () => {
                            previewItem.remove();
                            removeFile(file);
                        };

                        previewItem.appendChild(img);
                        previewItem.appendChild(removeBtn);
                        previewContainer.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function removeFile(file) {
            // Eliminar archivo de la lista seleccionada (no afecta input directamente)
            console.log(`Archivo eliminado: ${file.name}`);
        }
</script>

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


<script>
            function previewImage(event, previewId) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById(previewId);
                output.innerHTML = `<img src="${reader.result}" alt="Preview" style="max-width:100px;">`;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

</script>

@endsection
