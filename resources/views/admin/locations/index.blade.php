@extends('admin.layouts.superadmin')
@section('title', 'Company Locations')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.loc { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --nvy:  #003366; --nvlt: #e8eef7; --nvbd: #b3c6e0;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ══ HERO ══ */
.loc-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.loc-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.loc-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.loc-hero-glow {
    position: absolute; right:-60px; top:-60px; width:560px; height:300px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events: none;
}
.loc-hero-l { position: relative; display: flex; align-items: center; gap: 16px; }
.loc-hero-icon {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center; font-size: 20px; color: #8aadff;
}
.loc-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.loc-hero-sub   { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.38); margin-top: 6px; }
.loc-hero-r { position: relative; display: flex; align-items: center; gap: 10px; }
.loc-chip {
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
    border-radius: 10px; padding: 9px 16px; text-align: center;
}
.loc-chip-n { font-size: 20px; font-weight: 800; color: #fff; line-height: 1; }
.loc-chip-l { font-size: 9.5px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }
.loc-add-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 20px; border-radius: var(--rlg);
    background: var(--blue); color: #fff; font-size: 13px; font-weight: 700;
    font-family: 'Montserrat', sans-serif; border: none; text-decoration: none;
    transition: background .13s; white-space: nowrap;
    box-shadow: 0 2px 10px rgba(24,85,224,.4);
}
.loc-add-btn:hover { background: #1344c2; color: #fff; }

/* ══ STATS ══ */
.loc-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 18px; }
.loc-stat {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 15px 18px;
    display: flex; align-items: center; gap: 13px;
}
.loc-stat-icon {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 15px;
}
.loc-stat-icon.blue { background: var(--blt); color: var(--blue); }
.loc-stat-icon.grn  { background: var(--glt); color: var(--grn); }
.loc-stat-icon.red  { background: var(--rlt); color: var(--red); }
.loc-stat-icon.nvy  { background: var(--nvlt); color: var(--nvy); }
.loc-stat-val { font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
.loc-stat-lbl { font-size: 11px; font-weight: 600; color: var(--ink3); margin-top: 2px; }

/* ══ SEARCH BAR ══ */
.loc-search-bar {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 12px 18px;
    margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
}
.loc-search-ico { font-size: 13px; color: var(--ink3); flex-shrink: 0; }
.loc-search-input {
    flex: 1; border: none; outline: none; background: transparent;
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif; color: var(--ink);
}
.loc-search-input::placeholder { color: var(--ink3); }
.loc-search-count {
    font-size: 11.5px; font-weight: 700; color: var(--blue);
    background: var(--blt); border: 1px solid var(--bbd);
    border-radius: 9999px; padding: 2px 10px; white-space: nowrap;
}

/* ══ COMPANY BLOCK ══ */
.loc-company { margin-bottom: 24px; }

/* ── enhanced company header ── */
.loc-co-head {
    border-radius: var(--rlg) var(--rlg) 0 0;
    overflow: hidden; position: relative;
}
.loc-co-head-bg {
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);
    background-size: 32px 32px; pointer-events: none;
}
.loc-co-head-glow {
    position: absolute; right: -40px; top: -40px; width: 300px; height: 180px;
    border-radius: 50%; opacity: .3; pointer-events: none;
}
.loc-co-head-inner {
    position: relative; padding: 22px 28px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
}
.loc-co-head.nvy { background: var(--nvy); }
.loc-co-head.nvy .loc-co-head-glow { background: radial-gradient(circle, #4f80ff, transparent); }
.loc-co-head.red { background: var(--red); }
.loc-co-head.red .loc-co-head-glow { background: radial-gradient(circle, #f87171, transparent); }

/* avatar */
.loc-co-av-wrap { position: relative; flex-shrink: 0; }
.loc-co-av {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.15); border: 2px solid rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 800; color: #fff;
}
.loc-co-dot {
    position: absolute; bottom: -2px; right: -2px;
    width: 14px; height: 14px; border-radius: 50%;
    background: #22c55e; border: 2px solid rgba(0,0,0,.25);
}

/* company info */
.loc-co-info { flex: 1; min-width: 0; }
.loc-co-name  { font-size: 16px; font-weight: 800; color: #fff; letter-spacing: -.3px; }
.loc-co-meta  { display: flex; align-items: center; gap: 8px; margin-top: 5px; flex-wrap: wrap; }
.loc-co-email { font-size: 12px; color: rgba(255,255,255,.55); font-weight: 500; }
.loc-co-pill  {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 9999px; background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.75);
}

/* company stats mini */
.loc-co-stats { display: flex; gap: 8px; align-items: center; }
.loc-co-stat {
    display: flex; flex-direction: column; align-items: center;
    padding: 8px 14px; border-radius: var(--r);
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
    min-width: 60px;
}
.loc-co-stat-n { font-size: 18px; font-weight: 800; color: #fff; line-height: 1; }
.loc-co-stat-l { font-size: 9px; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .7px; margin-top: 2px; }

.loc-add-state-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--rlg);
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.22);
    color: rgba(255,255,255,.85); font-size: 12px; font-weight: 700;
    font-family: 'Montserrat', sans-serif; cursor: pointer; transition: all .15s;
    white-space: nowrap;
}
.loc-add-state-btn:hover { background: rgba(255,255,255,.22); color: #fff; }

/* ══ STATES GRID ══ */
.loc-states-grid {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 14px; padding: 22px 24px;
    background: var(--surf); border: 1px solid var(--bd); border-top: none;
}

/* ── enhanced state card ── */
.loc-state-card {
    background: var(--bg); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden; transition: box-shadow .15s;
}
.loc-state-card:hover { box-shadow: 0 3px 16px rgba(0,0,0,.08); }
.loc-state-card .top-bar { height: 3px; background: var(--nvy); }
.loc-state-card.no-base .top-bar { background: var(--red); }

.loc-state-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 15px; border-bottom: 1px solid var(--bd2); background: var(--surf);
}
.loc-state-head-l { display: flex; align-items: center; gap: 10px; }
.loc-state-icon {
    width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
    background: var(--nvlt); color: var(--nvy); font-weight: 800;
}
.loc-state-name { font-size: 14px; font-weight: 800; color: var(--ink); }
.loc-state-meta { display: flex; gap: 5px; margin-top: 3px; }
.loc-state-badge {
    font-size: 10px; font-weight: 700; padding: 2px 7px;
    border-radius: 5px; background: var(--bd2); color: var(--ink3);
}
.loc-state-badge.base  { background: var(--nvlt); color: var(--nvy); }
.loc-state-badge.city  { background: var(--rlt);  color: var(--red); }
.loc-state-badge.nobase{ background: var(--rlt);  color: var(--red); }

.loc-add-city-btn {
    width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
    background: none; border: 1px solid var(--bd); color: var(--ink3);
    display: flex; align-items: center; justify-content: center; font-size: 12px;
    cursor: pointer; transition: all .13s;
}
.loc-add-city-btn:hover { background: var(--glt); border-color: var(--gbd); color: var(--grn); }

.loc-state-body { padding: 12px 14px; display: flex; flex-direction: column; gap: 8px; }

/* base row */
.loc-base-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 12px; border-radius: var(--r);
    background: var(--nvlt); border: 1px solid var(--nvbd);
}
.loc-base-row-l { display: flex; align-items: center; gap: 9px; }
.loc-base-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: rgba(0,51,102,.15); display: flex; align-items: center;
    justify-content: center; font-size: 12px; color: var(--nvy);
}
.loc-base-label { font-size: 12.5px; font-weight: 800; color: var(--nvy); }
.loc-base-sub   { font-size: 10.5px; font-weight: 500; color: rgba(0,51,102,.45); margin-top: 1px; }
.loc-no-base {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 9px 12px; border-radius: var(--r);
    background: var(--rlt); border: 1px solid var(--rbd);
    font-size: 11.5px; font-weight: 700; color: var(--red);
}

