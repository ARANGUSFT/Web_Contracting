@extends('admin.layouts.superadmin')
@section('title', 'Global Items')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.itm { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --orn:  #ea580c; --olt:  #fff7ed; --obd:  #fed7aa;
    --r:    8px; --rlg: 13px; --rxl: 18px;
}

/* ══ HERO ══ */
.itm-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.itm-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.itm-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.itm-hero-glow {
    position: absolute; right:-60px; top:-60px; width:560px; height:300px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events: none;
}
.itm-hero-l { position: relative; display: flex; align-items: center; gap: 16px; }
.itm-hero-icon {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center; font-size: 20px; color: #8aadff;
}
.itm-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.itm-hero-sub   { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.38); margin-top: 6px; }
.itm-hero-r { position: relative; display: flex; align-items: center; gap: 10px; }
.itm-chip {
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
    border-radius: 10px; padding: 9px 16px; text-align: center;
}
.itm-chip-n { font-size: 20px; font-weight: 800; color: #fff; line-height: 1; }
.itm-chip-l { font-size: 9.5px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }
.itm-new-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 20px; border-radius: var(--rlg);
    background: var(--blue); color: #fff;
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: none; text-decoration: none; transition: background .13s; white-space: nowrap;
    box-shadow: 0 2px 10px rgba(24,85,224,.4);
}
.itm-new-btn:hover { background: #1344c2; color: #fff; }

/* ══ STAT CARDS ══ */
.itm-stats {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 12px; margin-bottom: 18px;
}
.itm-stat {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 16px 20px;
    display: flex; align-items: center; gap: 14px;
}
.itm-stat-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 17px;
}
.itm-stat-icon.blue { background: var(--blt); color: var(--blue); }
.itm-stat-icon.grn  { background: var(--glt); color: var(--grn); }
.itm-stat-icon.amb  { background: var(--alt); color: var(--amb); }
.itm-stat-icon.pur  { background: #f5f0ff;   color: #7c22e8; }
.itm-stat-val { font-size: 26px; font-weight: 800; color: var(--ink); line-height: 1; letter-spacing: -.5px; }
.itm-stat-lbl { font-size: 11.5px; font-weight: 600; color: var(--ink3); margin-top: 2px; }

/* ══ FILTER BAR ══ */
.itm-filters {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 16px 20px;
    margin-bottom: 20px;
}
.itm-filters-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.itm-filter-wrap { position: relative; flex: 1; min-width: 160px; }
.itm-filter-ico  { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--ink3); font-size: 12px; pointer-events: none; }
.itm-filter-input, .itm-filter-sel {
    padding: 9px 12px 9px 32px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 12.5px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s; appearance: none;
}
.itm-filter-input:focus, .itm-filter-sel:focus {
    border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09);
}
.itm-filter-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    font-size: 12.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s; white-space: nowrap;
}
.itm-filter-btn.primary { background: var(--blue); color: #fff; }
.itm-filter-btn.primary:hover { background: #1344c2; }
.itm-filter-btn.ghost   { background: var(--surf); border-color: var(--bd); color: var(--ink2); text-decoration: none; }
.itm-filter-btn.ghost:hover { background: var(--bg); }

/* ══ META ROW ══ */
.itm-meta {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px; flex-wrap: wrap; gap: 10px;
}
.itm-legend { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.itm-legend-dot {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11.5px; font-weight: 600; padding: 4px 10px;
    border-radius: 9999px;
}
.itm-legend-dot.blue { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.itm-legend-dot.amb  { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.itm-legend-dot.grn  { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.itm-legend-dot.gry  { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }
.itm-legend-dot span { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
.itm-legend-dot.blue span { background: var(--blue); }
.itm-legend-dot.amb  span { background: var(--amb); }
.itm-legend-dot.grn  span { background: var(--grn); }
.itm-legend-dot.gry  span { background: var(--ink3); }
.itm-showing { font-size: 12px; font-weight: 600; color: var(--ink3); }

/* ══ CATEGORY BLOCK ══ */
.itm-group { margin-bottom: 20px; }
.itm-group-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px; background: var(--surf);
    border: 1px solid var(--bd); border-radius: var(--rlg) var(--rlg) 0 0;
    border-bottom: 2px solid var(--bbd);
}
.itm-group-head-l { display: flex; align-items: center; gap: 12px; }
.itm-cat-badge {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 12.5px; font-weight: 800; padding: 5px 13px;
    border-radius: 9999px; background: var(--blt); color: var(--blue);
    border: 1px solid var(--bbd);
}
.itm-cat-count { font-size: 12px; font-weight: 700; color: var(--ink3); }
.itm-progress-wrap { display: flex; align-items: center; gap: 8px; }
.itm-progress-lbl { font-size: 11.5px; font-weight: 600; color: var(--ink3); }
.itm-progress-bar {
    width: 80px; height: 6px; background: var(--bd);
    border-radius: 9999px; overflow: hidden;
}
.itm-progress-fill { height: 100%; background: var(--blue); border-radius: 9999px; }
.itm-progress-pct  { font-size: 11.5px; font-weight: 800; color: var(--ink); }

/* ══ TABLE ══ */
.itm-tbl-wrap {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: 0 0 var(--rlg) var(--rlg);
    border-top: none; overflow: hidden;
}
.itm-tbl { width: 100%; border-collapse: collapse; }
.itm-tbl thead tr { background: #fafbfd; border-bottom: 1px solid var(--bd); }
.itm-tbl th {
    padding: 10px 18px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px; white-space: nowrap;
}
.itm-tbl th.r { text-align: right; }
.itm-tbl td   { padding: 12px 18px; border-bottom: 1px solid var(--bd2); vertical-align: middle; }
.itm-tbl tbody tr:last-child td { border-bottom: none; }
.itm-tbl tbody tr { transition: background .1s; }
.itm-tbl tbody tr:hover td { background: #fafbfd; }
.itm-tbl tbody tr.no-price td { background: rgba(217,119,6,.02); }
.itm-tbl tbody tr.inactive { opacity: .65; }

/* item name cell */
.itm-name-cell { display: flex; align-items: center; gap: 11px; }
.itm-name-icon {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.itm-name-icon.priced { background: var(--blt); color: var(--blue); }
.itm-name-icon.unpriced { background: var(--alt); color: var(--amb); }
.itm-name-text { font-size: 13px; font-weight: 700; color: var(--ink); }
.itm-name-desc { font-size: 11px; font-weight: 500; color: var(--ink3); margin-top: 1px; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* price pills */
.itm-price {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11.5px; font-weight: 800; padding: 4px 10px;
    border-radius: 8px; white-space: nowrap;
}
.itm-price.global   { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.itm-price.specific { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.itm-price.crew-tr  { background: var(--olt); color: var(--orn); border: 1px solid var(--obd); }
.itm-price.crew-no  { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.itm-price.empty    { color: var(--ink3); font-weight: 600; }

/* status */
.itm-status {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 800; padding: 3px 9px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .4px;
}
.itm-status.active   { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.itm-status.inactive { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }
.itm-updated { font-size: 10.5px; font-weight: 500; color: var(--ink3); margin-top: 3px; }

/* actions */
.itm-acts { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
.itm-act {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none;
}
.itm-act:hover       { background: var(--bg); border-color: var(--bd); }
.itm-act.edit:hover  { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.itm-act.del:hover   { background: var(--rlt); border-color: var(--rbd); color: var(--red); }

/* ══ EMPTY ══ */
.itm-empty {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rxl); padding: 72px 24px; text-align: center;
}
.itm-empty-icon {
    width: 70px; height: 70px; border-radius: 18px;
    background: var(--bg); border: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: var(--ink3); margin: 0 auto 18px;
}
.itm-empty-t { font-size: 16px; font-weight: 800; color: var(--ink); margin-bottom: 6px; }
.itm-empty-s { font-size: 13px; font-weight: 500; color: var(--ink3); margin-bottom: 22px; }

/* ══ PAGINATION ══ */
.itm-pag { margin-top: 20px; }

/* ══ SCROLLBAR ══ */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 1200px) { .itm-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 768px)  {
    .itm { padding: 16px; }
    .itm-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .itm-stats { grid-template-columns: 1fr 1fr; }
    .itm-tbl th:nth-child(3), .itm-tbl td:nth-child(3),
    .itm-tbl th:nth-child(4), .itm-tbl td:nth-child(4) { display: none; }
}
@media (max-width: 480px)  { .itm-stats { grid-template-columns: 1fr; } }
</style>

@php
    $totalItems  = $items->total();
    $withPrice   = $items->filter(fn($i) => !empty($i->global_price) && $i->global_price > 0)->count();
    $missingPrice = $totalItems - $withPrice;
    $activeItems = $items->filter(fn($i) => $i->is_active)->count();
    $grouped     = $items->groupBy(fn($i) => optional($i->category)->name ?? 'Uncategorized');
@endphp

<div class="itm">

    {{-- ══ HERO ══ --}}
    <div class="itm-hero">
        <div class="itm-hero-glow"></div>
        <div class="itm-hero-l">
            <div class="itm-hero-icon"><i class="fas fa-boxes-stacked"></i></div>
            <div>
                <div class="itm-hero-title">Global Items</div>
                <div class="itm-hero-sub">Service catalog grouped by category</div>
            </div>
        </div>
        <div class="itm-hero-r">
            <div class="itm-chip">
                <div class="itm-chip-n">{{ $totalItems }}</div>
                <div class="itm-chip-l">Items</div>
            </div>
            <div class="itm-chip">
                <div class="itm-chip-n" style="color:#34d399">{{ $activeItems }}</div>
                <div class="itm-chip-l">Active</div>
            </div>
            <a href="{{ route('superadmin.items.create') }}" class="itm-new-btn">
                <i class="fas fa-plus" style="font-size:11px"></i> New Item
            </a>
        </div>
    </div>

    {{-- ══ STATS ══ --}}
    <div class="itm-stats">
        <div class="itm-stat">
            <div class="itm-stat-icon blue"><i class="fas fa-box"></i></div>
            <div><div class="itm-stat-val">{{ $totalItems }}</div><div class="itm-stat-lbl">Total Items</div></div>
        </div>
        <div class="itm-stat">
            <div class="itm-stat-icon grn"><i class="fas fa-dollar-sign"></i></div>
            <div><div class="itm-stat-val">{{ $withPrice }}</div><div class="itm-stat-lbl">With Global Price</div></div>
        </div>
        <div class="itm-stat">
            <div class="itm-stat-icon amb"><i class="fas fa-triangle-exclamation"></i></div>
            <div><div class="itm-stat-val">{{ $missingPrice }}</div><div class="itm-stat-lbl">Missing Price</div></div>
        </div>
        <div class="itm-stat">
            <div class="itm-stat-icon pur"><i class="fas fa-check-circle"></i></div>
            <div><div class="itm-stat-val">{{ $activeItems }}</div><div class="itm-stat-lbl">Active Items</div></div>
        </div>
    </div>

    {{-- ══ FILTERS ══ --}}
    <div class="itm-filters">
        <form method="GET" action="{{ route('superadmin.items.index') }}">
            <div class="itm-filters-row">
                <div class="itm-filter-wrap" style="flex:2;min-width:200px">
                    <i class="fas fa-search itm-filter-ico"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="itm-filter-input" placeholder="Search by name or description…">
                </div>
                <div class="itm-filter-wrap">
                    <i class="fas fa-flag itm-filter-ico"></i>
                    <select name="status" class="itm-filter-sel">
                        <option value="">All statuses</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="itm-filter-wrap">
                    <i class="fas fa-dollar-sign itm-filter-ico"></i>
                    <select name="price_status" class="itm-filter-sel">
                        <option value="">All prices</option>
                        <option value="with"    {{ request('price_status') == 'with'    ? 'selected' : '' }}>With global price</option>
                        <option value="without" {{ request('price_status') == 'without' ? 'selected' : '' }}>Without global price</option>
                    </select>
                </div>
                <div class="itm-filter-wrap">
                    <i class="fas fa-folder itm-filter-ico"></i>
                    <select name="category_id" class="itm-filter-sel">
                        <option value="">All categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="itm-filter-btn primary">
                    <i class="fas fa-filter" style="font-size:11px"></i> Filter
                </button>
                <a href="{{ route('superadmin.items.index') }}" class="itm-filter-btn ghost">
                    <i class="fas fa-times" style="font-size:11px"></i> Clear
                </a>
            </div>
        </form>
    </div>

    {{-- ══ META ROW ══ --}}
    <div class="itm-meta">
        <div class="itm-legend">
            <span style="font-size:12px;font-weight:700;color:var(--ink3)">Legend:</span>
            <span class="itm-legend-dot blue"><span></span> With global price</span>
            <span class="itm-legend-dot amb"><span></span> Without global price</span>
            <span class="itm-legend-dot grn"><span></span> Active</span>
            <span class="itm-legend-dot gry"><span></span> Inactive</span>
        </div>
        <span class="itm-showing">
            Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items
        </span>
    </div>

    {{-- ══ ITEMS BY CATEGORY ══ --}}
    @forelse($grouped as $catName => $catItems)
    @php
        $catPriced = $catItems->filter(fn($i) => !empty($i->global_price) && $i->global_price > 0)->count();
        $catPct    = $catItems->count() > 0 ? round(($catPriced / $catItems->count()) * 100) : 0;
    @endphp
    <div class="itm-group">
        <div class="itm-group-head">
            <div class="itm-group-head-l">
                <span class="itm-cat-badge">
                    <i class="fas fa-folder" style="font-size:10px"></i>
                    {{ $catName }}
                </span>
                <span class="itm-cat-count">{{ $catItems->count() }} {{ Str::plural('item', $catItems->count()) }}</span>
            </div>
            <div class="itm-progress-wrap">
                <span class="itm-progress-lbl">Priced:</span>
                <div class="itm-progress-bar">
                    <div class="itm-progress-fill" style="width:{{ $catPct }}%"></div>
                </div>
                <span class="itm-progress-pct">{{ $catPct }}%</span>
            </div>
        </div>
        <div class="itm-tbl-wrap">
            <div style="overflow-x:auto">
                <table class="itm-tbl">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Global Price</th>
                            <th>Crew w/ Trailer</th>
                            <th>Crew w/o Trailer</th>
                            <th>Status</th>
                            <th class="r">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($catItems as $item)
                        @php $hp = !empty($item->global_price) && $item->global_price > 0; @endphp
                        <tr class="{{ !$hp ? 'no-price' : '' }} {{ !$item->is_active ? 'inactive' : '' }}">
                            <td>
                                <div class="itm-name-cell">
                                    <div class="itm-name-icon {{ $hp ? 'priced' : 'unpriced' }}">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="itm-name-text">{{ $item->name }}</div>
                                        @if($item->description)
                                        <div class="itm-name-desc">{{ Str::limit($item->description, 60) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($hp)
                                <span class="itm-price global">
                                    <i class="fas fa-dollar-sign" style="font-size:9px"></i>
                                    ${{ number_format($item->global_price, 2) }}
                                </span>
                                @else
                                <span class="itm-price specific">
                                    <i class="fas fa-exclamation-circle" style="font-size:9px"></i> Specific
                                </span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($item->crew_price_with_trailer) && $item->crew_price_with_trailer > 0)
                                <span class="itm-price crew-tr">
                                    <i class="fas fa-truck" style="font-size:9px"></i>
                                    ${{ number_format($item->crew_price_with_trailer, 2) }}
                                </span>
                                @else
                                <span class="itm-price empty">—</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($item->crew_price_without_trailer) && $item->crew_price_without_trailer > 0)
                                <span class="itm-price crew-no">
                                    <i class="fas fa-user" style="font-size:9px"></i>
                                    ${{ number_format($item->crew_price_without_trailer, 2) }}
                                </span>
                                @else
                                <span class="itm-price empty">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="itm-status {{ $item->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas fa-{{ $item->is_active ? 'check-circle' : 'minus-circle' }}" style="font-size:8px"></i>
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($item->updated_at)
                                <div class="itm-updated">
                                    <i class="far fa-clock" style="font-size:9px;margin-right:3px"></i>
                                    {{ $item->updated_at->diffForHumans() }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="itm-acts">
                                    <a href="{{ route('superadmin.items.edit', $item) }}"
                                       class="itm-act edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="itm-act del" title="Delete"
                                            onclick="itmDel({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="del-form-{{ $item->id }}" method="POST"
                                          action="{{ route('superadmin.items.destroy', $item) }}"
                                          style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="itm-empty">
        <div class="itm-empty-icon"><i class="fas fa-box-open"></i></div>
        <div class="itm-empty-t">No items found</div>
        <div class="itm-empty-s">
            {{ request()->hasAny(['search','status','price_status','category_id']) ? 'Try adjusting your filters.' : 'Get started by creating your first global item.' }}
        </div>
        <a href="{{ route('superadmin.items.create') }}" class="itm-new-btn">
            <i class="fas fa-plus" style="font-size:11px"></i> Create first item
        </a>
    </div>
    @endforelse

    {{-- ══ PAGINATION ══ --}}
    @if($items->hasPages())
    <div class="itm-pag">
        {{ $items->appends(request()->query())->links() }}
    </div>
    @endif

</div>

<script>
function itmDel(id, name) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete item?',
            html: `<p style="font-family:Montserrat,sans-serif;color:#374151;font-size:14px;line-height:1.6">
                     <strong>${name}</strong> will be permanently deleted from the catalog.
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) document.getElementById('del-form-'+id).submit(); });
    } else {
        if (confirm(`Delete "${name}"?`)) document.getElementById('del-form-'+id).submit();
    }
}
</script>

@endsection