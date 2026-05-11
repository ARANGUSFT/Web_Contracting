@extends('admin.layouts.superadmin')
@section('title', 'Invoices')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }

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

.inv-wrap {
    font-family: 'Montserrat', sans-serif;
    padding: 28px 32px;
}

/* ══ HERO ══ */
.inv-hero {
    position: relative; border-radius: var(--rxl);
    padding: 28px 40px; margin-bottom: 20px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.inv-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.inv-hero::after {
    content: ''; position: absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.inv-hero-glow {
    position: absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events: none;
}
.inv-hero-l { position: relative; display: flex; align-items: center; gap: 16px; }
.inv-hero-icon {
    width: 48px; height: 48px; border-radius: 13px; flex-shrink: 0;
    background: rgba(24,85,224,.2); border: 1px solid rgba(24,85,224,.35);
    display: flex; align-items: center; justify-content: center; font-size: 18px; color: #8aadff;
}
.inv-hero-title { font-size: 21px; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1; }
.inv-hero-sub   { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.38); margin-top: 5px; }
.inv-hero-r { position: relative; display: flex; align-items: center; gap: 10px; }

/* search in hero */
.inv-search-wrap { position: relative; }
.inv-search-ico  { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.35); font-size: 12px; pointer-events: none; }
.inv-search-input {
    padding: 9px 14px 9px 33px; border-radius: var(--rlg);
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
    color: rgba(255,255,255,.85); font-size: 12.5px; font-weight: 500;
    font-family: 'Montserrat', sans-serif; outline: none; width: 220px;
    transition: all .15s;
}
.inv-search-input::placeholder { color: rgba(255,255,255,.3); }
.inv-search-input:focus { background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.25); }

.inv-new-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: var(--rlg);
    background: var(--blue); color: #fff; font-size: 12.5px; font-weight: 700;
    font-family: 'Montserrat', sans-serif; border: none; text-decoration: none;
    transition: background .13s; white-space: nowrap;
    box-shadow: 0 2px 10px rgba(24,85,224,.4);
}
.inv-new-btn:hover { background: #1344c2; color: #fff; }

/* ══ STAT CARDS ══ */
.inv-stats {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 12px; margin-bottom: 16px;
}
.inv-stat {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); padding: 14px 18px;
    display: flex; align-items: center; gap: 13px;
}
.inv-stat-icon {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
}
.inv-stat-icon.blue { background: var(--blt); color: var(--blue); }
.inv-stat-icon.grn  { background: var(--glt); color: var(--grn); }
.inv-stat-icon.amb  { background: var(--alt); color: var(--amb); }
.inv-stat-icon.red  { background: var(--rlt); color: var(--red); }
.inv-stat-lbl { font-size: 10px; font-weight: 800; color: var(--ink3); text-transform: uppercase; letter-spacing: .6px; }
.inv-stat-val { font-size: 20px; font-weight: 800; color: var(--ink); line-height: 1; margin-top: 2px; }
.inv-stat-val.grn { color: var(--grn); }
.inv-stat-val.amb { color: var(--amb); }

/* ══ FILTER CARD ══ */
.inv-filter-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden; margin-bottom: 16px;
}
.inv-filter-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 18px; border-bottom: 1px solid var(--bd2);
    background: linear-gradient(to right, var(--surf), #fafbfd);
}
.inv-filter-title { font-size: 11.5px; font-weight: 800; color: var(--ink); display: flex; align-items: center; gap: 7px; }
.inv-filter-title i { font-size: 11px; color: var(--blue); }
.inv-filter-toggle {
    font-size: 11.5px; font-weight: 700; color: var(--blue);
    background: none; border: none; cursor: pointer; font-family: 'Montserrat', sans-serif;
    display: flex; align-items: center; gap: 5px; transition: color .13s;
}
.inv-filter-toggle:hover { color: #1344c2; }
.inv-filter-body { display: none; padding: 16px 18px; }
.inv-filter-body.open { display: block; }

.inv-filter-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; }
.inv-filter-lbl {
    display: block; font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .6px; margin-bottom: 5px;
}
.inv-filter-sel {
    padding: 8px 32px 8px 11px; border: 1px solid var(--bd); border-radius: var(--r);
    font-size: 12.5px; font-weight: 500; font-family: 'Montserrat', sans-serif;
    color: var(--ink); background: var(--surf); outline: none; width: 100%;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238c95a6' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .15s, box-shadow .15s;
}
.inv-filter-sel:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(24,85,224,.09); }
.inv-filter-foot {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 12px; margin-top: 12px; border-top: 1px solid var(--bd2);
}
.inv-filter-count { font-size: 12px; font-weight: 600; color: var(--ink3); }