/* cities */
.loc-cities-section { border-top: 1px solid var(--bd2); padding-top: 8px; }
.loc-cities-label {
    display: flex; align-items: center; justify-content: space-between;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px;
}
.loc-cities-scroll {
    max-height: 160px; overflow-y: auto; display: flex; flex-direction: column; gap: 4px;
    scrollbar-width: thin; scrollbar-color: #cdd0d8 transparent;
}
.loc-cities-scroll::-webkit-scrollbar { width: 3px; }
.loc-cities-scroll::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }
.loc-city-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 7px 10px; border-radius: var(--r);
    background: var(--surf); border: 1px solid var(--bd2); transition: border-color .13s;
}
.loc-city-row:hover { border-color: var(--blue); }
.loc-city-l   { display: flex; align-items: center; gap: 8px; }
.loc-city-icon {
    width: 24px; height: 24px; border-radius: 6px;
    background: var(--rlt); display: flex; align-items: center;
    justify-content: center; font-size: 10px; color: var(--red);
}
.loc-city-name { font-size: 12px; font-weight: 600; color: var(--ink2); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px; }
.loc-city-no   { text-align: center; padding: 10px 8px; font-size: 11.5px; font-weight: 500; color: var(--ink3); }
.loc-more      { text-align: center; padding: 5px 0; font-size: 11px; font-weight: 700; color: var(--ink3); border-top: 1px solid var(--bd2); }

