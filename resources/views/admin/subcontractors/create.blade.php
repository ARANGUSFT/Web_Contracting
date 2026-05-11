@extends('admin.layouts.superadmin')
@section('title', 'New Crew Manager')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.cc { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1100px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.cc-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 36px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.cc-hero-glow {
    position: absolute; pointer-events: none;
    width: 500px; height: 260px;
    background: radial-gradient(ellipse, rgba(13,158,106,.35) 0%, transparent 70%);
    right: -40px; top: -40px;
}
.cc-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#34d399 0%,#0d9e6a 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.cc-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.cc-hero-left { position: relative; display: flex; align-items: center; gap: 16px; }
.cc-hero-badge {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    background: rgba(13,158,106,.15); border: 1px solid rgba(13,158,106,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #34d399;
}
.cc-hero-title { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -.4px; line-height: 1; }
.cc-hero-sub   { font-size: 12px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.cc-back {
    position: relative;
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.cc-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── ERRORS ── */
.cc-err {
    display: flex; flex-direction: column; gap: 4px;
    padding: 14px 18px; border-radius: var(--rlg);
    background: var(--rlt); border: 1px solid var(--rbd);
    margin-bottom: 20px; animation: fd .25s ease;
}
.cc-err-title { font-size: 12.5px; font-weight: 800; color: var(--red); display: flex; align-items: center; gap: 7px; }
.cc-err ul { margin: 6px 0 0 18px; padding: 0; }
.cc-err li { font-size: 12px; font-weight: 500; color: #991b1b; }
@keyframes fd { from { opacity:0; transform:translateY(-6px); } to { opacity:1; } }

/* ── SECTION CARD ── */
.cc-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden; margin-bottom: 16px;
}
.cc-card-head {
    display: flex; align-items: center; gap: 9px;
    padding: 15px 20px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.cc-card-head i { font-size: 13px; }
.cc-card-head i.grn { color: var(--grn); }
.cc-card-head i.blue { color: var(--blue); }
.cc-card-head i.amb  { color: var(--amb); }
.cc-card-head i.ink3 { color: var(--ink3); }
.cc-card-title { font-size: 12.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .4px; }
.cc-card-body  { padding: 20px; }

/* ── FORM GRID ── */
.cc-fg { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.cc-fg.s1 { grid-template-columns: 1fr; }
.cc-fg.s3 { grid-template-columns: 1fr 1fr 1fr; }
.cc-field { display: flex; flex-direction: column; gap: 6px; }
.cc-field.span2 { grid-column: span 2; }
.cc-label {
    font-size: 10.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .6px;
}
.cc-label .req { color: var(--red); margin-left: 2px; }
.cc-input, .cc-select, .cc-textarea {
    padding: 9px 12px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none;
    transition: border-color .15s, box-shadow .15s; width: 100%; appearance: none;
}
.cc-input:focus, .cc-select:focus, .cc-textarea:focus {
    border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09);
}
.cc-input.err { border-color: var(--red); background: var(--rlt); }
.cc-sw { position: relative; }
.cc-sa { position: absolute; right: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--ink3); font-size: 10px; }
.cc-hint { font-size: 11px; font-weight: 500; color: var(--ink3); }

/* ── PASSWORD INPUT ── */
.cc-pass-wrap { position: relative; }
.cc-pass-wrap .cc-input { padding-right: 40px; }
.cc-pass-eye {
    position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: var(--ink3);
    font-size: 13px; padding: 2px; transition: color .13s;
}
.cc-pass-eye:hover { color: var(--ink2); }

/* ── CHECKBOX GRID ── */
.cc-check-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    padding: 14px; background: var(--bg); border: 1px solid var(--bd2);
    border-radius: var(--rlg);
}
.cc-check-item {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 10px; border-radius: var(--r);
    border: 1px solid var(--bd); background: var(--surf);
    cursor: pointer; transition: all .13s;
}
.cc-check-item:hover { border-color: var(--grn); background: var(--glt); }
.cc-check-item input[type="checkbox"] { display: none; }
.cc-check-box {
    width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0;
    border: 1.5px solid var(--bd); background: var(--surf);
    display: flex; align-items: center; justify-content: center;
    transition: all .13s; font-size: 9px; color: #fff;
}
.cc-check-item input:checked ~ .cc-check-box {
    background: var(--grn); border-color: var(--grn);
}
.cc-check-item input:checked ~ .cc-check-box::after { content: '✓'; }
.cc-check-item input:checked ~ .cc-check-lbl { color: var(--grn); font-weight: 700; }
.cc-check-lbl { font-size: 12px; font-weight: 500; color: var(--ink2); }

/* ── TOGGLE SWITCH ── */
.cc-toggle-wrap { display: flex; align-items: center; gap: 10px; }
.cc-toggle { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.cc-toggle input { opacity: 0; width: 0; height: 0; }
.cc-toggle-slider {
    position: absolute; inset: 0; border-radius: 9999px;
    background: var(--bd); cursor: pointer; transition: background .2s;
}
.cc-toggle-slider::before {
    content: ''; position: absolute;
    width: 18px; height: 18px; border-radius: 50%; background: #fff;
    left: 3px; top: 3px; transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.cc-toggle input:checked + .cc-toggle-slider { background: var(--grn); }
.cc-toggle input:checked + .cc-toggle-slider::before { transform: translateX(20px); }
.cc-toggle-lbl { font-size: 13px; font-weight: 600; color: var(--ink2); }

/* ── SELECT2 OVERRIDE ── */
.select2-container .select2-selection--multiple {
    border: 1px solid var(--bd) !important;
    border-radius: var(--r) !important;
    min-height: 40px !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13px !important;
    padding: 3px 8px !important;
    outline: none !important;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: var(--blue) !important;
    box-shadow: 0 0 0 3px rgba(24,85,224,.09) !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: var(--glt) !important; color: var(--grn) !important;
    border: 1px solid var(--gbd) !important; border-radius: 6px !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 11px !important; font-weight: 700 !important;
    padding: 2px 8px !important;
}
.select2-container--default .select2-results__option--highlighted {
    background-color: var(--blt) !important; color: var(--blue) !important;
}
.select2-dropdown { border: 1px solid var(--bd) !important; border-radius: var(--rlg) !important; font-family: 'Montserrat', sans-serif !important; }

/* ── FOOTER ── */
.cc-footer {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    padding: 18px 22px; background: var(--bg);
    border: 1px solid var(--bd); border-radius: var(--rlg);
    margin-top: 4px;
}
.cc-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--r);
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.cc-btn i { font-size: 11px; }
.cc-btn-grn   { background: var(--grn); color: #fff; }
.cc-btn-grn:hover   { background: #0a8559; color: #fff; }
.cc-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.cc-btn-ghost:hover { background: var(--bg); color: var(--ink); }
.cc-btn-red   { background: var(--rlt); color: var(--red); border-color: var(--rbd); }
.cc-btn-red:hover   { background: var(--red); color: #fff; }

@media (max-width: 768px) {
    .cc { padding: 16px; }
    .cc-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .cc-fg { grid-template-columns: 1fr; }
    .cc-fg.s3 { grid-template-columns: 1fr; }
    .cc-field.span2 { grid-column: span 1; }
    .cc-check-grid { grid-template-columns: 1fr; }
}
</style>

<div class="cc">

    {{-- ── HERO ── --}}
    <div class="cc-hero">
        <div class="cc-hero-glow"></div>
        <div class="cc-hero-accent"></div>
        <div class="cc-hero-grid"></div>

        <div class="cc-hero-left">
            <div class="cc-hero-badge">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <div class="cc-hero-title">New Crew Manager</div>
                <div class="cc-hero-sub">Fill in the details to create a new crew manager account</div>
            </div>
        </div>

        <a href="{{ route('superadmin.subcontractors.index') }}" class="cc-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to list
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="cc-err">
        <div class="cc-err-title">
            <i class="fas fa-exclamation-circle"></i> Please fix the following errors:
        </div>
        <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.subcontractors.store') }}" id="cc-form">
        @csrf

        {{-- ── PERSONAL INFO ── --}}
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="fas fa-user grn"></i>
                <span class="cc-card-title">Personal Information</span>
            </div>
            <div class="cc-card-body">
                <div class="cc-fg">

                    <div class="cc-field">
                        <label class="cc-label">First Name <span class="req">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="cc-input {{ $errors->has('name') ? 'err' : '' }}" required>
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                               class="cc-input">
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Company Name <span class="req">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                               class="cc-input {{ $errors->has('company_name') ? 'err' : '' }}" required>
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Email <span class="req">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="cc-input {{ $errors->has('email') ? 'err' : '' }}" required>
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="cc-input">
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">State <span class="req">*</span></label>
                        <input type="text" name="state" value="{{ old('state') }}"
                               class="cc-input {{ $errors->has('state') ? 'err' : '' }}"
                               placeholder="e.g. Florida" required>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── ROOFING SPECIALTIES ── --}}
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="fas fa-home grn"></i>
                <span class="cc-card-title">Roofing Specialties</span>
            </div>
            <div class="cc-card-body">
                <div class="cc-fg">

                    <div class="cc-field">
                        <label class="cc-label">Residential Roof Types</label>
                        <div class="cc-check-grid">
                            @foreach(['TPO','Low Slope','Tile','Wood Shakes','Asphalt Shingle','Metal'] as $roof)
                            <label class="cc-check-item">
                                <input type="checkbox" name="residential_roof_types[]"
                                       value="{{ $roof }}"
                                       {{ in_array($roof, old('residential_roof_types', [])) ? 'checked' : '' }}>
                                <span class="cc-check-box"></span>
                                <span class="cc-check-lbl">{{ $roof }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Commercial Roof Types</label>
                        <div class="cc-check-grid">
                            @foreach(['EPDM','Asphalt Shingle','Low Slope','TPO','Tar & Gravel','Metal'] as $roof)
                            <label class="cc-check-item">
                                <input type="checkbox" name="commercial_roof_types[]"
                                       value="{{ $roof }}"
                                       {{ in_array($roof, old('commercial_roof_types', [])) ? 'checked' : '' }}>
                                <span class="cc-check-box"></span>
                                <span class="cc-check-lbl">{{ $roof }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── WORK LOCATIONS ── --}}
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="fas fa-map-marked-alt blue"></i>
                <span class="cc-card-title">Work Locations</span>
            </div>
            <div class="cc-card-body">
                @php
                    $selectedStates = old('states_you_can_work', []);
                    $allStates = ['Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','West Virginia','Wisconsin','Wyoming'];
                @endphp
                <div class="cc-fg">

                    <div class="cc-field">
                        <label class="cc-label">States you can work in</label>
                        <select name="states_you_can_work[]" class="cc-states-select" multiple
                                data-placeholder="Select states…" id="states-select">
                            @foreach($allStates as $state)
                                <option value="{{ $state }}" {{ in_array($state, $selectedStates) ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="cc-field" style="justify-content:flex-end;padding-bottom:4px">
                        <label class="cc-label">Options</label>
                        <label class="cc-check-item" style="width:fit-content">
                            <input type="checkbox" name="all_states" id="all_states" value="1"
                                   {{ old('all_states') ? 'checked' : '' }}>
                            <span class="cc-check-box"></span>
                            <span class="cc-check-lbl">I can work in all states</span>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── ACCOUNT SETTINGS ── --}}
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="fas fa-lock amb"></i>
                <span class="cc-card-title">Account Settings</span>
            </div>
            <div class="cc-card-body">
                <div class="cc-fg">

                    <div class="cc-field">
                        <label class="cc-label">Password</label>
                        <div class="cc-pass-wrap">
                            <input type="password" name="password" id="pwd"
                                   class="cc-input {{ $errors->has('password') ? 'err' : '' }}"
                                   placeholder="Min. 8 characters">
                            <button type="button" class="cc-pass-eye" onclick="togglePwd('pwd','eye1')">
                                <i class="fas fa-eye" id="eye1"></i>
                            </button>
                        </div>
                        <span class="cc-hint">Minimum 8 characters</span>
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Confirm Password</label>
                        <div class="cc-pass-wrap">
                            <input type="password" name="password_confirmation" id="pwd2"
                                   class="cc-input" placeholder="Repeat password">
                            <button type="button" class="cc-pass-eye" onclick="togglePwd('pwd2','eye2')">
                                <i class="fas fa-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>

                    <div class="cc-field" style="justify-content:flex-end;padding-bottom:4px">
                        <label class="cc-label">Account Status</label>
                        <div class="cc-toggle-wrap">
                            <label class="cc-toggle">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                                <span class="cc-toggle-slider"></span>
                            </label>
                            <span class="cc-toggle-lbl" id="toggle-lbl">Active account</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── FOOTER ── --}}
        <div class="cc-footer">
            <button type="button" onclick="resetForm()" class="cc-btn cc-btn-red">
                <i class="fas fa-undo"></i> Reset
            </button>
            <a href="{{ route('superadmin.subcontractors.index') }}" class="cc-btn cc-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="cc-btn cc-btn-grn">
                <i class="fas fa-floppy-disk"></i> Save Crew Manager
            </button>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
/* ── SELECT2 ── */
$(document).ready(function() {
    $('#states-select').select2({
        placeholder: 'Select states…',
        allowClear: true,
        closeOnSelect: false,
        width: '100%',
    });

    /* All states toggle */
    $('#all_states').on('change', function() {
        const sel = $('#states-select');
        if (this.checked) {
            sel.val(null).trigger('change').prop('disabled', true);
        } else {
            sel.prop('disabled', false);
        }
    });

    @if(old('all_states'))
    $('#all_states').prop('checked', true).trigger('change');
    @endif
});

/* ── PASSWORD VISIBILITY ── */
function togglePwd(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const show = inp.type === 'password';
    inp.type   = show ? 'text' : 'password';
    icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
}

/* ── ACTIVE TOGGLE LABEL ── */
document.getElementById('is_active').addEventListener('change', function() {
    document.getElementById('toggle-lbl').textContent = this.checked ? 'Active account' : 'Inactive account';
});

/* ── RESET CONFIRM ── */
function resetForm() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Reset form?',
            text: 'All entered data will be cleared.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, reset',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) document.getElementById('cc-form').reset(); });
    } else {
        if (confirm('Reset form?')) document.getElementById('cc-form').reset();
    }
}
</script>

@endsection