@extends('admin.layouts.superadmin')
@section('title', 'Pending Users')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.pu { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1540px; }

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
.pu-hero {
    position: relative; border-radius: var(--rxl);
    padding: 34px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.pu-hero-glow {
    position: absolute; pointer-events: none;
    width: 600px; height: 300px;
    background: radial-gradient(ellipse, rgba(217,119,6,.3) 0%, transparent 70%);
    right: -60px; top: -60px;
}
.pu-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#fbbf24 0%,#d97706 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.pu-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.pu-hero-left { position: relative; display: flex; align-items: center; gap: 18px; }
.pu-hero-badge {
    width: 54px; height: 54px; border-radius: 14px; flex-shrink: 0;
    background: rgba(217,119,6,.2); border: 1px solid rgba(217,119,6,.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fbbf24;
}
.pu-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.pu-hero-sub   { font-size: 12.5px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.pu-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }
.pu-stat-chip {
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px; padding: 12px 18px; text-align: center;
}
.pu-stat-chip-n { font-size: 22px; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -.5px; }
.pu-stat-chip-l { font-size: 10px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }
.pu-stat-chip.alert { border-color: rgba(217,119,6,.4); background: rgba(217,119,6,.12); }
.pu-stat-chip.alert .pu-stat-chip-n { color: #fbbf24; }
.pu-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.pu-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── FLASH ── */
.pu-flash {
    display: flex; align-items: center; gap: 11px;
    padding: 12px 16px; border-radius: var(--rlg);
    margin-bottom: 18px; font-size: 13px; font-weight: 600;
    animation: fd .25s ease;
}
.pu-flash.ok  { background: var(--glt); border: 1px solid var(--gbd); color: #065f46; }
.pu-flash.err { background: var(--rlt); border: 1px solid var(--rbd); color: #991b1b; }
.pu-flash-x   { margin-left: auto; background: none; border: none; cursor: pointer; opacity: .5; font-size: 13px; color: inherit; }
.pu-flash-x:hover { opacity: 1; }
@keyframes fd { from { opacity:0; transform:translateY(-6px); } to { opacity:1; } }

/* ── FILTER ── */
.pu-filter {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); margin-bottom: 20px; overflow: hidden;
}
.pu-filter-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; cursor: pointer; user-select: none;
}
.pu-filter-head-l { display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; color: var(--ink2); text-transform: uppercase; letter-spacing: .5px; }
.pu-filter-head-l i { color: var(--ink3); }
.pu-filter-arr { color: var(--ink3); font-size: 10px; transition: transform .2s; }
.pu-filter-arr.open { transform: rotate(180deg); }
.pu-active-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); margin-left: 2px; display: inline-block; }
.pu-filter-body { padding: 18px 20px; border-top: 1px solid var(--bd2); }
.pu-fg { display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end; }
.pu-label { font-size: 11px; font-weight: 700; color: var(--ink3); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px; display: block; }
.pu-input, .pu-sel {
    width: 100%; padding: 9px 12px;
    border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none;
    transition: border-color .15s, box-shadow .15s; appearance: none;
}
.pu-input:focus, .pu-sel:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.pu-iw { position: relative; }
.pu-ii { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--ink3); font-size: 12px; pointer-events: none; }
.pu-input.pi { padding-left: 32px; }
.pu-sw { position: relative; }
.pu-sa { position: absolute; right: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--ink3); font-size: 10px; }
.pu-fa { display: flex; gap: 8px; }
.pu-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.pu-btn i { font-size: 11px; }
.pu-btn-blue  { background: var(--blue); color: #fff; }
.pu-btn-blue:hover  { background: #1344c2; color: #fff; }
.pu-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.pu-btn-ghost:hover { background: var(--bg); color: var(--ink); }

/* ── TABLE CARD ── */
.pu-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.05), 0 1px 3px rgba(0,0,0,.04);
}
.pu-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.pu-card-head-l { display: flex; align-items: center; gap: 10px; }
.pu-card-title { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -.3px; }
.pu-badge-count {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 9999px; background: var(--alt);
    color: var(--amb); border: 1px solid var(--abd);
}
.pu-page-info { font-size: 11.5px; font-weight: 500; color: var(--ink3); }

