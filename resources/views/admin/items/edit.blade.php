@extends('admin.layouts.superadmin')
@section('title', 'Edit Item · ' . $item->name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ie { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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
.ie-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ie-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.ie-hero::after {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.ie-glow {
    position:absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events:none;
}
.ie-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.ie-hero-icon {
    width:52px; height:52px; border-radius:14px; flex-shrink:0;
    background:rgba(24,85,224,.2); border:1px solid rgba(24,85,224,.35);
    display:flex; align-items:center; justify-content:center; font-size:20px; color:#8aadff;
}
.ie-hero-title { font-size:22px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; }
.ie-hero-sub   { font-size:12.5px; font-weight:600; color:rgba(255,255,255,.38); margin-top:6px; }
.ie-hero-sub strong { color:#8aadff; font-weight:700; }
.ie-back {
    position:relative; display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px; border-radius:var(--r);
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    color:rgba(255,255,255,.55); font-size:12px; font-weight:600;
    font-family:'Montserrat',sans-serif; text-decoration:none; transition:all .13s;
}
.ie-back:hover { background:rgba(255,255,255,.13); color:#fff; }

/* ══ ERRORS ══ */
.ie-err {
    padding:12px 16px; border-radius:var(--rlg); margin-bottom:18px;
    background:var(--rlt); border:1px solid var(--rbd); animation:fd .25s ease;
}
.ie-err-h { font-size:12px; font-weight:800; color:var(--red); display:flex; align-items:center; gap:6px; margin-bottom:5px; }
.ie-err ul { margin:0 0 0 16px; }
.ie-err li  { font-size:11.5px; font-weight:500; color:#991b1b; }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ══ LAYOUT ══ */
.ie-body { display:grid; grid-template-columns:1fr 320px; gap:16px; align-items:start; }
.ie-left { display:flex; flex-direction:column; gap:16px; }
.ie-right { display:flex; flex-direction:column; gap:16px; }

/* ══ CARDS ══ */
.ie-card {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden;
}
.ie-card-h {
    display:flex; align-items:center; gap:8px;
    padding:14px 20px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ie-card-h i     { font-size:13px; color:var(--blue); }
.ie-card-title   { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ie-card-b       { padding:20px; display:flex; flex-direction:column; gap:16px; }

/* ══ FIELDS ══ */
.ie-lbl {
    display:block; font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; margin-bottom:6px;
}
.ie-lbl .req { color:var(--red); margin-left:2px; }
.ie-lbl .opt { color:var(--ink3); font-weight:500; text-transform:none; letter-spacing:0; margin-left:4px; }
.ie-input, .ie-sel, .ie-textarea {
    padding:10px 13px; border:1px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.ie-input:focus, .ie-sel:focus, .ie-textarea:focus {
    border-color:var(--blue); box-shadow:0 0 0 3px rgba(24,85,224,.09);
}
.ie-input.err { border-color:var(--red); background:var(--rlt); }
.ie-sel { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238c95a6' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; padding-right:36px; }
.ie-textarea { resize:vertical; min-height:90px; }
.ie-ferr     { font-size:11px; font-weight:600; color:var(--red); display:flex; align-items:center; gap:4px; margin-top:5px; }
.ie-hint     { font-size:11px; font-weight:500; color:var(--ink3); display:flex; align-items:center; gap:5px; margin-top:5px; }
.ie-hint i   { color:var(--blue); font-size:10px; }

/* price input with $ prefix */
.ie-price-wrap { position:relative; }
.ie-price-prefix {
    position:absolute; left:13px; top:50%; transform:translateY(-50%);
    font-size:13px; font-weight:700; color:var(--ink3); pointer-events:none;
}
.ie-price-wrap .ie-input { padding-left:26px; }

/* 2-col grid for crew prices */
.ie-2col { display:grid; grid-template-columns:1fr 1fr; gap:12px; }

/* ══ TOGGLE ROW ══ */
.ie-toggle-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 16px; border:1px solid var(--bd2);
    border-radius:var(--rlg); background:var(--bg);
}
.ie-toggle-l  { display:flex; align-items:center; gap:12px; }
.ie-toggle-lbl  { font-size:13px; font-weight:700; color:var(--ink); }
.ie-toggle-hint { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:1px; }
.ie-toggle { position:relative; width:44px; height:24px; flex-shrink:0; }
.ie-toggle input { opacity:0; width:0; height:0; }
.ie-toggle-slider {
    position:absolute; inset:0; border-radius:9999px;
    background:var(--bd); cursor:pointer; transition:background .2s;
}
.ie-toggle-slider::before {
    content:''; position:absolute;
    width:18px; height:18px; border-radius:50%; background:#fff;
    left:3px; top:3px; transition:transform .2s;
    box-shadow:0 1px 3px rgba(0,0,0,.15);
}
.ie-toggle input:checked + .ie-toggle-slider { background:var(--grn); }
.ie-toggle input:checked + .ie-toggle-slider::before { transform:translateX(20px); }
.ie-status-badge {
    font-size:10.5px; font-weight:800; padding:4px 10px;
    border-radius:9999px; text-transform:uppercase; letter-spacing:.4px;
    display:inline-flex; align-items:center; gap:5px;
}
.ie-status-badge.on  { background:var(--glt); color:var(--grn); border:1px solid var(--gbd); }
.ie-status-badge.off { background:var(--alt); color:var(--amb); border:1px solid var(--abd); }

/* ══ SIDEBAR ══ */
.ie-summary {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden;
}
.ie-summary-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 18px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ie-summary-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ie-summary-b { padding:14px 16px; display:flex; flex-direction:column; gap:9px; }
.ie-sum-row {
    display:flex; align-items:center; gap:10px;
    padding:9px 12px; border:1px solid var(--bd2);
    border-radius:var(--r); background:var(--bg);
}
.ie-sum-icon {
    width:32px; height:32px; border-radius:8px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:12px;
}
.ie-sum-icon.blue { background:var(--blt); color:var(--blue); }
.ie-sum-icon.grn  { background:var(--glt); color:var(--grn); }
.ie-sum-icon.amb  { background:var(--alt); color:var(--amb); }
.ie-sum-icon.orn  { background:var(--olt); color:var(--orn); }
.ie-sum-icon.ink  { background:var(--bg);  color:var(--ink3); border:1px solid var(--bd); }
.ie-sum-key { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.4px; }
.ie-sum-val { font-size:12.5px; font-weight:700; color:var(--ink); }

.ie-tip {
    background:var(--blt); border:1px solid var(--bbd);
    border-radius:var(--rlg); padding:14px 16px;
}
.ie-tip-title { font-size:11.5px; font-weight:800; color:var(--blue); display:flex; align-items:center; gap:6px; margin-bottom:8px; }
.ie-tip-item  { display:flex; align-items:flex-start; gap:7px; margin-bottom:7px; font-size:11.5px; font-weight:500; color:var(--ink2); line-height:1.5; }
.ie-tip-item:last-child { margin-bottom:0; }
.ie-tip-item i { color:var(--blue); font-size:9px; margin-top:4px; flex-shrink:0; }

/* ══ FOOTER ══ */
.ie-foot {
    display:flex; align-items:center; justify-content:flex-end; gap:8px;
    padding:14px 18px; background:var(--bg);
    border:1px solid var(--bd); border-radius:var(--rlg); margin-top:4px;
}
.ie-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px; border-radius:var(--r);
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap;
}
.ie-btn i { font-size:10px; }
.ie-btn-blue  { background:var(--blue); color:#fff; }
.ie-btn-blue:hover  { background:#1344c2; color:#fff; }
.ie-btn-ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.ie-btn-ghost:hover { background:var(--bg); color:var(--ink); }

@media (max-width:1024px) { .ie-body { grid-template-columns:1fr; } }
@media (max-width:640px)  {
    .ie { padding:16px; }
    .ie-hero { padding:22px 20px; flex-direction:column; align-items:flex-start; }
    .ie-2col { grid-template-columns:1fr; }
}
</style>

<div class="ie">

    {{-- ══ HERO ══ --}}
    <div class="ie-hero">
        <div class="ie-glow"></div>
        <div class="ie-hero-l">
            <div class="ie-hero-icon"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <div class="ie-hero-title">Edit Item</div>
                <div class="ie-hero-sub">Editing: <strong>{{ $item->name }}</strong></div>
            </div>
        </div>
        <a href="{{ route('superadmin.items.index') }}" class="ie-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Items
        </a>
    </div>

    {{-- ══ ERRORS ══ --}}
    @if($errors->any())
    <div class="ie-err">
        <div class="ie-err-h"><i class="fas fa-exclamation-circle"></i> Please fix the following:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.items.update', $item) }}" id="ie-form">
        @csrf @method('PUT')

        <div class="ie-body">

            {{-- ══ LEFT ══ --}}
            <div class="ie-left">

                {{-- Basic info ── --}}
                <div class="ie-card">
                    <div class="ie-card-h">
                        <i class="fas fa-info-circle"></i>
                        <span class="ie-card-title">Basic Information</span>
                    </div>
                    <div class="ie-card-b">

                        <div>
                            <label class="ie-lbl" for="name">Item Name <span class="req">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="ie-input {{ $errors->has('name') ? 'err' : '' }}"
                                   value="{{ old('name', $item->name) }}"
                                   placeholder="E.g.: Roof replacement labor"
                                   required autofocus>
                            @error('name')
                            <div class="ie-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="ie-lbl" for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="ie-sel">
                                <option value="">— No category —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="ie-hint"><i class="fas fa-tag"></i> Helps organize items and price lists.</div>
                            @error('category_id')
                            <div class="ie-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                   

                    </div>
                </div>

                {{-- Prices ── --}}
                <div class="ie-card">
                    <div class="ie-card-h">
                        <i class="fas fa-dollar-sign"></i>
                        <span class="ie-card-title">Price Settings</span>
                    </div>
                    <div class="ie-card-b">

                        {{-- Global price --}}
                        <div>
                            <label class="ie-lbl" for="global_price">Global Price <span class="opt">(fallback)</span></label>
                            <div class="ie-price-wrap">
                                <span class="ie-price-prefix">$</span>
                                <input type="number" name="global_price" id="global_price" step="0.01" min="0"
                                       class="ie-input {{ $errors->has('global_price') ? 'err' : '' }}"
                                       value="{{ old('global_price', $item->global_price) }}"
                                       placeholder="0.00">
                            </div>
                            <div class="ie-hint"><i class="fas fa-info-circle"></i> Used when no state or city-specific price exists.</div>
                            @error('global_price')
                            <div class="ie-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Crew prices --}}
                        <div>
                            <label class="ie-lbl" style="margin-bottom:10px">Crew Prices</label>
                            <div class="ie-2col">
                                <div>
                                    <label class="ie-lbl" for="crew_price_with_trailer">
                                        <i class="fas fa-truck" style="font-size:9px;margin-right:3px;color:var(--orn)"></i>
                                        With Trailer
                                    </label>
                                    <div class="ie-price-wrap">
                                        <span class="ie-price-prefix">$</span>
                                        <input type="number" name="crew_price_with_trailer" id="crew_price_with_trailer"
                                               step="0.01" min="0"
                                               class="ie-input {{ $errors->has('crew_price_with_trailer') ? 'err' : '' }}"
                                               value="{{ old('crew_price_with_trailer', $item->crew_price_with_trailer) }}"
                                               placeholder="0.00">
                                    </div>
                                    @error('crew_price_with_trailer')
                                    <div class="ie-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="ie-lbl" for="crew_price_without_trailer">
                                        <i class="fas fa-user" style="font-size:9px;margin-right:3px;color:var(--amb)"></i>
                                        Without Trailer
                                    </label>
                                    <div class="ie-price-wrap">
                                        <span class="ie-price-prefix">$</span>
                                        <input type="number" name="crew_price_without_trailer" id="crew_price_without_trailer"
                                               step="0.01" min="0"
                                               class="ie-input {{ $errors->has('crew_price_without_trailer') ? 'err' : '' }}"
                                               value="{{ old('crew_price_without_trailer', $item->crew_price_without_trailer) }}"
                                               placeholder="0.00">
                                    </div>
                                    @error('crew_price_without_trailer')
                                    <div class="ie-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="ie-hint" style="margin-top:10px"><i class="fas fa-shield-alt"></i> Amount paid to crew based on trailer availability.</div>
                        </div>

                    </div>
                </div>

                {{-- Status ── --}}
                <div class="ie-card">
                    <div class="ie-card-h">
                        <i class="fas fa-toggle-on"></i>
                        <span class="ie-card-title">Status</span>
                    </div>
                    <div class="ie-card-b">
                        <div class="ie-toggle-row">
                            <div class="ie-toggle-l">
                                <label class="ie-toggle">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                           id="tog-active"
                                           {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                                           onchange="ieBadge(this.checked)">
                                    <span class="ie-toggle-slider"></span>
                                </label>
                                <div>
                                    <div class="ie-toggle-lbl">Active Item</div>
                                    <div class="ie-toggle-hint">Item will appear in pricing and catalogs</div>
                                </div>
                            </div>
                            <span class="ie-status-badge {{ old('is_active', $item->is_active) ? 'on' : 'off' }}"
                                  id="ie-badge">
                                <i class="fas fa-{{ old('is_active', $item->is_active) ? 'check-circle' : 'exclamation-triangle' }}"
                                   style="font-size:9px"></i>
                                {{ old('is_active', $item->is_active) ? 'Active' : 'Hidden from pricing' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT: sidebar ══ --}}
            <div class="ie-right">

                {{-- Current values summary ── --}}
                <div class="ie-summary">
                    <div class="ie-summary-h">
                        <i class="fas fa-chart-bar" style="font-size:12px;color:var(--ink3)"></i>
                        <span class="ie-summary-title">Current Values</span>
                    </div>
                    <div class="ie-summary-b">
                        <div class="ie-sum-row">
                            <div class="ie-sum-icon blue"><i class="fas fa-box"></i></div>
                            <div style="min-width:0">
                                <div class="ie-sum-key">Name</div>
                                <div class="ie-sum-val" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->name }}</div>
                            </div>
                        </div>
                        <div class="ie-sum-row">
                            <div class="ie-sum-icon amb"><i class="fas fa-tags"></i></div>
                            <div>
                                <div class="ie-sum-key">Category</div>
                                <div class="ie-sum-val">{{ optional($item->category)->name ?? '—' }}</div>
                            </div>
                        </div>
                        @if($item->global_price > 0)
                        <div class="ie-sum-row">
                            <div class="ie-sum-icon grn"><i class="fas fa-dollar-sign"></i></div>
                            <div>
                                <div class="ie-sum-key">Global Price</div>
                                <div class="ie-sum-val">${{ number_format($item->global_price, 2) }}</div>
                            </div>
                        </div>
                        @endif
                        @if($item->crew_price_with_trailer > 0)
                        <div class="ie-sum-row">
                            <div class="ie-sum-icon orn"><i class="fas fa-truck"></i></div>
                            <div>
                                <div class="ie-sum-key">Crew w/ Trailer</div>
                                <div class="ie-sum-val">${{ number_format($item->crew_price_with_trailer, 2) }}</div>
                            </div>
                        </div>
                        @endif
                        @if($item->crew_price_without_trailer > 0)
                        <div class="ie-sum-row">
                            <div class="ie-sum-icon amb"><i class="fas fa-user"></i></div>
                            <div>
                                <div class="ie-sum-key">Crew w/o Trailer</div>
                                <div class="ie-sum-val">${{ number_format($item->crew_price_without_trailer, 2) }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="ie-sum-row">
                            <div class="ie-sum-icon {{ $item->is_active ? 'grn' : 'ink' }}">
                                <i class="fas fa-{{ $item->is_active ? 'check-circle' : 'minus-circle' }}"></i>
                            </div>
                            <div>
                                <div class="ie-sum-key">Status</div>
                                <div class="ie-sum-val">{{ $item->is_active ? 'Active' : 'Inactive' }}</div>
                            </div>
                        </div>
                        @if($item->updated_at)
                        <div class="ie-sum-row">
                            <div class="ie-sum-icon ink"><i class="fas fa-clock"></i></div>
                            <div>
                                <div class="ie-sum-key">Last Updated</div>
                                <div class="ie-sum-val">{{ $item->updated_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Tips ── --}}
                <div class="ie-tip">
                    <div class="ie-tip-title"><i class="fas fa-lightbulb"></i> Tips</div>
                    <div class="ie-tip-item"><i class="fas fa-circle-dot"></i> The global price is used as a fallback when no state or city-specific price is defined.</div>
                    <div class="ie-tip-item"><i class="fas fa-circle-dot"></i> Crew prices override the global price for crew payment calculations.</div>
                    <div class="ie-tip-item"><i class="fas fa-circle-dot"></i> Inactive items are hidden from all pricing forms but their data is preserved.</div>
                </div>

            </div>

        </div>

        {{-- ══ FOOTER ══ --}}
        <div class="ie-foot">
            <a href="{{ route('superadmin.items.index') }}" class="ie-btn ie-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="ie-btn ie-btn-blue">
                <i class="fas fa-floppy-disk"></i> Update Item
            </button>
        </div>

    </form>
</div>

<script>
function ieBadge(on) {
    const b = document.getElementById('ie-badge');
    b.className = 'ie-status-badge ' + (on ? 'on' : 'off');
    b.innerHTML  = on
        ? '<i class="fas fa-check-circle" style="font-size:9px"></i> Active'
        : '<i class="fas fa-exclamation-triangle" style="font-size:9px"></i> Hidden from pricing';
}
</script>

@endsection