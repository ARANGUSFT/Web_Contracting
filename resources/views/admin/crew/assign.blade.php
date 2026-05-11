@extends('admin.layouts.superadmin')
@section('title', 'Assign Subcontractors · ' . $crew->name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ca { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1300px; }

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
.ca-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 36px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ca-hero-glow {
    position: absolute; pointer-events: none;
    width: 500px; height: 260px;
    background: radial-gradient(ellipse, rgba(24,85,224,.35) 0%, transparent 70%);
    right: -40px; top: -40px;
}
.ca-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#4f80ff 0%,#1855e0 50%,transparent 100%);
    border-radius: 0 2px 2px 0;
}
.ca-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
}
.ca-hero-left { position: relative; display: flex; align-items: center; gap: 16px; }
.ca-hero-badge {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #8aadff;
}
.ca-hero-title { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -.4px; line-height: 1; }
.ca-hero-crew  { font-size: 13px; font-weight: 700; color: #8aadff; margin-top: 4px; }
.ca-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }
.ca-stat-chip {
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px; padding: 10px 16px; text-align: center;
}
.ca-stat-chip-n { font-size: 20px; font-weight: 800; color: #fff; line-height: 1; }
.ca-stat-chip-l { font-size: 9.5px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }
.ca-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.ca-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── NOTICE ── */
.ca-notice {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 18px; border-radius: var(--rlg);
    background: var(--alt); border: 1px solid var(--abd);
    margin-bottom: 20px;
    font-size: 12.5px; font-weight: 600; color: #78350f;
}
.ca-notice i { color: var(--amb); font-size: 14px; flex-shrink: 0; }

/* ── LAYOUT ── */
.ca-layout { display: grid; grid-template-columns: 1fr 300px; gap: 18px; align-items: start; }

/* ── MAIN CARD ── */
.ca-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
}
.ca-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.ca-card-head-l { display: flex; align-items: center; gap: 10px; }
.ca-card-title  { font-size: 13.5px; font-weight: 800; color: var(--ink); letter-spacing: -.2px; }
.ca-selected-badge {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 9999px; background: var(--blt);
    color: var(--blue); border: 1px solid var(--bbd);
}

/* ── SEARCH + CONTROLS ── */
.ca-toolbar { display: flex; align-items: center; gap: 8px; padding: 14px 22px; border-bottom: 1px solid var(--bd2); }
.ca-search-wrap { position: relative; flex: 1; }
.ca-search-ico  { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--ink3); font-size: 12px; pointer-events: none; }
.ca-search-input {
    width: 100%; padding: 9px 12px 9px 32px;
    border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.ca-search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.ca-ctrl-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 9px 14px; border-radius: var(--r);
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid var(--bd); cursor: pointer; transition: all .13s; white-space: nowrap;
    background: var(--surf); color: var(--ink2);
}
.ca-ctrl-btn:hover { background: var(--bg); color: var(--ink); }
.ca-ctrl-btn.blue { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.ca-ctrl-btn.blue:hover { background: #dbeafe; }

/* ── SUB GRID ── */
.ca-sub-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(260px,1fr));
    gap: 10px; padding: 18px;
    max-height: 540px; overflow-y: auto;
    scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg);
}
.ca-sub-grid::-webkit-scrollbar { width: 4px; }
.ca-sub-grid::-webkit-scrollbar-track { background: var(--bg); }
.ca-sub-grid::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

/* ── SUB CARD ── */
.ca-sub {
    border: 1px solid var(--bd); border-radius: var(--rlg);
    padding: 12px 14px; background: var(--surf);
    transition: border-color .13s, box-shadow .13s, transform .13s;
    cursor: pointer; position: relative;
}
.ca-sub:hover:not(.ca-sub-locked) { border-color: var(--blue); transform: translateY(-1px); box-shadow: 0 3px 12px rgba(0,0,0,.07); }
.ca-sub.checked { border-color: var(--blue); background: var(--blt); }
.ca-sub.assigned-here { border-color: var(--grn); background: var(--glt); }
.ca-sub-locked { opacity: .55; cursor: not-allowed; }

.ca-sub-inner { display: flex; align-items: flex-start; gap: 10px; }
.ca-sub-check { flex-shrink: 0; margin-top: 2px; }
.ca-sub-cb { display: none; }
.ca-sub-box {
    width: 17px; height: 17px; border-radius: 5px; flex-shrink: 0;
    border: 1.5px solid var(--bd); background: var(--surf);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; color: #fff; transition: all .13s; cursor: pointer;
}
.ca-sub-cb:checked ~ * .ca-sub-box,
.ca-sub.checked .ca-sub-box { background: var(--blue); border-color: var(--blue); }
.ca-sub.assigned-here .ca-sub-box { background: var(--grn); border-color: var(--grn); }

