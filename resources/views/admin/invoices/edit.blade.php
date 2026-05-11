@extends('admin.layouts.superadmin')
@section('title', 'Edit Invoice · ' . $invoice->invoice_number)

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.ive { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

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
.ive-hero {
    position: relative; border-radius: var(--rxl);
    padding: 28px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.ive-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.ive-hero::after {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.ive-glow {
    position:absolute; right:-60px; top:-60px; width:540px; height:280px;
    background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%);
    pointer-events:none;
}
.ive-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.ive-hero-icon {
    width:48px; height:48px; border-radius:13px; flex-shrink:0;
    background:rgba(24,85,224,.2); border:1px solid rgba(24,85,224,.35);
    display:flex; align-items:center; justify-content:center; font-size:18px; color:#8aadff;
}
.ive-hero-title { font-size:21px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; display:flex; align-items:center; gap:10px; }
.ive-hero-sub   { font-size:12px; font-weight:600; color:rgba(255,255,255,.38); margin-top:5px; }
.ive-hero-sub a { color:rgba(255,255,255,.4); text-decoration:none; }
.ive-hero-sub a:hover { color:rgba(255,255,255,.65); }
.ive-hero-sub .sep { margin:0 5px; }
.ive-hero-r { position:relative; display:flex; align-items:center; gap:8px; }

.ive-status {
    font-size:11px; font-weight:800; padding:4px 11px;
    border-radius:8px; text-transform:uppercase; letter-spacing:.5px;
    display:inline-flex; align-items:center; gap:5px;
}
.ive-status.draft { background:rgba(255,255,255,.1); color:rgba(255,255,255,.6); border:1px solid rgba(255,255,255,.15); }
.ive-status.sent  { background:rgba(24,85,224,.3); color:#8aadff; border:1px solid rgba(24,85,224,.4); }
.ive-status.paid  { background:rgba(13,158,106,.3); color:#34d399; border:1px solid rgba(13,158,106,.4); }

.ive-hbtn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 18px; border-radius:var(--rlg);
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap;
}
.ive-hbtn i { font-size:10px; }
.ive-hbtn.ghost { background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.12); color:rgba(255,255,255,.6); }
.ive-hbtn.ghost:hover { background:rgba(255,255,255,.15); color:#fff; }
.ive-hbtn.blue  { background:var(--blue); color:#fff; box-shadow:0 2px 8px rgba(24,85,224,.4); }
.ive-hbtn.blue:hover { background:#1344c2; }

/* ══ ALERT ══ */
.ive-alert {
    display:none; padding:11px 16px; border-radius:var(--rlg); margin-bottom:14px;
    font-size:13px; font-weight:600; align-items:center; gap:8px; animation:fd .2s ease;
}
.ive-alert.show { display:flex; }
.ive-alert.err  { background:var(--rlt); border:1px solid var(--rbd); color:var(--red); }
.ive-alert.ok   { background:var(--glt); border:1px solid var(--gbd); color:var(--grn); }
@keyframes fd { from{opacity:0;transform:translateY(-4px)} to{opacity:1} }

/* ══ LAYOUT ══ */
.ive-body { display:grid; grid-template-columns:1fr 300px; gap:18px; align-items:start; }
.ive-left { display:flex; flex-direction:column; gap:16px; }
.ive-right { display:flex; flex-direction:column; gap:14px; position:sticky; top:90px; }

/* ══ CARDS ══ */
.ive-card { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.ive-card-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 20px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ive-card-h i { font-size:13px; color:var(--blue); }
.ive-card-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ive-card-b { padding:20px; }

/* ══ FIELDS ══ */
.ive-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.ive-field  { display:flex; flex-direction:column; gap:5px; }
.ive-field.full { grid-column:1/-1; }
.ive-lbl {
    font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px;
}
.ive-lbl .req  { color:var(--red); margin-left:2px; }
.ive-lbl .hint { color:var(--ink3); font-weight:500; text-transform:none; letter-spacing:0; font-size:9.5px; margin-left:4px; }

.ive-input, .ive-sel, .ive-textarea {
    padding:9px 12px; border:1.5px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; width:100%;
    transition:border-color .15s, box-shadow .15s;
}
.ive-input:focus, .ive-sel:focus, .ive-textarea:focus {
    border-color:var(--blue); box-shadow:0 0 0 3px rgba(24,85,224,.08);
}
.ive-input.is-invalid, .ive-sel.is-invalid { border-color:var(--red); background:var(--rlt); }
.ive-sel {
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238c95a6' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right 11px center; padding-right:32px;
}
.ive-sel:disabled { background-color:var(--bg); color:var(--ink3); cursor:not-allowed; }
.ive-textarea { resize:vertical; min-height:72px; }
.ive-ferr { font-size:11px; font-weight:600; color:var(--red); display:none; align-items:center; gap:4px; }
.ive-ferr.show { display:flex; }
.ive-hint-txt { font-size:11px; font-weight:500; color:var(--ink3); }

.ive-note-ta {
    width:100%; border:none; outline:none; background:transparent;
    font-size:11.5px; font-weight:500; font-family:'Montserrat',sans-serif;
    color:var(--ink3); resize:none; overflow:hidden; min-height:22px; padding:3px 0;
}
.ive-note-ta::placeholder { color:var(--bd); }
.ive-note-ta:focus { color:var(--ink); }

/* ══ ITEM ADD ROW ══ */
.ive-item-row { display:flex; align-items:flex-end; gap:10px; }
.ive-item-row .ive-field { flex:1; }
.ive-qty-wrap { display:flex; align-items:stretch; width:130px; flex-shrink:0; }
.ive-qty-wrap input {
    flex:1; padding:9px 8px; text-align:center;
    border:1.5px solid var(--bd); border-left:none; border-right:none; border-radius:0;
    font-size:13px; font-weight:600; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none;
}
.ive-qty-btn {
    padding:0 12px; border:1.5px solid var(--bd);
    background:var(--bg); color:var(--ink3); font-size:13px; font-weight:700;
    cursor:pointer; transition:all .13s; line-height:1;
}
.ive-qty-btn:first-child { border-radius:var(--r) 0 0 var(--r); }
.ive-qty-btn:last-child  { border-radius:0 var(--r) var(--r) 0; }
.ive-qty-btn:hover { background:var(--blt); color:var(--blue); border-color:var(--bbd); }

.ive-add-btn {
    display:inline-flex; align-items:center; gap:5px;
    padding:9px 16px; border-radius:var(--r);
    background:var(--blue); color:#fff; font-size:12.5px; font-weight:700;
    font-family:'Montserrat',sans-serif; border:none; cursor:pointer; transition:background .13s;
    white-space:nowrap; flex-shrink:0;
}
.ive-add-btn:hover { background:#1344c2; }

.ive-custom-btn {
    display:inline-flex; align-items:center; gap:5px;
    padding:7px 13px; border-radius:var(--r); margin-top:10px;
    background:none; border:1px solid var(--bd); color:var(--ink2);
    font-size:12px; font-weight:700; font-family:'Montserrat',sans-serif;
    cursor:pointer; transition:all .13s;
}
.ive-custom-btn:hover { background:var(--bg); border-color:var(--bbd); color:var(--blue); }

.ive-custom-box {
    display:none; margin-top:12px;
    background:var(--bg); border:1px solid var(--bd); border-radius:var(--rlg); padding:14px;
}
.ive-custom-box.open { display:block; }
.ive-custom-row { display:grid; grid-template-columns:2fr 1fr 80px auto; gap:8px; align-items:end; }

/* ══ TABLE ══ */
.ive-tbl-wrap { overflow-x:auto; margin-top:16px; scrollbar-width:thin; scrollbar-color:#cdd0d8 var(--bg); }
table.ive-items { width:100%; border-collapse:collapse; font-family:'Montserrat',sans-serif; }
table.ive-items thead { background:#fafbfd; border-bottom:1px solid var(--bd); }
table.ive-items th {
    padding:9px 14px; text-align:left;
    font-size:10px; font-weight:800; color:var(--ink3);
    text-transform:uppercase; letter-spacing:.7px; white-space:nowrap;
}
table.ive-items th.r { text-align:right; }
table.ive-items th.c { text-align:center; }
table.ive-items td { padding:10px 14px; border-bottom:1px solid var(--bd2); vertical-align:middle; }
table.ive-items tbody tr:last-child td { border-bottom:none; }
table.ive-items tbody tr:hover td { background:#fafbfd; }
.ive-item-name  { font-size:13px; font-weight:700; color:var(--ink); }
.ive-item-total { font-size:13px; font-weight:800; color:var(--ink); text-align:right; }
.ive-empty-row  { padding:40px 14px; text-align:center; color:var(--ink3); font-size:13px; font-weight:500; }
.ive-empty-row i { display:block; font-size:24px; opacity:.2; margin-bottom:8px; }

.ive-tbl-qty { display:flex; align-items:center; width:100px; margin:0 auto; }
.ive-tbl-qty input {
    flex:1; text-align:center; border:1.5px solid var(--bd);
    border-left:none; border-right:none; padding:5px 4px;
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none;
}
.ive-tbl-qbtn {
    width:26px; height:26px; border:1.5px solid var(--bd);
    background:var(--bg); color:var(--ink3); font-size:12px; font-weight:800;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    transition:all .13s; flex-shrink:0;
}
.ive-tbl-qbtn:first-child { border-radius:var(--r) 0 0 var(--r); }
.ive-tbl-qbtn:last-child  { border-radius:0 var(--r) var(--r) 0; }
.ive-tbl-qbtn:hover { background:var(--blt); color:var(--blue); border-color:var(--bbd); }

.ive-price-input {
    width:100px; padding:5px 8px; text-align:right;
    border:1.5px solid var(--bd); border-radius:var(--r);
    font-size:13px; font-weight:700; font-family:'Montserrat',sans-serif;
    color:var(--ink); background:var(--surf); outline:none; display:inline-block;
    transition:border-color .15s;
}
.ive-price-input:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(24,85,224,.08); }

.ive-rm-btn {
    width:28px; height:28px; border-radius:7px;
    display:flex; align-items:center; justify-content:center;
    font-size:11px; border:1px solid transparent;
    background:none; color:var(--ink3); cursor:pointer; transition:all .13s; margin:0 auto;
}
.ive-rm-btn:hover { background:var(--rlt); border-color:var(--rbd); color:var(--red); }

.ive-items-err { font-size:11.5px; font-weight:600; color:var(--red); display:none; align-items:center; gap:5px; margin-top:8px; }
.ive-items-err.show { display:flex; }

/* ══ SIDEBAR ══ */
.ive-summary { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.ive-sum-h {
    display:flex; align-items:center; gap:8px;
    padding:13px 16px; border-bottom:1px solid var(--bd2);
    background:linear-gradient(to right,var(--surf),#fafbfd);
}
.ive-sum-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.ive-sum-b { padding:16px; }
.ive-sum-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:8px 12px; border:1px solid var(--bd2); border-radius:var(--r);
    background:var(--bg); margin-bottom:6px;
}
.ive-sum-row:last-child { margin-bottom:0; }
.ive-sum-key { font-size:11.5px; font-weight:600; color:var(--ink3); }
.ive-sum-val { font-size:13px; font-weight:800; color:var(--ink); }
.ive-sum-total {
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 14px; background:var(--blt); border:1px solid var(--bbd);
    border-radius:var(--rlg); margin:10px 0;
}
.ive-sum-total-lbl { font-size:13px; font-weight:700; color:var(--blue); }
.ive-sum-total-val { font-size:20px; font-weight:800; color:var(--blue); letter-spacing:-.5px; }

.ive-quick-info { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.ive-qi-row { display:flex; flex-direction:column; gap:1px; padding:9px 14px; border-bottom:1px solid var(--bd2); }
.ive-qi-row:last-child { border-bottom:none; }
.ive-qi-key { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.5px; }
.ive-qi-val { font-size:13px; font-weight:700; color:var(--ink); }

/* ══ BUTTONS ══ */
.ive-btn {
    display:inline-flex; align-items:center; gap:6px; width:100%;
    padding:9px 18px; border-radius:var(--r); justify-content:center;
    font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif;
    border:1px solid transparent; cursor:pointer; transition:all .13s;
    text-decoration:none; white-space:nowrap; margin-bottom:8px;
}
.ive-btn:last-child { margin-bottom:0; }
.ive-btn i { font-size:10px; }
.ive-btn.blue  { background:var(--blue); color:#fff; }
.ive-btn.blue:hover { background:#1344c2; color:#fff; }
.ive-btn.ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.ive-btn.ghost:hover { background:var(--bg); }

::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-track { background:var(--bg); }
::-webkit-scrollbar-thumb { background:#cdd0d8; border-radius:9999px; }

@media (max-width:1100px) { .ive-body { grid-template-columns:1fr; } .ive-right { position:static; } }
@media (max-width:768px)  {
    .ive { padding:16px; }
    .ive-hero { padding:22px 20px; flex-direction:column; align-items:flex-start; }
    .ive-grid-2 { grid-template-columns:1fr; }
    .ive-custom-row { grid-template-columns:1fr 1fr; }
}
</style>

<div class="ive">

    {{-- ══ HERO ══ --}}
    <div class="ive-hero">
        <div class="ive-glow"></div>
        <div class="ive-hero-l">
            <div class="ive-hero-icon"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <div class="ive-hero-title">
                    Edit Invoice
                    <span class="ive-status {{ $invoice->status }}">
                        @if($invoice->status === 'paid')   <i class="fas fa-check-circle" style="font-size:9px"></i>
                        @elseif($invoice->status === 'sent') <i class="fas fa-paper-plane" style="font-size:9px"></i>
                        @else <i class="fas fa-pencil-alt" style="font-size:9px"></i>
                        @endif
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
                <div class="ive-hero-sub">
                    <a href="{{ route('superadmin.invoices.index') }}">Invoices</a>
                    <span class="sep">/</span>
                    <a href="{{ route('superadmin.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                    <span class="sep">/</span> Edit
                </div>
            </div>
        </div>
        <div class="ive-hero-r">
            <a href="{{ route('superadmin.invoices.index', $invoice) }}" class="ive-hbtn ghost">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button class="ive-hbtn blue" onclick="updateInvoice()" id="updateButton">
                <i class="fas fa-floppy-disk"></i> Update Invoice
            </button>
        </div>
    </div>

    {{-- ══ ALERT ══ --}}
    <div class="ive-alert" id="topAlert">
        <i class="fas fa-exclamation-circle"></i>
        <span id="topAlertMsg"></span>
    </div>

    <div class="ive-body">

        {{-- ══ LEFT ══ --}}
        <div class="ive-left">

            {{-- Invoice Info ── --}}
            <div class="ive-card">
                <div class="ive-card-h">
                    <i class="fas fa-file-invoice"></i>
                    <span class="ive-card-title">Invoice Information</span>
                </div>
                <div class="ive-card-b">
                    <div class="ive-grid-2">

                        <div class="ive-field">
                            <label class="ive-lbl" for="company">Company <span class="req">*</span></label>
                            <select id="company" class="ive-sel" onchange="onCompanyChange()">
                                @foreach($companies as $co)
                                <option value="{{ $co->id }}"
                                    {{ $invoice->companyLocation->user_id == $co->id ? 'selected' : '' }}>
                                    {{ $co->company_name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="ive-ferr" id="company_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="ive-field">
                            <label class="ive-lbl" for="location">Location <span class="req">*</span></label>
                            <select id="location" class="ive-sel" disabled>
                                <option value="{{ $invoice->company_location_id }}">
                                    {{ $invoice->companyLocation->state }}{{ $invoice->companyLocation->city ? ' — '.$invoice->companyLocation->city : '' }}
                                </option>
                            </select>
                            <div class="ive-ferr" id="location_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="ive-field">
                            <label class="ive-lbl" for="crew_id">Crew</label>
                            <select id="crew_id" class="ive-sel" disabled>
                                @php $selectedCrew = $crews->firstWhere('id', $invoice->crew_id); @endphp
                                <option value="{{ $invoice->crew_id }}">
                                    {{ $selectedCrew ? $selectedCrew->name.($selectedCrew->has_trailer?' (Trailer)':' (No trailer)') : 'No crew' }}
                                </option>
                            </select>
                        </div>

                        <div class="ive-field">
                            <label class="ive-lbl" for="customer_email">Customer Email</label>
                            <input type="email" id="customer_email" class="ive-input"
                                   value="{{ $invoice->customer_email }}"
                                   onblur="validateField('customer_email','email','Invalid email format')">
                            <div class="ive-ferr" id="customer_email_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="ive-field">
                            <label class="ive-lbl" for="address">Address</label>
                            <input type="text" id="address" class="ive-input" value="{{ $invoice->address }}">
                        </div>

                        <div class="ive-field">
                            <label class="ive-lbl" for="bill_to">Bill To <span class="req">*</span></label>
                            <input type="text" id="bill_to" class="ive-input"
                                   value="{{ $invoice->bill_to }}"
                                   onblur="validateField('bill_to','required|min:3','Required — at least 3 characters')">
                            <div class="ive-ferr" id="bill_to_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="ive-field">
                            <label class="ive-lbl" for="invoice_date">Invoice Date</label>
                            <input type="date" id="invoice_date" class="ive-input" value="{{ $invoice->invoice_date }}">
                        </div>

                        <div class="ive-field">
                            <label class="ive-lbl" for="due_date">Due Date</label>
                            <input type="date" id="due_date" class="ive-input"
                                   value="{{ $invoice->due_date }}"
                                   onblur="validateDueDate()">
                            <div class="ive-ferr" id="due_date_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="ive-field full">
                            <label class="ive-lbl" for="status">Status</label>
                            <select id="status" class="ive-sel" style="max-width:220px">
                                <option value="draft" {{ $invoice->status=='draft'?'selected':'' }}>Draft</option>
                                <option value="sent"  {{ $invoice->status=='sent'?'selected':'' }}>Sent</option>
                                <option value="paid"  {{ $invoice->status=='paid'?'selected':'' }}>Paid</option>
                            </select>
                        </div>

                        <div class="ive-field full">
                            <label class="ive-lbl" for="notes">Notes <span class="ive-lbl hint">(optional — appears on invoice)</span></label>
                            <textarea id="notes" class="ive-textarea" rows="2">{{ $invoice->notes }}</textarea>
                        </div>

                        <div class="ive-field full">
                            <label class="ive-lbl" for="memo">Internal Memo <span class="ive-lbl hint">(optional — internal only)</span></label>
                            <textarea id="memo" class="ive-textarea" rows="2">{{ $invoice->memo }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Add Items ── --}}
            <div class="ive-card">
                <div class="ive-card-h">
                    <i class="fas fa-plus-circle"></i>
                    <span class="ive-card-title">Add Items</span>
                </div>
                <div class="ive-card-b">
                    <div class="ive-item-row">
                        <div class="ive-field">
                            <label class="ive-lbl" for="itemSelect">Select Item</label>
                            <select id="itemSelect" class="ive-sel">
                                <option value="">Select an item…</option>
                            </select>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:5px;flex-shrink:0">
                            <label class="ive-lbl">Qty</label>
                            <div class="ive-qty-wrap">
                                <button class="ive-qty-btn" type="button" onclick="document.getElementById('itemQuantity').stepDown()">−</button>
                                <input type="number" id="itemQuantity" min="1" value="1">
                                <button class="ive-qty-btn" type="button" onclick="document.getElementById('itemQuantity').stepUp()">+</button>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;justify-content:flex-end;flex-shrink:0">
                            <button class="ive-add-btn" type="button" onclick="addItem()">
                                <i class="fas fa-plus" style="font-size:10px"></i> Add
                            </button>
                        </div>
                    </div>
                    <div class="ive-hint-txt" style="margin-top:6px">Price comes from the selected location</div>

                    <button type="button" class="ive-custom-btn" onclick="toggleCustomItem()">
                        <i class="fas fa-plus-circle" style="font-size:10px"></i> Add Custom Item
                    </button>

                    <div class="ive-custom-box" id="customItemBox">
                        <div class="ive-custom-row">
                            <div class="ive-field">
                                <label class="ive-lbl">Name <span class="req">*</span></label>
                                <input type="text" id="customName" class="ive-input" placeholder="Item name">
                                <div class="ive-ferr" id="customName_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                            </div>
                            <div class="ive-field">
                                <label class="ive-lbl">Unit Price <span class="req">*</span></label>
                                <input type="number" id="customPrice" class="ive-input" placeholder="0.00" step="0.01" min="0.01">
                                <div class="ive-ferr" id="customPrice_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                            </div>
                            <div class="ive-field">
                                <label class="ive-lbl">Qty</label>
                                <input type="number" id="customQty" class="ive-input" value="1" min="1">
                            </div>
                            <div style="display:flex;flex-direction:column;justify-content:flex-end">
                                <button type="button" class="ive-add-btn" onclick="addCustomItem()">
                                    <i class="fas fa-check" style="font-size:10px"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Invoice Items table ── --}}
            <div class="ive-card">
                <div class="ive-card-h">
                    <i class="fas fa-list"></i>
                    <span class="ive-card-title">Invoice Items</span>
                </div>
                <div class="ive-card-b">
                    <div class="ive-tbl-wrap">
                        <table class="ive-items">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="c">Qty</th>
                                    <th class="r">Unit Price</th>
                                    <th class="r">Total</th>
                                    <th class="c" style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                {{-- Rendered by JS --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="ive-items-err" id="items_error">
                        <i class="fas fa-exclamation-circle" style="font-size:10px"></i>
                        You must add at least one item
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ RIGHT ══ --}}
        <div class="ive-right">

            <div class="ive-summary">
                <div class="ive-sum-h">
                    <i class="fas fa-calculator" style="font-size:12px;color:var(--ink3)"></i>
                    <span class="ive-sum-title">Summary</span>
                </div>
                <div class="ive-sum-b">
                    <div class="ive-sum-row">
                        <span class="ive-sum-key">Subtotal</span>
                        <span class="ive-sum-val" id="subtotal">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="ive-sum-row">
                        <span class="ive-sum-key">Tax</span>
                        <span class="ive-sum-val" id="taxDisplay">${{ number_format($invoice->tax, 2) }}</span>
                    </div>
                    <div class="ive-sum-total">
                        <span class="ive-sum-total-lbl">Total</span>
                        <span class="ive-sum-total-val" id="total">${{ number_format($invoice->total, 2) }}</span>
                    </div>
                    <button class="ive-btn blue" onclick="updateInvoice()" id="updateButtonSide">
                        <i class="fas fa-floppy-disk"></i> Update Invoice
                    </button>
                    <a href="{{ route('superadmin.invoices.show', $invoice) }}" class="ive-btn ghost">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>

            <div class="ive-quick-info">
                <div class="ive-sum-h">
                    <i class="fas fa-info-circle" style="font-size:12px;color:var(--ink3)"></i>
                    <span class="ive-sum-title">Quick Info</span>
                </div>
                <div class="ive-qi-row">
                    <span class="ive-qi-key">Customer</span>
                    <span class="ive-qi-val" id="quickBillTo">{{ $invoice->bill_to }}</span>
                </div>
                <div class="ive-qi-row">
                    <span class="ive-qi-key">Email</span>
                    <span class="ive-qi-val" id="quickEmail">{{ $invoice->customer_email }}</span>
                </div>
                <div class="ive-qi-row">
                    <span class="ive-qi-key">Due Date</span>
                    <span class="ive-qi-val" id="quickDueDate">{{ \Carbon\Carbon::parse($invoice->due_date)->format('m/d/Y') }}</span>
                </div>
                <div class="ive-qi-row">
                    <span class="ive-qi-key">Items</span>
                    <span class="ive-qi-val" id="quickItemsCount">{{ count($invoiceItems) }}</span>
                </div>
                <div class="ive-qi-row">
                    <span class="ive-qi-key">Status</span>
                    <div style="margin-top:3px">
                        @php
                            $sc = ['draft'=>['var(--bg)','var(--ink3)','var(--bd)'],'sent'=>['var(--blt)','var(--blue)','var(--bbd)'],'paid'=>['var(--glt)','var(--grn)','var(--gbd)']];
                            [$sb,$st,$sbd] = $sc[$invoice->status] ?? $sc['draft'];
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:800;padding:3px 9px;border-radius:6px;text-transform:uppercase;letter-spacing:.4px;background:{{ $sb }};color:{{ $st }};border:1px solid {{ $sbd }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

/* ── NORMALIZAR DATOS AL CARGAR: fuerza price y quantity a número ── */
let invoiceItems = (@json($invoiceItems ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP))
    .map(i => ({
        ...i,
        price:    parseFloat(i.price)    || 0,
        quantity: parseInt(i.quantity)   || 1,
    }));

let currentLocationId = {{ $invoice->company_location_id }};
let allItems = [];

function fmt(a) { return '$' + parseFloat(a || 0).toFixed(2); }

/* ── ALERT ── */
function showAlert(msg, type='err') {
    const a = document.getElementById('topAlert');
    document.getElementById('topAlertMsg').textContent = msg;
    a.className = 'ive-alert show ' + type;
    setTimeout(() => a.classList.remove('show'), 5000);
    a.scrollIntoView({ behavior:'smooth', block:'start' });
}

/* ── VALIDATION ── */
function validateField(id, rules, message) {
    const field = document.getElementById(id);
    if (!field) return true;
    /* Si el campo está disabled y tiene un valor, considerarlo válido */
    if (field.disabled && field.value) return true;

    const val = field.value.trim();
    let ok = true, msg = message;

    if (rules.includes('required')) ok = val !== '';
    if (ok && rules.includes('email')) {
        ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
        msg = 'Invalid email format';
    }
    if (ok && rules.includes('min:')) {
        const m = parseInt(rules.split('min:')[1]) || 0;
        ok = val.length >= m;
        msg = `Must be at least ${m} characters`;
    }

    if (!ok) { field.classList.add('is-invalid'); showFerr(id, msg); }
    else      { field.classList.remove('is-invalid'); clearFerr(id); }
    return ok;
}

function showFerr(id, msg) {
    const el = document.getElementById(id + '_error');
    if (!el) return;
    const sp = el.querySelector('span');
    if (sp) sp.textContent = msg; else el.textContent = msg;
    el.classList.add('show');
    document.getElementById(id)?.classList.add('is-invalid');
}
function clearFerr(id) {
    document.getElementById(id + '_error')?.classList.remove('show');
    document.getElementById(id)?.classList.remove('is-invalid');
}
function validateDueDate() {
    const inv = document.getElementById('invoice_date').value;
    const due = document.getElementById('due_date').value;
    if (inv && due && due < inv) { showFerr('due_date', 'Due date cannot be before invoice date'); return false; }
    clearFerr('due_date'); return true;
}
function validateForm() {
    const c1 = validateField('company',  'required', 'Please select a company');
    const c2 = validateField('location', 'required', 'Please select a location');
    const c3 = validateField('bill_to',  'required|min:3', 'Required — at least 3 characters');
    const em = document.getElementById('customer_email').value;
    const c4 = em ? validateField('customer_email', 'email', 'Invalid email format') : true;
    const c5 = validateDueDate();
    const c6 = invoiceItems.length > 0;

    document.getElementById('items_error').classList.toggle('show', !c6);
    return c1 && c2 && c3 && c4 && c5 && c6;
}

/* ── COMPANY / LOCATION ── */
function onCompanyChange() {
    const id  = document.getElementById('company').value;
    const sel = document.getElementById('location');
    if (!id) { sel.innerHTML = '<option value="">Select a company first</option>'; sel.disabled = true; return; }

    sel.innerHTML = '<option value="">Loading…</option>';
    sel.disabled = false;

    fetch(`/superadmin/companies/${id}/locations/ajax`)
        .then(r => r.json())
        .then(data => {
            sel.innerHTML = '<option value="">Select location</option>';
            const byState = {};
            data.forEach(loc => {
                if (!byState[loc.state]) byState[loc.state] = [];
                byState[loc.state].push(loc);
            });
            Object.keys(byState).sort().forEach(state => {
                const og = document.createElement('optgroup');
                og.label = `── ${state} ──`;
                byState[state].forEach(loc => {
                    const o = document.createElement('option');
                    o.value = loc.id;
                    o.textContent = loc.city ? `${state}  /  ${loc.city}` : `${state}  •  Base Price`;
                    og.appendChild(o);
                });
                sel.appendChild(og);
            });
            if (currentLocationId) {
                const o = Array.from(sel.options).find(op => op.value == currentLocationId);
                if (o) o.selected = true;
            }
        })
        .catch(() => { sel.innerHTML = '<option value="">Error loading</option>'; });
}

/* ── LOAD ITEMS ── */
async function loadItemsByLocation(locationId) {
    const sel = document.getElementById('itemSelect');
    if (!locationId) { sel.innerHTML = '<option value="">Select a location first</option>'; return; }
    sel.innerHTML = '<option value="">Loading items…</option>';
    try {
        const r = await fetch(`/superadmin/invoices/location/${locationId}/items`);
        const items = await r.json();
        allItems = items;
        updateItemSelect(items);
    } catch (e) {
        console.error('Error loading items:', e);
        sel.innerHTML = '<option value="">Error loading items</option>';
    }
}

function updateItemSelect(items) {
    const sel = document.getElementById('itemSelect');
    sel.innerHTML = '<option value="">Select an item…</option>';
    if (!items.length) { sel.innerHTML += '<option disabled>No items available</option>'; return; }
    const grouped = {};
    items.forEach(i => {
        const c = i.category || 'Uncategorized';
        if (!grouped[c]) grouped[c] = [];
        grouped[c].push(i);
    });
    Object.keys(grouped).sort().forEach(cat => {
        const og = document.createElement('optgroup');
        og.label = cat;
        grouped[cat].forEach(i => {
            const o = document.createElement('option');
            o.value = i.id;
            o.textContent = `${i.name} — ${fmt(i.price)}`;
            o.dataset.price = i.price;
            o.dataset.name  = i.name;
            og.appendChild(o);
        });
        sel.appendChild(og);
    });
}

/* ── CUSTOM ITEM ── */
function toggleCustomItem() {
    const b = document.getElementById('customItemBox');
    b.classList.toggle('open');
    if (b.classList.contains('open')) document.getElementById('customName').focus();
}
function addCustomItem() {
    const name  = document.getElementById('customName').value.trim();
    const price = parseFloat(document.getElementById('customPrice').value) || 0;
    const qty   = parseInt(document.getElementById('customQty').value) || 1;
    let ok = true;
    if (!name)    { showFerr('customName',  'Item name is required'); ok = false; } else clearFerr('customName');
    if (price <= 0) { showFerr('customPrice', 'Price must be > 0');   ok = false; } else clearFerr('customPrice');
    if (!ok) return;
    invoiceItems.push({ id: null, name, price, quantity: qty, note: '' });
    document.getElementById('customName').value  = '';
    document.getElementById('customPrice').value = '';
    document.getElementById('customQty').value   = 1;
    document.getElementById('customItemBox').classList.remove('open');
    renderInvoiceItems();
}

/* ── ADD ITEM FROM SELECT ── */
function addItem() {
    const sel = document.getElementById('itemSelect');
    const qty = parseInt(document.getElementById('itemQuantity').value) || 1;
    if (!sel.value) { showAlert('Please select an item first'); return; }
    const opt   = sel.options[sel.selectedIndex];
    const price = parseFloat(opt.dataset.price) || 0;
    const name  = opt.dataset.name;
    const idx   = invoiceItems.findIndex(i => Number(i.id) === Number(sel.value));
    if (idx > -1) {
        invoiceItems[idx].quantity += qty;
    } else {
        invoiceItems.push({ id: Number(sel.value), name, price, quantity: qty, note: '' });
    }
    sel.value = '';
    document.getElementById('itemQuantity').value = 1;
    renderInvoiceItems();
}

/* ── MANAGE ITEMS ── */
function increaseQuantity(i) { invoiceItems[i].quantity++; renderInvoiceItems(); }
function decreaseQuantity(i) { if (invoiceItems[i].quantity > 1) { invoiceItems[i].quantity--; renderInvoiceItems(); } }
function updateQuantity(i, v) {
    const q = parseInt(v) || 1;
    invoiceItems[i].quantity = q < 1 ? 1 : q;
    renderInvoiceItems();
}
function updateItemNote(i, v) { invoiceItems[i].note = v; }
function removeItem(i) {
    if (confirm('Remove this item?')) { invoiceItems.splice(i, 1); renderInvoiceItems(); }
}
function updateItemPrice(i, v) {
    let p = parseFloat(v);
    if (isNaN(p) || p < 0) p = 0;
    invoiceItems[i].price = p;
    renderInvoiceItems();
}

/* ── RENDER ── */
function renderInvoiceItems() {
    const tbody = document.getElementById('invoiceItems');
    if (!invoiceItems.length) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="ive-empty-row"><i class="fas fa-box-open"></i>No items added</div></td></tr>`;
        updateSummary();
        return;
    }

    let html = '';
    invoiceItems.forEach((item, i) => {
        /* ← FIX: siempre parsear a número antes de operar */
        const price = parseFloat(item.price)    || 0;
        const qty   = parseInt(item.quantity)   || 1;
        const tot   = price * qty;

        html += `
        <tr>
            <td>
                <div class="ive-item-name">${item.name}</div>
                <textarea class="ive-note-ta" placeholder="Add note…"
                    oninput="updateItemNote(${i}, this.value)" rows="1">${item.note || ''}</textarea>
            </td>
            <td style="text-align:center">
                <div class="ive-tbl-qty">
                    <button type="button" class="ive-tbl-qbtn" onclick="decreaseQuantity(${i})">−</button>
                    <input type="text" value="${qty}" onchange="updateQuantity(${i}, this.value)">
                    <button type="button" class="ive-tbl-qbtn" onclick="increaseQuantity(${i})">+</button>
                </div>
            </td>
            <td style="text-align:right">
                <input type="number" class="ive-price-input"
                       value="${price.toFixed(2)}" step="0.01" min="0"
                       onchange="updateItemPrice(${i}, this.value)">
            </td>
            <td class="ive-item-total">${fmt(tot)}</td>
            <td style="text-align:center">
                <button type="button" class="ive-rm-btn" onclick="removeItem(${i})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;
    updateSummary();
}

/* ── SUMMARY ── */
function updateSummary() {
    const sub = invoiceItems.reduce((s, i) => s + (parseFloat(i.price) || 0) * (parseInt(i.quantity) || 1), 0);
    const tax = parseFloat("{{ $invoice->tax }}") || 0;
    document.getElementById('subtotal').textContent      = fmt(sub);
    document.getElementById('taxDisplay').textContent    = fmt(tax);
    document.getElementById('total').textContent         = fmt(sub + tax);
    document.getElementById('quickItemsCount').textContent = invoiceItems.length;
    document.getElementById('quickBillTo').textContent   = document.getElementById('bill_to').value || '—';
    document.getElementById('quickEmail').textContent    = document.getElementById('customer_email').value || '—';
    const d = document.getElementById('due_date').value;
    if (d) document.getElementById('quickDueDate').textContent = new Date(d + 'T00:00:00').toLocaleDateString('en-US');
}

/* ── UPDATE ── */
async function updateInvoice() {
    if (!validateForm()) { showAlert('Please correct the errors before updating'); return; }
    if (!confirm('Update this invoice?')) return;

    const payload = {
        company_location_id: document.getElementById('location').value,
        crew_id:             document.getElementById('crew_id').value,
        invoice_date:        document.getElementById('invoice_date').value,
        due_date:            document.getElementById('due_date').value,
        customer_email:      document.getElementById('customer_email').value,
        bill_to:             document.getElementById('bill_to').value,
        address:             document.getElementById('address').value,
        status:              document.getElementById('status').value,
        notes:               document.getElementById('notes').value,
        memo:                document.getElementById('memo').value,
        items: invoiceItems.map(i => ({
            id:       i.id,
            name:     i.name,
            price:    parseFloat(i.price)  || 0,
            quantity: parseInt(i.quantity) || 1,
            note:     i.note ?? ''
        }))
    };

    document.querySelectorAll('#updateButton, #updateButtonSide').forEach(b => {
        b.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i> Updating…';
        b.disabled  = true;
    });

    try {
        const r   = await fetch("{{ route('superadmin.invoices.update', $invoice) }}", {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const res = await r.json();

        document.querySelectorAll('#updateButton, #updateButtonSide').forEach(b => {
            b.innerHTML = '<i class="fas fa-floppy-disk" style="font-size:10px"></i> Update Invoice';
            b.disabled  = false;
        });

        if (res.success) {
            showAlert('Invoice updated successfully', 'ok');
            setTimeout(() => window.location.href = `/superadmin/invoices/${res.invoice_id}`, 1500);
        } else {
            showAlert(res.message || 'Error updating invoice');
        }
    } catch (e) {
        console.error(e);
        document.querySelectorAll('#updateButton, #updateButtonSide').forEach(b => {
            b.innerHTML = '<i class="fas fa-floppy-disk" style="font-size:10px"></i> Update Invoice';
            b.disabled  = false;
        });
        showAlert('Server error');
    }
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('company').addEventListener('change', () =>
        validateField('company', 'required', 'Please select a company'));
    document.getElementById('location').addEventListener('change', function () {
        validateField('location', 'required', 'Please select a location');
        loadItemsByLocation(this.value);
    });
    document.getElementById('bill_to').addEventListener('input', () =>
        validateField('bill_to', 'required|min:3', 'Required — at least 3 characters'));
    document.getElementById('customer_email').addEventListener('input', () =>
        validateField('customer_email', 'email', 'Invalid email format'));
    document.getElementById('due_date').addEventListener('input', validateDueDate);

    if (currentLocationId) {
        try { await loadItemsByLocation(currentLocationId); }
        catch (e) { console.error('Error loading items:', e); }
    }

    renderInvoiceItems();
    validateForm();
});
</script>

@endsection