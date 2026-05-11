@extends('admin.layouts.superadmin')
@section('title', 'Crew Management')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.cm { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1540px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --pur:  #7c22e8; --plt:  #f5f0ff; --pbd:  #ddd0fb;
    --ind:  #3730a3; --ilt:  #eef2ff; --ibd:  #c7d4fb;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.cm-hero {
    position: relative; border-radius: var(--rxl);
    padding: 34px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.cm-hero-glow {
    position: absolute; pointer-events: none;
    width: 600px; height: 300px;
    background: radial-gradient(ellipse, rgba(24,85,224,.35) 0%, transparent 70%);
    right: -60px; top: -60px;
}
.cm-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#4f80ff 0%,var(--blue) 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.cm-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.cm-hero-left { position: relative; display: flex; align-items: center; gap: 18px; }
.cm-hero-badge {
    width: 54px; height: 54px; border-radius: 14px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #8aadff;
}
.cm-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.cm-hero-sub   { font-size: 12.5px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.cm-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }
.cm-stat-chip {
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px; padding: 12px 18px; text-align: center;
}
.cm-stat-chip-n { font-size: 22px; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -.5px; }
.cm-stat-chip-l { font-size: 10px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }
.cm-new-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: var(--r);
    background: var(--blue); color: #fff;
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: none; cursor: pointer; text-decoration: none; transition: background .13s;
    white-space: nowrap;
}
.cm-new-btn:hover { background: #1344c2; color: #fff; }
.cm-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.cm-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── STATS STRIP ── */
.cm-stats { display: grid; grid-template-columns: repeat(5,1fr); gap: 12px; margin-bottom: 22px; }
.cm-stat-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 16px 18px;
    display: flex; align-items: center; gap: 12px;
    transition: box-shadow .15s; position: relative; overflow: hidden;
}
.cm-stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }
.cm-stat-card-bar { position: absolute; bottom: 0; left: 0; right: 0; height: 2px; }
.cm-stat-card-bar.blue { background: var(--blue); }
.cm-stat-card-bar.grn  { background: var(--grn); }
.cm-stat-card-bar.emr  { background: #059669; }
.cm-stat-card-bar.amb  { background: var(--amb); }
.cm-stat-card-bar.ind  { background: #4338ca; }
.cm-stat-icon {
    width: 40px; height: 40px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 15px;
}
.cm-stat-icon.blue { background: var(--blt); color: var(--blue); }
.cm-stat-icon.grn  { background: var(--glt); color: var(--grn); }
.cm-stat-icon.emr  { background: #ecfdf5; color: #059669; }
.cm-stat-icon.amb  { background: var(--alt); color: var(--amb); }
.cm-stat-icon.ind  { background: #eef2ff; color: #4338ca; }
.cm-stat-num { font-size: 26px; font-weight: 800; color: var(--ink); letter-spacing: -.5px; line-height: 1; }
.cm-stat-lbl { font-size: 10.5px; font-weight: 700; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }

/* ── FILTER CARD ── */
.cm-filter {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); margin-bottom: 22px; overflow: hidden;
}
.cm-filter-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; cursor: pointer; user-select: none;
}
.cm-filter-head-l { display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; color: var(--ink2); text-transform: uppercase; letter-spacing: .5px; }
.cm-filter-head-l i { color: var(--ink3); }
.cm-filter-arr { color: var(--ink3); font-size: 10px; transition: transform .2s; }
.cm-filter-arr.open { transform: rotate(180deg); }
.cm-active-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); margin-left: 2px; display: inline-block; }
.cm-filter-body { padding: 18px 20px; border-top: 1px solid var(--bd2); }
.cm-fg { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 12px; align-items: end; }
.cm-label { font-size: 11px; font-weight: 700; color: var(--ink3); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px; display: block; }
.cm-input, .cm-sel {
    width: 100%; padding: 9px 12px;
    border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none;
    transition: border-color .15s, box-shadow .15s; appearance: none;
}
.cm-input:focus, .cm-sel:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.cm-iw { position: relative; }
.cm-ii { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--ink3); font-size: 12px; pointer-events: none; }
.cm-input.pi { padding-left: 32px; }
.cm-sw { position: relative; }
.cm-sa { position: absolute; right: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--ink3); font-size: 10px; }
.cm-fa { display: flex; gap: 8px; }
.cm-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.cm-btn i { font-size: 11px; }
.cm-btn-blue  { background: var(--blue); color: #fff; }
.cm-btn-blue:hover  { background: #1344c2; color: #fff; }
.cm-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.cm-btn-ghost:hover { background: var(--bg); color: var(--ink); }
/* multi-select */
select[multiple].cm-sel { height: 100px; padding: 6px 8px; }
select[multiple].cm-sel option { padding: 4px 6px; border-radius: 4px; }

/* ── CREW GRID ── */
.cm-grid {
    display: grid; grid-template-columns: repeat(2,1fr); gap: 16px;
    padding: 20px;
}
.cm-card-wrap {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
    transition: box-shadow .15s, border-color .15s, transform .15s;
}
.cm-card-wrap:hover {
    border-color: var(--bbd); box-shadow: 0 6px 24px rgba(0,0,0,.08);
    transform: translateY(-1px);
}

/* ── CARD HEAD ── */
.cm-card-head {
    padding: 18px 20px; border-bottom: 1px solid var(--bd2);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
}
.cm-card-av-wrap { position: relative; flex-shrink: 0; }
.cm-card-av {
    width: 50px; height: 50px; border-radius: 14px;
    background: linear-gradient(135deg,var(--blue),#5b8af7);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: #fff; letter-spacing: -.3px;
}
.cm-card-av-dot {
    position: absolute; bottom: -2px; right: -2px;
    width: 14px; height: 14px; border-radius: 50%;
    border: 2px solid var(--surf);
}
.cm-card-av-dot.on  { background: var(--grn); }
.cm-card-av-dot.off { background: var(--red); }
.cm-card-name  { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.cm-card-co    { font-size: 12px; font-weight: 500; color: var(--ink3); margin-top: 2px; display: flex; align-items: center; gap: 5px; }
.cm-card-pills { display: flex; flex-wrap: wrap; gap: 5px; }
.cm-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 800; padding: 3px 9px;
    border-radius: 9999px; text-transform: uppercase; letter-spacing: .4px;
}
.cm-pill.assigned { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.cm-pill.avail    { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.cm-pill.trailer  { background: #eef2ff; color: #4338ca; border: 1px solid #c7d4fb; }
.cm-pill.no-trail { background: var(--bg); color: var(--ink3); border: 1px solid var(--bd); }
.cm-pill.active   { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.cm-pill.inactive { background: var(--rlt); color: var(--red); border: 1px solid var(--rbd); }

/* ── CARD BODY ── */
.cm-card-body { padding: 16px 20px; }
.cm-card-row {
    display: flex; align-items: center; gap: 10px;
    font-size: 12.5px; font-weight: 500; color: var(--ink2); margin-bottom: 8px;
}
.cm-card-row:last-child { margin-bottom: 0; }
.cm-card-row-icon {
    width: 28px; height: 28px; border-radius: 7px; flex-shrink: 0;
    background: var(--bg); display: flex; align-items: center; justify-content: center;
    font-size: 11px; color: var(--ink3);
}
.cm-card-row a { color: var(--ink2); text-decoration: none; }
.cm-card-row a:hover { color: var(--blue); }

/* states */
.cm-states-wrap { margin-top: 12px; }
.cm-states-title { font-size: 10.5px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 7px; }
.cm-states-pills { display: flex; flex-wrap: wrap; gap: 4px; }
.cm-state-tag {
    font-size: 10px; font-weight: 700; padding: 2px 7px;
    border-radius: 5px; background: var(--blt); color: var(--blue);
    border: 1px solid var(--bbd);
}
.cm-state-more {
    font-size: 10px; font-weight: 700; padding: 2px 7px;
    border-radius: 5px; background: var(--bg); color: var(--ink3);
    border: 1px solid var(--bd); cursor: default;
}

/* managers */
.cm-managers { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--bd2); }
.cm-managers-title { font-size: 10.5px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; }
.cm-manager-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: var(--r);
    border: 1px solid var(--bd2); background: var(--bg); margin-bottom: 5px;
}
.cm-manager-row:last-child { margin-bottom: 0; }
.cm-manager-av {
    width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
    background: linear-gradient(135deg,var(--amb),#fbbf24);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: #fff;
}
.cm-manager-name { font-size: 12px; font-weight: 700; color: var(--ink); }
.cm-manager-co   { font-size: 11px; font-weight: 500; color: var(--ink3); }

/* ── CARD FOOTER ── */
.cm-card-foot {
    padding: 13px 20px; background: #fafbfd;
    border-top: 1px solid var(--bd2);
    display: flex; align-items: center; justify-content: space-between; gap: 10px;
}
.cm-foot-left  { display: flex; align-items: center; gap: 6px; }
.cm-foot-right { display: flex; align-items: center; gap: 5px; }
.cm-foot-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 12px; border-radius: var(--r);
    font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s; text-decoration: none;
}
.cm-foot-btn i { font-size: 10px; }
.cm-foot-btn.assign { background: var(--blt); color: var(--blue); border-color: var(--bbd); }
.cm-foot-btn.assign:hover { background: var(--blue); color: #fff; }
.cm-foot-btn.edit   { background: var(--bg); color: var(--ink2); border-color: var(--bd); }
.cm-foot-btn.edit:hover { background: var(--surf); color: var(--ink); }
.cm-foot-btn.del    { background: var(--rlt); color: var(--red); border-color: var(--rbd); }
.cm-foot-btn.del:hover  { background: var(--red); color: #fff; }

/* ── EMPTY ── */
.cm-empty { text-align: center; padding: 72px 24px; }
.cm-empty-icon {
    width: 72px; height: 72px; border-radius: 18px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--ink3); margin: 0 auto 18px;
}
.cm-empty-t { font-size: 16px; font-weight: 800; color: var(--ink); margin-bottom: 6px; }
.cm-empty-s { font-size: 13px; font-weight: 500; color: var(--ink3); margin-bottom: 22px; }

/* ── PAGINATION ── */
.cm-pag {
    padding: 14px 22px 16px; border-top: 1px solid var(--bd2); background: #fafbfd;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
}
.cm-pag-info { font-size: 12px; font-weight: 600; color: var(--ink3); }

/* ── TABLE CARD SHELL ── */
.cm-table-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
}
.cm-table-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.cm-table-title { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.cm-table-count {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 9999px; background: var(--blt);
    color: var(--blue); border: 1px solid var(--bbd);
}

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 1200px) { .cm-stats { grid-template-columns: repeat(3,1fr); } .cm-grid { grid-template-columns: 1fr; } }
@media (max-width: 900px)  { .cm-fg { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px)  {
    .cm { padding: 16px; }
    .cm-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .cm-stats { grid-template-columns: repeat(2,1fr); }
    .cm-fg { grid-template-columns: 1fr; }
}
</style>

<div class="cm">

    {{-- ── HERO ── --}}
    <div class="cm-hero">
        <div class="cm-hero-glow"></div>
        <div class="cm-hero-accent"></div>
        <div class="cm-hero-grid"></div>

        <div class="cm-hero-left">
            <div class="cm-hero-badge"><i class="fas fa-users"></i></div>
            <div>
                <div class="cm-hero-title">Crew Management</div>
                <div class="cm-hero-sub">Manage your work teams and assignments</div>
            </div>
        </div>

        <div class="cm-hero-right">
            <div class="cm-stat-chip">
                <div class="cm-stat-chip-n">{{ $crews->count() }}</div>
                <div class="cm-stat-chip-l">Total</div>
            </div>
            <div class="cm-stat-chip">
                <div class="cm-stat-chip-n">{{ $crews->where('is_active', true)->count() }}</div>
                <div class="cm-stat-chip-l">Active</div>
            </div>
            <a href="{{ route('superadmin.crew.create') }}" class="cm-new-btn">
                <i class="fas fa-plus" style="font-size:11px"></i> New Crew
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="cm-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- ── STATS STRIP ── --}}
    <div class="cm-stats">
        <div class="cm-stat-card">
            <div class="cm-stat-icon blue"><i class="fas fa-users"></i></div>
            <div>
                <div class="cm-stat-num">{{ $crews->count() }}</div>
                <div class="cm-stat-lbl">Total Crews</div>
            </div>
            <div class="cm-stat-card-bar blue"></div>
        </div>
        <div class="cm-stat-card">
            <div class="cm-stat-icon grn"><i class="fas fa-user-check"></i></div>
            <div>
                <div class="cm-stat-num">{{ $crews->filter(fn($c) => $c->subcontractors->isNotEmpty())->count() }}</div>
                <div class="cm-stat-lbl">Assigned</div>
            </div>
            <div class="cm-stat-card-bar grn"></div>
        </div>
        <div class="cm-stat-card">
            <div class="cm-stat-icon emr"><i class="fas fa-bolt"></i></div>
            <div>
                <div class="cm-stat-num">{{ $crews->where('is_active', true)->count() }}</div>
                <div class="cm-stat-lbl">Active</div>
            </div>
            <div class="cm-stat-card-bar emr"></div>
        </div>
        <div class="cm-stat-card">
            <div class="cm-stat-icon amb"><i class="fas fa-clock"></i></div>
            <div>
                <div class="cm-stat-num">{{ $crews->filter(fn($c) => $c->subcontractors->isEmpty())->count() }}</div>
                <div class="cm-stat-lbl">Available</div>
            </div>
            <div class="cm-stat-card-bar amb"></div>
        </div>
        <div class="cm-stat-card">
            <div class="cm-stat-icon ind"><i class="fas fa-truck-moving"></i></div>
            <div>
                <div class="cm-stat-num">{{ $crews->where('has_trailer', true)->count() }}</div>
                <div class="cm-stat-lbl">With Trailer</div>
            </div>
            <div class="cm-stat-card-bar ind"></div>
        </div>
    </div>

    {{-- ── FILTER CARD ── --}}
    <div class="cm-filter">
        <div class="cm-filter-head" onclick="toggleF()">
            <div class="cm-filter-head-l">
                <i class="fas fa-sliders-h"></i>
                Search & Filters
                @if(request('search') || request('status') || request('trailer') || request('states'))
                    <span class="cm-active-dot"></span>
                @endif
            </div>
            <i class="fas fa-chevron-down cm-filter-arr {{ request('search')||request('status')||request('trailer')||request('states') ? 'open' : '' }}" id="farr"></i>
        </div>
        <div id="fbody" style="{{ request('search')||request('status')||request('trailer')||request('states') ? '' : 'display:none' }}" class="cm-filter-body">
            <form method="GET" action="{{ route('superadmin.crew.index') }}" id="cm-form">
                <div class="cm-fg">

                    <div>
                        <label class="cm-label">Search</label>
                        <div class="cm-iw">
                            <i class="fas fa-search cm-ii"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="cm-input pi" placeholder="Name, company, specialty…">
                        </div>
                    </div>

                    <div>
                        <label class="cm-label">Status</label>
                        <div class="cm-sw">
                            <select name="status" class="cm-sel">
                                <option value="">All status</option>
                                <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <i class="fas fa-chevron-down cm-sa"></i>
                        </div>
                    </div>

                    <div>
                        <label class="cm-label">Trailer</label>
                        <div class="cm-sw">
                            <select name="trailer" class="cm-sel">
                                <option value="">All</option>
                                <option value="1" {{ request('trailer') == '1' ? 'selected' : '' }}>Has trailer</option>
                                <option value="0" {{ request('trailer') == '0' ? 'selected' : '' }}>No trailer</option>
                            </select>
                            <i class="fas fa-chevron-down cm-sa"></i>
                        </div>
                    </div>

                    <div>
                        <label class="cm-label">States</label>
                        <select name="states[]" multiple class="cm-sel"
                                style="height:90px;padding:6px 8px">
                            @php
                                $statesList = ['AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'];
                                $selStates = request()->get('states', []);
                            @endphp
                            @foreach($statesList as $code => $name)
                            <option value="{{ $code }}" {{ in_array($code, $selStates) ? 'selected' : '' }}>
                                {{ $name }} ({{ $code }})
                            </option>
                            @endforeach
                        </select>
                        <span style="font-size:10.5px;font-weight:500;color:var(--ink3);margin-top:4px;display:block">Ctrl/Cmd + click for multiple</span>
                    </div>

                    <div class="cm-fa" style="align-self:flex-end">
                        <button type="submit" class="cm-btn cm-btn-blue">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button type="button" onclick="cmReset()" class="cm-btn cm-btn-ghost">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── CREW LIST ── --}}
    <div class="cm-table-card">
        <div class="cm-table-head">
            <div style="display:flex;align-items:center;gap:10px">
                <span class="cm-table-title">Crews</span>
                <span class="cm-table-count">{{ $crews->count() }} {{ Str::plural('crew', $crews->count()) }}</span>
            </div>
        </div>

        @if($crews->isEmpty())
        <div class="cm-empty">
            <div class="cm-empty-icon"><i class="fas fa-users"></i></div>
            <div class="cm-empty-t">No crews found</div>
            <div class="cm-empty-s">
                @if(request('search')||request('status')||request('trailer')||request('states'))
                    Try adjusting your filters.
                @else
                    Start building your team by adding the first crew.
                @endif
            </div>
            @if(!request('search') && !request('status') && !request('trailer') && !request('states'))
            <a href="{{ route('superadmin.crew.create') }}" class="cm-new-btn" style="margin:0 auto">
                <i class="fas fa-plus" style="font-size:11px"></i> Create First Crew
            </a>
            @endif
        </div>

        @else
        <div class="cm-grid">
            @foreach($crews as $crew)
            <div class="cm-card-wrap">

                {{-- HEAD ── --}}
                <div class="cm-card-head">
                    <div style="display:flex;align-items:flex-start;gap:14px;flex:1;min-width:0">
                        <div class="cm-card-av-wrap">
                            <div class="cm-card-av">{{ strtoupper(substr($crew->name,0,1)) }}</div>
                            <div class="cm-card-av-dot {{ $crew->is_active ? 'on' : 'off' }}"></div>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="cm-card-name">{{ $crew->name }}</div>
                            <div class="cm-card-co">
                                <i class="fas fa-building" style="font-size:10px"></i>
                                {{ $crew->company }}
                            </div>
                        </div>
                    </div>
                    <div class="cm-card-pills" style="flex-shrink:0">
                        @if($crew->subcontractors->isNotEmpty())
                            <span class="cm-pill assigned"><i class="fas fa-user-check" style="font-size:8px"></i> Assigned</span>
                        @else
                            <span class="cm-pill avail"><i class="fas fa-clock" style="font-size:8px"></i> Available</span>
                        @endif
                        @if($crew->has_trailer)
                            <span class="cm-pill trailer"><i class="fas fa-truck-moving" style="font-size:8px"></i> Trailer</span>
                        @else
                            <span class="cm-pill no-trail"><i class="fas fa-truck" style="font-size:8px"></i> No Trailer</span>
                        @endif
                    </div>
                </div>

                {{-- BODY ── --}}
                <div class="cm-card-body">
                    @if($crew->email)
                    <div class="cm-card-row">
                        <div class="cm-card-row-icon"><i class="fas fa-envelope"></i></div>
                        <a href="mailto:{{ $crew->email }}">{{ $crew->email }}</a>
                    </div>
                    @endif
                    @if($crew->phone)
                    <div class="cm-card-row">
                        <div class="cm-card-row-icon"><i class="fas fa-phone"></i></div>
                        <a href="tel:{{ $crew->phone }}">{{ $crew->phone }}</a>
                    </div>
                    @else
                    <div class="cm-card-row">
                        <div class="cm-card-row-icon"><i class="fas fa-phone"></i></div>
                        <span style="color:var(--ink3);font-style:italic;font-size:12px">Not specified</span>
                    </div>
                    @endif

                    {{-- States ── --}}
                    @if($crew->states && is_array($crew->states) && count($crew->states))
                    <div class="cm-states-wrap">
                        <div class="cm-states-title">Operating States</div>
                        <div class="cm-states-pills">
                            @foreach(array_slice($crew->states, 0, 5) as $st)
                                <span class="cm-state-tag">{{ $st }}</span>
                            @endforeach
                            @if(count($crew->states) > 5)
                                <span class="cm-state-more" title="{{ implode(', ', array_slice($crew->states, 5)) }}">
                                    +{{ count($crew->states) - 5 }} more
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Managers ── --}}
                    @if($crew->subcontractors->isNotEmpty())
                    <div class="cm-managers">
                        <div class="cm-managers-title">Assigned Managers</div>
                        @foreach($crew->subcontractors as $sub)
                        <div class="cm-manager-row">
                            <div class="cm-manager-av">{{ strtoupper(substr($sub->name,0,1)) }}</div>
                            <div>
                                <div class="cm-manager-name">{{ $sub->name }} {{ $sub->last_name }}</div>
                                <div class="cm-manager-co">{{ $sub->company_name }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- FOOTER ── --}}
                <div class="cm-card-foot">
                    <div class="cm-foot-left">
                        <span class="cm-pill {{ $crew->is_active ? 'active' : 'inactive' }}">
                            <i class="fas fa-{{ $crew->is_active ? 'check-circle' : 'times-circle' }}" style="font-size:8px"></i>
                            {{ $crew->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="cm-foot-right">
                        <a href="{{ route('superadmin.crew.assign', $crew->id) }}" class="cm-foot-btn assign">
                            <i class="fas fa-user-plus"></i> Assign
                        </a>
                        <a href="{{ route('superadmin.crew.edit', $crew->id) }}" class="cm-foot-btn edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="{{ route('superadmin.crew.destroy', $crew->id) }}"
                              method="POST" style="display:inline" id="dfc{{ $crew->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="cm-foot-btn del"
                                    onclick="cmDel({{ $crew->id }}, '{{ addslashes($crew->name) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        @if($crews->hasPages())
        <div class="cm-pag">
            <span class="cm-pag-info">
                Showing {{ $crews->firstItem() }}–{{ $crews->lastItem() }} of {{ $crews->total() }} crews
            </span>
            {{ $crews->links('vendor.pagination.tailwind') }}
        </div>
        @endif
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

function cmReset() {
    document.getElementById('cm-form').reset();
    window.location.href = '{{ route("superadmin.crew.index") }}';
}

function cmDel(id, name) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete crew?',
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px">
                     You are about to permanently delete<br><strong>${name}</strong>.
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) document.getElementById('dfc'+id).submit(); });
    } else {
        if (confirm('Delete ' + name + '?')) document.getElementById('dfc'+id).submit();
    }
}
</script>

@endsection