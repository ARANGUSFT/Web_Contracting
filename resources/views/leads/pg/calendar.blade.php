@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg:#f6f7f9;--surf:#ffffff;--surf-2:#fafbfc;
    --bd:rgba(15,23,42,.06);--bd-md:rgba(15,23,42,.10);--bd-strong:rgba(15,23,42,.16);
    --tx:#0d0f12;--tx2:#475569;--tx3:#94a3b8;--tx4:#cbd5e1;
    --blue:#2563eb;--blue-bg:#eff4ff;--blue-bd:#bfcffe;--blue-strong:#1d4ed8;
    --green:#16a34a;--green-bg:#f0fdf4;--green-bd:#bbf7d0;
    --amber:#d97706;--amber-bg:#fffbeb;--amber-bd:#fde68a;
    --red:#dc2626;--red-bg:#fef2f2;--red-bd:#fecaca;
    --purple:#7c3aed;--purple-bg:#faf5ff;--purple-bd:#ddd6fe;
    --cyan:#0e7490;--cyan-bg:#ecfeff;--cyan-bd:#a5f3fc;
    --weekend:#fafbfc;
    --r:8px;--r-lg:12px;--r-xl:16px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{background:var(--bg);font-family:'Inter',-apple-system,sans-serif;color:var(--tx);font-size:14px;line-height:1.5;}
a{color:inherit;text-decoration:none;}
.cal-page{padding:24px 28px;max-width:1600px;margin:0 auto;}

