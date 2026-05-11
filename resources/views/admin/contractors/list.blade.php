@extends('admin.layouts.superadmin')

@section('title', 'Contractors')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }

.cp { font-family: 'Montserrat', sans-serif; padding: 28px 32px; max-width: 1540px; }

/* ─── VARIABLES ─── */
:root {
    --ink:   #0f1117;
    --ink2:  #3c4353;
    --ink3:  #8c95a6;
    --bg:    #f4f5f8;
    --surf:  #ffffff;
    --bd:    #e4e7ed;
    --bd2:   #eef0f4;
    --blue:  #1855e0;
    --blt:   #eef2ff;
    --bbd:   #c7d4fb;
    --grn:   #0d9e6a;
    --glt:   #edfaf4;
    --gbd:   #9fe6c8;
    --red:   #d92626;
    --rlt:   #fff0f0;
    --rbd:   #fbcfcf;
    --r:     8px;
    --rlg:   13px;
    --rxl:   18px;
}

/* ─── HERO ─── */
.cp-hero {
    position: relative;
    border-radius: var(--rxl);
    padding: 34px 40px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    background: var(--ink);
    overflow: hidden;
}
.cp-hero-noise {
    position: absolute; inset: 0; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    opacity: .6;
}
.cp-hero-glow {
    position: absolute; pointer-events: none;
    width: 600px; height: 300px;
    background: radial-gradient(ellipse, rgba(24,85,224,.4) 0%, transparent 70%);
    right: -60px; top: -60px;
}
.cp-hero-accent {
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #4f80ff 0%, #1855e0 50%, transparent 100%);
    border-radius: 0 2px 2px 0;
}
.cp-hero-left { position: relative; display: flex; align-items: center; gap: 18px; }
.cp-hero-badge {
    width: 54px; height: 54px; border-radius: 14px; flex-shrink: 0;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #8aadff;
}
.cp-hero-title {
    font-size: 22px; font-weight: 800; color: #fff;
    letter-spacing: -.5px; line-height: 1;
}
.cp-hero-sub { font-size: 12.5px; color: rgba(255,255,255,.38); margin-top: 5px; font-weight: 500; }
.cp-hero-right { position: relative; display: flex; align-items: center; gap: 10px; }
.cp-stat-chip {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px; padding: 12px 18px; text-align: center;
}
.cp-stat-chip-n { font-size: 22px; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -.5px; }
.cp-stat-chip-l { font-size: 10px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 600; }
.cp-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 15px; border-radius: var(--r);
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.11);
    color: rgba(255,255,255,.6);
    font-size: 12.5px; font-weight: 600;
    text-decoration: none; transition: all .15s;
    font-family: 'Montserrat', sans-serif;
}
.cp-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ─── FLASH ─── */
.cp-flash {
    display: flex; align-items: center; gap: 11px;
    padding: 12px 16px; border-radius: var(--rlg);
    margin-bottom: 18px; font-size: 13px; font-weight: 600;
    animation: fd .25s ease;
}
.cp-flash.ok  { background: var(--glt); border: 1px solid var(--gbd); color: #065f46; }
.cp-flash.err { background: var(--rlt); border: 1px solid var(--rbd); color: #991b1b; }
.cp-flash-x { margin-left: auto; background: none; border: none; cursor: pointer; opacity: .5; font-size: 13px; color: inherit; }
.cp-flash-x:hover { opacity: 1; }
@keyframes fd { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; } }

/* ─── FILTER CARD ─── */
.cp-filter {
    background: var(--surf);
    border: 1px solid var(--bd);
    border-radius: var(--rlg);
    margin-bottom: 20px;
    overflow: hidden;
}
.cp-filter-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; cursor: pointer; user-select: none;
}
.cp-filter-head-l { display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 700; color: var(--ink2); text-transform: uppercase; letter-spacing: .5px; }
.cp-filter-head-l i { color: var(--ink3); }
.cp-filter-arr { color: var(--ink3); font-size: 10px; transition: transform .2s; }
.cp-filter-arr.open { transform: rotate(180deg); }
.cp-active-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--blue); margin-left: 2px; display: inline-block;
}
.cp-filter-body { padding: 18px 20px; border-top: 1px solid var(--bd2); }
.cp-fg { display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end; }
.cp-label { font-size: 11px; font-weight: 700; color: var(--ink3); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px; display: block; }
.cp-input, .cp-sel {
    width: 100%; padding: 9px 12px;
    border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 13px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none;
    transition: border-color .15s, box-shadow .15s;
    appearance: none;
}
.cp-input:focus, .cp-sel:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.cp-iw { position: relative; }
.cp-ii { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--ink3); font-size: 12px; pointer-events: none; }
.cp-input.pi { padding-left: 32px; }
.cp-sw { position: relative; }
.cp-sa { position: absolute; right: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--ink3); font-size: 10px; }
.cp-fa { display: flex; gap: 8px; }

