@extends('admin.layouts.superadmin')

@section('title', 'Dashboard')

@section('content')

@php
    $offersTotal   = $offersAssigned + $offersUnassigned;
    $pctAssigned   = $offersTotal ? round(($offersAssigned   / $offersTotal) * 100) : 0;
    $pctUnassigned = 100 - $pctAssigned;
@endphp

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.db { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1540px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --pur:  #7c22e8; --plt:  #f5f0ff; --pbd:  #ddd0fb;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.db-hero {
    position: relative; border-radius: var(--rxl);
    padding: 34px 40px; margin-bottom: 28px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.db-hero-glow {
    position: absolute; pointer-events: none;
    width: 700px; height: 340px;
    background: radial-gradient(ellipse, rgba(24,85,224,.35) 0%, transparent 70%);
    right: -80px; top: -80px;
}
.db-hero-glow2 {
    position: absolute; pointer-events: none;
    width: 300px; height: 200px;
    background: radial-gradient(ellipse, rgba(124,34,232,.2) 0%, transparent 70%);
    left: 30%; bottom: -40px;
}
.db-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#4f80ff 0%,#1855e0 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.db-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.db-hero-left { position: relative; }
.db-hero-eyebrow {
    font-size: 10.5px; font-weight: 700; color: rgba(255,255,255,.35);
    text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 8px;
    display: flex; align-items: center; gap: 7px;
}
.db-hero-eyebrow::before {
    content: ''; width: 18px; height: 2px;
    background: #4f80ff; border-radius: 2px; display: inline-block;
}
.db-hero-title {
    font-size: 28px; font-weight: 800; color: #fff;
    letter-spacing: -.6px; line-height: 1; margin-bottom: 7px;
}
.db-hero-sub { font-size: 13px; color: rgba(255,255,255,.38); font-weight: 500; }
.db-hero-right { position: relative; display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.db-hero-chip {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px; padding: 12px 20px; text-align: center; min-width: 80px;
}
.db-hero-chip-n { font-size: 24px; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -.5px; }
.db-hero-chip-l { font-size: 10px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }

/* ── SECTION LABEL ── */
.db-section-label {
    font-size: 10.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 14px; display: flex; align-items: center; gap: 10px;
}
.db-section-label::after { content: ''; flex: 1; height: 1px; background: var(--bd); }

/* ── MAIN GRID ── */
.db-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }
.db-grid-bottom { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

/* ── STAT CARD ── */
.db-card {
    background: var(--surf);
    border: 1px solid var(--bd);
    border-radius: var(--rxl);
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.db-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,.08);
    transform: translateY(-1px);
}
.db-card-top {
    padding: 20px 22px 16px;
    border-bottom: 1px solid var(--bd2);
    display: flex; align-items: flex-start; justify-content: space-between;
}
.db-card-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 17px;
}
.db-card-icon.blue { background: var(--blt); color: var(--blue); }
.db-card-icon.grn  { background: var(--glt); color: var(--grn); }
.db-card-icon.pur  { background: var(--plt); color: var(--pur); }
.db-card-icon.amb  { background: var(--alt); color: var(--amb); }
.db-card-label {
    font-size: 10.5px; font-weight: 700; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .7px; margin-bottom: 5px;
}
.db-card-num {
    font-size: 36px; font-weight: 800; color: var(--ink);
    letter-spacing: -1px; line-height: 1;
}
.db-card-body { padding: 16px 22px; }
.db-card-foot {
    padding: 13px 22px;
    border-top: 1px solid var(--bd2);
    display: flex; align-items: center; justify-content: space-between;
    background: #fafbfd;
}

