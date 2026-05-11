@extends('admin.layouts.superadmin')

@section('title', 'Edit Contractor')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ep { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1200px; }

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
.ep-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 36px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ep-hero-glow {
    position: absolute; pointer-events: none;
    width: 500px; height: 260px;
    background: radial-gradient(ellipse, rgba(24,85,224,.4) 0%, transparent 70%);
    right: -40px; top: -40px;
}
.ep-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#4f80ff 0%,#1855e0 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.ep-hero-left { position: relative; display: flex; align-items: center; gap: 16px; }
.ep-hero-badge {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #8aadff;
}
.ep-hero-title { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -.4px; line-height: 1; }
.ep-hero-sub   { font-size: 12px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.ep-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }

/* ── AVATAR PREVIEW IN HERO ── */
.ep-av-hero {
    width: 56px; height: 56px; border-radius: 14px;
    overflow: hidden; flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.15);
    background: linear-gradient(135deg,#1855e0,#5b8af7);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: #fff;
    position: relative;
}
.ep-av-hero img {
    width: 100%; height: 100%; object-fit: cover;
    position: absolute; inset: 0;
}

.ep-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.ep-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── FLASH ── */
.ep-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: var(--rlg);
    margin-bottom: 18px; font-size: 13px; font-weight: 600;
    animation: fd .25s ease;
}
.ep-flash.err { background: var(--rlt); border: 1px solid var(--rbd); color: #991b1b; }
@keyframes fd { from { opacity:0; transform:translateY(-6px); } to { opacity:1; } }

/* ── LAYOUT ── */
.ep-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
.ep-grid.single { grid-template-columns: 1fr; }

/* ── CARD ── */
.ep-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden;
}
.ep-card-head {
    display: flex; align-items: center; gap: 9px;
    padding: 15px 20px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.ep-card-head i { color: var(--ink3); font-size: 13px; }
.ep-card-title { font-size: 12.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .4px; }
.ep-card-body  { padding: 20px; }

/* ── FORM ELEMENTS ── */
.ep-fgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.ep-fgrid.s1 { grid-template-columns: 1fr; }
.ep-field { display: flex; flex-direction: column; gap: 6px; }
.ep-field.span2 { grid-column: span 2; }
.ep-label {
    font-size: 10.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .6px;
}
.ep-label .req { color: var(--red); margin-left: 2px; }
.ep-input, .ep-select, .ep-textarea {
    padding: 9px 12px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none;
    transition: border-color .15s, box-shadow .15s; width: 100%; appearance: none;
}
.ep-input:focus, .ep-select:focus, .ep-textarea:focus {
    border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09);
}
.ep-input.err-field { border-color: var(--red); background: var(--rlt); }
.ep-textarea { resize: vertical; min-height: 90px; }
.ep-sw { position: relative; }
.ep-sa { position: absolute; right: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--ink3); font-size: 10px; }
.ep-hint { font-size: 11px; font-weight: 500; color: var(--ink3); }

