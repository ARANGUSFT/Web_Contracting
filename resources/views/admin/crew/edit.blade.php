@extends('admin.layouts.superadmin')
@section('title', 'Edit Crew · ' . $crew->name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ce { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1200px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --pur:  #7c22e8; --plt:  #f5f0ff; --pbd:  #ddd0fb;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.ce-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 36px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ce-hero-glow {
    position: absolute; pointer-events: none;
    width: 500px; height: 260px;
    background: radial-gradient(ellipse, rgba(24,85,224,.35) 0%, transparent 70%);
    right: -40px; top: -40px;
}
.ce-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#4f80ff 0%,#1855e0 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.ce-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.ce-hero-left { position: relative; display: flex; align-items: center; gap: 16px; }
.ce-hero-badge {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #8aadff;
}
.ce-hero-title { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -.4px; line-height: 1; }
.ce-hero-sub   { font-size: 12px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.ce-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }
.ce-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.ce-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── LAYOUT ── */
.ce-layout { display: grid; grid-template-columns: 1fr 280px; gap: 18px; align-items: start; }

/* ── CARD ── */
.ce-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden; margin-bottom: 16px;
}
.ce-card-head {
    display: flex; align-items: center; gap: 9px;
    padding: 15px 20px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.ce-card-head i { font-size: 13px; color: var(--blue); }
.ce-card-title  { font-size: 12.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .4px; }
.ce-card-body   { padding: 20px; }

/* ── ERRORS ── */
.ce-err {
    padding: 14px 18px; border-radius: var(--rlg);
    background: var(--rlt); border: 1px solid var(--rbd);
    margin-bottom: 18px; animation: fd .25s ease;
}
.ce-err-title { font-size: 12.5px; font-weight: 800; color: var(--red); display: flex; align-items: center; gap: 7px; margin-bottom: 6px; }
.ce-err ul { margin: 0 0 0 18px; }
.ce-err li { font-size: 12px; font-weight: 500; color: #991b1b; }
@keyframes fd { from { opacity:0; transform:translateY(-6px); } to { opacity:1; } }

/* ── FORM ── */
.ce-fg { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.ce-field { display: flex; flex-direction: column; gap: 6px; }
.ce-label {
    font-size: 10.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .6px;
}
.ce-label .req { color: var(--red); margin-left: 2px; }
.ce-iw { position: relative; }
.ce-ii { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--ink3); font-size: 12px; pointer-events: none; }
.ce-input {
    padding: 9px 12px 9px 34px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s;
}
.ce-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.ce-input.err   { border-color: var(--red); background: var(--rlt); }

/* ── TOGGLE ROW ── */
.ce-toggle-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px; border: 1px solid var(--bd2);
    border-radius: var(--rlg); background: var(--bg); margin-bottom: 10px;
}
.ce-toggle-row:last-child { margin-bottom: 0; }
.ce-toggle-left  { display: flex; align-items: center; gap: 12px; }
.ce-toggle-lbl   { font-size: 13px; font-weight: 700; color: var(--ink); }
.ce-toggle-hint  { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 1px; }
.ce-toggle { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.ce-toggle input { opacity: 0; width: 0; height: 0; }
.ce-toggle-slider {
    position: absolute; inset: 0; border-radius: 9999px;
    background: var(--bd); cursor: pointer; transition: background .2s;
}
.ce-toggle-slider::before {
    content: ''; position: absolute;
    width: 18px; height: 18px; border-radius: 50%; background: #fff;
    left: 3px; top: 3px; transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.ce-toggle input:checked + .ce-toggle-slider { background: var(--blue); }
.ce-toggle input:checked + .ce-toggle-slider::before { transform: translateX(20px); }
.ce-status-badge {
    font-size: 10.5px; font-weight: 800; padding: 4px 10px;
    border-radius: 9999px; text-transform: uppercase; letter-spacing: .4px;
    display: inline-flex; align-items: center; gap: 5px;
}
.ce-status-badge.on      { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.ce-status-badge.off     { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }
.ce-status-badge.tr-on   { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.ce-status-badge.tr-off  { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }

/* ── STATES ── */
.ce-states-controls {
    display: flex; align-items: center; gap: 8px; margin-bottom: 14px; flex-wrap: wrap;
}
.ce-states-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--r);
    font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
}
.ce-states-btn.all   { background: var(--blt); color: var(--blue); border-color: var(--bbd); }
.ce-states-btn.all:hover   { background: #dbeafe; }
.ce-states-btn.none  { background: var(--bg);  color: var(--ink2); border-color: var(--bd); }
.ce-states-btn.none:hover  { background: var(--bd2); }
.ce-states-count { margin-left: auto; font-size: 11.5px; font-weight: 700; color: var(--ink3); }
.ce-states-count span { color: var(--blue); }

.ce-states-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px,1fr));
    gap: 7px; max-height: 340px; overflow-y: auto;
    padding: 2px;
    scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg);
}
.ce-states-grid::-webkit-scrollbar { width: 4px; }
.ce-states-grid::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