/* actions */
.loc-row-acts { display: flex; gap: 3px; }
.loc-row-act {
    width: 26px; height: 26px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer; transition: all .13s; text-decoration: none;
}
.loc-row-act:hover        { background: var(--bg); border-color: var(--bd); }
.loc-row-act.price:hover  { background: var(--glt); border-color: var(--gbd); color: var(--grn); }
.loc-row-act.edit:hover   { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.loc-row-act.del:hover    { background: var(--rlt); border-color: var(--rbd); color: var(--red); }

/* ══ COMPANY FOOTER ══ */
.loc-co-foot {
    padding: 10px 24px; background: var(--bg);
    border: 1px solid var(--bd); border-top: none;
    border-radius: 0 0 var(--rlg) var(--rlg);
    display: flex; align-items: center; justify-content: space-between;
    font-size: 11px; font-weight: 600; color: var(--ink3);
}

/* ══ EMPTY ══ */
.loc-empty {
    background: var(--surf); border: 2px dashed var(--bd);
    border-radius: var(--rxl); padding: 72px 24px; text-align: center;
}
.loc-empty-icon {
    width: 70px; height: 70px; border-radius: 18px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: var(--ink3); margin: 0 auto 16px;
}
.loc-empty-t { font-size: 16px; font-weight: 800; color: var(--ink); margin-bottom: 6px; }
.loc-empty-s { font-size: 13px; font-weight: 500; color: var(--ink3); margin-bottom: 22px; }

/* ══ MODALS ══ */
.loc-modal-bg {
    display: none; position: fixed; inset: 0; z-index: 300;
    background: rgba(0,0,0,.5); backdrop-filter: blur(4px);
    align-items: center; justify-content: center; padding: 20px;
}
.loc-modal-bg.open { display: flex; }
.loc-modal {
    background: var(--surf); border-radius: var(--rxl);
    width: 100%; max-width: 460px;
    box-shadow: 0 8px 40px rgba(0,0,0,.2);
    animation: mIn .2s ease-out;
}
@keyframes mIn { from{opacity:0;transform:translateY(-14px)} to{opacity:1;transform:none} }

/* modal header with color strip */
.loc-modal-head {
    padding: 20px 24px 18px; border-bottom: 1px solid var(--bd2);
    position: relative; overflow: hidden;
}
.loc-modal-head::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
}
.loc-modal-head.blue::before { background: var(--blue); }
.loc-modal-head.grn::before  { background: var(--grn); }
.loc-modal-title { font-size: 14.5px; font-weight: 800; color: var(--ink); margin-bottom: 2px; padding-left: 10px; }
.loc-modal-sub   { font-size: 12px; font-weight: 500; color: var(--ink3); padding-left: 10px; }

.loc-modal-b { padding: 20px 24px; display: flex; flex-direction: column; gap: 14px; }
.loc-modal-lbl {
    display: block; font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .7px; margin-bottom: 6px;
}
.loc-modal-lbl .req { color: var(--red); margin-left: 2px; }
.loc-modal-input, .loc-modal-ro {
    padding: 9px 12px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s;
}
.loc-modal-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.loc-modal-ro  { background: var(--bg); color: var(--ink3); cursor: default; }
.loc-modal-hint { font-size: 11px; font-weight: 500; color: var(--ink3); margin-top: 5px; display: flex; align-items: center; gap: 4px; }
.loc-modal-hint i { color: var(--blue); font-size:10px; }

