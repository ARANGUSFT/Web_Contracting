@extends('admin.layouts.superadmin')
@section('title', 'Crew Managers')

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
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ── HERO ── */
.cm-hero {
    position: relative; border-radius: var(--rxl);
    padding: 34px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.cm-hero-glow {
    position: absolute; pointer-events: none;
    width: 600px; height: 300px;
    background: radial-gradient(ellipse, rgba(13,158,106,.35) 0%, transparent 70%);
    right: -60px; top: -60px;
}
.cm-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(180deg,#34d399 0%,#0d9e6a 50%,transparent 100%);
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
    background: rgba(13,158,106,.15); border: 1px solid rgba(13,158,106,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #34d399;
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
.cm-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all .15s; font-family: 'Montserrat', sans-serif;
}
.cm-back:hover { background: rgba(255,255,255,.13); color: #fff; }
.cm-add-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    background: var(--grn); color: #fff;
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: none; cursor: pointer; text-decoration: none; transition: background .15s;
}
.cm-add-btn:hover { background: #0a8559; color: #fff; }

/* ── FLASH ── */
.cm-flash {
    display: flex; align-items: center; gap: 11px;
    padding: 12px 16px; border-radius: var(--rlg);
    margin-bottom: 18px; font-size: 13px; font-weight: 600;
    animation: fd .25s ease;
}
.cm-flash.ok  { background: var(--glt); border: 1px solid var(--gbd); color: #065f46; }
.cm-flash.err { background: var(--rlt); border: 1px solid var(--rbd); color: #991b1b; }
.cm-flash-x { margin-left: auto; background: none; border: none; cursor: pointer; opacity: .5; font-size: 13px; color: inherit; }
.cm-flash-x:hover { opacity: 1; }
@keyframes fd { from { opacity:0; transform:translateY(-6px); } to { opacity:1; } }

/* ── FILTER ── */
.cm-filter {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); margin-bottom: 20px; overflow: hidden;
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
.cm-fg { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 12px; align-items: end; }
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

/* ── TABLE CARD ── */
.cm-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.05), 0 1px 3px rgba(0,0,0,.04);
}
.cm-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.cm-card-head-l { display: flex; align-items: center; gap: 10px; }
.cm-card-title { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -.3px; }
.cm-badge-count {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 9999px; background: var(--glt);
    color: var(--grn); border: 1px solid var(--gbd);
}
.cm-page-info { font-size: 11.5px; font-weight: 500; color: var(--ink3); }

/* ── TABLE ── */
.cm-tbl { width: 100%; border-collapse: collapse; }
.cm-tbl thead tr { background: #fafbfd; border-bottom: 2px solid var(--bd); }
.cm-tbl th {
    padding: 11px 20px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .9px; white-space: nowrap;
}
.cm-tbl th.r { text-align: right; }
.cm-tbl td { padding: 13px 20px; border-bottom: 1px solid var(--bd2); vertical-align: middle; }
.cm-tbl tbody tr:last-child td { border-bottom: none; }
.cm-tbl tbody tr { transition: background .1s; }
.cm-tbl tbody tr:hover td { background: #f3fdf8; }

/* ── AVATAR ── */
.cm-av {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; color: #fff; letter-spacing: -.3px;
    overflow: hidden; position: relative;
}
.cm-av img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block; }
.cm-av span { position: relative; z-index: 1; }
.av0 { background: linear-gradient(135deg,#0d9e6a,#34d399); }
.av1 { background: linear-gradient(135deg,#1855e0,#5b8af7); }
.av2 { background: linear-gradient(135deg,#c97b04,#fbbf24); }
.av3 { background: linear-gradient(135deg,#7c22e8,#c084fc); }
.av4 { background: linear-gradient(135deg,#d92626,#f87171); }
.av5 { background: linear-gradient(135deg,#0284c7,#38bdf8); }

/* ── CELLS ── */
.cm-name { font-size: 13px; font-weight: 700; color: var(--ink); }
.cm-cl { display: flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 500; color: var(--ink2); }
.cm-cl i { color: var(--ink3); font-size: 10.5px; width: 12px; text-align: center; }
.cm-cl a { color: inherit; text-decoration: none; }
.cm-cl a:hover { color: var(--blue); }
.cm-co { display: flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 500; color: var(--ink2); }
.cm-co i { color: var(--ink3); font-size: 11px; }
.cm-na { font-size: 12px; color: var(--ink3); font-style: italic; }
.cm-state-pill {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10.5px; font-weight: 700; padding: 3px 9px;
    border-radius: 6px; background: var(--bg); color: var(--ink3);
    border: 1px solid var(--bd); text-transform: uppercase; letter-spacing: .4px;
}

/* ── STATUS ── */
.cm-st {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10.5px; font-weight: 800; padding: 4px 10px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .5px;
}
.cm-st.on  { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.cm-st.off { background: var(--rlt); color: var(--red); border: 1px solid var(--rbd); }
.cm-st-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

/* ── ACTION BTNS ── */
.cm-acts { display: flex; align-items: center; justify-content: flex-end; gap: 3px; }
.cm-ab {
    width: 32px; height: 32px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12.5px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none;
}
.cm-ab:hover   { background: var(--bg); border-color: var(--bd); }
.cm-ab.e:hover { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.cm-ab.d:hover { background: var(--rlt); border-color: var(--rbd); color: var(--red); }

/* ── EMPTY ── */
.cm-empty { text-align: center; padding: 60px 24px; }
.cm-empty-icon {
    width: 60px; height: 60px; border-radius: 14px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: var(--ink3); margin: 0 auto 16px;
}
.cm-empty-t { font-size: 14px; font-weight: 800; color: var(--ink); margin-bottom: 6px; letter-spacing: -.2px; }
.cm-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); max-width: 300px; margin: 0 auto; }

/* ── PAGINATION ── */
.cm-pag { padding: 14px 22px; border-top: 1px solid var(--bd2); background: #fafbfd; }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }
::-webkit-scrollbar-thumb:hover { background: #adb2be; }

@media (max-width: 768px) {
    .cm { padding: 16px; }
    .cm-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .cm-fg { grid-template-columns: 1fr; }
    .cm-tbl th:nth-child(4), .cm-tbl td:nth-child(4),
    .cm-tbl th:nth-child(5), .cm-tbl td:nth-child(5) { display: none; }
}
</style>

<div class="cm">

    {{-- ── HERO ── --}}
    <div class="cm-hero">
        <div class="cm-hero-glow"></div>
        <div class="cm-hero-accent"></div>
        <div class="cm-hero-grid"></div>

        <div class="cm-hero-left">
            <div class="cm-hero-badge">
                <i class="fas fa-people-carry-box"></i>
            </div>
            <div>
                <div class="cm-hero-title">Crew Managers</div>
                <div class="cm-hero-sub">Manage all crew managers and their assignments</div>
            </div>
        </div>

        <div class="cm-hero-right">
            <div class="cm-stat-chip">
                <div class="cm-stat-chip-n">{{ $subcontractors->total() }}</div>
                <div class="cm-stat-chip-l">Total</div>
            </div>
            <div class="cm-stat-chip">
                <div class="cm-stat-chip-n">{{ $subcontractors->where('is_active', true)->count() }}</div>
                <div class="cm-stat-chip-l">Active</div>
            </div>
            <a href="{{ route('superadmin.subcontractors.create') }}" class="cm-add-btn">
                <i class="fas fa-plus" style="font-size:11px"></i> New Crew Manager
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="cm-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- ── FLASH ── --}}
    @if(session('success'))
    <div class="cm-flash ok" id="cm-flash">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button class="cm-flash-x" onclick="document.getElementById('cm-flash').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif
    @if(session('error'))
    <div class="cm-flash err" id="cm-flash-e">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button class="cm-flash-x" onclick="document.getElementById('cm-flash-e').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- ── FILTERS ── --}}
    <div class="cm-filter">
        <div class="cm-filter-head" onclick="toggleF()">
            <div class="cm-filter-head-l">
                <i class="fas fa-sliders-h"></i>
                Filters
                @if(request('search') || request('status') || request('state'))
                    <span class="cm-active-dot"></span>
                @endif
            </div>
            <i class="fas fa-chevron-down cm-filter-arr {{ request('search')||request('status')||request('state') ? 'open' : '' }}" id="farr"></i>
        </div>
        <div id="fbody" style="{{ request('search')||request('status')||request('state') ? '' : 'display:none' }}" class="cm-filter-body">
            <form method="GET" action="{{ route('superadmin.subcontractors.index') }}">
                <div class="cm-fg">

                    <div>
                        <label class="cm-label">Search</label>
                        <div class="cm-iw">
                            <i class="fas fa-search cm-ii"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="cm-input pi" placeholder="Name, email, company…">
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
                        <label class="cm-label">State</label>
                        <div class="cm-sw">
                            <select name="state" class="cm-sel">
                                <option value="">All states</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down cm-sa"></i>
                        </div>
                    </div>

                    <div class="cm-fa">
                        <button type="submit" class="cm-btn cm-btn-blue">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <a href="{{ route('superadmin.subcontractors.index') }}" class="cm-btn cm-btn-ghost">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="cm-card">

        <div class="cm-card-head">
            <div class="cm-card-head-l">
                <span class="cm-card-title">Crew Managers List</span>
                <span class="cm-badge-count">{{ $subcontractors->total() }} {{ Str::plural('record', $subcontractors->total()) }}</span>
            </div>
            <span class="cm-page-info">Page {{ $subcontractors->currentPage() }} / {{ $subcontractors->lastPage() }}</span>
        </div>

        <div style="overflow-x:auto">
            <table class="cm-tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>State</th>
                        <th>Status</th>
                        <th class="r">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($subcontractors as $i => $sub)
                <tr>

                    {{-- NAME ── --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:11px">
                            @php $ini = strtoupper(substr($sub->name,0,1)).strtoupper(substr($sub->last_name??'',0,1)); @endphp
                            <div class="cm-av {{ $sub->profile_photo ?? false ? '' : 'av'.($i % 6) }}">
                                @if($sub->profile_photo ?? false)
                                    <img src="{{ asset('storage/'.$sub->profile_photo) }}"
                                         alt="{{ $sub->name }}"
                                         onerror="this.style.display='none';this.parentElement.classList.add('av{{ $i % 6 }}');this.parentElement.querySelector('span').style.display='inline'">
                                    <span style="display:none">{{ $ini }}</span>
                                @else
                                    <span>{{ $ini }}</span>
                                @endif
                            </div>
                            <div class="cm-name">{{ $sub->name }} {{ $sub->last_name }}</div>
                        </div>
                    </td>

                    {{-- COMPANY ── --}}
                    <td>
                        @if($sub->company_name)
                            <div class="cm-co"><i class="fas fa-building"></i> {{ $sub->company_name }}</div>
                        @else
                            <span class="cm-na">Not specified</span>
                        @endif
                    </td>

                    {{-- CONTACT ── --}}
                    <td>
                        @if($sub->email)
                        <div class="cm-cl" style="margin-bottom:3px">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:{{ $sub->email }}">{{ $sub->email }}</a>
                        </div>
                        @endif
                        @if($sub->phone)
                        <div class="cm-cl">
                            <i class="fas fa-phone"></i>
                            <a href="tel:{{ $sub->phone }}">{{ $sub->phone }}</a>
                        </div>
                        @endif
                    </td>

                    {{-- STATE ── --}}
                    <td>
                        @if($sub->state)
                            <span class="cm-state-pill">
                                <i class="fas fa-map-marker-alt" style="font-size:9px"></i>
                                {{ $sub->state }}
                            </span>
                        @else
                            <span class="cm-na">—</span>
                        @endif
                    </td>

                    {{-- STATUS ── --}}
                    <td>
                        @if($sub->is_active)
                            <span class="cm-st on"><span class="cm-st-dot"></span> Active</span>
                        @else
                            <span class="cm-st off"><span class="cm-st-dot"></span> Inactive</span>
                        @endif
                    </td>

                    {{-- ACTIONS ── --}}
                    <td>
                        <div class="cm-acts">
                            <a href="{{ route('superadmin.subcontractors.edit', $sub->id) }}"
                               class="cm-ab e" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <button type="button"
                                    onclick="cmDel({{ $sub->id }}, '{{ addslashes($sub->name.' '.($sub->last_name??'')) }}')"
                                    class="cm-ab d" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="df{{ $sub->id }}"
                                  action="{{ route('superadmin.subcontractors.destroy', $sub->id) }}"
                                  method="POST" style="display:none">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="cm-empty">
                            <div class="cm-empty-icon"><i class="fas fa-people-carry-box"></i></div>
                            <div class="cm-empty-t">No crew managers found</div>
                            <div class="cm-empty-s">
                                @if(request('search')||request('status')||request('state'))
                                    Try adjusting your filters.
                                @else
                                    No crew managers have been added yet.
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($subcontractors->hasPages())
        <div class="cm-pag">
            {{ $subcontractors->appends(request()->query())->links('vendor.pagination.tailwind') }}
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

function cmDel(id, name) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete crew manager?',
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
        }).then(r => { if (r.isConfirmed) document.getElementById('df'+id).submit(); });
    } else {
        if (confirm(`Delete ${name}?`)) document.getElementById('df'+id).submit();
    }
}
</script>

@endsection