@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
    --ink:#0f172a;--ink2:#334155;--ink3:#64748b;--ink4:#94a3b8;
    --line:#e2e8f0;--line2:#f1f5f9;--white:#ffffff;--page:#f0f2f5;
    --cyan:#0891b2;--cyan-bg:#ecfeff;--cyan-bd:#a5f3fc;
    --green:#059669;--green-bg:#f0fdf4;--green-bd:#6ee7b7;
    --amber:#d97706;--amber-bg:#fffbeb;--amber-bd:#fde68a;
    --red:#dc2626;--red-bg:#fef2f2;--red-bd:#fecaca;
    --purple:#7c3aed;--purple-bg:#f5f3ff;--purple-bd:#ddd6fe;
}
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Montserrat',sans-serif;background:var(--page);}
.rt-page{min-height:100vh;padding:1.75rem 1.5rem 3rem;}

/* ── Hero ── */
.rt-hero{background:var(--ink);border-radius:18px;padding:1.6rem 2rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;position:relative;overflow:hidden;}
.rt-hero::before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(8,145,178,.25) 0%,transparent 70%);pointer-events:none;}
.rt-hero::after{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,#67e8f9,var(--cyan));border-radius:18px 0 0 18px;}
.hero-eye{font-size:.6rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--ink4);margin-bottom:.3rem;display:flex;align-items:center;gap:.35rem;}
.hero-title{font-size:1.45rem;font-weight:800;color:#fff;letter-spacing:-.035em;line-height:1.1;margin-bottom:.2rem;}
.hero-sub{font-size:.73rem;color:var(--ink4);}
.hero-actions{display:flex;gap:.55rem;flex-wrap:wrap;align-items:center;}
.btn-ghost{display:inline-flex;align-items:center;gap:.35rem;font-weight:600;font-size:.75rem;padding:.4rem .9rem;border-radius:8px;text-decoration:none;background:rgba(255,255,255,.07);color:var(--ink4);border:1px solid rgba(255,255,255,.1);transition:all .18s;white-space:nowrap;cursor:pointer;font-family:'Montserrat',sans-serif;}
.btn-ghost:hover{color:#fff;background:rgba(255,255,255,.13);}
.btn-new{display:inline-flex;align-items:center;gap:.35rem;font-weight:700;font-size:.75rem;padding:.42rem 1rem;border-radius:8px;background:var(--cyan);color:#fff;border:none;cursor:pointer;font-family:'Montserrat',sans-serif;box-shadow:0 4px 12px rgba(8,145,178,.35);transition:filter .18s;white-space:nowrap;}
.btn-new:hover{filter:brightness(1.1);color:#fff;}

/* ── Stats ── */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.25rem;}
.stat{background:var(--white);border-radius:14px;border:1px solid var(--line);padding:.9rem 1.1rem;display:flex;align-items:center;gap:.85rem;position:relative;overflow:hidden;transition:box-shadow .15s;}
.stat:hover{box-shadow:0 4px 18px rgba(15,23,42,.07);}
.stat-bar{position:absolute;bottom:0;left:0;right:0;height:2.5px;}
.stat-icon{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0;}
.stat-num{font-size:1.55rem;font-weight:800;letter-spacing:-.04em;line-height:1;}
.stat-lbl{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink4);margin-top:2px;}

/* ── Toolbar ── */
.rt-toolbar{background:var(--white);border-radius:12px;border:1px solid var(--line);padding:.75rem 1rem;margin-bottom:1.1rem;display:flex;gap:.55rem;flex-wrap:wrap;align-items:center;box-shadow:0 1px 4px rgba(15,23,42,.04);}
.tb-search{display:flex;align-items:center;gap:.4rem;background:var(--line2);border:1.5px solid var(--line);border-radius:8px;padding:.35rem .8rem;flex:1;min-width:180px;transition:border-color .2s;}
.tb-search:focus-within{border-color:var(--cyan);}
.tb-search i{color:var(--ink4);font-size:.75rem;flex-shrink:0;}
.tb-search input{border:none;background:transparent;outline:none;font-family:'Montserrat',sans-serif;font-size:.8rem;color:var(--ink);width:100%;}
.tb-search input::placeholder{color:var(--ink4);}
.tb-select{font-family:'Montserrat',sans-serif;font-size:.78rem;border:1.5px solid var(--line);border-radius:8px;padding:.35rem .7rem;color:var(--ink);background:var(--white);cursor:pointer;transition:border-color .2s;}
.tb-select:focus{border-color:var(--cyan);outline:none;}
.btn-tb{display:inline-flex;align-items:center;gap:.3rem;font-family:'Montserrat',sans-serif;font-size:.75rem;font-weight:600;padding:.38rem .85rem;border-radius:8px;border:none;cursor:pointer;white-space:nowrap;transition:filter .15s,transform .1s;}
.btn-tb:hover{transform:translateY(-1px);filter:brightness(1.06);}
.btn-tb-primary{background:var(--cyan);color:#fff;}
.btn-tb-ghost{background:var(--line2);color:var(--ink3);border:1px solid var(--line);}
.btn-tb-ghost:hover{color:var(--ink);}

/* ── Ticket card ── */
.ticket-card{background:var(--white);border-radius:16px;border:1px solid var(--line);margin-bottom:.85rem;overflow:hidden;transition:box-shadow .15s,transform .12s;position:relative;}
.ticket-card:hover{box-shadow:0 6px 28px rgba(15,23,42,.08);transform:translateY(-1px);}
.ticket-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--cyan);border-radius:16px 0 0 16px;}
.tc-header{padding:.8rem 1.1rem .8rem 1.3rem;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;gap:.65rem;background:linear-gradient(to right,var(--cyan-bg),#fafbfd);}
.tc-left{display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;min-width:0;}
.tc-ref{display:inline-flex;align-items:center;gap:.3rem;font-size:.75rem;font-weight:700;color:var(--cyan);text-decoration:none;white-space:nowrap;}
.tc-ref:hover{text-decoration:underline;}
.tc-ref.emerg{color:#dc2626;}
.tc-id{font-size:.62rem;font-weight:700;color:var(--ink4);background:var(--line2);padding:.15rem .5rem;border-radius:99px;white-space:nowrap;}
.tc-date{font-size:.68rem;color:var(--ink4);display:flex;align-items:center;gap:.25rem;white-space:nowrap;}
.tc-right{display:flex;align-items:center;gap:.4rem;flex-shrink:0;}

/* ── Status badges — unified ── */
.s-badge{display:inline-flex;align-items:center;gap:.25rem;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:.18rem .6rem;border-radius:99px;white-space:nowrap;}
.s-pending   {background:var(--amber-bg);color:var(--amber);border:1px solid var(--amber-bd);}
.s-en_process{background:var(--purple-bg);color:var(--purple);border:1px solid var(--purple-bd);}
.s-completed {background:var(--green-bg);color:var(--green);border:1px solid var(--green-bd);}

.tc-actions{display:flex;gap:.3rem;align-items:center;}
.btn-sm{font-size:.68rem;font-weight:600;padding:.2rem .55rem;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:.22rem;transition:all .15s;border:1px solid;cursor:pointer;font-family:'Montserrat',sans-serif;white-space:nowrap;}
.btn-sm-edit{background:var(--line2);color:var(--ink2);border-color:var(--line);}
.btn-sm-edit:hover{background:var(--line);color:var(--ink);}
.btn-sm-del{background:var(--red-bg);color:var(--red);border-color:var(--red-bd);}
.btn-sm-del:hover{background:#fecaca;color:#b91c1c;}
.tc-body{padding:.9rem 1.1rem .9rem 1.3rem;}
.tc-desc{font-size:.82rem;color:var(--ink2);line-height:1.65;margin-bottom:.75rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.tc-meta{display:flex;gap:.4rem;flex-wrap:wrap;align-items:center;margin-bottom:.75rem;}
.meta-pill{display:inline-flex;align-items:center;gap:.28rem;font-size:.67rem;font-weight:600;color:var(--ink3);background:var(--line2);border:1px solid var(--line);padding:.18rem .6rem;border-radius:99px;}
.tc-photos{display:flex;gap:.4rem;flex-wrap:wrap;padding-top:.75rem;border-top:1px dashed var(--line);}
.ph-wrap{position:relative;flex-shrink:0;}
.ph-thumb{width:64px;height:64px;border-radius:8px;object-fit:cover;cursor:pointer;transition:opacity .15s,transform .15s;display:block;border:2px solid transparent;}
.ph-thumb:hover{opacity:.85;transform:scale(1.05);}
.ph-thumb.admin-thumb{border-color:#ffb347;}
.ph-thumb.crew-thumb{border-color:var(--cyan-bd);}
.ph-badge{position:absolute;bottom:3px;left:3px;font-size:8px;font-weight:700;text-transform:uppercase;padding:1px 5px;border-radius:4px;letter-spacing:.03em;}
.ph-badge.admin{background:rgba(255,179,71,.9);color:#7c3a00;}
.ph-badge.crew{background:rgba(8,145,178,.85);color:#fff;}
.ph-pdf{width:64px;height:64px;border-radius:8px;background:var(--line2);border:1.5px solid var(--line);display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:.58rem;font-weight:700;color:var(--ink3);text-decoration:none;gap:.18rem;transition:background .15s;flex-shrink:0;}
.ph-pdf:hover{background:var(--line);}
.ph-more{width:64px;height:64px;border-radius:8px;background:var(--cyan-bg);border:1.5px solid var(--cyan-bd);display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;color:var(--cyan);flex-shrink:0;gap:2px;}
.tl-connector{display:flex;padding:0 0 0 1.5rem;margin:-.3rem 0;}
.tl-line{width:2px;height:16px;background:linear-gradient(to bottom,var(--line),transparent);}

/* Empty */
.empty-state{text-align:center;padding:3.5rem 1rem;}
.empty-icon{width:68px;height:68px;border-radius:18px;background:var(--cyan-bg);border:1.5px solid var(--cyan-bd);display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;}
.empty-icon i{font-size:1.5rem;color:var(--cyan);}
.empty-title{font-size:.95rem;font-weight:700;color:var(--ink);margin-bottom:.35rem;}
.empty-sub{font-size:.78rem;color:var(--ink4);margin-bottom:1.1rem;}
.btn-empty{display:inline-flex;align-items:center;gap:.35rem;font-family:'Montserrat',sans-serif;font-weight:700;font-size:.82rem;padding:.6rem 1.4rem;border-radius:10px;border:none;background:var(--cyan);color:#fff;cursor:pointer;box-shadow:0 4px 12px rgba(8,145,178,.3);transition:filter .2s;text-decoration:none;}
.btn-empty:hover{filter:brightness(1.08);}

/* ══ DRAWER ══ */
.rt-drawer{position:fixed;top:0;right:0;bottom:0;z-index:1060;width:460px;max-width:100vw;background:var(--white);box-shadow:-12px 0 48px rgba(15,23,42,.18);border-left:1px solid var(--line);display:flex;flex-direction:column;transform:translateX(100%);transition:transform .3s cubic-bezier(.4,0,.2,1);}
.rt-drawer.open{transform:translateX(0);}
.rt-drawer-head{background:linear-gradient(135deg,#0c4a6e 0%,#0891b2 100%);padding:1.1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
.rt-drawer-icon{width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.rt-drawer-title{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.5);}
.rt-drawer-name{font-size:.95rem;font-weight:800;color:#fff;letter-spacing:-.02em;line-height:1.2;}
.rt-drawer-close{width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.65);font-size:13px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;}
.rt-drawer-close:hover{background:rgba(255,255,255,.2);color:#fff;}
.rt-ctx-bar{background:#083d5a;padding:.48rem 1.5rem;display:flex;align-items:center;gap:.55rem;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0;}
.rt-ctx-bar span{font-size:.68rem;color:rgba(255,255,255,.45);font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;}
.rt-ctx-pill{font-size:.6rem;font-weight:700;padding:.13rem .48rem;border-radius:99px;flex-shrink:0;}
.rt-ctx-pill.emerg{background:rgba(239,68,68,.2);color:#fca5a5;border:1px solid rgba(239,68,68,.3);}
.rt-ctx-pill.job{background:rgba(59,130,246,.2);color:#93c5fd;border:1px solid rgba(59,130,246,.3);}
.rt-ctx-pill.none{background:rgba(255,255,255,.07);color:rgba(255,255,255,.35);border:1px solid rgba(255,255,255,.1);}
.rt-drawer-body{flex:1;overflow-y:auto;padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:.95rem;}
.rt-label{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink3);margin-bottom:.25rem;display:block;}
.rt-ctrl{font-family:'Montserrat',sans-serif;font-size:.84rem;width:100%;border:1.5px solid var(--line);border-radius:9px;padding:.5rem .82rem;color:var(--ink);background:var(--white);transition:border-color .2s,box-shadow .2s;outline:none;}
.rt-ctrl:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(8,145,178,.1);}
textarea.rt-ctrl{resize:vertical;min-height:80px;}
.rt-step-head{display:flex;align-items:center;gap:.48rem;margin-bottom:.6rem;}
.rt-step-num{width:20px;height:20px;border-radius:50%;background:var(--cyan);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.rt-step-num span{font-size:.58rem;font-weight:800;color:#fff;}
.rt-step-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink3);}
.rt-divider{border:none;border-top:1px solid var(--line);}
.proj-preview{display:none;margin-top:.45rem;border-radius:10px;padding:.7rem .95rem;align-items:center;gap:.6rem;border:1.5px solid var(--cyan-bd);background:var(--cyan-bg);}
.proj-preview.emerg-preview{background:#fff1f2;border-color:#fca5a5;}
.proj-preview-icon{width:26px;height:26px;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.72rem;}
.proj-preview-num{font-size:.8rem;font-weight:700;color:var(--ink);}
.proj-preview-addr{font-size:.68rem;color:var(--ink3);margin-top:1px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.drop-zone{border:2px dashed var(--line);border-radius:11px;padding:1rem;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:var(--line2);}
.drop-zone:hover{border-color:var(--cyan);background:var(--cyan-bg);}
.drop-zone input[type="file"]{display:none;}
.drop-zone i{font-size:1.35rem;color:var(--ink4);opacity:.45;display:block;margin-bottom:.3rem;}
.drop-zone-title{font-size:.73rem;font-weight:600;color:var(--ink2);}
.drop-zone-sub{font-size:.64rem;color:var(--ink4);margin-top:.18rem;}
.preview-grid{margin-top:.6rem;flex-wrap:wrap;gap:.4rem;display:none;}
.preview-thumb{position:relative;width:64px;height:64px;border-radius:8px;overflow:hidden;border:1.5px solid var(--line);flex-shrink:0;}
.preview-thumb img{width:100%;height:100%;object-fit:cover;display:block;}
.preview-thumb-pdf{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;background:var(--line2);}
.btn-thumb-remove{position:absolute;top:2px;right:2px;width:17px;height:17px;border-radius:50%;background:rgba(15,23,42,.75);border:none;color:#fff;font-size:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;}
.btn-rt-submit{display:inline-flex;align-items:center;gap:.4rem;font-family:'Montserrat',sans-serif;font-weight:700;font-size:.82rem;padding:.58rem 1.5rem;border-radius:9px;border:none;background:var(--cyan);color:#fff;cursor:pointer;box-shadow:0 4px 12px rgba(8,145,178,.3);transition:filter .2s;}
.btn-rt-submit:hover{filter:brightness(1.08);}
.del-confirm{position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%) translateY(100px);z-index:2000;background:#0f172a;color:#fff;border-radius:14px;padding:.85rem 1.25rem;display:flex;align-items:center;gap:.85rem;box-shadow:0 8px 32px rgba(0,0,0,.3);transition:transform .3s cubic-bezier(.4,0,.2,1);min-width:320px;max-width:90vw;}
.del-confirm.show{transform:translateX(-50%) translateY(0);}
.del-confirm-txt{flex:1;font-size:.8rem;font-weight:600;}
.del-confirm-txt span{display:block;font-size:.7rem;font-weight:400;color:#94a3b8;margin-top:2px;}
.btn-del-yes{font-family:'Montserrat',sans-serif;font-weight:700;font-size:.75rem;padding:.35rem .85rem;border-radius:7px;border:none;background:#dc2626;color:#fff;cursor:pointer;transition:filter .15s;white-space:nowrap;}
.btn-del-yes:hover{filter:brightness(1.1);}
.btn-del-no{font-family:'Montserrat',sans-serif;font-weight:600;font-size:.75rem;padding:.35rem .85rem;border-radius:7px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);cursor:pointer;transition:all .15s;white-space:nowrap;}
.btn-del-no:hover{background:rgba(255,255,255,.14);color:#fff;}

@media(max-width:900px){.stats-row{grid-template-columns:1fr 1fr;}}
@media(max-width:640px){.rt-page{padding:1rem .75rem 2.5rem;}.rt-hero{padding:1.1rem 1.25rem;}.hero-title{font-size:1.2rem;}.rt-drawer{width:100vw;}}
</style>

<div class="rt-page">
<div class="container-xl px-0" style="max-width:960px;">

    {{-- Hero --}}
    <div class="rt-hero">
        <div>
            <div class="hero-eye"><i class="bi bi-tools"></i> Repair Tickets</div>
            <h1 class="hero-title">
                @if(request('ref_type') && request('ref_id'))
                    Repair History
                    @if($refLabel ?? null)<span style="color:var(--cyan);"> — {{ $refLabel }}</span>@endif
                @else
                    All Repair Tickets
                @endif
            </h1>
            <div class="hero-sub">
                {{ $tickets->total() }} ticket{{ $tickets->total() !== 1 ? 's' : '' }} found
                @if(request('status') || request('search')) · <span style="color:var(--cyan);">Filtered</span>@endif
            </div>
        </div>
        <div class="hero-actions">
            @if(request('ref_type') && request('ref_id'))
                @if(request('ref_type') === 'job')
                    <a href="{{ route('jobs.show', request('ref_id')) }}" class="btn-ghost"><i class="bi bi-briefcase"></i> Back to Job</a>
                @else
                    <a href="{{ route('emergency.show', request('ref_id')) }}" class="btn-ghost"><i class="bi bi-exclamation-octagon"></i> Back to Emergency</a>
                @endif
            @endif
            <a href="{{ route('calendar.view') }}" class="btn-ghost"><i class="bi bi-calendar3"></i> Calendar</a>
            <button type="button" class="btn-new" onclick="openRtDrawer()">
                <i class="bi bi-plus-lg"></i> New Ticket
            </button>
        </div>
    </div>

    {{-- Stats — unified statuses ─────────────────────────── --}}
    <div class="stats-row">
        <div class="stat">
            <div class="stat-bar" style="background:linear-gradient(90deg,var(--ink3),transparent)"></div>
            <div class="stat-icon" style="background:var(--line2);"><i class="bi bi-grid-3x3-gap-fill" style="color:var(--ink3);"></i></div>
            <div><div class="stat-num" style="color:var(--ink);">{{ $stats['total'] }}</div><div class="stat-lbl">Total</div></div>
        </div>
        <div class="stat">
            <div class="stat-bar" style="background:linear-gradient(90deg,var(--amber),transparent)"></div>
            <div class="stat-icon" style="background:var(--amber-bg);"><i class="bi bi-clock-fill" style="color:var(--amber);"></i></div>
            <div><div class="stat-num" style="color:var(--amber);">{{ $stats['pending'] }}</div><div class="stat-lbl">Scheduled</div></div>
        </div>
        <div class="stat">
            <div class="stat-bar" style="background:linear-gradient(90deg,var(--purple),transparent)"></div>
            <div class="stat-icon" style="background:var(--purple-bg);"><i class="bi bi-tools" style="color:var(--purple);"></i></div>
            <div><div class="stat-num" style="color:var(--purple);">{{ $stats['en_process'] }}</div><div class="stat-lbl">In Progress</div></div>
        </div>
        <div class="stat">
            <div class="stat-bar" style="background:linear-gradient(90deg,var(--green),transparent)"></div>
            <div class="stat-icon" style="background:var(--green-bg);"><i class="bi bi-check-circle-fill" style="color:var(--green);"></i></div>
            <div><div class="stat-num" style="color:var(--green);">{{ $stats['completed'] }}</div><div class="stat-lbl">Completed</div></div>
        </div>
    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('repair-tickets.index') }}">
        @if(request('ref_type') && request('ref_id'))
            <input type="hidden" name="ref_type" value="{{ request('ref_type') }}">
            <input type="hidden" name="ref_id"   value="{{ request('ref_id') }}">
        @endif
        <div class="rt-toolbar">
            <div class="tb-search">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by description…">
            </div>
            <select name="status" class="tb-select" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <option value="pending"    {{ request('status')==='pending'    ? 'selected':'' }}>🟡 Scheduled</option>
                <option value="en_process" {{ request('status')==='en_process' ? 'selected':'' }}>🔵 In Progress</option>
                <option value="completed"  {{ request('status')==='completed'  ? 'selected':'' }}>🟢 Completed</option>
            </select>
            <button type="submit" class="btn-tb btn-tb-primary"><i class="bi bi-search"></i> Search</button>
            @if(request('status') || request('search'))
                <a href="{{ route('repair-tickets.index', array_filter(['ref_type'=>request('ref_type'),'ref_id'=>request('ref_id')])) }}"
                   class="btn-tb btn-tb-ghost"><i class="bi bi-x-lg"></i> Clear</a>
            @endif
        </div>
    </form>

    {{-- Ticket list --}}
    @forelse($tickets as $ticket)
        @php
            $statusKey   = $ticket->status;
            $statusLabel = match($statusKey) {
                'pending'    => 'Scheduled',
                'en_process' => 'In Progress',
                'completed'  => 'Completed',
                default      => ucfirst(str_replace('_',' ',$statusKey)),
            };
            $statusIcon = match($statusKey) {
                'pending'    => 'bi-clock',
                'en_process' => 'bi-tools',
                'completed'  => 'bi-check-circle-fill',
                default      => 'bi-circle',
            };
            $fotosAdmin  = $ticket->fotosAdmin ?? collect();
            $fotosCrew   = $ticket->fotosCrew  ?? collect();
            $adminCount  = $fotosAdmin->count();
            $crewCount   = $fotosCrew->count();
            $photoCount  = $adminCount + $crewCount;
            $showAdmin   = $fotosAdmin->take(4);
            $remaining   = 4 - $showAdmin->count();
            $showCrew    = $remaining > 0 ? $fotosCrew->take($remaining) : collect();
            $extraPhotos = max(0, $photoCount - 4);
        @endphp

        <div class="ticket-card" id="ticket-{{ $ticket->id }}">
            <div class="tc-header">
                <div class="tc-left">
                    @if($ticket->reference_type === 'job' && $ticket->jobRequest)
                        <a href="{{ route('jobs.show', $ticket->reference_id) }}" class="tc-ref">
                            <i class="bi bi-briefcase"></i> {{ $ticket->jobRequest->job_number_name }}
                        </a>
                    @elseif($ticket->reference_type === 'emergency' && $ticket->emergency)
                        <a href="{{ route('emergency.show', $ticket->reference_id) }}" class="tc-ref emerg">
                            <i class="bi bi-exclamation-octagon"></i> {{ $ticket->emergency->job_number_name }}
                        </a>
                    @endif
                    <span class="tc-id">RT-{{ str_pad($ticket->id,4,'0',STR_PAD_LEFT) }}</span>
                    <span class="tc-date"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($ticket->repair_date)->format('M d, Y') }}</span>
                </div>
                <div class="tc-right">
                    <span class="s-badge s-{{ $statusKey }}"><i class="bi {{ $statusIcon }}"></i> {{ $statusLabel }}</span>
                    <div class="tc-actions">
                        <a href="{{ route('repair-tickets.edit', $ticket->id) }}" class="btn-sm btn-sm-edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn-sm btn-sm-del"
                                onclick="confirmDelete({{ $ticket->id }}, '{{ addslashes($ticket->description) }}')">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="tc-body">
                <p class="tc-desc">{{ $ticket->description }}</p>
                <div class="tc-meta">
                    <span class="meta-pill"><i class="bi bi-clock-history" style="color:var(--ink4);font-size:.65rem;"></i> {{ $ticket->created_at->diffForHumans() }}</span>
                    @if($adminCount)
                        <span class="meta-pill" style="background:#fff3e0;border-color:#ffcc80;color:#c2410c;">
                            <i class="bi bi-camera-fill" style="font-size:.65rem;"></i> {{ $adminCount }} damage
                        </span>
                    @endif
                    @if($crewCount)
                        <span class="meta-pill" style="background:var(--cyan-bg);border-color:var(--cyan-bd);color:var(--cyan);">
                            <i class="bi bi-person-fill-gear" style="font-size:.65rem;"></i> {{ $crewCount }} work
                        </span>
                    @endif
                    <span class="meta-pill" style="{{ $ticket->reference_type==='emergency' ? 'background:#fff1f2;border-color:#fca5a5;color:#dc2626;' : 'background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8;' }}">
                        <i class="bi {{ $ticket->reference_type==='emergency' ? 'bi-exclamation-octagon' : 'bi-briefcase' }}" style="font-size:.65rem;"></i>
                        {{ ucfirst($ticket->reference_type) }}
                    </span>
                </div>

                @if($photoCount)
                    <div class="tc-photos">
                        @foreach($showAdmin as $foto)
                            @php
                                $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url);
                                $ext = strtolower(pathinfo($foto->url, PATHINFO_EXTENSION));
                            @endphp
                            @if(in_array($ext,['jpg','jpeg','png','gif','webp']))
                                <div class="ph-wrap">
                                    <a href="{{ $url }}" target="_blank">
                                        <img src="{{ $url }}" alt="Damage" class="ph-thumb admin-thumb">
                                    </a>
                                    <span class="ph-badge admin">dmg</span>
                                </div>
                            @else
                                <a href="{{ $url }}" target="_blank" class="ph-pdf" style="border-color:#ffb347;">
                                    <i class="bi bi-file-pdf-fill" style="font-size:1.2rem;color:#ef4444;"></i><span>PDF</span>
                                </a>
                            @endif
                        @endforeach
                        @foreach($showCrew as $foto)
                            @php
                                $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url);
                                $ext = strtolower(pathinfo($foto->url, PATHINFO_EXTENSION));
                            @endphp
                            @if(in_array($ext,['jpg','jpeg','png','gif','webp']))
                                <div class="ph-wrap">
                                    <a href="{{ $url }}" target="_blank">
                                        <img src="{{ $url }}" alt="Work" class="ph-thumb crew-thumb">
                                    </a>
                                    <span class="ph-badge crew">work</span>
                                </div>
                            @else
                                <a href="{{ $url }}" target="_blank" class="ph-pdf" style="border-color:var(--cyan-bd);">
                                    <i class="bi bi-file-pdf-fill" style="font-size:1.2rem;color:#ef4444;"></i><span>PDF</span>
                                </a>
                            @endif
                        @endforeach
                        @if($extraPhotos > 0)
                            <div class="ph-more">
                                <span>+{{ $extraPhotos }}</span>
                                <span style="font-size:.58rem;font-weight:600;opacity:.7;">more</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if(!$loop->last)<div class="tl-connector"><div class="tl-line"></div></div>@endif

    @empty
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-tools"></i></div>
            <div class="empty-title">No repair tickets found</div>
            <div class="empty-sub">
                @if(request('status') || request('search')) No tickets match your current filters.
                @elseif(request('ref_type') && request('ref_id')) No repair tickets for this project yet.
                @else Use the New Ticket button to get started. @endif
            </div>
            @if(request('status') || request('search'))
                <a href="{{ route('repair-tickets.index', array_filter(['ref_type'=>request('ref_type'),'ref_id'=>request('ref_id')])) }}" class="btn-empty"><i class="bi bi-x-circle"></i> Clear filters</a>
            @else
                <button type="button" class="btn-empty" onclick="openRtDrawer()"><i class="bi bi-plus-lg"></i> New Ticket</button>
            @endif
        </div>
    @endforelse

    @if($tickets->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $tickets->withQueryString()->links() }}</div>
    @endif

</div>
</div>

{{-- ══ DRAWER ══ --}}
<div class="rt-drawer" id="rtDrawer">
    <div class="rt-drawer-head">
        <div style="display:flex;align-items:center;gap:.7rem;">
            <div class="rt-drawer-icon"><i class="bi bi-tools" style="color:#67e8f9;font-size:.95rem;"></i></div>
            <div>
                <div class="rt-drawer-title">New</div>
                <div class="rt-drawer-name">Repair Ticket</div>
            </div>
        </div>
        <button type="button" class="rt-drawer-close" onclick="closeRtDrawer()"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="rt-ctx-bar">
        <i class="bi bi-geo-alt-fill" style="color:rgba(255,255,255,.3);font-size:.65rem;flex-shrink:0;"></i>
        <span id="rtCtxText">No project selected</span>
        <span class="rt-ctx-pill none" id="rtCtxPill">—</span>
    </div>

    <div class="rt-drawer-body">
        @if(session('repair_success'))
            <div style="background:var(--green-bg);border:1.5px solid var(--green-bd);border-radius:10px;padding:.7rem .95rem;display:flex;align-items:center;gap:.55rem;font-size:.78rem;color:#065f46;">
                <i class="bi bi-check-circle-fill"></i> {{ session('repair_success') }}
            </div>
        @endif

        <form action="{{ route('repair-tickets.store') }}" method="POST" enctype="multipart/form-data" id="rtForm">
            @csrf
            {{-- Default status: pending (Scheduled) ── --}}
            <input type="hidden" name="status" value="pending">

            {{-- Step 1 --}}
            <div>
                <div class="rt-step-head"><div class="rt-step-num"><span>1</span></div><span class="rt-step-label">Project</span></div>

                @if(request('ref_type') && request('ref_id'))
                    @php
                        $fixedType    = request('ref_type');
                        $fixedId      = (int) request('ref_id');
                        $fixedModel   = $fixedType === 'job' ? \App\Models\JobRequest::find($fixedId) : \App\Models\Emergencies::find($fixedId);
                        $fixedLabel   = $fixedModel?->job_number_name ?? '—';
                        $fixedCompany = $fixedModel?->company_name ?? '';
                        $fixedAddr    = $fixedType === 'job'
                            ? collect([$fixedModel?->job_address_street_address,$fixedModel?->job_address_city,$fixedModel?->job_address_state])->filter()->implode(', ')
                            : collect([$fixedModel?->job_address,$fixedModel?->job_city,$fixedModel?->job_state])->filter()->implode(', ');
                    @endphp
                    <input type="hidden" name="reference_type" value="{{ $fixedType }}">
                    <input type="hidden" name="reference_id"   value="{{ $fixedId }}">
                    <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem 1rem;border-radius:11px;border:1.5px solid {{ $fixedType === 'emergency' ? '#fca5a5' : 'var(--cyan-bd)' }};background:{{ $fixedType === 'emergency' ? '#fff1f2' : 'var(--cyan-bg)' }};">
                        <div style="width:34px;height:34px;border-radius:9px;background:{{ $fixedType === 'emergency' ? 'rgba(220,38,38,.1)' : 'rgba(8,145,178,.12)' }};border:1px solid {{ $fixedType === 'emergency' ? '#fca5a5' : 'var(--cyan-bd)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi {{ $fixedType === 'emergency' ? 'bi-exclamation-octagon' : 'bi-briefcase' }}" style="font-size:.85rem;color:{{ $fixedType === 'emergency' ? '#dc2626' : 'var(--cyan)' }};"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.82rem;font-weight:700;color:var(--ink);">{{ $fixedLabel }}</div>
                            @if($fixedCompany)<div style="font-size:.68rem;color:var(--ink3);margin-top:1px;">{{ $fixedCompany }}</div>@endif
                            @if($fixedAddr)<div style="font-size:.65rem;color:var(--ink4);margin-top:1px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $fixedAddr }}</div>@endif
                        </div>
                        <span style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:.2rem .6rem;border-radius:99px;background:{{ $fixedType === 'emergency' ? 'rgba(220,38,38,.12)' : 'rgba(8,145,178,.12)' }};color:{{ $fixedType === 'emergency' ? '#dc2626' : 'var(--cyan)' }};border:1px solid {{ $fixedType === 'emergency' ? '#fca5a5' : 'var(--cyan-bd)' }};flex-shrink:0;">
                            {{ $fixedType === 'emergency' ? 'Emergency' : 'Job' }}
                        </span>
                    </div>
                @else
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.6rem;">
                        <div>
                            <label class="rt-label">Type</label>
                            <select name="reference_type" id="rtRefType" class="rt-ctrl" onchange="rtLoadRefs(this.value)">
                                <option value="" disabled selected>Select…</option>
                                <option value="job">Job Request</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>
                        <div>
                            <label class="rt-label">Job / Emergency</label>
                            <select name="reference_id" id="rtRefId" class="rt-ctrl" onchange="rtShowPreview(this)" disabled>
                                <option value="">Select type first…</option>
                            </select>
                        </div>
                    </div>
                    <div class="proj-preview" id="rtProjPreview">
                        <div class="proj-preview-icon" id="rtProjIcon" style="background:rgba(8,145,178,.1);border:1px solid var(--cyan-bd);color:var(--cyan);"><i class="bi bi-briefcase"></i></div>
                        <div style="flex:1;min-width:0;">
                            <div class="proj-preview-num" id="rtProjNum"></div>
                            <div class="proj-preview-addr" id="rtProjAddr"></div>
                        </div>
                    </div>
                @endif
            </div>

            <hr class="rt-divider">

            {{-- Step 2 --}}
            <div>
                <div class="rt-step-head"><div class="rt-step-num"><span>2</span></div><span class="rt-step-label">Schedule</span></div>
                <label class="rt-label">Repair Date</label>
                <input type="date" name="repair_date" class="rt-ctrl" value="{{ date('Y-m-d') }}" required>
            </div>

            <hr class="rt-divider">

            {{-- Step 3 --}}
            <div>
                <div class="rt-step-head"><div class="rt-step-num"><span>3</span></div><span class="rt-step-label">Damage Description</span></div>
                <textarea name="description" class="rt-ctrl" rows="4" placeholder="Describe the damage or issue found in detail…" required></textarea>
            </div>

            <hr class="rt-divider">

            {{-- Step 4 --}}
            <div>
                <div class="rt-step-head">
                    <div class="rt-step-num"><span>4</span></div>
                    <span class="rt-step-label">Photos <span style="font-size:.65rem;font-weight:400;text-transform:none;letter-spacing:0;color:var(--ink4);margin-left:4px;">(optional)</span></span>
                </div>
                <div class="drop-zone" id="rtDropZone"
                     onclick="document.getElementById('rtFileInput').click()"
                     ondragover="rtDragOver(event)" ondragleave="rtDragLeave(event)" ondrop="rtDrop(event)">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <div class="drop-zone-title">Click or drag photos here</div>
                    <div class="drop-zone-sub">JPG, PNG, WEBP, PDF — multiple files allowed</div>
                    <input type="file" id="rtFileInput" name="photos[]" accept="image/*,.pdf" multiple onchange="rtHandleFiles(this.files)">
                </div>
                <div id="rtPreviewGrid" class="preview-grid"></div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:.7rem;border-top:1.5px solid var(--line);margin-top:.1rem;">
                <button type="button" class="btn-ghost" onclick="closeRtDrawer()" style="font-size:.72rem;padding:.33rem .75rem;">Cancel</button>
                <button type="submit" class="btn-rt-submit"><i class="bi bi-send-check"></i> Submit Ticket</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete confirm toast --}}
<div class="del-confirm" id="delConfirm">
    <div><i class="bi bi-trash3-fill" style="color:#f87171;font-size:1.1rem;"></i></div>
    <div class="del-confirm-txt">Delete this ticket?<span id="delConfirmDesc"></span></div>
    <button type="button" class="btn-del-no" onclick="cancelDelete()">Cancel</button>
    <button type="button" class="btn-del-yes" onclick="executeDelete()">Delete</button>
</div>
<form id="delForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<script>
const _refCache = {};
let   rtFiles   = [];
let   _delTicketId = null;

function openRtDrawer()  { document.getElementById('rtDrawer').classList.add('open'); document.body.style.overflow='hidden'; }
function closeRtDrawer() { document.getElementById('rtDrawer').classList.remove('open'); document.body.style.overflow=''; }
document.addEventListener('keydown', e => { if(e.key==='Escape'){ closeRtDrawer(); cancelDelete(); } });

async function rtLoadRefs(type, preSelectId=null) {
    const sel = document.getElementById('rtRefId');
    if (!sel) return;
    sel.disabled = true; sel.innerHTML = '<option value="">Loading…</option>';
    updateCtxBar(type, null);
    document.getElementById('rtProjPreview').style.display = 'none';
    try {
        if (!_refCache[type]) {
            _refCache[type] = await fetch(`/repair-tickets/references?type=${type}`).then(r=>r.json());
        }
        sel.innerHTML = '<option value="" disabled selected>Select…</option>';
        _refCache[type].forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id; opt.textContent = item.label;
            opt.dataset.company = item.company ?? ''; opt.dataset.address = item.address ?? '';
            sel.appendChild(opt);
        });
        sel.disabled = false;
        if (preSelectId) { sel.value = preSelectId; rtShowPreview(sel); }
    } catch(e) { sel.innerHTML = '<option value="">Error loading</option>'; }
}

function rtShowPreview(sel) {
    const opt = sel.options[sel.selectedIndex];
    const preview = document.getElementById('rtProjPreview');
    const type = document.getElementById('rtRefType')?.value;
    if (!opt || !opt.value) { preview.style.display='none'; return; }
    document.getElementById('rtProjNum').textContent  = opt.textContent;
    document.getElementById('rtProjAddr').textContent = [opt.dataset.company,opt.dataset.address].filter(Boolean).join(' · ');
    const icon = document.getElementById('rtProjIcon');
    if (type === 'emergency') {
        preview.classList.add('emerg-preview');
        icon.style.cssText = 'background:#fff1f2;border:1px solid #fca5a5;color:#dc2626;';
        icon.innerHTML = '<i class="bi bi-exclamation-octagon" style="font-size:.75rem;"></i>';
    } else {
        preview.classList.remove('emerg-preview');
        icon.style.cssText = 'background:rgba(8,145,178,.1);border:1px solid var(--cyan-bd);color:var(--cyan);';
        icon.innerHTML = '<i class="bi bi-briefcase" style="font-size:.75rem;"></i>';
    }
    preview.style.display = 'flex';
    updateCtxBar(type, opt.textContent, opt.dataset.company, opt.dataset.address);
}

function updateCtxBar(type, label, company, address) {
    const text = document.getElementById('rtCtxText');
    const pill = document.getElementById('rtCtxPill');
    if (!label) { text.textContent='No project selected'; pill.className='rt-ctx-pill none'; pill.textContent='—'; return; }
    text.textContent = [label,company,address].filter(Boolean).join(' · ');
    pill.className   = type==='emergency' ? 'rt-ctx-pill emerg' : 'rt-ctx-pill job';
    pill.textContent = type==='emergency' ? 'Emergency' : 'Job';
}

function confirmDelete(id, desc) {
    _delTicketId = id;
    document.getElementById('delConfirmDesc').textContent = desc.length > 60 ? desc.slice(0,60)+'…' : desc;
    document.getElementById('delConfirm').classList.add('show');
}
function cancelDelete()  { _delTicketId = null; document.getElementById('delConfirm').classList.remove('show'); }
function executeDelete() { if (!_delTicketId) return; const f=document.getElementById('delForm'); f.action=`/repair-tickets/${_delTicketId}`; f.submit(); }

function rtDragOver(e)  { e.preventDefault(); const z=document.getElementById('rtDropZone'); z.style.borderColor='var(--cyan)'; z.style.background='var(--cyan-bg)'; }
function rtDragLeave(e) { if(!rtFiles.filter(Boolean).length){const z=document.getElementById('rtDropZone');z.style.borderColor='';z.style.background='';} }
function rtDrop(e)      { e.preventDefault(); rtHandleFiles(e.dataTransfer.files); }
function rtHandleFiles(incoming) {
    Array.from(incoming).forEach(file => { rtFiles.push(file); rtRenderPreview(file, rtFiles.length-1); });
    rtSyncInput();
    const z=document.getElementById('rtDropZone'); z.style.borderColor='var(--cyan)'; z.style.background='var(--cyan-bg)';
}
function rtRenderPreview(file, index) {
    const grid=document.getElementById('rtPreviewGrid'); grid.style.display='flex';
    const wrap=document.createElement('div'); wrap.id='rt-prev-'+index; wrap.className='preview-thumb';
    if(file.type==='application/pdf'){
        wrap.innerHTML=`<div class="preview-thumb-pdf"><i class="bi bi-file-pdf-fill" style="font-size:1.2rem;color:#ef4444;"></i><span style="font-size:8px;font-weight:600;color:var(--ink3);width:90%;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${file.name}</span></div>`;
    }else{
        const img=document.createElement('img'); img.src=URL.createObjectURL(file); wrap.appendChild(img);
    }
    const btn=document.createElement('button'); btn.type='button'; btn.className='btn-thumb-remove'; btn.innerHTML='&times;'; btn.onclick=()=>rtRemoveFile(index);
    wrap.appendChild(btn); grid.appendChild(wrap);
}
function rtRemoveFile(index) {
    rtFiles[index]=null;
    const el=document.getElementById('rt-prev-'+index); if(el)el.remove();
    if(rtFiles.every(f=>f===null)){const z=document.getElementById('rtDropZone');z.style.borderColor='';z.style.background='';document.getElementById('rtPreviewGrid').style.display='none';}
    rtSyncInput();
}
function rtSyncInput() {
    const dt=new DataTransfer(); rtFiles.filter(Boolean).forEach(f=>dt.items.add(f));
    document.getElementById('rtFileInput').files=dt.files;
}
</script>
@endsection