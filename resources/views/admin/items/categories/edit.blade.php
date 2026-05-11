@extends('admin.layouts.superadmin')
@section('title', 'Edit Category · ' . $itemCategory->name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.cate { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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
.cate-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.cate-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.cate-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#fdba74,#d97706 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.cate-glow {
    position: absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(217,119,6,.3) 0%,transparent 70%);
    pointer-events: none;
}
.cate-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.cate-hero-icon {
    width:52px; height:52px; border-radius:14px; flex-shrink:0;
    background:rgba(217,119,6,.2); border:1px solid rgba(217,119,6,.35);
    display:flex; align-items:center; justify-content:center; font-size:20px; color:#fdba74;
}
.cate-hero-title { font-size:22px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; }
.cate-hero-sub   { font-size:12.5px; font-weight:600; color:rgba(255,255,255,.38); margin-top:6px; }
.cate-hero-sub strong { color:#fdba74; font-weight:700; }
.cate-back {
    position:relative; display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px; border-radius:var(--r);
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    color:rgba(255,255,255,.55); font-size:12px; font-weight:600;
    font-family:'Montserrat',sans-serif; text-decoration:none; transition:all .13s;
}
.cate-back:hover { background:rgba(255,255,255,.13); color:#fff; }

/* ── ERRORS ── */
.cate-err {
    padding:12px 16px; border-radius:var(--rlg); margin-bottom:18px;
    background:var(--rlt); border:1px solid var(--rbd); animation:fd .25s ease;
}
.cate-err-h { font-size:12px; font-weight:800; color:var(--red); display:flex; align-items:center; gap:6px; margin-bottom:5px; }
.cate-err ul { margin:0 0 0 16px; }
.cate-err li  { font-size:11.5px; font-weight:500; color:#991b1b; }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ── 2-COL LAYOUT ── */
.cate-body { display:grid; grid-template-columns:1fr 380px; gap:16px; align-items:start; }

/* ── CARDS ── */
.cate-card {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden; margin-bottom:12px;
}
.cate-card:last-child { margin-bottom:0; }
.cate-card-h {
    display:flex; align-items:center; gap:8px;
    padding:14px 20px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.cate-card-h i     { font-size:13px; color:var(--amb); }
.cate-card-title   { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.cate-card-b       { padding:20px; }

/* ── FIELDS ── */
.cate-lbl {
    display:block; font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; margin-bottom:6px;
}
.cate-lbl .req { color:var(--red); margin-left:2px; }
.cate-input, .cate-textarea {
    padding:10px 13px; border:1px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.cate-input:focus, .cate-textarea:focus {
    border-color:var(--amb); box-shadow:0 0 0 3px rgba(217,119,6,.09);
}
.cate-input.err { border-color:var(--red); background:var(--rlt); }
.cate-textarea  { resize:vertical; min-height:120px; }
.cate-ferr      { font-size:11px; font-weight:600; color:var(--red); margin-top:5px; display:flex; align-items:center; gap:4px; }

/* ── TOGGLE ── */
.cate-toggle-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 16px; border:1px solid var(--bd2);
    border-radius:var(--rlg); background:var(--bg);
}
.cate-toggle-l   { display:flex; align-items:center; gap:12px; }
.cate-toggle-lbl  { font-size:13px; font-weight:700; color:var(--ink); }
.cate-toggle-hint { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:1px; }
.cate-toggle { position:relative; width:44px; height:24px; flex-shrink:0; }
.cate-toggle input { opacity:0; width:0; height:0; }
.cate-toggle-slider {
    position:absolute; inset:0; border-radius:9999px;
    background:var(--bd); cursor:pointer; transition:background .2s;
}
.cate-toggle-slider::before {
    content:''; position:absolute;
    width:18px; height:18px; border-radius:50%; background:#fff;
    left:3px; top:3px; transition:transform .2s;
    box-shadow:0 1px 3px rgba(0,0,0,.15);
}
.cate-toggle input:checked + .cate-toggle-slider { background:var(--grn); }
.cate-toggle input:checked + .cate-toggle-slider::before { transform:translateX(20px); }
.cate-status-badge {
    font-size:10.5px; font-weight:800; padding:4px 10px;
    border-radius:9999px; text-transform:uppercase; letter-spacing:.4px;
    display:inline-flex; align-items:center; gap:5px;
}
.cate-status-badge.on  { background:var(--glt); color:var(--grn); border:1px solid var(--gbd); }
.cate-status-badge.off { background:var(--bg);  color:var(--ink3); border:1px solid var(--bd); }

/* ── SIDEBAR SUMMARY ── */
.cate-summary {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden; margin-bottom:12px;
}
.cate-summary-h {
    display:flex; align-items:center; gap:8px;
    padding:14px 18px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.cate-summary-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.cate-summary-b { padding:16px 18px; display:flex; flex-direction:column; gap:10px; }
.cate-sum-row {
    display:flex; align-items:center; gap:10px;
    padding:9px 12px; border:1px solid var(--bd2);
    border-radius:var(--r); background:var(--bg);
}
.cate-sum-icon {
    width:32px; height:32px; border-radius:8px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:13px;
}
.cate-sum-icon.amb { background:var(--alt); color:var(--amb); }
.cate-sum-icon.grn { background:var(--glt); color:var(--grn); }
.cate-sum-icon.ink { background:var(--bg);  color:var(--ink3); border:1px solid var(--bd); }
.cate-sum-key { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.4px; }
.cate-sum-val { font-size:12.5px; font-weight:700; color:var(--ink); }

.cate-tip {
    background:var(--alt); border:1px solid var(--abd);
    border-radius:var(--rlg); padding:14px 16px;
}
.cate-tip-title { font-size:11.5px; font-weight:800; color:var(--amb); display:flex; align-items:center; gap:6px; margin-bottom:8px; }
.cate-tip-item  { display:flex; align-items:flex-start; gap:7px; margin-bottom:7px; font-size:11.5px; font-weight:500; color:var(--ink2); line-height:1.5; }
.cate-tip-item:last-child { margin-bottom:0; }
.cate-tip-item i { color:var(--amb); font-size:9px; margin-top:4px; flex-shrink:0; }

/* ── FOOTER ── */
.cate-foot {
    display:flex; align-items:center; justify-content:flex-end; gap:8px;
    padding:14px 18px; background:var(--bg);
    border:1px solid var(--bd); border-radius:var(--rlg); margin-top:4px;
}
.cate-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px; border-radius:var(--r);
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap;
}
.cate-btn i { font-size:10px; }
.cate-btn-amb   { background:var(--amb); color:#fff; }
.cate-btn-amb:hover { background:#b45309; color:#fff; }
.cate-btn-ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.cate-btn-ghost:hover { background:var(--bg); color:var(--ink); }

@media (max-width:900px)  { .cate-body { grid-template-columns:1fr; } }
@media (max-width:640px)  { .cate { padding:16px; } .cate-hero { padding:20px 18px; flex-direction:column; align-items:flex-start; } }
</style>

<div class="cate">

    {{-- ── HERO ── --}}
    <div class="cate-hero">
        <div class="cate-glow"></div>
        <div class="cate-hero-l">
            <div class="cate-hero-icon"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <div class="cate-hero-title">Edit Category</div>
                <div class="cate-hero-sub">Editing: <strong>{{ $itemCategory->name }}</strong></div>
            </div>
        </div>
        <a href="{{ route('superadmin.item-categories.index') }}" class="cate-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Categories
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="cate-err">
        <div class="cate-err-h"><i class="fas fa-exclamation-circle"></i> Please fix the following:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST"
          action="{{ route('superadmin.item-categories.update', $itemCategory) }}"
          id="cate-form">
        @csrf @method('PUT')

        <div class="cate-body">

            {{-- ══ LEFT ══ --}}
            <div>
                {{-- Details ── --}}
                <div class="cate-card">
                    <div class="cate-card-h">
                        <i class="fas fa-info-circle"></i>
                        <span class="cate-card-title">Category Details</span>
                    </div>
                    <div class="cate-card-b" style="display:flex;flex-direction:column;gap:16px">

                        <div>
                            <label class="cate-lbl" for="name">Category Name <span class="req">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="cate-input {{ $errors->has('name') ? 'err' : '' }}"
                                   value="{{ old('name', $itemCategory->name) }}"
                                   placeholder="e.g. Roofing Materials, Hardware, Tools…"
                                   required autofocus>
                            @error('name')
                            <div class="cate-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="cate-lbl" for="description">
                                Description
                                <span style="color:var(--ink3);font-weight:500;text-transform:none;letter-spacing:0;margin-left:4px">(optional)</span>
                            </label>
                            <textarea name="description" id="description" class="cate-textarea"
                                      placeholder="Brief description of what items belong in this category…">{{ old('description', $itemCategory->description ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Status ── --}}
                <div class="cate-card">
                    <div class="cate-card-h">
                        <i class="fas fa-toggle-on"></i>
                        <span class="cate-card-title">Status</span>
                    </div>
                    <div class="cate-card-b">
                        <div class="cate-toggle-row">
                            <div class="cate-toggle-l">
                                <label class="cate-toggle">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                           id="tog-active"
                                           {{ old('is_active', $itemCategory->is_active) ? 'checked' : '' }}
                                           onchange="cateBadge(this.checked)">
                                    <span class="cate-toggle-slider"></span>
                                </label>
                                <div>
                                    <div class="cate-toggle-lbl">Active Category</div>
                                    <div class="cate-toggle-hint">Category will be available for use in items</div>
                                </div>
                            </div>
                            <span class="cate-status-badge {{ old('is_active', $itemCategory->is_active) ? 'on' : 'off' }}"
                                  id="cate-badge">
                                <i class="fas fa-{{ old('is_active', $itemCategory->is_active) ? 'check-circle' : 'minus-circle' }}"
                                   style="font-size:9px"></i>
                                {{ old('is_active', $itemCategory->is_active) ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ RIGHT: sidebar ══ --}}
            <div>
                {{-- Current values summary ── --}}
                <div class="cate-summary">
                    <div class="cate-summary-h">
                        <i class="fas fa-chart-bar" style="font-size:12px;color:var(--ink3)"></i>
                        <span class="cate-summary-title">Current Values</span>
                    </div>
                    <div class="cate-summary-b">
                        <div class="cate-sum-row">
                            <div class="cate-sum-icon amb"><i class="fas fa-tag"></i></div>
                            <div>
                                <div class="cate-sum-key">Name</div>
                                <div class="cate-sum-val">{{ $itemCategory->name }}</div>
                            </div>
                        </div>
                        <div class="cate-sum-row">
                            <div class="cate-sum-icon {{ $itemCategory->is_active ? 'grn' : 'ink' }}">
                                <i class="fas fa-{{ $itemCategory->is_active ? 'check-circle' : 'minus-circle' }}"></i>
                            </div>
                            <div>
                                <div class="cate-sum-key">Status</div>
                                <div class="cate-sum-val">{{ $itemCategory->is_active ? 'Active' : 'Inactive' }}</div>
                            </div>
                        </div>
                        @if($itemCategory->created_at)
                        <div class="cate-sum-row">
                            <div class="cate-sum-icon ink"><i class="fas fa-calendar"></i></div>
                            <div>
                                <div class="cate-sum-key">Created</div>
                                <div class="cate-sum-val">{{ $itemCategory->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Tips ── --}}
                <div class="cate-tip">
                    <div class="cate-tip-title"><i class="fas fa-lightbulb"></i> Tips</div>
                    <div class="cate-tip-item"><i class="fas fa-circle-dot"></i> Renaming a category updates it everywhere it's used.</div>
                    <div class="cate-tip-item"><i class="fas fa-circle-dot"></i> Deactivating a category hides it from item forms but preserves existing items.</div>
                </div>
            </div>

        </div>

        {{-- ── FOOTER ── --}}
        <div class="cate-foot">
            <a href="{{ route('superadmin.item-categories.index') }}" class="cate-btn cate-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="cate-btn cate-btn-amb">
                <i class="fas fa-floppy-disk"></i> Update Category
            </button>
        </div>

    </form>
</div>

<script>
function cateBadge(on) {
    const b = document.getElementById('cate-badge');
    b.className = 'cate-status-badge ' + (on ? 'on' : 'off');
    b.innerHTML  = on
        ? '<i class="fas fa-check-circle" style="font-size:9px"></i> Active'
        : '<i class="fas fa-minus-circle" style="font-size:9px"></i> Inactive';
}
</script>

@endsection