/* ── TABLE ── */
.pu-tbl { width: 100%; border-collapse: collapse; }
.pu-tbl thead tr { background: #fafbfd; border-bottom: 2px solid var(--bd); }
.pu-tbl th {
    padding: 11px 20px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .9px; white-space: nowrap;
}
.pu-tbl th.r { text-align: right; }
.pu-tbl td { padding: 14px 20px; border-bottom: 1px solid var(--bd2); vertical-align: middle; }
.pu-tbl tbody tr:last-child td { border-bottom: none; }
.pu-tbl tbody tr { transition: background .1s; }
.pu-tbl tbody tr:hover td { background: #fdf8ee; }

/* ── AVATAR ── */
.pu-av {
    width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: #fff; letter-spacing: -.3px;
    overflow: hidden; position: relative;
    background: linear-gradient(135deg,#d97706,#fbbf24);
}
.pu-av img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block; }
.pu-av span { position: relative; z-index: 1; }

/* ── CELLS ── */
.pu-name { font-size: 13px; font-weight: 700; color: var(--ink); }
.pu-cl { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 2px; }
.pu-cl i { font-size: 10px; width: 11px; text-align: center; }
.pu-co-name { font-size: 13px; font-weight: 600; color: var(--ink2); }
.pu-co-sub  { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 2px; display: flex; align-items: center; gap: 5px; }
.pu-na { font-size: 12px; color: var(--ink3); font-style: italic; }
.pu-date-main { font-size: 12.5px; font-weight: 600; color: var(--ink2); }
.pu-date-rel  { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 2px; }

/* ── PENDING PILL ── */
.pu-pending-pill {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10.5px; font-weight: 800; padding: 4px 10px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .5px;
    background: var(--alt); color: var(--amb); border: 1px solid var(--abd);
    animation: pulse-amb 2.5s infinite;
}
@keyframes pulse-amb {
    0%,100% { box-shadow: 0 0 0 0 rgba(217,119,6,.25); }
    50%      { box-shadow: 0 0 0 4px rgba(217,119,6,0); }
}
.pu-pill-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

/* ── ACTION BTNS ── */
.pu-acts { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
.pu-ab {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: var(--r);
    font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer;
    transition: all .13s; text-decoration: none; white-space: nowrap;
}
.pu-ab i { font-size: 10px; }
.pu-ab.view    { background: var(--blt); color: var(--blue); border-color: var(--bbd); }
.pu-ab.view:hover { background: #dbeafe; }
.pu-ab.approve { background: var(--glt); color: var(--grn); border-color: var(--gbd); }
.pu-ab.approve:hover { background: var(--grn); color: #fff; }

/* ── EMPTY ── */
.pu-empty { text-align: center; padding: 64px 24px; }
.pu-empty-icon {
    width: 64px; height: 64px; border-radius: 16px;
    background: var(--glt); border: 1px solid var(--gbd);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: var(--grn); margin: 0 auto 16px;
}
.pu-empty-t { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 6px; letter-spacing: -.2px; }
.pu-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); }

/* ── PAGINATION ── */
.pu-pag { padding: 14px 22px; border-top: 1px solid var(--bd2); background: #fafbfd; }

/* ── MODAL ── */
.pu-modal .modal-content {
    border: 1px solid var(--bd); border-radius: var(--rxl);
    box-shadow: 0 8px 40px rgba(0,0,0,.12); overflow: hidden;
    font-family: 'Montserrat', sans-serif;
}
.pu-modal-hero {
    background: var(--ink); padding: 22px 24px;
    display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden;
}
.pu-modal-hero-glow {
    position: absolute; pointer-events: none;
    width: 300px; height: 160px;
    background: radial-gradient(ellipse, rgba(217,119,6,.3) 0%, transparent 70%);
    right: -30px; top: -30px;
}
.pu-modal-av {
    width: 56px; height: 56px; border-radius: 14px; flex-shrink: 0;
    background: linear-gradient(135deg,#d97706,#fbbf24);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: #fff;
    overflow: hidden; position: relative;
    border: 2px solid rgba(255,255,255,.15);
}
.pu-modal-av img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.pu-modal-av span { position: relative; z-index: 1; }
.pu-modal-name { font-size: 17px; font-weight: 800; color: #fff; letter-spacing: -.3px; line-height: 1; position: relative; }
.pu-modal-sub  { font-size: 12px; color: rgba(255,255,255,.45); margin-top: 5px; font-weight: 500; position: relative; }
.pu-modal-close {
    position: relative; margin-left: auto;
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
    color: rgba(255,255,255,.6); width: 32px; height: 32px;
    border-radius: 8px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 13px; transition: all .13s; flex-shrink: 0;
}
.pu-modal-close:hover { background: rgba(255,255,255,.15); color: #fff; }
.pu-modal-body { padding: 22px 24px; }
.pu-modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }
.pu-modal-section {
    background: var(--bg); border: 1px solid var(--bd2);
    border-radius: var(--rlg); padding: 14px 16px;
}
.pu-modal-section-title {
    font-size: 10.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .7px;
    margin-bottom: 12px; display: flex; align-items: center; gap: 7px;
}
.pu-modal-section-title::after { content: ''; flex: 1; height: 1px; background: var(--bd); }
.pu-modal-row { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; font-size: 12.5px; }
.pu-modal-row:last-child { margin-bottom: 0; }
.pu-modal-row-k { font-weight: 700; color: var(--ink3); min-width: 80px; flex-shrink: 0; }
.pu-modal-row-v { font-weight: 500; color: var(--ink2); }

/* ── DOCS IN MODAL ── */
.pu-doc-row {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px; border: 1px solid var(--bd2);
    border-radius: var(--r); margin-bottom: 6px;
    background: var(--surf); transition: border-color .13s;
}
.pu-doc-row:hover { border-color: var(--blue); }
.pu-doc-ico {
    width: 30px; height: 30px; border-radius: 7px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
    background: var(--rlt); color: var(--red);
}
.pu-doc-name { flex: 1; font-size: 12px; font-weight: 600; color: var(--ink); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-decoration: none; }
.pu-doc-name:hover { color: var(--blue); }

/* ── REJECT SECTION ── */
.pu-reject-section {
    background: var(--rlt); border: 1px solid var(--rbd);
    border-radius: var(--rlg); padding: 16px 18px;
}
.pu-reject-title {
    font-size: 11px; font-weight: 800; color: var(--red);
    text-transform: uppercase; letter-spacing: .6px;
    margin-bottom: 10px; display: flex; align-items: center; gap: 7px;
}
.pu-reject-textarea {
    width: 100%; padding: 9px 12px; border: 1px solid var(--rbd);
    border-radius: var(--r); font-size: 12.5px; font-weight: 500;
    font-family: 'Montserrat', sans-serif; color: var(--ink);
    background: var(--surf); outline: none; resize: vertical; min-height: 72px;
    transition: border-color .13s;
}
.pu-reject-textarea:focus { border-color: var(--red); box-shadow: 0 0 0 3px rgba(217,38,38,.09); }
.pu-reject-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 10px; }
.pu-reject-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
}
.pu-reject-btn.cancel { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.pu-reject-btn.cancel:hover { background: var(--bg); }
.pu-reject-btn.confirm { background: var(--red); color: #fff; }
.pu-reject-btn.confirm:hover { background: #b91c1c; }

/* ── MODAL FOOTER ── */
.pu-modal-foot {
    padding: 14px 24px; border-top: 1px solid var(--bd2);
    background: var(--bg); display: flex; align-items: center;
    justify-content: flex-end; gap: 8px;
}
.pu-approve-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--r);
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    background: var(--grn); color: #fff; border: none; cursor: pointer;
    transition: background .13s;
}
.pu-approve-btn:hover { background: #0a8559; }
.pu-close-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 16px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    background: var(--surf); border: 1px solid var(--bd); color: var(--ink2); cursor: pointer;
    transition: all .13s;
}
.pu-close-btn:hover { background: var(--bg); }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 768px) {
    .pu { padding: 16px; }
    .pu-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .pu-fg { grid-template-columns: 1fr; }
    .pu-modal-grid { grid-template-columns: 1fr; }
    .pu-tbl th:nth-child(3), .pu-tbl td:nth-child(3),
    .pu-tbl th:nth-child(4), .pu-tbl td:nth-child(4) { display: none; }
}
</style>

<div class="pu">

    {{-- ── HERO ── --}}
    <div class="pu-hero">
        <div class="pu-hero-glow"></div>
        <div class="pu-hero-accent"></div>
        <div class="pu-hero-grid"></div>

        <div class="pu-hero-left">
            <div class="pu-hero-badge">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <div class="pu-hero-title">Pending Users</div>
                <div class="pu-hero-sub">Review and approve contractor registrations</div>
            </div>
        </div>

        <div class="pu-hero-right">
            <div class="pu-stat-chip {{ $users->total() > 0 ? 'alert' : '' }}">
                <div class="pu-stat-chip-n">{{ $users->total() }}</div>
                <div class="pu-stat-chip-l">{{ $users->total() == 1 ? 'Pending' : 'Pending' }}</div>
            </div>
            <a href="{{ route('superadmin.users.index') }}" class="pu-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- ── FLASH ── --}}
    @if(session('success'))
    <div class="pu-flash ok" id="pu-flash">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button class="pu-flash-x" onclick="document.getElementById('pu-flash').remove()"><i class="fas fa-times"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div class="pu-flash err" id="pu-flash-e">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button class="pu-flash-x" onclick="document.getElementById('pu-flash-e').remove()"><i class="fas fa-times"></i></button>
    </div>
    @endif

    {{-- ── FILTER ── --}}
    <div class="pu-filter">
        <div class="pu-filter-head" onclick="toggleF()">
            <div class="pu-filter-head-l">
                <i class="fas fa-sliders-h"></i> Filters
                @if(request('search') || request('sort'))
                    <span class="pu-active-dot"></span>
                @endif
            </div>
            <i class="fas fa-chevron-down pu-filter-arr {{ request('search')||request('sort') ? 'open' : '' }}" id="farr"></i>
        </div>
        <div id="fbody" style="{{ request('search')||request('sort') ? '' : 'display:none' }}" class="pu-filter-body">
            <form method="GET" action="{{ route('superadmin.users.pending') }}">
                <div class="pu-fg">
                    <div>
                        <label class="pu-label">Search</label>
                        <div class="pu-iw">
                            <i class="fas fa-search pu-ii"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="pu-input pi" placeholder="Name, email, company…">
                        </div>
                    </div>
                    <div>
                        <label class="pu-label">Sort by</label>
                        <div class="pu-sw">
                            <select name="sort" class="pu-sel">
                                <option value=""       {{ request('sort') == ''       ? 'selected' : '' }}>Newest first</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest first</option>
                            </select>
                            <i class="fas fa-chevron-down pu-sa"></i>
                        </div>
                    </div>
                    <div class="pu-fa">
                        <button type="submit" class="pu-btn pu-btn-blue">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <a href="{{ route('superadmin.users.pending') }}" class="pu-btn pu-btn-ghost">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="pu-card">

        <div class="pu-card-head">
            <div class="pu-card-head-l">
                <span class="pu-card-title">Awaiting Review</span>
                <span class="pu-badge-count">{{ $users->total() }} {{ Str::plural('user', $users->total()) }}</span>
            </div>
            <span class="pu-page-info">Page {{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
        </div>

        <div style="overflow-x:auto">
            <table class="pu-tbl">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Company</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th class="r">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $i => $user)
                    @php
                        $ini  = strtoupper(substr($user->name,0,1)).strtoupper(substr($user->last_name??'',0,1));
                        $docs = is_array($user->company_documents) ? $user->company_documents : [];
                    @endphp
                    <tr>

                        {{-- USER ── --}}
                        <td>
                            <div style="display:flex;align-items:center;gap:11px">
                                <div class="pu-av">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="{{ $user->name }}"
                                             onerror="this.style.display='none';this.parentElement.querySelector('span').style.display='inline'">
                                        <span style="display:none">{{ $ini }}</span>
                                    @else
                                        <span>{{ $ini }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="pu-name">{{ $user->name }} {{ $user->last_name }}</div>
                                    <div class="pu-cl"><i class="fas fa-envelope"></i> {{ $user->email }}</div>
                                    @if($user->phone)
                                    <div class="pu-cl"><i class="fas fa-phone"></i> {{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- COMPANY ── --}}
                        <td>
                            @if($user->company_name)
                                <div class="pu-co-name">{{ $user->company_name }}</div>
                                <div class="pu-co-sub">
                                    <i class="fas fa-briefcase" style="font-size:9px"></i>
                                    {{ $user->years_experience ?? 'N/A' }} yrs exp
                                </div>
                            @else
                                <span class="pu-na">Not specified</span>
                            @endif
                        </td>

                        {{-- DATE ── --}}
                        <td>
                            <div class="pu-date-main">{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="pu-date-rel">{{ $user->created_at->diffForHumans() }}</div>
                        </td>

                        {{-- STATUS ── --}}
                        <td>
                            <span class="pu-pending-pill">
                                <span class="pu-pill-dot"></span> Pending
                            </span>
                        </td>

                        {{-- ACTIONS ── --}}
                        <td>
                            <div class="pu-acts">
                                <button type="button" class="pu-ab view"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-{{ $user->id }}">
                                    <i class="fas fa-eye"></i> Review
                                </button>
                                <form action="{{ route('superadmin.users.approve', $user) }}"
                                      method="POST" style="display:inline" id="apf{{ $user->id }}">
                                    @csrf
                                    <button type="button" class="pu-ab approve"
                                            onclick="puApprove('{{ addslashes($user->name.' '.($user->last_name??'')) }}', document.getElementById('apf{{ $user->id }}'))">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- ── MODAL ── --}}
                    <div class="modal fade pu-modal" id="modal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">

                                {{-- Hero ── --}}
                                <div class="pu-modal-hero">
                                    <div class="pu-modal-hero-glow"></div>
                                    <div class="pu-modal-av">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                                 alt="{{ $user->name }}"
                                                 onerror="this.style.display='none';this.parentElement.querySelector('span').style.display='inline'">
                                            <span style="display:none">{{ $ini }}</span>
                                        @else
                                            <span>{{ $ini }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="pu-modal-name">{{ $user->name }} {{ $user->last_name }}</div>
                                        <div class="pu-modal-sub">{{ $user->email }}{{ $user->phone ? ' · '.$user->phone : '' }}</div>
                                    </div>
                                    <button type="button" class="pu-modal-close" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                {{-- Body ── --}}
                                <div class="pu-modal-body">

                                    <div class="pu-modal-grid">

                                        {{-- Company Info ── --}}
                                        <div class="pu-modal-section">
                                            <div class="pu-modal-section-title"><i class="fas fa-building" style="font-size:10px"></i> Company</div>
                                            <div class="pu-modal-row">
                                                <span class="pu-modal-row-k">Company</span>
                                                <span class="pu-modal-row-v">{{ $user->company_name ?? '—' }}</span>
                                            </div>
                                            <div class="pu-modal-row">
                                                <span class="pu-modal-row-k">Experience</span>
                                                <span class="pu-modal-row-v">{{ $user->years_experience ?? 'N/A' }} yrs</span>
                                            </div>
                                            <div class="pu-modal-row">
                                                <span class="pu-modal-row-k">Language</span>
                                                <span class="pu-modal-row-v">{{ $user->language ?? 'English' }}</span>
                                            </div>
                                            <div class="pu-modal-row">
                                                <span class="pu-modal-row-k">Registered</span>
                                                <span class="pu-modal-row-v">{{ $user->created_at->format('M d, Y H:i') }}</span>
                                            </div>
                                            <div class="pu-modal-row">
                                                <span class="pu-modal-row-k">Ago</span>
                                                <span class="pu-modal-row-v">{{ $user->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        {{-- Documents ── --}}
                                        <div class="pu-modal-section">
                                            <div class="pu-modal-section-title"><i class="fas fa-file-alt" style="font-size:10px"></i> Documents</div>
                                            @forelse($docs as $doc)
                                                @php
                                                    $file = is_array($doc)
                                                        ? $doc
                                                        : ['file_name' => $doc, 'original_name' => basename($doc)];
                                                    $ext  = strtolower(pathinfo($file['file_name'], PATHINFO_EXTENSION));
                                                @endphp
                                                <div class="pu-doc-row">
                                                    <div class="pu-doc-ico">
                                                        <i class="fas {{ in_array($ext,['jpg','jpeg','png','gif']) ? 'fa-file-image' : 'fa-file-pdf' }}"></i>
                                                    </div>
                                                    <a href="{{ asset('storage/'.$file['file_name']) }}"
                                                       target="_blank" class="pu-doc-name">
                                                        {{ $file['original_name'] ?? basename($file['file_name']) }}
                                                    </a>
                                                    <a href="{{ asset('storage/'.$file['file_name']) }}" target="_blank"
                                                       style="font-size:11px;font-weight:700;color:var(--blue);text-decoration:none;white-space:nowrap">
                                                        <i class="fas fa-external-link-alt" style="font-size:10px"></i> View
                                                    </a>
                                                </div>
                                            @empty
                                                <div style="font-size:12.5px;font-weight:500;color:var(--ink3);padding:8px 0">
                                                    <i class="fas fa-folder-open" style="opacity:.4;margin-right:6px"></i> No documents uploaded
                                                </div>
                                            @endforelse
                                        </div>

                                    </div>

                                    {{-- Reject Section ── --}}
                                    <div class="pu-reject-section">
                                        <div class="pu-reject-title">
                                            <i class="fas fa-ban" style="font-size:11px"></i> Reject User
                                        </div>
                                        <form action="{{ route('superadmin.users.reject', $user) }}"
                                              method="POST" id="rjf{{ $user->id }}">
                                            @csrf
                                            <textarea name="rejection_reason"
                                                      class="pu-reject-textarea"
                                                      placeholder="Reason for rejection (optional — will be sent to the user by email)"></textarea>
                                            <div class="pu-reject-actions">
                                                <button type="button" class="pu-reject-btn cancel" data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="pu-reject-btn confirm"
                                                        onclick="puReject('{{ addslashes($user->name.' '.($user->last_name??'')) }}', document.getElementById('rjf{{ $user->id }}'))">
                                                    <i class="fas fa-ban" style="font-size:10px"></i> Confirm Rejection
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>

                                {{-- Footer ── --}}
                                <div class="pu-modal-foot">
                                    <button type="button" class="pu-close-btn" data-bs-dismiss="modal">
                                        <i class="fas fa-times" style="font-size:10px"></i> Close
                                    </button>
                                    <form action="{{ route('superadmin.users.approve', $user) }}"
                                          method="POST" style="display:inline" id="apfm{{ $user->id }}">
                                        @csrf
                                        <button type="button" class="pu-approve-btn"
                                                onclick="puApprove('{{ addslashes($user->name.' '.($user->last_name??'')) }}', document.getElementById('apfm{{ $user->id }}'))">
                                            <i class="fas fa-check" style="font-size:11px"></i> Approve Contractor
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="5">
                            <div class="pu-empty">
                                <div class="pu-empty-icon"><i class="fas fa-check-circle"></i></div>
                                <div class="pu-empty-t">All caught up! 🎉</div>
                                <div class="pu-empty-s">No pending users at the moment.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="pu-pag">
            {{ $users->appends(request()->query())->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

</div>

<script>
function toggleF() {
    const b = document.getElementById('fbody');
    const a = document.getElementById('farr');
    const open = b.style.display !== 'none';
    b.style.display = open ? 'none' : 'block';
    a.classList.toggle('open', !open);
}

function puApprove(name, form) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Approve contractor?',
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px;line-height:1.6">
                     <strong>${name}</strong> will be approved and notified by email.
                   </p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d9e6a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) form.submit(); });
    } else {
        if (confirm(`Approve ${name}?`)) form.submit();
    }
}

function puReject(name, form) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Reject user?',
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px;line-height:1.6">
                     <strong>${name}</strong> will be rejected and notified by email.<br>
                     <span style="font-size:12px;color:#9099a8">This action cannot be undone.</span>
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, reject',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) form.submit(); });
    } else {
        if (confirm(`Reject ${name}?`)) form.submit();
    }
}
</script>

@endsection