/* city rows inside state modal */
.loc-m-cities-hd {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px;
}
.loc-m-cities-lbl { font-size: 10px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .6px; }
.loc-m-add-city {
    font-size: 11px; font-weight: 700; color: var(--grn);
    background: none; border: none; cursor: pointer; font-family: 'Montserrat', sans-serif;
    display: flex; align-items: center; gap: 4px; transition: color .13s;
}
.loc-m-add-city:hover { color: #0a8559; }
.loc-m-city-list { display: flex; flex-direction: column; gap: 5px; max-height: 180px; overflow-y: auto; scrollbar-width: thin; }
.loc-m-city-row {
    display: flex; align-items: center; gap: 7px;
    padding: 6px 10px; border: 1px solid var(--bd2);
    border-radius: var(--r); background: var(--bg);
}
.loc-m-city-input {
    flex: 1; border: none; outline: none; background: transparent;
    font-size: 12.5px; font-weight: 500; font-family: 'Montserrat', sans-serif; color: var(--ink);
}
.loc-m-city-input::placeholder { color: var(--ink3); }
.loc-m-city-rm {
    width: 20px; height: 20px; border-radius: 5px; flex-shrink: 0;
    background: none; border: none; cursor: pointer; color: var(--ink3);
    display: flex; align-items: center; justify-content: center; font-size: 9px; transition: all .13s;
}
.loc-m-city-rm:hover { background: var(--rlt); color: var(--red); }
.loc-m-cities-hint { font-size: 10.5px; font-weight: 500; color: var(--ink3); margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.loc-m-cities-hint i { color: var(--blue); font-size: 9px; }

.loc-modal-foot {
    padding: 14px 24px; border-top: 1px solid var(--bd2);
    background: var(--bg); border-radius: 0 0 var(--rxl) var(--rxl);
    display: flex; justify-content: flex-end; gap: 8px;
}
.loc-mfbtn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
}
.loc-mfbtn i { font-size: 10px; }
.loc-mfbtn.blue  { background: var(--blue); color: #fff; }
.loc-mfbtn.blue:hover  { background: #1344c2; }
.loc-mfbtn.grn   { background: var(--grn); color: #fff; }
.loc-mfbtn.grn:hover   { background: #0a8559; }
.loc-mfbtn.ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.loc-mfbtn.ghost:hover { background: var(--bg); }

/* ══ SCROLLBAR ══ */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 1200px) { .loc-states-grid { grid-template-columns: repeat(2,1fr); } .loc-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 768px)  {
    .loc { padding: 16px; }
    .loc-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .loc-states-grid { grid-template-columns: 1fr; }
    .loc-stats { grid-template-columns: 1fr 1fr; }
    .loc-co-stats { display: none; }
}
</style>

@php
    $totalCompanies = $locations->count();
    $totalStates = 0; $totalBase = 0; $totalCities = 0;
    foreach($locations as $states) {
        $totalStates += $states->count();
        foreach($states as $locs) {
            $totalBase   += $locs->where('city', null)->count();
            $totalCities += $locs->whereNotNull('city')->count();
        }
    }
@endphp

<div class="loc">

    {{-- ══ HERO ══ --}}
    <div class="loc-hero">
        <div class="loc-hero-glow"></div>
        <div class="loc-hero-l">
            <div class="loc-hero-icon"><i class="fas fa-map-location-dot"></i></div>
            <div>
                <div class="loc-hero-title">Company Locations</div>
                <div class="loc-hero-sub">State base prices with city overrides</div>
            </div>
        </div>
        <div class="loc-hero-r">
            <div class="loc-chip"><div class="loc-chip-n">{{ $totalCompanies }}</div><div class="loc-chip-l">Companies</div></div>
            <div class="loc-chip"><div class="loc-chip-n">{{ $totalStates }}</div><div class="loc-chip-l">States</div></div>
            <a href="{{ route('superadmin.locations.create') }}" class="loc-add-btn">
                <i class="fas fa-plus" style="font-size:11px"></i> Add Location
            </a>
        </div>
    </div>

    {{-- ══ STATS ══ --}}
    <div class="loc-stats">
        <div class="loc-stat"><div class="loc-stat-icon blue"><i class="fas fa-building"></i></div><div><div class="loc-stat-val">{{ $totalCompanies }}</div><div class="loc-stat-lbl">Companies</div></div></div>
        <div class="loc-stat"><div class="loc-stat-icon nvy"><i class="fas fa-flag"></i></div><div><div class="loc-stat-val">{{ $totalStates }}</div><div class="loc-stat-lbl">States</div></div></div>
        <div class="loc-stat"><div class="loc-stat-icon grn"><i class="fas fa-layer-group"></i></div><div><div class="loc-stat-val">{{ $totalBase }}</div><div class="loc-stat-lbl">Base Prices</div></div></div>
        <div class="loc-stat"><div class="loc-stat-icon red"><i class="fas fa-city"></i></div><div><div class="loc-stat-val">{{ $totalCities }}</div><div class="loc-stat-lbl">City Overrides</div></div></div>
    </div>

    {{-- ══ SEARCH ══ --}}
    <div class="loc-search-bar">
        <i class="fas fa-search loc-search-ico"></i>
        <input type="text" id="loc-search" class="loc-search-input"
               placeholder="Search by company name or state code…"
               oninput="locSearch(this.value)">
        <span class="loc-search-count" id="loc-count" style="display:none"></span>
    </div>

    {{-- ══ COMPANIES ══ --}}
    @forelse($locations as $companyId => $states)
    @php
        $company = $states->first()->first()->user;
        $companyStates = $states->count();
        $companyCities = 0;
        $companyBase   = 0;
        foreach($states as $locs) {
            $companyCities += $locs->whereNotNull('city')->count();
            $companyBase   += $locs->where('city', null)->count();
        }
        $headClass = $companyId % 2 === 0 ? 'nvy' : 'red';
        $coName = $company->company_name ?? 'Unnamed Company';
    @endphp

    <div class="loc-company"
         data-search="{{ strtolower($coName . ' ' . $states->keys()->implode(' ')) }}">

        {{-- Enhanced company header ── --}}
        <div class="loc-co-head {{ $headClass }}">
            <div class="loc-co-head-bg"></div>
            <div class="loc-co-head-glow"></div>
            <div class="loc-co-head-inner">
                <div style="display:flex;align-items:center;gap:16px;flex:1;min-width:0">
                    <div class="loc-co-av-wrap">
                        <div class="loc-co-av">{{ strtoupper(substr($coName,0,1)) }}</div>
                        <span class="loc-co-dot"></span>
                    </div>
                    <div class="loc-co-info">
                        <div class="loc-co-name">{{ $coName }}</div>
                        <div class="loc-co-meta">
                            @if($company->email)
                            <span class="loc-co-email"><i class="fas fa-envelope" style="font-size:10px;margin-right:4px"></i>{{ $company->email }}</span>
                            @endif
                            <span class="loc-co-pill"><i class="fas fa-flag" style="font-size:9px;margin-right:4px"></i>{{ $companyStates }} states</span>
                            <span class="loc-co-pill"><i class="fas fa-city" style="font-size:9px;margin-right:4px"></i>{{ $companyCities }} cities</span>
                        </div>
                    </div>
                </div>

                {{-- mini stat chips ── --}}
                <div class="loc-co-stats">
                    <div class="loc-co-stat">
                        <span class="loc-co-stat-n">{{ $companyStates }}</span>
                        <span class="loc-co-stat-l">States</span>
                    </div>
                    <div class="loc-co-stat">
                        <span class="loc-co-stat-n">{{ $companyBase }}</span>
                        <span class="loc-co-stat-l">Base</span>
                    </div>
                    <div class="loc-co-stat">
                        <span class="loc-co-stat-n">{{ $companyCities }}</span>
                        <span class="loc-co-stat-l">Cities</span>
                    </div>
                </div>

                <button type="button" class="loc-add-state-btn"
                        onclick="locOpenState({{ $company->id }}, '{{ addslashes($coName) }}')">
                    <i class="fas fa-plus" style="font-size:10px"></i> Add State
                </button>
            </div>
        </div>

        {{-- States grid ── --}}
        <div class="loc-states-grid">
            @foreach($states as $state => $locationsByState)
            @php
                $baseLocation = $locationsByState->where('city', null)->first();
                $cities       = $locationsByState->whereNotNull('city');
                $cityCount    = $cities->count();
                $shownCities  = $cities->take(4);
                $moreCities   = $cityCount - 4;
            @endphp
            <div class="loc-state-card {{ !$baseLocation ? 'no-base' : '' }}">
                <div class="top-bar"></div>

                <div class="loc-state-head">
                    <div class="loc-state-head-l">
                        <div class="loc-state-icon">{{ $state }}</div>
                        <div>
                            <div class="loc-state-name">{{ $state }}</div>
                            <div class="loc-state-meta">
                                @if($baseLocation)
                                    <span class="loc-state-badge base"><i class="fas fa-layer-group" style="font-size:8px;margin-right:2px"></i>Base</span>
                                @else
                                    <span class="loc-state-badge nobase"><i class="fas fa-exclamation-circle" style="font-size:8px;margin-right:2px"></i>No base</span>
                                @endif
                                @if($cityCount > 0)
                                    <span class="loc-state-badge city"><i class="fas fa-city" style="font-size:8px;margin-right:2px"></i>{{ $cityCount }} cit.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="button" class="loc-add-city-btn" title="Add city override"
                            onclick="locOpenCity('{{ $state }}', {{ $company->id }})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <div class="loc-state-body">

                    {{-- Base price ── --}}
                    @if($baseLocation)
                    <div class="loc-base-row">
                        <div class="loc-base-row-l">
                            <div class="loc-base-icon"><i class="fas fa-layer-group"></i></div>
                            <div>
                                <div class="loc-base-label">Base Price</div>
                                <div class="loc-base-sub">All cities in {{ $state }}</div>
                            </div>
                        </div>
                        <div class="loc-row-acts">
                            <a href="{{ route('superadmin.locations.prices.index', $baseLocation) }}" class="loc-row-act price" title="Manage prices"><i class="fas fa-dollar-sign"></i></a>
                            <a href="{{ route('superadmin.locations.edit', $baseLocation) }}" class="loc-row-act edit" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('superadmin.locations.destroy', $baseLocation) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="button" class="loc-row-act del" title="Delete"
                                        onclick="locDel(this.closest('form'),'base price in {{ $state }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="loc-no-base">
                        <i class="fas fa-exclamation-triangle" style="font-size:11px"></i>
                        No base price set
                    </div>
                    @endif

                    {{-- Cities ── --}}
                    <div class="loc-cities-section">
                        <div class="loc-cities-label">
                            <span>City Overrides</span>
                            <span>{{ $cityCount }}</span>
                        </div>
                        <div class="loc-cities-scroll">
                            @foreach($shownCities as $location)
                            <div class="loc-city-row">
                                <div class="loc-city-l">
                                    <div class="loc-city-icon"><i class="fas fa-city"></i></div>
                                    <span class="loc-city-name" title="{{ $location->city }}">{{ $location->city }}</span>
                                </div>
                                <div class="loc-row-acts">
                                    <a href="{{ route('superadmin.locations.prices.index', $location) }}" class="loc-row-act price" title="Prices"><i class="fas fa-dollar-sign"></i></a>
                                    <a href="{{ route('superadmin.locations.edit', $location) }}" class="loc-row-act edit" title="Edit"><i class="fas fa-pen"></i></a>
                                    <form method="POST" action="{{ route('superadmin.locations.destroy', $location) }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="loc-row-act del" title="Delete"
                                                onclick="locDel(this.closest('form'),'{{ addslashes($location->city) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach

                            @if($moreCities > 0)
                            <div class="loc-more">+{{ $moreCities }} more {{ $moreCities == 1 ? 'city' : 'cities' }}</div>
                            @endif
                            @if($cityCount === 0)
                            <div class="loc-city-no"><i class="fas fa-city" style="opacity:.25;margin-right:5px"></i>No city overrides yet</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        <div class="loc-co-foot">
            <span>ID: {{ $companyId }} &nbsp;·&nbsp; {{ $companyStates }} states &nbsp;·&nbsp; {{ $companyCities }} city overrides</span>
            <a href="{{ route('superadmin.locations.create') }}" style="font-size:11px;font-weight:700;color:var(--blue);text-decoration:none">
                <i class="fas fa-plus" style="font-size:9px;margin-right:3px"></i> Add more locations
            </a>
        </div>
    </div>

    @empty
    <div class="loc-empty">
        <div class="loc-empty-icon"><i class="fas fa-map-location-dot"></i></div>
        <div class="loc-empty-t">No locations configured</div>
        <div class="loc-empty-s">Start by adding locations for your companies. Each company can have state-wide base prices and city-specific overrides.</div>
        <a href="{{ route('superadmin.locations.create') }}" class="loc-add-btn">
            <i class="fas fa-plus" style="font-size:11px"></i> Add First Location
        </a>
    </div>
    @endforelse

</div>

{{-- ══ MODAL: ADD STATE + CITIES ══ --}}
<div class="loc-modal-bg" id="modal-state">
    <div class="loc-modal">
        <div class="loc-modal-head blue">
            <div class="loc-modal-title" id="modal-state-title">Add State</div>
            <div class="loc-modal-sub" id="modal-state-sub">Creates a base price + optional cities</div>
        </div>
        <form method="POST" action="{{ route('superadmin.locations.store') }}" id="form-state">
            @csrf
            <input type="hidden" name="user_id" id="state-uid">
            {{-- base price always included (city = "") --}}
            <input type="hidden" name="locations[0][city]" value="">
            <div class="loc-modal-b">
                <div>
                    <label class="loc-modal-lbl">State Code <span class="req">*</span></label>
                    <input type="text" name="locations[0][state]" id="state-code"
                           class="loc-modal-input" placeholder="FL, TX, CA…"
                           maxlength="5" required
                           oninput="this.value=this.value.toUpperCase(); locSyncState()">
                    <div class="loc-modal-hint"><i class="fas fa-info-circle"></i> A state-wide base price will be created automatically.</div>
                </div>

                {{-- divider ── --}}
                <div style="border-top:1px solid var(--bd2);margin:0 -4px"></div>

                {{-- cities ── --}}
                <div>
                    <div class="loc-m-cities-hd">
                        <span class="loc-m-cities-lbl">City Overrides <span style="color:var(--ink3);text-transform:none;letter-spacing:0;font-weight:500">(optional)</span></span>
                        <button type="button" class="loc-m-add-city" onclick="locMAddCity()">
                            <i class="fas fa-plus" style="font-size:9px"></i> Add City
                        </button>
                    </div>
                    <div class="loc-m-city-list" id="m-city-list"></div>
                    <div class="loc-m-cities-hint" id="m-cities-hint" style="display:none">
                        <i class="fas fa-circle-dot"></i> Each city will be saved with the state code above
                    </div>
                </div>
            </div>
            <div class="loc-modal-foot">
                <button type="button" class="loc-mfbtn ghost" onclick="locCloseModals()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="loc-mfbtn blue" id="m-state-submit">
                    <i class="fas fa-floppy-disk"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: ADD CITY (single) ══ --}}
<div class="loc-modal-bg" id="modal-city">
    <div class="loc-modal">
        <div class="loc-modal-head grn">
            <div class="loc-modal-title" id="modal-city-title">Add City Override</div>
            <div class="loc-modal-sub" id="modal-city-sub">City-specific prices override the state base</div>
        </div>
        <form method="POST" action="{{ route('superadmin.locations.store') }}" id="form-city">
            @csrf
            <input type="hidden" name="user_id" id="city-uid">
            <input type="hidden" name="locations[0][state]" id="city-state-h">
            <div class="loc-modal-b">
                <div>
                    <label class="loc-modal-lbl">State</label>
                    <input type="text" id="city-state-d" class="loc-modal-ro" readonly>
                </div>
                <div>
                    <label class="loc-modal-lbl">City Name <span class="req">*</span></label>
                    <input type="text" name="locations[0][city]" id="city-input"
                           class="loc-modal-input" placeholder="Miami, Orlando, Houston…" required>
                    <div class="loc-modal-hint"><i class="fas fa-info-circle"></i> Overrides the state base price for this city only.</div>
                </div>
            </div>
            <div class="loc-modal-foot">
                <button type="button" class="loc-mfbtn ghost" onclick="locCloseModals()"><i class="fas fa-times"></i> Cancel</button>
                <button type="submit" class="loc-mfbtn grn"><i class="fas fa-floppy-disk"></i> Add City</button>
            </div>
        </form>
    </div>
</div>

<script>
/* ── SEARCH ── */
function locSearch(q) {
    const val    = q.trim().toLowerCase();
    const blocks = document.querySelectorAll('.loc-company');
    const cnt    = document.getElementById('loc-count');
    let shown    = 0;

    blocks.forEach(b => {
        const match = !val || b.dataset.search.includes(val);
        b.style.display = match ? '' : 'none';
        if (match) shown++;
    });

    if (val) {
        cnt.textContent = shown + ' result' + (shown !== 1 ? 's' : '');
        cnt.style.display = 'inline-flex';
    } else {
        cnt.style.display = 'none';
    }
}

/* ── MODAL: ADD STATE ── */
let mCityIdx = 1; // 0 is the base price entry

function locOpenState(uid, name) {
    document.getElementById('state-uid').value = uid;
    document.getElementById('state-code').value = '';
    document.getElementById('modal-state-title').textContent = `Add State — ${name}`;
    document.getElementById('modal-state-sub').textContent   = `Base price created automatically + optional cities`;
    document.getElementById('m-city-list').innerHTML = '';
    document.getElementById('m-cities-hint').style.display = 'none';
    mCityIdx = 1;
    document.getElementById('modal-state').classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('state-code').focus(), 100);
}

/* sync state code into city hidden fields */
function locSyncState() {
    const code = document.getElementById('state-code').value.toUpperCase();
    document.querySelectorAll('[data-city-state]').forEach(el => el.value = code);
}

/* add city row inside state modal */
function locMAddCity() {
    const list = document.getElementById('m-city-list');
    const rowId = 'mr-' + Date.now();
    const div   = document.createElement('div');
    div.className = 'loc-m-city-row';
    div.id = rowId;
    div.innerHTML = `
        <i class="fas fa-city" style="font-size:11px;color:var(--red);flex-shrink:0"></i>
        <input type="hidden" name="locations[${mCityIdx}][state]" data-city-state
               value="${document.getElementById('state-code').value}">
        <input type="text"   name="locations[${mCityIdx}][city]"
               class="loc-m-city-input" placeholder="City name…" required>
        <button type="button" class="loc-m-city-rm" onclick="locMRemCity('${rowId}')">
            <i class="fas fa-times"></i>
        </button>`;
    list.appendChild(div);
    div.querySelector('input[type="text"]').focus();
    mCityIdx++;
    document.getElementById('m-cities-hint').style.display = list.children.length ? 'flex' : 'none';
}

function locMRemCity(id) {
    document.getElementById(id)?.remove();
    const list = document.getElementById('m-city-list');
    document.getElementById('m-cities-hint').style.display = list.children.length ? 'flex' : 'none';
}

/* ── MODAL: ADD CITY ── */
function locOpenCity(state, uid) {
    document.getElementById('city-uid').value = uid;
    document.getElementById('city-state-h').value = state;
    document.getElementById('city-state-d').value = state;
    document.getElementById('city-input').value = '';
    document.getElementById('modal-city-title').textContent = `Add City — ${state}`;
    document.getElementById('modal-city-sub').textContent   = `Overrides the ${state} base price for this city`;
    document.getElementById('modal-city').classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('city-input').focus(), 100);
}

/* ── CLOSE ── */
function locCloseModals() {
    document.getElementById('modal-state').classList.remove('open');
    document.getElementById('modal-city').classList.remove('open');
    document.body.style.overflow = '';
}
['modal-state','modal-city'].forEach(id => {
    document.getElementById(id).addEventListener('click', e => { if (e.target.id === id) locCloseModals(); });
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') locCloseModals(); });

/* ── DELETE ── */
function locDel(form, label) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete location?',
            html: `<p style="font-family:Montserrat,sans-serif;font-size:14px;color:#374151;line-height:1.6">
                     Delete <strong>${label}</strong>? This cannot be undone.
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) form.submit(); });
    } else {
        if (confirm(`Delete ${label}?`)) form.submit();
    }
}
</script>

@endsection