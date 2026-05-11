@extends('admin.layouts.superadmin')
@section('title', ($tipo === 'repair' ? 'Repair Ticket' : ($tipo === 'job_request' ? 'Job Request' : 'Emergency')) . ' #' . $id . ' · Photos')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.pv { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1540px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --pur:  #7c22e8; --plt:  #f5f0ff; --pbd:  #ddd0fb;
    --cyan: #0891b2; --clt:  #ecfeff; --cbd:  #a5f3fc;
    --r:    8px; --rlg: 13px; --rxl: 18px;
    --accent:    {{ $tipo === 'repair' ? 'var(--cyan)' : ($tipo === 'job_request' ? 'var(--blue)' : 'var(--red)') }};
    --accent-lt: {{ $tipo === 'repair' ? 'var(--clt)'  : ($tipo === 'job_request' ? 'var(--blt)'  : 'var(--rlt)') }};
    --accent-bd: {{ $tipo === 'repair' ? 'var(--cbd)'  : ($tipo === 'job_request' ? 'var(--bbd)'  : 'var(--rbd)') }};
}

/* ── HERO ── */
.pv-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 36px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.pv-hero-glow {
    position: absolute; pointer-events: none; width: 600px; height: 300px;
    background: radial-gradient(ellipse,
        {{ $tipo === 'repair' ? 'rgba(8,145,178,.3)' : ($tipo === 'job_request' ? 'rgba(24,85,224,.35)' : 'rgba(217,38,38,.3)') }} 0%,
        transparent 70%);
    right: -60px; top: -60px;
}
.pv-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,
        {{ $tipo === 'repair' ? '#67e8f9' : ($tipo === 'job_request' ? '#4f80ff' : '#f87171') }} 0%,
        var(--accent) 50%, transparent 100%);
    border-radius: 0 2px 2px 0;
}
.pv-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.pv-hero-left { position: relative; display: flex; align-items: center; gap: 16px; }
.pv-hero-badge {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    background: {{ $tipo === 'repair' ? 'rgba(8,145,178,.2)' : ($tipo === 'job_request' ? 'rgba(24,85,224,.2)' : 'rgba(217,38,38,.2)') }};
    border: 1px solid {{ $tipo === 'repair' ? 'rgba(8,145,178,.35)' : ($tipo === 'job_request' ? 'rgba(24,85,224,.35)' : 'rgba(217,38,38,.35)') }};
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: {{ $tipo === 'repair' ? '#67e8f9' : ($tipo === 'job_request' ? '#8aadff' : '#f87171') }};
}
.pv-hero-title { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -.4px; line-height: 1; }
.pv-hero-sub   { font-size: 12px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.pv-hero-right { position: relative; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
.pv-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.pv-back:hover { background: rgba(255,255,255,.13); color: #fff; }
.pv-share-btn, .pv-dl-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: none; cursor: pointer; transition: all .15s; text-decoration: none; white-space: nowrap;
}
.pv-share-btn { background: var(--accent); color: #fff; }
.pv-share-btn:hover { opacity: .88; color: #fff; }
.pv-dl-btn { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); color: rgba(255,255,255,.7); }
.pv-dl-btn:hover { background: rgba(255,255,255,.14); color: #fff; }
.pv-dl-btn:disabled { opacity: .35; cursor: not-allowed; }

/* ── FLASH ── */
.pv-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: var(--rlg);
    margin-bottom: 18px; font-size: 13px; font-weight: 600; animation: fd .25s ease;
}
.pv-flash.ok { background: var(--glt); border: 1px solid var(--gbd); color: #065f46; }
@keyframes fd { from { opacity:0; transform:translateY(-6px); } to { opacity:1; } }

/* ── SHARE URL BOX ── */
.pv-share-box {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 18px; border-radius: var(--rlg);
    background: var(--blt); border: 1px solid var(--bbd); margin-bottom: 20px;
}
.pv-share-box-label { font-size: 11px; font-weight: 800; color: var(--blue); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.pv-share-url-input {
    flex: 1; padding: 8px 12px; border: 1px solid var(--bbd);
    border-radius: var(--r); font-size: 12.5px; font-weight: 500;
    font-family: 'Montserrat', sans-serif; color: var(--ink2);
    background: var(--surf); outline: none;
}
.pv-copy-btn, .pv-open-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 8px 14px; border-radius: var(--r);
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: none; cursor: pointer; transition: all .13s; text-decoration: none; white-space: nowrap;
}
.pv-copy-btn { background: var(--blue); color: #fff; }
.pv-copy-btn:hover { background: #1344c2; }
.pv-open-btn { background: var(--surf); border: 1px solid var(--bbd); color: var(--blue); }
.pv-open-btn:hover { background: var(--blt); }

/* ── INFO CARD ── */
.pv-info-card { background: var(--surf); border: 1px solid var(--bd); border-radius: var(--rxl); overflow: hidden; margin-bottom: 22px; }
.pv-info-head {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 22px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.pv-info-head-icon {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
    background: var(--accent-lt); color: var(--accent);
}
.pv-info-head-title { font-size: 13px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.pv-info-body { padding: 20px 22px; }
.pv-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.pv-info-section-title {
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 10px; display: flex; align-items: center; gap: 8px;
}
.pv-info-section-title::after { content: ''; flex: 1; height: 1px; background: var(--bd); }
.pv-info-row {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 8px 0; border-bottom: 1px solid var(--bd2); gap: 12px;
}
.pv-info-row:last-child { border-bottom: none; }
.pv-info-key { font-size: 12px; font-weight: 700; color: var(--ink3); flex-shrink: 0; }
.pv-info-val { font-size: 12.5px; font-weight: 500; color: var(--ink2); text-align: right; }
.pv-info-val a { color: var(--blue); text-decoration: none; }
.pv-info-val a:hover { text-decoration: underline; }
.pv-status-pill { font-size: 10px; font-weight: 800; padding: 3px 9px; border-radius: 6px; text-transform: uppercase; letter-spacing: .4px; }
.pv-status-pill.completed  { background: var(--glt); color: var(--grn);  border: 1px solid var(--gbd); }
.pv-status-pill.pending    { background: var(--alt); color: var(--amb);  border: 1px solid var(--abd); }
.pv-status-pill.in_progress{ background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.pv-status-pill.open       { background: var(--alt); color: var(--amb);  border: 1px solid var(--abd); }
.pv-status-pill.resolved   { background: var(--glt); color: var(--grn);  border: 1px solid var(--gbd); }
.pv-status-pill.closed     { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd);  }
.pv-status-pill.cancelled  { background: var(--rlt); color: var(--red);  border: 1px solid var(--rbd); }
.pv-extra-item { padding: 10px 14px; border-radius: var(--rlg); background: var(--bg); border: 1px solid var(--bd2); margin-bottom: 8px; }
.pv-extra-label { font-size: 10px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.pv-extra-val   { font-size: 12.5px; font-weight: 500; color: var(--ink2); }

/* ── GALLERY CARD ── */
.pv-gallery-card { background: var(--surf); border: 1px solid var(--bd); border-radius: var(--rxl); overflow: hidden; }
.pv-gallery-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.pv-gallery-head-l { display: flex; align-items: center; gap: 10px; }
.pv-gallery-title { font-size: 13px; font-weight: 800; color: var(--ink); }
.pv-gallery-count {
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 9999px;
    background: var(--accent-lt); color: var(--accent); border: 1px solid var(--accent-bd);
}
.pv-view-toggle { display: flex; border: 1px solid var(--bd); border-radius: var(--r); overflow: hidden; }
.pv-view-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; color: var(--ink3); transition: all .13s; font-size: 12px; }
.pv-view-btn.active             { background: var(--ink); color: #fff; }
.pv-view-btn:not(.active):hover { background: var(--bg); color: var(--ink); }

/* ── SECTION DIVIDER ── */
.pv-section-divider {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 22px 8px; font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .8px;
}
.pv-section-divider::after { content: ''; flex: 1; height: 1px; background: var(--bd2); }

/* ── GRID ── */
.pv-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; padding: 18px; }
.pv-photo-card { border: 1px solid var(--bd); border-radius: var(--rlg); overflow: hidden; background: var(--surf); transition: all .15s; cursor: pointer; }
.pv-photo-card:hover { border-color: var(--accent); box-shadow: 0 4px 16px rgba(0,0,0,.08); transform: translateY(-1px); }
.pv-photo-card.crew-card:hover { border-color: var(--cyan); }
.pv-photo-thumb { width: 100%; height: 160px; object-fit: cover; display: block; background: var(--bg); }
.pv-photo-thumb.crew-thumb { border-bottom: 3px solid var(--cyan); }
.pv-photo-foot { padding: 9px 12px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--bd2); }
.pv-photo-num  { font-size: 11px; font-weight: 700; color: var(--ink); }
.pv-photo-date { font-size: 10.5px; font-weight: 500; color: var(--ink3); }
.pv-photo-actions { display: flex; gap: 4px; }
.pv-photo-action { width: 26px; height: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 11px; border: 1px solid var(--bd); background: none; color: var(--ink3); cursor: pointer; transition: all .13s; text-decoration: none; }
.pv-photo-action:hover      { background: var(--bg); color: var(--ink); }
.pv-photo-action.view:hover { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.pv-photo-action.dl:hover   { background: var(--glt); border-color: var(--gbd); color: var(--grn); }

/* ── LIST ── */
.pv-list { display: flex; flex-direction: column; gap: 8px; padding: 18px; }
.pv-list-row { display: flex; align-items: center; gap: 14px; padding: 10px 14px; border: 1px solid var(--bd2); border-radius: var(--rlg); background: var(--surf); transition: border-color .13s; }
.pv-list-row:hover { border-color: var(--accent); }
.pv-list-thumb { width: 72px; height: 56px; object-fit: cover; border-radius: 8px; flex-shrink: 0; cursor: pointer; }
.pv-list-info  { flex: 1; }
.pv-list-num   { font-size: 12.5px; font-weight: 700; color: var(--ink); }
.pv-list-date  { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 2px; }
.pv-list-actions { display: flex; gap: 6px; flex-shrink: 0; }
.pv-list-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; border-radius: var(--r); font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif; border: 1px solid transparent; cursor: pointer; transition: all .13s; text-decoration: none; }
.pv-list-btn.view       { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.pv-list-btn.view:hover { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.pv-list-btn.dl         { background: var(--grn); color: #fff; }
.pv-list-btn.dl:hover   { background: #0a8559; }

/* ── EMPTY ── */
.pv-empty { text-align: center; padding: 48px 24px; }
.pv-empty-icon { width: 56px; height: 56px; border-radius: 14px; background: var(--bg); border: 1px solid var(--bd); display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--ink3); margin: 0 auto 12px; }
.pv-empty-t { font-size: 14px; font-weight: 800; color: var(--ink); margin-bottom: 5px; }
.pv-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); }

/* ── LIGHTBOX ── */
.pv-lb { display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; }
.pv-lb.open { display: flex; }
.pv-lb-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,.85); backdrop-filter: blur(4px); cursor: pointer; }
.pv-lb-box { position: relative; z-index: 1; max-width: 90vw; max-height: 90vh; display: flex; flex-direction: column; align-items: center; }
.pv-lb-img { max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: var(--rlg); box-shadow: 0 8px 40px rgba(0,0,0,.5); display: block; }
.pv-lb-close { position: absolute; top: -42px; right: 0; width: 34px; height: 34px; border-radius: 9px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.7); font-size: 14px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .13s; }
.pv-lb-close:hover { background: rgba(255,255,255,.2); color: #fff; }
.pv-lb-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.8); font-size: 14px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .13s; }
.pv-lb-nav:hover { background: rgba(255,255,255,.2); color: #fff; }
.pv-lb-nav.prev { left: -56px; }
.pv-lb-nav.next { right: -56px; }
.pv-lb-counter { position: absolute; bottom: -36px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: 700; color: rgba(255,255,255,.6); font-family: 'Montserrat', sans-serif; white-space: nowrap; }

::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width:1200px) { .pv-grid { grid-template-columns: repeat(3,1fr); } }
@media (max-width:900px)  { .pv-grid { grid-template-columns: repeat(2,1fr); } .pv-info-grid { grid-template-columns: 1fr; } }
@media (max-width:640px)  { .pv { padding: 16px; } .pv-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; } .pv-grid { grid-template-columns: 1fr; } .pv-lb-nav { display: none; } }
</style>

<div class="pv">

    {{-- ── HERO ── --}}
    <div class="pv-hero">
        <div class="pv-hero-glow"></div>
        <div class="pv-hero-accent"></div>
        <div class="pv-hero-grid"></div>

        <div class="pv-hero-left">
            <div class="pv-hero-badge">
                <i class="fas fa-{{ $tipo === 'repair' ? 'helmet-safety' : ($tipo === 'job_request' ? 'hard-hat' : 'triangle-exclamation') }}"></i>
            </div>
            <div>
                <div class="pv-hero-title">
                    {{ $tipo === 'repair' ? 'Repair Ticket' : ($tipo === 'job_request' ? 'Job Request' : 'Emergency') }} #{{ $id }}
                </div>
                <div class="pv-hero-sub">
                    @if($tipo === 'repair')
                        {{ ($fotosAdmin?->count() ?? 0) + ($fotosCrew?->count() ?? 0) }} total photos
                        · {{ $fotosAdmin?->count() ?? 0 }} damage · {{ $fotosCrew?->count() ?? 0 }} work
                    @else
                        {{ $fotos->count() }} {{ Str::plural('photo', $fotos->count()) }} available
                    @endif
                </div>
            </div>
        </div>

        <div class="pv-hero-right">
            <a href="{{ route('superadmin.photos.projects') }}" class="pv-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Projects
            </a>

            {{-- Share link — disponible para todos los tipos --}}
            <form method="POST" action="{{ route('superadmin.photos.share') }}" style="display:inline">
                @csrf
                <input type="hidden" name="tipo" value="{{ $tipo }}">
                <input type="hidden" name="id"   value="{{ $id }}">
                <button type="submit" class="pv-share-btn">
                    <i class="fas fa-share-nodes" style="font-size:11px"></i> Share link
                </button>
            </form>

            @php
                $totalPhotos = $tipo === 'repair'
                    ? (($fotosAdmin?->count() ?? 0) + ($fotosCrew?->count() ?? 0))
                    : $fotos->count();
            @endphp
            <button type="button" id="dl-all-btn" class="pv-dl-btn" {{ $totalPhotos === 0 ? 'disabled' : '' }}>
                <i class="fas fa-download" style="font-size:11px"></i> Download all
            </button>
        </div>
    </div>

    {{-- ── FLASH ── --}}
    @if(session('status'))
    <div class="pv-flash ok">
        <i class="fas fa-check-circle"></i> {{ session('status') }}
    </div>
    @endif

    {{-- ── SHARE URL BOX — para todos los tipos ── --}}
    @php $publicUrl = session('share_url') ?? ($shareUrl ?? null); @endphp
    @if($publicUrl)
    <div class="pv-share-box">
        <div style="flex:1">
            <div class="pv-share-box-label">
                <i class="fas fa-link" style="font-size:9px;margin-right:4px"></i> Public Share URL
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <input id="share-url-input" class="pv-share-url-input" readonly value="{{ $publicUrl }}">
                <button type="button" class="pv-copy-btn" onclick="pvCopy()" id="copy-btn">
                    <i class="fas fa-copy" style="font-size:10px"></i> Copy
                </button>
                <a href="{{ $publicUrl }}" target="_blank" class="pv-open-btn">
                    <i class="fas fa-external-link-alt" style="font-size:10px"></i> Open
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ── PROJECT DETAILS ── --}}
    <div class="pv-info-card">
        <div class="pv-info-head">
            <div class="pv-info-head-icon">
                <i class="fas fa-{{ $tipo === 'repair' ? 'helmet-safety' : ($tipo === 'job_request' ? 'hard-hat' : 'triangle-exclamation') }}"></i>
            </div>
            <span class="pv-info-head-title">Project Details</span>
        </div>
        <div class="pv-info-body">

            @if($tipo === 'repair')
            {{-- ── REPAIR INFO ── --}}
            <div class="pv-info-grid">
                <div>
                    <div class="pv-info-section-title"><i class="fas fa-info-circle" style="font-size:9px"></i> Repair Info</div>
                    @if(!empty($projectInfo->ref_number))
                    <div class="pv-info-row"><span class="pv-info-key">Ref #</span><span class="pv-info-val">{{ $projectInfo->ref_number }}</span></div>
                    @endif
                    @if(!empty($projectInfo->company_name))
                    <div class="pv-info-row"><span class="pv-info-key">Company</span><span class="pv-info-val">{{ $projectInfo->company_name }}</span></div>
                    @endif
                    @if(!empty($projectInfo->status))
                    <div class="pv-info-row"><span class="pv-info-key">Status</span><span class="pv-info-val"><span class="pv-status-pill {{ $projectInfo->status }}">{{ ucfirst(str_replace('_',' ',$projectInfo->status)) }}</span></span></div>
                    @endif
                    @if(!empty($projectInfo->repair_date))
                    <div class="pv-info-row"><span class="pv-info-key">Date</span><span class="pv-info-val">{{ \Carbon\Carbon::parse($projectInfo->repair_date)->format('M d, Y') }}</span></div>
                    @endif
                    @if(!empty($projectInfo->reference_type))
                    <div class="pv-info-row"><span class="pv-info-key">Type</span><span class="pv-info-val">{{ ucfirst($projectInfo->reference_type) }}</span></div>
                    @endif
                </div>
                <div>
                    <div class="pv-info-section-title"><i class="fas fa-map-marker-alt" style="font-size:9px"></i> Location</div>
                    @if(!empty($projectInfo->job_address))
                    <div class="pv-info-row"><span class="pv-info-key">Address</span><span class="pv-info-val">{{ $projectInfo->job_address }}</span></div>
                    @endif
                    @if(!empty($projectInfo->description))
                    <div class="pv-info-section-title" style="margin-top:16px"><i class="fas fa-file-text" style="font-size:9px"></i> Description</div>
                    <div style="font-size:13px;color:var(--ink2);line-height:1.6;padding:8px 0">{{ $projectInfo->description }}</div>
                    @endif
                </div>
            </div>

            @else
            {{-- ── JOB / EMERGENCY INFO ── --}}
            <div class="pv-info-grid">
                <div>
                    <div class="pv-info-section-title"><i class="fas fa-info-circle" style="font-size:9px"></i> Basic Information</div>
                    @if(isset($projectInfo->company_name) && $projectInfo->company_name)
                    <div class="pv-info-row"><span class="pv-info-key">Company</span><span class="pv-info-val">{{ $projectInfo->company_name }}</span></div>
                    @endif
                    @if(isset($projectInfo->job_number_name) && $projectInfo->job_number_name)
                    <div class="pv-info-row"><span class="pv-info-key">Job #</span><span class="pv-info-val">{{ $projectInfo->job_number_name }}</span></div>
                    @endif
                    @if(isset($projectInfo->type_of_supplement) && $projectInfo->type_of_supplement)
                    <div class="pv-info-row"><span class="pv-info-key">Type</span><span class="pv-info-val">{{ $projectInfo->type_of_supplement }}</span></div>
                    @endif
                    @if(isset($projectInfo->status) && $projectInfo->status)
                    <div class="pv-info-row"><span class="pv-info-key">Status</span><span class="pv-info-val"><span class="pv-status-pill {{ $projectInfo->status }}">{{ ucfirst(str_replace('_',' ',$projectInfo->status)) }}</span></span></div>
                    @endif
                    <div class="pv-info-section-title" style="margin-top:16px"><i class="fas fa-user" style="font-size:9px"></i> {{ $tipo === 'job_request' ? 'Customer' : 'Contact' }}</div>
                    @if($tipo === 'job_request' && (isset($projectInfo->customer_first_name) || isset($projectInfo->customer_last_name)))
                    <div class="pv-info-row"><span class="pv-info-key">Customer</span><span class="pv-info-val">{{ ($projectInfo->customer_first_name ?? '') . ' ' . ($projectInfo->customer_last_name ?? '') }}</span></div>
                    @endif
                    @if($tipo === 'job_request' && isset($projectInfo->customer_phone) && $projectInfo->customer_phone)
                    <div class="pv-info-row"><span class="pv-info-key">Phone</span><span class="pv-info-val"><a href="tel:{{ $projectInfo->customer_phone }}">{{ $projectInfo->customer_phone }}</a></span></div>
                    @endif
                    @if($tipo === 'job_request' && isset($projectInfo->company_rep) && $projectInfo->company_rep)
                    <div class="pv-info-row"><span class="pv-info-key">Rep</span><span class="pv-info-val">{{ $projectInfo->company_rep }}</span></div>
                    @endif
                    @if($tipo === 'emergency' && isset($projectInfo->company_contact_email) && $projectInfo->company_contact_email)
                    <div class="pv-info-row"><span class="pv-info-key">Email</span><span class="pv-info-val"><a href="mailto:{{ $projectInfo->company_contact_email }}">{{ $projectInfo->company_contact_email }}</a></span></div>
                    @endif
                </div>
                <div>
                    <div class="pv-info-section-title"><i class="fas fa-map-marker-alt" style="font-size:9px"></i> Location</div>
                    @php
                        $street = $tipo === 'job_request' ? ($projectInfo->job_address_street ?? null) : ($projectInfo->job_address ?? null);
                        $city   = $projectInfo->job_address_city ?? ($projectInfo->job_city ?? null);
                        $state  = $projectInfo->job_address_state ?? ($projectInfo->job_state ?? null);
                        $zip    = $projectInfo->job_address_zip ?? ($projectInfo->job_zip ?? null);
                    @endphp
                    @if($street)<div class="pv-info-row"><span class="pv-info-key">Address</span><span class="pv-info-val">{{ $street }}</span></div>@endif
                    @if($city)  <div class="pv-info-row"><span class="pv-info-key">City</span>   <span class="pv-info-val">{{ $city }}</span></div>@endif
                    @if($state) <div class="pv-info-row"><span class="pv-info-key">State</span>  <span class="pv-info-val">{{ $state }}</span></div>@endif
                    @if($zip)   <div class="pv-info-row"><span class="pv-info-key">ZIP</span>    <span class="pv-info-val">{{ $zip }}</span></div>@endif
                    <div class="pv-info-section-title" style="margin-top:16px"><i class="fas fa-calendar" style="font-size:9px"></i> Dates</div>
                    @if(isset($projectInfo->install_date_requested) && $projectInfo->install_date_requested)
                    <div class="pv-info-row"><span class="pv-info-key">Install</span><span class="pv-info-val">{{ \Carbon\Carbon::parse($projectInfo->install_date_requested)->format('M d, Y') }}</span></div>
                    @endif
                    @if(isset($projectInfo->date_submitted) && $projectInfo->date_submitted)
                    <div class="pv-info-row"><span class="pv-info-key">Submitted</span><span class="pv-info-val">{{ \Carbon\Carbon::parse($projectInfo->date_submitted)->format('M d, Y') }}</span></div>
                    @endif
                    @if(isset($projectInfo->created_at) && $projectInfo->created_at)
                    <div class="pv-info-row"><span class="pv-info-key">Created</span><span class="pv-info-val">{{ \Carbon\Carbon::parse($projectInfo->created_at)->format('M d, Y · H:i') }}</span></div>
                    @endif
                </div>
            </div>

            @php
                $extras = [];
                if($tipo === 'job_request') {
                    if(!empty($projectInfo->special_instructions))          $extras['Special Instructions'] = $projectInfo->special_instructions;
                    if(!empty($projectInfo->starter_bundles_ordered))       $extras['Starter Bundles']       = $projectInfo->starter_bundles_ordered;
                    if(!empty($projectInfo->hip_and_ridge_ordered))         $extras['Hip & Ridge']           = $projectInfo->hip_and_ridge_ordered;
                    if(!empty($projectInfo->field_shingle_bundles_ordered)) $extras['Field Shingles']        = $projectInfo->field_shingle_bundles_ordered;
                } else {
                    if(!empty($projectInfo->terms_conditions)) $extras['Terms & Conditions'] = $projectInfo->terms_conditions;
                    if(!empty($projectInfo->requirements))     $extras['Requirements']        = $projectInfo->requirements;
                }
            @endphp
            @if(!empty($extras))
            <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--bd2)">
                <div class="pv-info-section-title"><i class="fas fa-list-check" style="font-size:9px"></i> Additional Information</div>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px">
                    @foreach($extras as $label => $val)
                    <div class="pv-extra-item">
                        <div class="pv-extra-label">{{ $label }}</div>
                        <div class="pv-extra-val">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         GALLERY
    ══════════════════════════════════════════════════ --}}
    <div class="pv-gallery-card">
        <div class="pv-gallery-head">
            <div class="pv-gallery-head-l">
                <i class="fas fa-images" style="font-size:15px;color:var(--ink3)"></i>
                <span class="pv-gallery-title">Photo Gallery</span>
                <span class="pv-gallery-count">
                    @if($tipo === 'repair')
                        {{ ($fotosAdmin?->count() ?? 0) + ($fotosCrew?->count() ?? 0) }} photos
                    @else
                        {{ $fotos->count() }} {{ Str::plural('photo', $fotos->count()) }}
                    @endif
                </span>
            </div>
            @if($tipo !== 'repair')
            <div class="pv-view-toggle">
                <button type="button" class="pv-view-btn active" id="btn-grid" onclick="setView('grid')" title="Grid view"><i class="fas fa-grip"></i></button>
                <button type="button" class="pv-view-btn"        id="btn-list" onclick="setView('list')" title="List view"><i class="fas fa-list"></i></button>
            </div>
            @endif
        </div>

        {{-- ════════ REPAIR: 2 secciones ════════ --}}
        @if($tipo === 'repair')

            {{-- Admin — damage photos --}}
            <div class="pv-section-divider" style="color:#e65100">
                <i class="fas fa-camera" style="font-size:9px;color:#e65100"></i>
                Damage Photos — Admin
                <span style="background:#fff3e0;color:#e65100;border:1px solid #ffcc80;font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;">
                    {{ $fotosAdmin?->count() ?? 0 }}
                </span>
            </div>

            @if($fotosAdmin && $fotosAdmin->count())
                <div class="pv-grid">
                    @foreach($fotosAdmin as $foto)
                    @php $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url); @endphp
                    <div class="pv-photo-card" onclick="pvLbSrc({{ $loop->index }}, 'admin')">
                        <img src="{{ $url }}" alt="Damage {{ $loop->iteration }}" class="pv-photo-thumb" loading="lazy">
                        <div class="pv-photo-foot" onclick="event.stopPropagation()">
                            <div>
                                <div class="pv-photo-num">Damage #{{ $loop->iteration }}</div>
                                <div class="pv-photo-date">{{ $foto->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="pv-photo-actions">
                                <a href="{{ $url }}" download class="pv-photo-action dl" title="Download"><i class="fas fa-download"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="pv-empty" style="padding:32px 24px">
                    <div class="pv-empty-icon"><i class="fas fa-camera"></i></div>
                    <div class="pv-empty-s">No damage photos uploaded yet.</div>
                </div>
            @endif

            {{-- Crew — work photos --}}
            <div class="pv-section-divider" style="color:#0891b2;border-top:1px solid var(--bd2);margin-top:8px">
                <i class="fas fa-helmet-safety" style="font-size:9px;color:#0891b2"></i>
                Work Photos — Crew
                <span style="background:#ecfeff;color:#0891b2;border:1px solid #a5f3fc;font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;">
                    {{ $fotosCrew?->count() ?? 0 }}
                </span>
            </div>

            @if($fotosCrew && $fotosCrew->count())
                <div class="pv-grid">
                    @foreach($fotosCrew as $foto)
                    @php $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url); @endphp
                    <div class="pv-photo-card crew-card" onclick="pvLbSrc({{ $loop->index }}, 'crew')">
                        <img src="{{ $url }}" alt="Work {{ $loop->iteration }}" class="pv-photo-thumb crew-thumb" loading="lazy">
                        <div class="pv-photo-foot" onclick="event.stopPropagation()">
                            <div>
                                <div class="pv-photo-num" style="color:#0891b2">Work #{{ $loop->iteration }}</div>
                                <div class="pv-photo-date">{{ $foto->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="pv-photo-actions">
                                <a href="{{ $url }}" download class="pv-photo-action dl" title="Download"><i class="fas fa-download"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="pv-empty" style="padding:32px 24px">
                    <div class="pv-empty-icon" style="background:#ecfeff;border-color:#a5f3fc">
                        <i class="fas fa-helmet-safety" style="color:#0891b2"></i>
                    </div>
                    <div class="pv-empty-s">No work photos uploaded by crew yet.</div>
                </div>
            @endif

        {{-- ════════ JOB / EMERGENCY: lista plana ════════ --}}
        @else
            @if($fotos->isEmpty())
                <div class="pv-empty">
                    <div class="pv-empty-icon"><i class="fas fa-camera"></i></div>
                    <div class="pv-empty-t">No photos yet</div>
                    <div class="pv-empty-s">This project doesn't have any uploaded photos.</div>
                </div>
            @else
                <div id="view-grid" class="pv-grid">
                    @foreach($fotos as $foto)
                    <div class="pv-photo-card" onclick="pvLb({{ $loop->index }})">
                        <img src="{{ asset('storage/'.$foto->url) }}" alt="Photo {{ $loop->iteration }}" class="pv-photo-thumb" loading="lazy">
                        <div class="pv-photo-foot" onclick="event.stopPropagation()">
                            <div>
                                <div class="pv-photo-num">Photo #{{ $loop->iteration }}</div>
                                <div class="pv-photo-date">{{ $foto->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="pv-photo-actions">
                                <button type="button" class="pv-photo-action view" onclick="pvLb({{ $loop->index }})"><i class="fas fa-expand"></i></button>
                                <a href="{{ asset('storage/'.$foto->url) }}" download="{{ $tipo }}-{{ $id }}-photo-{{ $loop->iteration }}.jpg" class="pv-photo-action dl"><i class="fas fa-download"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div id="view-list" class="pv-list" style="display:none">
                    @foreach($fotos as $foto)
                    <div class="pv-list-row">
                        <img src="{{ asset('storage/'.$foto->url) }}" alt="Photo {{ $loop->iteration }}" class="pv-list-thumb" onclick="pvLb({{ $loop->index }})">
                        <div class="pv-list-info">
                            <div class="pv-list-num">Photo #{{ $loop->iteration }}</div>
                            <div class="pv-list-date">{{ $foto->created_at->format('M d, Y · H:i') }}</div>
                        </div>
                        <div class="pv-list-actions">
                            <button type="button" class="pv-list-btn view" onclick="pvLb({{ $loop->index }})"><i class="fas fa-expand" style="font-size:10px"></i> View</button>
                            <a href="{{ asset('storage/'.$foto->url) }}" download="{{ $tipo }}-{{ $id }}-photo-{{ $loop->iteration }}.jpg" class="pv-list-btn dl"><i class="fas fa-download" style="font-size:10px"></i> Download</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>

</div>

{{-- ── LIGHTBOX ── --}}
<div class="pv-lb" id="pv-lb">
    <div class="pv-lb-backdrop" onclick="pvLbClose()"></div>
    <div class="pv-lb-box">
        <button class="pv-lb-close" onclick="pvLbClose()"><i class="fas fa-times"></i></button>
        <button class="pv-lb-nav prev" onclick="pvLbNav(-1)"><i class="fas fa-chevron-left"></i></button>
        <button class="pv-lb-nav next" onclick="pvLbNav(1)"><i class="fas fa-chevron-right"></i></button>
        <img id="pv-lb-img" class="pv-lb-img" src="" alt="">
        <div class="pv-lb-counter" id="pv-lb-counter"></div>
    </div>
</div>

<script>
const pvPhotos      = @json(($fotos      ?? collect())->map(fn($f) => asset('storage/'.$f->url)));
const pvPhotosAdmin = @json(($fotosAdmin ?? collect())->map(fn($f) => str_starts_with($f->url,'http') ? $f->url : asset('storage/'.$f->url)));
const pvPhotosCrew  = @json(($fotosCrew  ?? collect())->map(fn($f) => str_starts_with($f->url,'http') ? $f->url : asset('storage/'.$f->url)));

let pvIdx = 0;
let pvCurrentArr = pvPhotos;

function pvLb(idx) { pvCurrentArr = pvPhotos; pvIdx = idx; pvLbShow(); }

function pvLbSrc(idx, source) {
    pvCurrentArr = source === 'admin' ? pvPhotosAdmin : pvPhotosCrew;
    pvIdx = idx; pvLbShow();
}

function pvLbShow() {
    document.getElementById('pv-lb-img').src = pvCurrentArr[pvIdx];
    document.getElementById('pv-lb').classList.add('open');
    document.body.style.overflow = 'hidden';
    pvLbCounter();
}

function pvLbClose() {
    document.getElementById('pv-lb').classList.remove('open');
    document.body.style.overflow = '';
}

function pvLbNav(dir) {
    pvIdx = (pvIdx + dir + pvCurrentArr.length) % pvCurrentArr.length;
    document.getElementById('pv-lb-img').src = pvCurrentArr[pvIdx];
    pvLbCounter();
}

function pvLbCounter() {
    document.getElementById('pv-lb-counter').textContent = (pvIdx+1) + ' / ' + pvCurrentArr.length;
}

document.addEventListener('keydown', e => {
    if (!document.getElementById('pv-lb').classList.contains('open')) return;
    if (e.key === 'Escape')     pvLbClose();
    if (e.key === 'ArrowLeft')  pvLbNav(-1);
    if (e.key === 'ArrowRight') pvLbNav(1);
});

function setView(v) {
    const g = document.getElementById('view-grid');
    const l = document.getElementById('view-list');
    if (g) g.style.display = v === 'grid' ? '' : 'none';
    if (l) l.style.display = v === 'list' ? '' : 'none';
    document.getElementById('btn-grid')?.classList.toggle('active', v === 'grid');
    document.getElementById('btn-list')?.classList.toggle('active', v === 'list');
}

document.getElementById('dl-all-btn')?.addEventListener('click', () => {
    const all = @json($tipo === 'repair')
        ? [...pvPhotosAdmin, ...pvPhotosCrew]
        : pvPhotos;
    all.forEach((url, i) => {
        const a = document.createElement('a');
        a.href = url; a.download = '{{ $tipo }}-{{ $id }}-photo-' + (i+1) + '.jpg';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
    });
});

function pvCopy() {
    const inp = document.getElementById('share-url-input');
    if (!inp) return;
    inp.select(); document.execCommand('copy');
    const btn = document.getElementById('copy-btn');
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check" style="font-size:10px"></i> Copied!';
    setTimeout(() => { btn.innerHTML = orig; }, 2000);
}
</script>

@endsection