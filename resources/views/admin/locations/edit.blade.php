@extends('admin.layouts.superadmin')
@section('title', 'Edit Location · ' . $location->user->company_name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.le { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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
.le-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.le-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.le-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.le-glow {
    position: absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events: none;
}
.le-hero-l { position: relative; display: flex; align-items: center; gap: 16px; }
.le-hero-icon {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center; font-size: 20px; color: #8aadff;
}
.le-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.le-hero-sub   { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.38); margin-top: 6px; }
.le-hero-sub strong { color: #8aadff; font-weight: 700; }
.le-back {
    position: relative; display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
    color: rgba(255,255,255,.55); font-size: 12px; font-weight: 600;
    font-family: 'Montserrat', sans-serif; text-decoration: none; transition: all .13s;
}
.le-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── ERRORS ── */
.le-err {
    padding: 12px 16px; border-radius: var(--rlg); margin-bottom: 18px;
    background: var(--rlt); border: 1px solid var(--rbd); animation: fd .25s ease;
}
.le-err-h { font-size: 12px; font-weight: 800; color: var(--red); display: flex; align-items: center; gap: 6px; margin-bottom: 5px; }
.le-err ul { margin: 0 0 0 16px; }
.le-err li  { font-size: 11.5px; font-weight: 500; color: #991b1b; }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ── LAYOUT ── */
.le-body { display: grid; grid-template-columns: 1fr 300px; gap: 16px; align-items: start; }
.le-left { display: flex; flex-direction: column; gap: 16px; }
.le-right { display: flex; flex-direction: column; gap: 14px; position: sticky; top: 90px; }

/* ── CARD ── */
.le-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden;
}
.le-card-h {
    display: flex; align-items: center; gap: 8px;
    padding: 14px 20px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.le-card-h i     { font-size: 13px; color: var(--blue); }
.le-card-title   { font-size: 12px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .5px; }
.le-card-b       { padding: 20px; display: flex; flex-direction: column; gap: 16px; }

/* ── FIELDS ── */
.le-lbl {
    display: block; font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .7px; margin-bottom: 6px;
}
.le-lbl .req { color: var(--red); margin-left: 2px; }
.le-lbl .opt { color: var(--ink3); font-weight: 500; text-transform: none; letter-spacing: 0; margin-left: 4px; }
.le-input {
    padding: 10px 13px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s;
}
.le-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.le-input.err   { border-color: var(--red); background: var(--rlt); }
.le-ferr        { font-size: 11px; font-weight: 600; color: var(--red); display: flex; align-items: center; gap: 4px; margin-top: 5px; }
.le-hint        { font-size: 11px; font-weight: 500; color: var(--ink3); margin-top: 5px; display: flex; align-items: center; gap: 5px; }
.le-hint i      { color: var(--blue); font-size: 10px; }

/* ── TYPE PILL ── */
.le-type-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 9999px;
    font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px;
}
.le-type-pill.base { background: #e8eef7; color: #003366; border: 1px solid #b3c6e0; }
.le-type-pill.city { background: var(--rlt); color: var(--red); border: 1px solid var(--rbd); }

/* ── SIDEBAR ── */
.le-summary {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden;
}
.le-summary-h {
    display: flex; align-items: center; gap: 8px;
    padding: 13px 16px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.le-summary-title { font-size: 11.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .5px; }
.le-summary-b { padding: 14px 16px; display: flex; flex-direction: column; gap: 8px; }
.le-sum-row {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px; border: 1px solid var(--bd2);
    border-radius: var(--r); background: var(--bg);
}
.le-sum-icon {
    width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 12px;
}
.le-sum-icon.blue { background: var(--blt); color: var(--blue); }
.le-sum-icon.grn  { background: var(--glt); color: var(--grn); }
.le-sum-icon.amb  { background: var(--alt); color: var(--amb); }
.le-sum-icon.red  { background: var(--rlt); color: var(--red); }
.le-sum-key { font-size: 10px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .4px; }
.le-sum-val { font-size: 12.5px; font-weight: 700; color: var(--ink); }

.le-tip {
    background: var(--blt); border: 1px solid var(--bbd);
    border-radius: var(--rlg); padding: 14px 16px;
}
.le-tip-title { font-size: 11.5px; font-weight: 800; color: var(--blue); display: flex; align-items: center; gap: 6px; margin-bottom: 8px; }
.le-tip-item  { display: flex; align-items: flex-start; gap: 6px; margin-bottom: 7px; font-size: 11px; font-weight: 500; color: var(--ink2); line-height: 1.5; }
.le-tip-item:last-child { margin-bottom: 0; }
.le-tip-item i { color: var(--blue); font-size: 9px; margin-top: 3px; flex-shrink: 0; }

/* ── FOOTER ── */
.le-foot {
    display: flex; align-items: center; justify-content: flex-end; gap: 8px;
    padding: 14px 18px; background: var(--bg);
    border: 1px solid var(--bd); border-radius: var(--rlg); margin-top: 4px;
}
.le-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
    text-decoration: none; white-space: nowrap;
}
.le-btn i { font-size: 10px; }
.le-btn-blue  { background: var(--blue); color: #fff; }
.le-btn-blue:hover  { background: #1344c2; color: #fff; }
.le-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.le-btn-ghost:hover { background: var(--bg); color: var(--ink); }

@media (max-width: 900px)  { .le-body { grid-template-columns: 1fr; } .le-right { position: static; } }
@media (max-width: 640px)  { .le { padding: 16px; } .le-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; } }
</style>

@php $isCityOverride = !empty($location->city); @endphp

<div class="le">

    {{-- ── HERO ── --}}
    <div class="le-hero">
        <div class="le-glow"></div>
        <div class="le-hero-l">
            <div class="le-hero-icon"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <div class="le-hero-title">Edit Location</div>
                <div class="le-hero-sub">Company: <strong>{{ $location->user->company_name }}</strong></div>
            </div>
        </div>
        <a href="{{ route('superadmin.locations.index') }}" class="le-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Locations
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="le-err">
        <div class="le-err-h"><i class="fas fa-exclamation-circle"></i> Please fix the following:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.locations.update', $location) }}" id="le-form">
        @csrf @method('PUT')

        <div class="le-body">

            {{-- ══ LEFT ══ --}}
            <div class="le-left">
                <div class="le-card">
                    <div class="le-card-h">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="le-card-title">Location Details</span>
                        <div style="margin-left:auto">
                            <span class="le-type-pill {{ $isCityOverride ? 'city' : 'base' }}">
                                <i class="fas fa-{{ $isCityOverride ? 'city' : 'layer-group' }}" style="font-size:9px"></i>
                                {{ $isCityOverride ? 'City Override' : 'Base Price' }}
                            </span>
                        </div>
                    </div>
                    <div class="le-card-b">

                        {{-- State ── --}}
                        <div>
                            <label class="le-lbl" for="state">State Code <span class="req">*</span></label>
                            <input type="text" name="state" id="state"
                                   class="le-input {{ $errors->has('state') ? 'err' : '' }}"
                                   value="{{ old('state', $location->state) }}"
                                   maxlength="5" required
                                   oninput="this.value=this.value.toUpperCase()"
                                   placeholder="FL, TX, CA…">
                            <div class="le-hint"><i class="fas fa-info-circle"></i> 2-letter state abbreviation (uppercase)</div>
                            @error('state')
                            <div class="le-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        {{-- City ── --}}
                        <div>
                            <label class="le-lbl" for="city">City <span class="opt">(optional)</span></label>
                            <input type="text" name="city" id="city"
                                   class="le-input {{ $errors->has('city') ? 'err' : '' }}"
                                   value="{{ old('city', $location->city) }}"
                                   placeholder="Leave empty for state-wide base price">
                            <div class="le-hint">
                                <i class="fas fa-info-circle"></i>
                                Fill in a city name to create a city override. Leave empty for a state-wide base price.
                            </div>
                            @error('city')
                            <div class="le-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- ══ RIGHT ══ --}}
            <div class="le-right">

                {{-- Current values ── --}}
                <div class="le-summary">
                    <div class="le-summary-h">
                        <i class="fas fa-chart-bar" style="font-size:12px;color:var(--ink3)"></i>
                        <span class="le-summary-title">Current Values</span>
                    </div>
                    <div class="le-summary-b">
                        <div class="le-sum-row">
                            <div class="le-sum-icon blue"><i class="fas fa-building"></i></div>
                            <div>
                                <div class="le-sum-key">Company</div>
                                <div class="le-sum-val">{{ $location->user->company_name }}</div>
                            </div>
                        </div>
                        <div class="le-sum-row">
                            <div class="le-sum-icon amb"><i class="fas fa-flag"></i></div>
                            <div>
                                <div class="le-sum-key">State</div>
                                <div class="le-sum-val">{{ $location->state }}</div>
                            </div>
                        </div>
                        @if($isCityOverride)
                        <div class="le-sum-row">
                            <div class="le-sum-icon red"><i class="fas fa-city"></i></div>
                            <div>
                                <div class="le-sum-key">City</div>
                                <div class="le-sum-val">{{ $location->city }}</div>
                            </div>
                        </div>
                        @else
                        <div class="le-sum-row">
                            <div class="le-sum-icon grn"><i class="fas fa-layer-group"></i></div>
                            <div>
                                <div class="le-sum-key">Type</div>
                                <div class="le-sum-val">State-wide Base Price</div>
                            </div>
                        </div>
                        @endif
                        @if($location->created_at)
                        <div class="le-sum-row">
                            <div class="le-sum-icon blue" style="background:var(--bg);color:var(--ink3)"><i class="fas fa-calendar"></i></div>
                            <div>
                                <div class="le-sum-key">Created</div>
                                <div class="le-sum-val">{{ $location->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Tips ── --}}
                <div class="le-tip">
                    <div class="le-tip-title"><i class="fas fa-lightbulb"></i> Tips</div>
                    <div class="le-tip-item"><i class="fas fa-circle-dot"></i> Changing the state code will move this location to the new state.</div>
                    <div class="le-tip-item"><i class="fas fa-circle-dot"></i> Clearing the city field converts this to a state-wide base price.</div>
                    <div class="le-tip-item"><i class="fas fa-circle-dot"></i> Item prices are managed separately from the Locations index.</div>
                </div>

            </div>

        </div>

        {{-- ── FOOTER ── --}}
        <div class="le-foot">
            <a href="{{ route('superadmin.locations.index') }}" class="le-btn le-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="le-btn le-btn-blue">
                <i class="fas fa-floppy-disk"></i> Update Location
            </button>
        </div>

    </form>
</div>

@endsection