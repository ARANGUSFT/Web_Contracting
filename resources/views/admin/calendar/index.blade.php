@extends('admin.layouts.superadmin')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --bg:        #f6f7f9;
    --surf:      #ffffff;
    --bd:        rgba(0,0,0,.07);
    --bd-md:     rgba(0,0,0,.12);
    --tx:        #0d0f12;
    --tx2:       #5a6272;
    --tx3:       #9099a8;
    --blue:      #2563eb;
    --blue-bg:   #eff4ff;
    --blue-bd:   #bfcffe;
    --green:     #16a34a;
    --green-bg:  #f0fdf4;
    --green-bd:  #bbf7d0;
    --amber:     #d97706;
    --amber-bg:  #fffbeb;
    --amber-bd:  #fde68a;
    --red:       #dc2626;
    --red-bg:    #fef2f2;
    --red-bd:    #fecaca;
    --cyan:      #0e7490;
    --cyan-bg:   #ecfeff;
    --cyan-bd:   #a5f3fc;
    --r:         8px;
    --r-lg:      12px;
    --r-xl:      16px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{background:var(--bg);font-family:'Inter',-apple-system,sans-serif;color:var(--tx);font-size:14px;line-height:1.5;}
a{color:inherit;text-decoration:none;}

.cal-page{padding:24px 28px;max-width:1600px;}
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.topbar-left{display:flex;align-items:center;gap:12px;}
.topbar-title{font-size:18px;font-weight:600;letter-spacing:-.3px;}
.topbar-sub{font-size:12px;color:var(--tx3);margin-top:1px;}
.topbar-actions{display:flex;gap:8px;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:var(--r);font-size:13px;font-weight:500;border:1px solid transparent;cursor:pointer;transition:all .15s;font-family:inherit;white-space:nowrap;}
.btn-ghost{background:var(--surf);border-color:var(--bd-md);color:var(--tx2);}
.btn-ghost:hover{background:var(--bg);color:var(--tx);}
.btn-primary{background:var(--blue);color:#fff;}
.btn-primary:hover{background:#1d4ed8;}
.btn-success{background:var(--green);color:#fff;}
.btn-success:hover{background:#15803d;}
.btn-cyan{background:var(--cyan);color:#fff;}
.btn-cyan:hover{filter:brightness(1.08);color:#fff;}
.btn-photos{background:#7c3aed;color:#fff;}
.btn-photos:hover{background:#6d28d9;color:#fff;}
.btn-sm{padding:5px 10px;font-size:12px;}
.btn-icon{background:none;border:1px solid var(--bd);color:var(--tx3);padding:4px 8px;border-radius:6px;cursor:pointer;font-size:11px;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;font-family:inherit;}
.btn-icon:hover{background:var(--bg);color:var(--blue);border-color:var(--blue-bd);}

/* ── Stats ── */
.stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:20px;}
.stat{background:var(--surf);border:1px solid var(--bd);border-radius:var(--r-lg);padding:16px 18px;display:flex;align-items:flex-start;gap:12px;position:relative;overflow:hidden;}
.stat-dot{width:8px;height:8px;border-radius:50%;margin-top:5px;flex-shrink:0;}
.stat-dot.blue{background:var(--blue);}
.stat-dot.green{background:var(--green);}
.stat-dot.amber{background:var(--amber);}
.stat-dot.red{background:var(--red);}
.stat-dot.cyan{background:var(--cyan);}
.stat-num{font-size:26px;font-weight:600;letter-spacing:-.5px;line-height:1;}
.stat-lbl{font-size:11px;color:var(--tx3);margin-top:3px;text-transform:uppercase;letter-spacing:.5px;font-weight:500;}
.stat-bar{position:absolute;bottom:0;left:0;height:2px;width:100%;background:var(--bd);}
.stat-bar-fill{height:100%;border-radius:2px;transition:width .4s ease;}
.stat-bar-fill.blue{background:var(--blue);}
.stat-bar-fill.green{background:var(--green);}
.stat-bar-fill.amber{background:var(--amber);}
.stat-bar-fill.red{background:var(--red);}
.stat-bar-fill.cyan{background:var(--cyan);}

/* ── Calendar ── */
.cal-wrap{background:var(--surf);border:1px solid var(--bd);border-radius:var(--r-xl);overflow:hidden;}
.cal-inner{padding:20px 20px 24px;}
#calendar{--fc-border-color:var(--bd);--fc-today-bg-color:var(--blue-bg);--fc-page-bg-color:transparent;}
.fc .fc-header-toolbar{margin-bottom:16px!important;padding:0;align-items:center;}
.fc .fc-toolbar-title{font-size:15px!important;font-weight:600!important;letter-spacing:-.2px;color:var(--tx);}
.fc .fc-button{background:var(--surf)!important;border:1px solid var(--bd-md)!important;color:var(--tx2)!important;border-radius:var(--r)!important;padding:5px 10px!important;font-size:12px!important;font-weight:500!important;box-shadow:none!important;font-family:'Inter',sans-serif!important;transition:all .15s!important;}
.fc .fc-button:hover{background:var(--bg)!important;color:var(--tx)!important;}
.fc .fc-button-active,.fc .fc-button-primary:not(:disabled).fc-button-active{background:var(--blue)!important;border-color:var(--blue)!important;color:#fff!important;}
.fc .fc-button-group{gap:4px;}
.fc .fc-daygrid-day-number{font-size:12px;font-weight:500;color:var(--tx2);padding:6px 8px!important;text-decoration:none;}
.fc .fc-day-today .fc-daygrid-day-number{background:var(--blue);color:#fff;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:4px;padding:0!important;font-size:11px;}
.fc .fc-col-header-cell-cushion{font-size:11px;font-weight:600;color:var(--tx3);text-transform:uppercase;letter-spacing:.6px;text-decoration:none;padding:8px 4px;}
.fc .fc-event{border:none!important;border-radius:5px!important;padding:3px 7px 4px!important;font-size:11.5px!important;font-weight:500!important;cursor:pointer;margin-bottom:2px;box-shadow:none!important;transition:opacity .1s,filter .1s;}
.fc .fc-event:hover{opacity:.85;filter:brightness(1.06);}
.fc-event-status-badge{display:inline-flex;align-items:center;gap:3px;font-size:9px;font-weight:700;padding:1px 6px;border-radius:9999px;background:#fff;margin-bottom:3px;flex-shrink:0;line-height:1.5;border-width:1.5px;border-style:solid;letter-spacing:.2px;}
.fc-event-company{font-size:9.5px;font-weight:700;letter-spacing:.1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:1px;opacity:.9;}
.fc-event-crew-tag{font-size:10px;opacity:.85;display:flex;align-items:center;gap:3px;margin-top:2px;font-weight:400;}
.fc .fc-more-link{font-size:11px;color:var(--blue);font-weight:500;padding:1px 6px;}
.fc .fc-daygrid-day.fc-day-today{background:var(--blue-bg)!important;}
.fc .fc-daygrid-day{min-height:90px;}

/* ── Pills ── */
.m-pill{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;text-transform:uppercase;letter-spacing:.5px;}
.m-pill.job{background:var(--blue-bg);color:var(--blue);border:1px solid var(--blue-bd);}
.m-pill.emerg{background:var(--red-bg);color:var(--red);border:1px solid var(--red-bd);}
.m-pill.repair{background:var(--cyan-bg);color:var(--cyan);border:1px solid var(--cyan-bd);}

/* ── Files ── */
.rp-upload-hint{font-size:12px;color:var(--tx3);margin-top:5px;}
.rp-file-list{margin-top:10px;}
.rp-file-entry{display:flex;align-items:center;gap:8px;padding:7px 10px;border:1px solid var(--bd);border-radius:var(--r);background:var(--surf);margin-bottom:5px;font-size:12px;}
.rp-file-entry-name{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--tx);}
.btn-remove-staged{background:none;border:none;color:var(--red);cursor:pointer;font-size:13px;padding:0;line-height:1;}
.rp-feedback{font-size:12.5px;border-radius:var(--r);padding:8px 12px;display:flex;align-items:center;gap:6px;}
.rp-feedback.ok{background:var(--green-bg);color:#15803d;border:1px solid var(--green-bd);}
.rp-feedback.err{background:var(--red-bg);color:var(--red);border:1px solid var(--red-bd);}

/* ── Offcanvas ── */
.offcanvas.offcanvas-end{width:340px!important;border-left:1px solid var(--bd);background:var(--surf);}
.offcanvas-header{padding:16px 20px;border-bottom:1px solid var(--bd);}
.offcanvas-title{font-size:14px;font-weight:600;color:var(--tx);}
.offcanvas-body{padding:16px 20px;}
.co-item{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:var(--r);border:1px solid var(--bd);margin-bottom:6px;transition:all .15s;}
.co-item:hover{border-color:var(--bd-md);background:var(--bg);}
.form-switch .form-check-input:checked{background-color:var(--blue);border-color:var(--blue);}
.cpick{width:20px;height:20px;border-radius:50%;border:2px solid white;outline:1px solid var(--bd-md);cursor:pointer;transition:transform .15s;display:block;}
.cpick:hover{transform:scale(1.2);}

/* ── Modal ── */
#eventModal .modal-content{border:1px solid var(--bd-md);border-radius:var(--r-xl);box-shadow:0 8px 40px rgba(0,0,0,.12);overflow:hidden;font-family:'Inter',sans-serif;}
.m-strip{padding:20px 24px 0;border-bottom:1px solid var(--bd);background:var(--surf);}
.m-strip-top{display:flex;align-items:flex-start;justify-content:space-between;padding-bottom:16px;}
.m-title{font-size:17px;font-weight:600;letter-spacing:-.3px;margin-top:6px;line-height:1.3;}
.m-date{font-size:12px;color:var(--tx3);margin-top:3px;}
.m-tabs{display:flex;gap:2px;padding:0 20px;background:var(--bg);border-top:1px solid var(--bd);overflow-x:auto;scrollbar-width:none;}
.m-tabs::-webkit-scrollbar{display:none;}
.m-tab{background:none;border:none;padding:11px 14px 10px;font-size:12px;font-weight:500;color:var(--tx3);cursor:pointer;border-bottom:2.5px solid transparent;border-top:2.5px solid transparent;transition:color .15s,border-color .15s,background .15s;display:flex;align-items:center;gap:6px;font-family:'Inter',sans-serif;white-space:nowrap;border-radius:0;position:relative;flex-shrink:0;}
.m-tab:hover{color:var(--tx);background:rgba(0,0,0,.03);}
.m-tab.on{color:var(--blue);border-bottom-color:var(--blue);background:var(--surf);font-weight:600;}
.m-tab.repair-mode.on{color:var(--cyan);border-bottom-color:var(--cyan);}
.m-tab i{font-size:11px;opacity:.7;}
.m-tab.on i{opacity:1;}
.m-tab-badge{background:var(--blue);color:#fff;font-size:9.5px;font-weight:700;border-radius:99px;padding:1px 6px;line-height:1.5;min-width:18px;text-align:center;}
.m-body{background:var(--surf);}
.m-panel{display:none;padding:22px 24px;}
.m-panel.on{display:block;animation:pin .18s ease;}
@keyframes pin{from{opacity:0;transform:translateY(3px)}to{opacity:1;transform:translateY(0)}}
.m-footer{padding:14px 24px;border-top:1px solid var(--bd);background:var(--bg);display:flex;align-items:center;justify-content:flex-end;gap:8px;}

/* ── Info grid ── */
.p-section{margin-bottom:22px;}
.p-section:last-child{margin-bottom:0;}
.p-heading{font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:.7px;color:var(--tx3);margin-bottom:10px;display:flex;align-items:center;gap:6px;}
.p-heading::after{content:'';flex:1;height:1px;background:var(--bd);}
.p-heading span{background:var(--bg);border:1px solid var(--bd);border-radius:9999px;font-size:10px;padding:1px 6px;color:var(--tx3);font-weight:600;}
.fi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;}
.fi-cell{background:var(--bg);border-radius:var(--r);padding:10px 12px;border:1px solid var(--bd);}
.fi-cell.s2{grid-column:span 2;}
.fi-cell.s3{grid-column:span 3;}
.fi-key{font-size:10.5px;font-weight:500;color:var(--tx3);text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;}
.fi-val{font-size:13px;color:var(--tx);word-break:break-word;}
.fi-val a{color:var(--blue);}
.file-row{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:var(--r);border:1px solid var(--bd);background:var(--surf);transition:border-color .15s;margin-bottom:5px;}
.file-row:hover{border-color:var(--blue);}
.file-ico{width:30px;height:30px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;}
.file-ico.pdf{background:var(--red-bg);color:var(--red);}
.file-ico.img{background:var(--blue-bg);color:var(--blue);}
.file-ico.other{background:var(--bg);color:var(--tx3);}
.file-name{font-size:12.5px;font-weight:500;color:var(--tx);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.file-ext{font-size:10.5px;color:var(--tx3);text-transform:uppercase;margin-top:1px;}

/* ── Notes ── */
.notes-feed{max-height:300px;overflow-y:auto;margin-bottom:14px;padding-right:2px;}
.note-row{display:flex;gap:10px;margin-bottom:12px;}
.note-av{width:28px;height:28px;border-radius:50%;background:var(--blue-bg);color:var(--blue);font-size:10px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;}
.note-body{flex:1;min-width:0;}
.note-meta{display:flex;align-items:center;gap:8px;margin-bottom:4px;}
.note-author{font-size:12px;font-weight:600;color:var(--tx);}
.note-time{font-size:11px;color:var(--tx3);}
.note-bubble{font-size:12.5px;color:var(--tx2);line-height:1.5;background:var(--bg);border-radius:0 var(--r) var(--r) var(--r);padding:9px 12px;border:1px solid var(--bd);}
.note-compose{background:var(--bg);border-radius:var(--r-lg);border:1px solid var(--bd);overflow:hidden;}
.note-compose textarea{width:100%;border:none;background:transparent;padding:10px 14px;font-size:13px;font-family:'Inter',sans-serif;resize:none;outline:none;color:var(--tx);}
.note-compose-footer{display:flex;align-items:center;justify-content:space-between;padding:6px 10px 8px;border-top:1px solid var(--bd);}
.note-hint{font-size:11px;color:var(--tx3);}
.kbd{font-size:10px;padding:1px 5px;border:1px solid var(--bd-md);border-radius:3px;background:var(--surf);color:var(--tx2);}
.note-img-wrap{margin-bottom:4px;}
.note-img{width:180px;height:140px;object-fit:cover;border-radius:10px;cursor:pointer;display:block;box-shadow:0 2px 8px rgba(0,0,0,.12);transition:opacity .15s,transform .15s;}
.note-img:hover{opacity:.92;transform:scale(1.01);}
.note-text{margin-top:2px;font-size:12.5px;line-height:1.5;color:inherit;}
#note-img-modal{display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;}
#note-img-modal.nim-open{display:flex;}
.nim-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.8);backdrop-filter:blur(4px);cursor:pointer;}
.nim-box{position:relative;z-index:1;max-width:90vw;max-height:90vh;display:flex;align-items:center;justify-content:center;}
.nim-box img{max-width:90vw;max-height:85vh;object-fit:contain;border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,.4);}
.nim-close{position:absolute;top:-14px;right:-14px;width:32px;height:32px;border-radius:50%;background:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;color:#0d0f12;box-shadow:0 2px 8px rgba(0,0,0,.2);z-index:2;}
.nim-close:hover{background:#f0f4fa;}

/* ── Crew ── */
.crew-assigned-card{border:1px solid var(--green-bd);background:var(--green-bg);border-radius:var(--r-lg);padding:14px 16px;margin-bottom:4px;display:flex;align-items:center;gap:12px;}
.crew-unassigned-card{border:1px dashed var(--bd-md);background:var(--bg);border-radius:var(--r-lg);padding:14px 16px;margin-bottom:4px;display:flex;align-items:center;gap:10px;color:var(--tx3);}
.crew-av{width:42px;height:42px;border-radius:50%;background:var(--green);color:#fff;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.crew-av-info{flex:1;min-width:0;}
.crew-av-name{font-size:13.5px;font-weight:600;color:#14532d;}
.crew-av-co{font-size:11.5px;color:var(--green);margin-top:2px;}
.crew-badge-active{background:#dcfce7;color:#166534;font-size:10px;font-weight:600;padding:2px 8px;border-radius:9999px;border:1px solid var(--green-bd);white-space:nowrap;}
.crew-sel-wrap{position:relative;margin-bottom:10px;}
.crew-sel-wrap select{width:100%;padding:9px 36px 9px 12px;border:1px solid var(--bd-md);border-radius:var(--r);font-size:13px;font-family:'Inter',sans-serif;color:var(--tx);background:var(--surf);outline:none;appearance:none;cursor:pointer;transition:border-color .15s;}
.crew-sel-wrap select:focus{border-color:var(--blue);box-shadow:0 0 0 2px rgba(37,99,235,.12);}
.crew-sel-arrow{position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;font-size:11px;color:var(--tx3);}
.hist-row{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--bd);}
.hist-row:last-child{border-bottom:none;}
.hist-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0;}
.hist-info{flex:1;min-width:0;}
.hist-name{font-size:12.5px;font-weight:500;color:var(--tx);}
.hist-when{font-size:11px;color:var(--tx3);margin-top:1px;}
.hist-pill{font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:9999px;white-space:nowrap;}
.hist-pill.active{background:#dcfce7;color:#166534;}
.hist-pill.removed{background:var(--bg);color:var(--tx3);border:1px solid var(--bd);}

/* ── Payment / Payout ── */
.pay-summary{border-radius:var(--r-lg);padding:14px 16px;margin-bottom:20px;display:flex;align-items:center;gap:12px;}
.pay-summary.paid{background:var(--green-bg);border:1px solid var(--green-bd);}
.pay-summary.unpaid{background:var(--amber-bg);border:1px solid var(--amber-bd);}
.pay-summary-icon{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
.pay-summary.paid .pay-summary-icon{background:#dcfce7;color:var(--green);}
.pay-summary.unpaid .pay-summary-icon{background:#fef3c7;color:var(--amber);}
.pay-summary-info{flex:1;}
.pay-summary-status{font-size:13px;font-weight:600;}
.pay-summary.paid .pay-summary-status{color:#14532d;}
.pay-summary.unpaid .pay-summary-status{color:#78350f;}
.pay-summary-detail{font-size:11.5px;margin-top:2px;}
.pay-summary.paid .pay-summary-detail{color:var(--green);}
.pay-summary.unpaid .pay-summary-detail{color:var(--amber);}
.pay-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;}
.pay-field{display:flex;flex-direction:column;gap:5px;}
.pay-field.s2{grid-column:span 2;}
.pay-label{font-size:11px;font-weight:600;color:var(--tx3);text-transform:uppercase;letter-spacing:.5px;}
.pay-input{padding:8px 11px;border:1px solid var(--bd-md);border-radius:var(--r);font-size:13px;font-family:'Inter',sans-serif;color:var(--tx);background:var(--surf);outline:none;transition:border-color .15s;width:100%;}
.pay-input:focus{border-color:var(--blue);box-shadow:0 0 0 2px rgba(37,99,235,.12);}
.pay-receipt-row{display:flex;align-items:center;gap:8px;padding:9px 12px;background:var(--bg);border:1px solid var(--bd);border-radius:var(--r);margin-bottom:14px;}
.pay-receipt-ico{width:28px;height:28px;border-radius:6px;background:var(--red-bg);color:var(--red);display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;}
.pay-receipt-name{flex:1;font-size:12.5px;font-weight:500;color:var(--tx);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.pay-feedback{margin-top:10px;font-size:12.5px;border-radius:var(--r);padding:9px 12px;display:flex;align-items:center;gap:6px;}
.pay-feedback.ok{background:var(--green-bg);color:#15803d;border:1px solid var(--green-bd);}
.pay-feedback.err{background:var(--red-bg);color:var(--red);border:1px solid var(--red-bd);}


.pay-hero{border-radius:var(--r-xl);padding:18px 20px;margin-bottom:20px;background:linear-gradient(135deg,#1e40af 0%,#2563eb 60%,#3b82f6 100%);display:flex;align-items:center;gap:16px;position:relative;overflow:hidden;}
.pay-hero::before{content:'';position:absolute;right:-20px;top:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.06);}
.pay-hero-ico{width:44px;height:44px;border-radius:var(--r-lg);background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;flex-shrink:0;z-index:1;}
.pay-hero-info{flex:1;z-index:1;}
.pay-hero-lbl{font-size:11px;font-weight:500;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.6px;}
.pay-hero-val{font-size:26px;font-weight:700;color:#fff;letter-spacing:-.5px;line-height:1.1;margin-top:2px;}
.pay-hero-sub{font-size:11.5px;color:rgba(255,255,255,.6);margin-top:3px;}
.pay-hero.no-invoice{background:linear-gradient(135deg,#475569 0%,#64748b 100%);}
.pay-status-toggle{display:flex;gap:6px;}
.pay-status-pill{flex:1;padding:8px 12px;border-radius:var(--r);border:1.5px solid var(--bd-md);background:var(--surf);font-size:12px;font-weight:600;cursor:pointer;text-align:center;transition:all .15s;font-family:'Inter',sans-serif;display:flex;align-items:center;justify-content:center;gap:5px;}
.pay-status-pill.active-unpaid{background:var(--amber-bg);border-color:var(--amber-bd);color:var(--amber);}
.pay-status-pill.active-paid{background:var(--green-bg);border-color:var(--green-bd);color:var(--green);}
.pay-receipt-section{background:var(--bg);border:1px solid var(--bd);border-radius:var(--r-lg);overflow:hidden;margin-bottom:16px;}
.pay-receipt-header{padding:10px 14px;border-bottom:1px solid var(--bd);}
.pay-receipt-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--tx3);display:flex;align-items:center;gap:6px;}
.pay-receipt-body{padding:12px 14px;}
.pay-receipt-file{display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--surf);border:1px solid var(--bd);border-radius:var(--r);margin-bottom:10px;}
.pay-receipt-file-ico{width:34px;height:34px;border-radius:8px;background:var(--red-bg);color:var(--red);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;}
.pay-receipt-file-info{flex:1;min-width:0;}
.pay-receipt-file-name{font-size:12.5px;font-weight:500;color:var(--tx);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.pay-receipt-file-sub{font-size:11px;color:var(--tx3);margin-top:1px;}
.pay-receipt-actions{display:flex;gap:5px;flex-shrink:0;}
.pay-receipt-upload{border:1.5px dashed var(--bd-md);border-radius:var(--r);padding:14px;text-align:center;cursor:pointer;transition:all .15s;display:block;}
.pay-receipt-upload:hover{border-color:var(--blue);background:var(--blue-bg);}
.pay-receipt-upload input{display:none;}
.pay-receipt-upload-lbl{font-size:12px;font-weight:500;color:var(--tx3);margin-top:4px;display:block;}
.pay-save-btn{width:100%;padding:11px;border-radius:var(--r-lg);background:var(--green);color:#fff;font-size:13px;font-weight:600;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:'Inter',sans-serif;transition:all .15s;}
.pay-save-btn:hover{background:#15803d;transform:translateY(-1px);box-shadow:0 4px 12px rgba(22,163,74,.3);}
.pay-save-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none;}
#eventModal .modal-content {
    display: flex;
    flex-direction: column;
    max-height: 90vh;
}

.m-body {
    background: var(--surf);
    overflow-y: auto;
    flex: 1;
    min-height: 0;
}
/* ── Payout total banner ── */
.payout-banner{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:var(--blue-bg);border:1px solid var(--blue-bd);border-radius:var(--r-lg);margin-bottom:16px;}
.payout-banner-lbl{font-size:12px;font-weight:600;color:var(--blue);}
.payout-banner-val{font-size:17px;font-weight:800;color:var(--blue);}

/* ── Utils ── */
.empty-state{text-align:center;padding:32px 16px;color:var(--tx3);}
.empty-state i{font-size:22px;margin-bottom:8px;display:block;opacity:.4;}
.empty-state p{font-size:12px;}
.sk{background:linear-gradient(90deg,var(--bg) 25%,#eaedf2 50%,var(--bg) 75%);background-size:200%;animation:sk .9s ease infinite;border-radius:4px;}
@keyframes sk{from{background-position:200% 0}to{background-position:-200% 0}}
.sk-line{height:13px;margin-bottom:8px;}

/* ── Search + chips ── */
.cal-search-wrap{display:flex;align-items:center;gap:8px;background:var(--surf);border:1px solid var(--bd);border-radius:var(--r-lg);padding:10px 16px;margin-bottom:12px;box-shadow:0 1px 4px rgba(0,0,0,.05);}
.cal-search-ico{font-size:14px;color:var(--tx3);flex-shrink:0;}
.cal-search-input{flex:1;border:none;outline:none;font-size:13px;font-family:'Inter',sans-serif;color:var(--tx);background:transparent;}
.cal-search-input::placeholder{color:var(--tx3);}
.cal-search-clear{background:none;border:none;cursor:pointer;color:var(--tx3);font-size:13px;padding:2px 4px;display:flex;align-items:center;}
.cal-search-clear:hover{color:var(--tx);}
.cal-search-count{font-size:12px;font-weight:600;color:var(--blue);background:var(--blue-bg);border:1px solid var(--blue-bd);border-radius:9999px;padding:2px 10px;white-space:nowrap;flex-shrink:0;}
.cal-filter-chips{display:flex;gap:6px;align-items:center;flex-wrap:wrap;}
.cal-chip{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;border:1.5px solid;cursor:pointer;transition:all .15s;user-select:none;font-family:'Inter',sans-serif;}
.cal-chip.job{color:var(--blue);border-color:var(--blue-bd);background:var(--blue-bg);}
.cal-chip.emerg{color:var(--red);border-color:var(--red-bd);background:var(--red-bg);}
.cal-chip.repair{color:var(--cyan);border-color:var(--cyan-bd);background:var(--cyan-bg);}
.cal-chip.off{opacity:.4;filter:grayscale(.8);}
.cal-chip-dot{width:6px;height:6px;border-radius:50%;background:currentColor;}

/* ── Pending Payments ── */
.pp-section{background:var(--surf);border:1px solid var(--red-bd);border-radius:var(--r-xl);overflow:hidden;}
.pp-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--bd);background:var(--red-bg);}
.pp-header-left{display:flex;align-items:center;gap:12px;}
.pp-icon{width:36px;height:36px;border-radius:var(--r);background:var(--red-bg);border:1px solid var(--red-bd);color:var(--red);display:flex;align-items:center;justify-content:center;font-size:15px;}
.pp-title{font-size:14px;font-weight:600;color:var(--tx);}
.pp-sub{font-size:11.5px;color:var(--tx3);margin-top:1px;}
.pp-badge{background:var(--red);color:#fff;font-size:12px;font-weight:700;padding:3px 10px;border-radius:9999px;}
.pp-empty{text-align:center;padding:28px 16px;color:var(--tx3);font-size:13px;}
.pp-table{width:100%;border-collapse:collapse;}
.pp-table thead tr{background:var(--bg);}
.pp-table th{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--tx3);padding:10px 16px;text-align:left;border-bottom:1px solid var(--bd);}
.pp-table td{font-size:13px;color:var(--tx);padding:11px 16px;border-bottom:1px solid var(--bd);vertical-align:middle;}
.pp-table tbody tr:last-child td{border-bottom:none;}
.pp-table tbody tr:hover{background:var(--bg);}
.pp-type-pill{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:2px 8px;border-radius:9999px;}
.pp-type-pill.job{background:var(--blue-bg);color:var(--blue);border:1px solid var(--blue-bd);}
.pp-type-pill.emerg{background:var(--red-bg);color:var(--red);border:1px solid var(--red-bd);}
.pp-amount{font-weight:600;color:var(--amber);}
.pp-amount.none{color:var(--tx3);font-style:italic;}
.pp-search-wrap{display:flex;align-items:center;gap:8px;padding:12px 20px;border-bottom:1px solid var(--bd);background:var(--surf);}
.pp-search-ico{font-size:13px;color:var(--tx3);flex-shrink:0;}
.pp-search-input{flex:1;border:none;outline:none;font-size:13px;font-family:'Inter',sans-serif;color:var(--tx);background:transparent;}
.pp-search-input::placeholder{color:var(--tx3);}
.pp-search-clear{background:none;border:none;cursor:pointer;color:var(--tx3);font-size:13px;padding:2px 4px;display:flex;align-items:center;}
.pp-search-clear:hover{color:var(--tx);}
.pp-row-hidden{display:none !important;}

@media(max-width:768px){
    .stats-grid{grid-template-columns:repeat(3,1fr);}
    .cal-page{padding:16px;}
    .fi-grid{grid-template-columns:1fr 1fr;}
    .fi-cell.s3{grid-column:span 2;}
    .pay-grid{grid-template-columns:1fr;}
    .pay-field.s2{grid-column:span 1;}
    .m-tabs{overflow-x:auto;}
}
</style>

@section('content')
<div class="cal-page">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <div style="width:36px;height:36px;background:var(--blue-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;border:1px solid var(--blue-bd)">
                <i class="fas fa-calendar-days" style="font-size:15px;color:var(--blue)"></i>
            </div>
            <div>
                <div class="topbar-title">Operations Calendar</div>
                <div class="topbar-sub">Jobs, emergencies &amp; repairs overview</div>
            </div>
        </div>
        <div class="topbar-actions">
            <button class="btn btn-ghost" data-bs-toggle="offcanvas" data-bs-target="#companiesCanvas">
                <i class="fas fa-sliders-h" style="font-size:12px"></i> View settings
            </button>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left" style="font-size:12px"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat"><div class="stat-dot blue"></div><div><div class="stat-num" id="stat-jobs">—</div><div class="stat-lbl">Total jobs</div></div><div class="stat-bar"><div class="stat-bar-fill blue" id="sbar-jobs" style="width:0%"></div></div></div>
        <div class="stat"><div class="stat-dot red"></div><div><div class="stat-num" id="stat-emerg">—</div><div class="stat-lbl">Emergencies</div></div><div class="stat-bar"><div class="stat-bar-fill red" id="sbar-emerg" style="width:0%"></div></div></div>
        <div class="stat"><div class="stat-dot cyan"></div><div><div class="stat-num" id="stat-repairs">—</div><div class="stat-lbl">Repairs</div></div><div class="stat-bar"><div class="stat-bar-fill cyan" id="sbar-repairs" style="width:0%"></div></div></div>
        <div class="stat"><div class="stat-dot amber"></div><div><div class="stat-num" id="stat-inprogress">—</div><div class="stat-lbl">In Progress</div></div><div class="stat-bar"><div class="stat-bar-fill amber" id="sbar-inprogress" style="width:0%"></div></div></div>
        <div class="stat"><div class="stat-dot green"></div><div><div class="stat-num" id="stat-done">—</div><div class="stat-lbl">Completed</div></div><div class="stat-bar"><div class="stat-bar-fill green" id="sbar-done" style="width:0%"></div></div></div>
    </div>

    {{-- SEARCH + CHIPS --}}
    <div class="cal-search-wrap">
        <i class="fas fa-search cal-search-ico"></i>
        <input type="text" id="cal-search" class="cal-search-input" placeholder="Search by job #, company, crew or repair…" oninput="filterCalEvents(this.value)">
        <button class="cal-search-clear" id="cal-search-clear" style="display:none" onclick="clearCalSearch()"><i class="fas fa-times"></i></button>
        <div style="width:1px;height:20px;background:var(--bd);margin:0 4px;flex-shrink:0;"></div>
        <div class="cal-filter-chips">
            <span class="cal-chip job"    data-type="job"       onclick="toggleChip(this)"><span class="cal-chip-dot"></span> Jobs</span>
            <span class="cal-chip emerg"  data-type="emergency" onclick="toggleChip(this)"><span class="cal-chip-dot"></span> Emergencies</span>
            <span class="cal-chip repair" data-type="repair"    onclick="toggleChip(this)"><span class="cal-chip-dot"></span> Repairs</span>
        </div>
        <span class="cal-search-count" id="cal-search-count" style="display:none"></span>
    </div>

    {{-- CALENDAR --}}
    <div class="cal-wrap">
        <div class="cal-inner"><div id="calendar"></div></div>
    </div>

    {{-- COMPANIES OFFCANVAS --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="companiesCanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Calendar settings</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <p style="font-size:12px;color:var(--tx3);margin-bottom:14px">Toggle visibility and change colors per company.</p>
            <div id="companies-list">
            @forelse($companies as $c)
                <div class="co-item">
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch mb-0">
                            <input type="checkbox" class="form-check-input company-toggle" role="switch"
                                id="t-{{ Str::slug($c['name']) }}" data-name="{{ $c['name'] }}"
                                {{ ($c['active'] ?? true) ? 'checked' : '' }}>
                        </div>
                        <label for="t-{{ Str::slug($c['name']) }}" style="font-size:13px;font-weight:500;cursor:pointer">{{ $c['name'] }}</label>
                    </div>
                    <div>
                        <input type="color" class="d-none company-color" data-name="{{ $c['name'] }}" value="{{ $c['color'] }}" id="co-{{ Str::slug($c['name']) }}">
                        <label for="co-{{ Str::slug($c['name']) }}" class="cpick" style="background:{{ $c['color'] }}"></label>
                    </div>
                </div>
            @empty
                <p style="font-size:13px;color:var(--tx3);text-align:center;padding:24px 0">No companies found</p>
            @endforelse
            </div>
        </div>
    </div>

   {{-- ══════════ EVENT MODAL ══════════ --}}
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="m-strip">
                    <div class="m-strip-top">
                        <div style="flex:1;min-width:0">
                            <span class="m-pill job" id="m-pill">JOB</span>
                            <div class="m-title" id="m-title">Event Details</div>
                            <div class="m-date" id="m-date">—</div>
                        </div>
                        <button type="button" class="btn-close ms-3 mt-1" data-bs-dismiss="modal" style="flex-shrink:0"></button>
                    </div>
                    <div class="m-tabs" id="m-tabs">
                        <button class="m-tab on" data-p="info"><i class="fas fa-table-list"></i> Info</button>
                        <button class="m-tab" data-p="files"><i class="fas fa-paperclip"></i> Files</button>
                        <button class="m-tab" data-p="notes">
                            <i class="fas fa-message"></i> Notes
                            <span class="m-tab-badge" id="notes-badge">0</span>
                        </button>
                        <button class="m-tab" data-p="crew"><i class="fas fa-people-group"></i> Crew</button>
                        <button class="m-tab" data-p="payment"><i class="fas fa-receipt"></i> Payment</button>
                        <button class="m-tab" data-p="payout" style="display:none"><i class="fas fa-dollar-sign"></i> Payout</button>
                    </div>
                </div>

                <div class="m-body">
                    <div class="m-panel on" id="p-info">
                        <div id="info-content">
                            <div class="sk sk-line" style="width:60%"></div>
                            <div class="sk sk-line" style="width:80%"></div>
                            <div class="sk sk-line" style="width:50%"></div>
                        </div>
                    </div>

                    <div class="m-panel" id="p-files">
                        <div id="files-content">
                            <div class="empty-state"><i class="fas fa-folder-open"></i><p>No files attached</p></div>
                        </div>
                    </div>

                    <div class="m-panel" id="p-notes">
                        <div class="notes-feed" id="notes-feed">
                            <div class="empty-state"><i class="fas fa-comments"></i><p>No notes yet</p></div>
                        </div>
                        <div class="note-compose">
                            <textarea id="note-input" rows="2" placeholder="Write a note…"></textarea>
                            <div class="note-compose-footer">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <label for="note-img-input" style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;font-size:11px;color:var(--tx3);padding:3px 7px;border:1px solid var(--bd);border-radius:5px;transition:all .15s;"
                                           onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                                           onmouseout="this.style.borderColor='var(--bd)';this.style.color='var(--tx3)'">
                                        <i class="fas fa-image" style="font-size:11px"></i> Photo
                                    </label>
                                    <input type="file" id="note-img-input" accept="image/*" style="display:none">
                                    <span id="note-img-preview-name" style="font-size:11px;color:var(--tx3);max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span class="note-hint"><span class="kbd">↵ Enter</span> to send</span>
                                    <button id="btn-note" class="btn btn-primary btn-sm">
                                        <i class="fas fa-paper-plane" style="font-size:10px"></i> Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="m-panel" id="p-crew">
                        <div class="p-section">
                            <div class="p-heading">Currently assigned</div>
                            <div id="crew-assigned-card" class="crew-unassigned-card">
                                <i class="fas fa-user-slash" style="font-size:18px;opacity:.35"></i>
                                <span style="font-size:12.5px">No crew assigned</span>
                            </div>
                        </div>
                        <div class="p-section">
                            <div class="p-heading">Change assignment</div>
                            <div class="crew-sel-wrap">
                                <select id="select-crew">
                                    <option value="">— Select a crew member —</option>
                                    @foreach($crews as $crew)
                                        <option value="{{ $crew->id }}">{{ $crew->name }} ({{ $crew->company }})</option>
                                    @endforeach
                                </select>
                                <span class="crew-sel-arrow">▾</span>
                            </div>
                            <button id="btn-assign" class="btn btn-success" style="width:100%;justify-content:center">
                                <i class="fas fa-floppy-disk" style="font-size:12px"></i> Save assignment
                            </button>
                            <div id="assign-feedback" style="margin-top:8px"></div>
                        </div>
                        <div class="p-section">
                            <div class="p-heading">Assignment history <span id="hist-count">0</span></div>
                            <div id="crew-history">
                                <div class="empty-state" style="padding:16px 0"><p>No previous assignments</p></div>
                            </div>
                        </div>
                    </div>

                    {{-- ── PAYMENT panel (job / emergency) ── --}}
                    <div class="m-panel" id="p-payment">

                        {{-- Hero invoice payout --}}
                        <div class="pay-hero no-invoice" id="pay-hero">
                            <div class="pay-hero-ico"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div class="pay-hero-info">
                                <div class="pay-hero-lbl">Invoice Payout</div>
                                <div class="pay-hero-val" id="pay-payout-total">—</div>
                                <div class="pay-hero-sub" id="pay-payout-sub">No invoice linked</div>
                            </div>
                        </div>

                        {{-- Status summary --}}
                        <div id="pay-summary" class="pay-summary unpaid">
                            <div class="pay-summary-icon"><i class="fas fa-clock"></i></div>
                            <div class="pay-summary-info">
                                <div class="pay-summary-status">Payment pending</div>
                                <div class="pay-summary-detail">No amount recorded yet</div>
                            </div>
                        </div>

                        {{-- Form --}}
                        <div class="p-section">
                            <div class="p-heading">Payment details</div>
                            <div class="pay-grid">
                                <div class="pay-field">
                                    <label class="pay-label">Amount ($)</label>
                                    <input type="number" step="0.01" id="pay-amount" class="pay-input" placeholder="0.00">
                                </div>
                                <div class="pay-field">
                                    <label class="pay-label">Payment date</label>
                                    <input type="date" id="pay-date" class="pay-input">
                                </div>
                                <div class="pay-field s2" style="display:none">
                                    <label class="pay-label">Status</label>
                                    <div class="pay-status-toggle">
                                      
                                            <input type="hidden" id="pay-status" value="paid">  {{-- ← siempre "paid" --}}

                                    </div>
                                    <input type="hidden" id="pay-status" value="unpaid">
                                </div>
                            </div>
                        </div>

                        {{-- Receipt --}}
                        <div class="pay-receipt-section">
                            <div class="pay-receipt-header">
                                <span class="pay-receipt-title"><i class="fas fa-file-pdf"></i> Receipt / Document</span>
                            </div>
                            <div class="pay-receipt-body">
                                <div id="pay-receipt-existing" style="display:none">
                                    <div class="pay-receipt-file">
                                        <div class="pay-receipt-file-ico"><i class="fas fa-file-pdf"></i></div>
                                        <div class="pay-receipt-file-info">
                                            <div class="pay-receipt-file-name" id="pay-receipt-filename">receipt.pdf</div>
                                            <div class="pay-receipt-file-sub">Current receipt</div>
                                        </div>
                                        <div class="pay-receipt-actions">
                                            <a id="pay-receipt-view" href="#" target="_blank" class="btn-icon">
                                                <i class="fas fa-eye" style="font-size:11px"></i> View
                                            </a>
                                            <button type="button" class="btn-icon" style="color:var(--red);border-color:var(--red-bd);" onclick="removePayReceipt()">
                                                <i class="fas fa-trash" style="font-size:11px"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <label class="pay-receipt-upload">
                                    <input type="file" id="pay-receipt" accept=".pdf,.jpg,.jpeg,.png" onchange="previewReceipt(this,'pay-receipt-upload-lbl')">
                                    <i class="fas fa-cloud-arrow-up" style="font-size:20px;color:var(--tx3)"></i>
                                    <span class="pay-receipt-upload-lbl" id="pay-receipt-upload-lbl">Click to upload receipt (PDF or image)</span>
                                </label>
                            </div>
                        </div>

                        <button class="pay-save-btn" id="btn-pay">
                            <i class="fas fa-floppy-disk" style="font-size:13px"></i> Save payment
                        </button>
                        <div id="pay-feedback"></div>
                    </div>

                    {{-- ── PAYOUT panel (repair) ── --}}
                    <div class="m-panel" id="p-payout">

                        {{-- Hero invoice payout --}}
                        <div class="pay-hero no-invoice" id="rp-pay-hero">
                            <div class="pay-hero-ico"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div class="pay-hero-info">
                                <div class="pay-hero-lbl">Invoice Payout</div>
                                <div class="pay-hero-val" id="rp-payout-total">—</div>
                                <div class="pay-hero-sub" id="rp-payout-sub">No invoice linked</div>
                            </div>
                        </div>

                        {{-- Status summary --}}
                        <div id="rp-pay-summary" class="pay-summary unpaid">
                            <div class="pay-summary-icon"><i class="fas fa-clock"></i></div>
                            <div class="pay-summary-info">
                                <div class="pay-summary-status">Payment pending</div>
                                <div class="pay-summary-detail">No amount recorded yet</div>
                            </div>
                        </div>

                        {{-- Form --}}
                        <div class="p-section">
                            <div class="p-heading">Payment details</div>
                            <div class="pay-grid">
                                <div class="pay-field">
                                    <label class="pay-label">Amount ($)</label>
                                    <input type="number" step="0.01" id="rp-pay-amount" class="pay-input" placeholder="0.00">
                                </div>
                                <div class="pay-field">
                                    <label class="pay-label">Payment date</label>
                                    <input type="date" id="rp-pay-date" class="pay-input">
                                </div>
                                <div class="pay-field s2">
                                    <label class="pay-label">Status</label>
                                    <div class="pay-status-toggle">
                                        <button type="button" class="pay-status-pill active-unpaid" id="rp-pill-unpaid" onclick="setPayStatus('rp','unpaid')">
                                            <i class="fas fa-clock" style="font-size:11px"></i> Unpaid
                                        </button>
                                        <button type="button" class="pay-status-pill" id="rp-pill-paid" onclick="setPayStatus('rp','paid')">
                                            <i class="fas fa-circle-check" style="font-size:11px"></i> Paid
                                        </button>
                                    </div>
                                    <input type="hidden" id="rp-pay-status" value="unpaid">
                                </div>
                            </div>
                        </div>

                        {{-- Receipt --}}
                        <div class="pay-receipt-section">
                            <div class="pay-receipt-header">
                                <span class="pay-receipt-title"><i class="fas fa-file-pdf"></i> Receipt / Document</span>
                            </div>
                            <div class="pay-receipt-body">
                                <div id="rp-pay-receipt-existing" style="display:none">
                                    <div class="pay-receipt-file">
                                        <div class="pay-receipt-file-ico"><i class="fas fa-file-pdf"></i></div>
                                        <div class="pay-receipt-file-info">
                                            <div class="pay-receipt-file-name" id="rp-pay-receipt-filename">receipt.pdf</div>
                                            <div class="pay-receipt-file-sub">Current receipt</div>
                                        </div>
                                        <div class="pay-receipt-actions">
                                            <a id="rp-pay-receipt-view" href="#" target="_blank" class="btn-icon">
                                                <i class="fas fa-eye" style="font-size:11px"></i> View
                                            </a>
                                            <button type="button" class="btn-icon" style="color:var(--red);border-color:var(--red-bd);" onclick="removeRpReceipt()">
                                                <i class="fas fa-trash" style="font-size:11px"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <label class="pay-receipt-upload">
                                    <input type="file" id="rp-pay-receipt" accept=".pdf,.jpg,.jpeg,.png" onchange="previewReceipt(this,'rp-receipt-upload-lbl')">
                                    <i class="fas fa-cloud-arrow-up" style="font-size:20px;color:var(--tx3)"></i>
                                    <span class="pay-receipt-upload-lbl" id="rp-receipt-upload-lbl">Click to upload receipt (PDF or image)</span>
                                </label>
                            </div>
                        </div>

                        <button class="pay-save-btn" id="btn-rp-pay">
                            <i class="fas fa-floppy-disk" style="font-size:13px"></i> Save payment
                        </button>
                        <div id="rp-pay-feedback"></div>
                    </div>
                </div>

                <div class="m-footer">
                    <a id="m-open-photos" href="#" target="_blank" class="btn btn-photos btn-sm" style="display:none">
                        <i class="fas fa-images" style="font-size:11px"></i> View Photos
                    </a>
                    <a id="m-new-invoice" href="#" target="_blank" class="btn btn-primary btn-sm" style="display:none">
                        <i class="fas fa-file-invoice-dollar" style="font-size:11px"></i> New Invoice
                    </a>
                    <a id="m-open-full" href="#" target="_blank" class="btn btn-ghost btn-sm" style="display:none">
                        <i class="fas fa-arrow-up-right-from-square" style="font-size:11px"></i> Open full page
                    </a>
                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-xmark" style="font-size:11px"></i> Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- PENDING PAYMENTS --}}
    <div class="pp-section" id="pp-section" style="margin-top:24px">
        <div class="pp-header">
            <div class="pp-header-left">
                <div class="pp-icon"><i class="fas fa-triangle-exclamation"></i></div>
                <div><div class="pp-title">Pending Payments</div><div class="pp-sub">Completed jobs awaiting payment</div></div>
            </div>
            <span class="pp-badge" id="pp-count">—</span>
        </div>
        <div class="pp-search-wrap">
            <i class="fas fa-search pp-search-ico"></i>
            <input type="text" id="pp-search" class="pp-search-input" placeholder="Search by job #, company or crew…" oninput="filterPPTable(this.value)">
            <button class="pp-search-clear" id="pp-search-clear" style="display:none" onclick="clearPPSearch()"><i class="fas fa-times"></i></button>
        </div>
        <div id="pp-loading" style="padding:20px;text-align:center;color:var(--tx3);font-size:13px"><i class="fas fa-spinner fa-spin"></i> Loading…</div>
        <div id="pp-empty" style="display:none" class="pp-empty"><i class="fas fa-circle-check" style="font-size:22px;color:var(--green);margin-bottom:8px;display:block"></i><p>All completed jobs have been paid. 🎉</p></div>
        <div id="pp-list" style="display:none">
            <table class="pp-table">
                <thead><tr><th>Job #</th><th>Company</th><th>Crew</th><th>Date</th><th>Amount</th><th>Type</th><th></th></tr></thead>
                <tbody id="pp-tbody"></tbody>
            </table>
        </div>
    </div>

    {{-- DEEP LINK OFFCANVAS --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="deepOffcanvas" style="width:980px;max-width:100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="deepTitle">Detail</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <iframe id="deepFrame" src="about:blank" style="border:0;width:100%;height:calc(100vh - 64px)"></iframe>
        </div>
    </div>

</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const CSRF       = '{{ csrf_token() }}';
    const jobBase    = @json(\Illuminate\Support\Facades\Route::has('superadmin.job_requests.show') ? route('superadmin.job_requests.show',['job_request'=>'__ID__']) : url('/superadmin/job_requests/__ID__'));
    const emergBase  = @json(\Illuminate\Support\Facades\Route::has('superadmin.emergencies.show') ? route('superadmin.emergencies.show',['emergency'=>'__ID__']) : url('/superadmin/emergencies/__ID__'));
    const repairBase = @json(url('/superadmin/repair-tickets/__ID__/edit'));
    const photosBase = @json(url('/superadmin/photos/__TIPO__/__ID__/view'));

    const deepUrl    = (t,id) => (t==='job'?jobBase:t==='emergency'?emergBase:repairBase).replace('__ID__',encodeURIComponent(id));
    const photosUrl  = (t,id) => photosBase.replace('__TIPO__', t==='job'?'job_request':t==='emergency'?'emergency':'repair').replace('__ID__',id);
    const invoiceUrl = (t,id) => `/superadmin/invoices/create?type=${t}&id=${id}`;

    let ev            = { type:null, id:null };
    let abort         = new AbortController();
    let stagedFiles   = [];
    let stagedNoteImg = null;

    // ══════════════════════════════════════════════════════════
    // RECEIPT — preview, eliminar job/emergency, eliminar repair
    // ══════════════════════════════════════════════════════════
    window.previewReceipt = function(input, lblId) {
        const f = input.files[0];
        const lbl = document.getElementById(lblId);
        if (f && lbl) { lbl.textContent = '✓ ' + f.name; lbl.style.color = 'var(--blue)'; }
    };

    window.removePayReceipt = function() {
        Swal.fire({ title:'Remove receipt?', text:'It will be deleted when you save.', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#334155', confirmButtonText:'Remove', cancelButtonText:'Cancel', reverseButtons:true })
        .then(result => {
            if (!result.isConfirmed) return;
            document.getElementById('pay-receipt-existing').style.display = 'none';
            document.getElementById('pay-receipt').dataset.removeExisting = '1';
            const lbl = document.getElementById('pay-receipt-upload-lbl');
            if (lbl) { lbl.textContent = 'Click to upload new receipt'; lbl.style.color = ''; }
        });
    };

    window.removeRpReceipt = function() {
        Swal.fire({ title:'Remove receipt?', text:'It will be deleted when you save.', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#334155', confirmButtonText:'Remove', cancelButtonText:'Cancel', reverseButtons:true })
        .then(result => {
            if (!result.isConfirmed) return;
            document.getElementById('rp-pay-receipt-existing').style.display = 'none';
            document.getElementById('rp-pay-receipt').dataset.removeExisting = '1';
            const lbl = document.getElementById('rp-receipt-upload-lbl');
            if (lbl) { lbl.textContent = 'Click to upload new receipt'; lbl.style.color = ''; }
        });
    };

    // ══════════════════════════════════════════════════════════
    // HERO PAYOUT BANNER
    // ══════════════════════════════════════════════════════════
    const updatePayHero = (heroId, valId, subId, d) => {
        const hero = document.getElementById(heroId);
        if (!hero) return;
        if (d.payout_total > 0) {
            hero.classList.remove('no-invoice');
            document.getElementById(valId).textContent = `$${parseFloat(d.payout_total).toFixed(2)}`;
            document.getElementById(subId).textContent = d.payout_date ? `Payout date: ${fmtD(d.payout_date)}` : 'Invoice payout ready';
        } else {
            hero.classList.add('no-invoice');
            document.getElementById(valId).textContent = '—';
            document.getElementById(subId).textContent = 'No invoice linked';
        }
    };

    // ══════════════════════════════════════════════════════════
    // INVOICE SMART BUTTON
    // ══════════════════════════════════════════════════════════
    const loadInvoiceLink = async (type, id) => {
        const btn = document.getElementById('m-new-invoice');
        btn.style.display    = 'inline-flex';
        btn.style.background = '';
        btn.href             = invoiceUrl(type, id);
        btn.innerHTML        = '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i>';
        try {
            const r    = await fetch(`/superadmin/invoices/linked?type=${type}&id=${id}`, { headers: {'X-CSRF-TOKEN':CSRF} });
            const data = await r.json();
            if (data && data.id) {
                btn.href             = `/superadmin/invoices/${data.id}/prepare`;
                btn.innerHTML        = `<i class="fas fa-file-invoice-dollar" style="font-size:11px"></i> ${data.invoice_number}`;
                btn.style.background = 'var(--green)';
            } else {
                btn.innerHTML        = '<i class="fas fa-file-invoice-dollar" style="font-size:11px"></i> New Invoice';
                btn.style.background = '';
            }
        } catch(e) {
            btn.innerHTML        = '<i class="fas fa-file-invoice-dollar" style="font-size:11px"></i> New Invoice';
            btn.style.background = '';
        }
    };

    // ══════════════════════════════════════════════════════════
    // CHIP FILTER
    // ══════════════════════════════════════════════════════════
    const activeTypes = new Set(['job','emergency','repair']);
    window.toggleChip = function(chip) {
        const t = chip.dataset.type;
        if (activeTypes.has(t)) { activeTypes.delete(t); chip.classList.add('off'); }
        else { activeTypes.add(t); chip.classList.remove('off'); }
        applyCalFilter(document.getElementById('cal-search').value);
    };
    function applyCalFilter(q) {
        const val   = q.trim().toLowerCase();
        const clear = document.getElementById('cal-search-clear');
        const count = document.getElementById('cal-search-count');
        if (clear) clear.style.display = val ? 'flex' : 'none';
        let shown = 0;
        cal.getEvents().forEach(e => {
            const type      = e.extendedProps.type || '';
            const typeMatch = activeTypes.has(type);
            const textMatch = !val
                || e.title.toLowerCase().includes(val)
                || (e.extendedProps.companyName||'').toLowerCase().includes(val)
                || (e.extendedProps.crewName||'').toLowerCase().includes(val)
                || (e.extendedProps.ref_number||'').toLowerCase().includes(val);
            const show = typeMatch && textMatch;
            e.setProp('display', show ? 'auto' : 'none');
            if (show) shown++;
        });
        count.textContent   = `${shown} result${shown !== 1 ? 's' : ''}`;
        count.style.display = (val || activeTypes.size < 3) ? 'inline-flex' : 'none';
    }
    window.filterCalEvents = q => applyCalFilter(q);
    window.clearCalSearch  = () => { const inp = document.getElementById('cal-search'); if (inp) { inp.value = ''; applyCalFilter(''); } };

    // ══════════════════════════════════════════════════════════
    // TABS
    // ══════════════════════════════════════════════════════════
    document.querySelectorAll('.m-tab').forEach(t => t.addEventListener('click', () => {
        document.querySelectorAll('.m-tab').forEach(x => x.classList.remove('on'));
        document.querySelectorAll('.m-panel').forEach(x => x.classList.remove('on'));
        t.classList.add('on');
        document.getElementById('p-' + t.dataset.p).classList.add('on');
    }));

    const resetTabs = () => {
        document.querySelectorAll('.m-tab').forEach(x => {
            x.classList.remove('on');
            x.classList.remove('repair-mode');
            x.style.display = '';
        });
        document.querySelectorAll('.m-panel').forEach(x => x.classList.remove('on'));
        document.querySelector('[data-p="info"]').classList.add('on');
        document.getElementById('p-info').classList.add('on');
        document.getElementById('m-open-photos').style.display    = 'none';
        document.getElementById('m-open-full').style.display      = 'none';
        document.getElementById('m-new-invoice').style.display    = 'none';
        document.getElementById('m-new-invoice').style.background = '';
        document.querySelector('[data-p="payout"]').style.display  = 'none';
        document.querySelector('[data-p="payment"]').style.display = '';
    };

    // ══════════════════════════════════════════════════════════
    // UTILS
    // ══════════════════════════════════════════════════════════
    const extOf    = u => { try { const pp = new URL(u, location.origin).pathname.split('/').pop().toLowerCase().split('.'); return pp.length > 1 ? pp.pop() : ''; } catch { return ''; } };
    const isImgExt = e => ['jpg','jpeg','png','webp','gif','svg'].includes(e);
    const fmtD = s => { if (!s) return '—'; const d = new Date(s.includes('T') ? s : s + 'T00:00:00'); return isNaN(d) ? '—' : d.toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric'}); };    const fmtDT    = s => { if (!s) return ''; const d = new Date(s); return isNaN(d) ? '' : d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'}); };
    const vColor   = c => { if (!c) return '#2563eb'; if (!c.startsWith('#')) c = '#' + c; return /^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$/.test(c) ? c : '#2563eb'; };
    const ini      = n => n ? n.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2) : '?';
    const normUrl  = url => {
        if (!url) return '';
        if (url.startsWith('http'))      return url.replace(/^https?:\/\/[^\/]+/, window.location.origin);
        if (url.startsWith('/storage/')) return url;
        if (url.startsWith('storage/'))  return '/' + url;
        return '/storage/' + url;
    };

    // ══════════════════════════════════════════════════════════
    // REPAIR — Info tab
    // ══════════════════════════════════════════════════════════
    const renderRepairInfo = (xp) => {
        const el = document.getElementById('info-content');
        const statusLabel = { pending:'Scheduled', en_process:'In Progress', completed:'Completed' };
        const statusColor = {
            pending:    { bg:'#fffbeb', color:'#b45309', bd:'#fde68a' },
            en_process: { bg:'#f5f3ff', color:'#6d28d9', bd:'#ddd6fe' },
            completed:  { bg:'#f0fdf4', color:'#15803d', bd:'#bbf7d0' },
        };
        const sc      = statusColor[xp.status] || { bg:'var(--bg)', color:'var(--tx3)', bd:'var(--bd)' };
        const editUrl = repairBase.replace('__ID__', xp.repair_id);
        el.innerHTML = `
        <div class="p-section">
            <div class="p-heading">Repair Ticket</div>
            <div class="fi-grid">
                <div class="fi-cell"><div class="fi-key">Ticket #</div><div class="fi-val" style="font-weight:700;color:var(--cyan)">#RT-${String(xp.repair_id).padStart(4,'0')}</div></div>
                <div class="fi-cell"><div class="fi-key">Status</div><div class="fi-val"><span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;text-transform:uppercase;letter-spacing:.4px;background:${sc.bg};color:${sc.color};border:1px solid ${sc.bd}">${statusLabel[xp.status] || xp.status}</span></div></div>
                <div class="fi-cell"><div class="fi-key">Company</div><div class="fi-val">${xp.companyName||'—'}</div></div>
                <div class="fi-cell s2"><div class="fi-key">Repair Date</div><div class="fi-val">${fmtD(xp.repair_date||'')}</div></div>
                <div class="fi-cell s3"><div class="fi-key">Damage Description</div><div class="fi-val">${xp.description||'—'}</div></div>
            </div>
        </div>
        <div class="p-section">
            <div class="p-heading">Actions</div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="${editUrl}" target="_blank" style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;padding:7px 14px;border-radius:var(--r);background:var(--surf);color:var(--tx2);border:1px solid var(--bd-md);text-decoration:none;transition:all .15s;" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='var(--surf)'">
                    <i class="fas fa-pencil" style="font-size:11px"></i> Edit Ticket
                </a>
            </div>
        </div>`;
        renderCrew({ crew_id:xp.crew_id||null, crew_name:xp.crew_name||null, crew_company:'', crew_history:[] });
    };

    // ══════════════════════════════════════════════════════════
    // REPAIR — Files tab
    // ══════════════════════════════════════════════════════════
    const renderRepairFiles = (filesAdmin, filesCrew, repairId) => {
        const el = document.getElementById('files-content');
        el.innerHTML = '';
        stagedFiles  = [];

        const buildRow = (url, withDelete = false, index = 0) => {
            const fullUrl = normUrl(url);
            const e    = extOf(fullUrl);
            const name = decodeURIComponent(fullUrl.split('/').pop().split('?')[0]);
            const ico  = isImgExt(e) ? 'img' : e === 'pdf' ? 'pdf' : 'other';
            const icoI = isImgExt(e) ? 'fa-image' : e === 'pdf' ? 'fa-file-pdf' : 'fa-file';
            const row  = document.createElement('div');
            row.className         = 'file-row';
            row.style.borderColor = withDelete ? 'var(--bd)' : 'var(--cyan-bd)';
            row.style.background  = withDelete ? 'var(--surf)' : 'var(--cyan-bg)';
            row.innerHTML = `
                <div class="file-ico ${ico}"><i class="fas ${icoI}"></i></div>
                <div style="flex:1;min-width:0"><div class="file-name">${name}</div><div class="file-ext">${e||'file'}</div></div>
                <div class="d-flex gap-1">
                    <a href="${fullUrl}" target="_blank" class="btn-icon"><i class="fas fa-eye" style="font-size:11px"></i> View</a>
                    <a href="${fullUrl}" download class="btn-icon"><i class="fas fa-download" style="font-size:11px"></i></a>
                    ${withDelete ? `<button class="btn-icon del-file-btn" style="color:var(--red)" data-index="${index}"><i class="fas fa-trash" style="font-size:11px"></i></button>` : ''}
                </div>`;
            if (withDelete) row.querySelector('.del-file-btn').addEventListener('click', () => confirmDeleteFile(repairId, index, row));
            return row;
        };

        const adminSec = document.createElement('div');
        adminSec.className = 'p-section';
        adminSec.innerHTML = `<div class="p-heading"><i class="fas fa-folder-open" style="color:var(--amber);margin-right:4px"></i>Support Files — Admin <span>${filesAdmin.length}</span><span style="font-size:9px;font-weight:400;text-transform:none;letter-spacing:0;color:var(--tx3);margin-left:4px;">visible to crew in mobile app</span></div>`;
        if (filesAdmin.length) { filesAdmin.forEach((url, i) => adminSec.appendChild(buildRow(url, true, i))); }
        else { adminSec.innerHTML += `<div style="font-size:12px;color:var(--tx3);font-style:italic;padding:8px 0">No files uploaded yet.</div>`; }
        el.appendChild(adminSec);

        const uploadSec = document.createElement('div');
        uploadSec.className = 'p-section';
        uploadSec.innerHTML = `
            <div class="p-heading"><i class="fas fa-cloud-upload-alt" style="margin-right:4px"></i> Upload Support Files</div>
            <label for="rp-file-input" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 20px;border-radius:var(--r-lg);border:1.5px dashed var(--bd-md);background:var(--bg);cursor:pointer;transition:all .15s;margin-bottom:10px;font-size:13px;font-weight:500;color:var(--tx2);" onmouseover="this.style.borderColor='var(--cyan)';this.style.background='var(--cyan-bg)'" onmouseout="this.style.borderColor='var(--bd-md)';this.style.background='var(--bg)'">
                <i class="fas fa-folder-open" style="font-size:16px;color:var(--cyan);"></i> Click to select files
                <span style="font-size:11px;color:var(--tx3);font-weight:400;">(photos, PDF, Word, Excel)</span>
            </label>
            <input type="file" id="rp-file-input" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;">
            <div class="rp-file-list" id="rp-file-list"></div>
            <div style="display:flex;align-items:center;justify-content:flex-end;gap:10px;margin-top:10px;">
                <div id="rp-upload-feedback"></div>
                <button id="btn-rp-upload" class="btn btn-cyan btn-sm" style="display:none;"><i class="fas fa-cloud-upload-alt" style="font-size:11px"></i> Upload &amp; sync to mobile</button>
            </div>`;
        el.appendChild(uploadSec);

        const fileInp   = document.getElementById('rp-file-input');
        const fileList  = document.getElementById('rp-file-list');
        const uploadBtn = document.getElementById('btn-rp-upload');
        fileInp.addEventListener('change', () => {
            Array.from(fileInp.files).forEach(f => {
                stagedFiles.push(f);
                const entry = document.createElement('div');
                entry.className = 'rp-file-entry';
                entry.innerHTML = `<i class="fas fa-file" style="font-size:12px;color:var(--tx3)"></i><span class="rp-file-entry-name">${f.name}</span><span style="font-size:11px;color:var(--tx3);flex-shrink:0">${(f.size/1024).toFixed(0)} KB</span><button class="btn-remove-staged"><i class="fas fa-times"></i></button>`;
                entry.querySelector('.btn-remove-staged').addEventListener('click', () => { stagedFiles = stagedFiles.filter(x => x !== f); entry.remove(); if (!stagedFiles.length) uploadBtn.style.display = 'none'; });
                fileList.appendChild(entry);
            });
            fileInp.value = '';
            uploadBtn.style.display = stagedFiles.length ? 'inline-flex' : 'none';
        });
        uploadBtn.addEventListener('click', () => uploadRepairFiles(repairId));
    };

    const loadRepairFiles = async (repairId) => {
        const el = document.getElementById('files-content');
        if (el) el.innerHTML = '<div class="sk sk-line" style="width:60%"></div><div class="sk sk-line" style="width:75%"></div><div class="sk sk-line" style="width:45%"></div>';
        try {
            const r    = await fetch(`/api/fotos/repair/${repairId}`, { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF } });
            const data = await r.json();
            renderRepairFiles((data.admin||[]).map(f => f.url ?? f), (data.crew||[]).map(f => f.url ?? f), repairId);
        } catch(e) {
            const el2 = document.getElementById('files-content');
            if (el2) el2.innerHTML = '<div class="empty-state"><i class="fas fa-triangle-exclamation"></i><p>Error loading files.</p></div>';
        }
    };

    const uploadRepairFiles = async (repairId) => {
        if (!stagedFiles.length) return;
        const btn = document.getElementById('btn-rp-upload'), orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:11px"></i> Uploading…'; btn.disabled = true;
        try {
            const fd = new FormData();
            fd.append('_token', CSRF);
            stagedFiles.forEach(f => fd.append('photos[]', f));
            const r    = await fetch(`/superadmin/repair-tickets/${repairId}/upload-photos`, { method:'POST', body:fd });
            const data = await r.json();
            if (r.ok && data.success) {
                stagedFiles = [];
                await loadRepairFiles(repairId);
                const fb2 = document.getElementById('rp-upload-feedback');
                if (fb2) { fb2.innerHTML = '<span class="rp-feedback ok"><i class="fas fa-check-circle"></i> Uploaded!</span>'; setTimeout(() => { if (fb2) fb2.innerHTML = ''; }, 3000); }
                cal.refetchEvents();
            } else {
                const fb = document.getElementById('rp-upload-feedback');
                if (fb) fb.innerHTML = `<span class="rp-feedback err"><i class="fas fa-xmark-circle"></i> ${data.message||'Upload failed'}</span>`;
                btn.innerHTML = orig; btn.disabled = false;
            }
        } catch(e) {
            const fb3 = document.getElementById('rp-upload-feedback');
            if (fb3) fb3.innerHTML = '<span class="rp-feedback err">Connection error</span>';
            btn.innerHTML = orig; btn.disabled = false;
        }
    };

    const confirmDeleteFile = (repairId, index, rowEl) => {
        Swal.fire({ title:'Delete this file?', text:'This cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#334155', confirmButtonText:'Delete', cancelButtonText:'Cancel', reverseButtons:true })
        .then(async result => {
            if (!result.isConfirmed) return;
            try {
                const r    = await fetch(`/superadmin/repair-tickets/${repairId}/photos/${index}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} });
                const data = await r.json();
                if (data.success) {
                    rowEl.style.transition = 'opacity .2s,transform .2s'; rowEl.style.opacity = '0'; rowEl.style.transform = 'translateX(10px)';
                    setTimeout(async () => { rowEl.remove(); await loadRepairFiles(repairId); }, 200);
                    Swal.fire({ icon:'success', title:'Deleted', timer:1200, showConfirmButton:false });
                } else { Swal.fire('Error', data.message || 'Something went wrong.', 'error'); }
            } catch(e) { Swal.fire('Error', 'Connection error', 'error'); }
        });
    };

    const loadRepairNotes = async (repairId) => {
        document.getElementById('notes-badge').textContent = '…';
        const feed = document.getElementById('notes-feed');
        feed.innerHTML = '<div style="padding:16px;text-align:center"><i class="fas fa-spinner fa-spin" style="color:var(--tx3)"></i></div>';
        try {
            const r    = await fetch(`{{ url('superadmin/calendar/notes') }}?type=repair&id=${repairId}`, { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF } });
            const data = await r.json();
            renderNotes(Array.isArray(data) ? data : (data.notes || []));
        } catch(e) {
            feed.innerHTML = '<div class="empty-state"><i class="fas fa-comments"></i><p>Error loading chat.</p></div>';
            document.getElementById('notes-badge').textContent = '0';
        }
    };

    // ══════════════════════════════════════════════════════════
    // REPAIR — Payout tab
    // ══════════════════════════════════════════════════════════
    const loadRepairPayout = async (repairId) => {
        try {
            const r    = await fetch(`{{ url('superadmin/calendar/event') }}/repair/${repairId}`, { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF } });
            const json = await r.json();
            renderRepairPayout(json.data ?? json);
        } catch(e) { console.error('Error loading repair payout:', e); }
    };

    const renderRepairPayout = (d) => {
        const amount = (d.payout_total > 0) ? d.payout_total : (d.amount ?? '');

        // Hero banner
        updatePayHero('rp-pay-hero', 'rp-payout-total', 'rp-payout-sub', d);

        document.getElementById('rp-pay-amount').value = amount ? parseFloat(amount).toFixed(2) : '';
        document.getElementById('rp-pay-date').value   = d.payout_date ?? d.payment_date ?? '';
        document.getElementById('rp-pay-status').value = 'paid';
        document.getElementById('rp-pay-feedback').innerHTML = '';

        // Summary — siempre paid si hay amount
        const bar = document.getElementById('rp-pay-summary');
        if (amount) {
            bar.className = 'pay-summary paid';
            bar.innerHTML = `<div class="pay-summary-icon"><i class="fas fa-circle-check"></i></div><div class="pay-summary-info"><div class="pay-summary-status">Payment confirmed</div><div class="pay-summary-detail">$${parseFloat(amount).toFixed(2)} · ${fmtD(d.payout_date ?? d.payment_date)}</div></div>`;
        } else {
            bar.className = 'pay-summary unpaid';
            bar.innerHTML = `<div class="pay-summary-icon"><i class="fas fa-clock"></i></div><div class="pay-summary-info"><div class="pay-summary-status">Payment pending</div><div class="pay-summary-detail">No amount recorded yet</div></div>`;
        }

        // Receipt
        const existing   = document.getElementById('rp-pay-receipt-existing');
        const receiptInp = document.getElementById('rp-pay-receipt');
        if (receiptInp) receiptInp.dataset.removeExisting = '';
        if (d.payment_receipt_url) {
            existing.style.display = 'block';
            const fname = document.getElementById('rp-pay-receipt-filename');
            const fview = document.getElementById('rp-pay-receipt-view');
            if (fname) fname.textContent = d.payment_receipt_url.split('/').pop() || 'receipt.pdf';
            if (fview) fview.href = normUrl(d.payment_receipt_url);
        } else {
            existing.style.display = 'none';
        }
        const lbl = document.getElementById('rp-receipt-upload-lbl');
        if (lbl) { lbl.textContent = 'Click to upload receipt (PDF or image)'; lbl.style.color = ''; }
    };

    document.getElementById('btn-rp-pay').addEventListener('click', async () => {
        if (!ev.id || ev.type !== 'repair') return;
        const btn = document.getElementById('btn-rp-pay'), fb = document.getElementById('rp-pay-feedback');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:11px"></i> Saving…'; btn.disabled = true;
        const fd = new FormData();
        fd.append('_token',         CSRF);
        fd.append('amount',         document.getElementById('rp-pay-amount').value);
        fd.append('payment_date',   document.getElementById('rp-pay-date').value);
        fd.append('payment_status', 'paid');
        const receiptInp = document.getElementById('rp-pay-receipt');
        if (receiptInp.dataset.removeExisting === '1') fd.append('remove_receipt', '1');
        const file = receiptInp.files[0];
        if (file) fd.append('payment_receipt', file);
        try {
            const r = await fetch(`/superadmin/repair-tickets/${ev.id}/payment`, { method:'POST', body:fd });
            if (r.ok) {
                fb.innerHTML = '<div class="pay-feedback ok"><i class="fas fa-check-circle" style="font-size:12px"></i> Payment saved.</div>';
                setTimeout(() => { if (fb) fb.innerHTML = ''; }, 3000);
                loadRepairPayout(ev.id);
            } else { fb.innerHTML = '<div class="pay-feedback err"><i class="fas fa-xmark-circle"></i> Error saving payment.</div>'; }
        } catch(e) { fb.innerHTML = '<div class="pay-feedback err">Connection error.</div>'; }
        finally { btn.innerHTML = '<i class="fas fa-floppy-disk" style="font-size:12px"></i> Save payment'; btn.disabled = false; }
    });

    // ══════════════════════════════════════════════════════════
    // JOB / EMERGENCY renders
    // ══════════════════════════════════════════════════════════
    const renderInfo = (d, type) => {
        const el = document.getElementById('info-content');
        if (type === 'job') {
            el.innerHTML = `<div class="p-section"><div class="p-heading">Company &amp; request</div><div class="fi-grid"><div class="fi-cell"><div class="fi-key">Company</div><div class="fi-val">${d.company_name||'—'}</div></div><div class="fi-cell"><div class="fi-key">Rep</div><div class="fi-val">${d.company_rep||'—'}</div></div><div class="fi-cell"><div class="fi-key">Install date</div><div class="fi-val">${fmtD(d.install_date_requested)}</div></div><div class="fi-cell"><div class="fi-key">Phone</div><div class="fi-val">${d.company_rep_phone||'—'}</div></div><div class="fi-cell s2"><div class="fi-key">Email</div><div class="fi-val"><a href="mailto:${d.company_rep_email}">${d.company_rep_email||'—'}</a></div></div>${d.job_number_name?`<div class="fi-cell"><div class="fi-key">Job #</div><div class="fi-val">${d.job_number_name}</div></div>`:''}</div></div><div class="p-section"><div class="p-heading">Customer</div><div class="fi-grid"><div class="fi-cell"><div class="fi-key">Name</div><div class="fi-val">${(d.customer_first_name||'')+' '+(d.customer_last_name||'')}</div></div><div class="fi-cell s2"><div class="fi-key">Phone</div><div class="fi-val">${d.customer_phone_number||'—'}</div></div><div class="fi-cell s3"><div class="fi-key">Job address</div><div class="fi-val">${[d.job_address_street_address,d.job_address_street_address_line_2,`${d.job_address_city||''}, ${d.job_address_state||''} ${d.job_address_zip_code||''}`].filter(Boolean).join(' · ')}</div></div></div></div>`;
        } else {
            el.innerHTML = `<div class="p-section"><div class="p-heading">Emergency</div><div class="fi-grid"><div class="fi-cell"><div class="fi-key">Company</div><div class="fi-val">${d.company_name||'—'}</div></div><div class="fi-cell"><div class="fi-key">Type</div><div class="fi-val">${d.type_of_supplement||'—'}</div></div><div class="fi-cell"><div class="fi-key">Date submitted</div><div class="fi-val">${fmtD(d.date_submitted)}</div></div><div class="fi-cell s2"><div class="fi-key">Contact email</div><div class="fi-val"><a href="mailto:${d.company_contact_email}">${d.company_contact_email||'—'}</a></div></div>${d.job_number_name?`<div class="fi-cell"><div class="fi-key">Job #</div><div class="fi-val">${d.job_number_name}</div></div>`:''}<div class="fi-cell s3"><div class="fi-key">Address</div><div class="fi-val">${[d.job_address,d.job_address_line2,`${d.job_city||''}, ${d.job_state||''} ${d.job_zip_code||''}`].filter(Boolean).join(' · ')}</div></div><div class="fi-cell"><div class="fi-key">Terms</div><div class="fi-val">${d.terms_conditions?'✅ Accepted':'❌ Pending'}</div></div><div class="fi-cell"><div class="fi-key">Requirements</div><div class="fi-val">${d.requirements?'✅ Met':'❌ Pending'}</div></div></div></div>`;
        }
    };

    const renderFiles = (d, type) => {
        const el = document.getElementById('files-content'); el.innerHTML = '';
        const groups = type === 'job'
            ? [['Aerial measurements',d.aerial_measurement],['Material order',d.material_order],['Other uploads',d.file_upload]]
            : [['Aerial images',d.aerial_measurement_path],['Contract files',d.contract_upload_path],['Picture uploads',d.file_picture_upload_path]];
        let total = 0;
        groups.forEach(([label, urls]) => {
            const items = (Array.isArray(urls) ? urls : []).filter(Boolean);
            if (!items.length) return; total += items.length;
            const sec = document.createElement('div'); sec.className = 'p-section';
            sec.innerHTML = `<div class="p-heading">${label} <span>${items.length}</span></div>`;
            items.forEach(url => {
                const e = extOf(url), name = url.split('/').pop();
                const ico  = isImgExt(e) ? 'img' : e === 'pdf' ? 'pdf' : 'other';
                const icoI = isImgExt(e) ? 'fa-image' : e === 'pdf' ? 'fa-file-pdf' : 'fa-file';
                const row = document.createElement('div'); row.className = 'file-row';
                row.innerHTML = `<div class="file-ico ${ico}"><i class="fas ${icoI}"></i></div><div style="flex:1;min-width:0"><div class="file-name">${name}</div><div class="file-ext">${e||'file'}</div></div><div class="d-flex gap-1"><a href="${url}" target="_blank" class="btn-icon"><i class="fas fa-eye" style="font-size:11px"></i> View</a><a href="${url}" download class="btn-icon"><i class="fas fa-download" style="font-size:11px"></i></a></div>`;
                sec.appendChild(row);
            });
            el.appendChild(sec);
        });
        if (!total) el.innerHTML = '<div class="empty-state"><i class="fas fa-folder-open"></i><p>No files attached</p></div>';
    };

    const renderNotes = notes => {
        const cnt  = notes ? notes.length : 0;
        document.getElementById('notes-badge').textContent = cnt;
        const feed = document.getElementById('notes-feed');
        feed.innerHTML = '';
        if (!cnt) { feed.innerHTML = '<div class="empty-state"><i class="fas fa-comments"></i><p>No notes yet</p></div>'; return; }
        notes.forEach(n => {
            const row    = document.createElement('div'); row.className = 'note-row';
            const imgUrl = normUrl(n.image_url || '');
            let bubble   = '';
            if (imgUrl)    bubble += `<div class="note-img-wrap"><img src="${imgUrl}" class="note-img" alt="Image" onclick="openNoteImg('${imgUrl}')" onerror="this.parentElement.innerHTML='<span style=\\'color:var(--tx3);font-size:11px\\'><i class=\\'fas fa-image\\'></i> Image unavailable</span>'"/></div>`;
            if (n.content) bubble += `<div class="note-text">${n.content}</div>`;
            if (!bubble)   bubble  = `<div class="note-text" style="color:var(--tx3);font-style:italic">No content</div>`;
            row.innerHTML = `<div class="note-av">${ini(n.user_name || '')}</div><div class="note-body"><div class="note-meta"><span class="note-author">${n.user_name || 'System'}</span><span class="note-time">${n.created_at_human || fmtDT(n.created_at_iso || n.created_at)}</span></div><div class="note-bubble">${bubble}</div></div>`;
            feed.appendChild(row);
        });
        feed.scrollTop = feed.scrollHeight;
    };

    window.openNoteImg = url => {
        let modal = document.getElementById('note-img-modal');
        if (!modal) {
            modal = document.createElement('div'); modal.id = 'note-img-modal';
            modal.innerHTML = `<div class="nim-backdrop" onclick="closeNoteImg()"></div><div class="nim-box"><button class="nim-close" onclick="closeNoteImg()"><i class="fas fa-times"></i></button><img id="note-img-full" src="" alt=""/></div>`;
            document.body.appendChild(modal);
        }
        document.getElementById('note-img-full').src = url;
        modal.classList.add('nim-open');
    };
    window.closeNoteImg = () => { const m = document.getElementById('note-img-modal'); if (m) m.classList.remove('nim-open'); };

    const renderCrew = d => {
        const card = document.getElementById('crew-assigned-card');
        document.getElementById('select-crew').value = d.crew_id || '';
        if (d.crew_id && d.crew_name) {
            card.className = 'crew-assigned-card';
            card.innerHTML = `<div class="crew-av">${ini(d.crew_name)}</div><div class="crew-av-info"><div class="crew-av-name">${d.crew_name}</div><div class="crew-av-co">${d.crew_company||''}</div></div><span class="crew-badge-active">✓ Assigned</span>`;
        } else {
            card.className = 'crew-unassigned-card';
            card.innerHTML = `<i class="fas fa-user-slash" style="font-size:18px;opacity:.35"></i><span style="font-size:12.5px">No crew assigned</span>`;
        }
        const hist     = document.getElementById('crew-history');
        const histData = d.crew_history || [];
        document.getElementById('hist-count').textContent = histData.length;
        if (!histData.length) { hist.innerHTML = '<div class="empty-state" style="padding:16px 0"><p>No previous assignments</p></div>'; return; }
        hist.innerHTML = '';
        histData.forEach((h, i) => {
            const isActive = i === 0 && d.crew_id;
            const row = document.createElement('div'); row.className = 'hist-row';
            row.innerHTML = `<div class="hist-dot" style="background:${isActive?'var(--green)':'#cbd5e1'}"></div><div class="hist-info"><div class="hist-name">${h.crew_name||'—'}</div><div class="hist-when">Assigned ${fmtD(h.assigned_at)}${h.assigned_by?' · by '+h.assigned_by:''}</div></div><span class="hist-pill ${isActive?'active':'removed'}">${isActive?'Active':'Removed'}</span>`;
            hist.appendChild(row);
        });
    };

    const renderPayment = d => {
        const amount = (d.payout_total > 0) ? d.payout_total : (d.amount ?? '');

        // Hero banner
        updatePayHero('pay-hero', 'pay-payout-total', 'pay-payout-sub', d);

        document.getElementById('pay-amount').value = amount ? parseFloat(amount).toFixed(2) : '';
        document.getElementById('pay-date').value   = d.payout_date ?? d.payment_date ?? '';
        document.getElementById('pay-status').value = 'paid';
        document.getElementById('pay-feedback').innerHTML = '';

        // Summary — siempre paid si hay amount
        updatePaySummary(amount ? 'paid' : 'unpaid', amount, d.payout_date ?? d.payment_date);

        // Receipt
        const existing   = document.getElementById('pay-receipt-existing');
        const receiptInp = document.getElementById('pay-receipt');
        if (receiptInp) receiptInp.dataset.removeExisting = '';
        if (d.payment_receipt_url) {
            existing.style.display = 'block';
            const fname = document.getElementById('pay-receipt-filename');
            const fview = document.getElementById('pay-receipt-view');
            if (fname) fname.textContent = d.payment_receipt_url.split('/').pop() || 'receipt.pdf';
            if (fview) fview.href = normUrl(d.payment_receipt_url);
        } else {
            existing.style.display = 'none';
        }
        const lbl = document.getElementById('pay-receipt-upload-lbl');
        if (lbl) { lbl.textContent = 'Click to upload receipt (PDF or image)'; lbl.style.color = ''; }
    };

    const updatePaySummary = (status, amount, date) => {
        const bar = document.getElementById('pay-summary');
        if (status === 'paid') {
            bar.className = 'pay-summary paid';
            bar.innerHTML = `<div class="pay-summary-icon"><i class="fas fa-circle-check"></i></div><div class="pay-summary-info"><div class="pay-summary-status">Payment confirmed</div><div class="pay-summary-detail">$${parseFloat(amount||0).toFixed(2)} · ${fmtD(date)}</div></div>`;
        } else {
            bar.className = 'pay-summary unpaid';
            bar.innerHTML = `<div class="pay-summary-icon"><i class="fas fa-clock"></i></div><div class="pay-summary-info"><div class="pay-summary-status">Payment pending</div><div class="pay-summary-detail">${amount ? '$'+parseFloat(amount).toFixed(2)+' recorded' : 'No amount recorded yet'}</div></div>`;
        }
    };

    // ══════════════════════════════════════════════════════════
    // API HELPERS
    // ══════════════════════════════════════════════════════════
    const API = {
        get:      async (type, id) => { abort.abort(); abort = new AbortController(); const r = await fetch(`{{ url('superadmin/calendar/event') }}/${type}/${id}`, { signal:abort.signal }); if (!r.ok) throw Error(r.status); return r.json(); },
        post:     (url, data)      => fetch(url, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) }).then(r => r.json()),
        putColor: data             => { data.color = vColor(data.color); return fetch('{{ route("superadmin.calendar.company.updateColor") }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) }); },
        putVis:   data             => fetch('{{ route("superadmin.calendar.company.updateVisibility") }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) }),
    };

    // ══════════════════════════════════════════════════════════
    // EVENT CLICK HANDLER
    // ══════════════════════════════════════════════════════════
    const handleEventClick = async ({ event }) => {
        const { id, extendedProps:xp } = event;
        ev = { id: xp.type === 'repair' ? xp.repair_id : id, type: xp.type, status: xp.status };
        resetTabs();

        const pill       = document.getElementById('m-pill');
        const fullLink   = document.getElementById('m-open-full');
        const photosLink = document.getElementById('m-open-photos');

        if (xp.type === 'repair') {
            pill.textContent = 'Repair';
            pill.className   = 'm-pill repair';
            document.getElementById('m-title').textContent = event.title;
            document.getElementById('m-date').textContent  = fmtD(event.startStr);
            const parentUrl = xp.reference_type === 'job' ? jobBase.replace('__ID__', xp.reference_id) : emergBase.replace('__ID__', xp.reference_id);
            fullLink.style.display   = 'inline-flex';
            fullLink.innerHTML       = '<i class="fas fa-arrow-up-right-from-square" style="font-size:11px"></i> Open parent';
            fullLink.href            = parentUrl;
            photosLink.style.display = 'inline-flex';
            photosLink.href          = photosUrl('repair', xp.repair_id);
            loadInvoiceLink('repair', xp.repair_id);
            document.querySelector('[data-p="payment"]').style.display = 'none';
            document.querySelector('[data-p="payout"]').style.display  = '';
            document.querySelector('[data-p="info"]').classList.add('repair-mode');
            document.getElementById('info-content').innerHTML = '<div class="sk sk-line" style="width:65%"></div><div class="sk sk-line" style="width:80%"></div>';
            new bootstrap.Modal(document.getElementById('eventModal')).show();
            renderRepairInfo(xp);
            loadRepairFiles(xp.repair_id);
            loadRepairNotes(xp.repair_id);
            loadRepairPayout(xp.repair_id);
            return;
        }

        pill.textContent = xp.type === 'job' ? 'Job' : 'Emergency';
        pill.className   = 'm-pill ' + (xp.type === 'job' ? 'job' : 'emerg');
        fullLink.style.display   = 'inline-flex';
        fullLink.innerHTML       = '';
        fullLink.href            = deepUrl(xp.type, id);
        photosLink.style.display = 'inline-flex';
        photosLink.href          = photosUrl(xp.type, id);
        loadInvoiceLink(xp.type, id);
        document.getElementById('m-title').textContent       = event.title || 'Event';
        document.getElementById('m-date').textContent        = '—';
        document.getElementById('info-content').innerHTML    = '<div class="sk sk-line" style="width:65%"></div><div class="sk sk-line" style="width:80%"></div><div class="sk sk-line" style="width:50%"></div><div class="sk sk-line" style="width:70%"></div>';
        document.getElementById('files-content').innerHTML   = '<div class="sk sk-line" style="width:50%"></div><div class="sk sk-line" style="width:70%"></div>';
        document.getElementById('assign-feedback').innerHTML = '';
        document.getElementById('pay-feedback').innerHTML    = '';
        new bootstrap.Modal(document.getElementById('eventModal')).show();
        try {
            const { data:d } = await API.get(xp.type, id);
            document.getElementById('m-date').textContent = xp.type === 'job' ? fmtD(d.install_date_requested) : fmtD(d.date_submitted);
            renderInfo(d, xp.type); renderFiles(d, xp.type); renderNotes(d.notes||[]); renderCrew(d); renderPayment(d);
        } catch(e) {
            if (e.name !== 'AbortError') document.getElementById('info-content').innerHTML = '<div class="empty-state"><i class="fas fa-triangle-exclamation"></i><p>Failed to load event details.</p></div>';
        }
    };

    // ══════════════════════════════════════════════════════════
    // FULLCALENDAR
    // ══════════════════════════════════════════════════════════
    const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth', locale: 'en',
        headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,listMonth' },
        events: { url:'{{ route("superadmin.calendar.events") }}', method:'GET' },
        eventDisplay: 'block', dayMaxEvents: 4, height: 'auto',

        eventContent: arg => {
            const crew    = arg.event.extendedProps.crewName    || '';
            const company = arg.event.extendedProps.companyName || '';
            const status  = arg.event.extendedProps.status;
            const type    = arg.event.extendedProps.type;
            const w = document.createElement('div');
            w.style.cssText = 'width:100%;padding:1px 0';
            if (type === 'repair') {
                const statusLabel = { pending:'Scheduled', en_process:'In Progress', completed:'Completed' };
                const statusColor = { pending:'#b45309', en_process:'#6d28d9', completed:'#15803d' };
                const sc = statusColor[status] || '#64748b';
                w.innerHTML = `<span class="fc-event-status-badge" style="color:${sc};border-color:${sc}"><i class="fas fa-tools" style="font-size:8px"></i>${statusLabel[status]||status}</span>` + (company ? `<div class="fc-event-company"><i class="fas fa-building" style="font-size:8px;margin-right:2px;opacity:.7"></i>${company}</div>` : '') + `<div style="font-weight:600;white-space:normal;line-height:1.3;font-size:11px">${arg.event.title}</div>` + (crew ? `<div class="fc-event-crew-tag"><i class="fas fa-user" style="font-size:9px"></i>${crew}</div>` : '');
                return { domNodes:[w] };
            }
            const badgeMap = { 'completed':{ icon:'fa-circle-check', color:'#16a34a', label:'Done' }, 'en_process':{ icon:'fa-hammer', color:'#d97706', label:'In Progress' }, 'pending':{ icon:'fa-clock', color:'#64748b', label:'Pending' } };
            const b = badgeMap[status] || null;
            w.innerHTML = (b ? `<span class="fc-event-status-badge" style="color:${b.color};border-color:${b.color}"><i class="fas ${b.icon}" style="font-size:8px"></i>${b.label}</span>` : '') + (company ? `<div class="fc-event-company"><i class="fas fa-building" style="font-size:8px;margin-right:2px;opacity:.7"></i>${company}</div>` : '') + `<div style="font-weight:600;white-space:normal;line-height:1.3;font-size:11px">${arg.event.title}</div>` + (crew ? `<div class="fc-event-crew-tag"><i class="fas fa-user" style="font-size:9px"></i>${crew}</div>` : '');
            return { domNodes:[w] };
        },

        eventClick: handleEventClick
    });

    cal.render();

    // ══════════════════════════════════════════════════════════
    // DEEP LINK
    // ══════════════════════════════════════════════════════════
    const _urlP  = new URLSearchParams(location.search);
    const _type  = _urlP.get('type');
    const _id    = _urlP.get('id');
    if (_type && _id) {
        const tryOpen = (attempts = 0) => {
            const events = cal.getEvents();
            let target = null;
            if (_type === 'repair') {
                target = events.find(e => String(e.extendedProps.repair_id) === String(_id));
            } else {
                target = events.find(e => String(e.id) === String(_id) && e.extendedProps.type === _type);
            }
            if (target) { handleEventClick({ event: target }); }
            else if (attempts < 15) { setTimeout(() => tryOpen(attempts + 1), 300); }
        };
        setTimeout(() => tryOpen(), 600);
    }

    // ══════════════════════════════════════════════════════════
    // STATS
    // ══════════════════════════════════════════════════════════
    fetch('{{ route("superadmin.calendar.events") }}?start=2020-01-01&end=2030-01-01')
        .then(r => r.json()).then(evts => {
            const j      = evts.filter(e => e.extendedProps?.type === 'job').length;
            const em     = evts.filter(e => e.extendedProps?.type === 'emergency').length;
            const rp     = evts.filter(e => e.extendedProps?.type === 'repair').length;
            const inProg = evts.filter(e => ['en_process','in_progress'].includes(e.extendedProps?.status)).length;
            const done   = evts.filter(e => ['completed','resolved'].includes(e.extendedProps?.status)).length;
            const tot    = j + em + rp || 1;
            document.getElementById('stat-jobs').textContent       = j;
            document.getElementById('stat-emerg').textContent      = em;
            document.getElementById('stat-repairs').textContent    = rp;
            document.getElementById('stat-inprogress').textContent = inProg;
            document.getElementById('stat-done').textContent       = done;
            document.getElementById('sbar-jobs').style.width       = Math.round(j/tot*100)+'%';
            document.getElementById('sbar-emerg').style.width      = Math.round(em/tot*100)+'%';
            document.getElementById('sbar-repairs').style.width    = Math.round(rp/tot*100)+'%';
            document.getElementById('sbar-inprogress').style.width = Math.round(inProg/(inProg+done||1)*100)+'%';
            document.getElementById('sbar-done').style.width       = Math.round(done/(inProg+done||1)*100)+'%';
        }).catch(() => {});

    // ══════════════════════════════════════════════════════════
    // ASSIGN CREW
    // ══════════════════════════════════════════════════════════
    document.getElementById('btn-assign').addEventListener('click', async () => {
        const sel = document.getElementById('select-crew');
        if (!ev.id || !sel.value) return;
        const btn = document.getElementById('btn-assign'), orig = btn.innerHTML;
        const fb  = document.getElementById('assign-feedback');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:11px"></i> Saving…'; btn.disabled = true;
        try {
            const r = await API.post('{{ route("superadmin.calendar.assign") }}', { type:ev.type, id:ev.id, crew_id:sel.value });
            if (r.success) {
                cal.refetchEvents();
                const name    = sel.options[sel.selectedIndex].text.split(' (')[0];
                const company = (sel.options[sel.selectedIndex].text.match(/\((.+)\)/) || [])[1] || '';
                const card    = document.getElementById('crew-assigned-card');
                card.className = 'crew-assigned-card';
                card.innerHTML = `<div class="crew-av">${ini(name)}</div><div class="crew-av-info"><div class="crew-av-name">${name}</div><div class="crew-av-co">${company}</div></div><span class="crew-badge-active">✓ Assigned</span>`;
                fb.innerHTML   = '<div style="font-size:12px;color:var(--green);display:flex;align-items:center;gap:5px;margin-top:4px"><i class="fas fa-check-circle"></i> Assignment saved.</div>';
            }
        } catch(e) { fb.innerHTML = '<div style="font-size:12px;color:var(--red)">Error saving assignment.</div>'; }
        finally { btn.innerHTML = orig; btn.disabled = false; }
    });

    // ══════════════════════════════════════════════════════════
    // NOTES
    // ══════════════════════════════════════════════════════════
    document.getElementById('note-img-input').addEventListener('change', function () {
        stagedNoteImg = this.files[0] || null;
        const preview = document.getElementById('note-img-preview-name');
        if (stagedNoteImg) { preview.textContent = stagedNoteImg.name; preview.style.color = 'var(--blue)'; }
        else { preview.textContent = ''; }
    });

    const sendNote = async () => {
        const txt = document.getElementById('note-input').value.trim();
        if (!txt && !stagedNoteImg) return;
        if (!ev.id) return;
        const btn = document.getElementById('btn-note'), orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i>'; btn.disabled = true;
        try {
            let n;
            if (stagedNoteImg) {
                const fd = new FormData();
                fd.append('_token', CSRF); fd.append('type', ev.type); fd.append('id', ev.id);
                if (txt) fd.append('content', txt);
                fd.append('image', stagedNoteImg);
                const r = await fetch('{{ route("superadmin.calendar.storeNote") }}', { method:'POST', body:fd });
                n = await r.json();
            } else {
                n = await API.post('{{ route("superadmin.calendar.storeNote") }}', { type:ev.type, id:ev.id, content:txt });
            }
            const feed  = document.getElementById('notes-feed');
            const empty = feed.querySelector('.empty-state');
            if (empty) empty.remove();
            const imgUrl  = normUrl(n.image_url || '');
            const row     = document.createElement('div'); row.className = 'note-row';
            const imgHtml = imgUrl ? `<div class="note-img-wrap"><img src="${imgUrl}" class="note-img" onclick="openNoteImg('${imgUrl}')" onerror="this.parentElement.innerHTML='<span style=\\'color:var(--tx3);font-size:11px\\'><i class=\\'fas fa-image\\'></i> Unavailable</span>'"/></div>` : '';
            row.innerHTML = `<div class="note-av">${ini(n.user_name || 'Me')}</div><div class="note-body"><div class="note-meta"><span class="note-author">${n.user_name || 'Me'}</span><span class="note-time">${fmtDT(n.created_at)}</span></div><div class="note-bubble">${imgHtml}${n.content ? `<div class="note-text">${n.content}</div>` : ''}</div></div>`;
            feed.appendChild(row);
            document.getElementById('notes-badge').textContent = parseInt(document.getElementById('notes-badge').textContent || 0) + 1;
            document.getElementById('note-input').value = '';
            stagedNoteImg = null;
            document.getElementById('note-img-input').value = '';
            document.getElementById('note-img-preview-name').textContent = '';
            feed.scrollTop = feed.scrollHeight;
        } catch(e) { alert('Error saving note.'); }
        finally { btn.innerHTML = orig; btn.disabled = false; }
    };

    document.getElementById('btn-note').addEventListener('click', sendNote);
    document.getElementById('note-input').addEventListener('keypress', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendNote(); } });

    // ══════════════════════════════════════════════════════════
    // PAYMENT (job / emergency)
    // ══════════════════════════════════════════════════════════
    document.getElementById('btn-pay').addEventListener('click', async () => {
        if (!ev.id) return;
        const btn = document.getElementById('btn-pay'), fb = document.getElementById('pay-feedback');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:11px"></i> Saving…'; btn.disabled = true;
        const fd = new FormData();
        fd.append('_method', 'PATCH'); fd.append('_token', CSRF);
        fd.append('amount',         document.getElementById('pay-amount').value);
        fd.append('payment_date',   document.getElementById('pay-date').value);
        fd.append('payment_status', 'paid');
        const receiptInp = document.getElementById('pay-receipt');
        if (receiptInp.dataset.removeExisting === '1') fd.append('remove_receipt', '1');
        const file = receiptInp.files[0];
        if (file) fd.append('payment_receipt', file);
        const url = ev.type === 'job' ? `/superadmin/jobs/${ev.id}/payment` : `/superadmin/emergencies/${ev.id}/payment`;
        try {
            const r = await fetch(url, { method:'POST', body:fd });
            if (r.ok) {
                updatePaySummary('paid', document.getElementById('pay-amount').value, document.getElementById('pay-date').value);
                fb.innerHTML = '<div class="pay-feedback ok"><i class="fas fa-check-circle" style="font-size:12px"></i> Payment saved.</div>';
                if (file) {
                    document.getElementById('pay-receipt-existing').style.display = 'block';
                    document.getElementById('pay-receipt-filename').textContent = file.name;
                }
                setTimeout(() => { if (fb) fb.innerHTML = ''; }, 3000);
            } else { fb.innerHTML = '<div class="pay-feedback err"><i class="fas fa-xmark-circle" style="font-size:12px"></i> Error saving payment.</div>'; }
        } catch(e) { fb.innerHTML = '<div class="pay-feedback err">Connection error.</div>'; }
        finally { btn.innerHTML = '<i class="fas fa-floppy-disk" style="font-size:12px"></i> Save payment'; btn.disabled = false; }
    });

    // ══════════════════════════════════════════════════════════
    // COMPANIES (color + visibility)
    // ══════════════════════════════════════════════════════════
    document.querySelectorAll('.company-color').forEach(inp => inp.addEventListener('change', async function () {
        const n = this.dataset.name, c = vColor(this.value);
        const lbl = document.querySelector(`label[for="co-${n.toLowerCase().replace(/ /g,'-')}"]`);
        if (lbl) lbl.style.background = c;
        await API.putColor({ name:n, color:c }); cal.refetchEvents();
    }));
    document.querySelectorAll('.company-toggle').forEach(inp => inp.addEventListener('change', async function () {
        try { await API.putVis({ name:this.dataset.name, active:this.checked }); cal.refetchEvents(); }
        catch(e) { this.checked = !this.checked; }
    }));

    let rt; window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(() => cal.updateSize(), 200); });

    // ══════════════════════════════════════════════════════════
    // PENDING PAYMENTS — search
    // ══════════════════════════════════════════════════════════
    window.filterPPTable = q => {
        const val   = q.trim().toLowerCase();
        const clear = document.getElementById('pp-search-clear');
        if (clear) clear.style.display = val ? 'flex' : 'none';
        document.querySelectorAll('#pp-tbody tr').forEach(tr => { tr.classList.toggle('pp-row-hidden', val && !tr.textContent.toLowerCase().includes(val)); });
        const visible = [...document.querySelectorAll('#pp-tbody tr')].filter(tr => !tr.classList.contains('pp-row-hidden'));
        let noRes = document.getElementById('pp-no-results');
        if (!visible.length && val) {
            if (!noRes) { noRes = document.createElement('tr'); noRes.id = 'pp-no-results'; noRes.innerHTML = `<td colspan="7" style="text-align:center;padding:20px;color:var(--tx3);font-size:13px"><i class="fas fa-magnifying-glass" style="margin-right:6px;opacity:.4"></i>No results for "${q}"</td>`; document.getElementById('pp-tbody').appendChild(noRes); }
            else { noRes.querySelector('td').innerHTML = `<i class="fas fa-magnifying-glass" style="margin-right:6px;opacity:.4"></i>No results for "${q}"`; noRes.style.display = ''; }
        } else if (noRes) { noRes.style.display = 'none'; }
    };
    window.clearPPSearch = () => { const inp = document.getElementById('pp-search'); if (inp) { inp.value = ''; filterPPTable(''); } };

    // ══════════════════════════════════════════════════════════
    // PENDING PAYMENTS — load
    // ══════════════════════════════════════════════════════════
    (async () => {
        try {
            const r = await fetch('{{ route("superadmin.calendar.pendingPayments") }}');
            const { count, items } = await r.json();
            document.getElementById('pp-loading').style.display = 'none';
            document.getElementById('pp-count').textContent     = count;
            if (!count) { document.getElementById('pp-empty').style.display = 'block'; return; }
            const tbody = document.getElementById('pp-tbody');
            items.forEach(it => {
                const amtHtml  = it.amount ? `<span class="pp-amount">$${parseFloat(it.amount).toFixed(2)}</span>` : `<span class="pp-amount none">Not set</span>`;
                const typePill = `<span class="pp-type-pill ${it.type==='job'?'job':'emerg'}"><i class="fas ${it.type==='job'?'fa-wrench':'fa-triangle-exclamation'}" style="font-size:9px"></i>${it.type==='job'?'Job':'Emergency'}</span>`;
                const tr = document.createElement('tr');
                tr.innerHTML = `<td><strong>${it.job_number}</strong></td><td>${it.company}</td><td>${it.crew}</td><td>${fmtD(it.date)}</td><td>${amtHtml}</td><td>${typePill}</td><td><button class="btn-icon" onclick="openPayModal('${it.type}',${it.id})"><i class="fas fa-dollar-sign" style="font-size:10px"></i> Pay</button></td>`;
                tbody.appendChild(tr);
            });
            document.getElementById('pp-list').style.display = 'block';
        } catch(e) { document.getElementById('pp-loading').innerHTML = '<span style="color:var(--red)">Error loading pending payments.</span>'; }
    })();

    // ══════════════════════════════════════════════════════════
    // OPEN PAY MODAL (desde tabla de pending payments)
    // ══════════════════════════════════════════════════════════
    window.openPayModal = (type, id) => {
        ev = { id, type }; resetTabs();
        document.getElementById('m-title').textContent = (type === 'job' ? 'Job #' : 'Emergency #') + id;
        const pill = document.getElementById('m-pill');
        pill.textContent = type === 'job' ? 'Job' : 'Emergency';
        pill.className   = 'm-pill ' + (type === 'job' ? 'job' : 'emerg');
        document.getElementById('m-date').textContent        = '—';
        document.getElementById('info-content').innerHTML    = '<div class="sk sk-line" style="width:65%"></div><div class="sk sk-line" style="width:80%"></div>';
        document.getElementById('assign-feedback').innerHTML = '';
        document.getElementById('pay-feedback').innerHTML    = '';
        const fullLink   = document.getElementById('m-open-full');
        const photosLink = document.getElementById('m-open-photos');
        fullLink.style.display   = 'inline-flex';
        fullLink.innerHTML       = '<i class="fas fa-arrow-up-right-from-square" style="font-size:11px"></i> Open full page';
        photosLink.style.display = 'inline-flex';
        photosLink.href          = photosUrl(type, id);
        loadInvoiceLink(type, id);
        document.querySelectorAll('.m-tab').forEach(x => x.classList.remove('on'));
        document.querySelectorAll('.m-panel').forEach(x => x.classList.remove('on'));
        document.querySelector('[data-p="payment"]').classList.add('on');
        document.getElementById('p-payment').classList.add('on');
        new bootstrap.Modal(document.getElementById('eventModal')).show();
        API.get(type, id).then(({ data:d }) => {
            document.getElementById('m-date').textContent  = type === 'job' ? fmtD(d.install_date_requested) : fmtD(d.date_submitted);
            document.getElementById('m-title').textContent = (type === 'job' ? 'Job #' : 'Emergency #') + (d.job_number_name || id);
            fullLink.href = deepUrl(type, d.id || id);
            renderInfo(d, type); renderFiles(d, type); renderNotes(d.notes||[]); renderCrew(d); renderPayment(d);
        }).catch(() => {});
    };

});
</script>