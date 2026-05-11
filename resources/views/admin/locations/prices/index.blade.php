@extends('admin.layouts.superadmin')
@section('title', 'Prices · ' . $location->state . ($location->city ? ' / ' . $location->city : ''))

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.lp { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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
.lp-hero {
    position: relative; border-radius: var(--rxl);
    padding: 30px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.lp-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.lp-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.lp-glow {
    position: absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events: none;
}
.lp-hero-l { position: relative; display: flex; align-items: center; gap: 16px; }
.lp-hero-icon {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center; font-size: 20px; color: #8aadff;
}
.lp-hero-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.lp-hero-sub   { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.38); margin-top: 6px; }
.lp-hero-sub strong { color: #8aadff; }
.lp-hero-r { position: relative; display: flex; align-items: center; gap: 10px; }
.lp-chip {
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
    border-radius: 10px; padding: 9px 16px; text-align: center;
}
.lp-chip-n { font-size: 20px; font-weight: 800; color: #fff; line-height: 1; }
.lp-chip-l { font-size: 9.5px; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .8px; margin-top: 3px; font-weight: 700; }
.lp-back {
    position: relative; display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: var(--r);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
    color: rgba(255,255,255,.55); font-size: 12px; font-weight: 600;
    font-family: 'Montserrat', sans-serif; text-decoration: none; transition: all .13s;
}
.lp-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ── LEGEND ── */
.lp-legend {
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 12px 18px; margin-bottom: 18px;
}
.lp-leg-label { font-size: 11.5px; font-weight: 800; color: var(--ink3); margin-right: 4px; }
.lp-leg-item  {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11.5px; font-weight: 600; padding: 4px 10px;
    border-radius: 9999px;
}
.lp-leg-item.blue { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.lp-leg-item.grn  { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.lp-leg-item.amb  { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }
.lp-leg-item span { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
.lp-leg-item.blue span { background: var(--blue); }
.lp-leg-item.grn  span { background: var(--grn); }
.lp-leg-item.amb  span { background: var(--amb); }

/* ── CATEGORY GROUP ── */
.lp-group { margin-bottom: 18px; }
.lp-group-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 22px; background: var(--surf);
    border: 1px solid var(--bd); border-radius: var(--rlg) var(--rlg) 0 0;
    border-bottom: 2px solid var(--bbd);
}
.lp-group-head-l { display: flex; align-items: center; gap: 10px; }
.lp-cat-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 800; padding: 4px 12px;
    border-radius: 9999px; background: var(--blt); color: var(--blue);
    border: 1px solid var(--bbd);
}
.lp-group-count { font-size: 11.5px; font-weight: 700; color: var(--ink3); }

/* ── TABLE ── */
.lp-tbl-wrap {
    background: var(--surf); border: 1px solid var(--bd);
    border-top: none; border-radius: 0 0 var(--rlg) var(--rlg); overflow: hidden;
}
.lp-tbl { width: 100%; border-collapse: collapse; }
.lp-tbl thead tr { background: #fafbfd; border-bottom: 1px solid var(--bd); }
.lp-tbl th {
    padding: 10px 20px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px; white-space: nowrap;
}
.lp-tbl th.r { text-align: right; width: 180px; }
.lp-tbl td   { padding: 11px 20px; border-bottom: 1px solid var(--bd2); vertical-align: middle; }
.lp-tbl tbody tr:last-child td { border-bottom: none; }
.lp-tbl tbody tr { transition: background .1s; }
.lp-tbl tbody tr:hover td { background: #fafbfd; }

.lp-item-name { font-size: 13px; font-weight: 700; color: var(--ink); }
.lp-item-desc { font-size: 11px; font-weight: 500; color: var(--ink3); margin-top: 1px; }

/* source pill */
.lp-src {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 800; padding: 3px 9px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .4px;
}
.lp-src.city    { background: var(--glt); color: var(--grn); border: 1px solid var(--gbd); }
.lp-src.state   { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.lp-src.global  { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.lp-src.missing { background: var(--alt); color: var(--amb); border: 1px solid var(--abd); }

/* price input */
.lp-price-wrap { position: relative; display: flex; justify-content: flex-end; }
.lp-price-prefix {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    font-size: 13px; font-weight: 700; color: var(--ink3); pointer-events: none;
}
.lp-price-input {
    padding: 8px 10px 8px 26px; border-radius: var(--r);
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    text-align: right; outline: none; width: 160px;
    transition: border-color .15s, box-shadow .15s;
}
.lp-price-input.has-price {
    border: 1px solid var(--bbd); background: var(--blt); color: var(--blue);
}
.lp-price-input.has-price:focus {
    border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); background: #fff;
}
.lp-price-input.city-price {
    border: 1px solid var(--gbd); background: var(--glt); color: var(--grn);
}
.lp-price-input.city-price:focus {
    border-color: var(--grn); box-shadow: 0 0 0 3px rgba(13,158,106,.09); background: #fff;
}
.lp-price-input.no-price {
    border: 2px solid var(--abd); background: var(--alt); color: var(--amb);
}
.lp-price-input.no-price:focus {
    border-color: var(--amb); box-shadow: 0 0 0 3px rgba(217,119,6,.09); background: #fff;
}

/* ── FOOTER ── */
.lp-foot {
    display: flex; align-items: center; justify-content: flex-end; gap: 8px;
    padding: 14px 18px; background: var(--bg);
    border: 1px solid var(--bd); border-radius: var(--rlg); margin-top: 4px;
}
.lp-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 22px; border-radius: var(--r);
    font-size: 13px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
    text-decoration: none; white-space: nowrap;
}
.lp-btn i { font-size: 11px; }
.lp-btn-grn   { background: var(--grn); color: #fff; box-shadow: 0 2px 10px rgba(13,158,106,.35); }
.lp-btn-grn:hover { background: #0a8559; color: #fff; }
.lp-btn-ghost { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.lp-btn-ghost:hover { background: var(--bg); color: var(--ink); }

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 768px) {
    .lp { padding: 16px; }
    .lp-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .lp-tbl th:nth-child(2), .lp-tbl td:nth-child(2) { display: none; }
    .lp-price-input { width: 120px; }
}
</style>

@php
    $grouped = $items->groupBy('category_name');
    $totalItems = $items->count();
    $withPrice  = $items->filter(fn($i) => $i->effective_price > 0)->count();
    $missing    = $totalItems - $withPrice;
@endphp

<div class="lp">

    {{-- ── HERO ── --}}
    <div class="lp-hero">
        <div class="lp-glow"></div>
        <div class="lp-hero-l">
            <div class="lp-hero-icon"><i class="fas fa-dollar-sign"></i></div>
            <div>
                <div class="lp-hero-title">
                    {{ $location->state }}{{ $location->city ? ' / ' . $location->city : '' }} — Prices
                </div>
                <div class="lp-hero-sub">
                    <strong>{{ $location->user->company_name }}</strong>
                    &nbsp;·&nbsp; {{ $totalItems }} items &nbsp;·&nbsp;
                    {{ $location->city ? 'City Override' : 'State-wide Base Price' }}
                </div>
            </div>
        </div>
        <div class="lp-hero-r">
            <div class="lp-chip">
                <div class="lp-chip-n" style="color:#34d399">{{ $withPrice }}</div>
                <div class="lp-chip-l">Priced</div>
            </div>
            @if($missing > 0)
            <div class="lp-chip">
                <div class="lp-chip-n" style="color:#fdba74">{{ $missing }}</div>
                <div class="lp-chip-l">Missing</div>
            </div>
            @endif
            <a href="{{ route('superadmin.locations.index') }}" class="lp-back">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Back
            </a>
        </div>
    </div>

    {{-- ── LEGEND ── --}}
    <div class="lp-legend">
        <span class="lp-leg-label">Legend:</span>
        <span class="lp-leg-item blue"><span></span> Global / State</span>
        <span class="lp-leg-item grn"><span></span> City Override</span>
        <span class="lp-leg-item amb"><span></span> Missing Price</span>
    </div>

    <form method="POST" action="{{ route('superadmin.locations.prices.store', $location) }}">
        @csrf

        {{-- ── CATEGORY GROUPS ── --}}
        @foreach($grouped as $category => $catItems)
        <div class="lp-group">
            <div class="lp-group-head">
                <div class="lp-group-head-l">
                    <span class="lp-cat-badge">
                        <i class="fas fa-folder" style="font-size:10px"></i>
                        {{ $category ?? 'Uncategorized' }}
                    </span>
                    <span class="lp-group-count">{{ $catItems->count() }} {{ Str::plural('item', $catItems->count()) }}</span>
                </div>
                @php
                    $catPriced = $catItems->filter(fn($i) => $i->effective_price > 0)->count();
                    $catPct    = $catItems->count() > 0 ? round($catPriced / $catItems->count() * 100) : 0;
                @endphp
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:11.5px;font-weight:600;color:var(--ink3)">Priced:</span>
                    <div style="width:70px;height:5px;background:var(--bd);border-radius:9999px;overflow:hidden">
                        <div style="height:100%;background:var(--grn);border-radius:9999px;width:{{ $catPct }}%"></div>
                    </div>
                    <span style="font-size:11.5px;font-weight:800;color:var(--ink)">{{ $catPct }}%</span>
                </div>
            </div>

            <div class="lp-tbl-wrap">
                <div style="overflow-x:auto">
                    <table class="lp-tbl">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Source</th>
                                <th class="r">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catItems as $item)
                            @php
                                $hp  = $item->effective_price > 0;
                                $src = $item->price_source ?? ($item->global_price > 0 ? 'global' : 'missing');
                                $inputClass = $src === 'city' ? 'city-price' : ($hp ? 'has-price' : 'no-price');
                            @endphp
                            <tr>
                                <td>
                                    <div class="lp-item-name">{{ $item->name }}</div>
                                    @if($item->description)
                                    <div class="lp-item-desc">{{ Str::limit($item->description, 55) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($src === 'city')
                                        <span class="lp-src city"><i class="fas fa-city" style="font-size:8px"></i> City</span>
                                    @elseif($src === 'state')
                                        <span class="lp-src state"><i class="fas fa-flag" style="font-size:8px"></i> State</span>
                                    @elseif($src === 'global' || $item->global_price > 0)
                                        <span class="lp-src global"><i class="fas fa-globe" style="font-size:8px"></i> Global</span>
                                    @else
                                        <span class="lp-src missing"><i class="fas fa-exclamation-circle" style="font-size:8px"></i> Missing</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="lp-price-wrap">
                                        <span class="lp-price-prefix">$</span>
                                        <input type="number"
                                               step="0.01" min="0"
                                               name="prices[{{ $item->id }}]"
                                               value="{{ $hp ? number_format($item->effective_price, 2, '.', '') : '' }}"
                                               placeholder="{{ $hp ? '' : '0.00' }}"
                                               class="lp-price-input {{ $inputClass }}">
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

        {{-- ── FOOTER ── --}}
        <div class="lp-foot">
            <a href="{{ route('superadmin.locations.index') }}" class="lp-btn lp-btn-ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="lp-btn lp-btn-grn">
                <i class="fas fa-floppy-disk"></i> Save Prices
            </button>
        </div>

    </form>
</div>

@endsection