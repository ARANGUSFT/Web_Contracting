@extends('admin.layouts.superadmin')
@section('title', 'Add Locations')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.lc { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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

/* ══ HERO ══ */
.lc-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.lc-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.lc-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.lc-hero-glow {
    position: absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events: none;
}
.lc-hero-l { position: relative; display: flex; align-items: center; gap: 16px; }
.lc-hero-icon {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center; font-size: 20px; color: #8aadff;
}
.lc-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.lc-hero-sub   { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.38); margin-top: 6px; }
.lc-back {
    position: relative; display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
    color: rgba(255,255,255,.55); font-size: 12px; font-weight: 600;
    font-family: 'Montserrat', sans-serif; text-decoration: none; transition: all .13s;
}
.lc-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ══ LAYOUT ══ */
.lc-body { display: grid; grid-template-columns: 1fr 300px; gap: 18px; align-items: start; }
.lc-left { display: flex; flex-direction: column; gap: 16px; }
.lc-right { display: flex; flex-direction: column; gap: 14px; position: sticky; top: 90px; }

/* ══ CARDS ══ */
.lc-card { background: var(--surf); border: 1px solid var(--bd); border-radius: var(--rlg); overflow: hidden; }
.lc-card-h {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.lc-card-h-l { display: flex; align-items: center; gap: 8px; }
.lc-card-h i { font-size: 13px; color: var(--blue); }
.lc-card-title { font-size: 12px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .5px; }
.lc-card-b { padding: 20px; }

/* ══ FIELDS ══ */
.lc-lbl {
    display: block; font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .7px; margin-bottom: 6px;
}
.lc-lbl .req { color: var(--red); margin-left: 2px; }
.lc-sel {
    padding: 10px 36px 10px 13px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none; width: 100%;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238c95a6' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center;
    transition: border-color .15s, box-shadow .15s;
}
.lc-sel:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }

.lc-co-pill {
    display: none; align-items: center; gap: 12px;
    padding: 12px 16px; border-radius: var(--rlg);
    background: var(--blt); border: 1px solid var(--bbd); margin-top: 12px;
}
.lc-co-av {
    width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
    background: var(--blue); display: flex; align-items: center;
    justify-content: center; font-size: 14px; font-weight: 800; color: #fff;
}
.lc-co-name { font-size: 13px; font-weight: 800; color: var(--blue); }
.lc-co-hint { font-size: 11px; font-weight: 500; color: rgba(24,85,224,.5); margin-top: 1px; }

/* ══ STATES TOOLBAR ══ */
.lc-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px; gap: 8px; flex-wrap: wrap;
}
.lc-toolbar-l { display: flex; align-items: center; gap: 8px; }
.lc-en-badge {
    font-size: 11px; font-weight: 800; padding: 3px 10px;
    border-radius: 9999px; background: var(--blt); color: var(--blue);
    border: 1px solid var(--bbd);
}
.lc-expand-btn {
    font-size: 11.5px; font-weight: 700; color: var(--blue);
    background: none; border: none; cursor: pointer; padding: 0;
    font-family: 'Montserrat', sans-serif; transition: color .13s;
}
.lc-expand-btn:hover { color: #1344c2; }

/* ══ STATES SCROLL ══ */
.lc-states-scroll {
    max-height: 580px; overflow-y: auto;
    scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg);
}
.lc-states-scroll::-webkit-scrollbar { width: 4px; }
.lc-states-scroll::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

.lc-states-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }

/* ══ STATE CARD ══ */
.lc-sc {
    border: 1px solid var(--bd); border-radius: var(--rlg);
    overflow: hidden; background: var(--surf); transition: border-color .15s, box-shadow .15s;
}
.lc-sc.on { border-color: var(--blue); box-shadow: 0 1px 8px rgba(24,85,224,.1); }

/* head */
.lc-sc-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 12px; cursor: pointer; transition: background .1s;
    user-select: none;
}
.lc-sc-head:hover { background: var(--bg); }
.lc-sc.on .lc-sc-head { background: var(--blt); }

.lc-sc-head-l { display: flex; align-items: center; gap: 9px; }