/* ══ FLASH MESSAGES ══ */
.inv-flash {
    display: flex; align-items: center; gap: 8px;
    padding: 11px 16px; border-radius: var(--rlg); margin-bottom: 14px;
    font-size: 13px; font-weight: 600; animation: fd .25s ease;
}
.inv-flash.ok  { background: var(--glt); border: 1px solid var(--gbd); color: var(--grn); }
.inv-flash.err { background: var(--rlt); border: 1px solid var(--rbd); color: var(--red); }
@keyframes fd { from{opacity:0;transform:translateY(-5px)} to{opacity:1} }

/* ══ TABLE CARD ══ */
.inv-table-card {
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: var(--rlg); overflow: hidden;
}
.inv-table-scroll { overflow-x: auto; scrollbar-width: thin; scrollbar-color: #cdd0d8 var(--bg); }
.inv-table-scroll::-webkit-scrollbar { height: 5px; }
.inv-table-scroll::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

table.inv-tbl { width: 100%; border-collapse: collapse; font-family: 'Montserrat', sans-serif; }
table.inv-tbl thead { background: #fafbfd; border-bottom: 1px solid var(--bd); }
table.inv-tbl th {
    padding: 10px 16px; text-align: left;
    font-size: 10px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .8px; white-space: nowrap;
}
table.inv-tbl td { padding: 11px 16px; border-bottom: 1px solid var(--bd2); vertical-align: top; }
table.inv-tbl tbody tr:last-child td { border-bottom: none; }
table.inv-tbl tbody tr { transition: background .1s; }
table.inv-tbl tbody tr:hover td { background: #fafbfd; }

/* ── cells ── */
.inv-num   { font-size: 13px; font-weight: 800; color: var(--ink); }
.inv-billto { font-size: 11.5px; font-weight: 500; color: var(--ink3); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px; }
.inv-co    { font-size: 13px; font-weight: 700; color: var(--ink); }
.inv-loc   { font-size: 11.5px; font-weight: 500; color: var(--ink3); display: flex; align-items: center; gap: 3px; margin-top: 2px; }
.inv-date  { font-size: 13px; font-weight: 600; color: var(--ink); }
.inv-due   { font-size: 11.5px; font-weight: 500; color: var(--ink3); display: flex; align-items: center; gap: 4px; margin-top: 2px; }
.inv-amount { font-size: 13px; font-weight: 800; color: var(--ink); }
.inv-payout { font-size: 13px; font-weight: 600; color: var(--ink2); }

/* margin bar */
.inv-margin-lbl { display: flex; justify-content: space-between; font-size: 11px; font-weight: 700; margin-bottom: 4px; }
.inv-margin-bar  { width: 120px; height: 5px; background: var(--bd); border-radius: 9999px; overflow: hidden; }
.inv-margin-fill { height: 100%; border-radius: 9999px; }

/* status badges */
.inv-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 800; padding: 3px 9px;
    border-radius: 6px; text-transform: uppercase; letter-spacing: .4px;
}
.inv-badge.draft { background: var(--bg);  color: var(--ink3); border: 1px solid var(--bd); }
.inv-badge.sent  { background: var(--blt); color: var(--blue); border: 1px solid var(--bbd); }
.inv-badge.paid  { background: var(--glt); color: var(--grn);  border: 1px solid var(--gbd); }

/* action buttons */
.inv-acts { display: flex; align-items: center; gap: 4px; }
.inv-act {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; border: 1px solid transparent;
    background: none; color: var(--ink3); cursor: pointer;
    transition: all .13s; text-decoration: none;
}
.inv-act:hover        { background: var(--bg); border-color: var(--bd); }
.inv-act.edit:hover   { background: var(--blt); border-color: var(--bbd); color: var(--blue); }
.inv-act.del:hover    { background: var(--rlt); border-color: var(--rbd); color: var(--red); }

/* action dropdown */
.inv-dd { position: relative; }
.inv-dd-menu {
    display: none; position: absolute; right: 0; top: calc(100% + 4px); z-index: 50;
    background: var(--surf); border: 1px solid var(--bd); border-radius: var(--rlg);
    box-shadow: 0 6px 24px rgba(0,0,0,.1); min-width: 180px;
    animation: ddIn .15s ease-out; overflow: hidden;
}
.inv-dd-menu.open { display: block; }
@keyframes ddIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:none} }
.inv-dd-item {
    display: flex; align-items: center; gap: 9px;
    padding: 9px 14px; font-size: 12.5px; font-weight: 600; color: var(--ink2);
    text-decoration: none; transition: background .1s; cursor: pointer;
    background: none; border: none; width: 100%; font-family: 'Montserrat', sans-serif;
}
.inv-dd-item:hover { background: var(--bg); }
.inv-dd-item.danger { color: var(--red); }
.inv-dd-item.danger:hover { background: var(--rlt); }
.inv-dd-item i { font-size: 12px; width: 16px; text-align: center; }
.inv-dd-divider { height: 1px; background: var(--bd2); margin: 4px 0; }

