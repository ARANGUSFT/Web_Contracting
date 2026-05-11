@extends('admin.layouts.superadmin')
@section('title', 'Edit Insurance · ' . $sub->name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ie { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1100px; }

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
.ie-hero {
    position: relative; border-radius: var(--rxl);
    padding: 26px 32px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; background: var(--ink); overflow: hidden;
}
.ie-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.ie-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#34d399,#0d9e6a 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.ie-glow {
    position:absolute; right:-60px; top:-60px; width:500px; height:280px;
    background: radial-gradient(ellipse,rgba(13,158,106,.3) 0%,transparent 70%);
    pointer-events:none;
}
.ie-hero-l { position:relative; display:flex; align-items:center; gap:14px; }
.ie-hero-icon {
    width:46px; height:46px; border-radius:12px; flex-shrink:0;
    background:rgba(13,158,106,.2); border:1px solid rgba(13,158,106,.35);
    display:flex; align-items:center; justify-content:center; font-size:17px; color:#34d399;
}
.ie-hero-title { font-size:18px; font-weight:800; color:#fff; letter-spacing:-.4px; line-height:1; }
.ie-hero-for   { font-size:11.5px; font-weight:600; color:rgba(255,255,255,.38); margin-top:4px; }
.ie-hero-for strong { color:#34d399; font-weight:700; }
.ie-back {
    position:relative; display:inline-flex; align-items:center; gap:6px;
    padding:8px 14px; border-radius:var(--r);
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
    color:rgba(255,255,255,.55); font-size:12px; font-weight:600;
    font-family:'Montserrat',sans-serif; text-decoration:none; transition:all .13s;
}
.ie-back:hover { background:rgba(255,255,255,.13); color:#fff; }

/* ── ERRORS ── */
.ie-err {
    padding:12px 16px; border-radius:var(--rlg); margin-bottom:18px;
    background:var(--rlt); border:1px solid var(--rbd); animation:fd .25s ease;
}
.ie-err-h { font-size:12px; font-weight:800; color:var(--red); display:flex; align-items:center; gap:6px; margin-bottom:5px; }
.ie-err ul { margin:0 0 0 16px; }
.ie-err li { font-size:11.5px; font-weight:500; color:#991b1b; }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ── 2-COL LAYOUT ── */
.ie-body { display:grid; grid-template-columns:1fr 380px; gap:16px; align-items:start; }
.ie-left  { display:flex; flex-direction:column; gap:16px; }
.ie-right { display:flex; flex-direction:column; gap:16px; }

/* ── CARDS ── */
.ie-card {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); overflow:hidden;
}
.ie-card-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 18px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ie-card-h i      { font-size:12.5px; color:var(--grn); }
.ie-card-title    { font-size:11.5px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ie-card-b        { padding:18px; }

/* ── SUB INFO ── */
.ie-sub-card {
    display:flex; align-items:center; gap:12px;
    padding:14px 16px; background:var(--glt);
    border:1px solid var(--gbd); border-radius:var(--rlg);
}
.ie-sub-av {
    width:40px; height:40px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#0d9e6a,#34d399);
    display:flex; align-items:center; justify-content:center;
    font-size:15px; font-weight:800; color:#fff;
}
.ie-sub-name { font-size:13px; font-weight:800; color:var(--ink); }
.ie-sub-co   { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:1px; }

/* ── FIELDS ── */
.ie-lbl {
    display:block; font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; margin-bottom:6px;
}
.ie-lbl .req { color:var(--red); margin-left:2px; }
.ie-input, .ie-textarea {
    padding:9px 12px; border:1px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.ie-input:focus, .ie-textarea:focus {
    border-color:var(--grn); box-shadow:0 0 0 3px rgba(13,158,106,.09);
}
.ie-input.err { border-color:var(--red); background:var(--rlt); }
.ie-textarea  { resize:vertical; min-height:120px; }
.ie-ferr      { font-size:11px; font-weight:600; color:var(--red); margin-top:5px; display:flex; align-items:center; gap:4px; }

/* ── EXISTING FILES ── */
.ie-existing-file {
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; border-radius:var(--r);
    border:1px solid var(--bd); background:var(--surf);
    margin-bottom:8px; transition:all .15s;
}
.ie-existing-file:last-child { margin-bottom:0; }
.ie-existing-file.marked-del {
    border-color:var(--rbd); background:var(--rlt);
    opacity:.7;
}
.ie-ef-ico { font-size:14px; color:var(--red); flex-shrink:0; }
.ie-ef-link {
    flex:1; font-size:12.5px; font-weight:600; color:var(--blue);
    text-decoration:none; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    min-width:0;
}
.ie-ef-link:hover { text-decoration:underline; }
.ie-ef-dl {
    width:26px; height:26px; border-radius:7px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:11px;
    border:1px solid var(--bd); background:none; color:var(--ink3);
    cursor:pointer; transition:all .13s; text-decoration:none;
}
.ie-ef-dl:hover { background:var(--blt); border-color:var(--bbd); color:var(--blue); }

/* custom toggle for delete */
.ie-del-toggle { display:flex; align-items:center; gap:6px; flex-shrink:0; cursor:pointer; }
.ie-del-toggle input { display:none; }
.ie-del-track {
    width:36px; height:20px; border-radius:9999px; background:var(--bd);
    position:relative; cursor:pointer; transition:background .2s; flex-shrink:0;
}
.ie-del-track::before {
    content:''; position:absolute;
    width:14px; height:14px; border-radius:50%; background:#fff;
    left:3px; top:3px; transition:transform .2s;
    box-shadow:0 1px 3px rgba(0,0,0,.15);
}
.ie-del-toggle input:checked + .ie-del-track { background:var(--red); }
.ie-del-toggle input:checked + .ie-del-track::before { transform:translateX(16px); }
.ie-del-lbl { font-size:10.5px; font-weight:700; color:var(--ink3); white-space:nowrap; }
.ie-del-toggle input:checked ~ .ie-del-lbl { color:var(--red); }

/* ── DROPZONE ── */
.ie-dz {
    border:2px dashed var(--bd); border-radius:var(--rlg);
    padding:28px 20px; text-align:center; cursor:pointer;
    background:var(--bg); transition:all .15s;
}
.ie-dz:hover, .ie-dz.over { border-color:var(--grn); background:var(--glt); }
.ie-dz.filled              { border-style:solid; border-color:var(--grn); background:var(--glt); }
.ie-dz-ico {
    width:48px; height:48px; border-radius:13px;
    background:var(--surf); border:1px solid var(--bd);
    display:flex; align-items:center; justify-content:center;
    font-size:18px; color:var(--ink3); margin:0 auto 12px; transition:all .15s;
}
.ie-dz:hover .ie-dz-ico, .ie-dz.over .ie-dz-ico, .ie-dz.filled .ie-dz-ico {
    border-color:var(--gbd); color:var(--grn);
}
.ie-dz-t    { font-size:13px; font-weight:700; color:var(--ink); margin-bottom:4px; }
.ie-dz-s    { font-size:11px; font-weight:500; color:var(--ink3); margin-bottom:14px; }
.ie-browse  {
    display:inline-flex; align-items:center; gap:6px;
    padding:7px 14px; border-radius:var(--r);
    background:var(--surf); border:1px solid var(--bd);
    color:var(--ink2); font-size:12px; font-weight:700;
    font-family:'Montserrat',sans-serif; cursor:pointer; transition:all .13s;
}
.ie-browse:hover { background:var(--blt); border-color:var(--bbd); color:var(--blue); }

/* ── NEW FILE LIST ── */
.ie-flist { display:flex; flex-direction:column; gap:6px; margin-top:10px; }
.ie-frow  {
    display:flex; align-items:center; gap:9px;
    padding:8px 11px; border:1px solid var(--gbd);
    border-radius:var(--r); background:var(--glt);
}
.ie-frow-ico  { font-size:13px; color:var(--red); flex-shrink:0; }
.ie-frow-name { flex:1; font-size:12px; font-weight:600; color:var(--ink2); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ie-frow-sz   { font-size:10px; font-weight:700; color:var(--grn); background:rgba(13,158,106,.1); border-radius:5px; padding:1px 6px; flex-shrink:0; }
.ie-frow-rm   {
    width:20px; height:20px; border-radius:5px; flex-shrink:0;
    background:none; border:none; cursor:pointer; color:var(--ink3);
    display:flex; align-items:center; justify-content:center; font-size:9px; transition:all .13s;
}
.ie-frow-rm:hover { background:var(--rlt); color:var(--red); }

/* ── FOOTER ── */
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
.ie-btn-grn   { background:var(--grn); color:#fff; }
.ie-btn-grn:hover { background:#0a8559; color:#fff; }
.ie-btn-ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.ie-btn-ghost:hover { background:var(--bg); color:var(--ink); }

@media (max-width:900px) { .ie-body { grid-template-columns:1fr; } }
@media (max-width:640px) { .ie { padding:16px; } .ie-hero { padding:20px 18px; flex-direction:column; align-items:flex-start; } }
</style>

<div class="ie">

    {{-- ── HERO ── --}}
    <div class="ie-hero">
        <div class="ie-glow"></div>
        <div class="ie-hero-l">
            <div class="ie-hero-icon"><i class="fas fa-file-contract"></i></div>
            <div>
                <div class="ie-hero-title">Edit Insurance Policy</div>
                <div class="ie-hero-for">Subcontractor: <strong>{{ $sub->name }} {{ $sub->last_name }}</strong></div>
            </div>
        </div>
        <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="ie-back">
            <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to List
        </a>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="ie-err">
        <div class="ie-err-h"><i class="fas fa-exclamation-circle"></i> Please fix the following:</div>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST"
          action="{{ route('superadmin.subcontractors.insurances.update', [$sub->id, $ins->id]) }}"
          enctype="multipart/form-data" id="ie-form">
        @csrf @method('PUT')

        <div class="ie-body">

            {{-- ══ LEFT ══ --}}
            <div class="ie-left">

                {{-- Sub info ── --}}
                <div class="ie-sub-card">
                    <div class="ie-sub-av">{{ strtoupper(substr($sub->name,0,1)) }}</div>
                    <div>
                        <div class="ie-sub-name">{{ $sub->name }} {{ $sub->last_name }}</div>
                        <div class="ie-sub-co">{{ $sub->company_name ?? 'No company' }}</div>
                    </div>
                </div>

                {{-- Policy details ── --}}
                <div class="ie-card">
                    <div class="ie-card-h">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="ie-card-title">Policy Details</span>
                    </div>
                    <div class="ie-card-b">

                        <div style="margin-bottom:16px">
                            <label class="ie-lbl" for="expires_at">Expiration Date <span class="req">*</span></label>
                            <input type="date" name="expires_at" id="expires_at"
                                   class="ie-input {{ $errors->has('expires_at') ? 'err' : '' }}"
                                   value="{{ old('expires_at', $ins->expires_at) }}" required>
                            @error('expires_at')
                            <div class="ie-ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="ie-lbl" for="notes">Additional Notes</label>
                            <textarea name="notes" id="notes" class="ie-textarea"
                                      placeholder="Policy number, coverage details, any important notes…">{{ old('notes', $ins->notes) }}</textarea>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div class="ie-right">

                {{-- Current files ── --}}
                @if(is_array($ins->file) && count($ins->file))
                <div class="ie-card">
                    <div class="ie-card-h">
                        <i class="fas fa-folder-open"></i>
                        <span class="ie-card-title">Current Files ({{ count($ins->file) }})</span>
                    </div>
                    <div class="ie-card-b">
                        @foreach($ins->file as $index => $f)
                        @if(isset($f['path'], $f['original_name']))
                        <div class="ie-existing-file" id="ef-{{ $index }}">
                            <i class="fas fa-file-pdf ie-ef-ico"></i>
                            <a href="{{ Storage::url($f['path']) }}" target="_blank"
                               class="ie-ef-link" title="{{ $f['original_name'] }}">
                                {{ $f['original_name'] }}
                            </a>
                            <a href="{{ Storage::url($f['path']) }}" download="{{ $f['original_name'] }}"
                               class="ie-ef-dl" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <label class="ie-del-toggle" title="Mark for deletion">
                                <input type="checkbox" name="delete_files[]"
                                       value="{{ $f['path'] }}"
                                       onchange="ieMarkDel(this, 'ef-{{ $index }}')">
                                <span class="ie-del-track"></span>
                                <span class="ie-del-lbl">Delete</span>
                            </label>
                        </div>
                        @endif
                        @endforeach
                        <p style="font-size:10.5px;font-weight:600;color:var(--ink3);margin-top:10px;margin-bottom:0">
                            <i class="fas fa-info-circle" style="margin-right:4px"></i>
                            Toggle "Delete" to remove a file on save
                        </p>
                    </div>
                </div>
                @endif

                {{-- Add more files ── --}}
                <div class="ie-card">
                    <div class="ie-card-h">
                        <i class="fas fa-paperclip"></i>
                        <span class="ie-card-title">Add More Files</span>
                        <span style="margin-left:6px;font-size:10px;font-weight:600;color:var(--ink3);text-transform:none;letter-spacing:0">(optional)</span>
                    </div>
                    <div class="ie-card-b">

                        <input type="file" name="file[]" id="ie-finput"
                               multiple accept=".pdf,.jpg,.jpeg,.png"
                               style="display:none"
                               onchange="ieAdd(this.files)">

                        <div class="ie-dz"
                             id="ie-dz"
                             onclick="document.getElementById('ie-finput').click()"
                             ondragover="event.preventDefault();this.classList.add('over')"
                             ondragleave="this.classList.remove('over')"
                             ondrop="event.preventDefault();this.classList.remove('over');ieAdd(event.dataTransfer.files)">
                            <div class="ie-dz-ico"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="ie-dz-t">Drop new files here</div>
                            <div class="ie-dz-s">PDF, JPG, PNG · Max 5 MB each</div>
                            <button type="button" class="ie-browse"
                                    onclick="event.stopPropagation();document.getElementById('ie-finput').click()">
                                <i class="fas fa-folder-open" style="font-size:10px"></i> Browse
                            </button>
                        </div>

                        <div class="ie-flist" id="ie-flist"></div>

                    </div>
                </div>

            </div>

        </div>

        {{-- ── FOOTER ── --}}
        <div class="ie-foot">
            <a href="{{ route('superadmin.subcontractors.insurances.index') }}" class="ie-btn ie-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="ie-btn ie-btn-grn">
                <i class="fas fa-floppy-disk"></i> Update Insurance
            </button>
        </div>

    </form>
</div>

<script>
/* ── EXISTING FILE: mark for deletion ── */
function ieMarkDel(cb, rowId) {
    const row = document.getElementById(rowId);
    row.classList.toggle('marked-del', cb.checked);
}

/* ── NEW FILES ── */
let iePool = [];

function ieAdd(fl) {
    for (const f of fl) iePool.push(f);
    ieSync(); ieRender();
    document.getElementById('ie-dz').classList.toggle('filled', iePool.length > 0);
}
function ieRemove(i) {
    iePool.splice(i, 1); ieSync(); ieRender();
    document.getElementById('ie-dz').classList.toggle('filled', iePool.length > 0);
}
function ieSync() {
    const dt = new DataTransfer();
    iePool.forEach(f => dt.items.add(f));
    document.getElementById('ie-finput').files = dt.files;
}
function ieRender() {
    const ul = document.getElementById('ie-flist');
    ul.innerHTML = '';
    iePool.forEach((f, i) => {
        const d = document.createElement('div');
        d.className = 'ie-frow';
        d.innerHTML = `<i class="fas fa-file-pdf ie-frow-ico"></i>
            <span class="ie-frow-name" title="${f.name}">${f.name}</span>
            <span class="ie-frow-sz">${ieSz(f.size)}</span>
            <button type="button" class="ie-frow-rm" onclick="ieRemove(${i})"><i class="fas fa-times"></i></button>`;
        ul.appendChild(d);
    });
}
function ieSz(b) {
    const u = ['B','KB','MB'];
    const i = Math.min(Math.floor(Math.log(Math.max(b,1))/Math.log(1024)), 2);
    return (b/Math.pow(1024,i)).toFixed(1)+' '+u[i];
}
</script>

@endsection