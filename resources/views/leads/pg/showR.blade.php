@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
    --ink:#0f172a;--ink2:#334155;--ink3:#64748b;--ink4:#94a3b8;
    --line:#e2e8f0;--line2:#f1f5f9;--white:#ffffff;--page:#f0f2f5;
    --red:#dc2626;--red-bg:#fef2f2;--red-bd:#fecaca;
    --cyan:#0891b2;--cyan-bg:#ecfeff;--cyan-bd:#a5f3fc;
    --green:#059669;--green-bg:#f0fdf4;--green-bd:#6ee7b7;
    --amber:#d97706;--amber-bg:#fffbeb;--amber-bd:#fde68a;
    --purple:#7c3aed;--purple-bg:#f5f3ff;--purple-bd:#ddd6fe;
    --blue:#2563eb;--blue-bg:#eff6ff;--blue-bd:#bfdbfe;
}
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Montserrat',sans-serif;background:var(--page);}
.jr-page{min-height:100vh;padding:1.75rem 1.5rem 3rem;}

/* ── Hero ── */
.jr-hero{
    background:var(--ink);border-radius:18px;
    padding:1.6rem 2rem;margin-bottom:1.25rem;
    display:flex;align-items:center;justify-content:space-between;
    gap:1rem;flex-wrap:wrap;position:relative;overflow:hidden;
}
.jr-hero::before{
    content:'';position:absolute;right:-80px;top:-80px;
    width:260px;height:260px;border-radius:50%;
    background:radial-gradient(circle,rgba(37,99,235,.20) 0%,transparent 70%);
    pointer-events:none;
}
.jr-hero::after{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,#60a5fa,var(--blue));border-radius:18px 0 0 18px;}
.hero-eye{font-size:.6rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--ink4);margin-bottom:.3rem;display:flex;align-items:center;gap:.35rem;}
.hero-title{font-size:1.45rem;font-weight:800;color:#fff;letter-spacing:-.035em;line-height:1.1;margin-bottom:.2rem;}
.hero-sub{font-size:.73rem;color:var(--ink4);display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;}
.hero-sub-sep{color:rgba(255,255,255,.15);}
.hero-actions{display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;position:relative;z-index:1;}

.btn-hg{display:inline-flex;align-items:center;gap:.35rem;font-family:'Montserrat',sans-serif;font-weight:600;font-size:.75rem;padding:.4rem .9rem;border-radius:8px;text-decoration:none;white-space:nowrap;transition:all .18s;cursor:pointer;border:none;}
.btn-back{background:rgba(255,255,255,.07);color:var(--ink4);border:1px solid rgba(255,255,255,.1);}
.btn-back:hover{color:#fff;background:rgba(255,255,255,.13);}
.btn-edit-hero{background:#fff;color:var(--ink);}
.btn-edit-hero:hover{background:#f1f5f9;color:var(--ink);}
.btn-repair-hero{background:rgba(8,145,178,.15);color:#67e8f9;border:1px solid rgba(8,145,178,.3);}
.btn-repair-hero:hover{background:rgba(8,145,178,.25);color:#a5f3fc;}
.btn-del-hero{background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);}
.btn-del-hero:hover{background:rgba(239,68,68,.25);color:#fca5a5;}

/* ── Quick Stats ── */
.qs-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.1rem;}
.qs-card{background:var(--white);border:1px solid var(--line);border-radius:14px;padding:.85rem 1rem;display:flex;align-items:center;gap:.85rem;transition:all .15s;position:relative;overflow:hidden;}
.qs-card:hover{border-color:rgba(15,23,42,.12);box-shadow:0 2px 8px rgba(15,23,42,.05);transform:translateY(-1px);}
.qs-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.9rem;}
.qs-icon.blue  {background:var(--blue-bg);  color:var(--blue);}
.qs-icon.amber {background:var(--amber-bg); color:var(--amber);}
.qs-icon.cyan  {background:var(--cyan-bg);  color:var(--cyan);}
.qs-icon.purple{background:var(--purple-bg);color:var(--purple);}
.qs-icon.red   {background:var(--red-bg);   color:var(--red);}
.qs-num{font-size:1.4rem;font-weight:800;letter-spacing:-.04em;line-height:1;color:var(--ink);}
.qs-lbl{font-size:.6rem;color:var(--ink4);margin-top:3px;text-transform:uppercase;letter-spacing:.07em;font-weight:700;}
.qs-bar{position:absolute;bottom:0;left:0;height:2px;width:100%;background:transparent;}
.qs-bar-fill{height:100%;transition:width .5s cubic-bezier(.4,0,.2,1);}

/* Stop work alert (especial para Job) */
.qs-card.alert-active{border-color:var(--red-bd);background:linear-gradient(135deg,var(--red-bg) 0%,#fff 60%);}
.qs-card.alert-active .qs-num{color:var(--red);}

/* ── Cards ── */
.card{background:var(--white);border-radius:16px;border:1px solid var(--line);overflow:hidden;margin-bottom:1.1rem;box-shadow:0 1px 6px rgba(15,23,42,.05);}
.card-head{padding:.9rem 1.3rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:.55rem;background:linear-gradient(to right,var(--blue-bg),#fafbfd);}
.card-head.cyan-head{background:linear-gradient(to right,var(--cyan-bg),#fafbfd);}
.card-head.green-head{background:linear-gradient(to right,var(--green-bg),#fafbfd);}
.card-head.purple-head{background:linear-gradient(to right,var(--purple-bg),#fafbfd);}
.card-head.amber-head{background:linear-gradient(to right,var(--amber-bg),#fafbfd);}
.card-head.neutral-head{background:linear-gradient(to right,var(--line2),#fafbfd);}
.card-head-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.8rem;}
.card-head h6{font-size:.82rem;font-weight:700;color:var(--ink);margin:0;letter-spacing:-.01em;}
.card-body{padding:1.1rem 1.3rem;}

/* ── Sub-headings dentro de card ── */
.sub-head{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink3);margin:0 0 .65rem;padding-bottom:.4rem;border-bottom:1px dashed var(--line);display:flex;align-items:center;gap:.35rem;}
.sub-head:not(:first-child){margin-top:1.1rem;}

/* ── Info rows ── */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:0;}
.info-row{padding:.6rem 0;border-bottom:1px solid var(--line2);display:flex;flex-direction:column;gap:.18rem;}
.info-row:last-child{border-bottom:none;}
.info-row.full{grid-column:span 2;}
.info-key{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink4);display:flex;align-items:center;gap:.3rem;}
.info-key i{font-size:.62rem;}
.info-val{font-size:.85rem;font-weight:500;color:var(--ink2);line-height:1.4;}
.info-row.pe{padding-right:1.5rem;border-right:1px solid var(--line2);}
.info-row.ps{padding-left:1.5rem;}

/* Status badge dinámico */
.status-pill{display:inline-flex;align-items:center;gap:.3rem;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:.22rem .7rem;border-radius:99px;border:1.5px solid;}
.status-pending    {color:var(--amber); border-color:var(--amber-bd); background:var(--amber-bg);}
.status-scheduled  {color:var(--blue);  border-color:var(--blue-bd);  background:var(--blue-bg);}
.status-in_progress{color:var(--purple);border-color:var(--purple-bd);background:var(--purple-bg);}
.status-completed  {color:var(--green); border-color:var(--green-bd); background:var(--green-bg);}
.status-cancelled  {color:var(--red);   border-color:var(--red-bd);   background:var(--red-bg);}

/* Address block */
.addr-block{background:var(--line2);border-radius:10px;padding:.75rem 1rem;font-size:.82rem;color:var(--ink2);font-weight:500;line-height:1.55;display:flex;align-items:flex-start;gap:.65rem;}
.addr-block i{color:var(--ink4);font-size:.85rem;margin-top:2px;flex-shrink:0;}

/* ── Yes/No Pills ── */
.yn-pill{display:inline-flex;align-items:center;gap:.25rem;font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;padding:.18rem .6rem;border-radius:99px;}
.yn-yes   {background:var(--green-bg);color:var(--green);border:1px solid var(--green-bd);}
.yn-no    {background:var(--line2);   color:var(--ink4); border:1px solid var(--line);}
.yn-active{background:var(--red-bg);  color:var(--red);  border:1px solid var(--red-bd);}

.yn-stack{display:flex;flex-wrap:wrap;gap:.35rem;}

/* Special instructions */
.instr-box{background:var(--blue-bg);border:1px solid var(--blue-bd);border-left:3px solid var(--blue);border-radius:0 10px 10px 0;padding:.85rem 1rem;font-size:.83rem;color:var(--ink2);line-height:1.55;}

/* ── Team ── */
.team-row{display:flex;align-items:center;gap:.8rem;padding:.7rem .9rem;border-radius:10px;background:var(--line2);border:1px solid var(--line);margin-bottom:.5rem;transition:transform .15s;}
.team-row:hover{transform:translateX(3px);}
.team-av{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;color:#fff;flex-shrink:0;background:linear-gradient(135deg,var(--ink3),var(--ink));}
.team-name{font-weight:600;font-size:.82rem;color:var(--ink);}
.team-role{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--ink4);margin-top:1px;}
.team-badge{margin-left:auto;font-size:.6rem;font-weight:700;text-transform:uppercase;padding:.15rem .5rem;border-radius:99px;background:var(--line);color:var(--ink3);flex-shrink:0;}

/* ── Files ── */
.file-section-title{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink3);margin:1rem 0 .5rem;display:flex;align-items:center;gap:.35rem;}
.file-section-title:first-child{margin-top:0;}
.file-row{display:flex;align-items:center;gap:.65rem;padding:.65rem .9rem;border-radius:9px;background:var(--line2);border:1px solid var(--line);margin-bottom:.4rem;}
.file-ico{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.85rem;}
.file-ico.pdf  {background:#fee2e2;color:var(--red);}
.file-ico.img  {background:#dbeafe;color:var(--blue);}
.file-ico.xls  {background:#dcfce7;color:var(--green);}
.file-ico.doc  {background:#e0f2fe;color:#0284c7;}
.file-ico.zip  {background:#fef9c3;color:var(--amber);}
.file-ico.other{background:var(--line);color:var(--ink3);}
.file-info{flex:1;min-width:0;}
.file-nm{font-size:.78rem;font-weight:500;color:var(--ink);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.file-ext{font-size:.62rem;font-weight:700;text-transform:uppercase;color:var(--ink4);letter-spacing:.05em;margin-top:1px;}
.file-btns{display:flex;gap:.3rem;flex-shrink:0;}
.btn-fview{font-size:.68rem;font-weight:600;padding:.22rem .55rem;border-radius:6px;background:var(--ink);color:#fff;text-decoration:none;transition:background .15s;display:inline-flex;align-items:center;}
.btn-fview:hover{background:var(--blue);color:#fff;}
.btn-fdl{font-size:.68rem;font-weight:600;padding:.22rem .55rem;border-radius:6px;background:var(--line);color:var(--ink2);text-decoration:none;transition:background .15s;display:inline-flex;align-items:center;}
.btn-fdl:hover{background:var(--ink4);color:#fff;}
.empty-txt{text-align:center;padding:1.25rem;color:var(--ink4);font-size:.78rem;font-style:italic;}

@media(max-width:992px){.qs-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:768px){
    .jr-page{padding:1rem .75rem 2.5rem;}
    .jr-hero{padding:1.1rem 1.25rem;}
    .hero-title{font-size:1.2rem;}
    .info-grid{grid-template-columns:1fr;}
    .info-row.pe{padding-right:0;border-right:none;}
    .info-row.ps{padding-left:0;}
    .info-row.full{grid-column:span 1;}
}
</style>

@php
    // ── Pre-cálculos para Quick Stats y Repair History ──
    $allTickets   = $job->repairTickets()->with(['fotosAdmin','fotosCrew'])->latest('repair_date')->get();
    $rtTotal      = $allTickets->count();
    $rtPending    = $allTickets->where('status','pending')->count();
    $rtInProcess  = $allTickets->where('status','en_process')->count();
    $rtCompleted  = $allTickets->where('status','completed')->count();

    $teamCount    = $job->teamMembers->count();

    // Files (pueden venir como JSON string o array)
    $normalizeFiles = function($raw){
        if (is_string($raw))  $raw = json_decode($raw, true);
        return is_array($raw) ? $raw : [];
    };
    $aerials      = $normalizeFiles($job->aerial_measurement);
    $materials    = $normalizeFiles($job->material_order);
    $otherFiles   = $normalizeFiles($job->file_upload);
    $fileCount    = count($aerials) + count($materials) + count($otherFiles);
    $hasFiles     = $fileCount > 0;

    // Status normalizado
    $statusKey   = strtolower(str_replace(' ','_', $job->status ?? 'pending'));
    $statusLabel = match($statusKey){
        'pending'     => 'Pending',
        'scheduled'   => 'Scheduled',
        'in_progress' => 'In Progress',
        'completed'   => 'Completed',
        'cancelled'   => 'Cancelled',
        default       => ucfirst(str_replace('_',' ',$statusKey)),
    };
    $statusIcon = match($statusKey){
        'completed'   => 'bi-check-circle-fill',
        'in_progress' => 'bi-tools',
        'scheduled'   => 'bi-calendar-check',
        'cancelled'   => 'bi-x-circle',
        default       => 'bi-clock'
    };
@endphp

<div class="jr-page">
<div class="container-xl px-0">

    {{-- Hero --}}
    <div class="jr-hero">
        <div style="position:relative;">
            <div class="hero-eye"><i class="bi bi-briefcase"></i> Job Request #{{ $job->id }}</div>
            <h1 class="hero-title">{{ $job->job_number_name ?? 'Job Details' }}</h1>
            <div class="hero-sub">
                <span><i class="bi bi-building" style="font-size:.65rem;"></i> {{ $job->company_name }}</span>
                <span class="hero-sub-sep">|</span>
                <span><i class="bi bi-calendar3" style="font-size:.65rem;"></i> Install: {{ $job->install_date_requested }}</span>
                <span class="hero-sub-sep">|</span>
                <span><i class="bi bi-clock-history" style="font-size:.65rem;"></i> Created {{ $job->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        <div class="hero-actions">
            <a href="{{ route('calendar.view') }}" class="btn-hg btn-back"><i class="bi bi-arrow-left"></i> Back</a>
            <a href="{{ route('jobs.edit', $job->id) }}" class="btn-hg btn-edit-hero"><i class="bi bi-pencil-square"></i> Edit</a>
            <a href="{{ route('repair-tickets.index', ['ref_type'=>'job','ref_id'=>$job->id]) }}"
               class="btn-hg btn-repair-hero">
                <i class="bi bi-tools"></i> Repair Tickets
            </a>
            <form id="del-form-{{ $job->id }}" action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="button" class="btn-hg btn-del-hero" onclick="confirmJobDel({{ $job->id }})">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    {{-- ── QUICK STATS ── --}}
    <div class="qs-grid">
        <div class="qs-card">
            <div class="qs-icon blue"><i class="bi bi-tools"></i></div>
            <div>
                <div class="qs-num" style="color:var(--blue);">{{ $rtTotal }}</div>
                <div class="qs-lbl">Repair Tickets</div>
            </div>
            <div class="qs-bar"><div class="qs-bar-fill" style="background:var(--blue);width:100%"></div></div>
        </div>
        <div class="qs-card">
            <div class="qs-icon amber"><i class="bi bi-clock-fill"></i></div>
            <div>
                <div class="qs-num" style="color:var(--amber);">{{ $rtPending }}</div>
                <div class="qs-lbl">Pending</div>
            </div>
            <div class="qs-bar"><div class="qs-bar-fill" style="background:var(--amber);width:{{ $rtTotal ? ($rtPending/$rtTotal*100) : 0 }}%"></div></div>
        </div>
        <div class="qs-card">
            <div class="qs-icon purple"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="qs-num" style="color:var(--purple);">{{ $teamCount }}</div>
                <div class="qs-lbl">Team Members</div>
            </div>
            <div class="qs-bar"><div class="qs-bar-fill" style="background:var(--purple);width:{{ $teamCount ? 100 : 0 }}%"></div></div>
        </div>
        @if($job->stop_work_request)
            <div class="qs-card alert-active">
                <div class="qs-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div>
                    <div class="qs-num">STOP</div>
                    <div class="qs-lbl">Work Request</div>
                </div>
                <div class="qs-bar"><div class="qs-bar-fill" style="background:var(--red);width:100%"></div></div>
            </div>
        @else
            <div class="qs-card">
                <div class="qs-icon cyan"><i class="bi bi-paperclip"></i></div>
                <div>
                    <div class="qs-num" style="color:var(--cyan);">{{ $fileCount }}</div>
                    <div class="qs-lbl">Documents</div>
                </div>
                <div class="qs-bar"><div class="qs-bar-fill" style="background:var(--cyan);width:{{ $hasFiles ? 100 : 0 }}%"></div></div>
            </div>
        @endif
    </div>

    <div class="row g-3">

        {{-- LEFT ── 8 cols ── --}}
        <div class="col-lg-8">

            {{-- General Information --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background:var(--blue-bg);color:var(--blue);"><i class="bi bi-info-circle-fill"></i></div>
                    <h6>General Information</h6>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row pe">
                            <span class="info-key"><i class="bi bi-calendar-event"></i> Install Date</span>
                            <span class="info-val">{{ $job->install_date_requested }}</span>
                        </div>
                        <div class="info-row ps">
                            <span class="info-key"><i class="bi bi-file-earmark-text"></i> Job Number</span>
                            <span class="info-val">{{ $job->job_number_name }}</span>
                        </div>
                        <div class="info-row pe">
                            <span class="info-key"><i class="bi bi-building"></i> Company</span>
                            <span class="info-val">{{ $job->company_name }}</span>
                        </div>
                        <div class="info-row ps">
                            <span class="info-key"><i class="bi bi-person"></i> Representative</span>
                            <span class="info-val">{{ $job->company_rep ?? '—' }}</span>
                        </div>
                        <div class="info-row pe">
                            <span class="info-key"><i class="bi bi-telephone"></i> Rep Phone</span>
                            <span class="info-val">{{ $job->company_rep_phone ?? '—' }}</span>
                        </div>
                        <div class="info-row ps">
                            <span class="info-key"><i class="bi bi-envelope"></i> Rep Email</span>
                            <span class="info-val" style="word-break:break-all;">{{ $job->company_rep_email ?? '—' }}</span>
                        </div>
                        <div class="info-row pe">
                            <span class="info-key"><i class="bi bi-person-badge"></i> Customer</span>
                            <span class="info-val">{{ trim(($job->customer_first_name ?? '').' '.($job->customer_last_name ?? '')) ?: '—' }}</span>
                        </div>
                        <div class="info-row ps">
                            <span class="info-key"><i class="bi bi-phone"></i> Customer Phone</span>
                            <span class="info-val">{{ $job->customer_phone_number ?? '—' }}</span>
                        </div>
                        <div class="info-row pe">
                            <span class="info-key"><i class="bi bi-truck"></i> Delivery Date</span>
                            <span class="info-val">{{ $job->delivery_date ?? '—' }}</span>
                        </div>
                        <div class="info-row ps">
                            <span class="info-key"><i class="bi bi-flag"></i> Status</span>
                            <span class="info-val">
                                <span class="status-pill status-{{ $statusKey }}">
                                    <i class="bi {{ $statusIcon }}"></i>
                                    {{ $statusLabel }}
                                </span>
                            </span>
                        </div>
                        <div class="info-row full" style="padding-top:.85rem;">
                            <span class="info-key" style="margin-bottom:.4rem;"><i class="bi bi-geo-alt"></i> Job Address</span>
                            <div class="addr-block">
                                <i class="bi bi-geo-alt-fill"></i>
                                <div>
                                    {{ $job->job_address_street_address }}
                                    @if($job->job_address_street_address_line_2)<br>{{ $job->job_address_street_address_line_2 }}@endif
                                    <br>{{ $job->job_address_city }}, {{ $job->job_address_state }} {{ $job->job_address_zip_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Project Details (Materials + Work Specs) --}}
            <div class="card">
                <div class="card-head purple-head">
                    <div class="card-head-icon" style="background:var(--purple-bg);color:var(--purple);"><i class="bi bi-clipboard2-data-fill"></i></div>
                    <h6>Project Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-0">

                        {{-- Materials Ordered --}}
                        <div class="col-md-6 pe-md-4">
                            <div class="sub-head"><i class="bi bi-box-seam"></i> Materials Ordered</div>
                            <div class="info-row">
                                <span class="info-key">Material Roof Loaded</span>
                                <span class="info-val">
                                    <span class="yn-pill {{ $job->material_roof_loaded === 'Yes' ? 'yn-yes' : 'yn-no' }}">
                                        <i class="bi {{ $job->material_roof_loaded === 'Yes' ? 'bi-check-lg' : 'bi-dash' }}"></i>
                                        {{ $job->material_roof_loaded ?? 'No' }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row"><span class="info-key">Starter Bundles</span><span class="info-val">{{ $job->starter_bundles_ordered ?? '—' }}</span></div>
                            <div class="info-row"><span class="info-key">Hip & Ridge</span><span class="info-val">{{ $job->hip_and_ridge_ordered ?? '—' }}</span></div>
                            <div class="info-row"><span class="info-key">Field Shingles</span><span class="info-val">{{ $job->field_shingle_bundles_ordered ?? '—' }}</span></div>
                            <div class="info-row"><span class="info-key">Modified Bitumen Cap Rolls</span><span class="info-val">{{ $job->modified_bitumen_cap_rolls_ordered ?? '—' }}</span></div>
                            <div class="info-row">
                                <span class="info-key">Material Verification</span>
                                <span class="info-val">
                                    <span class="yn-pill {{ $job->material_verification ? 'yn-yes' : 'yn-no' }}">
                                        <i class="bi {{ $job->material_verification ? 'bi-check-circle-fill' : 'bi-hourglass' }}"></i>
                                        {{ $job->material_verification ? 'Verified' : 'Pending' }}
                                    </span>
                                </span>
                            </div>
                        </div>

                        {{-- Work Specs --}}
                        <div class="col-md-6 ps-md-4" style="border-left:1px solid var(--line2);">
                            <div class="sub-head"><i class="bi bi-tools"></i> Work Specifications</div>
                            <div class="info-row"><span class="info-key">Shingle Layers to Remove</span><span class="info-val">{{ $job->asphalt_shingle_layers_to_remove ?? '—' }}</span></div>
                            <div class="info-row">
                                <span class="info-key">Mid Roof Inspection</span>
                                <span class="info-val">
                                    <span class="yn-pill {{ ($job->mid_roof_inspection ?? '') === 'Yes' ? 'yn-yes' : 'yn-no' }}">
                                        {{ $job->mid_roof_inspection ?? 'No' }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Siding Replacement</span>
                                <span class="info-val">
                                    <span class="yn-pill {{ $job->siding_being_replaced ? 'yn-yes' : 'yn-no' }}">
                                        <i class="bi {{ $job->siding_being_replaced ? 'bi-check-lg' : 'bi-dash' }}"></i>
                                        {{ $job->siding_being_replaced ? 'Yes' : 'No' }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Re-deck</span>
                                <span class="info-val">
                                    <span class="yn-pill {{ $job->re_deck ? 'yn-yes' : 'yn-no' }}">
                                        <i class="bi {{ $job->re_deck ? 'bi-check-lg' : 'bi-dash' }}"></i>
                                        {{ $job->re_deck ? 'Yes' : 'No' }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Skylights Replace</span>
                                <span class="info-val">
                                    <span class="yn-pill {{ $job->skylights_replace ? 'yn-yes' : 'yn-no' }}">
                                        <i class="bi {{ $job->skylights_replace ? 'bi-check-lg' : 'bi-dash' }}"></i>
                                        {{ $job->skylights_replace ? 'Yes' : 'No' }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Gutters</span>
                                <span class="info-val yn-stack">
                                    <span class="yn-pill {{ $job->gutter_remove ? 'yn-yes' : 'yn-no' }}">Remove: {{ $job->gutter_remove ? 'Yes' : 'No' }}</span>
                                    <span class="yn-pill {{ $job->gutter_detached_and_reset ? 'yn-yes' : 'yn-no' }}">Reset: {{ $job->gutter_detached_and_reset ? 'Yes' : 'No' }}</span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Satellite</span>
                                <span class="info-val yn-stack">
                                    <span class="yn-pill {{ $job->satellite_remove ? 'yn-yes' : 'yn-no' }}">Remove: {{ $job->satellite_remove ? 'Yes' : 'No' }}</span>
                                    <span class="yn-pill {{ $job->satellite_goes_in_the_trash ? 'yn-active' : 'yn-no' }}">Trash: {{ $job->satellite_goes_in_the_trash ? 'Yes' : 'No' }}</span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Stop Work Request</span>
                                <span class="info-val">
                                    <span class="yn-pill {{ $job->stop_work_request ? 'yn-active' : 'yn-no' }}">
                                        <i class="bi {{ $job->stop_work_request ? 'bi-exclamation-triangle-fill' : 'bi-check-circle' }}"></i>
                                        {{ $job->stop_work_request ? 'Active' : 'None' }}
                                    </span>
                                </span>
                            </div>
                        </div>

                    </div>

                    @if($job->special_instructions)
                        <div class="sub-head" style="margin-top:1.1rem;"><i class="bi bi-chat-left-text"></i> Special Instructions</div>
                        <div class="instr-box">{{ $job->special_instructions }}</div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT ── 4 cols ── --}}
        <div class="col-lg-4">

            {{-- Assigned Team --}}
            <div class="card">
                <div class="card-head neutral-head">
                    <div class="card-head-icon" style="background:var(--line);color:var(--ink3);"><i class="bi bi-people-fill"></i></div>
                    <h6>Assigned Team</h6>
                </div>
                <div class="card-body">
                    @forelse($job->teamMembers as $member)
                        <div class="team-row">
                            <div class="team-av">{{ strtoupper(substr($member->name,0,2)) }}</div>
                            <div>
                                <div class="team-name">{{ $member->name }}</div>
                                <div class="team-role">{{ ucfirst(str_replace('_',' ',$member->role)) }}</div>
                            </div>
                            <span class="team-badge">{{ $member->role }}</span>
                        </div>
                    @empty
                        <div class="empty-txt">No team members assigned.</div>
                    @endforelse
                </div>
            </div>

            {{-- Attachments --}}
            <div class="card">
                <div class="card-head neutral-head">
                    <div class="card-head-icon" style="background:var(--line);color:var(--ink3);"><i class="bi bi-paperclip"></i></div>
                    <h6>Attached Documents</h6>
                </div>
                <div class="card-body">
                    @php
                        $categories = [
                            ['label'=>'Aerial Measurements','icon'=>'bi-map',         'data'=>$aerials],
                            ['label'=>'Material Orders',    'icon'=>'bi-cart-check', 'data'=>$materials],
                            ['label'=>'Other Files',        'icon'=>'bi-file-earmark','data'=>$otherFiles],
                        ];
                    @endphp
                    @foreach($categories as $cat)
                        @if(!empty($cat['data']))
                            <div class="file-section-title"><i class="bi {{ $cat['icon'] }}"></i> {{ $cat['label'] }}</div>
                            @foreach($cat['data'] as $file)
                                @php
                                    $path     = is_array($file) ? ($file['path'] ?? '') : $file;
                                    $filename = is_array($file) ? ($file['original_name'] ?? basename($path)) : basename($path);
                                    $ext      = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    $url      = asset('storage/'.$path);
                                    $iconCls = match(true){
                                        $ext==='pdf' => 'pdf',
                                        in_array($ext,['jpg','jpeg','png','gif','webp']) => 'img',
                                        in_array($ext,['xlsx','xls','csv']) => 'xls',
                                        in_array($ext,['doc','docx']) => 'doc',
                                        $ext==='zip' => 'zip',
                                        default => 'other',
                                    };
                                    $iconBi = match($iconCls){
                                        'pdf' => 'bi-file-pdf-fill',
                                        'img' => 'bi-file-image-fill',
                                        'xls' => 'bi-file-spreadsheet-fill',
                                        'doc' => 'bi-file-word-fill',
                                        'zip' => 'bi-file-zip-fill',
                                        default => 'bi-file-earmark-fill',
                                    };
                                @endphp
                                <div class="file-row">
                                    <div class="file-ico {{ $iconCls }}"><i class="bi {{ $iconBi }}"></i></div>
                                    <div class="file-info">
                                        <div class="file-nm">{{ $filename }}</div>
                                        <div class="file-ext">{{ $ext }}</div>
                                    </div>
                                    <div class="file-btns">
                                        <a href="{{ $url }}" target="_blank" class="btn-fview"><i class="bi bi-eye"></i></a>
                                        <a href="{{ $url }}" download class="btn-fdl"><i class="bi bi-download"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                    @if(!$hasFiles)<div class="empty-txt">No attachments for this job.</div>@endif
                </div>
            </div>

        </div>
    </div>

    {{-- ── REPAIR HISTORY — full width, protagonista ── --}}
    <div class="card" style="margin-top:.25rem;">
        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#0c4a6e 0%,#0e7490 100%);padding:1.1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:.85rem;">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-tools" style="color:#67e8f9;font-size:.9rem;"></i>
                </div>
                <div>
                    <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.45);">Project</div>
                    <div style="font-size:1rem;font-weight:800;color:#fff;letter-spacing:-.02em;line-height:1.1;">Repair History</div>
                </div>
                {{-- Mini stats --}}
                <div style="display:flex;gap:.5rem;margin-left:.5rem;flex-wrap:wrap;">
                    <span style="font-size:.65rem;font-weight:700;padding:.2rem .65rem;border-radius:99px;background:rgba(255,255,255,.1);color:rgba(255,255,255,.7);">
                        {{ $rtTotal }} total
                    </span>
                    @if($rtPending)
                        <span style="font-size:.65rem;font-weight:700;padding:.2rem .65rem;border-radius:99px;background:rgba(251,191,36,.2);color:#fde68a;border:1px solid rgba(251,191,36,.3);">
                            {{ $rtPending }} scheduled
                        </span>
                    @endif
                    @if($rtInProcess)
                        <span style="font-size:.65rem;font-weight:700;padding:.2rem .65rem;border-radius:99px;background:rgba(167,139,250,.2);color:#c4b5fd;border:1px solid rgba(167,139,250,.3);">
                            {{ $rtInProcess }} in progress
                        </span>
                    @endif
                    @if($rtCompleted)
                        <span style="font-size:.65rem;font-weight:700;padding:.2rem .65rem;border-radius:99px;background:rgba(52,211,153,.2);color:#6ee7b7;border:1px solid rgba(52,211,153,.3);">
                            {{ $rtCompleted }} completed
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('repair-tickets.index', ['ref_type'=>'job','ref_id'=>$job->id]) }}"
               style="display:inline-flex;align-items:center;gap:.4rem;font-size:.72rem;font-weight:700;padding:.38rem .9rem;border-radius:8px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.75);text-decoration:none;transition:all .15s;white-space:nowrap;"
               onmouseover="this.style.background='rgba(255,255,255,.18)';this.style.color='#fff'"
               onmouseout="this.style.background='rgba(255,255,255,.1)';this.style.color='rgba(255,255,255,.75)'">
                <i class="bi bi-arrow-up-right-from-square" style="font-size:.65rem;"></i> View all
            </a>
        </div>

        {{-- Ticket grid --}}
        @if($allTickets->isEmpty())
            <div style="text-align:center;padding:3rem 1rem;">
                <div style="width:56px;height:56px;border-radius:14px;background:var(--cyan-bg);border:1px solid var(--cyan-bd);display:flex;align-items:center;justify-content:center;margin:0 auto .85rem;">
                    <i class="bi bi-tools" style="font-size:1.3rem;color:var(--cyan);"></i>
                </div>
                <div style="font-size:.88rem;font-weight:700;color:var(--ink);margin-bottom:.3rem;">No repair tickets yet</div>
                <div style="font-size:.75rem;color:var(--ink4);margin-bottom:1.1rem;">Start tracking repairs for this job.</div>
                <a href="{{ route('repair-tickets.index', ['ref_type'=>'job','ref_id'=>$job->id]) }}"
                   style="display:inline-flex;align-items:center;gap:.4rem;font-family:'Montserrat',sans-serif;font-weight:700;font-size:.78rem;padding:.55rem 1.3rem;border-radius:9px;background:var(--cyan);color:#fff;text-decoration:none;box-shadow:0 4px 12px rgba(8,145,178,.3);">
                    <i class="bi bi-plus-lg"></i> Create First Ticket
                </a>
            </div>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:0;border-top:1px solid var(--line);">
                @foreach($allTickets as $rt)
                    @php
                        [$rtBg,$rtColor,$rtBd] = match($rt->status){
                            'pending'    => ['#fffbeb','#b45309','#fde68a'],
                            'en_process' => ['#f5f3ff','#6d28d9','#ddd6fe'],
                            'completed'  => ['#f0fdf4','#059669','#6ee7b7'],
                            default      => ['#f1f5f9','#64748b','#e2e8f0'],
                        };
                        $rtIcon = match($rt->status){
                            'pending'    => 'bi-clock',
                            'en_process' => 'bi-tools',
                            'completed'  => 'bi-check-circle-fill',
                            default      => 'bi-circle',
                        };
                        $rtLabel = match($rt->status){
                            'pending'    => 'Scheduled',
                            'en_process' => 'In Progress',
                            'completed'  => 'Completed',
                            default      => ucfirst(str_replace('_',' ',$rt->status)),
                        };
                        $adminPh = $rt->fotosAdmin ?? collect();
                        $crewPh  = $rt->fotosCrew  ?? collect();
                        $phTotal = $adminPh->count() + $crewPh->count();
                    @endphp
                    <div style="padding:1rem 1.3rem;border-bottom:1px solid var(--line);border-right:1px solid var(--line);transition:background .15s;position:relative;"
                         onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">

                        {{-- Top row --}}
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.65rem;margin-bottom:.65rem;">
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem;flex-wrap:wrap;">
                                    <span style="font-size:.62rem;font-weight:700;color:var(--ink4);background:var(--line2);padding:.12rem .48rem;border-radius:99px;">
                                        RT-{{ str_pad($rt->id,4,'0',STR_PAD_LEFT) }}
                                    </span>
                                    <span style="font-size:.62rem;color:var(--ink4);display:flex;align-items:center;gap:.2rem;">
                                        <i class="bi bi-calendar3" style="font-size:.58rem;"></i>
                                        {{ \Carbon\Carbon::parse($rt->repair_date)->format('M d, Y') }}
                                    </span>
                                </div>
                                <p style="font-size:.82rem;font-weight:600;color:var(--ink);line-height:1.45;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $rt->description }}
                                </p>
                            </div>
                            <span style="display:inline-flex;align-items:center;gap:.25rem;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:.2rem .62rem;border-radius:99px;background:{{ $rtBg }};color:{{ $rtColor }};border:1px solid {{ $rtBd }};flex-shrink:0;white-space:nowrap;">
                                <i class="bi {{ $rtIcon }}" style="font-size:.6rem;"></i>
                                {{ $rtLabel }}
                            </span>
                        </div>

                        {{-- Photo strip --}}
                        @if($phTotal)
                            <div style="display:flex;gap:.35rem;flex-wrap:wrap;margin-bottom:.65rem;">
                                @foreach($adminPh->take(3) as $foto)
                                    @php $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url); @endphp
                                    <div style="position:relative;flex-shrink:0;">
                                        <a href="{{ $url }}" target="_blank">
                                            <img src="{{ $url }}" alt="" style="width:50px;height:50px;border-radius:7px;object-fit:cover;border:2px solid #ffb347;display:block;">
                                        </a>
                                        <span style="position:absolute;bottom:2px;left:2px;font-size:7px;font-weight:700;background:rgba(255,179,71,.9);color:#7c3a00;padding:1px 4px;border-radius:3px;text-transform:uppercase;">dmg</span>
                                    </div>
                                @endforeach
                                @foreach($crewPh->take(3) as $foto)
                                    @php $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url); @endphp
                                    <div style="position:relative;flex-shrink:0;">
                                        <a href="{{ $url }}" target="_blank">
                                            <img src="{{ $url }}" alt="" style="width:50px;height:50px;border-radius:7px;object-fit:cover;border:2px solid #a5f3fc;display:block;">
                                        </a>
                                        <span style="position:absolute;bottom:2px;left:2px;font-size:7px;font-weight:700;background:rgba(8,145,178,.85);color:#fff;padding:1px 4px;border-radius:3px;text-transform:uppercase;">work</span>
                                    </div>
                                @endforeach
                                @if($phTotal > 6)
                                    <div style="width:50px;height:50px;border-radius:7px;background:var(--cyan-bg);border:1.5px solid var(--cyan-bd);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;color:var(--cyan);flex-shrink:0;">
                                        +{{ $phTotal - 6 }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Footer actions --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:.6rem;border-top:1px dashed var(--line);">
                            <div style="display:flex;align-items:center;gap:.85rem;font-size:.67rem;color:var(--ink4);">
                                @if($adminPh->count())
                                    <span style="display:flex;align-items:center;gap:.25rem;">
                                        <i class="bi bi-camera-fill" style="font-size:.6rem;color:#e65100;"></i>
                                        {{ $adminPh->count() }} dmg
                                    </span>
                                @endif
                                @if($crewPh->count())
                                    <span style="display:flex;align-items:center;gap:.25rem;">
                                        <i class="bi bi-person-fill-gear" style="font-size:.6rem;color:var(--cyan);"></i>
                                        {{ $crewPh->count() }} work
                                    </span>
                                @endif
                                @if(!$adminPh->count() && !$crewPh->count())
                                    <span style="display:flex;align-items:center;gap:.25rem;">
                                        <i class="bi bi-camera" style="font-size:.6rem;"></i> No photos
                                    </span>
                                @endif
                                <span style="display:flex;align-items:center;gap:.25rem;">
                                    <i class="bi bi-clock" style="font-size:.6rem;"></i>
                                    {{ $rt->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <a href="{{ route('repair-tickets.edit', $rt->id) }}"
                               style="display:inline-flex;align-items:center;gap:.25rem;font-size:.65rem;font-weight:600;padding:.2rem .6rem;border-radius:6px;background:var(--line2);color:var(--ink2);text-decoration:none;border:1px solid var(--line);transition:all .15s;"
                               onmouseover="this.style.background='var(--line)'" onmouseout="this.style.background='var(--line2)'">
                                <i class="bi bi-pencil" style="font-size:.6rem;"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bottom CTA --}}
            <div style="padding:1rem 1.3rem;background:linear-gradient(to right,var(--cyan-bg),#f0f9ff);border-top:1px solid var(--cyan-bd);display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:.55rem;">
                    <i class="bi bi-tools" style="color:var(--cyan);font-size:.85rem;"></i>
                    <span style="font-size:.75rem;font-weight:600;color:var(--ink2);">
                        {{ $rtTotal }} repair ticket{{ $rtTotal !== 1 ? 's' : '' }} total
                        @if($rtPending) · <span style="color:var(--amber);">{{ $rtPending }} scheduled</span>@endif
                    </span>
                </div>
                <a href="{{ route('repair-tickets.index', ['ref_type'=>'job','ref_id'=>$job->id]) }}"
                   style="display:inline-flex;align-items:center;gap:.4rem;font-family:'Montserrat',sans-serif;font-weight:700;font-size:.75rem;padding:.42rem 1rem;border-radius:8px;background:var(--cyan);color:#fff;text-decoration:none;box-shadow:0 4px 10px rgba(8,145,178,.28);transition:filter .15s;white-space:nowrap;"
                   onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter=''">
                    <i class="bi bi-plus-lg"></i> New Repair Ticket
                </a>
            </div>
        @endif
    </div>

</div>
</div>

{{-- Delete confirm --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmJobDel(id) {
    Swal.fire({
        title: 'Delete Job Request?',
        text: 'This will permanently delete the job and all related data.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#334155',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then(result => {
        if (result.isConfirmed) document.getElementById('del-form-' + id).submit();
    });
}
</script>

@endsection