/* custom checkbox */
.lc-chk-wrap { display: flex; align-items: center; }
.lc-chk { display: none; }
.lc-chk-box {
    width: 18px; height: 18px; border-radius: 5px; flex-shrink: 0;
    border: 2px solid var(--bd); background: var(--surf);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; color: #fff; transition: all .13s;
}
.lc-sc.on .lc-chk-box { background: var(--blue); border-color: var(--blue); }

.lc-sc-code { font-size: 13px; font-weight: 800; color: var(--ink); }
.lc-sc-name { font-size: 11px; font-weight: 500; color: var(--ink3); }

.lc-sc-toggle {
    width: 22px; height: 22px; border-radius: 6px;
    background: none; border: none; cursor: pointer; color: var(--ink3);
    display: flex; align-items: center; justify-content: center; font-size: 10px;
    transition: all .13s; flex-shrink: 0;
}
.lc-sc-toggle i { transition: transform .2s; }
.lc-sc-toggle.open i { transform: rotate(180deg); }

/* ── base badge inside head ── */
.lc-base-badge {
    font-size: 9.5px; font-weight: 800; padding: 2px 7px;
    border-radius: 6px; background: rgba(24,85,224,.12); color: var(--blue);
    border: 1px solid rgba(24,85,224,.2); white-space: nowrap;
    display: none;
}
.lc-sc.on .lc-base-badge { display: inline-flex; align-items: center; gap: 3px; }

/* body */
.lc-sc-body {
    display: none; padding: 12px 12px 14px;
    background: var(--bg); border-top: 1px solid var(--bbd);
}
.lc-sc-body.open { display: block; }

