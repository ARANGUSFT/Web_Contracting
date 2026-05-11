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
.ep{min-height:100vh;padding:1.75rem 1.5rem 3rem;}
.ep-hero{background:var(--ink);border-radius:18px;padding:1.5rem 1.75rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;position:relative;overflow:hidden;}
.ep-hero::before{content:'';position:absolute;right:-80px;top:-80px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(8,145,178,.2) 0%,transparent 70%);pointer-events:none;}
.ep-hero::after{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,#67e8f9,var(--cyan));border-radius:18px 0 0 18px;}
.ep-hero-left{position:relative;display:flex;align-items:center;gap:1rem;}
.ep-hero-badge{width:46px;height:46px;border-radius:13px;background:rgba(8,145,178,.18);border:1px solid rgba(8,145,178,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ep-eye{font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--ink4);margin-bottom:.25rem;}
.ep-title{font-size:1.3rem;font-weight:800;color:#fff;letter-spacing:-.03em;line-height:1.1;margin-bottom:.2rem;}
.ep-sub{font-size:.7rem;color:var(--ink4);display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;}
.ep-actions{display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;position:relative;}
.btn-g{display:inline-flex;align-items:center;gap:.35rem;font-family:'Montserrat',sans-serif;font-weight:600;font-size:.75rem;padding:.4rem .9rem;border-radius:8px;text-decoration:none;white-space:nowrap;transition:all .18s;cursor:pointer;border:none;}
.btn-back{background:rgba(255,255,255,.07);color:var(--ink4);border:1px solid rgba(255,255,255,.1);}
.btn-back:hover{color:#fff;background:rgba(255,255,255,.13);}
.btn-save{background:var(--cyan);color:#fff;font-weight:700;box-shadow:0 4px 12px rgba(8,145,178,.35);}
.btn-save:hover{filter:brightness(1.1);color:#fff;}
.btn-del{background:rgba(220,38,38,.15);color:#fca5a5;border:1px solid rgba(220,38,38,.3);}
.btn-del:hover{background:rgba(220,38,38,.25);color:#fca5a5;}
.ep-grid{display:grid;grid-template-columns:1fr 400px;gap:1.1rem;align-items:start;}
.card{background:var(--white);border-radius:16px;border:1px solid var(--line);overflow:hidden;box-shadow:0 1px 5px rgba(15,23,42,.05);margin-bottom:1.1rem;}
.card:last-child{margin-bottom:0;}
.card-head{padding:.85rem 1.25rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:.55rem;}
.card-head-icon{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0;}
.card-head h6{font-size:.8rem;font-weight:700;color:var(--ink);margin:0;letter-spacing:-.01em;}
.card-body{padding:1.1rem 1.25rem;}
.f-label{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink3);margin-bottom:.25rem;display:block;}
.f-ctrl{font-family:'Montserrat',sans-serif;font-size:.84rem;width:100%;border:1.5px solid var(--line);border-radius:9px;padding:.5rem .82rem;color:var(--ink);background:var(--white);transition:border-color .2s,box-shadow .2s;outline:none;appearance:auto;}
.f-ctrl:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(8,145,178,.1);}
textarea.f-ctrl{resize:vertical;min-height:110px;}
.f-row{margin-bottom:.9rem;}
.f-row:last-child{margin-bottom:0;}
.f-2col{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.proj-ref{display:flex;align-items:center;gap:.75rem;padding:.85rem 1rem;border-radius:11px;border:1.5px solid;background:var(--cyan-bg);border-color:var(--cyan-bd);}
.proj-ref.emerg{background:#fff1f2;border-color:var(--red-bd);}
.proj-ref-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.8rem;}
.proj-ref-num{font-size:.82rem;font-weight:700;color:var(--ink);}
.proj-ref-sub{font-size:.67rem;color:var(--ink3);margin-top:2px;}
.proj-ref-link{margin-left:auto;font-size:.68rem;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:.25rem;transition:opacity .15s;flex-shrink:0;}
.proj-ref-link:hover{opacity:.7;}
.ph-section-title{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.65rem;display:flex;align-items:center;gap:.5rem;}
.ph-section-title::after{content:'';flex:1;height:1px;background:var(--line);}
.ph-grid{display:flex;flex-wrap:wrap;gap:.55rem;margin-bottom:.85rem;}
.ph-item{position:relative;width:76px;height:76px;border-radius:9px;overflow:hidden;border:2px solid var(--line);flex-shrink:0;transition:border-color .15s,transform .12s;}
.ph-item:hover{transform:scale(1.04);}
.ph-item.admin-item{border-color:#ffb347;}
.ph-item.crew-item{border-color:var(--cyan-bd);}
.ph-item img{width:100%;height:100%;object-fit:cover;display:block;}
.ph-badge{position:absolute;bottom:3px;left:3px;font-size:7px;font-weight:700;text-transform:uppercase;padding:1px 5px;border-radius:4px;}
.ph-badge.admin{background:rgba(255,179,71,.92);color:#7c3a00;}
.ph-badge.crew{background:rgba(8,145,178,.88);color:#fff;}
.btn-ph-del{position:absolute;top:3px;right:3px;width:18px;height:18px;border-radius:50%;background:rgba(220,38,38,.88);border:none;color:#fff;font-size:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;opacity:0;transition:opacity .15s;}
.ph-item:hover .btn-ph-del{opacity:1;}
.ph-empty{text-align:center;padding:1.25rem;color:var(--ink4);font-size:.75rem;font-style:italic;border:1.5px dashed var(--line);border-radius:10px;}
.up-zone{border:2px dashed var(--line);border-radius:11px;padding:1rem;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:var(--line2);}
.up-zone:hover{border-color:var(--cyan);background:var(--cyan-bg);}
.up-zone input[type="file"]{display:none;}
.up-zone i{font-size:1.3rem;color:var(--ink4);opacity:.45;display:block;margin-bottom:.3rem;}
.up-title{font-size:.72rem;font-weight:600;color:var(--ink2);}
.up-sub{font-size:.63rem;color:var(--ink4);margin-top:.18rem;}
.up-preview{margin-top:.6rem;display:none;flex-wrap:wrap;gap:.4rem;}
.up-thumb{position:relative;width:60px;height:60px;border-radius:7px;overflow:hidden;border:1.5px solid var(--line);}
.up-thumb img{width:100%;height:100%;object-fit:cover;display:block;}
.up-thumb-pdf{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;background:var(--line2);font-size:8px;font-weight:600;color:var(--ink3);}
.btn-up-rm{position:absolute;top:2px;right:2px;width:16px;height:16px;border-radius:50%;background:rgba(15,23,42,.75);border:none;color:#fff;font-size:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;}
.act-row{display:flex;gap:.65rem;padding:.6rem 0;border-bottom:1px solid var(--line2);}
.act-row:last-child{border-bottom:none;}
.act-dot{width:8px;height:8px;border-radius:50%;margin-top:5px;flex-shrink:0;}
.act-txt{font-size:.75rem;color:var(--ink2);line-height:1.4;}
.act-time{font-size:.65rem;color:var(--ink4);margin-top:2px;}
.flash-ok{display:flex;align-items:center;gap:.55rem;background:var(--green-bg);border:1.5px solid var(--green-bd);border-radius:10px;padding:.7rem 1rem;margin-bottom:1rem;font-size:.78rem;font-weight:600;color:#065f46;}
@media(max-width:1024px){.ep-grid{grid-template-columns:1fr;}}
@media(max-width:640px){.ep{padding:1rem .75rem 2.5rem;}.ep-hero{padding:1.1rem 1.25rem;}.ep-title{font-size:1.1rem;}.f-2col{grid-template-columns:1fr;}}
</style>

<div class="ep">
<div class="container-xl px-0" style="max-width:1100px;">

    {{-- Hero --}}
    <div class="ep-hero">
        <div class="ep-hero-left">
            <div class="ep-hero-badge">
                <i class="bi bi-tools" style="color:#67e8f9;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="ep-eye">Editing Repair Ticket</div>
                <h1 class="ep-title">RT-{{ str_pad($repairTicket->id,4,'0',STR_PAD_LEFT) }}</h1>
                <div class="ep-sub">
                    @if($repairTicket->reference_type === 'job' && $repairTicket->jobRequest)
                        <span><i class="bi bi-briefcase" style="font-size:.6rem;"></i> {{ $repairTicket->jobRequest->job_number_name }}</span>
                    @elseif($repairTicket->emergency)
                        <span><i class="bi bi-exclamation-octagon" style="font-size:.6rem;"></i> {{ $repairTicket->emergency->job_number_name }}</span>
                    @endif
                    <span style="color:rgba(255,255,255,.15);">|</span>
                    <span><i class="bi bi-calendar3" style="font-size:.6rem;"></i> {{ \Carbon\Carbon::parse($repairTicket->repair_date)->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
        <div class="ep-actions">
            <a href="{{ route('repair-tickets.index', ['ref_type'=>$repairTicket->reference_type,'ref_id'=>$repairTicket->reference_id]) }}"
               class="btn-g btn-back"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="button" class="btn-g btn-del" onclick="confirmDelTicket()">
                <i class="bi bi-trash3"></i> Delete
            </button>
            <button type="submit" form="editForm" class="btn-g btn-save">
                <i class="bi bi-check-circle"></i> Save Changes
            </button>
        </div>
    </div>

    @if(session('repair_success'))
        <div class="flash-ok"><i class="bi bi-check-circle-fill"></i> {{ session('repair_success') }}</div>
    @endif

    <form method="POST" action="{{ route('repair-tickets.update', $repairTicket->id) }}"
          enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')

        <div class="ep-grid">

            {{-- LEFT ── Details + Photos ── --}}
            <div>

                <div class="card">
                    <div class="card-head" style="background:linear-gradient(to right,var(--cyan-bg),#fafbfd);">
                        <div class="card-head-icon" style="background:var(--cyan-bg);color:var(--cyan);"><i class="bi bi-info-circle-fill"></i></div>
                        <h6>Ticket Details</h6>
                    </div>
                    <div class="card-body">

                        <div class="f-row">
                            <label class="f-label">Project</label>
                            @if($repairTicket->reference_type === 'job' && $repairTicket->jobRequest)
                                <div class="proj-ref">
                                    <div class="proj-ref-icon" style="background:rgba(8,145,178,.1);border:1px solid var(--cyan-bd);color:var(--cyan);"><i class="bi bi-briefcase"></i></div>
                                    <div>
                                        <div class="proj-ref-num">{{ $repairTicket->jobRequest->job_number_name }}</div>
                                        <div class="proj-ref-sub">Job Request · {{ $repairTicket->jobRequest->company_name }}</div>
                                    </div>
                                    <a href="{{ route('jobs.show', $repairTicket->reference_id) }}" class="proj-ref-link" style="color:var(--cyan);">
                                        <i class="bi bi-arrow-up-right-from-square" style="font-size:.65rem;"></i> Open
                                    </a>
                                </div>
                            @elseif($repairTicket->emergency)
                                <div class="proj-ref emerg">
                                    <div class="proj-ref-icon" style="background:rgba(220,38,38,.1);border:1px solid var(--red-bd);color:var(--red);"><i class="bi bi-exclamation-octagon"></i></div>
                                    <div>
                                        <div class="proj-ref-num">{{ $repairTicket->emergency->job_number_name }}</div>
                                        <div class="proj-ref-sub">Emergency · {{ $repairTicket->emergency->company_name }}</div>
                                    </div>
                                    <a href="{{ route('emergency.show', $repairTicket->reference_id) }}" class="proj-ref-link" style="color:var(--red);">
                                        <i class="bi bi-arrow-up-right-from-square" style="font-size:.65rem;"></i> Open
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Status — read-only display, crew updates from mobile ── --}}
                        <input type="hidden" name="status" value="{{ $repairTicket->status }}">

                        <div class="f-2col">
                            <div class="f-row">
                                <label class="f-label" for="repair_date">Repair Date</label>
                                <input type="date" name="repair_date" id="repair_date" class="f-ctrl"
                                       value="{{ \Carbon\Carbon::parse($repairTicket->repair_date)->format('Y-m-d') }}" required>
                            </div>
                            <div class="f-row">
                                <label class="f-label">Status</label>
                                @php
                                    $badgeSt = match($repairTicket->status){
                                        'pending'    => ['bg'=>'var(--amber-bg)','color'=>'var(--amber)','bd'=>'var(--amber-bd)','icon'=>'bi-clock','label'=>'Scheduled'],
                                        'en_process' => ['bg'=>'var(--purple-bg)','color'=>'var(--purple)','bd'=>'var(--purple-bd)','icon'=>'bi-tools','label'=>'In Progress'],
                                        'completed'  => ['bg'=>'var(--green-bg)','color'=>'var(--green)','bd'=>'var(--green-bd)','icon'=>'bi-check-circle-fill','label'=>'Completed'],
                                        default      => ['bg'=>'var(--line2)','color'=>'var(--ink3)','bd'=>'var(--line)','icon'=>'bi-circle','label'=>ucfirst(str_replace('_',' ',$repairTicket->status))],
                                    };
                                @endphp
                                <div style="display:flex;align-items:center;gap:.5rem;padding:.5rem .82rem;border-radius:9px;border:1.5px solid {{ $badgeSt['bd'] }};background:{{ $badgeSt['bg'] }};height:2.35rem;">
                                    <i class="bi {{ $badgeSt['icon'] }}" style="font-size:.75rem;color:{{ $badgeSt['color'] }};"></i>
                                    <span style="font-size:.8rem;font-weight:700;color:{{ $badgeSt['color'] }};">{{ $badgeSt['label'] }}</span>
                                    <span style="margin-left:auto;font-size:.6rem;color:var(--ink4);">crew updates</span>
                                </div>
                            </div>
                        </div>

                        <div class="f-row">
                            <label class="f-label" for="description">Damage Description <span style="color:var(--red);">*</span></label>
                            <textarea name="description" id="description" class="f-ctrl" rows="5" required>{{ $repairTicket->description }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Photos --}}
                <div class="card">
                    <div class="card-head" style="background:linear-gradient(to right,var(--line2),#fafbfd);">
                        <div class="card-head-icon" style="background:var(--line2);color:var(--ink3);"><i class="bi bi-images"></i></div>
                        <h6>Photos</h6>
                        @php
                            $adminFotos = $repairTicket->fotosAdmin ?? collect();
                            $crewFotos  = $repairTicket->fotosCrew  ?? collect();
                            $totalPh    = $adminFotos->count() + $crewFotos->count();
                        @endphp
                        @if($totalPh)
                            <span style="margin-left:auto;font-size:.65rem;font-weight:700;background:var(--cyan-bg);color:var(--cyan);border:1px solid var(--cyan-bd);padding:.15rem .55rem;border-radius:99px;">
                                {{ $totalPh }} total
                            </span>
                        @endif
                    </div>
                    <div class="card-body">

                        <div class="ph-section-title" style="color:#e65100;">
                            <i class="bi bi-camera-fill" style="font-size:.65rem;"></i>
                            Damage Photos — Admin ({{ $adminFotos->count() }})
                        </div>
                        @if($adminFotos->count())
                            <div class="ph-grid">
                                @foreach($adminFotos as $index => $foto)
                                    @php
                                        $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url);
                                        $ext = strtolower(pathinfo($foto->url, PATHINFO_EXTENSION));
                                    @endphp
                                    @if(in_array($ext,['jpg','jpeg','png','gif','webp']))
                                        <div class="ph-item admin-item">
                                            <a href="{{ $url }}" target="_blank"><img src="{{ $url }}" alt="Damage"></a>
                                            <span class="ph-badge admin">dmg</span>
                                            <button type="button" class="btn-ph-del"
                                                    onclick="delPhoto({{ $repairTicket->id }},{{ $index }},this)"><i class="bi bi-x" style="font-size:10px;"></i></button>
                                        </div>
                                    @else
                                        <div class="ph-item admin-item" style="cursor:pointer;" onclick="window.open('{{ $url }}','_blank')">
                                            <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;background:var(--line2);">
                                                <i class="bi bi-file-pdf-fill" style="font-size:1.3rem;color:#ef4444;"></i>
                                                <span style="font-size:8px;font-weight:700;color:var(--ink3);">PDF</span>
                                            </div>
                                            <span class="ph-badge admin">dmg</span>
                                            <button type="button" class="btn-ph-del"
                                                    onclick="event.stopPropagation();delPhoto({{ $repairTicket->id }},{{ $index }},this)"><i class="bi bi-x" style="font-size:10px;"></i></button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="ph-empty" style="margin-bottom:.85rem;">No damage photos yet.</div>
                        @endif

                        <div class="ph-section-title" style="color:var(--cyan);">
                            <i class="bi bi-helmet-safety" style="font-size:.65rem;"></i>
                            Work Photos — Crew ({{ $crewFotos->count() }})
                        </div>
                        @if($crewFotos->count())
                            <div class="ph-grid">
                                @foreach($crewFotos as $foto)
                                    @php
                                        $url = str_starts_with($foto->url,'http') ? $foto->url : asset('storage/'.$foto->url);
                                        $ext = strtolower(pathinfo($foto->url, PATHINFO_EXTENSION));
                                    @endphp
                                    @if(in_array($ext,['jpg','jpeg','png','gif','webp']))
                                        <div class="ph-item crew-item">
                                            <a href="{{ $url }}" target="_blank"><img src="{{ $url }}" alt="Work"></a>
                                            <span class="ph-badge crew">work</span>
                                        </div>
                                    @else
                                        <div class="ph-item crew-item" style="cursor:pointer;" onclick="window.open('{{ $url }}','_blank')">
                                            <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;background:var(--line2);">
                                                <i class="bi bi-file-pdf-fill" style="font-size:1.3rem;color:#ef4444;"></i>
                                                <span style="font-size:8px;font-weight:700;color:var(--ink3);">PDF</span>
                                            </div>
                                            <span class="ph-badge crew">work</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="ph-empty" style="margin-bottom:.85rem;">No work photos uploaded by crew yet.</div>
                        @endif

                        <div class="ph-section-title" style="color:var(--ink3);margin-top:.5rem;">
                            <i class="bi bi-cloud-upload" style="font-size:.65rem;"></i>
                            Add Damage Photos
                        </div>
                        <div class="up-zone" id="upZone"
                             onclick="document.getElementById('upInput').click()"
                             ondragover="upDragOver(event)" ondragleave="upDragLeave(event)" ondrop="upDrop(event)">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="up-title">Click or drag photos here</div>
                            <div class="up-sub">JPG, PNG, WEBP, PDF — multiple files allowed</div>
                            <input type="file" id="upInput" name="photos[]" accept="image/*,.pdf" multiple onchange="upHandleFiles(this.files)">
                        </div>
                        <div id="upPreview" class="up-preview"></div>

                    </div>
                </div>

            </div>

            {{-- RIGHT ── Status + Info ── --}}
            <div>

                <div class="card">
                    <div class="card-head" style="background:linear-gradient(to right,var(--line2),#fafbfd);">
                        <div class="card-head-icon" style="background:var(--line2);color:var(--ink3);"><i class="bi bi-activity"></i></div>
                        <h6>Ticket Status</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $stColors = [
                                'pending'    => ['bg'=>'var(--amber-bg)','color'=>'var(--amber)','bd'=>'var(--amber-bd)','icon'=>'bi-clock'],
                                'en_process' => ['bg'=>'var(--purple-bg)','color'=>'var(--purple)','bd'=>'var(--purple-bd)','icon'=>'bi-tools'],
                                'completed'  => ['bg'=>'var(--green-bg)','color'=>'var(--green)','bd'=>'var(--green-bd)','icon'=>'bi-check-circle-fill'],
                            ];
                            $stLabel = ['pending'=>'Scheduled','en_process'=>'In Progress','completed'=>'Completed'];
                            $sc = $stColors[$repairTicket->status] ?? ['bg'=>'var(--line2)','color'=>'var(--ink3)','bd'=>'var(--line)','icon'=>'bi-circle'];
                            $sl = $stLabel[$repairTicket->status] ?? ucfirst(str_replace('_',' ',$repairTicket->status));
                        @endphp
                        <div style="display:flex;align-items:center;gap:.85rem;padding:1rem;border-radius:12px;background:{{ $sc['bg'] }};border:1.5px solid {{ $sc['bd'] }};margin-bottom:1rem;">
                            <div style="width:40px;height:40px;border-radius:11px;background:rgba(255,255,255,.5);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi {{ $sc['icon'] }}" style="font-size:1.1rem;color:{{ $sc['color'] }};"></i>
                            </div>
                            <div>
                                <div style="font-size:.8rem;font-weight:700;color:{{ $sc['color'] }};">{{ $sl }}</div>
                                <div style="font-size:.67rem;color:var(--ink3);margin-top:2px;">Last updated {{ $repairTicket->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;">
                            <div style="background:var(--line2);border-radius:10px;padding:.75rem;text-align:center;">
                                <div style="font-size:1.1rem;font-weight:800;color:var(--ink);letter-spacing:-.03em;">{{ $adminFotos->count() }}</div>
                                <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#e65100;margin-top:2px;">Damage</div>
                            </div>
                            <div style="background:var(--line2);border-radius:10px;padding:.75rem;text-align:center;">
                                <div style="font-size:1.1rem;font-weight:800;color:var(--ink);letter-spacing:-.03em;">{{ $crewFotos->count() }}</div>
                                <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--cyan);margin-top:2px;">Work</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="card">
                    <div class="card-head" style="background:linear-gradient(to right,var(--line2),#fafbfd);">
                        <div class="card-head-icon" style="background:var(--line2);color:var(--ink3);"><i class="bi bi-clock-history"></i></div>
                        <h6>Timeline</h6>
                    </div>
                    <div class="card-body">
                        <div class="act-row">
                            <div class="act-dot" style="background:var(--green);"></div>
                            <div>
                                <div class="act-txt">Ticket created</div>
                                <div class="act-time">{{ $repairTicket->created_at->format('M d, Y · H:i') }}</div>
                            </div>
                        </div>
                        <div class="act-row">
                            <div class="act-dot" style="background:var(--cyan);"></div>
                            <div>
                                <div class="act-txt">Last updated · {{ $repairTicket->updated_at->diffForHumans() }}</div>
                                <div class="act-time">{{ $repairTicket->updated_at->format('M d, Y · H:i') }}</div>
                            </div>
                        </div>
                        <div class="act-row">
                            <div class="act-dot" style="background:var(--amber);"></div>
                            <div>
                                <div class="act-txt">Scheduled repair date</div>
                                <div class="act-time">{{ \Carbon\Carbon::parse($repairTicket->repair_date)->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Save box --}}
                <div style="background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:16px;padding:1.1rem 1.25rem;display:flex;align-items:center;gap:.85rem;margin-bottom:1.1rem;">
                    <div style="flex:1;">
                        <div style="font-size:.75rem;font-weight:700;color:#fff;margin-bottom:.2rem;">Ready to save?</div>
                        <div style="font-size:.65rem;color:rgba(255,255,255,.5);">Changes will update this repair ticket.</div>
                    </div>
                    <button type="submit" form="editForm"
                            style="display:inline-flex;align-items:center;gap:.4rem;font-family:'Montserrat',sans-serif;font-weight:700;font-size:.78rem;padding:.55rem 1.2rem;border-radius:9px;border:none;background:#fff;color:var(--cyan);cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,.2);transition:filter .2s;white-space:nowrap;">
                        <i class="bi bi-check-circle"></i> Save Changes
                    </button>
                </div>

                {{-- Danger zone --}}
                <div style="background:var(--red-bg);border:1px solid var(--red-bd);border-radius:12px;padding:.85rem 1.1rem;display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap;">
                    <div>
                        <div style="font-size:.78rem;font-weight:700;color:var(--red);">Delete Ticket</div>
                        <div style="font-size:.68rem;color:var(--ink4);margin-top:1px;">This action cannot be undone.</div>
                    </div>
                    <button type="button" onclick="confirmDelTicket()"
                            style="display:inline-flex;align-items:center;gap:.35rem;font-family:'Montserrat',sans-serif;font-weight:700;font-size:.75rem;padding:.42rem 1rem;border-radius:8px;background:var(--red);color:#fff;border:none;cursor:pointer;transition:filter .18s;">
                        <i class="bi bi-trash3"></i> Delete
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>
</div>

<form id="delTicketForm" method="POST" action="{{ route('repair-tickets.destroy', $repairTicket->id) }}" style="display:none;">
    @csrf @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelTicket() {
    Swal.fire({
        title:'Delete this ticket?',text:'This action cannot be undone.',icon:'warning',
        showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#334155',
        confirmButtonText:'Yes, delete',cancelButtonText:'Cancel',reverseButtons:true,
    }).then(r=>{ if(r.isConfirmed) document.getElementById('delTicketForm').submit(); });
}

function delPhoto(ticketId, index, btn) {
    Swal.fire({
        title:'Delete photo?',text:'Cannot be undone.',icon:'warning',
        showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#334155',
        confirmButtonText:'Delete',cancelButtonText:'Cancel',reverseButtons:true,
    }).then(async r=>{
        if(!r.isConfirmed) return;
        try{
            const res  = await fetch(`/repair-tickets/${ticketId}/photos/${index}`,{
                method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
            });
            const data = await res.json();
            if(data.success){
                const item=btn.closest('.ph-item');
                item.style.transition='opacity .2s,transform .2s'; item.style.opacity='0'; item.style.transform='scale(.8)';
                setTimeout(()=>item.remove(),200);
                Swal.fire({icon:'success',title:'Deleted',timer:1200,showConfirmButton:false});
            }else{ Swal.fire('Error',data.message||'Something went wrong.','error'); }
        }catch{ Swal.fire('Error','Connection error.','error'); }
    });
}

let upFiles = [];
function upDragOver(e)  { e.preventDefault(); const z=document.getElementById('upZone'); z.style.borderColor='var(--cyan)'; z.style.background='var(--cyan-bg)'; }
function upDragLeave(e) { if(!upFiles.filter(Boolean).length){const z=document.getElementById('upZone');z.style.borderColor='';z.style.background='';} }
function upDrop(e)      { e.preventDefault(); upHandleFiles(e.dataTransfer.files); }
function upHandleFiles(files) {
    Array.from(files).forEach(f=>{ upFiles.push(f); upRenderThumb(f, upFiles.length-1); });
    upSync();
    const z=document.getElementById('upZone'); z.style.borderColor='var(--cyan)'; z.style.background='var(--cyan-bg)';
}
function upRenderThumb(file, idx) {
    const grid=document.getElementById('upPreview'); grid.style.display='flex';
    const wrap=document.createElement('div'); wrap.id='up-th-'+idx; wrap.className='up-thumb';
    if(file.type==='application/pdf'){
        wrap.innerHTML=`<div class="up-thumb-pdf"><i class="bi bi-file-pdf-fill" style="font-size:1.1rem;color:#ef4444;"></i>${file.name.slice(0,10)}</div>`;
    }else{
        const img=document.createElement('img'); img.src=URL.createObjectURL(file); wrap.appendChild(img);
    }
    const rm=document.createElement('button'); rm.type='button'; rm.className='btn-up-rm'; rm.innerHTML='&times;'; rm.onclick=()=>upRemove(idx);
    wrap.appendChild(rm); grid.appendChild(wrap);
}
function upRemove(idx) {
    upFiles[idx]=null;
    const el=document.getElementById('up-th-'+idx); if(el)el.remove();
    if(upFiles.every(f=>f===null)){const z=document.getElementById('upZone');z.style.borderColor='';z.style.background='';document.getElementById('upPreview').style.display='none';}
    upSync();
}
function upSync() {
    const dt=new DataTransfer(); upFiles.filter(Boolean).forEach(f=>dt.items.add(f));
    document.getElementById('upInput').files=dt.files;
}
</script>
@endsection