/* ─── BUTTONS ─── */
.cp-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    letter-spacing: .1px; border: 1px solid transparent;
    cursor: pointer; transition: all .15s; text-decoration: none; white-space: nowrap;
}
.cp-btn i { font-size: 11px; }
.cp-btn-blue { background: var(--blue); color: #fff; }
.cp-btn-blue:hover { background: #1344c2; color: #fff; }
.cp-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.cp-btn-ghost:hover { background: var(--bg); color: var(--ink); }
.cp-btn-new {
    background: var(--ink); color: #fff;
    font-size: 12px; font-weight: 700; padding: 8px 16px;
}
.cp-btn-new:hover { background: #1c2130; color: #fff; }

/* ─── TABLE CARD ─── */
.cp-card {
    background: var(--surf);
    border: 1px solid var(--bd);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.05), 0 1px 3px rgba(0,0,0,.04);
}
.cp-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.cp-card-head-l { display: flex; align-items: center; gap: 10px; }
.cp-card-title { font-size: 14px; font-weight: 800; color: var(--ink); letter-spacing: -.3px; }
.cp-badge-count {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 9999px; background: var(--blt);
    color: var(--blue); border: 1px solid var(--bbd);
}
.cp-page-info { font-size: 11.5px; font-weight: 500; color: var(--ink3); }

/* ─── TABLE ─── */
.cp-tbl { width: 100%; border-collapse: collapse; }
.cp-tbl thead tr { background: #fafbfd; border-bottom: 2px solid var(--bd); }
.cp-tbl th {
    padding: 11px 20px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .9px; white-space: nowrap;
}
.cp-tbl th.r { text-align: right; }
.cp-tbl td { padding: 13px 20px; border-bottom: 1px solid var(--bd2); vertical-align: middle; }
.cp-tbl tbody tr:last-child td { border-bottom: none; }
.cp-tbl tbody tr { transition: background .1s; }
.cp-tbl tbody tr:hover td { background: #f7f8ff; }

/* ─── AVATAR ─── */
.cp-av {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; color: #fff; letter-spacing: -.3px;
    overflow: hidden; position: relative;
}
.cp-av img {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: cover;
    display: block;
}
.av0 { background: linear-gradient(135deg,#1855e0,#5b8af7); }
.av1 { background: linear-gradient(135deg,#0d9e6a,#34d399); }
.av2 { background: linear-gradient(135deg,#c97b04,#fbbf24); }
.av3 { background: linear-gradient(135deg,#7c22e8,#c084fc); }
.av4 { background: linear-gradient(135deg,#d92626,#f87171); }
.av5 { background: linear-gradient(135deg,#0284c7,#38bdf8); }

/* ─── CELL CONTENT ─── */
.cp-name { font-size: 13px; font-weight: 700; color: var(--ink); }
.cp-pos  { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 2px; }
.cp-cl   { display: flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 500; color: var(--ink2); margin-bottom: 3px; }
.cp-cl:last-child { margin-bottom: 0; }
.cp-cl i { color: var(--ink3); font-size: 10.5px; width: 12px; text-align: center; }
.cp-cl a { color: inherit; text-decoration: none; }
.cp-cl a:hover { color: var(--blue); }
.cp-co   { display: flex; align-items: center; gap: 7px; font-size: 12.5px; font-weight: 500; color: var(--ink2); }
.cp-co i { color: var(--ink3); font-size: 11px; }
.cp-na   { font-size: 12px; color: var(--ink3); font-style: italic; }

/* ─── STATUS ─── */
.cp-st {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10.5px; font-weight: 800; padding: 4px 10px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .5px;
}
.cp-st.on  { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.cp-st.off { background: var(--rlt); color: var(--red); border: 1px solid var(--rbd); }
.cp-st-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

/* ─── ACTION BTNS ─── */
.cp-acts { display: flex; align-items: center; justify-content: flex-end; gap: 3px; }
.cp-ab {
    width: 32px; height: 32px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12.5px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none;
}
.cp-ab:hover       { background: var(--bg); border-color: var(--bd); }
.cp-ab.e:hover     { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.cp-ab.d:hover     { background: var(--rlt); border-color: var(--rbd); color: var(--red); }
.cp-ab.t           { }
.cp-ab.t.active    { color: var(--grn); }
.cp-ab.t.inactive  { color: var(--ink3); }

/* ─── EMPTY ─── */
.cp-empty { text-align: center; padding: 60px 24px; }
.cp-empty-icon {
    width: 60px; height: 60px; border-radius: 14px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: var(--ink3); margin: 0 auto 16px;
}
.cp-empty-t { font-size: 14px; font-weight: 800; color: var(--ink); margin-bottom: 6px; letter-spacing: -.2px; }
.cp-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); max-width: 300px; margin: 0 auto; }

/* ─── PAGINATION ─── */
.cp-pag { padding: 14px 22px; border-top: 1px solid var(--bd2); background: #fafbfd; }

/* ─── SCROLLBAR ─── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }
::-webkit-scrollbar-thumb:hover { background: #adb2be; }

@media (max-width: 768px) {
    .cp { padding: 16px; }
    .cp-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .cp-fg { grid-template-columns: 1fr; }
    .cp-tbl th:nth-child(3), .cp-tbl td:nth-child(3) { display: none; }
}
</style>

<div class="cp">

    {{-- ── HERO ── --}}
    <div class="cp-hero">
        <div class="cp-hero-noise"></div>
        <div class="cp-hero-glow"></div>
        <div class="cp-hero-accent"></div>

        <div class="cp-hero-left">
            <div class="cp-hero-badge">
                <i class="fas fa-hard-hat"></i>
            </div>
            <div>
                <div class="cp-hero-title">Contractors</div>
                <div class="cp-hero-sub">Manage all contractors and their profiles</div>
            </div>
        </div>

        <div class="cp-hero-right">
            <div class="cp-stat-chip">
                <div class="cp-stat-chip-n">{{ $contractors }}</div>
                <div class="cp-stat-chip-l">Total</div>
            </div>
            <div class="cp-stat-chip">
                <div class="cp-stat-chip-n">{{ $users->where('is_active', true)->count() }}</div>
                <div class="cp-stat-chip-l">Active</div>
            </div>
            <a href="{{ route('superadmin.users.index') }}" class="cp-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- ── FLASH MESSAGES ── --}}
    @if(session('success'))
    <div class="cp-flash ok" id="cp-flash">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button class="cp-flash-x" onclick="document.getElementById('cp-flash').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="cp-flash err" id="cp-flash-e">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button class="cp-flash-x" onclick="document.getElementById('cp-flash-e').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- ── FILTERS ── --}}
    <div class="cp-filter">
        <div class="cp-filter-head" onclick="toggleF()">
            <div class="cp-filter-head-l">
                <i class="fas fa-sliders-h"></i>
                Filters
                @if(request('search') || request('status'))
                    <span class="cp-active-dot"></span>
                @endif
            </div>
            <i class="fas fa-chevron-down cp-filter-arr {{ request('search') || request('status') ? 'open' : '' }}" id="farr"></i>
        </div>
        <div id="fbody" style="{{ request('search') || request('status') ? '' : 'display:none' }}" class="cp-filter-body">
            <form method="GET" action="{{ route('superadmin.users.contractors') }}">
                <div class="cp-fg">

                    <div>
                        <label class="cp-label">Search</label>
                        <div class="cp-iw">
                            <i class="fas fa-search cp-ii"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="cp-input pi" placeholder="Name, email, company…">
                        </div>
                    </div>

                    <div>
                        <label class="cp-label">Status</label>
                        <div class="cp-sw">
                            <select name="status" class="cp-sel">
                                <option value="">All status</option>
                                <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <i class="fas fa-chevron-down cp-sa"></i>
                        </div>
                    </div>

                    <div class="cp-fa">
                        <button type="submit" class="cp-btn cp-btn-blue">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <a href="{{ route('superadmin.users.contractors') }}" class="cp-btn cp-btn-ghost">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="cp-card">

        <div class="cp-card-head">
            <div class="cp-card-head-l">
                <span class="cp-card-title">Contractors List</span>
                <span class="cp-badge-count">{{ $users->total() }} {{ Str::plural('record', $users->total()) }}</span>
            </div>
            <div style="display:flex;align-items:center;gap:14px">
                <span class="cp-page-info">Page {{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
                @if(Route::has('superadmin.contractors.create'))
                <a href="{{ route('superadmin.contractors.create') }}" class="cp-btn cp-btn-new">
                    <i class="fas fa-plus"></i> New Contractor
                </a>
                @endif
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="cp-tbl">
                <thead>
                    <tr>
                        <th>Contractor</th>
                        <th>Contact</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th class="r">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $i => $user)
                <tr>

                    {{-- CONTRACTOR --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:11px">
                            @php $ini = strtoupper(substr($user->name,0,1)).strtoupper(substr($user->last_name??'',0,1)); @endphp
                            <div class="cp-av {{ $user->profile_photo ? '' : 'av'.($i % 6) }}">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                         alt="{{ $user->name }}"
                                         onerror="this.style.display='none';this.parentElement.classList.add('av{{ $i % 6 }}');this.parentElement.querySelector('span').style.display='inline'">
                                    <span style="display:none;position:relative;z-index:1">{{ $ini }}</span>
                                @else
                                    <span>{{ $ini }}</span>
                                @endif
                            </div>
                            <div>
                                <div class="cp-name">{{ $user->name }} {{ $user->last_name }}</div>
                                @if($user->position)
                                <div class="cp-pos"><i class="fas fa-briefcase" style="font-size:9px;margin-right:3px"></i>{{ $user->position }}</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- CONTACT --}}
                    <td>
                        <div class="cp-cl">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </div>
                        @if($user->phone)
                        <div class="cp-cl">
                            <i class="fas fa-phone"></i>
                            <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                        </div>
                        @endif
                    </td>

                    {{-- COMPANY --}}
                    <td>
                        @if($user->company_name)
                            <div class="cp-co"><i class="fas fa-building"></i> {{ $user->company_name }}</div>
                        @else
                            <span class="cp-na">Not specified</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($user->is_active)
                            <span class="cp-st on"><span class="cp-st-dot"></span> Active</span>
                        @else
                            <span class="cp-st off"><span class="cp-st-dot"></span> Inactive</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td>
                        <div class="cp-acts">

                            {{-- Toggle --}}
                            <form action="{{ route('superadmin.contractors.toggle-active', $user->id) }}"
                                  method="POST" style="display:inline" id="tf{{ $user->id }}">
                                @csrf @method('PATCH')
                                <button type="button"
                                        onclick="cpToggle('{{ addslashes($user->name.' '.($user->last_name??'')) }}', {{ $user->is_active ? 'true' : 'false' }}, document.getElementById('tf{{ $user->id }}'))"
                                        class="cp-ab t {{ $user->is_active ? 'active' : 'inactive' }}"
                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'toggle-on' : 'toggle-off' }}"
                                       style="font-size:15px"></i>
                                </button>
                            </form>

                            {{-- Edit --}}
                            <a href="{{ route('superadmin.contractors.edit', $user->id) }}"
                               class="cp-ab e" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('superadmin.contractors.destroy', $user->id) }}"
                                  method="POST" style="display:inline" id="df{{ $user->id }}">
                                @csrf @method('DELETE')
                                <button type="button"
                                        onclick="cpDel('{{ addslashes($user->name.' '.($user->last_name??'')) }}', document.getElementById('df{{ $user->id }}'))"
                                        class="cp-ab d" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="cp-empty">
                            <div class="cp-empty-icon"><i class="fas fa-users"></i></div>
                            <div class="cp-empty-t">No contractors found</div>
                            <div class="cp-empty-s">
                                @if(request('search')||request('status'))
                                    Try adjusting your filters.
                                @else
                                    No contractors have been added yet.
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="cp-pag">
            {{ $users->links('vendor.pagination.tailwind') }}
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

function cpToggle(name, isActive, form) {
    const action  = isActive ? 'Deactivate' : 'Activate';
    const iconCls = isActive ? 'fa-ban' : 'fa-check-circle';
    const color   = isActive ? '#d97706' : '#0d9e6a';
    const btnColor= isActive ? '#d97706' : '#0d9e6a';
    const msg     = isActive
        ? `<strong>${name}</strong> will be set as <strong>Inactive</strong> and won't be able to access the platform.`
        : `<strong>${name}</strong> will be set as <strong>Active</strong> and will regain access.`;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: `${action} contractor?`,
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px;line-height:1.6">${msg}</p>`,
            icon: isActive ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: btnColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${action.toLowerCase()}`,
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) form.submit(); });
    } else {
        if (confirm(`${action} ${name}?`)) form.submit();
    }
}

function cpDel(name, form) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete contractor?',
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
        }).then(r => { if (r.isConfirmed) form.submit(); });
    } else {
        if (confirm(`Delete ${name}?`)) form.submit();
    }
}
</script>

@endsection