/* ── Topbar ── */
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.topbar-left{display:flex;align-items:center;gap:12px;}
.topbar-title{font-size:18px;font-weight:600;letter-spacing:-.3px;}
.topbar-sub{font-size:12px;color:var(--tx3);margin-top:1px;}
.topbar-actions{display:flex;gap:8px;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:var(--r);font-size:13px;font-weight:500;border:1px solid transparent;cursor:pointer;transition:all .15s;font-family:inherit;white-space:nowrap;text-decoration:none;}
.btn-ghost{background:var(--surf);border-color:var(--bd-md);color:var(--tx2);}
.btn-ghost:hover{background:var(--bg);color:var(--tx);}
.btn-primary{background:var(--blue);color:#fff;}
.btn-primary:hover{background:#1d4ed8;color:#fff;}
.btn-danger{background:var(--red);color:#fff;}
.btn-danger:hover{background:#b91c1c;color:#fff;}

/* ── Stats ── */
.stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:20px;}
.stat{background:var(--surf);border:1px solid var(--bd);border-radius:var(--r-lg);padding:14px 16px;display:flex;align-items:center;gap:12px;position:relative;overflow:hidden;transition:all .15s;}
.stat:hover{border-color:var(--bd-md);box-shadow:0 2px 8px rgba(15,23,42,.04);transform:translateY(-1px);}
.stat-ico{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;}
.stat-ico.blue  {background:var(--blue-bg);  color:var(--blue);}
.stat-ico.red   {background:var(--red-bg);   color:var(--red);}
.stat-ico.cyan  {background:var(--cyan-bg);  color:var(--cyan);}
.stat-ico.purple{background:var(--purple-bg);color:var(--purple);}
.stat-ico.gray  {background:#f1f5f9;          color:var(--tx2);}
.stat-num{font-size:24px;font-weight:700;letter-spacing:-.5px;line-height:1;}
.stat-lbl{font-size:10.5px;color:var(--tx3);margin-top:4px;text-transform:uppercase;letter-spacing:.6px;font-weight:600;}
.stat-bar{position:absolute;bottom:0;left:0;height:2px;width:100%;background:transparent;}
.stat-bar-fill{height:100%;border-radius:2px;transition:width .5s cubic-bezier(.4,0,.2,1);}
.stat-bar-fill.blue  {background:var(--blue);}
.stat-bar-fill.red   {background:var(--red);}
.stat-bar-fill.cyan  {background:var(--cyan);}
.stat-bar-fill.purple{background:var(--purple);}
.stat-bar-fill.gray  {background:var(--tx3);}

/* ── Search + filters ── */
.cal-search-wrap{display:flex;align-items:center;gap:8px;background:var(--surf);border:1px solid var(--bd);border-radius:var(--r-lg);padding:8px 14px;margin-bottom:12px;flex-wrap:wrap;transition:border-color .15s;}
.cal-search-wrap:focus-within{border-color:var(--blue-bd);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
.cal-search-ico{font-size:13px;color:var(--tx3);flex-shrink:0;}
.cal-search-input{flex:1;min-width:160px;border:none;outline:none;font-size:13px;font-family:'Inter',sans-serif;color:var(--tx);background:transparent;padding:4px 0;}
.cal-search-input::placeholder{color:var(--tx3);}
.cal-search-clear{background:none;border:none;cursor:pointer;color:var(--tx3);font-size:12px;padding:2px 6px;display:none;align-items:center;border-radius:4px;}
.cal-search-clear:hover{color:var(--tx);background:var(--bg);}
.cal-search-count{font-size:11px;font-weight:700;color:var(--blue);background:var(--blue-bg);border:1px solid var(--blue-bd);border-radius:99px;padding:3px 10px;white-space:nowrap;flex-shrink:0;display:none;}
.cal-filter-divider{width:1px;height:20px;background:var(--bd);margin:0 4px;flex-shrink:0;}
.cal-filter-chips{display:flex;gap:5px;align-items:center;flex-wrap:wrap;}
.cal-chip{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:4px 10px;border-radius:99px;border:1.5px solid;cursor:pointer;transition:all .15s;user-select:none;font-family:'Inter',sans-serif;}
.cal-chip:hover{transform:translateY(-1px);}
.cal-chip.chip-job   {color:var(--blue);  border-color:var(--blue-bd);  background:var(--blue-bg);}
.cal-chip.chip-emerg {color:var(--red);   border-color:var(--red-bd);   background:var(--red-bg);}
.cal-chip.chip-repair{color:var(--cyan);  border-color:var(--cyan-bd);  background:var(--cyan-bg);}
.cal-chip.chip-appr  {color:var(--purple);border-color:var(--purple-bd);background:var(--purple-bg);}
.cal-chip.off{opacity:.35;filter:grayscale(.6);}
.cal-chip-dot{width:6px;height:6px;border-radius:50%;background:currentColor;}

.search-notice{display:none;align-items:center;gap:8px;background:var(--cyan-bg);border:1px solid var(--cyan-bd);border-radius:var(--r);padding:9px 14px;font-size:12px;font-weight:600;color:var(--cyan);margin-bottom:12px;}
.search-notice.visible{display:flex;}
.search-notice a{margin-left:auto;color:var(--cyan);font-size:11px;font-weight:700;}

/* ── Calendar wrapper ── */
.cal-wrap{background:var(--surf);border:1px solid var(--bd);border-radius:var(--r-xl);overflow:hidden;box-shadow:0 1px 3px rgba(15,23,42,.04);}
.cal-inner{padding:18px 20px 22px;}

/* ── FullCalendar overrides ── */
.fc{font-family:'Inter',-apple-system,sans-serif !important;}
.fc .fc-header-toolbar{margin-bottom:18px !important;padding:0;align-items:center;}
.fc .fc-toolbar-title{font-size:15px !important;font-weight:700 !important;letter-spacing:-.2px;color:var(--tx) !important;}
.fc .fc-button{background:var(--surf) !important;border:1px solid var(--bd-md) !important;color:var(--tx2) !important;border-radius:var(--r) !important;padding:5px 11px !important;font-size:12px !important;font-weight:600 !important;box-shadow:none !important;font-family:'Inter',sans-serif !important;transition:all .15s !important;}
.fc .fc-button:hover{background:var(--bg) !important;color:var(--tx) !important;border-color:var(--bd-strong) !important;}
.fc .fc-button-active,.fc .fc-button-primary:not(:disabled).fc-button-active{background:var(--blue) !important;border-color:var(--blue) !important;color:#fff !important;}
.fc .fc-button-group{gap:4px;}

/* ── Day cells: clean look ── */
.fc th,.fc td{border-color:var(--bd) !important;}
.fc .fc-scrollgrid{border-color:var(--bd) !important;}
.fc .fc-col-header-cell-cushion{font-size:10.5px;font-weight:700;color:var(--tx3);text-transform:uppercase;letter-spacing:.7px;text-decoration:none;padding:10px 4px;}
.fc .fc-daygrid-day{min-height:90px;transition:background .12s ease;}
.fc .fc-daygrid-day:hover{background:var(--bg) !important;cursor:pointer;}
.fc .fc-daygrid-day-number{font-size:12px;font-weight:600;color:var(--tx2);padding:8px 10px !important;text-decoration:none;display:inline-block;}
.fc .fc-day-other .fc-daygrid-day-number{color:var(--tx4);font-weight:500;}

/* Weekend tint */
.fc .fc-day-sat,.fc .fc-day-sun{background:var(--weekend);}
.fc .fc-day-sat:hover,.fc .fc-day-sun:hover{background:#f1f5f9 !important;}

/* Today: subtle full-cell highlight + top accent + bold number */
.fc .fc-daygrid-day.fc-day-today{background:linear-gradient(180deg,rgba(37,99,235,.06) 0%,rgba(37,99,235,.02) 100%) !important;position:relative;}
.fc .fc-daygrid-day.fc-day-today::before{content:'';position:absolute;top:0;left:8%;right:8%;height:2px;background:var(--blue);border-radius:0 0 2px 2px;}
.fc .fc-day-today .fc-daygrid-day-number{color:var(--blue);font-weight:800;}

/* ── Events: compact single-line design ── */
.fc .fc-event{
    border:none !important;
    border-radius:6px !important;
    padding:0 !important;
    font-size:11.5px !important;
    font-weight:500 !important;
    cursor:pointer;
    margin-bottom:3px;
    box-shadow:none !important;
    transition:all .12s ease;
    overflow:hidden !important;
}
.fc .fc-event:hover{transform:translateX(2px);filter:brightness(1.06);box-shadow:0 2px 6px rgba(15,23,42,.12) !important;}
.fc .fc-daygrid-event-harness{margin-top:1px;}

/* Custom event content wrapper */
.evt-pill{display:flex;align-items:center;gap:6px;padding:4px 7px 4px 0;width:100%;min-width:0;}
.evt-pill-bar{width:3px;align-self:stretch;border-radius:6px 0 0 6px;flex-shrink:0;background:rgba(255,255,255,.55);}
.evt-pill-title{flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#fff;font-weight:600;font-size:11px;letter-spacing:-.1px;}
.evt-pill-status{flex-shrink:0;width:6px;height:6px;border-radius:50%;background:#fff;opacity:.85;}
.evt-pill-status.s-pending,.evt-pill-status.s-open{background:#fde047;opacity:1;}
.evt-pill-status.s-in_progress,.evt-pill-status.s-en_process{background:#c4b5fd;opacity:1;}
.evt-pill-status.s-completed,.evt-pill-status.s-resolved{background:#86efac;opacity:1;}
.evt-pill-status.s-cancelled,.evt-pill-status.s-closed{background:#fca5a5;opacity:1;}

/* List view: keep readable with type-color text and status pill */
.fc-list-event-title .evt-pill{padding:0;gap:8px;}
.fc-list-event-title .evt-pill-title{color:var(--tx);font-weight:600;font-size:13px;}
.fc-list-event-title .evt-pill-bar{display:none;}
.fc-list-event-title .evt-pill-status{width:auto;height:auto;background:transparent !important;opacity:1;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;padding:2px 8px;border-radius:99px;border:1px solid;}

.fc .fc-daygrid-day.fc-day-today{background:linear-gradient(180deg,rgba(37,99,235,.07) 0%,rgba(37,99,235,.02) 100%) !important;}

/* More link */
.fc .fc-more-link{font-size:10.5px;color:var(--tx2);font-weight:600;padding:2px 8px;background:var(--bg);border-radius:5px;margin-top:2px;display:inline-block;transition:all .12s;}
.fc .fc-more-link:hover{background:var(--blue-bg);color:var(--blue);}

/* List view */
.fc .fc-list-day-cushion{background:var(--bg) !important;font-size:11.5px !important;font-weight:700 !important;color:var(--tx) !important;}
.fc .fc-list-event:hover td{background:var(--bg) !important;}
.fc .fc-list-event-title a{text-decoration:none !important;}
.fc .fc-list-event-time{color:var(--tx3) !important;font-size:11.5px !important;font-weight:500 !important;}

/* Popover (More link) */
.fc .fc-popover{border-radius:var(--r-xl) !important;border:1px solid var(--bd-strong) !important;box-shadow:0 12px 40px rgba(15,23,42,.18) !important;overflow:hidden !important;font-family:'Inter',sans-serif !important;min-width:240px !important;}
.fc .fc-popover-header{background:var(--tx) !important;color:#fff !important;padding:10px 14px !important;font-size:12px !important;font-weight:700 !important;border-bottom:none !important;}
.fc .fc-popover-close{color:rgba(255,255,255,.6) !important;opacity:1 !important;}
.fc .fc-popover-close:hover{color:#fff !important;}
.fc .fc-popover-body{background:var(--surf) !important;padding:8px !important;display:flex !important;flex-direction:column !important;gap:4px !important;}
.fc .fc-popover-body .fc-event{margin:0 !important;}

/* ─────────────────────────── DRAWER ─────────────────────────── */
.drawer{position:fixed;top:0;right:0;bottom:0;z-index:1050;width:440px;max-width:100vw;background:var(--surf);box-shadow:-12px 0 48px rgba(0,0,0,.22);display:flex;flex-direction:column;transform:translateX(100%);transition:transform .3s cubic-bezier(.4,0,.2,1);border-left:1px solid var(--bd-md);}
.drawer.open{transform:translateX(0);}
.drawer-head{display:flex;align-items:center;justify-content:space-between;padding:18px 22px 16px;border-bottom:1px solid var(--bd);background:#0f1117;flex-shrink:0;}
.drawer-head-date{font-size:15px;font-weight:700;color:#fff;letter-spacing:-.3px;}
.drawer-head-day{font-size:11px;color:rgba(255,255,255,.45);font-weight:500;margin-top:2px;}
.drawer-close{width:32px;height:32px;border-radius:9px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;}
.drawer-close:hover{background:rgba(255,255,255,.16);color:#fff;}
.drawer-tabs{display:flex;background:var(--bg);border-bottom:1px solid var(--bd);flex-shrink:0;}
.drawer-tab{flex:1;padding:11px 12px 10px;background:none;border:none;font-size:12px;font-weight:500;color:var(--tx3);cursor:pointer;border-bottom:2.5px solid transparent;transition:color .15s,border-color .15s,background .15s;display:flex;align-items:center;justify-content:center;gap:6px;font-family:'Inter',sans-serif;}
.drawer-tab:hover{color:var(--tx);background:rgba(0,0,0,.03);}
.drawer-tab.on{color:var(--blue);border-bottom-color:var(--blue);background:var(--surf);font-weight:600;}
.drawer-body{flex:1;overflow-y:auto;}
.drawer-panel{display:none;}
.drawer-panel.on{display:block;animation:dpIn .18s ease;}
@keyframes dpIn{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
.drawer-events{padding:16px;display:flex;flex-direction:column;gap:10px;}
.ev-card{border-radius:var(--r-lg);overflow:hidden;border:1px solid var(--bd);background:var(--surf);transition:box-shadow .15s,border-color .15s;}
.ev-card:hover{box-shadow:0 3px 14px rgba(15,23,42,.09);border-color:var(--bd-md);}
.ev-card-top{display:flex;align-items:stretch;}
.ev-card-bar{width:4px;flex-shrink:0;}
.ev-card-body{padding:12px 14px;flex:1;min-width:0;}
.ev-card-title{font-size:13px;font-weight:700;color:var(--tx);margin-bottom:5px;line-height:1.3;}
.ev-card-meta{display:flex;flex-wrap:wrap;gap:8px;font-size:11.5px;color:var(--tx3);}
.ev-card-meta-item{display:flex;align-items:center;gap:4px;}
.ev-card-meta-item i{font-size:10px;}
.ev-card-footer{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-top:1px solid var(--bd);background:var(--bg);}
.ev-status-pill{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;padding:3px 9px;border-radius:99px;color:#fff;}
.ev-type-pill{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;padding:2px 8px;border-radius:99px;border:1px solid;flex-shrink:0;}
.ev-type-job{color:var(--blue);background:var(--blue-bg);border-color:var(--blue-bd);}
.ev-type-emerg{color:var(--red);background:var(--red-bg);border-color:var(--red-bd);}
.ev-type-appr{color:var(--purple);background:var(--purple-bg);border-color:var(--purple-bd);}
.ev-type-repair{color:var(--cyan);background:var(--cyan-bg);border-color:var(--cyan-bd);}
.btn-open{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:600;padding:5px 12px;border-radius:var(--r);background:var(--tx);color:#fff;text-decoration:none;transition:opacity .15s;}
.btn-open:hover{opacity:.82;color:#fff;}
.empty-day{text-align:center;padding:48px 20px;color:var(--tx3);}
.empty-day i{font-size:26px;display:block;margin-bottom:10px;opacity:.35;}
.empty-day p{font-size:13px;}

@media(max-width:900px){.stats-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:640px){.cal-page{padding:16px;}.stats-grid{grid-template-columns:repeat(2,1fr);}.cal-inner{padding:12px;}.drawer{width:100vw;}}
</style>

<div class="cal-page">
    <div class="topbar">
        <div class="topbar-left">
            <div style="width:36px;height:36px;background:var(--blue-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;border:1px solid var(--blue-bd)">
                <i class="fas fa-calendar-days" style="font-size:15px;color:var(--blue)"></i>
            </div>
            <div>
                <div class="topbar-title">Job Calendar</div>
                <div class="topbar-sub">Your assigned jobs, emergencies &amp; repairs</div>
            </div>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('jobs.create') }}" class="btn btn-primary"><i class="fas fa-plus" style="font-size:11px"></i> New Job</a>
            <a href="{{ route('emergency.form') }}" class="btn btn-danger"><i class="fas fa-circle-exclamation" style="font-size:11px"></i> Emergency</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat">
            <div class="stat-ico blue"><i class="fas fa-briefcase"></i></div>
            <div><div class="stat-num" id="stat-jobs">—</div><div class="stat-lbl">Jobs</div></div>
            <div class="stat-bar"><div class="stat-bar-fill blue" id="sbar-jobs" style="width:0%"></div></div>
        </div>
        <div class="stat">
            <div class="stat-ico red"><i class="fas fa-circle-exclamation"></i></div>
            <div><div class="stat-num" id="stat-emergencies">—</div><div class="stat-lbl">Emergencies</div></div>
            <div class="stat-bar"><div class="stat-bar-fill red" id="sbar-emerg" style="width:0%"></div></div>
        </div>
        <div class="stat">
            <div class="stat-ico cyan"><i class="fas fa-screwdriver-wrench"></i></div>
            <div><div class="stat-num" id="stat-repairs">—</div><div class="stat-lbl">Repairs</div></div>
            <div class="stat-bar"><div class="stat-bar-fill cyan" id="sbar-repairs" style="width:0%"></div></div>
        </div>
        <div class="stat">
            <div class="stat-ico purple"><i class="fas fa-circle-check"></i></div>
            <div><div class="stat-num" id="stat-approvals">—</div><div class="stat-lbl">Approvals</div></div>
            <div class="stat-bar"><div class="stat-bar-fill purple" id="sbar-appr" style="width:0%"></div></div>
        </div>
        <div class="stat">
            <div class="stat-ico gray"><i class="fas fa-layer-group"></i></div>
            <div><div class="stat-num" id="stat-total">—</div><div class="stat-lbl">Total</div></div>
            <div class="stat-bar"><div class="stat-bar-fill gray" id="sbar-total" style="width:100%"></div></div>
        </div>
    </div>

    <div class="cal-search-wrap">
        <i class="fas fa-search cal-search-ico"></i>
        <input type="text" id="calSearch" class="cal-search-input" placeholder="Search by job number, company, address…">
        <button type="button" class="cal-search-clear" id="btnClearSearch" onclick="clearSearch()"><i class="fas fa-times"></i></button>
        <div class="cal-filter-divider"></div>
        <div class="cal-filter-chips">
            <span class="cal-chip chip-job"    data-filter="Job Request"   onclick="toggleChip(this)"><span class="cal-chip-dot"></span> Jobs</span>
            <span class="cal-chip chip-emerg"  data-filter="Emergency"     onclick="toggleChip(this)"><span class="cal-chip-dot"></span> Emergencies</span>
            <span class="cal-chip chip-repair" data-filter="Repair Ticket" onclick="toggleChip(this)"><span class="cal-chip-dot"></span> Repairs</span>
            <span class="cal-chip chip-appr"   data-filter="Lead Approval" onclick="toggleChip(this)"><span class="cal-chip-dot"></span> Approvals</span>
        </div>
        <span class="cal-search-count" id="eventCount"></span>
    </div>

    <div class="search-notice" id="searchNotice">
        <i class="fas fa-info-circle"></i>
        <span id="searchNoticeText"></span>
        <a href="#" onclick="clearSearch();return false;">Clear</a>
    </div>

    <div class="cal-wrap">
        <div class="cal-inner"><div id="calendar"></div></div>
    </div>
</div>

{{-- DRAWER --}}
<div class="drawer" id="mainDrawer">
    <div class="drawer-head">
        <div>
            <div class="drawer-head-date" id="drawerDateLabel">—</div>
            <div class="drawer-head-day"  id="drawerDayLabel">—</div>
        </div>
        <button class="drawer-close" onclick="closeDrawer()"><i class="fas fa-times"></i></button>
    </div>

    <div class="drawer-tabs">
        <button class="drawer-tab on" data-tab="events">
            <i class="fas fa-calendar-days"></i> Events
        </button>
    </div>

    <div class="drawer-body">
        <div class="drawer-panel on" id="panel-events">
            <div class="drawer-events" id="drawerEventsBody"></div>
        </div>
    </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    // ── Color maps ──────────────────────────────
    const TYPE_COLORS = {
        'Job Request':    '#2563eb',
        'Emergency':      '#dc2626',
        'Lead Approval':  '#7c3aed',
        'Repair Ticket':  '#0e7490'
    };
    const STATUS_SOLID = {
        pending:'#d97706', open:'#d97706', scheduled:'#2563eb',
        in_progress:'#7c3aed', en_process:'#7c3aed',
        completed:'#16a34a', resolved:'#16a34a',
        cancelled:'#dc2626', closed:'#9099a8',
        emergency:'#dc2626', repair:'#0e7490', approval:'#7c3aed'
    };
    const STATUS_LIGHT = {
        pending:    {bg:'#fef3c7', color:'#92400e', bd:'#fde68a'},
        open:       {bg:'#fef3c7', color:'#92400e', bd:'#fde68a'},
        scheduled:  {bg:'#dbeafe', color:'#1e40af', bd:'#bfdbfe'},
        in_progress:{bg:'#ede9fe', color:'#5b21b6', bd:'#ddd6fe'},
        en_process: {bg:'#ede9fe', color:'#5b21b6', bd:'#ddd6fe'},
        completed:  {bg:'#dcfce7', color:'#166534', bd:'#bbf7d0'},
        resolved:   {bg:'#dcfce7', color:'#166534', bd:'#bbf7d0'},
        cancelled:  {bg:'#fee2e2', color:'#991b1b', bd:'#fecaca'},
        closed:     {bg:'#f1f5f9', color:'#475569', bd:'#e2e8f0'},
        emergency:  {bg:'#fee2e2', color:'#991b1b', bd:'#fecaca'},
        repair:     {bg:'#cffafe', color:'#155e75', bd:'#a5f3fc'},
        approval:   {bg:'#ede9fe', color:'#5b21b6', bd:'#ddd6fe'}
    };

    function sKey(s){return(s||'pending').toLowerCase().replace(/\s+/g,'_');}
    function accentColor(type,status){
        if(type==='Emergency')return'#dc2626';
        if(type==='Lead Approval')return'#7c3aed';
        if(type==='Repair Ticket')return'#0e7490';
        return(STATUS_LIGHT[sKey(status)]||{}).color||'#9099a8';
    }
    function typeTag(t){
        if(t==='Emergency')     return `<span class="ev-type-pill ev-type-emerg">Emergency</span>`;
        if(t==='Lead Approval') return `<span class="ev-type-pill ev-type-appr">Approval</span>`;
        if(t==='Repair Ticket') return `<span class="ev-type-pill ev-type-repair">Repair</span>`;
        return `<span class="ev-type-pill ev-type-job">Job</span>`;
    }

    window.allEvents = [];
    const activeFilters = new Set(['Job Request','Emergency','Repair Ticket','Lead Approval']);
    let searchQuery = '';

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'),{
        initialView:'dayGridMonth',
        headerToolbar:{left:'prev,next today',center:'title',right:'dayGridMonth,timeGridWeek,listMonth'},
        height:'auto',
        dayMaxEvents:3,
        fixedWeekCount:false,
        events:function(fetchInfo,successCallback,failureCallback){
            fetch("{{ route('calendar.data') }}")
                .then(r=>r.json())
                .then(data=>{
                    window.allEvents = Array.isArray(data.events) ? data.events : [];
                    const j  = data.job_count          ?? 0,
                          em = data.emergency_count    ?? 0,
                          rp = data.repair_count       ?? 0,
                          ap = data.lead_approval_count?? 0,
                          tot= j+em+rp+ap || 1;
                    document.getElementById('stat-jobs').textContent        = j;
                    document.getElementById('stat-emergencies').textContent = em;
                    document.getElementById('stat-repairs').textContent     = rp;
                    document.getElementById('stat-approvals').textContent   = ap;
                    document.getElementById('stat-total').textContent       = window.allEvents.length;
                    document.getElementById('sbar-jobs').style.width    = Math.round(j/tot*100)+'%';
                    document.getElementById('sbar-emerg').style.width   = Math.round(em/tot*100)+'%';
                    document.getElementById('sbar-repairs').style.width = Math.round(rp/tot*100)+'%';
                    document.getElementById('sbar-appr').style.width    = Math.round(ap/tot*100)+'%';
                    renderFilteredEvents(successCallback);
                }).catch(err=>failureCallback(err));
        },

        // ── Compact one-line event ──
        eventContent:function(arg){
            const p = arg.event.extendedProps || {};
            const status = sKey(p.status||'');
            const isList = ['listMonth','listWeek','listDay'].includes(arg.view.type);

            const wrap = document.createElement('div');
            wrap.className = 'evt-pill';

            if (!isList) {
                // Bar de jerarquía
                const bar = document.createElement('span');
                bar.className = 'evt-pill-bar';
                wrap.appendChild(bar);
            }

            // Title
            const title = document.createElement('span');
            title.className = 'evt-pill-title';
            title.textContent = arg.event.title;
            wrap.appendChild(title);

            // Status indicator
            const dot = document.createElement('span');
            dot.className = 'evt-pill-status s-' + status;
            if (isList) {
                const sl = STATUS_LIGHT[status] || {};
                dot.textContent = status.replace('_',' ');
                dot.style.cssText = `background:${sl.bg||'#f1f5f9'};color:${sl.color||'#475569'};border-color:${sl.bd||'#e2e8f0'};`;
            }
            wrap.appendChild(dot);

            return { domNodes: [wrap] };
        },

        eventClick:function(info){
            info.jsEvent.preventDefault();
            openDrawer(info.event.startStr.substring(0,10));
        },
        dateClick:function(info){ openDrawer(info.dateStr); },
        moreLinkClick:function(info){
            openDrawer(info.date.toISOString().substring(0,10));
            return 'stop';
        },
    });
    calendar.render();
    window.refreshCalendar = ()=>calendar.refetchEvents();

    function getFiltered(){
        if(!searchQuery) return window.allEvents.filter(ev=>activeFilters.has(ev.type));
        const q = searchQuery;
        const direct = window.allEvents.filter(ev=>{
            if(!activeFilters.has(ev.type)) return false;
            return (ev.title||'').toLowerCase().includes(q)
                || (ev.extendedProps?.company||'').toLowerCase().includes(q)
                || (ev.extendedProps?.address||'').toLowerCase().includes(q);
        });
        const urls = new Set(direct.map(ev=>ev.url).filter(Boolean));
        const res  = new Map();
        direct.forEach(ev=>res.set(ev.title+'|'+ev.start, ev));
        window.allEvents.forEach(ev=>{
            if(!activeFilters.has(ev.type)) return;
            if(ev.url && urls.has(ev.url)) res.set(ev.title+'|'+ev.start, ev);
        });
        return Array.from(res.values());
    }

    function renderFilteredEvents(successCallback){
        const filtered = getFiltered();
        const count = filtered.length;
        const countEl = document.getElementById('eventCount');
        countEl.textContent = count + ' event' + (count!==1 ? 's' : '');
        countEl.style.display = (searchQuery || activeFilters.size<4) ? 'inline-flex' : 'none';

        const mapped = filtered.map(ev=>({
            ...ev,
            backgroundColor: TYPE_COLORS[ev.type] || ev.color || '#9099a8',
            borderColor:     TYPE_COLORS[ev.type] || ev.color || '#9099a8',
            textColor:'#fff'
        }));

        if (successCallback) {
            successCallback(mapped);
        } else {
            calendar.getEventSources().forEach(s=>s.remove());
            calendar.addEventSource(mapped);
            const notice = document.getElementById('searchNotice');
            if (searchQuery) {
                calendar.changeView('listMonth');
                notice.classList.add('visible');
                document.getElementById('searchNoticeText').textContent =
                    `${count} event${count!==1?'s':''} found for "${searchQuery}" across all months`;
            } else {
                calendar.changeView('dayGridMonth');
                calendar.today();
                notice.classList.remove('visible');
            }
        }
    }

    window.toggleChip = function(chip){
        const f = chip.dataset.filter;
        if (activeFilters.has(f)) { activeFilters.delete(f); chip.classList.add('off'); }
        else                      { activeFilters.add(f);    chip.classList.remove('off'); }
        renderFilteredEvents(null);
    };
    document.getElementById('calSearch').addEventListener('input',function(){
        searchQuery = this.value.toLowerCase().trim();
        document.getElementById('btnClearSearch').style.display = searchQuery ? 'flex' : 'none';
        renderFilteredEvents(null);
    });
    window.clearSearch = function(){
        document.getElementById('calSearch').value = '';
        searchQuery = '';
        document.getElementById('btnClearSearch').style.display = 'none';
        renderFilteredEvents(null);
    };

    window.openDrawer = function(dateStr){
        const evs = (window.allEvents||[]).filter(ev=>ev.start && ev.start.startsWith(dateStr));
        const d = new Date(dateStr+'T00:00:00');
        document.getElementById('drawerDateLabel').textContent =
            d.toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'});
        document.getElementById('drawerDayLabel').textContent =
            d.toLocaleDateString('en-US',{weekday:'long'});

        const body = document.getElementById('drawerEventsBody');
        body.innerHTML = '';

        if (!evs.length) {
            body.innerHTML = `<div class="empty-day"><i class="fas fa-calendar-days"></i><p>No events on this date.</p></div>`;
        } else {
            evs.forEach(ev=>{
                const p = ev.extendedProps || {};
                const acc = accentColor(ev.type, p.status);
                const sk  = sKey(p.status||ev.type);
                const solidColor = STATUS_SOLID[sk] || '#9099a8';
                const card = document.createElement('div');
                card.className = 'ev-card';
                card.innerHTML = `
                    <div class="ev-card-top">
                        <div class="ev-card-bar" style="background:${acc}"></div>
                        <div class="ev-card-body">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:6px;">
                                <div class="ev-card-title">${ev.title || '—'}</div>
                                ${typeTag(ev.type)}
                            </div>
                            <div class="ev-card-meta">
                                ${p.company ? `<span class="ev-card-meta-item"><i class="fas fa-building"></i>${p.company}</span>` : ''}
                                ${p.address ? `<span class="ev-card-meta-item"><i class="fas fa-map-marker-alt"></i>${p.address}</span>` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="ev-card-footer">
                        <span class="ev-status-pill" style="background:${solidColor}">${sk.replace('_',' ')}</span>
                        ${ev.url ? `<a href="${ev.url}" class="btn-open">Open <i class="fas fa-arrow-right" style="font-size:10px"></i></a>` : ''}
                    </div>`;
                body.appendChild(card);
            });
        }

        document.getElementById('mainDrawer').classList.add('open');
    };

    window.closeDrawer = function(){
        document.getElementById('mainDrawer').classList.remove('open');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', e => { if(e.key==='Escape') closeDrawer(); });
});
</script>