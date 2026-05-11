@extends('admin.layouts.superadmin')
@section('title', 'Prepare Payout · ' . $invoice->invoice_number)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.pp { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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

/* ══ HERO ══ */
.pp-hero {
    position: relative; border-radius: var(--rxl);
    padding: 28px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.pp-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.pp-hero::after {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#34d399,var(--grn) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.pp-glow {
    position:absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(13,158,106,.3) 0%,transparent 70%);
    pointer-events:none;
}
.pp-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.pp-hero-icon {
    width:48px; height:48px; border-radius:13px; flex-shrink:0;
    background:rgba(13,158,106,.2); border:1px solid rgba(13,158,106,.35);
    display:flex; align-items:center; justify-content:center; font-size:18px; color:#34d399;
}
.pp-hero-title { font-size:21px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; }
.pp-hero-sub   { font-size:12px; font-weight:600; color:rgba(255,255,255,.38); margin-top:5px; }
.pp-hero-sub a { color:rgba(255,255,255,.4); text-decoration:none; }
.pp-hero-sub a:hover { color:rgba(255,255,255,.7); }
.pp-hero-sub .sep { margin:0 5px; }
.pp-hero-r { position:relative; display:flex; align-items:center; gap:8px; }
.pp-hbtn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px; border-radius:var(--rlg);
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap;
}
.pp-hbtn i { font-size:10px; }
.pp-hbtn.ghost { background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.12); color:rgba(255,255,255,.6); }
.pp-hbtn.ghost:hover { background:rgba(255,255,255,.15); color:#fff; }
.pp-hbtn.grn   { background:var(--grn); color:#fff; box-shadow:0 2px 8px rgba(13,158,106,.4); }
.pp-hbtn.grn:hover { background:#0a8559; color:#fff; }

/* ══ LAYOUT ══ */
.pp-body { display:grid; grid-template-columns:1fr 280px; gap:18px; align-items:start; }
.pp-left { display:flex; flex-direction:column; gap:16px; }
.pp-right { display:flex; flex-direction:column; gap:14px; position:sticky; top:90px; min-width:0; overflow:hidden; }

/* ══ CARDS ══ */
.pp-card { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.pp-card-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 20px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.pp-card-h i { font-size:13px; color:var(--grn); }
.pp-card-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.pp-card-b { padding:20px; }

/* ══ FIELDS ══ */
.pp-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.pp-field { display:flex; flex-direction:column; gap:5px; }
.pp-lbl { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.7px; }
.pp-lbl .req { color:var(--red); margin-left:2px; }
.pp-input {
    padding:9px 12px; border:1.5px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.pp-input:focus { border-color:var(--grn); box-shadow:0 0 0 3px rgba(13,158,106,.09); }
.pp-hint { font-size:11px; font-weight:500; color:var(--ink3); display:flex; align-items:center; gap:4px; }
.pp-hint i { color:var(--grn); font-size:10px; }

/* info row (read-only) */
.pp-info-row {
    display:flex; align-items:center; gap:12px;
    padding:10px 12px; border:1px solid var(--bd2); border-radius:var(--rlg); background:var(--bg); min-width:0; overflow:hidden;
}
.pp-info-icon {
    width:36px; height:36px; border-radius:9px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:14px;
}
.pp-info-icon.grn { background:var(--glt); color:var(--grn); }
.pp-info-icon.red { background:var(--rlt); color:var(--red); }
.pp-info-icon.blue { background:var(--blt); color:var(--blue); }
.pp-info-key { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.5px; }
.pp-info-val { font-size:13px; font-weight:700; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* crew avatar */
.pp-crew-av {
    width:36px; height:36px; border-radius:50%; flex-shrink:0;
    background:var(--blt); border:1px solid var(--bbd);
    display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:800; color:var(--blue);
}
.pp-crew-pill {
    display:inline-flex; align-items:center; gap:4px;
    font-size:11px; font-weight:700; padding:2px 8px;
    border-radius:6px; background:var(--glt); color:var(--grn); border:1px solid var(--gbd);
    margin-left:8px;
}

/* ══ ADD ITEMS ══ */
.pp-add-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.pp-add-card { background:var(--bg); border:1px solid var(--bd); border-radius:var(--rlg); padding:16px; }
.pp-add-card-title { font-size:11px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.6px; margin-bottom:10px; }

/* ── catalog row: select + qty + button stacked cleanly ── */
.pp-add-row-catalog { display:flex; flex-direction:column; gap:8px; }
.pp-add-row-catalog .pp-sel { width:100%; }
.pp-add-row-catalog .pp-row-controls { display:flex; align-items:center; gap:8px; }
.pp-add-row-catalog .pp-row-controls input { width:70px; flex:none; }

/* ── custom row: two inputs per line + button ── */
.pp-add-row-custom { display:flex; flex-direction:column; gap:8px; }
.pp-add-row-custom .pp-row-top { display:flex; gap:8px; }
.pp-add-row-custom .pp-row-top input { flex:1; min-width:0; }
.pp-add-row-custom .pp-row-bottom { display:flex; align-items:center; gap:8px; }
.pp-add-row-custom .pp-row-bottom input { width:70px; flex:none; }

.pp-add-btn {
    display:inline-flex; align-items:center; gap:5px; flex-shrink:0;
    padding:8px 16px; border-radius:var(--r);
    background:var(--grn); color:#fff; font-size:12px; font-weight:700;
    font-family:'Montserrat',sans-serif; border:none; cursor:pointer; transition:background .13s;
    white-space:nowrap;
}
.pp-add-btn:hover { background:#0a8559; }
.pp-add-btn.sec { background:var(--blue); }
.pp-add-btn.sec:hover { background:#1344c2; }

.pp-sel {
    padding:9px 32px 9px 11px; border:1.5px solid var(--bd); border-radius:var(--r);
    font-size:12.5px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; min-width:0;
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238c95a6' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 10px center;
    transition:border-color .15s;
}
.pp-sel:focus { border-color:var(--grn); box-shadow:0 0 0 3px rgba(13,158,106,.09); }

/* ══ TABLE ══ */
.pp-tbl-wrap { overflow-x:auto; scrollbar-width:thin; scrollbar-color:#cdd0d8 var(--bg); }
table.pp-tbl { width:100%; border-collapse:collapse; font-family:'Montserrat',sans-serif; }
table.pp-tbl thead { background:#fafbfd; border-bottom:1px solid var(--bd); }
table.pp-tbl th {
    padding:9px 16px; text-align:left;
    font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; white-space:nowrap;
}
table.pp-tbl th.r { text-align:right; }
table.pp-tbl th.c { text-align:center; }
table.pp-tbl td { padding:10px 16px; border-bottom:1px solid var(--bd2); vertical-align:middle; }
table.pp-tbl tbody tr:last-child td { border-bottom:none; }
table.pp-tbl tbody tr:hover td { background:#fafbfd; }

.pp-desc { font-size:13px; font-weight:700; color:var(--ink); }
.pp-price-input {
    width:95px; padding:6px 8px; text-align:right;
    border:1.5px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:700; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none;
    transition:border-color .15s;
}
.pp-price-input:focus { border-color:var(--grn); box-shadow:0 0 0 3px rgba(13,158,106,.09); }
.pp-qty-input {
    width:70px; padding:6px 8px; text-align:center;
    border:1.5px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:700; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none;
    transition:border-color .15s;
}
.pp-qty-input:focus { border-color:var(--grn); box-shadow:0 0 0 3px rgba(13,158,106,.09); }
.pp-line-total { font-size:13px; font-weight:800; color:var(--ink); text-align:right; }
.pp-empty-row  { padding:40px 16px; text-align:center; color:var(--ink3); font-size:13px; font-weight:500; }
.pp-empty-row i { display:block; font-size:24px; opacity:.2; margin-bottom:8px; }

.pp-rm-btn {
    width:28px; height:28px; border-radius:7px; margin:0 auto;
    display:flex; align-items:center; justify-content:center;
    font-size:11px; border:1px solid transparent;
    background:none; color:var(--ink3); cursor:pointer; transition:all .13s;
}
.pp-rm-btn:hover { background:var(--rlt); border-color:var(--rbd); color:var(--red); }

/* ══ SIDEBAR ══ */
.pp-summary { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; min-width:0; }
.pp-sum-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 16px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.pp-sum-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.pp-sum-b { padding:14px; overflow:hidden; }
.pp-sum-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:8px 12px; border:1px solid var(--bd2); border-radius:var(--r);
    background:var(--bg); margin-bottom:6px;
}
.pp-sum-row:last-child { margin-bottom:0; }
.pp-sum-key { font-size:11.5px; font-weight:600; color:var(--ink3); }
.pp-sum-val { font-size:13px; font-weight:800; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:130px; }
.pp-sum-total { flex-wrap:wrap;
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 14px; background:var(--glt); border:1px solid var(--gbd);
    border-radius:var(--rlg); margin:10px 0;
}
.pp-sum-total-lbl { font-size:13px; font-weight:700; color:var(--grn); }
.pp-sum-total-val { font-size:17px; font-weight:800; color:var(--grn); letter-spacing:-.5px; word-break:break-all; }

.pp-invoice-info { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.pp-inv-row { padding:10px 16px; border-bottom:1px solid var(--bd2); }
.pp-inv-row:last-child { border-bottom:none; }
.pp-inv-key { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.5px; margin-bottom:1px; }
.pp-inv-val { font-size:13px; font-weight:700; color:var(--ink); }

/* ══ FOOTER ══ */
.pp-foot {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 18px; background:var(--bg);
    border:1px solid var(--bd); border-radius:var(--rlg); margin-top:4px;
}
.pp-submit { word-break:keep-all; flex-wrap:nowrap; overflow:hidden; max-width:100%;
    display:inline-flex; align-items:center; gap:7px;
    padding:10px 22px; border-radius:var(--rlg);
    background:var(--grn); color:#fff; font-size:13px; font-weight:700;
    font-family:'Montserrat',sans-serif; border:none; cursor:pointer;
    transition:background .13s; box-shadow:0 2px 10px rgba(13,158,106,.35);
}
.pp-submit:hover { background:#0a8559; }

/* ══ SCROLLBAR ══ */
::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-track { background:var(--bg); }
::-webkit-scrollbar-thumb { background:#cdd0d8; border-radius:9999px; }

@media (max-width:1100px) { .pp-body { grid-template-columns:1fr; } .pp-right { position:static; } }
@media (max-width:768px)  {
    .pp { padding:16px; }
    .pp-hero { padding:22px 20px; flex-direction:column; align-items:flex-start; }
    .pp-grid-2, .pp-add-grid { grid-template-columns:1fr; }
}
</style>

@php $rowCount = $invoice->payoutItems->count(); @endphp

<div class="pp">

    {{-- ══ HERO ══ --}}
    <div class="pp-hero">
        <div class="pp-glow"></div>
        <div class="pp-hero-l">
            <div class="pp-hero-icon"><i class="fas fa-file-pdf"></i></div>
            <div>
                <div class="pp-hero-title">Prepare Payout</div>
                <div class="pp-hero-sub">
                    <a href="{{ route('superadmin.invoices.index') }}">Invoices</a>
                    <span class="sep">/</span>
                    <a href="{{ route('superadmin.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                    <span class="sep">/</span> Prepare PDF
                </div>
            </div>
        </div>
        <div class="pp-hero-r">
            <a href="{{ url()->previous() }}" class="pp-hbtn ghost">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('superadmin.invoices.generateCustomPdf', $invoice) }}" id="pp-form">
        @csrf

        <div class="pp-body">

            {{-- ══ LEFT ══ --}}
            <div class="pp-left">

                {{-- Invoice Details ── --}}
                <div class="pp-card">
                    <div class="pp-card-h">
                        <i class="fas fa-info-circle"></i>
                        <span class="pp-card-title">Invoice Details</span>
                    </div>
                    <div class="pp-card-b" style="display:flex;flex-direction:column;gap:14px">

                        {{-- Trailer + Crew ── --}}
                        <div class="pp-grid-2">
                            <div class="pp-info-row">
                                <div class="pp-info-icon {{ $invoice->crew?->has_trailer ? 'grn' : 'red' }}">
                                    <i class="fas fa-{{ $invoice->crew?->has_trailer ? 'check-circle' : 'times-circle' }}"></i>
                                </div>
                                <div>
                                    <div class="pp-info-key">Trailer Status</div>
                                    <div class="pp-info-val">
                                        {{ $invoice->crew?->has_trailer ? 'With Trailer' : 'No Trailer' }}
                                    </div>
                                </div>
                            </div>

                            <div class="pp-info-row">
                                @if($invoice->crew)
                                <div class="pp-crew-av">{{ strtoupper(substr($invoice->crew->name, 0, 1)) }}</div>
                                <div>
                                    <div class="pp-info-key">Assigned Crew</div>
                                    <div class="pp-info-val" style="display:flex;align-items:center">
                                        {{ $invoice->crew->name }}
                                        @if($invoice->crew->has_trailer)
                                        <span class="pp-crew-pill"><i class="fas fa-truck" style="font-size:9px"></i> Trailer</span>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <div class="pp-info-icon blue"><i class="fas fa-user-slash"></i></div>
                                <div>
                                    <div class="pp-info-key">Assigned Crew</div>
                                    <div class="pp-info-val" style="color:var(--ink3)">Not assigned</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Date + Address ── --}}
                        <div class="pp-grid-2">
                            <div class="pp-field">
                                <label class="pp-lbl" for="invoice_date">Invoice Date <span class="req">*</span></label>
                                <input type="date" id="invoice_date" name="invoice_date"
                                       class="pp-input" value="{{ $invoice->invoice_date }}" required>
                                <div class="pp-hint"><i class="fas fa-info-circle"></i> Date shown on the PDF</div>
                            </div>
                            <div class="pp-field">
                                <label class="pp-lbl" for="address">Job Address <span class="req">*</span></label>
                                <input type="text" id="address" name="address"
                                       class="pp-input"
                                       value="{{ old('address', $invoice->address) }}"
                                       placeholder="123 Main St, City, State ZIP" required>
                                <div class="pp-hint"><i class="fas fa-info-circle"></i> Location where work was performed</div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Add Items ── --}}
                <div class="pp-card">
                    <div class="pp-card-h">
                        <i class="fas fa-plus-circle"></i>
                        <span class="pp-card-title">Add Items</span>
                    </div>
                    <div class="pp-card-b">
                        <div class="pp-add-grid">

                            {{-- From catalog ── --}}
                            <div class="pp-add-card">
                                <div class="pp-add-card-title">From Catalog</div>
                                <div class="pp-add-row-catalog">
                                    <select class="pp-sel" id="availableItemSelect">
                                        <option value="">Select item…</option>
                                        @foreach($availableItems->groupBy(fn($i) => $i->category->name ?? 'Uncategorized') as $cat => $items)
                                        <optgroup label="{{ $cat }}">
                                            @foreach($items as $item)
                                            @php $price = $invoice->crew ? $item->getCrewPrice($invoice->crew->has_trailer) : 0; @endphp
                                            <option value="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
                                                    data-description="{{ $item->description }}"
                                                    data-price="{{ $price }}">
                                                {{ $item->name }} — ${{ number_format($price, 2) }}
                                            </option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                    <div class="pp-row-controls">
                                        <input type="number" id="newItemQty" class="pp-input" value="1" min="1" style="width:70px">
                                        <button type="button" class="pp-add-btn" style="flex:1" onclick="addNewItem()">
                                            <i class="fas fa-plus" style="font-size:10px"></i> Add Item
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Custom ── --}}
                            <div class="pp-add-card">
                                <div class="pp-add-card-title">Custom Item</div>
                                <div class="pp-add-row-custom">
                                    <div class="pp-row-top">
                                        <input type="text" id="customDescription" class="pp-input" placeholder="Item description">
                                    </div>
                                    <div class="pp-row-bottom">
                                        <input type="number" id="customPrice" step="0.01" class="pp-input" placeholder="Price" style="width:90px;flex:none">
                                        <input type="number" id="customQty" class="pp-input" value="1" min="1" style="width:70px;flex:none">
                                        <button type="button" class="pp-add-btn sec" style="flex:1" onclick="addCustomItem()">
                                            <i class="fas fa-plus" style="font-size:10px"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Items Table ── --}}
                <div class="pp-card">
                    <div class="pp-card-h">
                        <i class="fas fa-list"></i>
                        <span class="pp-card-title">Payout Items</span>
                    </div>
                    <div class="pp-tbl-wrap">
                        <table class="pp-tbl">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th class="r">Unit Price</th>
                                    <th class="c">Qty</th>
                                    <th class="r">Total</th>
                                    <th class="c" style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItemsTableBody">
                                @forelse($invoice->payoutItems as $index => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][description]" value="{{ $item->description }}">
                                        <div class="pp-desc">{{ $item->description }}</div>
                                    </td>
                                    <td style="text-align:right">
                                        <input type="number" step="0.01"
                                               name="items[{{ $index }}][price]"
                                               class="pp-price-input price-input"
                                               value="{{ old('items.'.$index.'.price', $item->price) }}">
                                    </td>
                                    <td style="text-align:center">
                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="pp-qty-input quantity-input"
                                               value="{{ $item->quantity }}" min="1">
                                    </td>
                                    <td class="pp-line-total">
                                        $<span class="subtotal-text">{{ number_format($item->price * $item->quantity, 2) }}</span>
                                    </td>
                                    <td style="text-align:center">
                                        <button type="button" class="pp-rm-btn" onclick="removeRow(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="pp-empty-row">
                                    <td colspan="5">
                                        <div class="pp-empty-row">
                                            <i class="fas fa-box-open"></i>
                                            No items added yet
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT ══ --}}
            <div class="pp-right">

                {{-- Totals ── --}}
                <div class="pp-summary">
                    <div class="pp-sum-h">
                        <i class="fas fa-calculator" style="font-size:12px;color:var(--ink3)"></i>
                        <span class="pp-sum-title">Payout Summary</span>
                    </div>
                    <div class="pp-sum-b">
                        <div class="pp-sum-row">
                            <span class="pp-sum-key">Subtotal</span>
                            <span class="pp-sum-val">$<span id="subtotalTotal">0.00</span></span>
                        </div>
                        <div class="pp-sum-row">
                            <span class="pp-sum-key">Tax</span>
                            <span class="pp-sum-val">$<span id="taxTotal">0.00</span></span>
                        </div>
                        <div class="pp-sum-total">
                            <span class="pp-sum-total-lbl">Total Payout</span>
                            <span class="pp-sum-total-val">$<span id="grandTotal">0.00</span></span>
                        </div>
                        <button type="submit" class="pp-submit" style="width:100%;justify-content:center">
                            <i class="fas fa-file-pdf" style="font-size:12px"></i> Generate PDF Payout
                        </button>
                    </div>
                </div>

                {{-- Invoice Info ── --}}
                <div class="pp-invoice-info">
                    <div class="pp-sum-h">
                        <i class="fas fa-file-invoice" style="font-size:12px;color:var(--ink3)"></i>
                        <span class="pp-sum-title">Invoice Info</span>
                    </div>
                    <div class="pp-inv-row">
                        <div class="pp-inv-key">Invoice #</div>
                        <div class="pp-inv-val">{{ $invoice->invoice_number }}</div>
                    </div>
                    <div class="pp-inv-row">
                        <div class="pp-inv-key">Bill To</div>
                        <div class="pp-inv-val">{{ $invoice->bill_to ?? '—' }}</div>
                    </div>
                    <div class="pp-inv-row">
                        <div class="pp-inv-key">Location</div>
                        <div class="pp-inv-val">
                            {{ $invoice->companyLocation->state }}
                            @if($invoice->companyLocation->city) / {{ $invoice->companyLocation->city }} @endif
                        </div>
                    </div>
                    <div class="pp-inv-row">
                        <div class="pp-inv-key">Status</div>
                        <div class="pp-inv-val">
                            @php $sc=['draft'=>['var(--bg)','var(--ink3)','var(--bd)'],'sent'=>['var(--blt)','var(--blue)','var(--bbd)'],'paid'=>['var(--glt)','var(--grn)','var(--gbd)']]; [$sb,$st,$sbd]=$sc[$invoice->status]??$sc['draft']; @endphp
                            <span style="display:inline-flex;align-items:center;font-size:11px;font-weight:800;padding:2px 8px;border-radius:5px;text-transform:uppercase;background:{{ $sb }};color:{{ $st }};border:1px solid {{ $sbd }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══ FOOTER ══ --}}
        <div class="pp-foot">
            <a href="{{ url()->previous() }}" class="pp-hbtn ghost" style="border-color:var(--bd);color:var(--ink2);background:var(--surf)">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button type="submit" class="pp-submit">
                <i class="fas fa-file-pdf" style="font-size:12px"></i> Generate PDF Payout
            </button>
        </div>

    </form>
</div>

<script>
let rowIndex = {{ $rowCount }};

function addNewItem() {
    const sel = document.getElementById('availableItemSelect');
    const opt = sel.options[sel.selectedIndex];
    const qty = parseInt(document.getElementById('newItemQty').value) || 1;
    if (!opt.value) return;
    const desc  = opt.dataset.description || opt.dataset.name;
    const price = parseFloat(opt.dataset.price) || 0;
    addItemToTable(desc, price, qty);
    sel.selectedIndex = 0;
    document.getElementById('newItemQty').value = 1;
}

function addCustomItem() {
    const desc  = document.getElementById('customDescription').value.trim();
    const price = parseFloat(document.getElementById('customPrice').value);
    const qty   = parseInt(document.getElementById('customQty').value) || 1;
    if (!desc || isNaN(price)) return;
    addItemToTable(desc, price, qty);
    document.getElementById('customDescription').value = '';
    document.getElementById('customPrice').value       = '';
    document.getElementById('customQty').value         = 1;
}

function addItemToTable(description, price, quantity) {
    // remove empty row if present
    const empty = document.getElementById('pp-empty-row');
    if (empty) empty.remove();

    const tbody = document.getElementById('invoiceItemsTableBody');
    const row   = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="hidden" name="items[${rowIndex}][description]" value="${description}">
            <div class="pp-desc">${description}</div>
        </td>
        <td style="text-align:right">
            <input type="number" step="0.01" name="items[${rowIndex}][price]"
                   class="pp-price-input price-input" value="${price.toFixed(2)}">
        </td>
        <td style="text-align:center">
            <input type="number" name="items[${rowIndex}][quantity]"
                   class="pp-qty-input quantity-input" value="${quantity}" min="1">
        </td>
        <td class="pp-line-total">$<span class="subtotal-text">${(price*quantity).toFixed(2)}</span></td>
        <td style="text-align:center">
            <button type="button" class="pp-rm-btn" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>`;
    tbody.appendChild(row);
    rowIndex++;
    updateSubtotals();
}

function removeRow(btn) {
    btn.closest('tr').remove();
    updateSubtotals();
}

function updateSubtotals() {
    let sub = 0;
    document.querySelectorAll('#invoiceItemsTableBody tr').forEach(row => {
        const p = parseFloat(row.querySelector('.price-input')?.value)    || 0;
        const q = parseInt(row.querySelector('.quantity-input')?.value)   || 0;
        const t = p * q;
        sub += t;
        const span = row.querySelector('.subtotal-text');
        if (span) span.textContent = t.toFixed(2);
    });
    document.getElementById('subtotalTotal').textContent = sub.toFixed(2);
    document.getElementById('taxTotal').textContent      = '0.00';
    document.getElementById('grandTotal').textContent    = sub.toFixed(2);
}

document.addEventListener('change', e => {
    if (e.target.classList.contains('price-input') ||
        e.target.classList.contains('quantity-input')) {
        updateSubtotals();
    }
});

document.addEventListener('DOMContentLoaded', updateSubtotals);
</script>

@endsection