/* ── GROWTH STRIP ── */
.db-growth {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-radius: var(--rlg);
    border: 1px solid var(--bd2); background: var(--bg);
    margin-bottom: 12px;
}
.db-growth-left { font-size: 12px; font-weight: 600; color: var(--ink3); }
.db-growth-val  { font-size: 18px; font-weight: 800; color: var(--ink); }
.db-growth-badge {
    font-size: 11px; font-weight: 700; padding: 3px 9px;
    border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;
}
.db-growth-badge.up  { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.db-growth-badge.flat{ background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }

/* ── PILL STATUS ── */
.db-pill {
    font-size: 10.5px; font-weight: 700; padding: 4px 10px;
    border-radius: 9999px; display: inline-flex; align-items: center; gap: 5px;
    text-transform: uppercase; letter-spacing: .4px;
}
.db-pill.blue { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.db-pill.grn  { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.db-pill.amb  { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.db-pill.red  { background: var(--rlt); color: var(--red); border: 1px solid var(--rbd); animation: pulse-red 2s infinite; }
.db-pill.pur  { background: var(--plt); color: var(--pur); border: 1px solid var(--pbd); }
@keyframes pulse-red {
    0%,100% { box-shadow: 0 0 0 0 rgba(217,38,38,.3); }
    50%      { box-shadow: 0 0 0 5px rgba(217,38,38,.0); }
}

/* ── VIEW LINK ── */
.db-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; text-decoration: none;
    transition: gap .15s;
}
.db-link:hover { gap: 9px; }
.db-link.blue { color: var(--blue); }
.db-link.grn  { color: var(--grn); }
.db-link.pur  { color: var(--pur); }
.db-link.amb  { color: var(--amb); }

/* ── MINI STATS (offers) ── */
.db-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px; }
.db-mini {
    padding: 12px 14px; border-radius: var(--rlg); border: 1px solid var(--bd2);
}
.db-mini.grn { background: var(--glt); border-color: var(--gbd); }
.db-mini.amb { background: var(--alt); border-color: var(--abd); }
.db-mini-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.db-mini.grn .db-mini-label { color: var(--grn); }
.db-mini.amb .db-mini-label { color: var(--amb); }
.db-mini-num  { font-size: 22px; font-weight: 800; color: var(--ink); letter-spacing: -.5px; line-height: 1; }
.db-mini-pct  { font-size: 11px; font-weight: 600; margin-top: 2px; }
.db-mini.grn .db-mini-pct { color: var(--grn); }
.db-mini.amb .db-mini-pct { color: var(--amb); }

/* ── PROGRESS BAR ── */
.db-prog-wrap { margin-bottom: 4px; }
.db-prog-labels { display: flex; justify-content: space-between; font-size: 11px; font-weight: 700; color: var(--ink3); margin-bottom: 7px; }
.db-prog-track {
    height: 7px; border-radius: 9999px; background: var(--bd2); overflow: hidden;
}
.db-prog-fill {
    height: 100%; border-radius: 9999px;
    background: linear-gradient(90deg, var(--grn), #34d399);
    transition: width 1s cubic-bezier(.4,0,.2,1);
}

/* ── PENDING CARD ── */
.db-pending-inner {
    display: flex; align-items: center; gap: 16px;
    padding: 20px 22px; border-bottom: 1px solid var(--bd2);
}
.db-pending-num { font-size: 48px; font-weight: 800; color: var(--ink); letter-spacing: -2px; line-height: 1; }
.db-pending-lbl { font-size: 12px; font-weight: 600; color: var(--ink3); margin-top: 3px; }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 1024px) {
    .db-grid { grid-template-columns: 1fr 1fr; }
    .db-grid-bottom { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .db { padding: 16px; }
    .db-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .db-grid { grid-template-columns: 1fr; }
}
</style>

<div class="db">

    {{-- ── HERO ── --}}
    <div class="db-hero">
        <div class="db-hero-glow"></div>
        <div class="db-hero-glow2"></div>
        <div class="db-hero-accent"></div>
        <div class="db-hero-grid"></div>

        <div class="db-hero-left">
            <div class="db-hero-eyebrow">Operations Overview</div>
            <div class="db-hero-title">Dashboard</div>
            <div class="db-hero-sub">Platform summary and recent activity</div>
        </div>

        <div class="db-hero-right">
            <div class="db-hero-chip">
                <div class="db-hero-chip-n">{{ $contractors }}</div>
                <div class="db-hero-chip-l">Contractors</div>
            </div>
            <div class="db-hero-chip">
                <div class="db-hero-chip-n">{{ $subcontractors }}</div>
                <div class="db-hero-chip-l">Crews</div>
            </div>
            <div class="db-hero-chip">
                <div class="db-hero-chip-n">{{ $offersTotal }}</div>
                <div class="db-hero-chip-l">Offers</div>
            </div>
            @if($pendingUsers > 0)
            <div class="db-hero-chip" style="border-color:rgba(217,38,38,.4);background:rgba(217,38,38,.1)">
                <div class="db-hero-chip-n" style="color:#f87171">{{ $pendingUsers }}</div>
                <div class="db-hero-chip-l" style="color:rgba(248,113,113,.6)">Pending</div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── MAIN CARDS ── --}}
    <div class="db-section-label">Key Metrics</div>

    <div class="db-grid">

        {{-- ── CONTRACTORS ── --}}
        <div class="db-card">
            <div class="db-card-top">
                <div>
                    <div class="db-card-label">Total Contractors</div>
                    <div class="db-card-num">{{ $contractors }}</div>
                </div>
                <div class="db-card-icon blue">
                    <i class="fas fa-hard-hat"></i>
                </div>
            </div>
            <div class="db-card-body">
                <div class="db-growth">
                    <div>
                        <div class="db-growth-left">New this month</div>
                        <div class="db-growth-val">{{ $contractorsLastMonth }}</div>
                    </div>
                    @if($growthContractors > 0)
                        <span class="db-growth-badge up">
                            <i class="fas fa-arrow-up" style="font-size:9px"></i> +{{ $growthContractors }}%
                        </span>
                    @else
                        <span class="db-growth-badge flat">{{ $growthContractors }}%</span>
                    @endif
                </div>
                <span class="db-pill blue">
                    <i class="fas fa-users" style="font-size:9px"></i> Approved
                </span>
            </div>
            <div class="db-card-foot">
                <a href="{{ route('superadmin.users.contractors') }}" class="db-link blue">
                    <i class="fas fa-arrow-right" style="font-size:10px"></i> View all
                </a>
                <span style="font-size:11px;font-weight:600;color:var(--ink3)">Last 30 days</span>
            </div>
        </div>

        {{-- ── CREW MANAGERS ── --}}
        <div class="db-card">
            <div class="db-card-top">
                <div>
                    <div class="db-card-label">Total Crew Managers</div>
                    <div class="db-card-num">{{ $subcontractors }}</div>
                </div>
                <div class="db-card-icon grn">
                    <i class="fas fa-people-carry-box"></i>
                </div>
            </div>
            <div class="db-card-body">
                <div class="db-growth">
                    <div>
                        <div class="db-growth-left">New this month</div>
                        <div class="db-growth-val">{{ $subcontractorsLastMonth }}</div>
                    </div>
                    @if($growthSubcontractors > 0)
                        <span class="db-growth-badge up">
                            <i class="fas fa-arrow-up" style="font-size:9px"></i> +{{ $growthSubcontractors }}%
                        </span>
                    @else
                        <span class="db-growth-badge flat">{{ $growthSubcontractors }}%</span>
                    @endif
                </div>
                <span class="db-pill grn">
                    <i class="fas fa-user-gear" style="font-size:9px"></i> Active crews
                </span>
            </div>
            <div class="db-card-foot">
                <a href="{{ route('superadmin.subcontractors.index') }}" class="db-link grn">
                    <i class="fas fa-arrow-right" style="font-size:10px"></i> View all
                </a>
                <span style="font-size:11px;font-weight:600;color:var(--ink3)">Last 30 days</span>
            </div>
        </div>

        {{-- ── OFFERS ── --}}
        <div class="db-card">
            <div class="db-card-top">
                <div>
                    <div class="db-card-label">Total Offers</div>
                    <div class="db-card-num">{{ $offersTotal }}</div>
                </div>
                <div class="db-card-icon pur">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
            <div class="db-card-body">

                <div class="db-growth" style="margin-bottom:10px">
                    <div>
                        <div class="db-growth-left">New this month</div>
                        <div class="db-growth-val">{{ $offersLastMonth }}</div>
                    </div>
                    @if($growthOffers > 0)
                        <span class="db-growth-badge up">
                            <i class="fas fa-arrow-up" style="font-size:9px"></i> +{{ $growthOffers }}%
                        </span>
                    @else
                        <span class="db-growth-badge flat">{{ $growthOffers }}%</span>
                    @endif
                </div>

                <div class="db-mini-grid">
                    <div class="db-mini grn">
                        <div class="db-mini-label">Assigned</div>
                        <div class="db-mini-num">{{ $offersAssigned }}</div>
                        <div class="db-mini-pct">{{ $pctAssigned }}%</div>
                    </div>
                    <div class="db-mini amb">
                        <div class="db-mini-label">Pending</div>
                        <div class="db-mini-num">{{ $offersUnassigned }}</div>
                        <div class="db-mini-pct">{{ $pctUnassigned }}%</div>
                    </div>
                </div>

                <div class="db-prog-wrap">
                    <div class="db-prog-labels">
                        <span>Assignment progress</span>
                        <span>{{ $pctAssigned }}%</span>
                    </div>
                    <div class="db-prog-track">
                        <div class="db-prog-fill" style="width:{{ $pctAssigned }}%"></div>
                    </div>
                </div>

            </div>
            <div class="db-card-foot">
                <a href="{{ route('superadmin.calendar.index') }}" class="db-link pur">
                    <i class="fas fa-arrow-right" style="font-size:10px"></i> View calendar
                </a>
                <span class="db-pill pur" style="font-size:10px;padding:3px 8px">
                    <i class="fas fa-calendar" style="font-size:9px"></i> Jobs + Emergencies
                </span>
            </div>
        </div>

    </div>

    {{-- ── BOTTOM ROW ── --}}
    <div class="db-section-label" style="margin-top:8px">Requires Attention</div>

    <div class="db-grid-bottom">

        {{-- ── PENDING APPROVAL ── --}}
        <div class="db-card">
            <div class="db-pending-inner">
                <div>
                    <div class="db-card-label" style="margin-bottom:6px">Users Pending Approval</div>
                    <div class="db-pending-num">{{ $pendingUsers }}</div>
                    <div class="db-pending-lbl">Awaiting review</div>
                </div>
                <div style="margin-left:auto">
                    @if($pendingUsers > 0)
                        <span class="db-pill red">
                            <i class="fas fa-circle" style="font-size:6px"></i> Action required
                        </span>
                    @else
                        <span class="db-pill grn">
                            <i class="fas fa-check-circle" style="font-size:9px"></i> All approved
                        </span>
                    @endif
                </div>
            </div>
            <div class="db-card-foot">
                <a href="{{ route('superadmin.users.pending') }}" class="db-link amb">
                    <i class="fas fa-arrow-right" style="font-size:10px"></i> Review users
                </a>
                <div style="width:36px;height:36px;border-radius:9px;background:var(--alt);border:1px solid var(--abd);display:flex;align-items:center;justify-content:center;color:var(--amb);font-size:15px">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
        </div>

        {{-- ── UNASSIGNED QUICK VIEW ── --}}
        <div class="db-card">
            <div class="db-card-top" style="border-bottom:none;padding-bottom:10px">
                <div>
                    <div class="db-card-label">Unassigned Offers</div>
                    <div class="db-card-num">{{ $offersUnassigned }}</div>
                </div>
                <div class="db-card-icon amb">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
            <div class="db-card-body" style="padding-top:0">
                <div class="db-mini-grid">
                    <div class="db-mini" style="background:var(--blt);border-color:var(--bbd)">
                        <div class="db-mini-label" style="color:var(--blue)">Jobs</div>
                        <div class="db-mini-num">
                            {{ \App\Models\JobRequest::where(function($q){$q->whereNull('crew_id')->orWhere('crew_id',0);})->count() }}
                        </div>
                    </div>
                    <div class="db-mini" style="background:var(--rlt);border-color:var(--rbd)">
                        <div class="db-mini-label" style="color:var(--red)">Emergencies</div>
                        <div class="db-mini-num">
                            {{ \App\Models\Emergencies::where(function($q){$q->whereNull('crew_id')->orWhere('crew_id',0);})->count() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="db-card-foot">
                <a href="{{ route('superadmin.calendar.index') }}" class="db-link amb">
                    <i class="fas fa-arrow-right" style="font-size:10px"></i> Assign crews
                </a>
                <span style="font-size:11px;font-weight:600;color:var(--ink3)">Needs assignment</span>
            </div>
        </div>

    </div>

</div>

@endsection