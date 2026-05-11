@extends('admin.layouts.superadmin')
@section('title', 'Photo Projects')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.pp { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1540px; }

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
}

/* ── HERO ── */
.pp-hero {
    position: relative; border-radius: var(--rxl);
    padding: 34px 40px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.pp-hero-glow {
    position: absolute; pointer-events: none;
    width: 600px; height: 300px;
    background: radial-gradient(ellipse, rgba(124,34,232,.3) 0%, transparent 70%);
    right: -60px; top: -60px;
}
.pp-hero-glow2 {
    position: absolute; pointer-events: none;
    width: 300px; height: 200px;
    background: radial-gradient(ellipse, rgba(24,85,224,.2) 0%, transparent 70%);
    left: 30%; bottom: -40px;
}
.pp-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#c084fc 0%,#7c22e8 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.pp-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.pp-hero-left { position: relative; display: flex; align-items: center; gap: 18px; }
.pp-hero-badge {
    width: 54px; height: 54px; border-radius: 14px; flex-shrink: 0;
    background: rgba(124,34,232,.2); border: 1px solid rgba(124,34,232,.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #c084fc;
}
.pp-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.pp-hero-sub   { font-size: 12.5px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.pp-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }
.pp-stat-chip {
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px; padding: 12px 18px; text-align: center;
}
.pp-stat-chip-n { font-size: 22px; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -.5px; }
.pp-stat-chip-l { font-size: 10px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }
.pp-stat-chip.cyan { border-color: rgba(8,145,178,.3); }
.pp-stat-chip.cyan .pp-stat-chip-n { color: #67e8f9; }

/* ── SEARCH ── */
.pp-search-wrap {
    display: flex; align-items: center; gap: 8px;
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 10px 16px;
    margin-bottom: 24px; box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.pp-search-ico   { font-size: 14px; color: var(--ink3); flex-shrink: 0; }
.pp-search-input {
    flex: 1; border: none; outline: none;
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: transparent;
}
.pp-search-input::placeholder { color: var(--ink3); }
.pp-search-clear {
    background: none; border: none; cursor: pointer;
    color: var(--ink3); font-size: 13px; padding: 2px 4px; display: none;
}
.pp-search-clear:hover { color: var(--ink); }
.pp-search-count {
    font-size: 12px; font-weight: 700; color: var(--pur);
    background: var(--plt); border: 1px solid var(--pbd);
    border-radius: 9999px; padding: 2px 10px; white-space: nowrap; display: none;
}

/* ── SECTION LABEL ── */
.pp-section-label {
    font-size: 10.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 14px; display: flex; align-items: center; gap: 10px;
}
.pp-section-label::after { content: ''; flex: 1; height: 1px; background: var(--bd); }

/* ── SECTION BLOCK ── */
.pp-section { margin-bottom: 32px; }
.pp-section-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; background: var(--surf);
    border: 1px solid var(--bd); border-radius: var(--rlg) var(--rlg) 0 0;
    border-bottom: none;
}
.pp-section-head-l { display: flex; align-items: center; gap: 10px; }
.pp-section-title  { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.pp-section-sub    { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 1px; }
.pp-section-count  { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 9999px; }
.pp-section-count.blue { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.pp-section-count.red  { background: var(--rlt); color: var(--red);  border: 1px solid var(--rbd); }
.pp-section-count.cyan { background: var(--clt); color: var(--cyan); border: 1px solid var(--cbd); }
.pp-section-icon { width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 15px; }
.pp-section-icon.blue { background: var(--blt); color: var(--blue); }
.pp-section-icon.red  { background: var(--rlt); color: var(--red); }
.pp-section-icon.cyan { background: var(--clt); color: var(--cyan); }

/* ── CARDS GRID ── */
.pp-cards-wrap {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: 0 0 var(--rlg) var(--rlg); padding: 18px;
}
.pp-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }

/* ── PROJECT CARD ── */
.pp-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden;
    transition: box-shadow .15s, transform .15s, border-color .15s;
}
.pp-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.08); transform: translateY(-1px); border-color: var(--bd2); }
.pp-card-top { height: 5px; }
.pp-card-top.blue { background: linear-gradient(90deg, var(--blue), #5b8af7); }
.pp-card-top.red  { background: linear-gradient(90deg, var(--red),  #f87171); }
.pp-card-top.cyan { background: linear-gradient(90deg, #0c4a6e, var(--cyan)); }
.pp-card-body { padding: 16px; }
.pp-card-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 800; padding: 3px 9px;
    border-radius: 9999px; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 12px;
}
.pp-card-pill.blue { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.pp-card-pill.red  { background: var(--rlt); color: var(--red);  border: 1px solid var(--rbd); }
.pp-card-pill.cyan { background: var(--clt); color: var(--cyan); border: 1px solid var(--cbd); }

/* Status mini badge */
.pp-card-status {
    float: right; font-size: 10px; font-weight: 700; padding: 2px 8px;
    border-radius: 9999px; margin-bottom: 12px;
}
.pp-card-status.open        { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.pp-card-status.in_progress { background: var(--plt); color: var(--pur); border: 1px solid var(--pbd); }
.pp-card-status.resolved    { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.pp-card-status.closed      { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }

.pp-card-title   { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; clear: both; }
.pp-card-job-num { font-size: 11px; font-weight: 700; color: var(--ink3); margin-bottom: 12px; }
.pp-card-meta    { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
.pp-card-meta-row{ display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 500; color: var(--ink2); }
.pp-card-meta-row i { color: var(--ink3); font-size: 10.5px; width: 12px; text-align: center; }
.pp-card-foot    { display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid var(--bd2); }
.pp-card-updated { font-size: 11px; font-weight: 500; color: var(--ink3); }

/* Photo count pill */
.pp-card-photos {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; padding: 2px 8px;
    border-radius: 9999px; background: var(--clt); color: var(--cyan);
    border: 1px solid var(--cbd); margin-bottom: 8px;
}

.pp-view-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 14px; border-radius: var(--r);
    font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    text-decoration: none; transition: all .13s; border: none; cursor: pointer;
}
.pp-view-btn.blue { background: var(--blue); color: #fff; }
.pp-view-btn.blue:hover { background: #1344c2; color: #fff; }
.pp-view-btn.red  { background: var(--red);  color: #fff; }
.pp-view-btn.red:hover  { background: #b91c1c; color: #fff; }
.pp-view-btn.cyan { background: var(--cyan); color: #fff; }
.pp-view-btn.cyan:hover { background: #0e7490; color: #fff; }

/* ── EMPTY STATE ── */
.pp-empty {
    text-align: center; padding: 48px 24px;
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: 0 0 var(--rlg) var(--rlg);
}
.pp-empty-icon {
    width: 56px; height: 56px; border-radius: 14px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: var(--ink3); margin: 0 auto 14px;
}
.pp-empty-t { font-size: 13.5px; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
.pp-empty-s { font-size: 12px; font-weight: 500; color: var(--ink3); }

::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 1200px) { .pp-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 768px)  {
    .pp { padding: 16px; }
    .pp-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .pp-grid { grid-template-columns: 1fr; }
}
</style>

<div class="pp">

    {{-- ── HERO ── --}}
    <div class="pp-hero">
        <div class="pp-hero-glow"></div>
        <div class="pp-hero-glow2"></div>
        <div class="pp-hero-accent"></div>
        <div class="pp-hero-grid"></div>

        <div class="pp-hero-left">
            <div class="pp-hero-badge"><i class="fas fa-images"></i></div>
            <div>
                <div class="pp-hero-title">Photo Projects</div>
                <div class="pp-hero-sub">Manage and review all project photos</div>
            </div>
        </div>

        <div class="pp-hero-right">
            <div class="pp-stat-chip">
                <div class="pp-stat-chip-n">{{ $jobs->count() }}</div>
                <div class="pp-stat-chip-l">Jobs</div>
            </div>
            <div class="pp-stat-chip">
                <div class="pp-stat-chip-n">{{ $emergencies->count() }}</div>
                <div class="pp-stat-chip-l">Emergencies</div>
            </div>
            <div class="pp-stat-chip cyan">
                <div class="pp-stat-chip-n">{{ $repairs->count() }}</div>
                <div class="pp-stat-chip-l">Repairs</div>
            </div>
            <div class="pp-stat-chip">
                <div class="pp-stat-chip-n">{{ $jobs->count() + $emergencies->count() + $repairs->count() }}</div>
                <div class="pp-stat-chip-l">Total</div>
            </div>
        </div>
    </div>

    {{-- ── SEARCH ── --}}
    <div class="pp-search-wrap">
        <i class="fas fa-search pp-search-ico"></i>
        <input type="text" id="pp-search" class="pp-search-input"
               placeholder="Search by company, job #, customer, city…"
               oninput="ppFilter(this.value)">
        <button class="pp-search-clear" id="pp-clear" onclick="ppClear()">
            <i class="fas fa-times"></i>
        </button>
        <span class="pp-search-count" id="pp-count"></span>
    </div>

    {{-- ── JOB REQUESTS ── --}}
    <div class="pp-section" id="section-jobs">
        <div class="pp-section-label">Job Requests</div>
        <div class="pp-section-head">
            <div class="pp-section-head-l">
                <div class="pp-section-icon blue"><i class="fas fa-hard-hat"></i></div>
                <div>
                    <div class="pp-section-title">Job Requests</div>
                    <div class="pp-section-sub">Photo projects for scheduled jobs</div>
                </div>
            </div>
            <span class="pp-section-count blue">{{ $jobs->count() }} {{ Str::plural('project', $jobs->count()) }}</span>
        </div>

        @if($jobs->isEmpty())
        <div class="pp-empty">
            <div class="pp-empty-icon"><i class="fas fa-camera"></i></div>
            <div class="pp-empty-t">No job requests found</div>
            <div class="pp-empty-s">There are no photo projects for scheduled jobs.</div>
        </div>
        @else
        <div class="pp-cards-wrap">
            <div class="pp-grid" id="jobs-grid">
                @foreach($jobs as $job)
                <article class="pp-card"
                    data-search="{{ strtolower(($job->company_name ?? '').' '.($job->job_number_name ?? '').' '.($job->customer_first_name ?? '').' '.($job->customer_last_name ?? '').' '.($job->job_address_city ?? '').' '.($job->job_address_state ?? '')) }}">
                    <div class="pp-card-top blue"></div>
                    <div class="pp-card-body">
                        <span class="pp-card-pill blue"><i class="fas fa-hard-hat" style="font-size:8px"></i> Job Request</span>
                        <div class="pp-card-title">{{ $job->company_name ?: 'Job #'.$job->id }}</div>
                        @if($job->job_number_name)
                        <div class="pp-card-job-num"><i class="fas fa-hashtag" style="font-size:9px;margin-right:3px"></i>{{ $job->job_number_name }}</div>
                        @endif
                        <div class="pp-card-meta">
                            @if($job->customer_first_name || $job->customer_last_name)
                            <div class="pp-card-meta-row"><i class="fas fa-user"></i> {{ $job->customer_first_name }} {{ $job->customer_last_name }}</div>
                            @endif
                            @if($job->job_address_city || $job->job_address_state)
                            <div class="pp-card-meta-row"><i class="fas fa-map-marker-alt"></i> {{ $job->job_address_city }}{{ $job->job_address_city && $job->job_address_state ? ', ' : '' }}{{ $job->job_address_state }}</div>
                            @endif
                            @if($job->install_date_requested)
                            <div class="pp-card-meta-row"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($job->install_date_requested)->format('M d, Y') }}</div>
                            @endif
                        </div>
                        <div class="pp-card-foot">
                            <span class="pp-card-updated">
                                @if($job->updated_at)<i class="fas fa-clock" style="font-size:9px;margin-right:3px"></i>{{ \Carbon\Carbon::parse($job->updated_at)->diffForHumans() }}@endif
                            </span>
                            <a href="{{ route('superadmin.photos.view', ['tipo' => 'job_request', 'id' => $job->id]) }}" class="pp-view-btn blue">
                                <i class="fas fa-images" style="font-size:10px"></i> View Photos
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ── EMERGENCIES ── --}}
    <div class="pp-section" id="section-emerg">
        <div class="pp-section-label">Emergencies</div>
        <div class="pp-section-head">
            <div class="pp-section-head-l">
                <div class="pp-section-icon red"><i class="fas fa-triangle-exclamation"></i></div>
                <div>
                    <div class="pp-section-title">Emergencies</div>
                    <div class="pp-section-sub">Photo projects for emergency situations</div>
                </div>
            </div>
            <span class="pp-section-count red">{{ $emergencies->count() }} {{ Str::plural('emergency', $emergencies->count()) }}</span>
        </div>

        @if($emergencies->isEmpty())
        <div class="pp-empty">
            <div class="pp-empty-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="pp-empty-t">No emergencies found</div>
            <div class="pp-empty-s">There are no photo projects for emergencies.</div>
        </div>
        @else
        <div class="pp-cards-wrap">
            <div class="pp-grid" id="emerg-grid">
                @foreach($emergencies as $emergency)
                <article class="pp-card"
                    data-search="{{ strtolower(($emergency->company_name ?? '').' '.($emergency->job_number_name ?? '').' '.($emergency->type_of_supplement ?? '').' '.($emergency->job_city ?? '').' '.($emergency->job_state ?? '')) }}">
                    <div class="pp-card-top red"></div>
                    <div class="pp-card-body">
                        <span class="pp-card-pill red"><i class="fas fa-triangle-exclamation" style="font-size:8px"></i> Emergency</span>
                        <div class="pp-card-title">{{ $emergency->type_of_supplement ?? 'Emergency #'.$emergency->id }}</div>
                        @if($emergency->job_number_name)
                        <div class="pp-card-job-num"><i class="fas fa-hashtag" style="font-size:9px;margin-right:3px"></i>{{ $emergency->job_number_name }}</div>
                        @endif
                        <div class="pp-card-meta">
                            @if($emergency->company_name)
                            <div class="pp-card-meta-row"><i class="fas fa-building"></i> {{ $emergency->company_name }}</div>
                            @endif
                            @if($emergency->job_city || $emergency->job_state)
                            <div class="pp-card-meta-row"><i class="fas fa-map-marker-alt"></i> {{ $emergency->job_city }}{{ $emergency->job_city && $emergency->job_state ? ', ' : '' }}{{ $emergency->job_state }}</div>
                            @endif
                            @if($emergency->date_submitted)
                            <div class="pp-card-meta-row"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($emergency->date_submitted)->format('M d, Y') }}</div>
                            @endif
                        </div>
                        <div class="pp-card-foot">
                            <span class="pp-card-updated">
                                @if($emergency->updated_at)<i class="fas fa-clock" style="font-size:9px;margin-right:3px"></i>{{ \Carbon\Carbon::parse($emergency->updated_at)->diffForHumans() }}@endif
                            </span>
                            <a href="{{ route('superadmin.photos.view', ['tipo' => 'emergency', 'id' => $emergency->id]) }}" class="pp-view-btn red">
                                <i class="fas fa-images" style="font-size:10px"></i> View Photos
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ── REPAIR TICKETS ── --}}
    <div class="pp-section" id="section-repairs">
        <div class="pp-section-label">Repair Tickets</div>
        <div class="pp-section-head">
            <div class="pp-section-head-l">
                <div class="pp-section-icon cyan"><i class="fas fa-helmet-safety"></i></div>
                <div>
                    <div class="pp-section-title">Repair Tickets</div>
                    <div class="pp-section-sub">Damage & work photos uploaded by admin and crew</div>
                </div>
            </div>
            <span class="pp-section-count cyan">{{ $repairs->count() }} {{ Str::plural('repair', $repairs->count()) }}</span>
        </div>

        @if($repairs->isEmpty())
        <div class="pp-empty">
            <div class="pp-empty-icon" style="background:var(--clt);border-color:var(--cbd)">
                <i class="fas fa-helmet-safety" style="color:var(--cyan)"></i>
            </div>
            <div class="pp-empty-t">No repair tickets found</div>
            <div class="pp-empty-s">Repair tickets with photos will appear here.</div>
        </div>
        @else
        <div class="pp-cards-wrap">
            <div class="pp-grid" id="repairs-grid">
                @foreach($repairs as $repair)
                @php
                    $ref     = $repair->reference_type === 'job'
                        ? optional($repair->jobRequest)->job_number_name
                        : optional($repair->emergency)->job_number_name;
                    $company = $repair->reference_type === 'job'
                        ? optional($repair->jobRequest)->company_name
                        : optional($repair->emergency)->company_name;
                    $address = $repair->reference_type === 'job'
                        ? optional($repair->jobRequest)->job_address_street_address
                        : optional($repair->emergency)->job_address;
                    $adminCount = $repair->fotosAdmin()->count();
                    $crewCount  = $repair->fotosCrew()->count();
                @endphp
                <article class="pp-card"
                    data-search="{{ strtolower(($company ?? '').' '.($ref ?? '').' '.($repair->description ?? '').' '.($address ?? '')) }}">
                    <div class="pp-card-top cyan"></div>
                    <div class="pp-card-body">
                        <span class="pp-card-pill cyan"><i class="fas fa-helmet-safety" style="font-size:8px"></i> Repair</span>
                        <span class="pp-card-status {{ $repair->status }}">{{ ucfirst(str_replace('_',' ',$repair->status)) }}</span>

                        <div class="pp-card-title">{{ $company ?? 'Repair #'.$repair->id }}</div>

                        @if($ref)
                        <div class="pp-card-job-num"><i class="fas fa-hashtag" style="font-size:9px;margin-right:3px"></i>{{ $ref }}</div>
                        @endif

                        {{-- Photo counts --}}
                        <div style="display:flex;gap:6px;margin-bottom:10px;flex-wrap:wrap">
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;background:#fff3e0;color:#e65100;border:1px solid #ffcc80">
                                <i class="fas fa-camera" style="font-size:8px;margin-right:2px"></i>{{ $adminCount }} damage
                            </span>
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;background:var(--clt);color:var(--cyan);border:1px solid var(--cbd)">
                                <i class="fas fa-helmet-safety" style="font-size:8px;margin-right:2px"></i>{{ $crewCount }} work
                            </span>
                        </div>

                        <div class="pp-card-meta">
                            @if($address)
                            <div class="pp-card-meta-row"><i class="fas fa-map-marker-alt"></i> {{ $address }}</div>
                            @endif
                            @if($repair->repair_date)
                            <div class="pp-card-meta-row"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($repair->repair_date)->format('M d, Y') }}</div>
                            @endif
                            @if($repair->description)
                            <div class="pp-card-meta-row" style="align-items:flex-start">
                                <i class="fas fa-file-text" style="margin-top:2px"></i>
                                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px">{{ $repair->description }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="pp-card-foot">
                            <span class="pp-card-updated">
                                @if($repair->updated_at)<i class="fas fa-clock" style="font-size:9px;margin-right:3px"></i>{{ \Carbon\Carbon::parse($repair->updated_at)->diffForHumans() }}@endif
                            </span>
                            <a href="{{ route('superadmin.photos.view', ['tipo' => 'repair', 'id' => $repair->id]) }}" class="pp-view-btn cyan">
                                <i class="fas fa-images" style="font-size:10px"></i> View Photos
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>

<script>
function ppFilter(val) {
    const q     = val.trim().toLowerCase();
    const clear = document.getElementById('pp-clear');
    const count = document.getElementById('pp-count');
    clear.style.display = q ? 'block' : 'none';

    const cards = document.querySelectorAll('.pp-card');
    let shown = 0;
    cards.forEach(c => {
        const match = !q || c.dataset.search.includes(q);
        c.style.display = match ? '' : 'none';
        if (match) shown++;
    });

    if (q) {
        count.textContent = shown + ' result' + (shown !== 1 ? 's' : '');
        count.style.display = 'inline-flex';
    } else {
        count.style.display = 'none';
    }
}

function ppClear() {
    const inp = document.getElementById('pp-search');
    inp.value = '';
    ppFilter('');
    inp.focus();
}
</script>

@endsection