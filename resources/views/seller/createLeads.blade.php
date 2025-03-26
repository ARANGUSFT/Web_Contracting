@extends('layouts.app')

@section('title', 'Página de Inicio')

@section('content')



   <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          
            <form action="{{ route('seller.store') }}" method="POST" enctype="multipart/form-data"><br>
                @csrf

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
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                    
                                <div class="col-md-12">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name">
                                </div>
                    
                                <div class="col-md-12">
                                    <label for="cross_reference" class="form-label">Cross Reference</label>
                                    <input type="text" class="form-control" id="cross_reference" name="cross_reference">
                                </div>
                    
                                <div class="col-md-4">
                                    <label for="job_category" class="form-label">Job Category</label>
                                    <select class="form-select" id="job_category" name="job_category">
                                        <option value="">Select Category</option>
                                        <option value="Commercial">Commercial</option>
                                        <option value="Property Management">Property Management</option>
                                        <option value="Residential">Residential</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-4">
                                    <label for="work_type" class="form-label">Work Type</label>
                                    <select class="form-select" id="work_type" name="work_type">
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
                    
                                <div class="col-md-4">
                                    <label for="job_trades" class="form-label">Trade Type</label>
                                    <select class="form-select" id="job_trades" name="job_trades">
                                        <option value="">Select Trade Type</option>
                                        <option value="Gutters">Gutters</option>
                                        <option value="Roofing">Roofing</option>
                                        <option value="Siding">Siding</option>
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
                                        <option value="">Select Source</option>
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
                    
                                <div class="col-md-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="( ) ___-____" required>
                                </div>
                    
                                <div class="col-md-2">
                                    <label for="phone_ext" class="form-label">Ext</label>
                                    <input type="text" class="form-control" id="phone_ext" name="phone_ext">
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="phone_type" class="form-label">Type</label>
                                    <select id="phone_type" name="phone_type" class="form-select">
                                        <option disabled selected value="">Select Type</option>
                                        <option value="home">Home</option>
                                        <option value="mobile">Mobile</option>
                                        <option value="work">Work</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control">
                                </div>
                    
                                <h6 class="mt-4">Location Address</h6>
                                <div class="col-md-6">
                                    <label for="street" class="form-label">Street <span class="text-danger">*</span></label>
                                    <input type="text" id="street" name="street" class="form-control" required>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="suite" class="form-label">Suite/Apt/Unit</label>
                                    <input type="text" id="suite" name="suite" class="form-control">
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" class="form-control" required>
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                    <select id="state" name="state" class="form-select" required>
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
                                    <label for="zip" class="form-label">Zip <span class="text-danger">*</span></label>
                                    <input type="text" id="zip" name="zip" class="form-control" required>
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" id="country" name="country" value="US" class="form-control">
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
                    </div><br>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="m-0">Insurance Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="insurance_company" class="form-label">Insurance Company</label>
                                    <select class="form-select" id="insurance_company" name="insurance_company">
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
                    
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="insurance_not_listed" name="insurance_not_listed">
                                        <label class="form-check-label" for="insurance_not_listed">Insurance Company Not Listed</label>
                                    </div>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="damage_location" class="form-label">Damage Location</label>
                                    <input type="text" class="form-control" id="damage_location" name="damage_location">
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="date_of_loss" class="form-label">Date of Loss</label>
                                    <input type="date" class="form-control" id="date_of_loss" name="date_loss">
                                </div>
                    
                                <div class="col-md-4">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="claim_filed" name="claim_filed">
                                        <label class="form-check-label" for="claim_filed">Claim Filed?</label>
                                    </div>
                                </div>
                    
                                <div class="col-md-4">
                                    <label for="claim_number" class="form-label">Claim Number</label>
                                    <input type="text" class="form-control" id="claim_number" name="claim_number">
                                </div>
                    
                                <div class="col-md-4">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="has_paperwork" name="has_paperwork">
                                        <label class="form-check-label" for="has_paperwork">Has Paperwork?</label>
                                    </div>
                                </div>
                    
                                <h6 class="mt-4">Adjuster Information</h6>
                    
                                <div class="col-md-4">
                                    <label for="adjuster_phone" class="form-label">Adjuster Phone</label>
                                    <input type="tel" class="form-control" id="adjuster_phone" name="adjuster_phone" placeholder="( ) ___-____">
                                </div>
                    
                                <div class="col-md-2">
                                    <label for="adjuster_ext" class="form-label">Ext</label>
                                    <input type="text" class="form-control" id="adjuster_ext" name="adjuster_ext">
                                </div>
                    
                                <div class="col-md-3">
                                    <label for="adjuster_phone_type" class="form-label">Phone Type</label>
                                    <select class="form-select" id="adjuster_phone_type" name="adjuster_phone_type">
                                        <option disabled selected>Choose...</option>
                                        <option value="home">Home</option>
                                        <option value="mobile">Mobile</option>
                                        <option value="work">Work</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="adjuster_fax" class="form-label">Adjuster Fax</label>
                                    <input type="tel" class="form-control" id="adjuster_fax" name="adjuster_fax" placeholder="( ) ___-____">
                                </div>
                    
                                <div class="col-md-6">
                                    <label for="adjuster_email" class="form-label">Adjuster Email</label>
                                    <input type="email" class="form-control" id="adjuster_email" name="adjuster_email">
                                </div>
                    
                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="met_adjuster" name="met_adjuster">
                                        <label class="form-check-label" for="met_adjuster">Met with Adjuster?</label>
                                    </div>
                                </div>
                    
                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="claim_approved" name="claim_approved">
                                        <label class="form-check-label" for="claim_approved">Claim Approved?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="notas" class="form-label">Notas</label>
                        <textarea id="notas" name="notas" class="form-control" rows="4" placeholder="Enter additional notes here..."></textarea>
                    </div>
                    
                    
              
            
            
            
            
                  
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-3" id="documentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#documento" type="button" role="tab">Document</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#finanzas" type="button" role="tab">Finance</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#anexos" type="button" role="tab">Annexes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contratos" type="button" role="tab">Contracts</button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="documento" role="tabpanel">
                            <h5>Manage Documents</h5>
                            <div class="mb-3">
                                <label for="files_documento" class="form-label">Upload Document</label>
                                <input type="file" class="form-control" id="files_documento" name="files_documento" data-preview="preview_documento">
                                <ul id="preview_documento" class="preview-list"></ul>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="finanzas" role="tabpanel">
                            <h5>Finance Documents</h5>
                            <div class="mb-3">
                                <label for="files_finanzas" class="form-label">Upload Finance Document</label>
                                <input type="file" class="form-control" id="files_finanzas" name="files_finanzas" data-preview="preview_finanzas">
                                <ul id="preview_finanzas" class="preview-list"></ul>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="anexos" role="tabpanel">
                            <h5>Annex Documents</h5>
                            <div class="mb-3">
                                <label for="files_anexos" class="form-label">Upload Annex Document</label>
                                <input type="file" class="form-control" id="files_anexos" name="files_anexos" data-preview="preview_anexos">
                                <ul id="preview_anexos" class="preview-list"></ul>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="contratos" role="tabpanel">
                            <h5>Contract Documents</h5>
                            <div class="mb-3">
                                <label for="files_contratos" class="form-label">Upload Contract Document</label>
                                <input type="file" class="form-control" id="files_contratos" name="files_contratos" data-preview="preview_contratos">
                                <ul id="preview_contratos" class="preview-list"></ul>
                            </div>
                        </div>
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