.ce-state-cb { display: none; }
.ce-state-label {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 10px; border: 1px solid var(--bd);
    border-radius: var(--r); cursor: pointer;
    background: var(--surf); transition: all .13s; user-select: none;
}
.ce-state-label:hover { border-color: var(--blue); background: var(--blt); }
.ce-state-cb:checked + .ce-state-label { border-color: var(--blue); background: var(--blt); }
.ce-state-box {
    width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0;
    border: 1.5px solid var(--bd); background: var(--surf);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; color: #fff; transition: all .13s;
}
.ce-state-cb:checked + .ce-state-label .ce-state-box { background: var(--blue); border-color: var(--blue); }
.ce-state-cb:checked + .ce-state-label .ce-state-box::after { content: '✓'; }
.ce-state-name { font-size: 12px; font-weight: 600; color: var(--ink2); }
.ce-state-code { font-size: 10px; font-weight: 700; color: var(--ink3); }

/* ── FOOTER ── */
.ce-footer {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    padding: 16px 20px; background: var(--bg);
    border: 1px solid var(--bd); border-radius: var(--rlg);
    margin-top: 4px;
}
.ce-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--r);
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.ce-btn i { font-size: 11px; }
.ce-btn-blue  { background: var(--blue); color: #fff; }
.ce-btn-blue:hover  { background: #1344c2; color: #fff; }
.ce-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.ce-btn-ghost:hover { background: var(--bg); color: var(--ink); }

/* ── SIDEBAR SUMMARY ── */
.ce-summary-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
    position: sticky; top: 90px;
}
.ce-summary-head {
    display: flex; align-items: center; gap: 9px;
    padding: 14px 18px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.ce-summary-title { font-size: 12.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .4px; }
.ce-summary-body  { padding: 16px; }

.ce-sum-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 12px; border: 1px solid var(--bd2);
    border-radius: var(--rlg); margin-bottom: 8px; background: var(--surf);
}
.ce-sum-item:last-child { margin-bottom: 0; }
.ce-sum-icon {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
}
.ce-sum-icon.blue { background: var(--blt); color: var(--blue); }
.ce-sum-icon.grn  { background: var(--glt); color: var(--grn); }
.ce-sum-icon.pur  { background: var(--plt); color: var(--pur); }
.ce-sum-icon.amb  { background: var(--alt); color: var(--amb); }
.ce-sum-key  { font-size: 10.5px; font-weight: 700; color: var(--ink3); text-transform: uppercase; letter-spacing: .4px; }
.ce-sum-val  { font-size: 12.5px; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.ce-states-tags { display: flex; flex-wrap: wrap; gap: 5px; padding: 14px 16px; border-top: 1px solid var(--bd2); }
.ce-state-tag {
    font-size: 10.5px; font-weight: 700; padding: 3px 8px;
    border-radius: 6px; background: var(--blt); color: var(--blue);
    border: 1px solid var(--bbd);
}

@media (max-width: 1024px) {
    .ce-layout { grid-template-columns: 1fr; }
    .ce-summary-card { position: static; }
}
@media (max-width: 640px) {
    .ce { padding: 16px; }
    .ce-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .ce-fg { grid-template-columns: 1fr; }
    .ce-states-grid { grid-template-columns: repeat(auto-fill, minmax(120px,1fr)); }
}
</style>

<div class="ce">

    {{-- ── HERO ── --}}
    <div class="ce-hero">
        <div class="ce-hero-glow"></div>
        <div class="ce-hero-accent"></div>
        <div class="ce-hero-grid"></div>
        <div class="ce-hero-left">
            <div class="ce-hero-badge"><i class="fas fa-users-gear"></i></div>
            <div>
                <div class="ce-hero-title">Edit Crew</div>
                <div class="ce-hero-sub">{{ $crew->name }} · {{ $crew->company }}</div>
            </div>
        </div>
        <a href="{{ route('superadmin.crew.index') }}" class="ce-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Crews
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="ce-err">
        <div class="ce-err-title"><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    @php
        $stateMap = [
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
        $selStates = is_array($crew->states ?? null) ? ($crew->states ?? []) : [];
        $selStates = old('states', $selStates);
    @endphp

    <div class="ce-layout">

        {{-- ── FORM ── --}}
        <div>
            <form method="POST" action="{{ route('superadmin.crew.update', $crew) }}" id="ce-form">
                @csrf @method('PUT')

                {{-- Basic Info ── --}}
                <div class="ce-card">
                    <div class="ce-card-head">
                        <i class="fas fa-info-circle"></i>
                        <span class="ce-card-title">Basic Information</span>
                    </div>
                    <div class="ce-card-body">
                        <div class="ce-fg">

                            <div class="ce-field">
                                <label class="ce-label">Crew Name <span class="req">*</span></label>
                                <div class="ce-iw">
                                    <i class="fas fa-users ce-ii"></i>
                                    <input type="text" name="name" value="{{ old('name', $crew->name) }}"
                                           class="ce-input {{ $errors->has('name') ? 'err' : '' }}"
                                           placeholder="Enter crew name" required>
                                </div>
                            </div>

                            <div class="ce-field">
                                <label class="ce-label">Company <span class="req">*</span></label>
                                <div class="ce-iw">
                                    <i class="fas fa-building ce-ii"></i>
                                    <input type="text" name="company" value="{{ old('company', $crew->company) }}"
                                           class="ce-input {{ $errors->has('company') ? 'err' : '' }}"
                                           placeholder="Company name" required>
                                </div>
                            </div>

                            <div class="ce-field">
                                <label class="ce-label">Email Address <span class="req">*</span></label>
                                <div class="ce-iw">
                                    <i class="fas fa-envelope ce-ii"></i>
                                    <input type="email" name="email" value="{{ old('email', $crew->email) }}"
                                           class="ce-input {{ $errors->has('email') ? 'err' : '' }}"
                                           placeholder="crew@example.com" required>
                                </div>
                            </div>

                            <div class="ce-field">
                                <label class="ce-label">Phone Number</label>
                                <div class="ce-iw">
                                    <i class="fas fa-phone ce-ii"></i>
                                    <input type="tel" name="phone" value="{{ old('phone', $crew->phone) }}"
                                           class="ce-input" id="phone-input" placeholder="(123) 456-7890">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Status & Equipment ── --}}
                <div class="ce-card">
                    <div class="ce-card-head">
                        <i class="fas fa-toggle-on"></i>
                        <span class="ce-card-title">Status & Equipment</span>
                    </div>
                    <div class="ce-card-body">

                        {{-- Trailer ── --}}
                        <div class="ce-toggle-row">
                            <div class="ce-toggle-left">
                                <label class="ce-toggle">
                                    <input type="hidden" name="has_trailer" value="0">
                                    <input type="checkbox" name="has_trailer" value="1" id="tog-trailer"
                                           {{ old('has_trailer', $crew->has_trailer) ? 'checked' : '' }}
                                           onchange="ceBadge('badge-trailer','tr-on','tr-off',
                                               '<i class=\'fas fa-truck\' style=\'font-size:9px\'></i> With Trailer',
                                               '<i class=\'fas fa-ban\' style=\'font-size:9px\'></i> No Trailer',this.checked)">
                                    <span class="ce-toggle-slider"></span>
                                </label>
                                <div>
                                    <div class="ce-toggle-lbl">Has Trailer</div>
                                    <div class="ce-toggle-hint">This crew operates with its own trailer</div>
                                </div>
                            </div>
                            <span class="ce-status-badge {{ old('has_trailer', $crew->has_trailer) ? 'tr-on' : 'tr-off' }}" id="badge-trailer">
                                <i class="fas fa-{{ old('has_trailer', $crew->has_trailer) ? 'truck' : 'ban' }}" style="font-size:9px"></i>
                                {{ old('has_trailer', $crew->has_trailer) ? 'With Trailer' : 'No Trailer' }}
                            </span>
                        </div>

                        {{-- Active ── --}}
                        <div class="ce-toggle-row">
                            <div class="ce-toggle-left">
                                <label class="ce-toggle">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" id="tog-active"
                                           {{ old('is_active', $crew->is_active) ? 'checked' : '' }}
                                           onchange="ceBadge('badge-active','on','off',
                                               '<i class=\'fas fa-check-circle\' style=\'font-size:9px\'></i> Active',
                                               '<i class=\'fas fa-times-circle\' style=\'font-size:9px\'></i> Inactive',this.checked)">
                                    <span class="ce-toggle-slider"></span>
                                </label>
                                <div>
                                    <div class="ce-toggle-lbl">Active Crew</div>
                                    <div class="ce-toggle-hint">Crew will be available for job assignments</div>
                                </div>
                            </div>
                            <span class="ce-status-badge {{ old('is_active', $crew->is_active) ? 'on' : 'off' }}" id="badge-active">
                                <i class="fas fa-{{ old('is_active', $crew->is_active) ? 'check-circle' : 'times-circle' }}" style="font-size:9px"></i>
                                {{ old('is_active', $crew->is_active) ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Operating States ── --}}
                <div class="ce-card">
                    <div class="ce-card-head">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="ce-card-title">Operating States</span>
                    </div>
                    <div class="ce-card-body">
                        <div class="ce-states-controls">
                            <button type="button" class="ce-states-btn all" onclick="ceSelectStates(true)">
                                <i class="fas fa-check-square" style="font-size:10px"></i> Select All
                            </button>
                            <button type="button" class="ce-states-btn none" onclick="ceSelectStates(false)">
                                <i class="fas fa-times-circle" style="font-size:10px"></i> Deselect All
                            </button>
                            <div class="ce-states-count"><span id="state-count">0</span> selected</div>
                        </div>

                        <div class="ce-states-grid">
                            @foreach($stateMap as $code => $name)
                            <div>
                                <input type="checkbox" name="states[]" value="{{ $code }}"
                                       id="s{{ $code }}" class="ce-state-cb"
                                       {{ in_array($code, $selStates) ? 'checked' : '' }}
                                       onchange="ceCountStates()">
                                <label for="s{{ $code }}" class="ce-state-label">
                                    <span class="ce-state-box"></span>
                                    <div>
                                        <div class="ce-state-name">{{ $name }}</div>
                                        <div class="ce-state-code">{{ $code }}</div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        @error('states')
                        <div style="font-size:11px;font-weight:600;color:var(--red);display:flex;align-items:center;gap:4px;margin-top:10px">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                {{-- Footer ── --}}
                <div class="ce-footer">
                    <a href="{{ route('superadmin.crew.index') }}" class="ce-btn ce-btn-ghost">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="ce-btn ce-btn-blue" id="submit-btn">
                        <i class="fas fa-floppy-disk"></i> Update Crew
                    </button>
                </div>

            </form>
        </div>

        {{-- ── SIDEBAR SUMMARY ── --}}
        <div>
            <div class="ce-summary-card">
                <div class="ce-summary-head">
                    <i class="fas fa-chart-bar" style="color:var(--ink3);font-size:13px"></i>
                    <span class="ce-summary-title">Crew Summary</span>
                </div>
                <div class="ce-summary-body">

                    <div class="ce-sum-item">
                        <div class="ce-sum-icon blue"><i class="fas fa-users"></i></div>
                        <div style="min-width:0">
                            <div class="ce-sum-key">Crew Name</div>
                            <div class="ce-sum-val">{{ $crew->name }}</div>
                        </div>
                    </div>

                    <div class="ce-sum-item">
                        <div class="ce-sum-icon grn"><i class="fas fa-building"></i></div>
                        <div style="min-width:0">
                            <div class="ce-sum-key">Company</div>
                            <div class="ce-sum-val">{{ $crew->company }}</div>
                        </div>
                    </div>

                    <div class="ce-sum-item">
                        <div class="ce-sum-icon pur"><i class="fas fa-envelope"></i></div>
                        <div style="min-width:0">
                            <div class="ce-sum-key">Email</div>
                            <div class="ce-sum-val" style="font-size:11px">{{ $crew->email }}</div>
                        </div>
                    </div>

                    <div class="ce-sum-item">
                        <div class="ce-sum-icon amb"><i class="fas fa-{{ $crew->is_active ? 'check' : 'times' }}-circle"></i></div>
                        <div>
                            <div class="ce-sum-key">Status</div>
                            <div class="ce-sum-val">{{ $crew->is_active ? 'Active' : 'Inactive' }}</div>
                        </div>
                    </div>

                    @if($crew->subcontractors ?? null)
                    <div class="ce-sum-item">
                        <div class="ce-sum-icon blue"><i class="fas fa-person-digging"></i></div>
                        <div>
                            <div class="ce-sum-key">Subcontractors</div>
                            <div class="ce-sum-val">{{ $crew->subcontractors->count() }} assigned</div>
                        </div>
                    </div>
                    @endif

                </div>

                @if(count($selStates) > 0)
                <div class="ce-states-tags">
                    @foreach($selStates as $code)
                        @if(isset($stateMap[$code]))
                        <span class="ce-state-tag">{{ $code }}</span>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
/* ── BADGE UPDATE ── */
function ceBadge(id, onCls, offCls, onHtml, offHtml, checked) {
    const b = document.getElementById(id);
    b.className = 'ce-status-badge ' + (checked ? onCls : offCls);
    b.innerHTML = checked ? onHtml : offHtml;
}

/* ── STATES COUNT ── */
function ceCountStates() {
    const n = document.querySelectorAll('.ce-state-cb:checked').length;
    document.getElementById('state-count').textContent = n;
}
function ceSelectStates(all) {
    document.querySelectorAll('.ce-state-cb').forEach(cb => { cb.checked = all; });
    ceCountStates();
}

/* ── PHONE FORMAT ── */
document.getElementById('phone-input').addEventListener('input', function() {
    let v = this.value.replace(/\D/g,'');
    const m = v.match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
    this.value = !m[2] ? m[1] : '(' + m[1] + ') ' + m[2] + (m[3] ? '-' + m[3] : '');
});

/* ── SUBMIT ── */
document.getElementById('ce-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:11px"></i> Updating…';
    btn.disabled  = true;
});

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', ceCountStates);
</script>

@endsection