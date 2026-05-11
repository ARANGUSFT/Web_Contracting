@extends('admin.layouts.superadmin')
@section('title', 'Create Category')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.catc { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --r: 8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.catc-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.catc-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.catc-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#fdba74,#d97706 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.catc-glow {
    position: absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(217,119,6,.3) 0%,transparent 70%);
    pointer-events: none;
}
.catc-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.catc-hero-icon {
    width:52px; height:52px; border-radius:14px; flex-shrink:0;
    background:rgba(217,119,6,.2); border:1px solid rgba(217,119,6,.35);
    display:flex; align-items:center; justify-content:center; font-size:20px; color:#fdba74;
}
.catc-hero-title { font-size:22px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; }
.catc-hero-sub   { font-size:12.5px; font-weight:600; color:rgba(255,255,255,.38); margin-top:6px; }
.catc-back {
    position:relative; display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px; border-radius:var(--r);
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    color:rgba(255,255,255,.55); font-size:12px; font-weight:600;
    font-family:'Montserrat',sans-serif; text-decoration:none; transition:all .13s;
}
.catc-back:hover { background:rgba(255,255,255,.13); color:#fff; }

/* ── ERRORS ── */
.catc-err {
    padding:12px 16px; border-radius:var(--rlg); margin-bottom:18px;
    background:var(--rlt); border:1px solid var(--rbd); animation:fd .25s ease;
}
.catc-err-h { font-size:12px; font-weight:800; color:var(--red); display:flex; align-items:center; gap:6px; margin-bottom:5px; }
.catc-err ul { margin:0 0 0 16px; }
.catc-err li  { font-size:11.5px; font-weight:500; color:#991b1b; }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ── 2-COL LAYOUT ── */
.catc-body { display:grid; grid-template-columns:1fr 380px; gap:16px; align-items:start; }

/* ── CARDS ── */
.catc-card {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden; margin-bottom:12px;
}
.catc-card:last-child { margin-bottom:0; }
.catc-card-h {
    display:flex; align-items:center; gap:8px;
    padding:14px 20px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.catc-card-h i     { font-size:13px; color:var(--amb); }
.catc-card-title   { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.catc-card-b       { padding:20px; }

/* ── FIELDS ── */
.catc-lbl {
    display:block; font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; margin-bottom:6px;
}
.catc-lbl .req { color:var(--red); margin-left:2px; }
.catc-input, .catc-textarea {
    padding:10px 13px; border:1px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.catc-input:focus, .catc-textarea:focus {
    border-color:var(--amb); box-shadow:0 0 0 3px rgba(217,119,6,.09);
}
.catc-input.err { border-color:var(--red); background:var(--rlt); }
.catc-textarea  { resize:vertical; min-height:120px; }
.catc-ferr      { font-size:11px; font-weight:600; color:var(--red); margin-top:5px; display:flex; align-items:center; gap:4px; }
.catc-hint      { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:5px; }

/* ── TOGGLE ── */
.catc-toggle-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 16px; border:1px solid var(--bd2);
    border-radius:var(--rlg); background:var(--bg);
}
.catc-toggle-l   { display:flex; align-items:center; gap:12px; }
.catc-toggle-lbl  { font-size:13px; font-weight:700; color:var(--ink); }
.catc-toggle-hint { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:1px; }
.catc-toggle { position:relative; width:44px; height:24px; flex-shrink:0; }
.catc-toggle input { opacity:0; width:0; height:0; }
.catc-toggle-slider {
    position:absolute; inset:0; border-radius:9999px;
    background:var(--bd); cursor:pointer; transition:background .2s;
}
.catc-toggle-slider::before {
    content:''; position:absolute;
    width:18px; height:18px; border-radius:50%; background:#fff;
    left:3px; top:3px; transition:transform .2s;
    box-shadow:0 1px 3px rgba(0,0,0,.15);
}
.catc-toggle input:checked + .catc-toggle-slider { background:var(--grn); }
.catc-toggle input:checked + .catc-toggle-slider::before { transform:translateX(20px); }
.catc-status-badge {
    font-size:10.5px; font-weight:800; padding:4px 10px;
    border-radius:9999px; text-transform:uppercase; letter-spacing:.4px;
    display:inline-flex; align-items:center; gap:5px;
}
.catc-status-badge.on  { background:var(--glt); color:var(--grn); border:1px solid var(--gbd); }
.catc-status-badge.off { background:var(--bg);  color:var(--ink3); border:1px solid var(--bd); }

/* ── SIDEBAR INFO ── */
.catc-tip {
    background:var(--alt); border:1px solid var(--abd);
    border-radius:var(--rlg); padding:16px 18px;
}
.catc-tip-title { font-size:12px; font-weight:800; color:var(--amb); display:flex; align-items:center; gap:7px; margin-bottom:10px; }
.catc-tip-item  { display:flex; align-items:flex-start; gap:8px; margin-bottom:8px; font-size:12px; font-weight:500; color:var(--ink2); line-height:1.5; }
.catc-tip-item:last-child { margin-bottom:0; }
.catc-tip-item i { color:var(--amb); font-size:10px; margin-top:3px; flex-shrink:0; }

/* ── FOOTER ── */
.catc-foot {
    display:flex; align-items:center; justify-content:flex-end; gap:8px;
    padding:14px 18px; background:var(--bg);
    border:1px solid var(--bd); border-radius:var(--rlg); margin-top:4px;
}
.catc-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px; border-radius:var(--r);
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap;
}
.catc-btn i { font-size:10px; }
.catc-btn-amb   { background:var(--amb); color:#fff; }
.catc-btn-amb:hover { background:#b45309; color:#fff; }
.catc-btn-ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.catc-btn-ghost:hover { background:var(--bg); color:var(--ink); }

@media (max-width:900px)  { .catc-body { grid-template-columns:1fr; } }
@media (max-width:640px)  { .catc { padding:16px; } .catc-hero { padding:20px 18px; flex-direction:column; align-items:flex-start; } }
</style>

<div class="catc">

    {{-- ── HERO ── --}}
    <div class="catc-hero">
        <div class="catc-glow"></div>
        <div class="catc-hero-l">
            <div class="catc-hero-icon"><i class="fas fa-tag"></i></div>
            <div>
                <div class="catc-hero-title">Create Category</div>
                <div class="catc-hero-sub">Add a new item category to the system</div>
            </div>
        </div>
        <a href="{{ route('superadmin.item-categories.index') }}" class="catc-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Categories
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="catc-err">
        <div class="catc-err-h"><i class="fas fa-exclamation-circle"></i> Please fix the following:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.item-categories.store') }}" id="catc-form">
        @csrf

        <div class="catc-body">

            {{-- ══ LEFT ══ --}}
            <div>
                {{-- Details ── --}}
                <div class="catc-card">
                    <div class="catc-card-h">
                        <i class="fas fa-info-circle"></i>
                        <span class="catc-card-title">Category Details</span>
                    </div>
                    <div class="catc-card-b" style="display:flex;flex-direction:column;gap:16px">

                        <div>
                            <label class="catc-lbl" for="name">Category Name <span class="req">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="catc-input {{ $errors->has('name') ? 'err' : '' }}"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Roofing Materials, Hardware, Tools…"
                                   required autofocus>
                            @error('name')
                            <div class="catc-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="catc-lbl" for="description">
                                Description
                                <span style="color:var(--ink3);font-weight:500;text-transform:none;letter-spacing:0;margin-left:4px">(optional)</span>
                            </label>
                            <textarea name="description" id="description" class="catc-textarea"
                                      placeholder="Brief description of what items belong in this category…">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Status ── --}}
                <div class="catc-card">
                    <div class="catc-card-h">
                        <i class="fas fa-toggle-on"></i>
                        <span class="catc-card-title">Status</span>
                    </div>
                    <div class="catc-card-b">
                        <div class="catc-toggle-row">
                            <div class="catc-toggle-l">
                                <label class="catc-toggle">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                           id="tog-active" checked
                                           onchange="catcBadge(this.checked)">
                                    <span class="catc-toggle-slider"></span>
                                </label>
                                <div>
                                    <div class="catc-toggle-lbl">Active Category</div>
                                    <div class="catc-toggle-hint">Category will be available for use in items</div>
                                </div>
                            </div>
                            <span class="catc-status-badge on" id="catc-badge">
                                <i class="fas fa-check-circle" style="font-size:9px"></i> Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ RIGHT: tips sidebar ══ --}}
            <div>
                <div class="catc-tip">
                    <div class="catc-tip-title"><i class="fas fa-lightbulb"></i> Tips</div>
                    <div class="catc-tip-item"><i class="fas fa-circle-dot"></i> Use clear, descriptive names so items are easy to classify.</div>
                    <div class="catc-tip-item"><i class="fas fa-circle-dot"></i> Keep category names short — they'll appear in dropdowns throughout the app.</div>
                    <div class="catc-tip-item"><i class="fas fa-circle-dot"></i> Inactive categories won't appear in item creation forms but their items are preserved.</div>
                </div>
            </div>

        </div>

        {{-- ── FOOTER ── --}}
        <div class="catc-foot">
            <a href="{{ route('superadmin.item-categories.index') }}" class="catc-btn catc-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="catc-btn catc-btn-amb">
                <i class="fas fa-floppy-disk"></i> Save Category
            </button>
        </div>

    </form>
</div>

<script>
function catcBadge(on) {
    const b = document.getElementById('catc-badge');
    b.className = 'catc-status-badge ' + (on ? 'on' : 'off');
    b.innerHTML  = on
        ? '<i class="fas fa-check-circle" style="font-size:9px"></i> Active'
        : '<i class="fas fa-minus-circle" style="font-size:9px"></i> Inactive';
}
</script>

@endsection