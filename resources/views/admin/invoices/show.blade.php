@extends('admin.layouts.superadmin')
@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ivs { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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
.ivs-hero {
    position: relative; border-radius: var(--rxl);
    padding: 28px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ivs-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.ivs-hero::after {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.ivs-glow {
    position:absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events:none;
}
.ivs-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.ivs-hero-icon {
    width:48px; height:48px; border-radius:13px; flex-shrink:0;
    background:rgba(24,85,224,.2); border:1px solid rgba(24,85,224,.35);
    display:flex; align-items:center; justify-content:center; font-size:18px; color:#8aadff;
}
.ivs-hero-title { font-size:21px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; display:flex; align-items:center; gap:10px; }
.ivs-hero-sub   { font-size:12px; font-weight:600; color:rgba(255,255,255,.38); margin-top:5px; }
.ivs-hero-r { position:relative; display:flex; align-items:center; gap:8px; }

/* status badge in hero */
.ivs-status {
    font-size:11px; font-weight:800; padding:4px 11px;
    border-radius:8px; text-transform:uppercase; letter-spacing:.5px;
    display:inline-flex; align-items:center; gap:5px;
}
.ivs-status.draft { background:rgba(255,255,255,.1); color:rgba(255,255,255,.6); border:1px solid rgba(255,255,255,.15); }
.ivs-status.sent  { background:rgba(24,85,224,.3);   color:#8aadff;             border:1px solid rgba(24,85,224,.4); }
.ivs-status.paid  { background:rgba(13,158,106,.3);  color:#34d399;             border:1px solid rgba(13,158,106,.4); }

/* hero action buttons */
.ivs-hbtn {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px; border-radius:var(--rlg);
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap;
}
.ivs-hbtn.ghost { background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.12); color:rgba(255,255,255,.6); }
.ivs-hbtn.ghost:hover { background:rgba(255,255,255,.15); color:#fff; }
.ivs-hbtn.blue  { background:var(--blue); color:#fff; box-shadow:0 2px 8px rgba(24,85,224,.4); }
.ivs-hbtn.blue:hover  { background:#1344c2; }
.ivs-hbtn.pdf   { background:rgba(215,38,38,.25); border-color:rgba(215,38,38,.3); color:#fca5a5; }
.ivs-hbtn.pdf:hover { background:rgba(215,38,38,.35); color:#fff; }

/* ══ STAT CARDS ══ */
.ivs-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:22px; }
.ivs-stat {
    background:var(--surf); border:1px solid var(--bd);
    border-radius:var(--rlg); padding:15px 18px;
    display:flex; align-items:center; gap:13px;
}
.ivs-stat-icon {
    width:40px; height:40px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:15px;
}
.ivs-stat-icon.blue { background:var(--blt); color:var(--blue); }
.ivs-stat-icon.grn  { background:var(--glt); color:var(--grn); }
.ivs-stat-icon.amb  { background:var(--alt); color:var(--amb); }
.ivs-stat-icon.ink  { background:var(--bg);  color:var(--ink3); border:1px solid var(--bd); }
.ivs-stat-lbl { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.6px; }
.ivs-stat-val { font-size:20px; font-weight:800; color:var(--ink); line-height:1.1; margin-top:2px; }
.ivs-stat-val.sm { font-size:14px; font-weight:700; }

/* ══ LAYOUT ══ */
.ivs-body { display:grid; grid-template-columns:1fr 300px; gap:18px; align-items:start; }
.ivs-left { display:flex; flex-direction:column; gap:16px; }
.ivs-right { display:flex; flex-direction:column; gap:14px; position:sticky; top:90px; }

/* ══ CARDS ══ */
.ivs-card { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.ivs-card-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 20px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ivs-card-h i  { font-size:13px; color:var(--blue); }
.ivs-card-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ivs-card-b { padding:20px; }

/* ══ TABLE ══ */
.ivs-tbl-wrap { overflow-x:auto; scrollbar-width:thin; scrollbar-color:#cdd0d8 var(--bg); }
.ivs-tbl-wrap::-webkit-scrollbar { height:4px; }
.ivs-tbl-wrap::-webkit-scrollbar-thumb { background:#cdd0d8; border-radius:9999px; }
table.ivs-tbl { width:100%; border-collapse:collapse; font-family:'Montserrat',sans-serif; }
table.ivs-tbl thead { background:#fafbfd; border-bottom:1px solid var(--bd); }
table.ivs-tbl th {
    padding:10px 18px; text-align:left;
    font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.8px; white-space:nowrap;
}
table.ivs-tbl th.c { text-align:center; }
table.ivs-tbl th.r { text-align:right; }
table.ivs-tbl td { padding:12px 18px; border-bottom:1px solid var(--bd2); vertical-align:middle; }
table.ivs-tbl tbody tr:last-child td { border-bottom:none; }
table.ivs-tbl tbody tr:hover td { background:#fafbfd; }
.ivs-item-name { font-size:13px; font-weight:700; color:var(--ink); }
.ivs-item-note { font-size:11.5px; font-weight:500; color:var(--ink3); margin-top:2px; font-style:italic; }
.ivs-tbl-qty   { font-size:13px; font-weight:700; color:var(--ink); text-align:center; }
.ivs-tbl-price { font-size:13px; font-weight:600; color:var(--ink2); text-align:right; }
.ivs-tbl-total { font-size:13px; font-weight:800; color:var(--ink); text-align:right; }

/* totals footer */
.ivs-tbl-foot { padding:14px 20px; border-top:1px solid var(--bd); background:var(--bg); }
.ivs-total-row {
    display:flex; align-items:center; justify-content:space-between;
    max-width:300px; margin-left:auto; padding:5px 0;
    font-size:12.5px; font-weight:600; color:var(--ink2);
}
.ivs-total-row.final {
    border-top:1px solid var(--bd); margin-top:4px; padding-top:9px;
    font-size:14px; font-weight:800; color:var(--ink);
}
.ivs-total-row span:last-child { font-weight:800; color:var(--ink); }

/* ══ NOTES / MEMO ══ */
.ivs-2col { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.ivs-note-card { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.ivs-note-h    { display:flex; align-items:center; gap:7px; padding:11px 16px; border-bottom:1px solid var(--bd2); background:linear-gradient(to right,var(--surf),#fafbfd); }
.ivs-note-icon { font-size:12px; color:var(--blue); }
.ivs-note-title { font-size:11px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ivs-note-b    { padding:14px 16px; font-size:13px; font-weight:500; color:var(--ink2); line-height:1.6; white-space:pre-line; }
.ivs-note-empty { color:var(--ink3); font-style:italic; font-size:12.5px; }

/* ══ SIDEBAR INFO ══ */
.ivs-info-card { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.ivs-info-h    { display:flex; align-items:center; gap:8px; padding:13px 16px; border-bottom:1px solid var(--bd2); background:linear-gradient(to right,var(--surf),#fafbfd); }
.ivs-info-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ivs-info-row  { padding:11px 16px; border-bottom:1px solid var(--bd2); }
.ivs-info-row:last-child { border-bottom:none; }
.ivs-info-key  { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.5px; margin-bottom:2px; }
.ivs-info-val  { font-size:13px; font-weight:700; color:var(--ink); }
.ivs-info-val a { color:var(--blue); text-decoration:none; }
.ivs-info-val a:hover { text-decoration:underline; }

/* crew badge */
.ivs-crew-pill {
    display:inline-flex; align-items:center; gap:5px;
    font-size:11px; font-weight:700; padding:3px 9px;
    border-radius:6px; background:var(--bg); color:var(--ink3); border:1px solid var(--bd);
    margin-left:8px;
}

/* ══ ATTACHMENTS ══ */
.ivs-attach-list { display:flex; flex-direction:column; gap:7px; }
.ivs-attach-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:9px 12px; border:1px solid var(--bd2); border-radius:var(--r);
    background:var(--bg); transition:border-color .13s;
}
.ivs-attach-row:hover { border-color:var(--bbd); background:var(--blt); }
.ivs-attach-l { display:flex; align-items:center; gap:10px; }
.ivs-attach-icon {
    width:32px; height:32px; border-radius:8px; flex-shrink:0;
    background:var(--surf); border:1px solid var(--bd);
    display:flex; align-items:center; justify-content:center; font-size:13px; color:var(--ink3);
}
.ivs-attach-name { font-size:12.5px; font-weight:700; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:160px; }
.ivs-attach-date { font-size:11px; font-weight:500; color:var(--ink3); margin-top:1px; }
.ivs-attach-dl {
    width:30px; height:30px; border-radius:8px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; color:var(--ink3); text-decoration:none;
    border:1px solid transparent; transition:all .13s;
}
.ivs-attach-dl:hover { background:var(--blue); color:#fff; border-color:var(--blue); }

/* ══ FOOTER ══ */
.ivs-foot {
    margin-top:22px; padding-top:16px; border-top:1px solid var(--bd);
    display:flex; align-items:center; justify-content:space-between;
    font-size:12px; font-weight:600; color:var(--ink3);
}

/* ══ SCROLLBAR ══ */
::-webkit-scrollbar { width:5px; }
::-webkit-scrollbar-track { background:var(--bg); }
::-webkit-scrollbar-thumb { background:#cdd0d8; border-radius:9999px; }

@media (max-width:1100px) { .ivs-body { grid-template-columns:1fr; } .ivs-right { position:static; } }
@media (max-width:900px)  { .ivs-stats { grid-template-columns:1fr 1fr; } }
@media (max-width:768px)  {
    .ivs { padding:16px; }
    .ivs-hero { padding:22px 20px; flex-direction:column; align-items:flex-start; }
    .ivs-2col { grid-template-columns:1fr; }
    .ivs-stats { grid-template-columns:1fr 1fr; }
}
</style>

<div class="ivs">

    {{-- ══ HERO ══ --}}
    <div class="ivs-hero">
        <div class="ivs-glow"></div>
        <div class="ivs-hero-l">
            <div class="ivs-hero-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <div>
                <div class="ivs-hero-title">
                    {{ $invoice->invoice_number }}
                    <span class="ivs-status {{ $invoice->status }}">
                        @if($invoice->status === 'paid')
                            <i class="fas fa-check-circle" style="font-size:9px"></i>
                        @elseif($invoice->status === 'sent')
                            <i class="fas fa-paper-plane" style="font-size:9px"></i>
                        @else
                            <i class="fas fa-pencil-alt" style="font-size:9px"></i>
                        @endif
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
                <div class="ivs-hero-sub">
                    {{ $invoice->bill_to ?? '—' }}
                    @if($invoice->companyLocation->company->company_name ?? null)
                        &nbsp;·&nbsp; {{ $invoice->companyLocation->company->company_name }}
                    @endif
                    &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}
                </div>
            </div>
        </div>
        <div class="ivs-hero-r">
            <a href="{{ route('superadmin.invoices.index') }}" class="ivs-hbtn ghost">
                <i class="fas fa-arrow-left" style="font-size:10px"></i> Back
            </a>
            @if($invoice->status === 'draft')
            <a href="{{ route('superadmin.invoices.edit', $invoice) }}" class="ivs-hbtn blue">
                <i class="fas fa-pen" style="font-size:10px"></i> Edit
            </a>
            @endif
            <a href="{{ route('superadmin.invoices.pdf', $invoice) }}" target="_blank" class="ivs-hbtn pdf">
                <i class="fas fa-file-pdf" style="font-size:10px"></i> Download PDF
            </a>
        </div>
    </div>

    {{-- ══ STATS ══ --}}
    <div class="ivs-stats">
        <div class="ivs-stat">
            <div class="ivs-stat-icon blue"><i class="fas fa-dollar-sign"></i></div>
            <div>
                <div class="ivs-stat-lbl">Total Amount</div>
                <div class="ivs-stat-val">${{ number_format($invoice->total, 2) }}</div>
            </div>
        </div>
        <div class="ivs-stat">
            <div class="ivs-stat-icon grn"><i class="fas fa-list"></i></div>
            <div>
                <div class="ivs-stat-lbl">Items</div>
                <div class="ivs-stat-val">{{ $invoice->items->count() }}</div>
            </div>
        </div>
        <div class="ivs-stat">
            <div class="ivs-stat-icon amb"><i class="fas fa-user"></i></div>
            <div>
                <div class="ivs-stat-lbl">Customer</div>
                <div class="ivs-stat-val sm" title="{{ $invoice->bill_to }}">{{ Str::limit($invoice->bill_to ?? '—', 22) }}</div>
            </div>
        </div>
        <div class="ivs-stat">
            <div class="ivs-stat-icon ink"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <div class="ivs-stat-lbl">Due Date</div>
                <div class="ivs-stat-val sm">
                    @if($invoice->due_date)
                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="ivs-body">

        {{-- ══ LEFT ══ --}}
        <div class="ivs-left">

            {{-- Items table ── --}}
            <div class="ivs-card">
                <div class="ivs-card-h">
                    <i class="fas fa-list"></i>
                    <span class="ivs-card-title">Invoice Items</span>
                </div>
                <div class="ivs-tbl-wrap">
                    <table class="ivs-tbl">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="c">Qty</th>
                                <th class="r">Unit Price</th>
                                <th class="r">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td>
                                    <div class="ivs-item-name">{{ $item->description }}</div>
                                    @if($item->note)
                                    <div class="ivs-item-note">{{ $item->note }}</div>
                                    @endif
                                </td>
                                <td class="ivs-tbl-qty">{{ $item->quantity }}</td>
                                <td class="ivs-tbl-price">${{ number_format($item->price, 2) }}</td>
                                <td class="ivs-tbl-total">${{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="ivs-tbl-foot">
                    <div class="ivs-total-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->tax > 0)
                    <div class="ivs-total-row">
                        <span>Tax</span>
                        <span>${{ number_format($invoice->tax, 2) }}</span>
                    </div>
                    @endif
                    <div class="ivs-total-row final">
                        <span>Total</span>
                        <span>${{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes & Memo ── --}}
            <div class="ivs-2col">
                <div class="ivs-note-card">
                    <div class="ivs-note-h">
                        <i class="fas fa-comment-alt ivs-note-icon"></i>
                        <span class="ivs-note-title">Notes</span>
                    </div>
                    <div class="ivs-note-b">
                        @if($invoice->notes)
                            {{ $invoice->notes }}
                        @else
                            <span class="ivs-note-empty">No notes provided</span>
                        @endif
                    </div>
                </div>
                <div class="ivs-note-card">
                    <div class="ivs-note-h">
                        <i class="fas fa-lock ivs-note-icon"></i>
                        <span class="ivs-note-title">Internal Memo</span>
                    </div>
                    <div class="ivs-note-b">
                        @if($invoice->memo)
                            {{ $invoice->memo }}
                        @else
                            <span class="ivs-note-empty">No memo provided</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT ══ --}}
        <div class="ivs-right">

            {{-- Customer info ── --}}
            <div class="ivs-info-card">
                <div class="ivs-info-h">
                    <i class="fas fa-user" style="font-size:12px;color:var(--blue)"></i>
                    <span class="ivs-info-title">Customer</span>
                </div>
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Bill To</div>
                    <div class="ivs-info-val">{{ $invoice->bill_to ?? '—' }}</div>
                </div>
                @if($invoice->address)
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Address</div>
                    <div class="ivs-info-val">{{ $invoice->address }}</div>
                </div>
                @endif
                @if($invoice->customer_email)
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Email</div>
                    <div class="ivs-info-val">
                        <a href="mailto:{{ $invoice->customer_email }}">{{ $invoice->customer_email }}</a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Company & Location ── --}}
            <div class="ivs-info-card">
                <div class="ivs-info-h">
                    <i class="fas fa-building" style="font-size:12px;color:var(--blue)"></i>
                    <span class="ivs-info-title">Company & Location</span>
                </div>
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Company</div>
                    <div class="ivs-info-val">{{ $invoice->companyLocation->company->company_name ?? '—' }}</div>
                </div>
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Location</div>
                    <div class="ivs-info-val">
                        @if($invoice->companyLocation->city)
                            {{ $invoice->companyLocation->city }},
                        @endif
                        {{ $invoice->companyLocation->state ?? '—' }}
                    </div>
                </div>
                @if($invoice->crew)
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Crew</div>
                    <div class="ivs-info-val" style="display:flex;align-items:center">
                        {{ $invoice->crew->name }}
                        @if($invoice->crew->has_trailer)
                        <span class="ivs-crew-pill"><i class="fas fa-truck" style="font-size:9px"></i> Trailer</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Invoice details ── --}}
            <div class="ivs-info-card">
                <div class="ivs-info-h">
                    <i class="fas fa-file-invoice" style="font-size:12px;color:var(--blue)"></i>
                    <span class="ivs-info-title">Invoice Details</span>
                </div>
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Invoice #</div>
                    <div class="ivs-info-val">{{ $invoice->invoice_number }}</div>
                </div>
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Issue Date</div>
                    <div class="ivs-info-val">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</div>
                </div>
                @if($invoice->due_date)
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Due Date</div>
                    <div class="ivs-info-val">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>
                </div>
                @endif
                <div class="ivs-info-row">
                    <div class="ivs-info-key">Status</div>
                    <div class="ivs-info-val">
                        @php
                            $sc = ['draft'=>['var(--bg)','var(--ink3)','var(--bd)'], 'sent'=>['var(--blt)','var(--blue)','var(--bbd)'], 'paid'=>['var(--glt)','var(--grn)','var(--gbd)']];
                            [$sbg,$stx,$sbd] = $sc[$invoice->status] ?? $sc['draft'];
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:800;padding:3px 9px;border-radius:6px;text-transform:uppercase;letter-spacing:.4px;background:{{ $sbg }};color:{{ $stx }};border:1px solid {{ $sbd }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Attachments ── --}}
            @if($invoice->attachments->count())
            <div class="ivs-info-card">
                <div class="ivs-info-h" style="justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:8px">
                        <i class="fas fa-paperclip" style="font-size:12px;color:var(--blue)"></i>
                        <span class="ivs-info-title">Attachments</span>
                    </div>
                    <span style="font-size:11px;font-weight:800;padding:2px 8px;border-radius:5px;background:var(--bg);color:var(--ink3);border:1px solid var(--bd)">
                        {{ $invoice->attachments->count() }}
                    </span>
                </div>
                <div style="padding:12px 14px">
                    <div class="ivs-attach-list">
                        @foreach($invoice->attachments as $file)
                        <div class="ivs-attach-row">
                            <div class="ivs-attach-l">
                                <div class="ivs-attach-icon">
                                    @php
                                        $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                        $fic = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'fa-image' :
                                               ($ext === 'pdf' ? 'fa-file-pdf' :
                                               (in_array($ext, ['doc','docx']) ? 'fa-file-word' :
                                               (in_array($ext, ['xls','xlsx']) ? 'fa-file-excel' : 'fa-file')));
                                    @endphp
                                    <i class="fas {{ $fic }}"></i>
                                </div>
                                <div>
                                    <div class="ivs-attach-name" title="{{ basename($file->original_name) }}">{{ basename($file->original_name) }}</div>
                                    <div class="ivs-attach-date">{{ \Carbon\Carbon::parse($file->created_at)->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="ivs-attach-dl" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ══ FOOTER ══ --}}
    <div class="ivs-foot">
        <span>
            Created {{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}
            @if($invoice->updated_at && $invoice->created_at != $invoice->updated_at)
                &nbsp;·&nbsp; Updated {{ \Carbon\Carbon::parse($invoice->updated_at)->format('M d, Y') }}
            @endif
        </span>
        <a href="{{ route('superadmin.invoices.pdf', $invoice) }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:700;color:var(--red);text-decoration:none">
            <i class="fas fa-file-pdf" style="font-size:11px"></i> Download PDF
        </a>
    </div>

</div>

@endsection