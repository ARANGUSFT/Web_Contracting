@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --slate-900: #0f172a;
        --slate-700: #334155;
        --slate-600: #475569;
        --slate-400: #94a3b8;
        --slate-300: #cbd5e1;
        --slate-200: #e2e8f0;
        --slate-100: #f1f5f9;
        --white:     #ffffff;
        --blue:      #2563eb;
        --blue-light:#eff6ff;
        --blue-mid:  #bfdbfe;
        --green:     #10b981;
        --danger:    #ef4444;
    }

    * { box-sizing: border-box; }

    .edit-page { font-family: 'Montserrat', sans-serif; background: var(--slate-900); min-height: 100vh; }

    .edit-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }

    /* ── Sidebar ── */
    .edit-sidebar {
        background: #0a0f1a; padding: 2rem 1.5rem;
        display: flex; flex-direction: column; gap: 0;
        border-right: 1px solid rgba(255,255,255,0.06);
        position: sticky; top: 0; height: 100vh; overflow-y: auto;
    }
    .sidebar-back {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.72rem; font-weight: 600;
        color: var(--slate-400); text-decoration: none;
        margin-bottom: 2rem; transition: color 0.2s;
    }
    .sidebar-back:hover { color: var(--white); }
    .sidebar-label {
        font-size: 0.6rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--slate-400); margin-bottom: 0.5rem;
    }
    .sidebar-title {
        font-family: 'Montserrat', sans-serif; font-size: 1.1rem; font-weight: 800;
        color: var(--white); line-height: 1.3; margin-bottom: 0.4rem; letter-spacing: -0.02em;
    }
    .sidebar-meta { font-size: 0.72rem; color: var(--slate-400); line-height: 1.6; margin-bottom: 2rem; }

    .sidebar-nav { display: flex; flex-direction: column; gap: 0.25rem; }
    .nav-item {
        display: flex; align-items: center; gap: 0.65rem;
        padding: 0.6rem 0.75rem; border-radius: 8px;
        font-size: 0.78rem; font-weight: 600;
        color: var(--slate-400); cursor: pointer;
        transition: all 0.2s; text-decoration: none;
        border: none; background: none; width: 100%; text-align: left;
    }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--white); }
    .nav-item.active { background: rgba(37,99,235,0.2); color: #93c5fd; }
    .nav-item i { width: 16px; text-align: center; font-size: 0.85rem; }

    .sidebar-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 1rem 0; }

    .btn-save-sidebar {
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        font-family: 'Montserrat', sans-serif; font-weight: 700;
        font-size: 0.82rem; padding: 0.7rem; border-radius: 10px; border: none;
        background: var(--blue); color: var(--white);
        cursor: pointer; margin-top: auto; transition: filter 0.2s; width: 100%;
    }
    .btn-save-sidebar:hover { filter: brightness(1.1); }

    /* ── Main ── */
    .edit-main { background: var(--slate-100); padding: 2rem 2.5rem 3rem; overflow-y: auto; }

    .section-title {
        font-family: 'Montserrat', sans-serif; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: var(--slate-400); margin: 0 0 1.25rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--slate-200); }

    .field-group { background: var(--white); border-radius: 12px; border: 1px solid var(--slate-200); margin-bottom: 1.25rem; overflow: hidden; }
    .field-group-body { padding: 1.25rem; }

    .sub-head {
        font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.08em; color: var(--slate-400);
        margin: 1rem 0 0.5rem; padding-bottom: 0.35rem;
        border-bottom: 1px dashed var(--slate-200);
    }

    /* Controls */
    .form-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--slate-400); margin-bottom: 0.3rem; display: block; }
    .form-control, .form-select {
        font-family: 'Montserrat', sans-serif; font-size: 0.88rem; width: 100%;
        border: 1.5px solid var(--slate-200); border-radius: 9px;
        padding: 0.6rem 0.85rem; color: var(--slate-900); background: var(--white);
        transition: border-color 0.2s, box-shadow 0.2s; appearance: auto;
    }
    .form-control:focus, .form-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); outline: none; }
    .form-control[readonly] { background: var(--slate-100); color: var(--slate-500); }
    textarea.form-control { resize: vertical; min-height: 85px; }

    /* Job number */
    .job-num {
        font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 1.1rem;
        color: var(--blue); letter-spacing: 0.06em; padding: 0.5rem 0;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .job-num-badge { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: var(--blue-mid); color: var(--blue); padding: 0.15rem 0.5rem; border-radius: 99px; }

    /* Terms toggles */
    .terms-toggle {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.9rem 1.1rem; border-radius: 9px;
        border: 1.5px solid var(--slate-200); background: var(--slate-100);
        margin-bottom: 0.6rem; cursor: pointer; transition: border-color 0.2s; gap: 1rem;
    }
    .terms-toggle:has(input:checked) { border-color: var(--green); background: #ecfdf5; }
    .terms-toggle-text { flex: 1; }
    .terms-toggle-label { font-weight: 600; font-size: 0.85rem; color: var(--slate-900); }
    .terms-toggle-sub { font-size: 0.75rem; color: var(--slate-400); margin-top: 0.1rem; }
    .terms-toggle input[type="checkbox"] { width: 18px; height: 18px; flex-shrink: 0; accent-color: var(--green); cursor: pointer; }

    /* Team */
    .team-card {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.7rem 0.9rem; border-radius: 9px;
        border: 1.5px solid var(--slate-200); background: var(--slate-100);
        cursor: pointer; transition: all 0.15s; margin-bottom: 0.45rem;
    }
    .team-card:has(input:checked) { border-color: var(--blue); background: var(--blue-light); }
    .team-avatar {
        width: 32px; height: 32px; border-radius: 8px;
        background: linear-gradient(135deg, var(--slate-700), var(--slate-900));
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.7rem; color: var(--white); flex-shrink: 0;
    }
    .team-card:has(input:checked) .team-avatar { background: linear-gradient(135deg, var(--blue), #1d4ed8); }
    .team-card input[type="checkbox"] { width: 16px; height: 16px; margin-left: auto; accent-color: var(--blue); flex-shrink: 0; cursor: pointer; }
    .team-name { font-weight: 600; font-size: 0.82rem; color: var(--slate-900); }
    .team-role { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); }

    /* Files */
    .file-zone { border: 1px solid var(--slate-200); border-radius: 10px; overflow: hidden; margin-bottom: 0.85rem; }
    .file-zone-head {
        padding: 0.6rem 0.9rem; background: var(--slate-100); border-bottom: 1px solid var(--slate-200);
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-600);
    }
    .file-zone-head i { color: var(--blue); font-size: 0.85rem; }
    .file-zone-body { padding: 0.85rem; }
    .existing-file { display: flex; align-items: center; gap: 0.6rem; padding: 0.5rem 0.7rem; border-radius: 7px; background: var(--white); border: 1px solid var(--slate-200); margin-bottom: 0.35rem; }
    .file-icon-sm { width: 26px; height: 26px; border-radius: 5px; background: var(--slate-100); display: flex; align-items: center; justify-content: center; color: var(--slate-500); flex-shrink: 0; font-size: 0.78rem; }
    .file-link { font-size: 0.78rem; font-weight: 500; color: var(--slate-700); flex-grow: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-decoration: none; }
    .file-link:hover { color: var(--blue); }
    .btn-del { background: none; border: none; cursor: pointer; color: var(--slate-300); font-size: 0.8rem; padding: 0.15rem 0.3rem; border-radius: 4px; flex-shrink: 0; transition: color 0.2s, background 0.2s; }
    .btn-del:hover { color: var(--danger); background: #fff1f2; }
    .new-entry { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.35rem; }
    .new-entry input[type="file"] { flex: 1; font-size: 0.78rem; font-family: 'Montserrat', sans-serif; border: 1.5px solid var(--slate-200); border-radius: 7px; padding: 0.35rem 0.6rem; background: var(--white); cursor: pointer; }
    .btn-add { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 6px; background: var(--slate-200); color: var(--slate-700); border: none; cursor: pointer; margin-top: 0.4rem; transition: background 0.2s; }
    .btn-add:hover { background: var(--slate-300); }
    .btn-remove-file { background: none; border: none; color: var(--danger); cursor: pointer; font-size: 0.9rem; padding: 0; flex-shrink: 0; line-height: 1; }
    .divider-dashed { border: none; border-top: 1px dashed var(--slate-200); margin: 0.65rem 0; }

    @media (max-width: 768px) {
        .edit-layout { grid-template-columns: 1fr; }
        .edit-sidebar { position: static; height: auto; }
        .edit-main { padding: 1.5rem 1rem 2rem; }
    }
</style>

<div class="edit-layout">

    {{-- Sidebar --}}
    <aside class="edit-sidebar">
        <a href="{{ route('jobs.show', $job->id) }}" class="sidebar-back">
            <i class="bi bi-arrow-left"></i> Back to details
        </a>

        <span class="sidebar-label">Editing job request</span>
        <h2 class="sidebar-title">{{ $job->job_number_name }}</h2>
        <div class="sidebar-meta">
            <i class="bi bi-building me-1"></i> {{ $job->company_name }}<br>
            <i class="bi bi-calendar3 me-1"></i> {{ $job->install_date_requested }}
        </div>

        <nav class="sidebar-nav">
            <span class="sidebar-label">Sections</span>
            <a href="#sec-general"   class="nav-item active"><i class="bi bi-building"></i> General Info</a>
            <a href="#sec-customer"  class="nav-item"><i class="bi bi-person"></i> Customer</a>
            <a href="#sec-address"   class="nav-item"><i class="bi bi-geo-alt"></i> Address</a>
            <a href="#sec-materials" class="nav-item"><i class="bi bi-box-seam"></i> Materials</a>
            <a href="#sec-specs"     class="nav-item"><i class="bi bi-clipboard2-check"></i> Inspections</a>
            <a href="#sec-notes"     class="nav-item"><i class="bi bi-file-earmark-check"></i> Notes</a>
            <a href="#sec-team"      class="nav-item"><i class="bi bi-people"></i> Team</a>
            <a href="#sec-files"     class="nav-item"><i class="bi bi-paperclip"></i> Files</a>
        </nav>

        <hr class="sidebar-divider">

        <button type="submit" form="editJobForm" class="btn-save-sidebar">
            <i class="bi bi-check-circle"></i> Save Changes
        </button>
    </aside>

    {{-- Main --}}
    <main class="edit-main">
        <form action="{{ route('jobs.update', $job->id) }}" method="POST"
              enctype="multipart/form-data" id="editJobForm">
            @csrf
            @method('PUT')

            {{-- General --}}
            <p class="section-title" id="sec-general"><i class="bi bi-building-fill"></i> General Information</p>
            <div class="field-group">
                <div class="field-group-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Job Number</label>
                            <div class="job-num">{{ $job->job_number_name }}<span class="job-num-badge">Locked</span></div>
                            <input type="hidden" name="job_number_name" value="{{ $job->job_number_name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Installation Date</label>
                            <input type="date" name="install_date_requested" class="form-control"
                                   value="{{ $job->install_date_requested ? \Carbon\Carbon::parse($job->install_date_requested)->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $job->company_name }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Representative</label>
                            <input type="text" name="company_rep" class="form-control" value="{{ $job->company_rep }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rep Phone</label>
                            <input type="text" name="company_rep_phone" class="form-control" value="{{ $job->company_rep_phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rep Email</label>
                            <input type="email" name="company_rep_email" class="form-control" value="{{ $job->company_rep_email }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer --}}
            <p class="section-title" id="sec-customer"><i class="bi bi-person-fill"></i> Customer Information</p>
            <div class="field-group">
                <div class="field-group-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="customer_first_name" class="form-control" value="{{ $job->customer_first_name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="customer_last_name" class="form-control" value="{{ $job->customer_last_name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="customer_phone_number" class="form-control" value="{{ $job->customer_phone_number }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Address --}}
            <p class="section-title" id="sec-address"><i class="bi bi-geo-alt-fill"></i> Job Address</p>
            <div class="field-group">
                <div class="field-group-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="job_address_street_address" class="form-control" value="{{ $job->job_address_street_address }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Line 2</label>
                            <input type="text" name="job_address_street_address_line_2" class="form-control" value="{{ $job->job_address_street_address_line_2 }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">City</label>
                            <input type="text" name="job_address_city" class="form-control" value="{{ $job->job_address_city }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <select name="job_address_state" class="form-select">
                                @foreach(['AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'] as $abbr => $name)
                                    <option value="{{ $abbr }}" {{ $job->job_address_state === $abbr ? 'selected' : '' }}>{{ $abbr }} – {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ZIP</label>
                            <input type="text" name="job_address_zip_code" class="form-control" value="{{ $job->job_address_zip_code }}" maxlength="10">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Materials --}}
            <p class="section-title" id="sec-materials"><i class="bi bi-box-seam-fill"></i> Materials</p>
            <div class="field-group">
                <div class="field-group-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Material Roof Loaded</label>
                            <select name="material_roof_loaded" class="form-select">
                                <option value="" {{ !$job->material_roof_loaded ? 'selected' : '' }}>Select...</option>
                                <option value="Yes" {{ $job->material_roof_loaded === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No"  {{ $job->material_roof_loaded === 'No'  ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control"
                                   value="{{ $job->delivery_date ? \Carbon\Carbon::parse($job->delivery_date)->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Starter Bundles</label>
                            <input type="number" name="starter_bundles_ordered" class="form-control" min="0" value="{{ $job->starter_bundles_ordered }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hip & Ridge</label>
                            <input type="number" name="hip_and_ridge_ordered" class="form-control" min="0" value="{{ $job->hip_and_ridge_ordered }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Field Shingle Bundles</label>
                            <input type="number" name="field_shingle_bundles_ordered" class="form-control" min="0" value="{{ $job->field_shingle_bundles_ordered }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Modified Bitumen Cap Rolls</label>
                            <input type="number" name="modified_bitumen_cap_rolls_ordered" class="form-control" min="0" value="{{ $job->modified_bitumen_cap_rolls_ordered }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inspections --}}
            <p class="section-title" id="sec-specs"><i class="bi bi-clipboard2-check-fill"></i> Inspections & Work Specs</p>
            <div class="field-group">
                <div class="field-group-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Shingle Layers to Remove</label>
                            <input type="number" name="asphalt_shingle_layers_to_remove" class="form-control" min="0" value="{{ $job->asphalt_shingle_layers_to_remove }}">
                        </div>
                        @foreach([
                            'mid_roof_inspection'         => 'Mid-Roof Inspection',
                            'siding_being_replaced'       => 'Siding Being Replaced',
                            're_deck'                     => 'Re-Deck',
                            'skylights_replace'           => 'Skylights Replacement',
                            'gutter_remove'               => 'Gutter Removal',
                            'gutter_detached_and_reset'   => 'Gutter Detached & Reset',
                            'satellite_remove'            => 'Satellite Removal',
                            'satellite_goes_in_the_trash' => 'Satellite in Trash',
                            'open_soffit_ceiling'         => 'Open Soffit Ceiling',
                            'detached_garage_roof'        => 'Detached Garage Roof',
                            'detached_shed_roof'          => 'Detached Shed Roof',
                        ] as $field => $label)
                            <div class="col-md-6">
                                <label class="form-label">{{ $label }}</label>
                                <select name="{{ $field }}" class="form-select">
                                    <option value="" {{ $job->$field === null ? 'selected' : '' }}>Select...</option>
                                    <option value="Yes" {{ $job->$field === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No"  {{ $job->$field === 'No'  ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <p class="section-title" id="sec-notes"><i class="bi bi-file-earmark-check-fill"></i> Notes & Acknowledgements</p>
            <div class="field-group">
                <div class="field-group-body">
                    <div class="row g-3 mb-2">
                        <div class="col-12">
                            <label class="form-label">Special Instructions</label>
                            <textarea name="special_instructions" class="form-control">{{ $job->special_instructions }}</textarea>
                        </div>
                    </div>
                    <p class="sub-head">Required Acknowledgements</p>
                    <label class="terms-toggle" for="material_verification">
                        <div class="terms-toggle-text">
                            <div class="terms-toggle-label">Material Verification</div>
                            <div class="terms-toggle-sub">Alert Contracting Alliance the night before if materials are not on site.</div>
                        </div>
                        <input type="checkbox" name="material_verification" value="1" id="material_verification" {{ $job->material_verification ? 'checked' : '' }}>
                    </label>
                    <label class="terms-toggle" for="stop_work_request">
                        <div class="terms-toggle-text">
                            <div class="terms-toggle-label">Stop Work Request</div>
                            <div class="terms-toggle-sub">Notify by 4:00 PM CT the day prior if project is to be put on hold.</div>
                        </div>
                        <input type="checkbox" name="stop_work_request" value="1" id="stop_work_request" {{ $job->stop_work_request ? 'checked' : '' }}>
                    </label>
                    <label class="terms-toggle" for="documentationattachment">
                        <div class="terms-toggle-text">
                            <div class="terms-toggle-label">Documentation Attached</div>
                            <div class="terms-toggle-sub">Aerial measurement, material order, and photos are required.</div>
                        </div>
                        <input type="checkbox" name="documentationattachment" value="1" id="documentationattachment" {{ $job->documentationattachment ? 'checked' : '' }}>
                    </label>
                </div>
            </div>

            {{-- Team --}}
            <p class="section-title" id="sec-team"><i class="bi bi-people-fill"></i> Assign Team Members</p>
            <div class="field-group">
                <div class="field-group-body">
                    @php $grouped = $teamMembers->groupBy('role'); @endphp
                    @foreach($grouped as $role => $members)
                        <p style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--slate-400);margin:0.75rem 0 0.4rem;">
                            {{ ucfirst(str_replace('_',' ',$role)) }}
                        </p>
                        <div class="row g-0">
                            @foreach($members as $member)
                                <div class="col-md-6 pe-md-2">
                                    <label class="team-card" for="tm_{{ $member->id }}">
                                        <div class="team-avatar">{{ strtoupper(substr($member->name,0,2)) }}</div>
                                        <div>
                                            <div class="team-name">{{ $member->name }}</div>
                                            <div class="team-role">{{ ucfirst(str_replace('_',' ',$member->role)) }}</div>
                                        </div>
                                        <input type="checkbox" name="assigned_team_members[]"
                                               value="{{ $member->id }}" id="tm_{{ $member->id }}"
                                               {{ $job->teamMembers->contains($member->id) ? 'checked' : '' }}>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Files --}}
            <p class="section-title" id="sec-files"><i class="bi bi-paperclip"></i> File Attachments</p>

            @php
                $fileSections = [
                    ['label'=>'Aerial Measurements', 'icon'=>'bi-map',        'field'=>'aerial_measurement', 'raw'=>$job->aerial_measurement ?? []],
                    ['label'=>'Material Orders',     'icon'=>'bi-cart-check',  'field'=>'material_order',     'raw'=>$job->material_order     ?? []],
                    ['label'=>'Other Files',         'icon'=>'bi-file-earmark','field'=>'file_upload',        'raw'=>$job->file_upload         ?? []],
                ];
            @endphp

            @foreach($fileSections as $sec)
                @php
                    $raw = $sec['raw'];
                    if (is_string($raw)) $raw = json_decode($raw, true) ?? [];
                    $files = is_array($raw) ? $raw : [];
                @endphp
                <div class="file-zone">
                    <div class="file-zone-head">
                        <i class="bi {{ $sec['icon'] }}"></i> {{ $sec['label'] }}
                        <span style="margin-left:auto;font-size:0.62rem;font-weight:500;color:var(--slate-400);">{{ count($files) }} file(s)</span>
                    </div>
                    <div class="file-zone-body">
                        @forelse($files as $index => $fileData)
                            @php
                                $fp  = $fileData['path'] ?? '';
                                $fn  = $fileData['original_name'] ?? basename($fp);
                                $ext = strtolower(pathinfo($fp, PATHINFO_EXTENSION));
                                $ico = match($ext) { 'pdf'=>'bi-file-pdf-fill','jpg','jpeg','png','gif','webp'=>'bi-file-image-fill',default=>'bi-file-earmark-text' };
                            @endphp
                            <div class="existing-file">
                                <div class="file-icon-sm"><i class="bi {{ $ico }}"></i></div>
                                <a href="{{ asset('storage/'.$fp) }}" target="_blank" class="file-link">{{ $fn }}</a>
                                <span style="font-size:0.6rem;color:var(--slate-400);text-transform:uppercase;flex-shrink:0;">{{ $ext }}</span>
                                <button type="button" class="btn-del delete-file-btn"
                                        data-field="{{ $sec['field'] }}" data-index="{{ $index }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @empty
                            <p style="font-size:0.78rem;color:var(--slate-400);font-style:italic;margin:0 0 0.65rem;">No files yet.</p>
                        @endforelse

                        <hr class="divider-dashed">

                        <div id="{{ $sec['field'] }}-entries">
                            <div class="new-entry">
                                <input type="file" name="{{ $sec['field'] }}[]" accept=".pdf,.jpg,.jpeg,.png,.webp">
                            </div>
                        </div>
                        <button type="button" class="btn-add" data-field="{{ $sec['field'] }}">
                            <i class="bi bi-plus"></i> Add file
                        </button>
                    </div>
                </div>
            @endforeach

        </form>
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-add').forEach(btn => {
    btn.addEventListener('click', function () {
        const field = this.dataset.field;
        const container = document.getElementById(field + '-entries');
        const entry = document.createElement('div');
        entry.className = 'new-entry';
        entry.innerHTML = `<input type="file" name="${field}[]" accept=".pdf,.jpg,.jpeg,.png,.webp">
            <button type="button" class="btn-remove-file">&times;</button>`;
        container.appendChild(entry);
        entry.querySelector('.btn-remove-file').addEventListener('click', () => entry.remove());
    });
});

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
    });
});

document.querySelectorAll('.delete-file-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const jobId = @json($job->id);
        const field = this.dataset.field;
        const index = this.dataset.index;
        const row   = this.closest('.existing-file');
        Swal.fire({
            title: 'Delete file?', text: 'Cannot be undone.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#2563eb', cancelButtonColor: '#334155',
            confirmButtonText: 'Delete', cancelButtonText: 'Cancel', reverseButtons: true,
        }).then(r => {
            if (!r.isConfirmed) return;
            fetch(`/jobs/${jobId}/files/${field}/${index}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
            .then(res => { if (!res.ok) return res.json().then(e => { throw new Error(e.error || 'Failed') }); return res.json(); })
            .then(() => { row.remove(); Swal.fire({ icon:'success', title:'Deleted', timer:1200, showConfirmButton:false }); })
            .catch(err => Swal.fire('Error', err.message || 'Something went wrong.', 'error'));
        });
    });
});
</script>

@endsection