/* ══ EMPTY ══ */
.inv-empty { padding: 60px 24px; text-align: center; }
.inv-empty i { font-size: 32px; color: var(--bd); display: block; margin-bottom: 12px; }
.inv-empty-t { font-size: 14px; font-weight: 700; color: var(--ink3); margin-bottom: 4px; }
.inv-empty-s { font-size: 12.5px; font-weight: 500; color: var(--ink3); }

/* ══ PAGINATION ══ */
.inv-pag {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 18px; border-top: 1px solid var(--bd2);
    font-size: 12px; font-weight: 600; color: var(--ink3);
}
.inv-pag-pages { display: flex; align-items: center; gap: 4px; }
.inv-pag-btn {
    padding: 4px 9px; border-radius: var(--r); font-size: 12px; font-weight: 700;
    font-family: 'Montserrat', sans-serif; text-decoration: none;
    border: 1px solid var(--bd); color: var(--ink2); background: var(--surf);
    transition: all .13s; cursor: pointer;
}
.inv-pag-btn:hover { background: var(--bg); }
.inv-pag-btn.active { background: var(--blue); border-color: var(--blue); color: #fff; }
.inv-pag-btn.disabled { opacity: .4; cursor: not-allowed; pointer-events: none; }

/* ══ BUTTONS ══ */
.inv-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--r);
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    border: 1px solid transparent; cursor: pointer; transition: all .13s;
    text-decoration: none; white-space: nowrap;
}
.inv-btn.primary { background: var(--blue); color: #fff; }
.inv-btn.primary:hover { background: #1344c2; }
.inv-btn.ghost   { background: var(--surf); border-color: var(--bd); color: var(--ink2); }
.inv-btn.ghost:hover { background: var(--bg); }

/* ══ SCROLLBAR ══ */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: #cdd0d8; border-radius: 9999px; }

@media (max-width: 1200px) { .inv-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 900px)  {
    .inv-wrap { padding: 16px; }
    .inv-hero { padding: 22px 20px; flex-direction: column; align-items: flex-start; }
    .inv-stats { grid-template-columns: 1fr 1fr; }
    .inv-filter-grid { grid-template-columns: 1fr 1fr; }
    table.inv-tbl th:nth-child(3), table.inv-tbl td:nth-child(3) { display: none; }
}
</style>

<div class="inv-wrap" x-data="{ showFilters: {{ request()->hasAny(['status','company_id','period','state']) ? 'true' : 'false' }} }">

    {{-- ══ HERO ══ --}}
    <div class="inv-hero">
        <div class="inv-hero-glow"></div>
        <div class="inv-hero-l">
            <div class="inv-hero-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <div>
                <div class="inv-hero-title">Invoices</div>
                <div class="inv-hero-sub">Manage and review all invoice records</div>
            </div>
        </div>
        <div class="inv-hero-r">
            <div class="inv-search-wrap">
                <i class="fas fa-search inv-search-ico"></i>
                <input type="text" class="inv-search-input" placeholder="Search invoices…"
                       oninput="invSearch(this.value)">
            </div>
           
        </div>
    </div>

    {{-- ══ STATS ══ --}}
    <div class="inv-stats">
        <div class="inv-stat">
            <div class="inv-stat-icon blue"><i class="fas fa-file-invoice"></i></div>
            <div>
                <div class="inv-stat-lbl">Total</div>
                <div class="inv-stat-val">{{ $invoices->total() }}</div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon grn"><i class="fas fa-dollar-sign"></i></div>
            <div>
                <div class="inv-stat-lbl">Total Amount</div>
                <div class="inv-stat-val">${{ number_format($invoices->sum('total'), 2) }}</div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon amb"><i class="fas fa-clock"></i></div>
            <div>
                <div class="inv-stat-lbl">Pending</div>
                <div class="inv-stat-val amb">{{ $invoices->where('status', 'sent')->count() }}</div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon grn"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="inv-stat-lbl">Paid</div>
                <div class="inv-stat-val grn">{{ $invoices->where('status', 'paid')->count() }}</div>
            </div>
        </div>
    </div>

    {{-- ══ FLASH ══ --}}
    @if(session('success'))
    <div class="inv-flash ok">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="inv-flash err">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{-- ══ FILTERS ══ --}}
    <div class="inv-filter-card">
        <div class="inv-filter-head">
            <div class="inv-filter-title">
                <i class="fas fa-sliders-h"></i> Filters
            </div>
            <button type="button" class="inv-filter-toggle" onclick="invToggleFilters(this)">
                <i class="fas fa-chevron-down" id="inv-filter-ico" style="{{ request()->hasAny(['status','company_id','period','state']) ? 'transform:rotate(180deg)' : '' }}"></i>
                <span id="inv-filter-lbl">{{ request()->hasAny(['status','company_id','period','state']) ? 'Hide' : 'Show' }}</span>
            </button>
        </div>
        <div class="inv-filter-body {{ request()->hasAny(['status','company_id','period','state']) ? 'open' : '' }}" id="inv-filter-body">
            <form method="GET" action="{{ route('superadmin.invoices.index') }}">
                <div class="inv-filter-grid">
                    <div>
                        <label class="inv-filter-lbl">Status</label>
                        <select name="status" class="inv-filter-sel">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent"  {{ request('status') === 'sent'  ? 'selected' : '' }}>Sent</option>
                            <option value="paid"  {{ request('status') === 'paid'  ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div>
                        <label class="inv-filter-lbl">Company</label>
                        <select name="company_id" class="inv-filter-sel">
                            <option value="">All Companies</option>
                            @foreach($companies as $co)
                            <option value="{{ $co->id }}" {{ request('company_id') == $co->id ? 'selected' : '' }}>
                                {{ $co->company_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="inv-filter-lbl">Date Range</label>
                        <select name="period" class="inv-filter-sel">
                            <option value="">All Time</option>
                            <option value="this_month"   {{ request('period') === 'this_month'   ? 'selected' : '' }}>This Month</option>
                            <option value="last_30_days" {{ request('period') === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_quarter" {{ request('period') === 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                            <option value="this_year"    {{ request('period') === 'this_year'    ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                    <div>
                        <label class="inv-filter-lbl">State</label>
                        <select name="state" class="inv-filter-sel">
                            <option value="">All States</option>
                            @foreach($states as $state)
                            <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="inv-filter-foot">
                    <span class="inv-filter-count">{{ $invoices->total() }} records found</span>
                    <div style="display:flex;gap:8px">
                        <a href="{{ route('superadmin.invoices.index') }}" class="inv-btn ghost">
                            <i class="fas fa-times" style="font-size:10px"></i> Clear
                        </a>
                        <button type="submit" class="inv-btn primary">
                            <i class="fas fa-filter" style="font-size:10px"></i> Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ TABLE ══ --}}
    <div class="inv-table-card">
        <div class="inv-table-scroll">
            <table class="inv-tbl">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Client</th>
                        <th>Dates</th>
                        <th>Amount</th>
                        <th>Payout</th>
                        <th>Margin</th>
                        <th>Status</th>
                        <th>Linked To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                @php
                    $payoutTotal = $invoice->payoutItems->sum(fn($i) => $i->price * $i->quantity);
                    $margin  = $invoice->total - $payoutTotal;
                    $percent = $invoice->total > 0 ? ($margin / $invoice->total) * 100 : 0;
                    $barW    = min(abs($percent), 100);
                    $barCol  = $margin >= 0 ? '#0d9e6a' : '#d92626';
                    $lblCol  = $margin >= 0 ? 'color:var(--grn)' : 'color:var(--red)';
                @endphp
                <tr data-search="{{ strtolower($invoice->invoice_number . ' ' . ($invoice->bill_to ?? '') . ' ' . ($invoice->companyLocation->user->company_name ?? '')) }}">

                    {{-- Invoice ── --}}
                    <td>
                        <div class="inv-num">{{ $invoice->invoice_number }}</div>
                        <div class="inv-billto" title="{{ $invoice->bill_to }}">{{ $invoice->bill_to ?? '—' }}</div>
                    </td>

                    {{-- Client ── --}}
                    <td>
                        <div class="inv-co">{{ $invoice->companyLocation->user->company_name ?? '—' }}</div>
                        <div class="inv-loc">
                            <i class="fas fa-map-marker-alt" style="font-size:9px"></i>
                            {{ $invoice->companyLocation->state ?? '—' }}
                            @if($invoice->companyLocation->city)
                                · {{ $invoice->companyLocation->city }}
                            @endif
                        </div>
                    </td>

                    {{-- Dates ── --}}
                    <td>
                        <div class="inv-date">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</div>
                        <div class="inv-due">
                            <i class="far fa-clock" style="font-size:9px"></i>
                            Due {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d') }}
                        </div>
                    </td>

                    {{-- Amount ── --}}
                    <td><div class="inv-amount">${{ number_format($invoice->invoice_subtotal ?? 0, 2) }}</div></td>

                    {{-- Payout ── --}}
                    <td><div class="inv-payout">${{ number_format($payoutTotal, 2) }}</div></td>

                    {{-- Margin ── --}}
                    <td>
                        <div class="inv-margin-lbl">
                            <span style="{{ $lblCol }}">${{ number_format($margin, 2) }}</span>
                            <span style="color:var(--ink3)">{{ number_format($percent, 1) }}%</span>
                        </div>
                        <div class="inv-margin-bar">
                            <div class="inv-margin-fill" style="width:{{ $barW }}%;background:{{ $barCol }}"></div>
                        </div>
                    </td>

                    {{-- Status ── --}}
                    <td>
                        <span class="inv-badge {{ $invoice->status }}">
                            @if($invoice->status === 'draft')
                                <i class="fas fa-pencil-alt" style="font-size:8px"></i> Draft
                            @elseif($invoice->status === 'sent')
                                <i class="fas fa-paper-plane" style="font-size:8px"></i> Sent
                            @else
                                <i class="fas fa-check-circle" style="font-size:8px"></i> Paid
                            @endif
                        </span>
                    </td>

                   {{-- Linked To ── --}}
<td>
    @if($invoice->invoiceable_type && $invoice->invoiceable_id)
        @php
            $typeMap = [
                'App\\Models\\JobRequest'   => ['Job',      'job',       'fa-wrench',               'var(--blt)', 'var(--blue)', 'var(--bbd)'],
                'App\\Models\\Emergencies'  => ['Emergency','emergency', 'fa-triangle-exclamation',  'var(--rlt)', 'var(--red)',  'var(--rbd)'],
                'App\\Models\\RepairTicket' => ['Repair',   'repair',    'fa-hammer',                '#ecfeff',   '#0e7490',    '#a5f3fc'],
            ];
            $tm  = $typeMap[$invoice->invoiceable_type] ?? null;
            $rel = $invoice->invoiceable;
            $jobNum = match($invoice->invoiceable_type) {
                'App\\Models\\JobRequest'   => $rel?->job_number_name ?? ('#'.$invoice->invoiceable_id),
                'App\\Models\\Emergencies'  => $rel?->job_number_name ?? ('#'.$invoice->invoiceable_id),
                'App\\Models\\RepairTicket' => 'RT-'.str_pad($invoice->invoiceable_id, 4, '0', STR_PAD_LEFT),
                default                     => '#'.$invoice->invoiceable_id,
            };
        @endphp
        @if($tm)
        <a href="/superadmin/calendar?type={{ $tm[1] }}&id={{ $invoice->invoiceable_id }}"
           style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;
                  padding:3px 9px;border-radius:6px;text-decoration:none;
                  background:{{ $tm[3] }};color:{{ $tm[4] }};border:1px solid {{ $tm[5] }}">
            <i class="fas {{ $tm[2] }}" style="font-size:9px"></i>
            {{ $tm[0] }} · {{ $jobNum }}
            <i class="fas fa-arrow-up-right-from-square" style="font-size:8px;opacity:.5"></i>
        </a>
        @endif
    @else
        <span style="font-size:12px;color:var(--ink3)">—</span>
    @endif
</td>

                    {{-- Actions ── --}}
                    <td>
                        <div class="inv-acts">
                            @if($invoice->status === 'draft')
                            <a href="{{ route('superadmin.invoices.edit', $invoice) }}"
                               class="inv-act edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            @endif

                            <div class="inv-dd">
                                <button type="button" class="inv-act"
                                        onclick="invToggleDD(this)" title="More actions">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="inv-dd-menu">
                                    <a href="{{ route('superadmin.invoices.pdf', $invoice) }}"
                                       target="_blank" class="inv-dd-item">
                                        <i class="fas fa-file-pdf" style="color:var(--red)"></i> Download PDF
                                    </a>
                                    <a href="{{ route('superadmin.invoices.prepare', $invoice) }}"
                                       class="inv-dd-item">
                                        <i class="fas fa-dollar-sign" style="color:var(--grn)"></i> Prepare Payout
                                    </a>
                                    @if($invoice->status !== 'draft')
                                    <a href="{{ route('superadmin.invoices.edit', $invoice) }}"
                                       class="inv-dd-item">
                                        <i class="fas fa-eye" style="color:var(--blue)"></i> View Details
                                    </a>
                                    @endif
                                    <div class="inv-dd-divider"></div>
                                    <form method="POST"
                                          action="{{ route('superadmin.invoices.destroy', $invoice) }}"
                                          id="del-inv-{{ $invoice->id }}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="inv-dd-item danger"
                                            onclick="invDel({{ $invoice->id }}, '{{ addslashes($invoice->invoice_number) }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="inv-empty">
                            <i class="fas fa-file-invoice"></i>
                            <div class="inv-empty-t">No invoices found</div>
                            <div class="inv-empty-s">
                                {{ request()->hasAny(['status','company_id','period','state']) ? 'Try adjusting your filters.' : 'Create your first invoice to get started.' }}
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination ── --}}
        @if($invoices->hasPages())
        <div class="inv-pag">
            <span>Showing {{ $invoices->firstItem() }}–{{ $invoices->lastItem() }} of {{ $invoices->total() }}</span>
            <div class="inv-pag-pages">
                @if($invoices->onFirstPage())
                    <span class="inv-pag-btn disabled">← Prev</span>
                @else
                    <a href="{{ $invoices->previousPageUrl() }}" class="inv-pag-btn">← Prev</a>
                @endif

                @foreach($invoices->getUrlRange(max(1,$invoices->currentPage()-2), min($invoices->lastPage(),$invoices->currentPage()+2)) as $page => $url)
                    @if($page == $invoices->currentPage())
                        <span class="inv-pag-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inv-pag-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if($invoices->hasMorePages())
                    <a href="{{ $invoices->nextPageUrl() }}" class="inv-pag-btn">Next →</a>
                @else
                    <span class="inv-pag-btn disabled">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>

<script>
/* ── FILTER TOGGLE ── */
function invToggleFilters(btn) {
    const body = document.getElementById('inv-filter-body');
    const ico  = document.getElementById('inv-filter-ico');
    const lbl  = document.getElementById('inv-filter-lbl');
    const open = body.classList.toggle('open');
    ico.style.transform = open ? 'rotate(180deg)' : '';
    lbl.textContent = open ? 'Hide' : 'Show';
}

/* ── SEARCH ── */
function invSearch(q) {
    const val = q.trim().toLowerCase();
    document.querySelectorAll('table.inv-tbl tbody tr[data-search]').forEach(row => {
        row.style.display = !val || row.dataset.search.includes(val) ? '' : 'none';
    });
}

/* ── DROPDOWN ── */
function invToggleDD(btn) {
    const menu = btn.nextElementSibling;
    const isOpen = menu.classList.contains('open');
    // close all
    document.querySelectorAll('.inv-dd-menu.open').forEach(m => m.classList.remove('open'));
    if (!isOpen) menu.classList.add('open');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.inv-dd')) {
        document.querySelectorAll('.inv-dd-menu.open').forEach(m => m.classList.remove('open'));
    }
});

/* ── DELETE ── */
function invDel(id, num) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete invoice?',
            html: `<p style="font-family:Montserrat,sans-serif;font-size:14px;color:#374151;line-height:1.6">
                     Invoice <strong>${num}</strong> will be permanently deleted.
                   </p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d92626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(r => { if (r.isConfirmed) document.getElementById('del-inv-' + id).submit(); });
    } else {
        if (confirm(`Delete invoice ${num}?`)) document.getElementById('del-inv-' + id).submit();
    }
}
</script>

@endsection