/* base price info row */
.lc-base-row {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 12px; border-radius: var(--r);
    background: #e8eef7; border: 1px solid #b3c6e0;
    margin-bottom: 12px;
}
.lc-base-row-icon {
    width: 26px; height: 26px; border-radius: 6px; flex-shrink: 0;
    background: rgba(0,51,102,.15); display: flex; align-items: center;
    justify-content: center; font-size: 11px; color: #003366;
}
.lc-base-row-lbl { font-size: 12px; font-weight: 700; color: #003366; }
.lc-base-row-sub { font-size: 10.5px; font-weight: 500; color: rgba(0,51,102,.5); margin-top:1px; }

/* cities section */
.lc-cities-hd {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px;
}
.lc-cities-lbl { font-size: 10px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .6px; }
.lc-add-city {
    font-size: 11px; font-weight: 700; color: var(--grn);
    background: none; border: none; cursor: pointer; padding: 0;
    font-family: 'Montserrat', sans-serif; display: flex; align-items: center; gap: 4px;
    transition: color .13s;
}
.lc-add-city:hover { color: #0a8559; }
.lc-city-list { display: flex; flex-direction: column; gap: 5px; }
.lc-city-row {
    display: flex; align-items: center; gap: 6px;
    background: var(--surf); border: 1px solid var(--bd2); border-radius: var(--r); padding: 5px 9px;
}
.lc-city-input {
    flex: 1; border: none; outline: none; background: transparent;
    font-size: 12px; font-weight: 500; font-family: 'Montserrat', sans-serif; color: var(--ink);
}
.lc-city-input::placeholder { color: var(--ink3); }
.lc-city-input.dup { background: var(--rlt); border-radius: 4px; }
.lc-city-rm {
    width: 20px; height: 20px; border-radius: 5px; flex-shrink: 0;
    background: none; border: none; cursor: pointer; color: var(--ink3);
    display: flex; align-items: center; justify-content: center; font-size: 9px; transition: all .13s;
}
.lc-city-rm:hover { background: var(--rlt); color: var(--red); }
.lc-cities-hint {
    font-size: 10.5px; font-weight: 500; color: var(--ink3);
    margin-top: 8px; display: flex; align-items: center; gap: 4px;
}

/* ══ SIDEBAR ══ */
.lc-summary { background: var(--surf); border: 1px solid var(--bd); border-radius: var(--rlg); overflow: hidden; }
.lc-sum-h {
    display: flex; align-items: center; gap: 8px;
    padding: 13px 16px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.lc-sum-title { font-size: 11.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .5px; }
.lc-sum-b { padding: 14px 16px; display: flex; flex-direction: column; gap: 8px; }
.lc-sum-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 9px 12px; border: 1px solid var(--bd2); border-radius: var(--r); background: var(--bg);
}
.lc-sum-k { font-size: 11.5px; font-weight: 600; color: var(--ink3); display: flex; align-items: center; gap: 6px; }
.lc-sum-k i { font-size: 10px; color: var(--blue); }
.lc-sum-v { font-size: 14px; font-weight: 800; color: var(--ink); }
.lc-sum-v.hi { color: var(--blue); }

.lc-tip { background: var(--blt); border: 1px solid var(--bbd); border-radius: var(--rlg); padding: 14px 16px; }
.lc-tip-ttl { font-size: 11.5px; font-weight: 800; color: var(--blue); display: flex; align-items: center; gap: 6px; margin-bottom: 8px; }
.lc-tip-item { display: flex; align-items: flex-start; gap: 6px; margin-bottom: 7px; font-size: 11px; font-weight: 500; color: var(--ink2); line-height: 1.5; }
.lc-tip-item:last-child { margin-bottom: 0; }
.lc-tip-item i { color: var(--blue); font-size: 9px; margin-top: 3px; flex-shrink: 0; }

/* ══ FOOTER ══ */
.lc-foot {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px; background: var(--bg);
    border: 1px solid var(--bd); border-radius: var(--rlg); margin-top: 4px;
}
.lc-foot-status { font-size: 12px; font-weight: 600; color: var(--ink3); display: flex; align-items: center; gap: 6px; }
.lc-foot-status.ok { color: var(--grn); }
.lc-foot-r { display: flex; gap: 8px; }
.lc-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
    text-decoration: none; white-space: nowrap;
}
.lc-btn i { font-size: 10px; }
.lc-btn-blue  { background: var(--blue); color: #fff; }
.lc-btn-blue:hover  { background: #1344c2; color: #fff; }
.lc-btn-blue:disabled { opacity: .4; cursor: not-allowed; }
.lc-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.lc-btn-ghost:hover { background: var(--bg); color: var(--ink); }

/* ══ SCROLLBAR ══ */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 1100px) { .lc-body { grid-template-columns: 1fr; } .lc-right { position: static; } }
@media (max-width: 768px)  {
    .lc { padding: 16px; }
    .lc-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .lc-states-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 480px)  { .lc-states-grid { grid-template-columns: 1fr; } }
</style>

@php
$allStates = [
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
@endphp

<div class="lc">

    {{-- ══ HERO ══ --}}
    <div class="lc-hero">
        <div class="lc-hero-glow"></div>
        <div class="lc-hero-l">
            <div class="lc-hero-icon"><i class="fas fa-map-location-dot"></i></div>
            <div>
                <div class="lc-hero-title">Add Locations</div>
                <div class="lc-hero-sub">Select states — each state always includes a base price. Add cities optionally.</div>
            </div>
        </div>
        <a href="{{ route('superadmin.locations.index') }}" class="lc-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('superadmin.locations.store') }}" id="lc-form">
        @csrf
        {{-- hidden fields built by JS --}}
        <div id="lc-hidden"></div>

        <div class="lc-body">

            {{-- ══ LEFT ══ --}}
            <div class="lc-left">

                {{-- Company ── --}}
                <div class="lc-card">
                    <div class="lc-card-h">
                        <div class="lc-card-h-l">
                            <i class="fas fa-building"></i>
                            <span class="lc-card-title">Company</span>
                        </div>
                    </div>
                    <div class="lc-card-b">
                        <label class="lc-lbl" for="company-sel">Select Company <span class="req">*</span></label>
                        <select name="user_id" id="company-sel" class="lc-sel" required onchange="lcCompany(this)">
                            <option value="" disabled selected>— Choose a company —</option>
                            @foreach($companies as $co)
                            <option value="{{ $co->id }}" data-name="{{ $co->company_name }}">
                                {{ $co->company_name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="lc-co-pill" id="lc-co-pill">
                            <div class="lc-co-av" id="lc-co-av">?</div>
                            <div>
                                <div class="lc-co-name" id="lc-co-name">—</div>
                                <div class="lc-co-hint">Ready to configure locations</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- States ── --}}
                <div class="lc-card">
                    <div class="lc-card-h">
                        <div class="lc-card-h-l">
                            <i class="fas fa-flag"></i>
                            <span class="lc-card-title">States</span>
                        </div>
                        <div class="lc-toolbar" style="margin:0">
                            <div class="lc-toolbar-l">
                                <span id="lc-en-badge" class="lc-en-badge" style="display:none">
                                    <span id="lc-en-count">0</span> selected
                                </span>
                            </div>
                            <button type="button" class="lc-expand-btn" onclick="lcExpandAll()">
                                <i class="fas fa-expand-alt" style="font-size:10px;margin-right:4px"></i>Expand All
                            </button>
                        </div>
                    </div>
                    <div class="lc-card-b">
                        <div class="lc-states-scroll">
                            <div class="lc-states-grid">
                                @foreach($allStates as $code => $name)
                                <div class="lc-sc" id="sc-{{ $code }}">

                                    {{-- Head: click to toggle ON/OFF ── --}}
                                    <div class="lc-sc-head" onclick="lcToggleState('{{ $code }}')">
                                        <div class="lc-sc-head-l">
                                            <div class="lc-chk-wrap">
                                                <div class="lc-chk-box" id="chkbox-{{ $code }}">
                                                    <i class="fas fa-check" id="chkico-{{ $code }}" style="display:none;font-size:9px"></i>
                                                </div>
                                            </div>
                                            <span class="lc-sc-code">{{ $code }}</span>
                                            <span class="lc-sc-name">{{ $name }}</span>
                                            <span class="lc-base-badge" id="basebadge-{{ $code }}">
                                                <i class="fas fa-layer-group" style="font-size:8px"></i> Base
                                            </span>
                                        </div>
                                        <button type="button" class="lc-sc-toggle" id="tog-{{ $code }}"
                                                onclick="event.stopPropagation(); lcToggleDetails('{{ $code }}')">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>

                                    {{-- Body (only shown when state is ON) ── --}}
                                    <div class="lc-sc-body" id="body-{{ $code }}">

                                        {{-- Base price indicator (always included) ── --}}
                                        <div class="lc-base-row">
                                            <div class="lc-base-row-icon"><i class="fas fa-layer-group"></i></div>
                                            <div>
                                                <div class="lc-base-row-lbl">State-wide Base Price</div>
                                                <div class="lc-base-row-sub">Will be created automatically for {{ $code }}</div>
                                            </div>
                                        </div>

                                        {{-- Cities ── --}}
                                        <div class="lc-cities-hd">
                                            <span class="lc-cities-lbl">City Overrides (optional)</span>
                                            <button type="button" class="lc-add-city"
                                                    onclick="lcAddCity('{{ $code }}')">
                                                <i class="fas fa-plus" style="font-size:9px"></i> Add City
                                            </button>
                                        </div>
                                        <div class="lc-city-list" id="cities-{{ $code }}"></div>
                                        <div class="lc-cities-hint">
                                            <i class="fas fa-info-circle" style="font-size:9px;color:var(--blue)"></i>
                                            City prices override the base price for that specific city
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div class="lc-right">

                <div class="lc-summary">
                    <div class="lc-sum-h">
                        <i class="fas fa-chart-bar" style="font-size:12px;color:var(--ink3)"></i>
                        <span class="lc-sum-title">Summary</span>
                    </div>
                    <div class="lc-sum-b">
                        <div class="lc-sum-row">
                            <span class="lc-sum-k"><i class="fas fa-flag"></i> States Selected</span>
                            <span class="lc-sum-v hi" id="sum-states">0</span>
                        </div>
                        <div class="lc-sum-row">
                            <span class="lc-sum-k"><i class="fas fa-layer-group"></i> Base Prices</span>
                            <span class="lc-sum-v" id="sum-base">0</span>
                        </div>
                        <div class="lc-sum-row">
                            <span class="lc-sum-k"><i class="fas fa-city"></i> Cities Added</span>
                            <span class="lc-sum-v" id="sum-cities">0</span>
                        </div>
                        <div class="lc-sum-row">
                            <span class="lc-sum-k"><i class="fas fa-database"></i> Total Records</span>
                            <span class="lc-sum-v" id="sum-total">0</span>
                        </div>
                    </div>
                </div>

                <div class="lc-tip">
                    <div class="lc-tip-ttl"><i class="fas fa-lightbulb"></i> How it works</div>
                    <div class="lc-tip-item">
                        <i class="fas fa-circle-dot"></i>
                        <span><strong>Click a state</strong> to enable it. This always creates a state-wide base price automatically.</span>
                    </div>
                    <div class="lc-tip-item">
                        <i class="fas fa-circle-dot"></i>
                        <span>Expand the state to <strong>add cities</strong> with specific prices that override the base.</span>
                    </div>
                    <div class="lc-tip-item">
                        <i class="fas fa-circle-dot"></i>
                        <span>You can select states <strong>without adding cities</strong> — just the base price is enough.</span>
                    </div>
                    <div class="lc-tip-item">
                        <i class="fas fa-circle-dot"></i>
                        After saving, set item prices from the <strong>Locations index</strong>.
                    </div>
                </div>

            </div>

        </div>

        {{-- ══ FOOTER ══ --}}
        <div class="lc-foot">
            <div class="lc-foot-status" id="lc-status">
                <i class="fas fa-info-circle"></i> No states selected yet
            </div>
            <div class="lc-foot-r">
                <a href="{{ route('superadmin.locations.index') }}" class="lc-btn lc-btn-ghost">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="lc-btn lc-btn-blue" id="lc-submit" disabled>
                    <i class="fas fa-floppy-disk"></i> Save Locations
                </button>
            </div>
        </div>

    </form>
</div>

<script>
/* ════════════════════════════════
   STATE
════════════════════════════════ */
const lcEnabled = new Set();   // active state codes

/* ── toggle state ON / OFF by clicking the head ── */
function lcToggleState(code) {
    const isOn = lcEnabled.has(code);

    if (isOn) {
        // turn OFF
        lcEnabled.delete(code);
        document.getElementById('sc-' + code).classList.remove('on');
        document.getElementById('chkico-' + code).style.display = 'none';
        document.getElementById('body-' + code).classList.remove('open');
        document.getElementById('tog-' + code).classList.remove('open');
        // clear cities
        document.getElementById('cities-' + code).innerHTML = '';
    } else {
        // turn ON
        lcEnabled.add(code);
        document.getElementById('sc-' + code).classList.add('on');
        document.getElementById('chkico-' + code).style.display = '';
        document.getElementById('body-' + code).classList.add('open');
        document.getElementById('tog-' + code).classList.add('open');
    }

    lcBuildHidden();
    lcUpdateSummary();
}

/* ── toggle expand / collapse body (chevron) ── */
function lcToggleDetails(code) {
    // auto-enable if not yet ON
    if (!lcEnabled.has(code)) {
        lcToggleState(code);
        return; // lcToggleState already opens body
    }
    const body = document.getElementById('body-' + code);
    const tog  = document.getElementById('tog-' + code);
    body.classList.toggle('open');
    tog.classList.toggle('open');
}

/* ══ ADD CITY ══ */
function lcAddCity(code) {
    const list   = document.getElementById('cities-' + code);
    const cityId = 'cid-' + code + '-' + Date.now();
    const div = document.createElement('div');
    div.className = 'lc-city-row';
    div.id = cityId;
    div.innerHTML = `
        <i class="fas fa-city" style="font-size:11px;color:var(--red);flex-shrink:0"></i>
        <input type="text" class="lc-city-input"
               placeholder="City name (e.g. Miami, Orlando…)"
               data-state="${code}"
               oninput="lcCityInput(this,'${code}'); lcBuildHidden(); lcUpdateSummary()">
        <button type="button" class="lc-city-rm"
                onclick="lcRemCity('${cityId}')">
            <i class="fas fa-times"></i>
        </button>`;
    list.appendChild(div);
    div.querySelector('input').focus();
    lcBuildHidden();
    lcUpdateSummary();
}

/* ══ REMOVE CITY ══ */
function lcRemCity(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const code = el.querySelector('input')?.dataset.state;
    el.remove();
    if (code) lcBuildHidden();
    lcUpdateSummary();
}

/* ══ DUPLICATE CHECK ══ */
function lcCityInput(input, code) {
    const val = input.value.trim().toLowerCase();
    const all = document.querySelectorAll(`#cities-${code} .lc-city-input`);
    let dups = 0;
    all.forEach(s => { if (s !== input && s.value.trim().toLowerCase() === val && val) dups++; });
    input.classList.toggle('dup', dups > 0);
}

/* ══ BUILD HIDDEN FIELDS ══ */
function lcBuildHidden() {
    const container = document.getElementById('lc-hidden');
    container.innerHTML = '';
    let i = 0;

    lcEnabled.forEach(code => {
        // 1) always add the base price (city = empty string → controller treats as null)
        container.insertAdjacentHTML('beforeend',
            `<input type="hidden" name="locations[${i}][state]" value="${code}">` +
            `<input type="hidden" name="locations[${i}][city]" value="">`
        );
        i++;

        // 2) add each filled city
        document.querySelectorAll(`#cities-${code} .lc-city-input`).forEach(inp => {
            const val = inp.value.trim();
            if (val) {
                container.insertAdjacentHTML('beforeend',
                    `<input type="hidden" name="locations[${i}][state]" value="${code}">` +
                    `<input type="hidden" name="locations[${i}][city]" value="${val}">`
                );
                i++;
            }
        });
    });
}

/* ══ SUMMARY ══ */
function lcUpdateSummary() {
    const n = lcEnabled.size;

    // count filled cities across all enabled states
    let cities = 0;
    lcEnabled.forEach(code => {
        document.querySelectorAll(`#cities-${code} .lc-city-input`).forEach(inp => {
            if (inp.value.trim()) cities++;
        });
    });

    document.getElementById('sum-states').textContent = n;
    document.getElementById('sum-base').textContent   = n;          // 1 base per state
    document.getElementById('sum-cities').textContent = cities;
    document.getElementById('sum-total').textContent  = n + cities; // total records to be created

    // badge in card header
    const badge = document.getElementById('lc-en-badge');
    badge.style.display = n > 0 ? 'inline-flex' : 'none';
    document.getElementById('lc-en-count').textContent = n;

    // footer
    const status = document.getElementById('lc-status');
    const submit = document.getElementById('lc-submit');
    if (n > 0) {
        status.className = 'lc-foot-status ok';
        status.innerHTML = `<i class="fas fa-check-circle"></i> ${n} state${n>1?'s':''} selected — ${n + cities} record${(n+cities)>1?'s':''} will be created`;
        submit.disabled  = false;
    } else {
        status.className = 'lc-foot-status';
        status.innerHTML = '<i class="fas fa-info-circle"></i> No states selected yet';
        submit.disabled  = true;
    }
}

/* ══ EXPAND ALL ══ */
function lcExpandAll() {
    lcEnabled.forEach(code => {
        document.getElementById('body-' + code)?.classList.add('open');
        document.getElementById('tog-' + code)?.classList.add('open');
    });
    // also expand all so user can see them all
    document.querySelectorAll('.lc-sc-body').forEach(b => b.classList.add('open'));
    document.querySelectorAll('.lc-sc-toggle').forEach(t => t.classList.add('open'));
}

/* ══ SUBMIT ══ */
document.getElementById('lc-form').addEventListener('submit', function(e) {
    if (!document.getElementById('company-sel').value) {
        e.preventDefault();
        Swal?.fire ? Swal.fire({icon:'warning',title:'Select a company',text:'Please choose a company before saving.'}) : alert('Please select a company.');
        return;
    }
    if (!lcEnabled.size) {
        e.preventDefault();
        Swal?.fire ? Swal.fire({icon:'warning',title:'No states selected',text:'Please select at least one state.'}) : alert('Please select at least one state.');
        return;
    }
    lcBuildHidden();
    const btn = document.getElementById('lc-submit');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i> Saving…';
    btn.disabled  = true;
});

/* ══ COMPANY SELECT ══ */
function lcCompany(sel) {
    const opt  = sel.options[sel.selectedIndex];
    const name = opt.dataset.name || '';
    const pill = document.getElementById('lc-co-pill');
    document.getElementById('lc-co-name').textContent = name;
    document.getElementById('lc-co-av').textContent   = name[0]?.toUpperCase() || '?';
    pill.style.display = name ? 'flex' : 'none';
}
</script>

@endsection