/* ── PHOTO UPLOAD ── */
.ep-photo-wrap {
    display: flex; align-items: center; gap: 16px;
    padding: 14px; border: 1.5px dashed var(--bd);
    border-radius: var(--rlg); background: var(--bg);
    cursor: pointer; transition: border-color .15s;
}
.ep-photo-wrap:hover { border-color: var(--blue); }
.ep-photo-thumb {
    width: 72px; height: 72px; border-radius: 14px; flex-shrink: 0;
    overflow: hidden; position: relative;
    background: linear-gradient(135deg,#1855e0,#5b8af7);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 800; color: #fff;
    border: 2px solid var(--bd);
}
.ep-photo-thumb img {
    width: 100%; height: 100%; object-fit: cover;
    position: absolute; inset: 0;
}
.ep-photo-info { flex: 1; }
.ep-photo-name { font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 3px; }
.ep-photo-hint { font-size: 11px; font-weight: 500; color: var(--ink3); }
.ep-photo-change {
    font-size: 11.5px; font-weight: 700; color: var(--blue);
    background: var(--blt); border: 1px solid var(--bbd);
    border-radius: 6px; padding: 5px 12px; cursor: pointer;
    transition: all .13s; white-space: nowrap;
    font-family: 'Montserrat', sans-serif;
}
.ep-photo-change:hover { background: #dbeafe; }
.ep-file-hidden { display: none; }

/* ── DOCUMENTS ── */
.ep-doc-row {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border: 1px solid var(--bd2);
    border-radius: var(--r); margin-bottom: 6px;
    background: var(--surf); transition: border-color .15s;
}
.ep-doc-row:hover { border-color: var(--bd); }
.ep-doc-ico {
    width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
}
.ep-doc-ico.pdf  { background: var(--rlt); color: var(--red); }
.ep-doc-ico.img  { background: var(--blt); color: var(--blue); }
.ep-doc-ico.xls  { background: var(--glt); color: var(--grn); }
.ep-doc-ico.doc  { background: #f0f4ff; color: #3b5bdb; }
.ep-doc-ico.other{ background: var(--bg);  color: var(--ink3); }
.ep-doc-name { flex: 1; font-size: 12.5px; font-weight: 600; color: var(--ink); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-decoration: none; }
.ep-doc-name:hover { color: var(--blue); }
.ep-doc-type { font-size: 10px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; }
.ep-doc-date { font-size: 11px; font-weight: 500; color: var(--ink3); white-space: nowrap; }
.ep-doc-acts { display: flex; gap: 4px; }
.ep-doc-btn {
    width: 28px; height: 28px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 11.5px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none;
}
.ep-doc-btn:hover { background: var(--bg); border-color: var(--bd); }
.ep-doc-btn.del:hover { background: var(--rlt); border-color: var(--rbd); color: var(--red); }
.ep-no-docs {
    text-align: center; padding: 24px; color: var(--ink3);
    font-size: 12.5px; font-weight: 500; border: 1.5px dashed var(--bd);
    border-radius: var(--r); background: var(--bg);
}

/* ── STATUS TOGGLE CARD ── */
.ep-status-card {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-radius: var(--rlg);
    border: 1.5px solid var(--bd); background: var(--surf); margin-bottom: 18px;
}
.ep-status-left { display: flex; align-items: center; gap: 12px; }
.ep-status-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    background: {{ $user->is_active ? 'var(--grn)' : 'var(--red)' }};
    box-shadow: 0 0 0 3px {{ $user->is_active ? 'rgba(13,158,106,.2)' : 'rgba(217,38,38,.2)' }};
}
.ep-status-label { font-size: 13px; font-weight: 700; color: var(--ink); }
.ep-status-sub   { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 1px; }

/* ── FOOTER ACTIONS ── */
.ep-footer {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    padding: 18px 22px; background: var(--bg);
    border: 1px solid var(--bd); border-radius: var(--rlg);
    margin-top: 4px;
}
.ep-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--r);
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.ep-btn i { font-size: 11px; }
.ep-btn-blue  { background: var(--blue); color: #fff; }
.ep-btn-blue:hover  { background: #1344c2; color: #fff; }
.ep-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.ep-btn-ghost:hover { background: var(--bg); color: var(--ink); }
.ep-btn-grn   { background: var(--grn);  color: #fff; }
.ep-btn-grn:hover   { background: #0a8559; color: #fff; }
.ep-btn-red   { background: var(--rlt);  color: var(--red); border-color: var(--rbd); }
.ep-btn-red:hover   { background: var(--red); color: #fff; }
.ep-btn-amb   { background: var(--alt);  color: var(--amb); border-color: var(--abd); }
.ep-btn-amb:hover   { background: var(--amb); color: #fff; }

@media (max-width: 768px) {
    .ep { padding: 16px; }
    .ep-grid { grid-template-columns: 1fr; }
    .ep-fgrid { grid-template-columns: 1fr; }
    .ep-field.span2 { grid-column: span 1; }
}
</style>

<div class="ep">

    {{-- ── HERO ── --}}
    <div class="ep-hero">
        <div class="ep-hero-glow"></div>
        <div class="ep-hero-accent"></div>

        <div class="ep-hero-left">
            {{-- Foto del contractor en el hero --}}
            <div class="ep-av-hero" id="hero-av">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="{{ $user->name }}"
                         onerror="this.style.display='none'">
                @endif
                <span id="hero-initials" style="{{ $user->profile_photo ? 'display:none' : '' }}">
                    {{ strtoupper(substr($user->name,0,1)) }}{{ strtoupper(substr($user->last_name??'',0,1)) }}
                </span>
            </div>
            <div>
                <div class="ep-hero-title">{{ $user->name }} {{ $user->last_name }}</div>
                <div class="ep-hero-sub">
                    {{ $user->company_name ?? 'No company' }} &nbsp;·&nbsp;
                    {{ $user->email }}
                </div>
            </div>
        </div>

        <div class="ep-hero-right">
            <a href="{{ route('superadmin.users.contractors') }}" class="ep-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Contractors
            </a>
        </div>
    </div>

    {{-- ── ERRORS ── --}}
    @if($errors->any())
    <div class="ep-flash err" style="flex-direction:column;align-items:flex-start">
        <div style="display:flex;align-items:center;gap:8px;font-weight:800">
            <i class="fas fa-exclamation-circle"></i> Please fix the following errors:
        </div>
        <ul style="margin:8px 0 0 18px;font-weight:500;font-size:12.5px">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.contractors.update', $user->id) }}"
          enctype="multipart/form-data" id="edit-form">
        @csrf @method('PUT')

        {{-- ── STATUS CARD ── --}}
        <div class="ep-status-card">
            <div class="ep-status-left">
                <div class="ep-status-dot"></div>
                <div>
                    <div class="ep-status-label">
                        {{ $user->is_active ? 'Active contractor' : 'Inactive contractor' }}
                    </div>
                    <div class="ep-status-sub">
                        {{ $user->is_active
                            ? 'This contractor has access to the platform'
                            : 'This contractor does not have access to the platform' }}
                    </div>
                </div>
            </div>
            {{-- Toggle button (fuera del form principal) --}}
            @if($user->is_active)
                <button type="button" class="ep-btn ep-btn-amb"
                        onclick="cpToggle('{{ addslashes($user->name.' '.($user->last_name??'')) }}', true)">
                    <i class="fas fa-user-slash"></i> Deactivate
                </button>
            @else
                <button type="button" class="ep-btn ep-btn-grn"
                        onclick="cpToggle('{{ addslashes($user->name.' '.($user->last_name??'')) }}', false)">
                    <i class="fas fa-user-check"></i> Activate
                </button>
            @endif
        </div>

        {{-- ── MAIN GRID ── --}}
        <div class="ep-grid">

            {{-- ── PERSONAL INFO ── --}}
            <div class="ep-card">
                <div class="ep-card-head">
                    <i class="fas fa-user"></i>
                    <span class="ep-card-title">Personal Information</span>
                </div>
                <div class="ep-card-body">
                    <div class="ep-fgrid">

                        <div class="ep-field">
                            <label class="ep-label">First Name <span class="req">*</span></label>
                            <input type="text" name="name" class="ep-input {{ $errors->has('name') ? 'err-field' : '' }}"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="ep-field">
                            <label class="ep-label">Last Name</label>
                            <input type="text" name="last_name" class="ep-input"
                                   value="{{ old('last_name', $user->last_name) }}">
                        </div>

                        <div class="ep-field">
                            <label class="ep-label">Email <span class="req">*</span></label>
                            <input type="email" name="email" class="ep-input {{ $errors->has('email') ? 'err-field' : '' }}"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="ep-field">
                            <label class="ep-label">Phone</label>
                            <input type="tel" name="phone" class="ep-input"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="ep-field">
                            <label class="ep-label">Language</label>
                            <div class="ep-sw">
                                <select name="language" class="ep-select">
                                    <option value="English" {{ ($user->language ?? 'English') == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Spanish" {{ $user->language == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                                </select>
                                <i class="fas fa-chevron-down ep-sa"></i>
                            </div>
                        </div>

                     

                    </div>
                </div>
            </div>

            {{-- ── COMPANY INFO ── --}}
            <div class="ep-card">
                <div class="ep-card-head">
                    <i class="fas fa-building"></i>
                    <span class="ep-card-title">Company Information</span>
                </div>
                <div class="ep-card-body">
                    <div class="ep-fgrid">

                        <div class="ep-field span2">
                            <label class="ep-label">Company Name <span class="req">*</span></label>
                            <input type="text" name="company_name" class="ep-input {{ $errors->has('company_name') ? 'err-field' : '' }}"
                                   value="{{ old('company_name', $user->company_name) }}" required>
                        </div>

                        <div class="ep-field">
                            <label class="ep-label">Years of Experience</label>
                            <input type="number" name="years_experience" class="ep-input"
                                   value="{{ old('years_experience', $user->years_experience) }}" min="0">
                        </div>

                        <div class="ep-field">
                            <label class="ep-label">Member since</label>
                            <input type="text" class="ep-input" disabled
                                   value="{{ $user->created_at->format('M d, Y') }}"
                                   style="background:var(--bg);color:var(--ink3)">
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- ── PHOTO CARD (full width) ── --}}
        <div class="ep-card" style="margin-bottom:18px">
            <div class="ep-card-head">
                <i class="fas fa-camera"></i>
                <span class="ep-card-title">Profile Photo</span>
            </div>
            <div class="ep-card-body">
                <div class="ep-photo-wrap" onclick="document.getElementById('photo-input').click()">

                    {{-- Thumbnail ── se actualiza en tiempo real ── --}}
                    <div class="ep-photo-thumb" id="photo-thumb">
                        @if($user->profile_photo)
                            <img id="photo-preview-img"
                                 src="{{ asset('storage/'.$user->profile_photo) }}"
                                 alt="Profile photo">
                        @else
                            <span id="photo-initials">
                                {{ strtoupper(substr($user->name,0,1)) }}{{ strtoupper(substr($user->last_name??'',0,1)) }}
                            </span>
                            <img id="photo-preview-img" src="" alt="" style="display:none">
                        @endif
                    </div>

                    <div class="ep-photo-info">
                        <div class="ep-photo-name" id="photo-label">
                            {{ $user->profile_photo ? basename($user->profile_photo) : 'No photo uploaded' }}
                        </div>
                        <div class="ep-photo-hint">Click to change · JPG, PNG, GIF · Max 2MB</div>
                    </div>

                    <span class="ep-photo-change">
                        <i class="fas fa-upload" style="font-size:11px;margin-right:5px"></i>
                        {{ $user->profile_photo ? 'Change photo' : 'Upload photo' }}
                    </span>

                    <input type="file" id="photo-input" name="profile_photo"
                           accept="image/*" class="ep-file-hidden"
                           onchange="previewPhoto(event)">
                </div>
            </div>
        </div>

        {{-- ── DOCUMENTS CARD (full width) ── --}}
        <div class="ep-card" style="margin-bottom:18px">
            <div class="ep-card-head">
                <i class="fas fa-file-contract"></i>
                <span class="ep-card-title">Company Documents</span>
            </div>
            <div class="ep-card-body">

                {{-- Upload input --}}
                <div style="margin-bottom:16px">
                    <label class="ep-label" style="margin-bottom:7px;display:block">Upload New Documents</label>
                    <input type="file" name="company_documents[]" multiple
                           class="ep-input" style="padding:7px 12px"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                    <div class="ep-hint" style="margin-top:5px">Max 5MB each · PDF, JPG, PNG, DOC, XLS</div>
                </div>

                {{-- Existing documents --}}
                @if(!empty($user->company_documents) && is_array($user->company_documents) && count($user->company_documents))

                    <div style="margin-top:4px">
                        <div class="ep-label" style="margin-bottom:10px;display:block">Uploaded Documents</div>

                        @foreach($user->company_documents as $index => $doc)
                            @php
                                $file      = is_array($doc) ? $doc : ['file_name' => $doc, 'original_name' => basename($doc)];
                                $filename  = basename($file['file_name']);
                                $ext       = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                $icoClass  = match(true) {
                                    $ext === 'pdf'                   => 'pdf',
                                    in_array($ext,['jpg','jpeg','png','gif','webp']) => 'img',
                                    in_array($ext,['xls','xlsx'])    => 'xls',
                                    in_array($ext,['doc','docx'])    => 'doc',
                                    default                          => 'other',
                                };
                                $icoIcon   = match($icoClass) {
                                    'pdf'   => 'fa-file-pdf',
                                    'img'   => 'fa-file-image',
                                    'xls'   => 'fa-file-excel',
                                    'doc'   => 'fa-file-word',
                                    default => 'fa-file',
                                };
                            @endphp
                            <div class="ep-doc-row">
                                <div class="ep-doc-ico {{ $icoClass }}">
                                    <i class="fas {{ $icoIcon }}"></i>
                                </div>
                                <a href="{{ asset('storage/'.$file['file_name']) }}"
                                   target="_blank" class="ep-doc-name">
                                    {{ $file['original_name'] ?? $filename }}
                                </a>
                                <span class="ep-doc-type">{{ strtoupper($ext) }}</span>
                                <span class="ep-doc-date">
                                    {{ isset($file['uploaded_at']) ? \Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y') : '—' }}
                                </span>
                                <div class="ep-doc-acts">
                                    <a href="{{ asset('storage/'.$file['file_name']) }}" download
                                       class="ep-doc-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button"
                                            onclick="confirmDelDoc({{ $index }}, '{{ addslashes($file['original_name'] ?? $filename) }}')"
                                            class="ep-doc-btn del" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="ep-no-docs">
                        <i class="fas fa-folder-open" style="font-size:20px;opacity:.4;display:block;margin-bottom:8px"></i>
                        No documents uploaded yet
                    </div>
                @endif
            </div>
        </div>

        {{-- ── FOOTER ── --}}
        <div class="ep-footer">
            <a href="{{ route('superadmin.users.contractors') }}" class="ep-btn ep-btn-ghost">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="ep-btn ep-btn-blue">
                <i class="fas fa-floppy-disk"></i> Save Changes
            </button>
        </div>

    </form>

</div>

{{-- Toggle form (separado del edit form) --}}
<form id="toggle-form"
      action="{{ route('superadmin.contractors.toggle-active', $user->id) }}"
      method="POST" style="display:none">
    @csrf @method('PATCH')
</form>

<script>
/* ── PHOTO PREVIEW ── */
function previewPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const img      = document.getElementById('photo-preview-img');
        const initials = document.getElementById('photo-initials');
        const label    = document.getElementById('photo-label');
        const heroImg  = document.querySelector('#hero-av img') || (() => {
            const i = document.createElement('img');
            document.getElementById('hero-av').prepend(i);
            return i;
        })();
        const heroInit = document.getElementById('hero-initials');

        // Thumb en el card
        img.src = e.target.result;
        img.style.display = 'block';
        if (initials) initials.style.display = 'none';

        // Thumb en el hero
        heroImg.src = e.target.result;
        heroImg.style.display = 'block';
        if (heroInit) heroInit.style.display = 'none';

        // Label
        label.textContent = file.name;
    };
    reader.readAsDataURL(file);
}

/* ── TOGGLE ACTIVE ── */
function cpToggle(name, isActive) {
    const action   = isActive ? 'Deactivate' : 'Activate';
    const msg      = isActive
        ? `<strong>${name}</strong> will be set as <strong>Inactive</strong> and will lose platform access.`
        : `<strong>${name}</strong> will be set as <strong>Active</strong> and will regain platform access.`;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: `${action} contractor?`,
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px;line-height:1.6">${msg}</p>`,
            icon: isActive ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isActive ? '#d97706' : '#0d9e6a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${action.toLowerCase()}`,
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) document.getElementById('toggle-form').submit(); });
    } else {
        if (confirm(`${action} ${name}?`)) document.getElementById('toggle-form').submit();
    }
}

/* ── DELETE DOCUMENT ── */
function confirmDelDoc(index, name) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete document?',
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px"><strong>${name}</strong> will be permanently deleted.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => {
            if (!r.isConfirmed) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('superadmin.contractors.documents.delete', ['user' => $user->id, 'index' => 'IDX']) }}"
                .replace('IDX', index);
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        });
    } else {
        if (confirm(`Delete ${name}?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('superadmin.contractors.documents.delete', ['user' => $user->id, 'index' => 'IDX']) }}"
                .replace('IDX', index);
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        }
    }
}
</script>

@endsection