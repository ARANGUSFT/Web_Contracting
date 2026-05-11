@extends('admin.layouts.superadmin')
@section('title', 'Add Insurance · ' . $sub->name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ic { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1100px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --r: 8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.ic-hero {
    position: relative; border-radius: var(--rxl);
    padding: 26px 32px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; background: var(--ink); overflow: hidden;
}
.ic-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.ic-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#34d399,#0d9e6a 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.ic-glow {
    position:absolute; right:-60px; top:-60px; width:500px; height:280px;
    background: radial-gradient(ellipse,rgba(13,158,106,.3) 0%,transparent 70%);
    pointer-events:none;
}
.ic-hero-l { position:relative; display:flex; align-items:center; gap:14px; }
.ic-hero-icon {
    width:46px; height:46px; border-radius:12px; flex-shrink:0;
    background:rgba(13,158,106,.2); border:1px solid rgba(13,158,106,.35);
    display:flex; align-items:center; justify-content:center; font-size:17px; color:#34d399;
}
.ic-hero-title { font-size:18px; font-weight:800; color:#fff; letter-spacing:-.4px; line-height:1; }
.ic-hero-for   { font-size:11.5px; font-weight:600; color:rgba(255,255,255,.38); margin-top:4px; }
.ic-hero-for strong { color:#34d399; font-weight:700; }
.ic-back {
    position:relative; display:inline-flex; align-items:center; gap:6px;
    padding:8px 14px; border-radius:var(--r);
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    color:rgba(255,255,255,.55); font-size:12px; font-weight:600;
    font-family:'Montserrat',sans-serif; text-decoration:none; transition:all .13s;
}
.ic-back:hover { background:rgba(255,255,255,.13); color:#fff; }

/* ── ERRORS ── */
.ic-err {
    padding:12px 16px; border-radius:var(--rlg); margin-bottom:18px;
    background:var(--rlt); border:1px solid var(--rbd); animation:fd .25s ease;
}
.ic-err-h { font-size:12px; font-weight:800; color:var(--red); display:flex; align-items:center; gap:6px; margin-bottom:5px; }
.ic-err ul { margin:0 0 0 16px; }
.ic-err li { font-size:11.5px; font-weight:500; color:#991b1b; }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ── 2-COL LAYOUT ── */
.ic-body { display:grid; grid-template-columns:1fr 380px; gap:16px; align-items:start; }

.ic-left  { display:flex; flex-direction:column; gap:16px; }
.ic-right { display:flex; flex-direction:column; gap:16px; }

/* ── CARDS ── */
.ic-card {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden;
}
.ic-card-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 18px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ic-card-h i      { font-size:12.5px; color:var(--grn); }
.ic-card-title    { font-size:11.5px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ic-card-b        { padding:18px; }

/* ── SUB INFO ── */
.ic-sub-card {
    display:flex; align-items:center; gap:12px;
    padding:14px 16px; background:var(--glt);
    border:1px solid var(--gbd); border-radius:var(--rlg);
}
.ic-sub-av {
    width:40px; height:40px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#0d9e6a,#34d399);
    display:flex; align-items:center; justify-content:center;
    font-size:15px; font-weight:800; color:#fff;
}
.ic-sub-name { font-size:13px; font-weight:800; color:var(--ink); }
.ic-sub-co   { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:1px; }

/* ── FIELDS ── */
.ic-lbl {
    display:block; font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; margin-bottom:6px;
}
.ic-lbl .req { color:var(--red); margin-left:2px; }
.ic-input, .ic-textarea {
    padding:9px 12px; border:1px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.ic-input:focus, .ic-textarea:focus {
    border-color:var(--grn); box-shadow:0 0 0 3px rgba(13,158,106,.09);
}
.ic-input.err { border-color:var(--red); background:var(--rlt); }
.ic-textarea  { resize:vertical; min-height:130px; }
.ic-ferr      { font-size:11px; font-weight:600; color:var(--red); margin-top:5px; display:flex; align-items:center; gap:4px; }

/* ── DROPZONE ── */
.ic-dz {
    border:2px dashed var(--bd); border-radius:var(--rlg);
    padding:32px 20px; text-align:center; cursor:pointer;
    background:var(--bg); transition:all .15s;
}
.ic-dz:hover, .ic-dz.over   { border-color:var(--grn); background:var(--glt); }
.ic-dz.filled                { border-style:solid; border-color:var(--grn); background:var(--glt); }
.ic-dz.err-dz                { border-color:var(--red); background:var(--rlt); }

.ic-dz-ico {
    width:52px; height:52px; border-radius:14px;
    background:var(--surf); border:1px solid var(--bd);
    display:flex; align-items:center; justify-content:center;
    font-size:20px; color:var(--ink3); margin:0 auto 14px; transition:all .15s;
}
.ic-dz:hover .ic-dz-ico, .ic-dz.over .ic-dz-ico, .ic-dz.filled .ic-dz-ico {
    border-color:var(--gbd); color:var(--grn);
}
.ic-dz-t    { font-size:13.5px; font-weight:700; color:var(--ink); margin-bottom:4px; }
.ic-dz-s    { font-size:11.5px; font-weight:500; color:var(--ink3); margin-bottom:16px; }
.ic-browse  {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px; border-radius:var(--r);
    background:var(--surf); border:1px solid var(--bd);
    color:var(--ink2); font-size:12px; font-weight:700;
    font-family:'Montserrat',sans-serif; cursor:pointer; transition:all .13s;
}
.ic-browse:hover { background:var(--blt); border-color:var(--bbd); color:var(--blue); }

/* ── FILE LIST ── */
.ic-flist { display:flex; flex-direction:column; gap:6px; margin-top:12px; }
.ic-frow  {
    display:flex; align-items:center; gap:9px;
    padding:8px 11px; border:1px solid var(--gbd);
    border-radius:var(--r); background:var(--glt);
}
.ic-frow-ico  { font-size:13px; color:var(--red); flex-shrink:0; }
.ic-frow-name { flex:1; font-size:12px; font-weight:600; color:var(--ink2); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ic-frow-sz   { font-size:10px; font-weight:700; color:var(--grn); background:rgba(13,158,106,.1); border-radius:5px; padding:1px 6px; flex-shrink:0; }
.ic-frow-rm   {
    width:20px; height:20px; border-radius:5px; flex-shrink:0;
    background:none; border:none; cursor:pointer; color:var(--ink3);
    display:flex; align-items:center; justify-content:center; font-size:9px; transition:all .13s;
}
.ic-frow-rm:hover { background:var(--rlt); color:var(--red); }

/* ── FOOTER ── */
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
.ic-btn-grn   { background:var(--grn); color:#fff; }
.ic-btn-grn:hover { background:#0a8559; color:#fff; }
.ic-btn-ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.ic-btn-ghost:hover { background:var(--bg); color:var(--ink); }

@media (max-width:900px) { .ic-body { grid-template-columns:1fr; } }
@media (max-width:640px) { .ic { padding:16px; } .ic-hero { padding:20px 18px; flex-direction:column; align-items:flex-start; } }
</style>

<div class="ic">

    {{-- ── HERO ── --}}
    <div class="ic-hero">
        <div class="ic-glow"></div>
        <div class="ic-hero-l">
            <div class="ic-hero-icon"><i class="fas fa-file-medical"></i></div>
            <div>
                <div class="ic-hero-title">Add New Insurance Policy</div>
                <div class="ic-hero-for">Subcontractor: <strong>{{ $sub->name }} {{ $sub->last_name }}</strong></div>
            </div>
        </div>
        <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="ic-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="ic-err">
        <div class="ic-err-h"><i class="fas fa-exclamation-circle"></i> Please fix the following:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST"
          action="{{ route('superadmin.subcontractors.insurances.store', $sub->id) }}"
          enctype="multipart/form-data" id="ic-form">
        @csrf

        <div class="ic-body">

            {{-- ══ LEFT ══ --}}
            <div class="ic-left">

                {{-- Sub info ── --}}
                <div class="ic-sub-card">
                    <div class="ic-sub-av">{{ strtoupper(substr($sub->name,0,1)) }}</div>
                    <div>
                        <div class="ic-sub-name">{{ $sub->name }} {{ $sub->last_name }}</div>
                        <div class="ic-sub-co">{{ $sub->company_name ?? 'No company' }}</div>
                    </div>
                </div>

                {{-- Policy details ── --}}
                <div class="ic-card">
                    <div class="ic-card-h">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="ic-card-title">Policy Details</span>
                    </div>
                    <div class="ic-card-b">

                        <div style="margin-bottom:16px">
                            <label class="ic-lbl" for="expires_at">Expiration Date <span class="req">*</span></label>
                            <input type="date" name="expires_at" id="expires_at"
                                   class="ic-input {{ $errors->has('expires_at') ? 'err' : '' }}"
                                   value="{{ old('expires_at') }}" required>
                            @error('expires_at')
                            <div class="ic-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="ic-lbl" for="notes">Additional Notes</label>
                            <textarea name="notes" id="notes" class="ic-textarea"
                                      placeholder="Policy number, coverage details, any important notes…">{{ old('notes') }}</textarea>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div class="ic-right">
                <div class="ic-card">
                    <div class="ic-card-h">
                        <i class="fas fa-paperclip"></i>
                        <span class="ic-card-title">Insurance Documents</span>
                        <span style="margin-left:4px;color:var(--red);font-size:11px">*</span>
                    </div>
                    <div class="ic-card-b">

                        <input type="file" name="file[]" id="ic-finput"
                               multiple accept=".pdf,.jpg,.jpeg,.png"
                               style="display:none"
                               onchange="icAdd(this.files)">

                        <div class="ic-dz {{ $errors->has('file') || $errors->has('file.*') ? 'err-dz' : '' }}"
                             id="ic-dz"
                             onclick="document.getElementById('ic-finput').click()"
                             ondragover="event.preventDefault();this.classList.add('over')"
                             ondragleave="this.classList.remove('over')"
                             ondrop="event.preventDefault();this.classList.remove('over');icAdd(event.dataTransfer.files)">
                            <div class="ic-dz-ico"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="ic-dz-t">Drop files here</div>
                            <div class="ic-dz-s">PDF, JPG, PNG · Max 5 MB each</div>
                            <button type="button" class="ic-browse"
                                    onclick="event.stopPropagation();document.getElementById('ic-finput').click()">
                                <i class="fas fa-folder-open" style="font-size:10px"></i> Browse files
                            </button>
                        </div>

                        @error('file')
                        <div class="ic-ferr" style="margin-top:8px"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                        @error('file.*')
                        <div class="ic-ferr" style="margin-top:8px"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror

                        <div class="ic-flist" id="ic-flist"></div>

                    </div>
                </div>
            </div>

        </div>

        {{-- ── FOOTER ── --}}
        <div class="ic-foot">
            <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="ic-btn ic-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="button" class="ic-btn ic-btn-ghost"
                    onclick="document.getElementById('ic-form').reset();icPool=[];icSync();icRender();document.getElementById('ic-dz').className='ic-dz'">
                <i class="fas fa-rotate-left"></i> Reset
            </button>
            <button type="submit" class="ic-btn ic-btn-grn">
                <i class="fas fa-floppy-disk"></i> Save Insurance
            </button>
        </div>

    </form>
</div>

<script>
let icPool = [];

function icAdd(fl) {
    for (const f of fl) icPool.push(f);
    icSync(); icRender();
    const dz = document.getElementById('ic-dz');
    dz.classList.toggle('filled', icPool.length > 0);
    dz.classList.remove('err-dz');
}
function icRemove(i) {
    icPool.splice(i, 1); icSync(); icRender();
    document.getElementById('ic-dz').classList.toggle('filled', icPool.length > 0);
}
function icSync() {
    const dt = new DataTransfer();
    icPool.forEach(f => dt.items.add(f));
    document.getElementById('ic-finput').files = dt.files;
}
function icRender() {
    const ul = document.getElementById('ic-flist');
    ul.innerHTML = '';
    icPool.forEach((f, i) => {
        const d = document.createElement('div');
        d.className = 'ic-frow';
        d.innerHTML = `<i class="fas fa-file-pdf ic-frow-ico"></i>
            <span class="ic-frow-name" title="${f.name}">${f.name}</span>
            <span class="ic-frow-sz">${icSz(f.size)}</span>
            <button type="button" class="ic-frow-rm" onclick="icRemove(${i})"><i class="fas fa-times"></i></button>`;
        ul.appendChild(d);
    });
}
function icSz(b) {
    const u = ['B','KB','MB'];
    const i = Math.min(Math.floor(Math.log(Math.max(b,1))/Math.log(1024)), 2);
    return (b/Math.pow(1024,i)).toFixed(1)+' '+u[i];
}
</script>

@endsection