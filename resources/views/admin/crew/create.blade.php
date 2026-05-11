@extends('admin.layouts.superadmin')
@section('title', 'Create Crew')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
    background: radial-gradient(ellipse, rgba(24,85,224,.35) 0%, transparent 70%);
    right: -40px; top: -40px;
}
.cc-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#4f80ff 0%,#1855e0 50%,transparent 100%);
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
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #8aadff;
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
    padding: 14px 18px; border-radius: var(--rlg);
    background: var(--rlt); border: 1px solid var(--rbd);
    margin-bottom: 20px; animation: fd .25s ease;
}
.cc-err-title { font-size: 12.5px; font-weight: 800; color: var(--red); display: flex; align-items: center; gap: 7px; margin-bottom: 6px; }
.cc-err ul { margin: 0 0 0 18px; }
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
.cc-card-head-icon { font-size: 13px; color: var(--blue); }
.cc-card-title { font-size: 12.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .4px; }
.cc-card-body  { padding: 20px; }

/* ── FORM GRID ── */
.cc-fg { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.cc-field { display: flex; flex-direction: column; gap: 6px; }
.cc-label {
    font-size: 10.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .6px;
}
.cc-label .req { color: var(--red); margin-left: 2px; }
.cc-iw { position: relative; }
.cc-ii { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--ink3); font-size: 12px; pointer-events: none; }
.cc-input {
    padding: 9px 12px 9px 34px;
    border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s;
}
.cc-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.cc-input.err   { border-color: var(--red); background: var(--rlt); }
.cc-field-err   { font-size: 11px; font-weight: 600; color: var(--red); display: flex; align-items: center; gap: 4px; }

/* ── TOGGLE ROW ── */
.cc-toggle-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px; border: 1px solid var(--bd2);
    border-radius: var(--rlg); background: var(--bg);
}
.cc-toggle-left  { display: flex; align-items: center; gap: 12px; }
.cc-toggle-lbl   { font-size: 13px; font-weight: 700; color: var(--ink); }
.cc-toggle-hint  { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 1px; }
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
.cc-toggle input:checked + .cc-toggle-slider { background: var(--blue); }
.cc-toggle input:checked + .cc-toggle-slider::before { transform: translateX(20px); }
.cc-status-badge {
    font-size: 10.5px; font-weight: 800; padding: 4px 10px;
    border-radius: 9999px; text-transform: uppercase; letter-spacing: .4px;
    display: inline-flex; align-items: center; gap: 5px;
}
.cc-status-badge.on  { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.cc-status-badge.off { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }
.cc-status-badge.trailer-on  { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.cc-status-badge.trailer-off { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }

/* ── STATES ── */
.cc-states-controls {
    display: flex; align-items: center; gap: 8px; margin-bottom: 14px;
    flex-wrap: wrap;
}
.cc-states-ctrl-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--r);
    font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
}
.cc-states-ctrl-btn.all   { background: var(--blt); color: var(--blue); border-color: var(--bbd); }
.cc-states-ctrl-btn.all:hover   { background: #dbeafe; }
.cc-states-ctrl-btn.none  { background: var(--bg);  color: var(--ink2); border-color: var(--bd); }
.cc-states-ctrl-btn.none:hover  { background: var(--bd2); }
.cc-states-count { margin-left: auto; font-size: 11.5px; font-weight: 700; color: var(--ink3); }
.cc-states-count span { color: var(--blue); }

.cc-states-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px,1fr));
    gap: 7px;
    max-height: 340px; overflow-y: auto;
    padding: 2px;
    scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg);
}
.cc-states-grid::-webkit-scrollbar { width: 4px; }
.cc-states-grid::-webkit-scrollbar-track { background: var(--bg); }
.cc-states-grid::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

