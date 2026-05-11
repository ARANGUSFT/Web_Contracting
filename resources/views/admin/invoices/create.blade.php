@extends('admin.layouts.superadmin')
@section('title', 'Create Invoice')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; }
.inv-c { font-family: 'Montserrat', sans-serif; padding: 28px 32px; }

:root {
    --ink:  #0f1117; --ink2: #3c4353; --ink3: #8c95a6;
    --bg:   #f4f5f8; --surf: #ffffff;
    --bd:   #e4e7ed; --bd2:  #eef0f4;
    --blue: #1855e0; --blt:  #eef2ff; --bbd:  #c7d4fb;
    --grn:  #0d9e6a; --glt:  #edfaf4; --gbd:  #9fe6c8;
    --red:  #d92626; --rlt:  #fff0f0; --rbd:  #fbcfcf;
    --amb:  #d97706; --alt:  #fffbeb; --abd:  #fde68a;
    --r: 8px; --rlg: 13px; --rxl: 18px;
}

.inv-hero {
    position: relative; border-radius: var(--rxl);
    padding: 28px 40px; margin-bottom: 22px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; background: var(--ink); overflow: hidden;
}
.inv-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
    background-size: 48px 48px;
}
.inv-hero::after {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: linear-gradient(180deg,#4f80ff,var(--blue) 60%,transparent);
    border-radius: 0 2px 2px 0;
}
.inv-hero-glow { position:absolute; right:-60px; top:-60px; width:540px; height:280px; background: radial-gradient(ellipse,rgba(24,85,224,.35) 0%,transparent 70%); pointer-events:none; }
.inv-hero-l { position:relative; display:flex; align-items:center; gap:16px; }
.inv-hero-icon { width:48px; height:48px; border-radius:13px; flex-shrink:0; background:rgba(24,85,224,.2); border:1px solid rgba(24,85,224,.35); display:flex; align-items:center; justify-content:center; font-size:18px; color:#8aadff; }
.inv-hero-title { font-size:21px; font-weight:800; color:#fff; letter-spacing:-.5px; line-height:1; }
.inv-hero-sub   { font-size:12px; font-weight:600; color:rgba(255,255,255,.38); margin-top:4px; }
.inv-hero-sub a { color:rgba(255,255,255,.4); text-decoration:none; }
.inv-hero-sub a:hover { color:rgba(255,255,255,.65); }
.inv-hero-sub .sep { margin: 0 5px; }
.inv-save-btn { position:relative; display:inline-flex; align-items:center; gap:7px; padding:10px 22px; border-radius:var(--rlg); background:var(--blue); color:#fff; font-size:13px; font-weight:700; font-family:'Montserrat',sans-serif; border:none; cursor:pointer; transition:background .13s; white-space:nowrap; box-shadow:0 2px 10px rgba(24,85,224,.4); }
.inv-save-btn:hover:not(:disabled) { background:#1344c2; }
.inv-save-btn:disabled { opacity:.45; cursor:not-allowed; }

.inv-alert { display:none; padding:11px 16px; border-radius:var(--rlg); margin-bottom:14px; background:var(--rlt); border:1px solid var(--rbd); font-size:13px; font-weight:600; color:var(--red); align-items:center; gap:8px; animation:fd .2s ease; }
.inv-alert.show { display:flex; }
@keyframes fd { from{opacity:0;transform:translateY(-4px)} to{opacity:1} }

.inv-body  { display:grid; grid-template-columns:1fr 300px; gap:18px; align-items:start; }
.inv-left  { display:flex; flex-direction:column; gap:16px; min-width:0; }
.inv-right { display:flex; flex-direction:column; gap:14px; position:sticky; top:90px; min-width:0; overflow:hidden; }

.inv-card   { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.inv-card-h { display:flex; align-items:center; gap:8px; padding:13px 20px; border-bottom:1px solid var(--bd2); background:linear-gradient(to right,var(--surf),#fafbfd); }
.inv-card-h i { font-size:13px; color:var(--blue); }
.inv-card-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.inv-card-b { padding:20px; }

.inv-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.inv-field  { display:flex; flex-direction:column; gap:5px; min-width:0; }
.inv-field.full { grid-column:1/-1; }
.inv-lbl { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.7px; }
.inv-lbl .req  { color:var(--red); margin-left:2px; }
.inv-lbl .hint { color:var(--ink3); font-weight:500; text-transform:none; letter-spacing:0; font-size:9.5px; margin-left:4px; }
.inv-input, .inv-sel, .inv-textarea { padding:9px 12px; border:1.5px solid var(--bd); border-radius:var(--r); font-size:13px; font-weight:500; font-family:'Montserrat',sans-serif; color:var(--ink); background:var(--surf); outline:none; width:100%; transition:border-color .15s, box-shadow .15s; }
.inv-input:focus, .inv-sel:focus, .inv-textarea:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(24,85,224,.08); }
.inv-input.is-invalid, .inv-sel.is-invalid { border-color:var(--red); background:var(--rlt); }
.inv-sel { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238c95a6' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 11px center; padding-right:32px; }
.inv-sel:disabled { background-color:var(--bg); color:var(--ink3); cursor:not-allowed; }
.inv-textarea { resize:vertical; min-height:72px; }
.inv-ferr { font-size:11px; font-weight:600; color:var(--red); display:none; align-items:center; gap:4px; }
.inv-ferr.show { display:flex; }
.inv-hint-txt { font-size:11px; font-weight:500; color:var(--ink3); }
.inv-locked-badge { display:inline-flex; align-items:center; gap:4px; font-size:10px; font-weight:700; color:var(--grn); background:var(--glt); border:1px solid var(--gbd); padding:2px 8px; border-radius:9999px; margin-top:3px; }

.inv-note-ta { width:100%; border:none; outline:none; background:transparent; font-size:11.5px; font-weight:500; font-family:'Montserrat',sans-serif; color:var(--ink3); resize:none; overflow:hidden; min-height:22px; padding:3px 0; }
.inv-note-ta::placeholder { color:var(--bd); }
.inv-note-ta:focus { color:var(--ink); }

.inv-item-sel-row { display:flex; align-items:flex-end; gap:10px; }
.inv-item-sel-row .inv-field { flex:1; min-width:0; }
.inv-qty-wrap { display:flex; align-items:stretch; width:110px; flex-shrink:0; }
.inv-qty-wrap input { flex:1; padding:9px 4px; text-align:center; border:1.5px solid var(--bd); border-left:none; border-right:none; border-radius:0; font-size:13px; font-weight:600; font-family:'Montserrat',sans-serif; color:var(--ink); background:var(--surf); outline:none; min-width:0; }
.inv-qty-btn { padding:0 10px; border:1.5px solid var(--bd); border-radius:var(--r); background:var(--bg); color:var(--ink3); font-size:13px; font-weight:700; cursor:pointer; transition:all .13s; line-height:1; flex-shrink:0; }
.inv-qty-btn:first-child { border-radius:var(--r) 0 0 var(--r); }
.inv-qty-btn:last-child  { border-radius:0 var(--r) var(--r) 0; }
.inv-qty-btn:hover { background:var(--blt); color:var(--blue); border-color:var(--bbd); }
.inv-add-btn { display:inline-flex; align-items:center; gap:5px; padding:9px 16px; border-radius:var(--r); background:var(--blue); color:#fff; font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif; border:none; cursor:pointer; transition:background .13s; white-space:nowrap; flex-shrink:0; }
.inv-add-btn:hover { background:#1344c2; }
.inv-custom-btn { display:inline-flex; align-items:center; gap:5px; padding:7px 13px; border-radius:var(--r); margin-top:10px; background:none; border:1px solid var(--bd); color:var(--ink2); font-size:12px; font-weight:700; font-family:'Montserrat',sans-serif; cursor:pointer; transition:all .13s; }
.inv-custom-btn:hover { background:var(--bg); border-color:var(--bbd); color:var(--blue); }
.inv-custom-box { display:none; margin-top:12px; background:var(--bg); border:1px solid var(--bd); border-radius:var(--rlg); padding:14px; }
.inv-custom-box.open { display:block; }
.inv-custom-top    { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px; }
.inv-custom-bottom { display:flex; align-items:flex-end; gap:10px; }
.inv-custom-bottom .inv-field { width:100px; flex-shrink:0; }

.inv-tbl-wrap { overflow-x:auto; margin-top:16px; scrollbar-width:thin; scrollbar-color:#cdd0d8 var(--bg); }
.inv-tbl-wrap::-webkit-scrollbar { height:4px; }
.inv-tbl-wrap::-webkit-scrollbar-thumb { background:#cdd0d8; border-radius:9999px; }
table.inv-items { width:100%; border-collapse:collapse; font-family:'Montserrat',sans-serif; }
table.inv-items thead tr { background:#fafbfd; border-bottom:1px solid var(--bd); }
table.inv-items th { padding:9px 14px; text-align:left; font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.7px; white-space:nowrap; }
table.inv-items th.r { text-align:right; }
table.inv-items th.c { text-align:center; }
table.inv-items td { padding:10px 14px; border-bottom:1px solid var(--bd2); vertical-align:middle; }
table.inv-items tbody tr:last-child td { border-bottom:none; }
table.inv-items tbody tr:hover td { background:#fafbfd; }
.inv-item-name      { font-size:13px; font-weight:700; color:var(--ink); }
.inv-item-price-sub { font-size:11px; font-weight:500; color:var(--ink3); margin-top:1px; }
.inv-item-total     { font-size:13px; font-weight:800; color:var(--ink); text-align:right; }
.inv-empty-row      { padding:40px 14px; text-align:center; color:var(--ink3); font-size:13px; font-weight:500; }
.inv-empty-row i    { display:block; font-size:24px; opacity:.25; margin-bottom:8px; }
.inv-tbl-qty { display:flex; align-items:center; width:100px; margin:0 auto; }
.inv-tbl-qty input { flex:1; text-align:center; border:1.5px solid var(--bd); border-left:none; border-right:none; padding:5px 4px; min-width:0; font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif; color:var(--ink); background:var(--surf); outline:none; }
.inv-tbl-qbtn { width:26px; height:26px; border:1.5px solid var(--bd); background:var(--bg); color:var(--ink3); font-size:12px; font-weight:800; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .13s; flex-shrink:0; }
.inv-tbl-qbtn:first-child { border-radius:var(--r) 0 0 var(--r); }
.inv-tbl-qbtn:last-child  { border-radius:0 var(--r) var(--r) 0; }
.inv-tbl-qbtn:hover { background:var(--blt); color:var(--blue); border-color:var(--bbd); }
.inv-price-input { width:95px; padding:5px 8px; text-align:right; border:1.5px solid var(--bd); border-radius:var(--r); font-size:13px; font-weight:700; font-family:'Montserrat',sans-serif; color:var(--ink); background:var(--surf); outline:none; display:inline-block; transition:border-color .15s; }
.inv-price-input:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(24,85,224,.08); }
.inv-rm-btn { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:11px; border:1px solid transparent; background:none; color:var(--ink3); cursor:pointer; transition:all .13s; margin:0 auto; }
.inv-rm-btn:hover { background:var(--rlt); border-color:var(--rbd); color:var(--red); }
.inv-items-err { font-size:11.5px; font-weight:600; color:var(--red); display:none; align-items:center; gap:5px; margin-top:8px; }
.inv-items-err.show { display:flex; }

.inv-dropzone { border:2px dashed var(--bd); border-radius:var(--rlg); padding:22px; text-align:center; background:var(--bg); transition:border-color .15s; cursor:pointer; margin-bottom:12px; }
.inv-dropzone:hover, .inv-dropzone.over { border-color:var(--blue); background:var(--blt); }
.inv-dropzone i { font-size:22px; color:var(--ink3); display:block; margin-bottom:8px; }
.inv-dropzone p { font-size:12.5px; font-weight:500; color:var(--ink3); margin:0 0 6px; }
.inv-dropzone small { font-size:11px; color:var(--ink3); }
.inv-dz-label { display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:var(--r); border:1px solid var(--bd); background:var(--surf); color:var(--ink2); font-size:12px; font-weight:700; font-family:'Montserrat',sans-serif; cursor:pointer; transition:all .13s; margin-top:8px; }
.inv-dz-label:hover { background:var(--bg); }
.inv-attach-item { display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border:1px solid var(--bd2); border-radius:var(--r); margin-bottom:6px; background:var(--bg); }
.inv-attach-l { display:flex; align-items:center; gap:9px; min-width:0; }
.inv-attach-icon { width:32px; height:32px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:13px; background:var(--surf); border:1px solid var(--bd); color:var(--ink3); }
.inv-attach-name { font-size:12.5px; font-weight:700; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.inv-attach-size { font-size:11px; font-weight:500; color:var(--ink3); }
.inv-attach-rm { width:24px; height:24px; border-radius:6px; border:none; flex-shrink:0; background:none; color:var(--ink3); cursor:pointer; font-size:10px; display:flex; align-items:center; justify-content:center; transition:all .13s; }
.inv-attach-rm:hover { background:var(--rlt); color:var(--red); }
.inv-no-attach { font-size:12.5px; font-weight:500; color:var(--ink3); text-align:center; padding:8px 0; }

.inv-summary  { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.inv-sum-h    { display:flex; align-items:center; gap:8px; padding:13px 16px; border-bottom:1px solid var(--bd2); background:linear-gradient(to right,var(--surf),#fafbfd); }
.inv-sum-title { font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase; letter-spacing:.5px; }
.inv-sum-b    { padding:14px; overflow:hidden; }
.inv-sum-row  { display:flex; align-items:center; justify-content:space-between; padding:8px 12px; border:1px solid var(--bd2); border-radius:var(--r); background:var(--bg); margin-bottom:6px; }
.inv-sum-row:last-child { margin-bottom:0; }
.inv-sum-key  { font-size:11.5px; font-weight:600; color:var(--ink3); }
.inv-sum-val  { font-size:13px; font-weight:800; color:var(--ink); }
.inv-sum-total { display:flex; align-items:center; justify-content:space-between; padding:11px 13px; background:var(--blt); border:1px solid var(--bbd); border-radius:var(--rlg); margin:10px 0; flex-wrap:wrap; gap:4px; }
.inv-sum-total-lbl { font-size:13px; font-weight:700; color:var(--blue); }
.inv-sum-total-val { font-size:18px; font-weight:800; color:var(--blue); letter-spacing:-.5px; word-break:break-all; }
.inv-quick-info { background:var(--surf); border:1px solid var(--bd); border-radius:var(--rlg); overflow:hidden; }
.inv-qi-row { display:flex; flex-direction:column; gap:1px; padding:9px 14px; border-bottom:1px solid var(--bd2); }
.inv-qi-row:last-child { border-bottom:none; }
.inv-qi-key { font-size:10px; font-weight:800; color:var(--ink3); text-transform:uppercase; letter-spacing:.5px; }
.inv-qi-val { font-size:13px; font-weight:700; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.inv-status-badge { display:inline-flex; align-items:center; gap:4px; font-size:10.5px; font-weight:800; padding:3px 9px; border-radius:6px; text-transform:uppercase; letter-spacing:.4px; background:var(--bg); color:var(--ink3); border:1px solid var(--bd); }

.inv-btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:var(--r); width:100%; justify-content:center; max-width:100%; overflow:hidden; font-size:12.5px; font-weight:700; font-family:'Montserrat',sans-serif; border:1px solid transparent; cursor:pointer; transition:all .13s; text-decoration:none; white-space:nowrap; margin-bottom:8px; }
.inv-btn:last-child { margin-bottom:0; }
.inv-btn i { font-size:10px; flex-shrink:0; }
.inv-btn.blue  { background:var(--blue); color:#fff; }
.inv-btn.blue:hover { background:#1344c2; color:#fff; }
.inv-btn.blue:disabled { opacity:.4; cursor:not-allowed; }
.inv-btn.ghost { background:var(--surf); border-color:var(--bd); color:var(--ink2); }
.inv-btn.ghost:hover { background:var(--bg); }

::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-track { background:var(--bg); }
::-webkit-scrollbar-thumb { background:#cdd0d8; border-radius:9999px; }

@media (max-width:1100px) { .inv-body { grid-template-columns:1fr; } .inv-right { position:static; } }
@media (max-width:768px)  {
    .inv-c { padding:16px; }
    .inv-hero { padding:22px 20px; flex-direction:column; align-items:flex-start; }
    .inv-grid-2 { grid-template-columns:1fr; }
    .inv-custom-top { grid-template-columns:1fr; }
}
</style>

<div class="inv-c">

    <div class="inv-hero">
        <div class="inv-hero-glow"></div>
        <div class="inv-hero-l">
            <div class="inv-hero-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <div>
                <div class="inv-hero-title">Create Invoice</div>
                <div class="inv-hero-sub">
                    <a href="{{ route('superadmin.invoices.index') }}">Invoices</a>
                    <span class="sep">/</span> New
                </div>
            </div>
        </div>
        <button class="inv-save-btn" onclick="saveInvoice()" id="saveButton" disabled>
            <i class="fas fa-floppy-disk" style="font-size:12px"></i> Save Invoice
        </button>
    </div>

    <div class="inv-alert" id="topAlert">
        <i class="fas fa-exclamation-circle"></i>
        <span id="topAlertMsg"></span>
    </div>

    <div class="inv-body">
        <div class="inv-left">

            <div class="inv-card">
                <div class="inv-card-h">
                    <i class="fas fa-file-invoice"></i>
                    <span class="inv-card-title">Invoice Information</span>
                </div>
                <div class="inv-card-b">
                    <div class="inv-grid-2">

                        {{-- Link to Work Order --}}
                        <div class="inv-field">
                            <label class="inv-lbl" for="invoiceable_type">
                                Link to Work Order <span class="hint">(optional)</span>
                            </label>
                            <select id="invoiceable_type" class="inv-sel" onchange="onInvoiceableTypeChange()">
                                <option value="">— None —</option>
                                <option value="job">Job Request</option>
                                <option value="emergency">Emergency</option>
                                <option value="repair">Repair Ticket</option>
                            </select>
                        </div>

                        <div class="inv-field" id="invoiceable_id_wrap" style="display:none">
                            <label class="inv-lbl" for="invoiceable_id">Select Record</label>
                            <select id="invoiceable_id" class="inv-sel">
                                <option value="">Select type first…</option>
                            </select>
                        </div>

                        {{-- Company --}}
                        <div class="inv-field">
                            <label class="inv-lbl" for="company">Company <span class="req">*</span></label>
                            <select id="company" class="inv-sel">
                                <option value="">Select company…</option>
                                @foreach($companies as $co)
                                <option value="{{ $co->id }}"
                                        data-name="{{ $co->company_name }}"
                                        data-email="{{ $co->email }}"
                                        data-address="{{ $co->address ?? '' }}">
                                    {{ $co->company_name }}
                                </option>
                                @endforeach
                            </select>
                            <div id="company-locked-badge" style="display:none">
                                <span class="inv-locked-badge"><i class="fas fa-lock" style="font-size:9px"></i> Locked to work order</span>
                            </div>
                            <div class="inv-ferr" id="company_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        {{-- Location --}}
                        <div class="inv-field">
                            <label class="inv-lbl" for="location">Location <span class="req">*</span></label>
                            <select id="location" class="inv-sel" disabled>
                                <option value="">First select a company</option>
                            </select>
                            <div id="location-locked-badge" style="display:none">
                                <span class="inv-locked-badge"><i class="fas fa-lock" style="font-size:9px"></i> Locked to work order</span>
                            </div>
                            <div class="inv-ferr" id="location_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="inv-field">
                            <label class="inv-lbl" for="crew_id">Crew</label>
                            <select id="crew_id" class="inv-sel">
                                <option value="">Select crew…</option>
                                @foreach($crews as $crew)
                                <option value="{{ $crew->id }}">{{ $crew->name }} ({{ $crew->has_trailer ? 'Trailer' : 'No trailer' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="inv-field">
                            <label class="inv-lbl" for="invoice_number">Invoice Number</label>
                            <input type="text" id="invoice_number" class="inv-input" value="{{ $nextInvoiceNumber }}">
                            <div class="inv-hint-txt">You can edit this number</div>
                        </div>

                        <div class="inv-field">
                            <label class="inv-lbl" for="invoice_date">Invoice Date</label>
                            <input type="date" id="invoice_date" class="inv-input" value="{{ now()->toDateString() }}">
                        </div>

                        <div class="inv-field">
                            <label class="inv-lbl" for="due_date">Due Date</label>
                            <input type="date" id="due_date" class="inv-input" value="{{ now()->addDays(15)->toDateString() }}" onblur="validateDueDate()">
                            <div class="inv-ferr" id="due_date_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="inv-field">
                            <label class="inv-lbl" for="customer_email">Customer Email</label>
                            <input type="email" id="customer_email" class="inv-input" placeholder="customer@example.com" onblur="validateField('customer_email','email','Invalid email format')">
                            <div class="inv-ferr" id="customer_email_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="inv-field">
                            <label class="inv-lbl" for="address">Address</label>
                            <input type="text" id="address" class="inv-input" placeholder="Address">
                        </div>

                        <div class="inv-field full">
                            <label class="inv-lbl" for="bill_to">Bill To <span class="req">*</span></label>
                            <input type="text" id="bill_to" class="inv-input" placeholder="Customer name or company" onblur="validateField('bill_to','required|min:3','Required — at least 3 characters')">
                            <div class="inv-ferr" id="bill_to_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                        </div>

                        <div class="inv-field full">
                            <label class="inv-lbl" for="notes">Notes <span class="hint">(optional — appears on invoice)</span></label>
                            <textarea id="notes" class="inv-textarea" rows="2" placeholder="Notes that will appear on the invoice"></textarea>
                        </div>

                        <div class="inv-field full">
                            <label class="inv-lbl" for="memo">Internal Memo <span class="hint">(optional — internal only)</span></label>
                            <textarea id="memo" class="inv-textarea" rows="2" placeholder="Internal notes for reference"></textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="inv-card">
                <div class="inv-card-h"><i class="fas fa-list"></i><span class="inv-card-title">Invoice Items</span></div>
                <div class="inv-card-b">
                    <div class="inv-item-sel-row">
                        <div class="inv-field">
                            <label class="inv-lbl" for="itemSelect">Select Item</label>
                            <select id="itemSelect" class="inv-sel"><option value="">Select an item…</option></select>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:5px;flex-shrink:0">
                            <label class="inv-lbl">Qty</label>
                            <div class="inv-qty-wrap">
                                <button class="inv-qty-btn" type="button" onclick="document.getElementById('itemQuantity').stepDown()">−</button>
                                <input type="number" id="itemQuantity" min="1" value="1">
                                <button class="inv-qty-btn" type="button" onclick="document.getElementById('itemQuantity').stepUp()">+</button>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;justify-content:flex-end;flex-shrink:0">
                            <button class="inv-add-btn" type="button" onclick="addItemManually()"><i class="fas fa-plus" style="font-size:10px"></i> Add</button>
                        </div>
                    </div>
                    <div class="inv-hint-txt" style="margin-top:6px">Select a location first to load available items</div>
                    <button type="button" class="inv-custom-btn" onclick="toggleCustomItem()"><i class="fas fa-plus-circle" style="font-size:10px"></i> Add Custom Item</button>
                    <div class="inv-custom-box" id="customItemBox">
                        <div class="inv-custom-top">
                            <div class="inv-field">
                                <label class="inv-lbl">Name <span class="req">*</span></label>
                                <input type="text" id="customName" class="inv-input" placeholder="Item name">
                                <div class="inv-ferr" id="customName_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                            </div>
                            <div class="inv-field">
                                <label class="inv-lbl">Unit Price <span class="req">*</span></label>
                                <input type="number" id="customPrice" class="inv-input" placeholder="0.00" step="0.01" min="0.01">
                                <div class="inv-ferr" id="customPrice_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> <span></span></div>
                            </div>
                        </div>
                        <div class="inv-custom-bottom">
                            <div class="inv-field"><label class="inv-lbl">Qty</label><input type="number" id="customQty" class="inv-input" value="1" min="1"></div>
                            <button type="button" class="inv-add-btn" style="flex:1" onclick="addCustomItem()"><i class="fas fa-check" style="font-size:10px"></i> Add Item</button>
                        </div>
                    </div>
                    <div class="inv-tbl-wrap">
                        <table class="inv-items">
                            <thead><tr><th>Item</th><th class="c">Qty</th><th class="r">Unit Price</th><th class="r">Total</th><th class="c" style="width:40px"></th></tr></thead>
                            <tbody id="invoiceItems">
                                <tr><td colspan="5"><div class="inv-empty-row"><i class="fas fa-box-open"></i>No items added yet</div></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="inv-items-err" id="items_error"><i class="fas fa-exclamation-circle" style="font-size:10px"></i> You must add at least one item</div>
                </div>
            </div>

            <div class="inv-card">
                <div class="inv-card-h"><i class="fas fa-paperclip"></i><span class="inv-card-title">Attachments</span></div>
                <div class="inv-card-b">
                    <div class="inv-dropzone" id="dropZone" onclick="document.getElementById('attachments').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag files here or click to select</p>
                        <small>PDF, images, Word, Excel · Max 10MB per file</small>
                        <label class="inv-dz-label" onclick="event.stopPropagation(); document.getElementById('attachments').click()"><i class="fas fa-folder-open" style="font-size:11px"></i> Select Files</label>
                        <input type="file" id="attachments" style="display:none" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                    </div>
                    <div id="attachmentsList"><div class="inv-no-attach">No attachments added</div></div>
                </div>
            </div>

        </div>

        <div class="inv-right">
            <div class="inv-summary">
                <div class="inv-sum-h"><i class="fas fa-calculator" style="font-size:12px;color:var(--ink3)"></i><span class="inv-sum-title">Summary</span></div>
                <div class="inv-sum-b">
                    <div class="inv-sum-row"><span class="inv-sum-key">Subtotal</span><span class="inv-sum-val" id="subtotal">$0.00</span></div>
                    <div class="inv-sum-total"><span class="inv-sum-total-lbl">Total</span><span class="inv-sum-total-val" id="total">$0.00</span></div>
                    <button class="inv-btn blue" onclick="saveInvoice()" id="saveButtonSide" disabled><i class="fas fa-floppy-disk"></i> Save Invoice</button>
                    <button class="inv-btn ghost" onclick="window.history.back()"><i class="fas fa-times"></i> Cancel</button>
                </div>
            </div>
            <div class="inv-quick-info">
                <div class="inv-sum-h"><i class="fas fa-info-circle" style="font-size:12px;color:var(--ink3)"></i><span class="inv-sum-title">Quick Info</span></div>
                <div class="inv-qi-row"><span class="inv-qi-key">Customer</span><span class="inv-qi-val" id="quickBillTo">—</span></div>
                <div class="inv-qi-row"><span class="inv-qi-key">Email</span><span class="inv-qi-val" id="quickEmail">—</span></div>
                <div class="inv-qi-row"><span class="inv-qi-key">Due Date</span><span class="inv-qi-val" id="quickDueDate">{{ now()->addDays(15)->format('m/d/Y') }}</span></div>
                <div class="inv-qi-row"><span class="inv-qi-key">Items</span><span class="inv-qi-val" id="quickItemsCount">0</span></div>
                <div class="inv-qi-row"><span class="inv-qi-key">Linked To</span><span class="inv-qi-val" id="quickLinkedTo">—</span></div>
                <div class="inv-qi-row"><span class="inv-qi-key">Status</span><span class="inv-status-badge"><i class="fas fa-pencil-alt" style="font-size:8px"></i> Draft</span></div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedFiles = [];
let invoiceItems  = [];
let allItems      = [];
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// ══════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    ['bill_to','customer_email','due_date'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateQuickInfo);
    });
    setupCompanyLocation();
    setupAttachments();
    updateQuickInfo();
    validateForm();

    // ── AUTO-REDIRECT + PRE-FILL desde URL params ──────────
    (async () => {
        const urlP = new URLSearchParams(location.search);
        const type = urlP.get('type');
        const id   = urlP.get('id');
        if (!type || !id) return;

        // 1. Pre-seleccionar tipo de work order, cargar opciones y bloquear ambos campos
        const typeSelect = document.getElementById('invoiceable_type');
        if (typeSelect) {
            typeSelect.value = type;
            // Pasar callback: se ejecuta cuando las opciones ya están cargadas
            onInvoiceableTypeChange(() => {
                // Bloquear type y record DESPUÉS de que las opciones cargaron
                lockSelect(typeSelect, null);
                const idSelect = document.getElementById('invoiceable_id');
                if (idSelect) lockSelect(idSelect, null);
            });
        }

        // 2. Verificar si ya existe invoice → redirigir al prepare
        try {
            const r    = await fetch(`/superadmin/invoices/linked?type=${type}&id=${id}`);
            const data = await r.json();
            if (data && data.id) {
                window.location.replace(`/superadmin/invoices/${data.id}/prepare`);
                return;
            }
        } catch(e) {}

        // 3. Pre-llenar y bloquear solo la company
        try {
            const r = await fetch(`/superadmin/invoices/work-order-info?type=${type}&id=${id}`);
            const d = await r.json();
            if (!d?.company_id) return;

            const companySel = document.getElementById('company');
            companySel.value = d.company_id;
            clearFerr('company');                              // ← AGREGAR ESTO
            lockSelect(companySel, 'company-locked-badge');
            companySel.dispatchEvent(new Event('change'));
        } catch(e) {}
    })();
});

// Bloquea visualmente un select y muestra el badge si se provee badgeId
function lockSelect(sel, badgeId) {
    sel.disabled            = true;
    sel.style.background    = 'var(--bg)';
    sel.style.color         = 'var(--ink2)';
    sel.style.cursor        = 'not-allowed';
    sel.style.pointerEvents = 'none';
    sel.style.opacity       = '0.85';
    if (badgeId) {
        const badge = document.getElementById(badgeId);
        if (badge) badge.style.display = 'block';
    }
}

// ══════════════════════════════════════════════════════════
// COMPANY / LOCATION
// ══════════════════════════════════════════════════════════
function setupCompanyLocation() {
    const companySel  = document.getElementById('company');
    const locationSel = document.getElementById('location');

    companySel.addEventListener('change', function() {
        const id  = this.value;
        const opt = this.options[this.selectedIndex];
        document.getElementById('bill_to').value        = opt.getAttribute('data-name')    || '';
        document.getElementById('customer_email').value = opt.getAttribute('data-email')   || '';
        document.getElementById('address').value        = opt.getAttribute('data-address') || '';
        updateQuickInfo();

        if (!id) {
            locationSel.innerHTML = '<option value="">First select a company</option>';
            locationSel.disabled  = true;
            resetItems(); return;
        }

        locationSel.innerHTML = '<option value="">Loading…</option>';
        fetch(`/superadmin/companies/${id}/locations/ajax`)
            .then(r => r.json())
         .then(data => {
    locationSel.innerHTML = '<option value="">Select location</option>';
    if (!data.length) {
        locationSel.innerHTML = '<option value="" disabled>No locations available</option>';
        locationSel.disabled  = true;
        return;
    }
    const byState = {};
    data.forEach(loc => {
        if (!byState[loc.state]) byState[loc.state] = [];
        byState[loc.state].push(loc);
    });
    Object.keys(byState).sort().forEach(state => {
        const og = document.createElement('optgroup');
        og.label = `── ${state} ──`;
        byState[state].forEach(loc => {
            const o       = document.createElement('option');
            o.value       = loc.id;
            o.textContent = loc.city
                ? `${state}  /  ${loc.city}`
                : `${state}  •  Base Price (All cities)`;
            og.appendChild(o);
        });
        locationSel.appendChild(og);
    });
    locationSel.disabled = false;
    clearFerr('company');  // ← AGREGAR AQUÍ
    validateForm();        // ← Y AQUÍ
})
            .catch(() => { locationSel.innerHTML = '<option value="">Error loading</option>'; });
    });

    locationSel.addEventListener('change', function() {
        if (!this.value) { resetItems(); return; }
        loadItems(this.value);
        validateForm();
    });
}

function resetItems() {
    allItems = [];
    document.getElementById('itemSelect').innerHTML = '<option value="">Select a location first</option>';
}

function loadItems(locationId) {
    const sel = document.getElementById('itemSelect');
    sel.innerHTML = '<option value="">Loading items…</option>';
    fetch(`/superadmin/invoices/location/${locationId}/items`)
        .then(r => r.json())
        .then(items => { allItems = items; updateItemSelect(); })
        .catch(() => { sel.innerHTML = '<option value="">Error loading items</option>'; });
}

function updateItemSelect() {
    const sel = document.getElementById('itemSelect');
    sel.innerHTML = '<option value="">Select an item…</option>';
    if (!allItems.length) {
        const o = document.createElement('option');
        o.value = ''; o.textContent = 'No items available'; o.disabled = true;
        sel.appendChild(o); return;
    }
    const grouped = {};
    allItems.forEach(item => {
        const cat = item.category || 'Uncategorized';
        if (!grouped[cat]) grouped[cat] = [];
        grouped[cat].push(item);
    });
    Object.keys(grouped).sort().forEach(cat => {
        const og = document.createElement('optgroup');
        og.label = cat;
        grouped[cat].forEach(item => {
            const o         = document.createElement('option');
            o.value         = item.id;
            o.textContent   = `${item.name} — $${parseFloat(item.price).toFixed(2)}`;
            o.dataset.price = item.price;
            o.dataset.name  = item.name;
            og.appendChild(o);
        });
        sel.appendChild(og);
    });
}

// ══════════════════════════════════════════════════════════
// LINK TO WORK ORDER
// Acepta un callback opcional que se ejecuta cuando las opciones ya están cargadas
// ══════════════════════════════════════════════════════════
function onInvoiceableTypeChange(afterLoaded) {
    const type = document.getElementById('invoiceable_type').value;
    const wrap = document.getElementById('invoiceable_id_wrap');
    const sel  = document.getElementById('invoiceable_id');
    const qi   = document.getElementById('quickLinkedTo');

    if (!type) {
        wrap.style.display = 'none';
        sel.innerHTML      = '<option value="">Select type first…</option>';
        qi.textContent     = '—';
        return;
    }

    wrap.style.display = '';
    sel.innerHTML      = '<option value="">Loading…</option>';

    fetch(`/superadmin/calendar/invoiceable-options?type=${type}`, {
        headers: { 'X-CSRF-TOKEN': csrf }
    })
    .then(r => r.json())
    .then(items => {
        sel.innerHTML = '<option value="">Select…</option>';
        items.forEach(i => {
            const o       = document.createElement('option');
            o.value       = i.id;
            o.textContent = i.label;
            sel.appendChild(o);
        });
        sel.onchange = () => {
            const opt  = sel.options[sel.selectedIndex];
            qi.textContent = opt.value ? opt.textContent : '—';
        };
        // Pre-seleccionar ID desde URL (dentro del .then para que las opciones ya existan)
        const urlId = new URLSearchParams(location.search).get('id');
        if (urlId) {
            sel.value = urlId;
            const opt = sel.options[sel.selectedIndex];
            if (opt?.value) qi.textContent = opt.textContent;
        }
        // Ejecutar callback si se pasó (ej. para bloquear los campos)
        if (typeof afterLoaded === 'function') afterLoaded();
    })
    .catch(() => { sel.innerHTML = '<option value="">Error loading</option>'; });
}

// ══════════════════════════════════════════════════════════
// CUSTOM ITEM
// ══════════════════════════════════════════════════════════
function toggleCustomItem() {
    const box = document.getElementById('customItemBox');
    box.classList.toggle('open');
    if (box.classList.contains('open')) document.getElementById('customName').focus();
}

function addCustomItem() {
    const name  = document.getElementById('customName').value.trim();
    const price = parseFloat(document.getElementById('customPrice').value) || 0;
    const qty   = parseInt(document.getElementById('customQty').value) || 1;
    let ok = true;
    if (!name)    { showFerr('customName','Item name is required'); ok = false; } else clearFerr('customName');
    if (price<=0) { showFerr('customPrice','Price must be > 0');    ok = false; } else clearFerr('customPrice');
    if (!ok) return;
    invoiceItems.push({ id:null, name, price, quantity:qty, note:'' });
    document.getElementById('customName').value  = '';
    document.getElementById('customPrice').value = '';
    document.getElementById('customQty').value   = 1;
    document.getElementById('customItemBox').classList.remove('open');
    renderInvoiceItems(); updateQuickInfo(); validateForm();
}

// ══════════════════════════════════════════════════════════
// ADD FROM CATALOG
// ══════════════════════════════════════════════════════════
function addItemToInvoice() {
    const sel    = document.getElementById('itemSelect');
    const qty    = parseInt(document.getElementById('itemQuantity').value) || 1;
    const itemId = sel.value;
    if (!itemId) return;
    const opt   = sel.options[sel.selectedIndex];
    const price = parseFloat(opt.dataset.price) || 0;
    const name  = opt.dataset.name;
    const idx   = invoiceItems.findIndex(i => i.id == itemId);
    if (idx > -1) { invoiceItems[idx].quantity += qty; }
    else { invoiceItems.push({ id:itemId, name, price, quantity:qty, note:'' }); }
    sel.value = '';
    document.getElementById('itemQuantity').value = 1;
    renderInvoiceItems(); updateQuickInfo(); validateForm();
}

function addItemManually() {
    if (!document.getElementById('itemSelect').value) { showAlert('Please select an item first'); return; }
    addItemToInvoice();
}

// ══════════════════════════════════════════════════════════
// MANAGE ITEMS
// ══════════════════════════════════════════════════════════
function updateItemQuantity(i, change) {
    const nq = invoiceItems[i].quantity + change;
    if (nq < 1) { showAlert('Quantity cannot be less than 1'); return; }
    invoiceItems[i].quantity = nq;
    renderInvoiceItems(); updateQuickInfo();
}
function updateQuantityInput(i, v) {
    const nq = parseInt(v) || 1;
    invoiceItems[i].quantity = nq < 1 ? 1 : nq;
    renderInvoiceItems(); updateQuickInfo();
}
function updateItemNote(i, v)  { invoiceItems[i].note = v; }
function removeInvoiceItem(i) {
    if (confirm('Remove this item?')) {
        invoiceItems.splice(i, 1);
        renderInvoiceItems(); updateQuickInfo(); validateForm();
    }
}
function updateItemPrice(i, v) {
    let p = parseFloat(v);
    if (isNaN(p) || p < 0) p = 0;
    invoiceItems[i].price = p;
    renderInvoiceItems(); updateQuickInfo(); validateForm();
}

// ══════════════════════════════════════════════════════════
// RENDER TABLE
// ══════════════════════════════════════════════════════════
function renderInvoiceItems() {
    const tbody = document.getElementById('invoiceItems');
    let subtotal = 0;
    if (!invoiceItems.length) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="inv-empty-row"><i class="fas fa-box-open"></i>No items added yet</div></td></tr>`;
        updateSummary(0); return;
    }
    let html = '';
    invoiceItems.forEach((item, i) => {
        const tot = item.price * item.quantity; subtotal += tot;
        html += `
        <tr>
            <td>
                <div class="inv-item-name">${item.name}</div>
                <div class="inv-item-price-sub">$${item.price.toFixed(2)} each</div>
                <textarea class="inv-note-ta" placeholder="Add note…"
                    oninput="autoResize(this); updateItemNote(${i}, this.value)"
                    rows="1">${item.note||''}</textarea>
            </td>
            <td style="text-align:center">
                <div class="inv-tbl-qty">
                    <button type="button" class="inv-tbl-qbtn" onclick="updateItemQuantity(${i},-1)">−</button>
                    <input type="text" value="${item.quantity}" onchange="updateQuantityInput(${i},this.value)">
                    <button type="button" class="inv-tbl-qbtn" onclick="updateItemQuantity(${i},1)">+</button>
                </div>
            </td>
            <td style="text-align:right">
                <input type="number" class="inv-price-input"
                       value="${item.price.toFixed(2)}" step="0.01" min="0"
                       onchange="updateItemPrice(${i},this.value)">
            </td>
            <td class="inv-item-total">$${tot.toFixed(2)}</td>
            <td style="text-align:center">
                <button type="button" class="inv-rm-btn" onclick="removeInvoiceItem(${i})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });
    tbody.innerHTML = html;
    updateSummary(subtotal);
}

function autoResize(el) { el.style.height = 'auto'; el.style.height = el.scrollHeight + 'px'; }

function updateSummary(subtotal) {
    document.getElementById('subtotal').textContent        = `$${subtotal.toFixed(2)}`;
    document.getElementById('total').textContent           = `$${subtotal.toFixed(2)}`;
    document.getElementById('quickItemsCount').textContent = invoiceItems.length;
}

// ══════════════════════════════════════════════════════════
// VALIDATION
// ══════════════════════════════════════════════════════════
function validateField(fieldId, rules, message) {
    const field = document.getElementById(fieldId); if (!field) return true;
    const val = field.value.trim(); let ok = true, msg = message;
    if (rules.includes('required')) ok = val !== '';
    if (ok && rules.includes('email')) { ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val); msg = 'Invalid email format'; }
    if (ok && rules.includes('min:')) {
        const min = parseInt(rules.split('min:')[1]) || 0;
        ok = val.length >= min; msg = `Must be at least ${min} characters`;
    }
    if (!ok) { field.classList.add('is-invalid'); showFerr(fieldId, msg); }
    else     { field.classList.remove('is-invalid'); clearFerr(fieldId); }
    return ok;
}
function showFerr(id, msg) {
    const el = document.getElementById(id + '_error'); if (!el) return;
    const sp = el.querySelector('span'); if (sp) sp.textContent = msg; else el.textContent = msg;
    el.classList.add('show'); document.getElementById(id)?.classList.add('is-invalid');
}
function clearFerr(id) {
    document.getElementById(id + '_error')?.classList.remove('show');
    document.getElementById(id)?.classList.remove('is-invalid');
}
function validateDueDate() {
    const inv = document.getElementById('invoice_date').value;
    const due = document.getElementById('due_date').value;
    if (inv && due && due < inv) { showFerr('due_date','Due date cannot be before invoice date'); return false; }
    clearFerr('due_date'); return true;
}
function validateForm() {
    const companyVal  = document.getElementById('company').value;
    const locationVal = document.getElementById('location').value;
    const c1 = companyVal  !== '' ? (clearFerr('company'),  true) : (showFerr('company',  'Please select a company'),  false);
    const c2 = locationVal !== '' ? (clearFerr('location'), true) : (showFerr('location', 'Please select a location'), false);
    const c3 = validateField('bill_to','required|min:3','Required — at least 3 characters');
    const em = document.getElementById('customer_email').value;
    const c4 = em ? validateField('customer_email','email','Invalid email format') : true;
    const c5 = validateDueDate();
    const c6 = invoiceItems.length > 0;
    document.getElementById('items_error').classList.toggle('show', !c6);
    const valid = c1 && c2 && c3 && c4 && c5 && c6;
    document.querySelectorAll('#saveButton,#saveButtonSide').forEach(b => b.disabled = !valid);
    return valid;
}

// ══════════════════════════════════════════════════════════
// ATTACHMENTS
// ══════════════════════════════════════════════════════════
function setupAttachments() {
    const input    = document.getElementById('attachments');
    const list     = document.getElementById('attachmentsList');
    const dropZone = document.getElementById('dropZone');

    input.addEventListener('change', () => {
        handleFiles(Array.from(input.files));
        setTimeout(() => { input.value = ''; }, 100);
    });
    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('over'); });
    dropZone.addEventListener('dragleave', ()  => dropZone.classList.remove('over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault(); dropZone.classList.remove('over');
        handleFiles(Array.from(e.dataTransfer.files));
    });

    function handleFiles(files) {
        files.forEach(f => {
            if (f.size > 10*1024*1024) { alert(`${f.name} exceeds 10MB`); return; }
            selectedFiles.push(f);
        });
        renderAttachments();
    }

    window.removeAttachment = function(i) { selectedFiles.splice(i, 1); renderAttachments(); };

    function renderAttachments() {
        if (!selectedFiles.length) { list.innerHTML = '<div class="inv-no-attach">No attachments added</div>'; return; }
        list.innerHTML = selectedFiles.map((f, i) => {
            const size = (f.size/1024/1024).toFixed(2);
            const ico  = f.type.includes('image') ? 'fa-image'      :
                         f.type.includes('pdf')   ? 'fa-file-pdf'   :
                         f.type.includes('word')  ? 'fa-file-word'  :
                         f.type.includes('excel') ? 'fa-file-excel' : 'fa-file';
            return `<div class="inv-attach-item">
                <div class="inv-attach-l">
                    <div class="inv-attach-icon"><i class="fas ${ico}"></i></div>
                    <div style="min-width:0">
                        <div class="inv-attach-name">${f.name}</div>
                        <div class="inv-attach-size">${size} MB</div>
                    </div>
                </div>
                <button class="inv-attach-rm" type="button" onclick="removeAttachment(${i})">
                    <i class="fas fa-times"></i>
                </button>
            </div>`;
        }).join('');
    }
}

// ══════════════════════════════════════════════════════════
// QUICK INFO
// ══════════════════════════════════════════════════════════
function updateQuickInfo() {
    document.getElementById('quickBillTo').textContent = document.getElementById('bill_to').value || '—';
    document.getElementById('quickEmail').textContent  = document.getElementById('customer_email').value || '—';
    const d = document.getElementById('due_date').value;
    if (d) document.getElementById('quickDueDate').textContent =
        new Date(d + 'T00:00:00').toLocaleDateString('en-US');
}

// ══════════════════════════════════════════════════════════
// SAVE
// ══════════════════════════════════════════════════════════
function saveInvoice() {
    if (!validateForm()) { showAlert('Please correct the errors before saving'); return; }
    if (!confirm('Create this invoice?')) return;

    const fd = new FormData();
    fd.append('company_location_id', document.getElementById('location').value);
    fd.append('crew_id',             document.getElementById('crew_id').value);
    fd.append('invoice_date',        document.getElementById('invoice_date').value);
    fd.append('due_date',            document.getElementById('due_date').value);
    fd.append('customer_email',      document.getElementById('customer_email').value);
    fd.append('address',             document.getElementById('address').value);
    fd.append('bill_to',             document.getElementById('bill_to').value);
    fd.append('memo',                document.getElementById('memo').value);
    fd.append('notes',               document.getElementById('notes').value);
    fd.append('invoice_number',      document.getElementById('invoice_number').value);
    fd.append('invoiceable_type',    document.getElementById('invoiceable_type').value);
    fd.append('invoiceable_id',      document.getElementById('invoiceable_id').value);

    invoiceItems.forEach((item, i) => {
        fd.append(`items[${i}][id]`,       item.id ?? '');
        fd.append(`items[${i}][name]`,     item.name.trim());
        fd.append(`items[${i}][price]`,    Number(item.price));
        fd.append(`items[${i}][quantity]`, Number(item.quantity));
        fd.append(`items[${i}][note]`,     item.note?.trim() ?? '');
    });
    selectedFiles.forEach(f => fd.append('attachments[]', f));

    showLoading(true);
    fetch("{{ route('superadmin.invoices.store') }}", {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf },
        body:    fd
    })
    .then(r => r.json())
    .then(data => {
        showLoading(false);
        if (data.success) window.location.href = `/superadmin/invoices/${data.invoice_id}`;
        else showAlert(data.message || 'Error creating invoice');
    })
    .catch(err => { showLoading(false); console.error(err); showAlert('Server error'); });
}

// ══════════════════════════════════════════════════════════
// HELPERS
// ══════════════════════════════════════════════════════════
function showAlert(msg) {
    const a = document.getElementById('topAlert');
    document.getElementById('topAlertMsg').textContent = msg;
    a.classList.add('show');
    setTimeout(() => a.classList.remove('show'), 5000);
    a.scrollIntoView({ behavior:'smooth', block:'start' });
}
function showLoading(on) {
    document.querySelectorAll('#saveButton,#saveButtonSide').forEach(b => {
        b.innerHTML = on
            ? '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i> Saving…'
            : '<i class="fas fa-floppy-disk" style="font-size:10px"></i> Save Invoice';
        b.disabled = on;
    });
}
</script>



@endsection