.ca-sub-av {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; color: #fff;
    background: linear-gradient(135deg,#1855e0,#5b8af7);
}
.ca-sub.assigned-here .ca-sub-av { background: linear-gradient(135deg,#0d9e6a,#34d399); }
.ca-sub-locked .ca-sub-av { background: linear-gradient(135deg,#9ca3af,#d1d5db); }

.ca-sub-info  { flex: 1; min-width: 0; }
.ca-sub-name  { font-size: 12.5px; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ca-sub-co    { font-size: 11px; font-weight: 500; color: var(--ink3); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ca-sub-meta  { display: flex; flex-direction: column; gap: 2px; margin-top: 6px; }
.ca-sub-meta-row { display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 500; color: var(--ink3); }
.ca-sub-meta-row i { font-size: 9.5px; width: 11px; text-align: center; }

.ca-sub-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 9.5px; font-weight: 800; padding: 2px 7px;
    border-radius: 9999px; text-transform: uppercase; letter-spacing: .3px;
    margin-top: 7px;
}
.ca-sub-pill.available     { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.ca-sub-pill.assigned-here { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.ca-sub-pill.locked        { background: var(--rlt); color: var(--red); border: 1px solid var(--rbd); }

/* ── EMPTY ── */
.ca-empty { text-align: center; padding: 48px 24px; display: none; }
.ca-empty-icon { font-size: 28px; color: var(--ink3); margin-bottom: 12px; }
.ca-empty-t { font-size: 13.5px; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
.ca-empty-s { font-size: 12px; font-weight: 500; color: var(--ink3); }

/* ── FOOTER ── */
.ca-card-foot {
    padding: 14px 22px; border-top: 1px solid var(--bd2);
    background: var(--bg); display: flex; align-items: center; justify-content: space-between; gap: 12px;
}
.ca-foot-summary { font-size: 12px; font-weight: 600; color: var(--ink3); display: flex; align-items: center; gap: 6px; }
.ca-foot-actions { display: flex; gap: 8px; }
.ca-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.ca-btn i { font-size: 11px; }
.ca-btn-blue  { background: var(--blue); color: #fff; }
.ca-btn-blue:hover  { background: #1344c2; color: #fff; }
.ca-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.ca-btn-ghost:hover { background: var(--bg); color: var(--ink); }

/* ── SIDEBAR CARD ── */
.ca-side-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
    position: sticky; top: 90px;
}
.ca-side-head {
    display: flex; align-items: center; gap: 9px;
    padding: 14px 18px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--glt), var(--surf));
}
.ca-side-title { font-size: 12.5px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .4px; }
.ca-side-body  { padding: 14px; max-height: 460px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg); }
.ca-side-body::-webkit-scrollbar { width: 3px; }
.ca-side-body::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

.ca-assigned-row {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 10px; border: 1px solid var(--gbd);
    border-radius: var(--r); background: var(--glt); margin-bottom: 7px;
    transition: border-color .13s;
}
.ca-assigned-row:last-child { margin-bottom: 0; }
.ca-assigned-av {
    width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
    background: linear-gradient(135deg,#0d9e6a,#34d399);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: #fff;
}
.ca-assigned-name { font-size: 12px; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ca-assigned-co   { font-size: 10.5px; font-weight: 500; color: var(--ink3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ca-side-empty { text-align: center; padding: 28px 16px; color: var(--ink3); font-size: 12px; font-weight: 500; }

@media (max-width: 1024px) {
    .ca-layout { grid-template-columns: 1fr; }
    .ca-side-card { position: static; }
}
@media (max-width: 640px) {
    .ca { padding: 16px; }
    .ca-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .ca-toolbar { flex-wrap: wrap; }
    .ca-sub-grid { grid-template-columns: 1fr; }
}
</style>

<div class="ca">

    {{-- ── HERO ── --}}
    <div class="ca-hero">
        <div class="ca-hero-glow"></div>
        <div class="ca-hero-accent"></div>
        <div class="ca-hero-grid"></div>

        <div class="ca-hero-left">
            <div class="ca-hero-badge"><i class="fas fa-user-plus"></i></div>
            <div>
                <div class="ca-hero-title">Assign Subcontractors</div>
                <div class="ca-hero-crew"><i class="fas fa-users" style="font-size:10px;margin-right:5px"></i>{{ $crew->name }}</div>
            </div>
        </div>

        <div class="ca-hero-right">
            <div class="ca-stat-chip">
                <div class="ca-stat-chip-n">{{ $crew->subcontractors->count() }}</div>
                <div class="ca-stat-chip-l">Assigned</div>
            </div>
            <div class="ca-stat-chip">
                <div class="ca-stat-chip-n">{{ $subcontractors->where('crew_id', null)->count() }}</div>
                <div class="ca-stat-chip-l">Available</div>
            </div>
            <a href="{{ route('superadmin.crew.index') }}" class="ca-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Back to Crews
            </a>
        </div>
    </div>

    {{-- ── NOTICE ── --}}
    <div class="ca-notice">
        <i class="fas fa-exclamation-triangle"></i>
        Each subcontractor can only be assigned to one crew. Already assigned subcontractors are locked.
    </div>

    <div class="ca-layout">

        {{-- ── MAIN CARD ── --}}
        <div>
            <form method="POST" action="{{ route('superadmin.crew.assign.store', $crew->id) }}" id="assign-form">
                @csrf

                <div class="ca-card">

                    {{-- Head ── --}}
                    <div class="ca-card-head">
                        <div class="ca-card-head-l">
                            <i class="fas fa-people-group" style="font-size:15px;color:var(--ink3)"></i>
                            <span class="ca-card-title">Select Subcontractors</span>
                        </div>
                        <span class="ca-selected-badge" id="sel-badge">0 selected</span>
                    </div>

                    {{-- Toolbar ── --}}
                    <div class="ca-toolbar">
                        <div class="ca-search-wrap">
                            <i class="fas fa-search ca-search-ico"></i>
                            <input type="text" id="ca-search" class="ca-search-input"
                                   placeholder="Search by name, company…"
                                   oninput="caSearch(this.value)">
                        </div>
                        <button type="button" class="ca-ctrl-btn blue" onclick="caSelectAll()">
                            <i class="fas fa-check-double" style="font-size:10px"></i> Select Available
                        </button>
                        <button type="button" class="ca-ctrl-btn" onclick="caDeselectAll()">
                            <i class="fas fa-times" style="font-size:10px"></i> Clear
                        </button>
                    </div>

                    {{-- Grid ── --}}
                    <div class="ca-sub-grid" id="sub-grid">
                        @foreach($subcontractors as $sub)
                        @php
                            $isHere   = $crew->subcontractors->contains($sub->id);
                            $isLocked = $sub->crew_id && !$isHere;
                            $avail    = !$isLocked;
                        @endphp

                        <div class="ca-sub {{ $isHere ? 'assigned-here checked' : ($isLocked ? 'ca-sub-locked' : '') }}"
                             id="card-{{ $sub->id }}"
                             data-search="{{ strtolower($sub->name.' '.($sub->last_name ?? '').' '.($sub->company_name ?? '')) }}"
                             data-available="{{ $avail ? '1' : '0' }}"
                             onclick="{{ $avail ? 'caToggle('.$sub->id.')' : '' }}">
                            <div class="ca-sub-inner">

                                {{-- Checkbox ── --}}
                                <div class="ca-sub-check">
                                    @if($avail)
                                    <input type="checkbox" name="subcontractors[]" value="{{ $sub->id }}"
                                           id="cb{{ $sub->id }}" class="ca-sub-cb"
                                           {{ $isHere ? 'checked' : '' }}
                                           onchange="caCount()">
                                    <div class="ca-sub-box" id="box{{ $sub->id }}">
                                        <i class="fas fa-check" style="{{ $isHere ? '' : 'display:none' }}" id="chk{{ $sub->id }}"></i>
                                    </div>
                                    @else
                                    <div class="ca-sub-box" style="background:var(--bd);border-color:var(--bd)">
                                        <i class="fas fa-lock" style="color:var(--ink3)"></i>
                                    </div>
                                    @endif
                                </div>

                                {{-- Avatar ── --}}
                                <div class="ca-sub-av">{{ strtoupper(substr($sub->name,0,1)) }}</div>

                                {{-- Info ── --}}
                                <div class="ca-sub-info">
                                    <div class="ca-sub-name">{{ $sub->name }} {{ $sub->last_name }}</div>
                                    <div class="ca-sub-co">{{ $sub->company_name ?? '—' }}</div>
                                    <div class="ca-sub-meta">
                                        @if($sub->email)
                                        <div class="ca-sub-meta-row"><i class="fas fa-envelope"></i>{{ $sub->email }}</div>
                                        @endif
                                        @if($sub->phone)
                                        <div class="ca-sub-meta-row"><i class="fas fa-phone"></i>{{ $sub->phone }}</div>
                                        @endif
                                    </div>
                                    @if($isHere)
                                        <span class="ca-sub-pill assigned-here"><i class="fas fa-check-circle" style="font-size:8px"></i> Assigned Here</span>
                                    @elseif($isLocked)
                                        <span class="ca-sub-pill locked"><i class="fas fa-ban" style="font-size:8px"></i>
                                            {{ $sub->crew ? 'In: '.$sub->crew->name : 'Unavailable' }}
                                        </span>
                                    @else
                                        <span class="ca-sub-pill available"><i class="fas fa-check" style="font-size:8px"></i> Available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Empty ── --}}
                    <div class="ca-empty" id="ca-empty">
                        <div class="ca-empty-icon"><i class="fas fa-search"></i></div>
                        <div class="ca-empty-t">No results found</div>
                        <div class="ca-empty-s">Try adjusting your search</div>
                    </div>

                    {{-- Footer ── --}}
                    <div class="ca-card-foot">
                        <div class="ca-foot-summary">
                            <i class="fas fa-info-circle" style="color:var(--blue)"></i>
                            <span id="foot-summary">Select subcontractors to assign</span>
                        </div>
                        <div class="ca-foot-actions">
                            <a href="{{ route('superadmin.crew.index') }}" class="ca-btn ca-btn-ghost">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="ca-btn ca-btn-blue" id="submit-btn">
                                <i class="fas fa-floppy-disk"></i> Save Assignments
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        {{-- ── SIDEBAR: Currently Assigned ── --}}
        <div>
            <div class="ca-side-card">
                <div class="ca-side-head">
                    <i class="fas fa-check-circle" style="color:var(--grn);font-size:13px"></i>
                    <span class="ca-side-title">Currently Assigned ({{ $crew->subcontractors->count() }})</span>
                </div>
                <div class="ca-side-body">
                    @forelse($crew->subcontractors as $sub)
                    <div class="ca-assigned-row">
                        <div class="ca-assigned-av">{{ strtoupper(substr($sub->name,0,1)) }}</div>
                        <div style="flex:1;min-width:0">
                            <div class="ca-assigned-name">{{ $sub->name }} {{ $sub->last_name }}</div>
                            <div class="ca-assigned-co">{{ $sub->company_name ?? '—' }}</div>
                        </div>
                        <i class="fas fa-check-circle" style="color:var(--grn);font-size:12px;flex-shrink:0"></i>
                    </div>
                    @empty
                    <div class="ca-side-empty">
                        <i class="fas fa-user-slash" style="font-size:20px;opacity:.3;display:block;margin-bottom:8px"></i>
                        No subcontractors assigned yet
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script>
/* ── TOGGLE ── */
function caToggle(id) {
    const cb  = document.getElementById('cb' + id);
    const box = document.getElementById('box' + id);
    const chk = document.getElementById('chk' + id);
    const card = document.getElementById('card-' + id);
    if (!cb) return;
    cb.checked = !cb.checked;
    if (cb.checked) {
        box.style.background = 'var(--blue)'; box.style.borderColor = 'var(--blue)';
        chk.style.display = ''; card.classList.add('checked');
    } else {
        box.style.background = ''; box.style.borderColor = '';
        chk.style.display = 'none'; card.classList.remove('checked');
    }
    caCount();
}

/* ── COUNT ── */
function caCount() {
    const n = document.querySelectorAll('.ca-sub-cb:checked').length;
    document.getElementById('sel-badge').textContent = n + ' selected';
    document.getElementById('foot-summary').textContent =
        n === 0 ? 'Select subcontractors to assign' :
        n === 1 ? '1 subcontractor selected' :
        n + ' subcontractors selected';
}

/* ── SELECT ALL / CLEAR ── */
function caSelectAll() {
    document.querySelectorAll('.ca-sub[data-available="1"]').forEach(card => {
        const id = card.id.replace('card-','');
        const cb = document.getElementById('cb'+id);
        if (cb && !cb.checked) caToggle(id);
    });
}
function caDeselectAll() {
    document.querySelectorAll('.ca-sub-cb:checked').forEach(cb => {
        caToggle(cb.value);
    });
}

/* ── SEARCH ── */
function caSearch(q) {
    const val   = q.trim().toLowerCase();
    const cards = document.querySelectorAll('.ca-sub');
    let shown   = 0;
    cards.forEach(c => {
        const match = !val || c.dataset.search.includes(val);
        c.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    const empty = document.getElementById('ca-empty');
    const grid  = document.getElementById('sub-grid');
    empty.style.display = shown === 0 ? 'block' : 'none';
    grid.style.paddingBottom = shown === 0 ? '0' : '';
}

/* ── SUBMIT ── */
document.getElementById('assign-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:11px"></i> Saving…';
    btn.disabled  = true;
});

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', caCount);
</script>

@endsection