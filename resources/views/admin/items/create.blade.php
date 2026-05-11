@extends('admin.layouts.superadmin')
@section('title', 'Create Item')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ic { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --orn:  #ea580c; --olt:  #fff7ed; --obd:  #fed7aa;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ══ HERO ══ */
.ic-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ic-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.ic-hero::after {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.ic-glow {
    position:absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events:none;
}
.ic-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.ic-hero-icon {
    width:52px; height:52px; border-radius:14px; flex-shrink:0;
    background:rgba(24,85,224,.2); border:1px solid rgba(24,85,224,.35);
    display:flex; align-items:center; justify-content:center; font-size:20px; color:#8aadff;
}
.ic-hero-title { font-size:22px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; }
.ic-hero-sub   { font-size:12.5px; font-weight:600; color:rgba(255,255,255,.38); margin-top:6px; }
.ic-back {
    position:relative; display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px; border-radius:var(--r);
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    color:rgba(255,255,255,.55); font-size:12px; font-weight:600;
    font-family:'Montserrat',sans-serif; text-decoration:none; transition:all .13s;
}
.ic-back:hover { background:rgba(255,255,255,.13); color:#fff; }

/* ══ ERRORS ══ */
.ic-err {
    padding:12px 16px; border-radius:var(--rlg); margin-bottom:18px;
    background:var(--rlt); border:1px solid var(--rbd); animation:fd .25s ease;
}
.ic-err-h { font-size:12px; font-weight:800; color:var(--red); display:flex; align-items:center; gap:6px; margin-bottom:5px; }
.ic-err ul { margin:0 0 0 16px; }
.ic-err li  { font-size:11.5px; font-weight:500; color:#991b1b; }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ══ LAYOUT ══ */
.ic-body { display:grid; grid-template-columns:1fr 320px; gap:16px; align-items:start; }
.ic-left { display:flex; flex-direction:column; gap:16px; }
.ic-right { display:flex; flex-direction:column; gap:16px; }

/* ══ CARDS ══ */
.ic-card {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden;
}
.ic-card-h {
    display:flex; align-items:center; gap:8px;
    padding:14px 20px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ic-card-h i     { font-size:13px; color:var(--blue); }
.ic-card-title   { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ic-card-b       { padding:20px; display:flex; flex-direction:column; gap:16px; }

/* ══ FIELDS ══ */
.ic-lbl {
    display:block; font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; margin-bottom:6px;
}
.ic-lbl .req { color:var(--red); margin-left:2px; }
.ic-lbl .opt { color:var(--ink3); font-weight:500; text-transform:none; letter-spacing:0; margin-left:4px; }
.ic-input, .ic-sel, .ic-textarea {
    padding:10px 13px; border:1px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.ic-input:focus, .ic-sel:focus, .ic-textarea:focus {
    border-color:var(--blue); box-shadow:0 0 0 3px rgba(24,85,224,.09);
}
.ic-input.err { border-color:var(--red); background:var(--rlt); }
.ic-sel {
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238c95a6' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 12px center; padding-right:36px;
}
.ic-textarea { resize:vertical; min-height:90px; }
.ic-ferr     { font-size:11px; font-weight:600; color:var(--red); display:flex; align-items:center; gap:4px; margin-top:5px; }
.ic-hint     { font-size:11px; font-weight:500; color:var(--ink3); display:flex; align-items:center; gap:5px; margin-top:5px; }
.ic-hint i   { color:var(--blue); font-size:10px; }

/* price $ prefix */
.ic-price-wrap { position:relative; }
.ic-price-prefix {
    position:absolute; left:13px; top:50%; transform:translateY(-50%);
    font-size:13px; font-weight:700; color:var(--ink3); pointer-events:none;
}
.ic-price-wrap .ic-input { padding-left:26px; }

/* crew 2-col */
.ic-2col { display:grid; grid-template-columns:1fr 1fr; gap:12px; }

/* ══ TOGGLE ══ */
.ic-toggle-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 16px; border:1px solid var(--bd2);
    border-radius:var(--rlg); background:var(--bg);
}
.ic-toggle-l  { display:flex; align-items:center; gap:12px; }
.ic-toggle-lbl  { font-size:13px; font-weight:700; color:var(--ink); }
.ic-toggle-hint { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:1px; }
.ic-toggle { position:relative; width:44px; height:24px; flex-shrink:0; }
.ic-toggle input { opacity:0; width:0; height:0; }
.ic-toggle-slider {
    position:absolute; inset:0; border-radius:9999px;
    background:var(--bd); cursor:pointer; transition:background .2s;
}
.ic-toggle-slider::before {
    content:''; position:absolute;
    width:18px; height:18px; border-radius:50%; background:#fff;
    left:3px; top:3px; transition:transform .2s;
    box-shadow:0 1px 3px rgba(0,0,0,.15);
}
.ic-toggle input:checked + .ic-toggle-slider { background:var(--grn); }
.ic-toggle input:checked + .ic-toggle-slider::before { transform:translateX(20px); }
.ic-status-badge {
    font-size:10.5px; font-weight:800; padding:4px 10px;
    border-radius:9999px; text-transform:uppercase; letter-spacing:.4px;
    display:inline-flex; align-items:center; gap:5px;
}
.ic-status-badge.on  { background:var(--glt); color:var(--grn); border:1px solid var(--gbd); }
.ic-status-badge.off { background:var(--alt); color:var(--amb); border:1px solid var(--abd); }

/* ══ SIDEBAR TIPS ══ */
.ic-tip {
    background:var(--blt); border:1px solid var(--bbd);
    border-radius:var(--rlg); padding:16px 18px;
}
.ic-tip-title { font-size:12px; font-weight:800; color:var(--blue); display:flex; align-items:center; gap:6px; margin-bottom:10px; }
.ic-tip-item  { display:flex; align-items:flex-start; gap:7px; margin-bottom:8px; font-size:11.5px; font-weight:500; color:var(--ink2); line-height:1.5; }
.ic-tip-item:last-child { margin-bottom:0; }
.ic-tip-item i { color:var(--blue); font-size:9px; margin-top:4px; flex-shrink:0; }

/* price legend card */
.ic-legend {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden;
}
.ic-legend-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 18px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ic-legend-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ic-legend-b { padding:14px 16px; display:flex; flex-direction:column; gap:8px; }
.ic-legend-row {
    display:flex; align-items:center; gap:10px;
    padding:8px 12px; border-radius:var(--r); border:1px solid var(--bd2);
}
.ic-legend-icon {
    width:30px; height:30px; border-radius:8px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:11px;
}
.ic-legend-icon.grn { background:var(--glt); color:var(--grn); }
.ic-legend-icon.orn { background:var(--olt); color:var(--orn); }
.ic-legend-icon.amb { background:var(--alt); color:var(--amb); }
.ic-legend-lbl { font-size:11.5px; font-weight:700; color:var(--ink); }
.ic-legend-desc { font-size:10.5px; font-weight:500; color:var(--ink3); margin-top:1px; }

/* ══ FOOTER ══ */
.ic-foot {
    display:flex; align-items:center; justify-content:flex-end; gap:8px;
    padding:14px 18px; background:var(--bg);
    border:1px solid var(--bd); border-radius:var(--rlg); margin-top:4px;
}
.ic-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px; border-radius:var(--r);
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap;
}
.ic-btn i { font-size:10px; }
.ic-btn-blue  { background:var(--blue); color:#fff; }
.ic-btn-blue:hover  { background:#1344c2; color:#fff; }
.ic-btn-ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.ic-btn-ghost:hover { background:var(--bg); color:var(--ink); }

@media (max-width:1024px) { .ic-body { grid-template-columns:1fr; } }
@media (max-width:640px)  {
    .ic { padding:16px; }
    .ic-hero { padding:22px 20px; flex-direction:column; align-items:flex-start; }
    .ic-2col { grid-template-columns:1fr; }
}
</style>

<div class="ic">

    {{-- ══ HERO ══ --}}
    <div class="ic-hero">
        <div class="ic-glow"></div>
        <div class="ic-hero-l">
            <div class="ic-hero-icon"><i class="fas fa-plus-circle"></i></div>
            <div>
                <div class="ic-hero-title">Create New Item</div>
                <div class="ic-hero-sub">Add a new item to the global service catalog</div>
            </div>
        </div>
        <a href="{{ route('superadmin.items.index') }}" class="ic-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Items
        </a>
    </div>

    {{-- ══ ERRORS ══ --}}
    @if($errors->any())
    <div class="ic-err">
        <div class="ic-err-h"><i class="fas fa-exclamation-circle"></i> Please fix the following:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.items.store') }}" id="ic-form">
        @csrf

        <div class="ic-body">

            {{-- ══ LEFT ══ --}}
            <div class="ic-left">

                {{-- Basic info ── --}}
                <div class="ic-card">
                    <div class="ic-card-h">
                        <i class="fas fa-info-circle"></i>
                        <span class="ic-card-title">Basic Information</span>
                    </div>
                    <div class="ic-card-b">

                        <div>
                            <label class="ic-lbl" for="name">Item Name <span class="req">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="ic-input {{ $errors->has('name') ? 'err' : '' }}"
                                   value="{{ old('name') }}"
                                   placeholder="E.g.: Roof replacement labor"
                                   required autofocus>
                            @error('name')
                            <div class="ic-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="ic-lbl" for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="ic-sel">
                                <option value="">— No category —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="ic-hint"><i class="fas fa-tag"></i> Helps organize items and price lists.</div>
                            @error('category_id')
                            <div class="ic-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="ic-lbl" for="description">Description <span class="opt">(optional)</span></label>
                            <textarea name="description" id="description" class="ic-textarea"
                                      placeholder="Additional details about the item…">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Prices ── --}}
                <div class="ic-card">
                    <div class="ic-card-h">
                        <i class="fas fa-dollar-sign"></i>
                        <span class="ic-card-title">Price Settings</span>
                    </div>
                    <div class="ic-card-b">

                        {{-- Global price --}}
                        <div>
                            <label class="ic-lbl" for="global_price">Global Price <span class="opt">(fallback)</span></label>
                            <div class="ic-price-wrap">
                                <span class="ic-price-prefix">$</span>
                                <input type="number" name="global_price" id="global_price"
                                       step="0.01" min="0"
                                       class="ic-input {{ $errors->has('global_price') ? 'err' : '' }}"
                                       value="{{ old('global_price') }}" placeholder="0.00">
                            </div>
                            <div class="ic-hint"><i class="fas fa-info-circle"></i> Used when no state or city-specific price exists.</div>
                            @error('global_price')
                            <div class="ic-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Crew prices --}}
                        <div>
                            <label class="ic-lbl" style="margin-bottom:10px">Crew Prices</label>
                            <div class="ic-2col">
                                <div>
                                    <label class="ic-lbl" for="crew_price_with_trailer">
                                        <i class="fas fa-truck" style="font-size:9px;margin-right:3px;color:var(--orn)"></i>
                                        With Trailer
                                    </label>
                                    <div class="ic-price-wrap">
                                        <span class="ic-price-prefix">$</span>
                                        <input type="number" name="crew_price_with_trailer" id="crew_price_with_trailer"
                                               step="0.01" min="0"
                                               class="ic-input {{ $errors->has('crew_price_with_trailer') ? 'err' : '' }}"
                                               value="{{ old('crew_price_with_trailer') }}" placeholder="0.00">
                                    </div>
                                    @error('crew_price_with_trailer')
                                    <div class="ic-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="ic-lbl" for="crew_price_without_trailer">
                                        <i class="fas fa-user" style="font-size:9px;margin-right:3px;color:var(--amb)"></i>
                                        Without Trailer
                                    </label>
                                    <div class="ic-price-wrap">
                                        <span class="ic-price-prefix">$</span>
                                        <input type="number" name="crew_price_without_trailer" id="crew_price_without_trailer"
                                               step="0.01" min="0"
                                               class="ic-input {{ $errors->has('crew_price_without_trailer') ? 'err' : '' }}"
                                               value="{{ old('crew_price_without_trailer') }}" placeholder="0.00">
                                    </div>
                                    @error('crew_price_without_trailer')
                                    <div class="ic-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="ic-hint" style="margin-top:10px">
                                <i class="fas fa-shield-alt"></i> Amount paid to crew based on trailer availability.
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Status ── --}}
                <div class="ic-card">
                    <div class="ic-card-h">
                        <i class="fas fa-toggle-on"></i>
                        <span class="ic-card-title">Status</span>
                    </div>
                    <div class="ic-card-b">
                        <div class="ic-toggle-row">
                            <div class="ic-toggle-l">
                                <label class="ic-toggle">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                           id="tog-active" checked
                                           onchange="icBadge(this.checked)">
                                    <span class="ic-toggle-slider"></span>
                                </label>
                                <div>
                                    <div class="ic-toggle-lbl">Active Item</div>
                                    <div class="ic-toggle-hint">Inactive items won't appear in new estimates</div>
                                </div>
                            </div>
                            <span class="ic-status-badge on" id="ic-badge">
                                <i class="fas fa-check-circle" style="font-size:9px"></i> Active
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT: sidebar ══ --}}
            <div class="ic-right">

                {{-- Price legend ── --}}
                <div class="ic-legend">
                    <div class="ic-legend-h">
                        <i class="fas fa-dollar-sign" style="font-size:12px;color:var(--ink3)"></i>
                        <span class="ic-legend-title">Price Types</span>
                    </div>
                    <div class="ic-legend-b">
                        <div class="ic-legend-row">
                            <div class="ic-legend-icon grn"><i class="fas fa-globe"></i></div>
                            <div>
                                <div class="ic-legend-lbl">Global Price</div>
                                <div class="ic-legend-desc">Fallback used when no location-specific price is set</div>
                            </div>
                        </div>
                        <div class="ic-legend-row">
                            <div class="ic-legend-icon orn"><i class="fas fa-truck"></i></div>
                            <div>
                                <div class="ic-legend-lbl">Crew w/ Trailer</div>
                                <div class="ic-legend-desc">Payment to crew that brings their own trailer</div>
                            </div>
                        </div>
                        <div class="ic-legend-row">
                            <div class="ic-legend-icon amb"><i class="fas fa-user"></i></div>
                            <div>
                                <div class="ic-legend-lbl">Crew w/o Trailer</div>
                                <div class="ic-legend-desc">Payment to crew without trailer</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tips ── --}}
                <div class="ic-tip">
                    <div class="ic-tip-title"><i class="fas fa-lightbulb"></i> Tips</div>
                    <div class="ic-tip-item"><i class="fas fa-circle-dot"></i> The global price is optional — you can add state/city-specific prices after creating the item.</div>
                    <div class="ic-tip-item"><i class="fas fa-circle-dot"></i> Crew prices are independent of the global price and used for crew payment calculations.</div>
                    <div class="ic-tip-item"><i class="fas fa-circle-dot"></i> Assigning a category makes it easier to filter and organize items in estimates.</div>
                </div>

            </div>

        </div>

        {{-- ══ FOOTER ══ --}}
        <div class="ic-foot">
            <a href="{{ route('superadmin.items.index') }}" class="ic-btn ic-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="ic-btn ic-btn-blue">
                <i class="fas fa-floppy-disk"></i> Save Item
            </button>
        </div>

    </form>
</div>

<script>
function icBadge(on) {
    const b = document.getElementById('ic-badge');
    b.className = 'ic-status-badge ' + (on ? 'on' : 'off');
    b.innerHTML  = on
        ? '<i class="fas fa-check-circle" style="font-size:9px"></i> Active'
        : '<i class="fas fa-exclamation-triangle" style="font-size:9px"></i> Hidden from pricing';
}
</script>

@endsection