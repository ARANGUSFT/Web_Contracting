@extends('layouts.app')

@section('title', 'Página de Inicio')

@section('content')



   <div class="py-12">
       <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
     

            
            <form action="{{ route('leads.store') }}" method="POST" enctype="multipart/form-data"><br>
                @csrf

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
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="small font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Segunda fila con Company Name y Cross Reference -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="company_name" class="small font-weight-bold">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name">
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Tercera fila con Cross Reference -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cross_reference" class="small font-weight-bold">Cross Reference</label>
                                        <input type="text" class="form-control" id="cross_reference" name="cross_reference">
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Última fila con Job Category, Work Type y Trade Type -->
                            <div class="row">
            
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="job_category" class="small font-weight-bold">Job Category</label>
                                        <select class="form-control" id="job_category" name="job_category">
                                            <option value="">Select Category</option>
                                            <option value="Commercial">Commercial</option>
                                            <option value="Property Management">Property Management</option>
                                            <option value="Residential">Residential</option>
                                        </select>
                                    </div>
                                </div>
            
            
            
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="work_type" class="small font-weight-bold">Work Type</label>
                                        <select class="form-control" id="work_type" name="work_type">
                                            <option value="">Select Work Type</option>
                                            <option value="Inspection">Inspection</option>
                                            <option value="Insurance">Insurance</option>
                                            <option value="New">New</option>
                                            <option value="Repair">Repair</option>
                                            <option value="Retail">Retail</option>
                                            <option value="Service">Service</option>
                                            <option value="Warranty">Warranty</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="trade_type" class="small font-weight-bold">Trade Type</label>
                                        <select class="form-control" id="job_trades" name="job_trades">
                                            <option value="">Select Trade Type</option>
                                            <option value="Gutters">Gutters</option>
                                            <option value="Roofing">Roofing</option>
                                            <option value="Siding">Siding</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
            
            
                    <div class="container">
                        <div class="card">
                            <div class="card-body">
                                    <!-- Lead Source -->
                                    <div class="form-group">
                                        <label for="lead_source" class="small font-weight-bold">Lead Source</label>
                                        <select class="form-control" id="lead_source" name="lead_source">
                                            <option value="" hidden></option>
                                            <option value="0">Canvasser</option>
                                            <option value="1">Direct Mailings</option>
                                            <option value="2">Door hanger</option>
                                            <option value="3">Door Knocking</option>
                                            <option value="4">Internet</option>
                                            <option value="5">K104</option>
                                            <option value="6">Newspaper</option>
                                            <option value="7">Other</option>
                                            <option value="8">Phonebook</option>
                                            <option value="9">Previous Customer</option>
                                            <option value="10">Radio</option>
                                            <option value="11">Referral</option>
                                            <option value="12">Telemarketing</option>
                                            <option value="13">Truck</option>
                                            <option value="14">Yard Sign</option>
                                        </select>
                                    </div>
                        
                                    <!-- Phone Section -->
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Phone</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="( ) ___-____" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="phone_ext" name="phone_ext" placeholder="Ext">
                                            </div>
                                            <div class="col-md-3">
                                                <select id="phone_type" name="phone_type" class="form-control">
                                                    <option disabled selected value="">Type</option>
                                                    <option value="home">Home</option>
                                                    <option value="mobile">Mobile</option>
                                                    <option value="work">Work</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <!-- Email Section -->
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Email</label>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="email" id="email" name="email" class="form-control">
                                            </div>
                                        </div>
                                    </div><br>
                        
                                    <!-- Location Address -->
                                    <h6 class="font-weight-bold mt-4">Location Address:</h6>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="street" class="small font-weight-bold">Street <span class="text-danger">*</span></label>
                                                <input type="text" id="street" name="street" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="suite" class="small font-weight-bold">Suite/Apt/Unit</label>
                                                <input type="text" id="suite" name="suite" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="city" class="small font-weight-bold">City <span class="text-danger">*</span></label>
                                                <input type="text" id="city" name="city" class="form-control" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="state" class="small font-weight-bold">State <span class="text-danger">*</span></label>
                                                <select id="state" name="state" class="form-control" required>
                                                    <option value="" disabled selected>Choose...</option>
                                                    <option value="AL">AL</option>
                                                    <option value="AK">AK</option>
                                                    <option value="AZ">AZ</option>
                                                    <option value="AR">AR</option>
                                                    <option value="CA">CA</option>
                                                    <option value="CO">CO</option>
                                                    <option value="CT">CT</option>
                                                    <option value="DE">DE</option>
                                                    <option value="FL">FL</option>
                                                    <option value="GA">GA</option>
                                                    <option value="HI">HI</option>
                                                    <option value="ID">ID</option>
                                                    <option value="IL">IL</option>
                                                    <option value="IN">IN</option>
                                                    <option value="IA">IA</option>
                                                    <option value="KS">KS</option>
                                                    <option value="KY">KY</option>
                                                    <option value="LA">LA</option>
                                                    <option value="ME">ME</option>
                                                    <option value="MD">MD</option>
                                                    <option value="MA">MA</option>
                                                    <option value="MI">MI</option>
                                                    <option value="MN">MN</option>
                                                    <option value="MS">MS</option>
                                                    <option value="MO">MO</option>
                                                    <option value="MT">MT</option>
                                                    <option value="NE">NE</option>
                                                    <option value="NV">NV</option>
                                                    <option value="NH">NH</option>
                                                    <option value="NJ">NJ</option>
                                                    <option value="NM">NM</option>
                                                    <option value="NY">NY</option>
                                                    <option value="NC">NC</option>
                                                    <option value="ND">ND</option>
                                                    <option value="OH">OH</option>
                                                    <option value="OK">OK</option>
                                                    <option value="OR">OR</option>
                                                    <option value="PA">PA</option>
                                                    <option value="PR">PR</option>
                                                    <option value="RI">RI</option>
                                                    <option value="SC">SC</option>
                                                    <option value="SD">SD</option>
                                                    <option value="TN">TN</option>
                                                    <option value="TX">TX</option>
                                                    <option value="UT">UT</option>
                                                    <option value="VT">VT</option>
                                                    <option value="VA">VA</option>
                                                    <option value="WA">WA</option>
                                                    <option value="DC">DC</option>
                                                    <option value="WV">WV</option>
                                                    <option value="WI">WI</option>
                                                    <option value="WY">WY</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="zip" class="small font-weight-bold">Zip <span class="text-danger">*</span></label>
                                                <input type="text" id="zip" name="zip" class="form-control" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="country" class="small font-weight-bold">Country</label>
                                                <input type="text" id="country" name="country" value="US" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
            
            
            
                    {{-- New --}}
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
                            <div class="preview-container" id="preview-location">
                                <img id="imagePreview" src="" class="preview-img" style="display:none; max-width: 100%; margin-top: 10px;">
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
                                    <option value="sameM" selected>Same as Location</option>
                                    <option value="mailing">Same as Mailing</option>
                                    <option value="new">New Address</option>
                                </select>
            
                                <!-- Nueva dirección (oculta por defecto) -->
                                <div id="newAddressFieldsMailing" class="mt-3" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="streetmailing" class="small font-weight-bold">Street</label>
                                            <input type="text" id="streetmailing" name="streetmailing" class="form-control" placeholder="Enter street">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="suitemailing" class="small font-weight-bold">Suite/Apt/Unit</label>
                                            <input type="text" id="suitemailing" name="suitemailing" class="form-control" placeholder="Enter suite">
                                        </div>
                                    </div>
            
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label for="citymailing" class="small font-weight-bold">City</label>
                                            <input type="text" id="citymailing" name="citymailing" class="form-control" placeholder="Enter city">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="statemailing" class="small font-weight-bold">State</label>
                                            <select id="statemailing" name="statemailing" class="form-control">
                                                <option value="" disabled selected>Choose...</option>
                                                <option value="AL">AL</option>
                                                <option value="AK">AK</option>
                                                <option value="AZ">AZ</option>
                                                <option value="AR">AR</option>
                                                <option value="CA">CA</option>
                                                <option value="CO">CO</option>
                                                <option value="CT">CT</option>
                                                <option value="DE">DE</option>
                                                <option value="FL">FL</option>
                                                <option value="GA">GA</option>
                                                <option value="HI">HI</option>
                                                <option value="ID">ID</option>
                                                <option value="IL">IL</option>
                                                <option value="IN">IN</option>
                                                <option value="IA">IA</option>
                                                <option value="KS">KS</option>
                                                <option value="KY">KY</option>
                                                <option value="LA">LA</option>
                                                <option value="ME">ME</option>
                                                <option value="MD">MD</option>
                                                <option value="MA">MA</option>
                                                <option value="MI">MI</option>
                                                <option value="MN">MN</option>
                                                <option value="MS">MS</option>
                                                <option value="MO">MO</option>
                                                <option value="MT">MT</option>
                                                <option value="NE">NE</option>
                                                <option value="NV">NV</option>
                                                <option value="NH">NH</option>
                                                <option value="NJ">NJ</option>
                                                <option value="NM">NM</option>
                                                <option value="NY">NY</option>
                                                <option value="NC">NC</option>
                                                <option value="ND">ND</option>
                                                <option value="OH">OH</option>
                                                <option value="OK">OK</option>
                                                <option value="OR">OR</option>
                                                <option value="PA">PA</option>
                                                <option value="PR">PR</option>
                                                <option value="RI">RI</option>
                                                <option value="SC">SC</option>
                                                <option value="SD">SD</option>
                                                <option value="TN">TN</option>
                                                <option value="TX">TX</option>
                                                <option value="UT">UT</option>
                                                <option value="VT">VT</option>
                                                <option value="VA">VA</option>
                                                <option value="WA">WA</option>
                                                <option value="DC">DC</option>
                                                <option value="WV">WV</option>
                                                <option value="WI">WI</option>
                                                <option value="WY">WY</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="zipmailing" class="small font-weight-bold">Zip</label>
                                            <input type="text" id="zipmailing" name="zipmailing" class="form-control" placeholder="Enter zip code">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="countrymailing" class="small font-weight-bold">Country</label>
                                            <select id="countrymailing" name="countrymailing" class="form-control">
                                                <option value="" disabled selected>Choose...</option>
                                                <option value="US">US</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                     
            
                            <!-- Billing Address -->
                            <div class="form-group">
                                <label class="small font-weight-bold">Billing Address:</label>
                                <select id="billing_address" name="billing_address" class="form-control" onchange="toggleAddressForm(this, 'newAddressFieldsBilling')">
                                    <option value="sameB" selected>Same as Location</option>
                                    <option value="billing">Same as Billing</option>
                                    <option value="new">New Address</option>
                                </select>
            
                                <!-- Nueva dirección de Facturación (oculta por defecto) -->
                                <div id="newAddressFieldsBilling" class="mt-3" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="streetbilling" class="small font-weight-bold">Street</label>
                                            <input type="text" id="streetbilling" name="streetbilling" class="form-control" placeholder="Enter street">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="suitebilling" class="small font-weight-bold">Suite/Apt/Unit</label>
                                            <input type="text" id="suitebilling" name="suitebilling" class="form-control" placeholder="Enter suite">
                                        </div>
                                    </div>
            
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label for="citybilling" class="small font-weight-bold">City</label>
                                            <input type="text" id="citybilling" name="citybilling" class="form-control" placeholder="Enter city">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="statebilling" class="small font-weight-bold">State</label>
                                            <select id="statebilling" name="statebilling" class="form-control">
                                                <option value="" disabled selected>Choose...</option>
                                                <option value="AL">AL</option>
                                                <option value="AK">AK</option>
                                                <option value="AZ">AZ</option>
                                                <option value="AR">AR</option>
                                                <option value="CA">CA</option>
                                                <option value="CO">CO</option>
                                                <option value="CT">CT</option>
                                                <option value="DE">DE</option>
                                                <option value="FL">FL</option>
                                                <option value="GA">GA</option>
                                                <option value="HI">HI</option>
                                                <option value="ID">ID</option>
                                                <option value="IL">IL</option>
                                                <option value="IN">IN</option>
                                                <option value="IA">IA</option>
                                                <option value="KS">KS</option>
                                                <option value="KY">KY</option>
                                                <option value="LA">LA</option>
                                                <option value="ME">ME</option>
                                                <option value="MD">MD</option>
                                                <option value="MA">MA</option>
                                                <option value="MI">MI</option>
                                                <option value="MN">MN</option>
                                                <option value="MS">MS</option>
                                                <option value="MO">MO</option>
                                                <option value="MT">MT</option>
                                                <option value="NE">NE</option>
                                                <option value="NV">NV</option>
                                                <option value="NH">NH</option>
                                                <option value="NJ">NJ</option>
                                                <option value="NM">NM</option>
                                                <option value="NY">NY</option>
                                                <option value="NC">NC</option>
                                                <option value="ND">ND</option>
                                                <option value="OH">OH</option>
                                                <option value="OK">OK</option>
                                                <option value="OR">OR</option>
                                                <option value="PA">PA</option>
                                                <option value="PR">PR</option>
                                                <option value="RI">RI</option>
                                                <option value="SC">SC</option>
                                                <option value="SD">SD</option>
                                                <option value="TN">TN</option>
                                                <option value="TX">TX</option>
                                                <option value="UT">UT</option>
                                                <option value="VT">VT</option>
                                                <option value="VA">VA</option>
                                                <option value="WA">WA</option>
                                                <option value="DC">DC</option>
                                                <option value="WV">WV</option>
                                                <option value="WI">WI</option>
                                                <option value="WY">WY</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="zipbilling" class="small font-weight-bold">Zip</label>
                                            <input type="text" id="zipbilling" name="zipbilling" class="form-control" placeholder="Enter zip code">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="countrybilling" class="small font-weight-bold">Country</label>
                                            <select id="countrybilling" name="countrybilling" class="form-control">
                                                <option value="" disabled selected>Choose...</option>
                                                <option value="US">US</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                  
            
            
                        </div>
                    </div>
                    
            
            
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
                                            <option value="Allstate">Allstate</option>
                                            <option value="American Family">American Family</option>
                                            <option value="Garm Bureau">Garm Bureau</option>
                                            <option value="Farmers">Farmers</option>
                                            <option value="Liberty Mutual">Liberty Mutual</option>
                                            <option value="Nationwide">Nationwide</option>
                                            <option value="Other">Other</option>
                                            <option value="Safeco">Safeco</option>
                                            <option value="State Farm">State Farm</option>
                                            <option value="Travelers">Travelers</option>
                                            <option value="USAA">USAA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <input type="checkbox" id="insurance_not_listed" name="insurance_not_listed" class="mr-2">
                                    <label for="company_not_listed" class="small font-weight-bold mb-0">Insurance Company Not Listed</label>
                                </div>
                            </div>
                    
                            <!-- Damage Location & Date of Loss -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="damage_location" class="small font-weight-bold">Damage Location</label>
                                        <input type="text" class="form-control" id="damage_location" name="damage_location">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_of_loss" class="small font-weight-bold">Date of Loss</label>
                                        <input type="date" class="form-control" id="date_of_loss" name="date_loss">
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Claim Details -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Claim Filed?</label><br>
                                        <input type="checkbox" id="claim_filed" name="claim_filed" class="mr-2">
                                        <label for="claim_filed" class="small mb-0">Yes</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="claim_number" class="small font-weight-bold">Claim Number</label>
                                        <input type="text" class="form-control" id="claim_number" name="claim_number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Has Paperwork?</label><br>
                                        <input type="checkbox" id="has_paperwork" name="has_paperwork" class="mr-2">
                                        <label for="has_paperwork" class="small mb-0">Yes</label>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Adjuster Information -->
                            <h6 class="font-weight-bold mt-4">Adjuster Name</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="adjuster_phone" class="small font-weight-bold">Adjuster Phone</label>
                                        <input type="tel" class="form-control" id="adjuster_phone" name="adjuster_phone" placeholder="( ) ___-____">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="adjuster_ext" class="small font-weight-bold">Adjuster Ext</label>
                                        <input type="text" class="form-control" id="adjuster_ext" name="adjuster_ext">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="adjuster_phone_type" class="small font-weight-bold">Type</label>
                                        <select class="form-control" id="adjuster_phone_type" name="adjuster_phone_type">
                                            <option disabled selected value="">Choose...</option>
                                            <option value="home">Home</option>
                                            <option value="mobile">Mobile</option>
                                            <option value="work">Work</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Adjuster Fax & Email -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="adjuster_fax" class="small font-weight-bold">Adjuster Fax</label>
                                        <input type="tel" class="form-control" id="adjuster_fax" name="adjuster_fax" placeholder="( ) ___-____">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="adjuster_email" class="small font-weight-bold">Adjuster Email</label>
                                        <input type="email" class="form-control" id="adjuster_email" name="adjuster_email">
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Meeting & Approval -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Met with Adjuster?</label><br>
                                        <input type="checkbox" id="met_adjuster" name="met_adjuster" class="mr-2">
                                        <label for="met_with_adjuster" class="small mb-0">Yes</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Claim Approved?</label><br>
                                        <input type="checkbox" id="claim_approved" name="claim_approved" class="mr-2">
                                        <label for="claim_approved" class="small mb-0">Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            
            
            
                    <!-- Notas -->
                    <div class="form-group">
                        <label for="notas">Notas</label>
                        <textarea id="notas" name="notas" class="form-control"></textarea>
                    </div>
            
            
            
            
                
                    <!-- Pestañas de Navegación -->
                  <!-- Pestañas de Navegación (ubicadas abajo) -->
    <ul class="nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" onclick="showTab('documento')">Document</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" onclick="showTab('finanzas')">Finance</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" onclick="showTab('anexos')">Annexes</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" onclick="showTab('contratos')">Contracts</button>
        </li>
    </ul>

    <!-- Contenedor de Pestañas -->
    <div class="tab-content active" id="documento">
        <div class="tab-pane">
            <h4>Manage Documents</h4>
            <label>Upload Document:</label>
            <input type="file" id="files_documento" name="files_documento">
        </div>
    </div>

    <div class="tab-content" id="finanzas">
        <div class="tab-pane">
            <h4>Finance Documents</h4>
            <label>Upload Finance Document:</label>
            <input type="file" id="files_finanzas" name="files_finanzas">
        </div>
    </div>

    <div class="tab-content" id="anexos">
        <div class="tab-pane">
            <h4>Annex Documents</h4>
            <label>Upload Annex Document:</label>
            <input type="file" id="files_anexos" name="files_anexos">
        </div>
    </div>

    <div class="tab-content" id="contratos">
        <div class="tab-pane">
            <h4>Contract Documents</h4>
            <label>Upload Contract Document:</label>
            <input type="file" id="files_contratos" name="files_contratos">
        </div>
    </div>



                
                    
                
                    <!-- Botón de Envío -->
                    <div class="form-group mt-3">
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </div>

                </div>
            
        
              
            </form>


       
        </div>
    </div>



 

        <style>
            

            /* Sección de carga de imágenes */
            .drop-zone {
                border: 2px dashed #007bff;
                padding: 30px;
                text-align: center;
                cursor: pointer;
                border-radius: 8px;
                transition: all 0.3s ease-in-out;
            }

            .drop-zone:hover {
                background-color: #e9f5ff;
            }

            .drop-zone-text {
                font-weight: bold;
                color: #007bff;
            }

            .preview-container {
                text-align: center;
                margin-top: 10px;
            }

            .preview-img {
                max-width: 100%;
                border-radius: 5px;
                display: none;
            }

            /* Pestañas (ubicadas abajo) */
            .nav-tabs {
                display: flex;
                border-top: 2px solid #ddd;
                list-style: none;
                padding: 0;
                margin-top: 20px;
                justify-content: center;
            }

            .nav-tabs .nav-item {
                flex: 1;
                text-align: center;
            }

            .nav-tabs .nav-link {
                width: 100%;
                display: block;
                padding: 12px 15px;
                cursor: pointer;
                font-weight: bold;
                color: #007bff;
                border: none;
                background: none;
                transition: all 0.3s ease;
                text-align: center;
            }

            .nav-tabs .nav-link.active {
                border-top: 3px solid #007bff;
                color: #0056b3;
                background-color: #e9f5ff;
            }

            /* Contenedor de pestañas */
            .tab-content {
                display: none;
                padding-top: 15px;
            }

            .tab-content.active {
                display: block;
            }

            /* Estilos para inputs */
            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .form-group input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
            }
        </style>

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





        <script>
            document.addEventListener("DOMContentLoaded", function () {
            
                // ✅ Manejo de Pestañas en Bootstrap 5
                var triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'))
                triggerTabList.forEach(function (triggerEl) {
                    var tabTrigger = new bootstrap.Tab(triggerEl)
                    triggerEl.addEventListener('click', function (event) {
                        event.preventDefault()
                        tabTrigger.show()
                    })
                });
            
                // ✅ Manejo de Collapse en Documentos
                document.querySelectorAll('.section-title').forEach((item) => {
                    item.addEventListener('click', () => {
                        let target = item.getAttribute('data-bs-target');
                        let collapseElement = document.querySelector(target);
                        if (collapseElement) {
                            let bsCollapse = new bootstrap.Collapse(collapseElement, {
                                toggle: true
                            });
                        }
                    });
                });
            
                // ✅ Función para Vista Previa de Archivos
                function previewFile(input, previewContainer) {
                    let file = input.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            let imgPreview = document.createElement("img");
                            imgPreview.src = e.target.result;
                            imgPreview.classList.add("preview-img");
                            imgPreview.style.maxWidth = "150px";
                            imgPreview.style.marginTop = "10px";
                            
                            // Limpiar contenedor antes de añadir la nueva imagen
                            document.getElementById(previewContainer).innerHTML = "";
                            document.getElementById(previewContainer).appendChild(imgPreview);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            
                // ✅ Asignar eventos para cada input de archivo
                document.getElementById("files_documento").addEventListener("change", function () {
                    previewFile(this, "previewLead");
                });
            
                document.getElementById("files_finanzas").addEventListener("change", function () {
                    previewFile(this, "previewFinance");
                });
            
                document.getElementById("files_anexos").addEventListener("change", function () {
                    previewFile(this, "previewAnnex");
                });
            
                document.getElementById("files_contratos").addEventListener("change", function () {
                    previewFile(this, "previewContract");
                });
            
            });
        </script>
        
        <script>
            function showTab(tabId) {
                // Ocultar todas las pestañas
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));

                // Mostrar la pestaña seleccionada
                document.getElementById(tabId).classList.add('active');

                // Resaltar la pestaña activa
                document.querySelector(`.nav-item .nav-link[onclick="showTab('${tabId}')"]`).classList.add('active');
            }

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



@endsection