.cc-state-item { display: none; } /* real checkbox hidden */
.cc-state-label {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 10px; border: 1px solid var(--bd);
    border-radius: var(--r); cursor: pointer;
    background: var(--surf); transition: all .13s;
    user-select: none;
}
.cc-state-label:hover { border-color: var(--blue); background: var(--blt); }
.cc-state-item:checked + .cc-state-label {
    border-color: var(--blue); background: var(--blt);
}
.cc-state-box {
    width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0;
    border: 1.5px solid var(--bd); background: var(--surf);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; color: #fff; transition: all .13s;
}
.cc-state-item:checked + .cc-state-label .cc-state-box {
    background: var(--blue); border-color: var(--blue);
}
.cc-state-item:checked + .cc-state-label .cc-state-box::after { content: '✓'; }
.cc-state-name { font-size: 12px; font-weight: 600; color: var(--ink2); }
.cc-state-code { font-size: 10px; font-weight: 700; color: var(--ink3); }

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
.cc-btn-blue  { background: var(--blue); color: #fff; }
.cc-btn-blue:hover  { background: #1344c2; color: #fff; }
.cc-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.cc-btn-ghost:hover { background: var(--bg); color: var(--ink); }

@media (max-width: 768px) {
    .cc { padding: 16px; }
    .cc-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .cc-fg { grid-template-columns: 1fr; }
    .cc-states-grid { grid-template-columns: repeat(auto-fill, minmax(120px,1fr)); }
}
</style>

<div class="cc">

    {{-- ── HERO ── --}}
    <div class="cc-hero">
        <div class="cc-hero-glow"></div>
        <div class="cc-hero-accent"></div>
        <div class="cc-hero-grid"></div>
        <div class="cc-hero-left">
            <div class="cc-hero-badge"><i class="fas fa-user-plus"></i></div>
            <div>
                <div class="cc-hero-title">Create New Crew</div>
                <div class="cc-hero-sub">Register a new work team with all necessary details</div>
            </div>
        </div>
        <a href="{{ route('superadmin.crew.index') }}" class="cc-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Crews
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="cc-err">
        <div class="cc-err-title"><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.crew.store') }}" id="crew-form">
        @csrf

        {{-- ── BASIC INFORMATION ── --}}
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="fas fa-info-circle cc-card-head-icon"></i>
                <span class="cc-card-title">Basic Information</span>
            </div>
            <div class="cc-card-body">
                <div class="cc-fg">

                    <div class="cc-field">
                        <label class="cc-label">Crew Name <span class="req">*</span></label>
                        <div class="cc-iw">
                            <i class="fas fa-users cc-ii"></i>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="cc-input {{ $errors->has('name') ? 'err' : '' }}"
                                   placeholder="Enter crew name" required>
                        </div>
                        @error('name')<div class="cc-field-err"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Company <span class="req">*</span></label>
                        <div class="cc-iw">
                            <i class="fas fa-building cc-ii"></i>
                            <input type="text" name="company" value="{{ old('company') }}"
                                   class="cc-input {{ $errors->has('company') ? 'err' : '' }}"
                                   placeholder="Company name" required>
                        </div>
                        @error('company')<div class="cc-field-err"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Email Address <span class="req">*</span></label>
                        <div class="cc-iw">
                            <i class="fas fa-envelope cc-ii"></i>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="cc-input {{ $errors->has('email') ? 'err' : '' }}"
                                   placeholder="crew@example.com" required>
                        </div>
                        @error('email')<div class="cc-field-err"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>

                    <div class="cc-field">
                        <label class="cc-label">Phone Number</label>
                        <div class="cc-iw">
                            <i class="fas fa-phone cc-ii"></i>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="cc-input" id="phone-input"
                                   placeholder="(123) 456-7890">
                        </div>
                        @error('phone')<div class="cc-field-err"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── TRAILER + STATUS ── --}}
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="fas fa-toggle-on cc-card-head-icon"></i>
                <span class="cc-card-title">Status & Equipment</span>
            </div>
            <div class="cc-card-body" style="display:flex;flex-direction:column;gap:10px">

                {{-- Trailer --}}
                <div class="cc-toggle-row">
                    <div class="cc-toggle-left">
                        <label class="cc-toggle">
                            <input type="hidden" name="has_trailer" value="0">
                            <input type="checkbox" name="has_trailer" id="toggle-trailer" value="1"
                                   {{ old('has_trailer') ? 'checked' : '' }}
                                   onchange="updateBadge('trailer-badge','trailer-badge','trailer-on','trailer-off',
                                       '<i class=\'fas fa-truck\' style=\'font-size:9px\'></i> With Trailer',
                                       '<i class=\'fas fa-ban\' style=\'font-size:9px\'></i> No Trailer',this.checked)">
                            <span class="cc-toggle-slider"></span>
                        </label>
                        <div>
                            <div class="cc-toggle-lbl">Has Trailer</div>
                            <div class="cc-toggle-hint">This crew operates with its own trailer</div>
                        </div>
                    </div>
                    <span class="cc-status-badge {{ old('has_trailer') ? 'trailer-on' : 'trailer-off' }}" id="trailer-badge">
                        <i class="fas fa-{{ old('has_trailer') ? 'truck' : 'ban' }}" style="font-size:9px"></i>
                        {{ old('has_trailer') ? 'With Trailer' : 'No Trailer' }}
                    </span>
                </div>

                {{-- Active --}}
                <div class="cc-toggle-row">
                    <div class="cc-toggle-left">
                        <label class="cc-toggle">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="toggle-active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   onchange="updateBadge('active-badge','active-badge','on','off',
                                       '<i class=\'fas fa-check-circle\' style=\'font-size:9px\'></i> Active',
                                       '<i class=\'fas fa-times-circle\' style=\'font-size:9px\'></i> Inactive',this.checked)">
                            <span class="cc-toggle-slider"></span>
                        </label>
                        <div>
                            <div class="cc-toggle-lbl">Active Crew</div>
                            <div class="cc-toggle-hint">Crew will be available for job assignments</div>
                        </div>
                    </div>
                    <span class="cc-status-badge {{ old('is_active', true) ? 'on' : 'off' }}" id="active-badge">
                        <i class="fas fa-{{ old('is_active', true) ? 'check-circle' : 'times-circle' }}" style="font-size:9px"></i>
                        {{ old('is_active', true) ? 'Active' : 'Inactive' }}
                    </span>
                </div>

            </div>
        </div>

        {{-- ── OPERATING STATES ── --}}
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="fas fa-map-marker-alt cc-card-head-icon"></i>
                <span class="cc-card-title">Operating States</span>
            </div>
            <div class="cc-card-body">

                <div class="cc-states-controls">
                    <button type="button" class="cc-states-ctrl-btn all" onclick="selectStates(true)">
                        <i class="fas fa-check-square" style="font-size:11px"></i> Select All
                    </button>
                    <button type="button" class="cc-states-ctrl-btn none" onclick="selectStates(false)">
                        <i class="fas fa-times-circle" style="font-size:11px"></i> Deselect All
                    </button>
                    <div class="cc-states-count"><span id="state-count">0</span> selected</div>
                </div>

                @php
                    $states = [
                        'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas',
                        'CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware',
                        'FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho',
                        'IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas',
                        'KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland',
                        'MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi',
                        'MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada',
                        'NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York',
                        'NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma',
                        'OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina',
                        'SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah',
                        'VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia',
                        'WI'=>'Wisconsin','WY'=>'Wyoming'
                    ];
                    $selected = old('states', []);
                @endphp

                <div class="cc-states-grid">
                    @foreach($states as $code => $name)
                    <div>
                        <input type="checkbox" name="states[]" value="{{ $code }}"
                               id="s{{ $code }}" class="cc-state-item"
                               {{ in_array($code, $selected) ? 'checked' : '' }}
                               onchange="countStates()">
                        <label for="s{{ $code }}" class="cc-state-label">
                            <span class="cc-state-box"></span>
                            <div>
                                <div class="cc-state-name">{{ $name }}</div>
                                <div class="cc-state-code">{{ $code }}</div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>

                @error('states')
                <div class="cc-field-err" style="margin-top:10px">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        {{-- ── FOOTER ── --}}
        <div class="cc-footer">
            <a href="{{ route('superadmin.crew.index') }}" class="cc-btn cc-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="cc-btn cc-btn-blue">
                <i class="fas fa-floppy-disk"></i> Create Crew
            </button>
        </div>

    </form>
</div>

<script>
/* ── TOGGLE BADGE ── */
function updateBadge(id, _, onClass, offClass, onHtml, offHtml, checked) {
    const badge = document.getElementById(id);
    badge.className = 'cc-status-badge ' + (checked ? onClass : offClass);
    badge.innerHTML  = checked ? onHtml : offHtml;
}

/* ── STATE COUNT ── */
function countStates() {
    const n = document.querySelectorAll('.cc-state-item:checked').length;
    document.getElementById('state-count').textContent = n;
}
function selectStates(all) {
    document.querySelectorAll('.cc-state-item').forEach(cb => { cb.checked = all; });
    countStates();
}

/* ── PHONE FORMAT ── */
document.getElementById('phone-input').addEventListener('input', function() {
    let v = this.value.replace(/\D/g,'');
    const m = v.match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
    this.value = !m[2] ? m[1] : '(' + m[1] + ') ' + m[2] + (m[3] ? '-' + m[3] : '');
});

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', countStates);
</script>

@endsection