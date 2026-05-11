@extends('admin.layouts.superadmin')

@section('title', 'Weekly Accounting')

@section('content')

<style>
/* ══════════════════════════════════════════════════════════════
   WEEKLY ACCOUNTING · "Full Visibility" Layout
   ══════════════════════════════════════════════════════════════
   Sections:
   1.  Wrapper
   2.  Header bar
   3.  Summary bar (KPIs + Period filter, 2 rows)
   4.  Chart card
   5.  Ledger navigation (Year + Month tabs)
   6.  Search bar & status
   7.  Toolbar
   8.  Week card (header + body)
   9.  Invoices table
   10. Operating costs dashboard
   11. Notes + Save
   12. Paginator + Empty state
   13. Responsive
   ══════════════════════════════════════════════════════════════ */


/* ════════════════════════════════════════════
   1. WRAPPER
   ════════════════════════════════════════════ */
.wa-wrap {
    font-family: 'Montserrat', sans-serif;
    padding: 20px 24px;
    max-width: 1700px;
    margin: 0 auto;
}


/* ════════════════════════════════════════════
   2. HEADER BAR
   ════════════════════════════════════════════ */
.wa-headbar {
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(135deg, #0f1117 0%, #1a1f2e 100%);
    border-radius: 12px; padding: 14px 22px; margin-bottom: 14px;
    position: relative; overflow: hidden; gap: 14px; flex-wrap: wrap;
}
.wa-headbar::before {
    content: ''; position: absolute; right: -80px; top: -80px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(24,85,224,.2) 0%, transparent 70%);
    pointer-events: none;
}
.wa-headbar-l { display: flex; align-items: center; gap: 12px; position: relative; }
.wa-headbar-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, rgba(24,85,224,.3), rgba(79,128,255,.15));
    border: 1px solid rgba(79,128,255,.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; color: #8aadff;
}
.wa-headbar-title { font-size: 17px; font-weight: 800; color: #fff; letter-spacing: -.4px; line-height: 1.1; }
.wa-headbar-sub { font-size: 11px; font-weight: 600; color: rgba(255,255,255,.45); margin-top: 2px; }
.wa-headbar-r { display: flex; align-items: center; gap: 8px; position: relative; flex-wrap: wrap; }

.wa-quick-stat {
    display: flex; flex-direction: column; gap: 1px;
    padding: 6px 12px; background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1); border-radius: 8px;
}
.wa-quick-stat-lbl {
    font-size: 9px; font-weight: 800; color: rgba(255,255,255,.5);
    text-transform: uppercase; letter-spacing: .5px;
}
.wa-quick-stat-val {
    font-size: 13px; font-weight: 800; color: #fff;
    font-variant-numeric: tabular-nums;
}

.wa-settings-btn {
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
    color: #fff; padding: 8px 12px; border-radius: 8px;
    font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    cursor: pointer; transition: all .15s;
    display: inline-flex; align-items: center; gap: 6px;
}
.wa-settings-btn:hover { background: rgba(255,255,255,.14); }


/* ════════════════════════════════════════════
   3. SUMMARY BAR (KPIs + Period filter, 2 rows)
   ════════════════════════════════════════════
   Layout:
   ┌─────────────────────────────────────────────┐
   │ Title  │  KPIs (Invoiced, Payout, ...)      │
   ├─────────────────────────────────────────────┤
   │      Period buttons + Custom range          │
   └─────────────────────────────────────────────┘
*/
.wa-summary {
    background: #fff; border: 1px solid var(--bd); border-radius: 12px;
    padding: 14px 18px; margin-bottom: 14px;
    display: grid;
    grid-template-columns: auto 1fr;
    grid-template-areas:
        "title  kpis"
        "period period";
    gap: 14px 18px;
    align-items: center;
}

/* Row 1 — Left: title */
.wa-summary-l {
    grid-area: title;
    display: flex; align-items: center; gap: 10px;
}
.wa-summary-l-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--blt); color: var(--blue); border: 1px solid var(--bbd);
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.wa-summary-l h3 { font-size: 13px; font-weight: 800; color: var(--ink); margin: 0; line-height: 1.1; }
.wa-summary-l .sub { font-size: 10.5px; color: var(--ink3); font-weight: 600; margin-top: 2px; }

/* Row 1 — Right: KPIs */
.wa-kpis-row {
    grid-area: kpis;
    display: flex;
    align-items: stretch;
    border-left: 1px solid var(--bd2);
    padding-left: 18px;
    flex-wrap: wrap;
    gap: 0;
}
.wa-kpi-mini {
    padding: 4px 18px; border-right: 1px solid var(--bd2);
    display: flex; flex-direction: column; gap: 2px; min-width: 110px;
}
.wa-kpi-mini:last-child { border-right: none; }
.wa-kpi-mini-lbl {
    font-size: 9px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .5px;
}
.wa-kpi-mini-val {
    font-size: 16px; font-weight: 800; color: var(--ink);
    font-variant-numeric: tabular-nums; letter-spacing: -.4px; line-height: 1.1;
}
.wa-kpi-mini.payout .wa-kpi-mini-val { color: #b45309; }
.wa-kpi-mini.gross  .wa-kpi-mini-val { color: var(--blue); }
.wa-kpi-mini.equity .wa-kpi-mini-val { color: var(--grn); }

/* Row 2 — Period filter (full width with separator) */
.wa-period-wrap {
    grid-area: period;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
    border-top: 1px solid var(--bd2);
    padding-top: 12px;
}

.wa-period-group {
    display: flex; background: var(--bg);
    border-radius: 7px; padding: 2px; border: 1px solid var(--bd);
    flex-wrap: wrap;
}
.wa-period-btn {
    padding: 6px 12px; border-radius: 5px; border: none;
    background: transparent; color: var(--ink3);
    font-size: 11px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    cursor: pointer; transition: all .13s;
    white-space: nowrap;
}
.wa-period-btn:hover { color: var(--ink); background: rgba(0,0,0,.04); }
.wa-period-btn.active { background: var(--surf); color: var(--blue); box-shadow: 0 1px 3px rgba(0,0,0,.08); }

.wa-custom-range {
    display: flex; align-items: center; gap: 5px;
    padding: 5px 10px; background: var(--surf);
    border: 1px solid var(--bd); border-radius: 7px;
}
.wa-custom-range input[type="date"] {
    border: none; background: transparent;
    font-size: 11px; font-family: 'Montserrat', sans-serif; font-weight: 700;
    color: var(--ink); cursor: pointer; width: 110px;
}
.wa-custom-range input[type="date"]:focus { outline: none; }
.wa-custom-range .sep { color: var(--ink3); font-size: 11px; }


/* ════════════════════════════════════════════
   4. CHART CARD
   ════════════════════════════════════════════ */
.wa-chart-card {
    background: #fff; border: 1px solid var(--bd); border-radius: 12px;
    padding: 14px 18px; margin-bottom: 14px;
}
.wa-chart-head {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 12px; gap: 8px; flex-wrap: wrap;
}
.wa-chart-title {
    font-size: 12px; font-weight: 800; color: var(--ink);
    display: flex; align-items: center; gap: 7px;
}
.wa-chart-title i { color: var(--blue); font-size: 11px; }
.wa-chart-meta { font-size: 10.5px; color: var(--ink3); font-weight: 600; }

.wa-chart {
    display: flex; align-items: flex-end; gap: 6px;
    height: 130px; padding: 6px 0; overflow-x: auto;
}
.wa-bar-group {
    display: flex; flex-direction: column; align-items: center;
    gap: 4px; flex: 1; min-width: 42px; height: 100%; justify-content: flex-end;
}
.wa-bar-wrap { display: flex; align-items: flex-end; width: 100%; height: calc(100% - 18px); }
.wa-bar {
    flex: 1; border-radius: 4px 4px 0 0;
    background: linear-gradient(to top, var(--blue), #4f80ff);
    transition: all .2s; cursor: pointer; position: relative; min-height: 3px;
}
.wa-bar:hover { opacity: .85; }
.wa-bar .tip {
    position: absolute; bottom: 100%; left: 50%;
    transform: translateX(-50%) translateY(-5px);
    background: var(--ink); color: #fff;
    padding: 4px 8px; border-radius: 5px;
    font-size: 9.5px; font-weight: 700; white-space: nowrap;
    opacity: 0; pointer-events: none; transition: opacity .15s; z-index: 5;
}
.wa-bar:hover .tip { opacity: 1; }
.wa-chart-lbl {
    font-size: 9px; font-weight: 700; color: var(--ink3);
    text-align: center; white-space: nowrap;
}
.wa-chart-empty {
    display: flex; align-items: center; justify-content: center;
    color: var(--ink3); font-size: 11px; font-weight: 600;
    width: 100%; padding: 30px 0;
}


/* ════════════════════════════════════════════
   5. LEDGER NAVIGATION (Years + Months)
   ════════════════════════════════════════════ */
.wa-ledger-nav {
    background: #fff; border: 1px solid var(--bd); border-radius: 12px;
    padding: 12px 18px; margin-bottom: 14px;
    display: flex; flex-direction: column; gap: 10px;
}
.wa-ledger-row {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.wa-ledger-lbl {
    font-size: 9.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .5px;
    min-width: 50px;
}

/* Year tabs */
.wa-year-tabs { display: flex; gap: 4px; flex-wrap: wrap; }
.wa-year-tab {
    padding: 5px 12px; border-radius: 6px;
    border: 1px solid var(--bd); background: var(--surf);
    color: var(--ink2); cursor: pointer;
    font-size: 11.5px; font-weight: 800; font-family: 'Montserrat', sans-serif;
    transition: all .13s; font-variant-numeric: tabular-nums;
}
.wa-year-tab:hover { border-color: var(--blue); color: var(--blue); background: var(--blt); }
.wa-year-tab.active {
    background: linear-gradient(135deg, #0f1117, #1a1f2e);
    color: #fff; border-color: #0f1117;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
}

/* Month tabs */
.wa-month-tabs { display: flex; gap: 4px; flex-wrap: wrap; }
.wa-month-tab {
    padding: 5px 11px; border-radius: 6px;
    border: 1px solid var(--bd); background: var(--surf);
    color: var(--ink3); cursor: pointer;
    font-size: 11px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    transition: all .13s;
    display: inline-flex; align-items: center; gap: 5px;
}
.wa-month-tab:hover { border-color: var(--blue); color: var(--blue); background: var(--blt); }
.wa-month-tab.active {
    background: var(--blue); color: #fff; border-color: var(--blue);
    box-shadow: 0 2px 6px rgba(24,85,224,.3);
}
.wa-month-tab .count {
    background: rgba(255,255,255,.25); color: inherit;
    padding: 1px 6px; border-radius: 8px;
    font-size: 9.5px; font-weight: 800;
}
.wa-month-tab:not(.active) .count { background: var(--bg); color: var(--ink3); }
.wa-month-tabs-empty { font-size: 11px; color: var(--ink3); font-weight: 600; padding: 6px 0; }


/* ════════════════════════════════════════════
   6. SEARCH BAR & STATUS
   ════════════════════════════════════════════ */
.wa-search {
    display: flex; align-items: center; gap: 8px;
    margin-left: auto; min-width: 280px;
    background: var(--bg); border: 1.5px solid var(--bd);
    border-radius: 8px; padding: 4px 10px;
    transition: all .15s;
}
.wa-search:focus-within {
    border-color: var(--blue); background: #fff;
    box-shadow: 0 0 0 3px rgba(24,85,224,.12);
}
.wa-search i.search-icon { color: var(--ink3); font-size: 12px; }
.wa-search input {
    flex: 1; border: none; background: transparent;
    padding: 6px 0; font-size: 12px; font-weight: 600;
    font-family: 'Montserrat', sans-serif; color: var(--ink);
    outline: none;
}
.wa-search input::placeholder { color: var(--ink3); font-weight: 500; }
.wa-search-clear {
    border: none; background: var(--bd); color: var(--ink2);
    width: 18px; height: 18px; border-radius: 50%;
    display: none; align-items: center; justify-content: center;
    cursor: pointer; font-size: 10px; transition: all .13s;
}
.wa-search-clear:hover { background: var(--ink3); color: #fff; }
.wa-search.has-value .wa-search-clear { display: inline-flex; }

.wa-search-status {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 1px solid #f59e0b;
    border-radius: 8px; padding: 8px 14px;
    display: none; align-items: center; gap: 8px;
    font-size: 11.5px; font-weight: 700; color: #78350f;
    margin-bottom: 12px;
}
.wa-search-status.active { display: flex; }
.wa-search-status i { font-size: 12px; }
.wa-search-status .count {
    background: #f59e0b; color: #fff;
    padding: 2px 8px; border-radius: 4px;
    font-weight: 800; font-size: 10.5px;
}

/* Highlight + hidden states */
.wa-search-hit { background: #fef08a !important; font-weight: 800; }
.wa-week.search-hidden { display: none; }
.wa-week.month-hidden { display: none; }
table.wa-tbl tbody tr.search-hidden { display: none; }


/* ════════════════════════════════════════════
   7. TOOLBAR
   ════════════════════════════════════════════ */
.wa-toolbar {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 12px; flex-wrap: wrap; gap: 8px;
    padding: 9px 14px; background: var(--surf);
    border: 1px solid var(--bd); border-radius: 10px;
}
.wa-toolbar-info {
    font-size: 11px; color: var(--ink3); font-weight: 600;
    display: flex; align-items: center; gap: 6px;
}
.wa-toolbar-info strong { color: var(--ink); font-weight: 800; }
.wa-toolbar-actions { display: flex; gap: 5px; }
.wa-ctrl-btn {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--surf); border: 1px solid var(--bd); color: var(--ink2);
    padding: 6px 11px; border-radius: 6px;
    font-size: 11px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    cursor: pointer; transition: all .13s;
}
.wa-ctrl-btn:hover { border-color: var(--blue); color: var(--blue); background: var(--blt); }


/* ════════════════════════════════════════════
   8. WEEK CARD (header + body)
   ════════════════════════════════════════════ */
.wa-week {
    background: var(--surf); border: 1px solid var(--bd); border-radius: 12px;
    overflow: hidden; margin-bottom: 12px; transition: all .2s;
}
.wa-week:hover { border-color: #b9c0cd; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
.wa-week.current {
    border-color: var(--blue); border-width: 2px;
    box-shadow: 0 0 0 4px rgba(24,85,224,.08);
}

/* Week header */
.wa-week-head {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 18px;
    background: linear-gradient(to bottom, #fdfdff, #f4f6fa);
    border-bottom: 1px solid var(--bd);
    cursor: pointer; user-select: none; transition: background .13s;
}
.wa-week-head:hover { background: linear-gradient(to bottom, var(--blt), #dce5fc); }
.wa-week.collapsed .wa-week-head { border-bottom: 0; }

.wa-week-num {
    width: 44px; height: 44px; border-radius: 11px;
    background: linear-gradient(135deg, var(--blt), #c7d4fb);
    color: var(--blue);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; flex-shrink: 0;
    border: 1px solid var(--bbd); letter-spacing: -.3px;
}
.wa-week.current .wa-week-num {
    background: linear-gradient(135deg, var(--blue), #4f80ff);
    color: #fff; border-color: var(--blue);
    box-shadow: 0 4px 12px rgba(24,85,224,.3);
}

.wa-week-info { flex: 1; min-width: 0; }
.wa-week-title-row {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    font-size: 14px; font-weight: 800; color: var(--ink); line-height: 1;
}
.wa-week-dates { font-variant-numeric: tabular-nums; }

.wa-week-pill {
    background: var(--blt); color: var(--blue);
    padding: 3px 8px; border-radius: 5px;
    font-size: 10.5px; font-weight: 800; border: 1px solid var(--bbd);
}
.wa-week.current .wa-week-pill {
    background: var(--blue); color: #fff; border-color: var(--blue);
}
.wa-week-current-pill {
    background: #fef3c7; color: #92400e; border: 1px solid #fde68a;
    padding: 3px 8px; border-radius: 5px;
    font-size: 10.5px; font-weight: 800;
    display: inline-flex; align-items: center; gap: 4px;
}
.wa-week-meta-row {
    display: flex; align-items: center; gap: 10px; margin-top: 5px;
    font-size: 10.5px; color: var(--ink3); font-weight: 600;
}
.wa-week-meta-row .dot {
    width: 3px; height: 3px; border-radius: 50%; background: var(--ink3);
}

/* Inline metrics */
.wa-week-metrics {
    display: flex; gap: 0; align-items: stretch;
    border-left: 1px solid var(--bd2); padding-left: 14px;
}
.wa-metric {
    padding: 0 14px; border-right: 1px solid var(--bd2);
    display: flex; flex-direction: column; gap: 2px; min-width: 90px;
}
.wa-metric:last-child { border-right: none; padding-right: 4px; }
.wa-metric-lbl {
    font-size: 8.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .4px;
}
.wa-metric-val {
    font-size: 13.5px; font-weight: 800; color: var(--ink);
    font-variant-numeric: tabular-nums; letter-spacing: -.3px; line-height: 1.1;
}
.wa-metric.payout .wa-metric-val { color: #b45309; }
.wa-metric.margin .wa-metric-val { color: #7e22ce; }
.wa-metric.gross  .wa-metric-val { color: var(--blue); }
.wa-metric.equity .wa-metric-val { color: var(--grn); }

.wa-chevron {
    color: var(--ink3); font-size: 12px;
    transition: transform .25s; margin-left: 6px; flex-shrink: 0;
}
.wa-week.collapsed .wa-chevron { transform: rotate(-90deg); }

/* Week body */
.wa-week-body { max-height: 5000px; overflow: hidden; transition: max-height .35s ease; }
.wa-week.collapsed .wa-week-body { max-height: 0; }
.wa-week-content { padding: 14px; }

/* Section title (inside week body) */
.wa-section-title {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 8px;
}
.wa-section-title-l {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 800; color: var(--ink);
}
.wa-section-title-l i { color: var(--blue); font-size: 12px; }
.wa-section-title-r { font-size: 11px; font-weight: 800; color: var(--ink3); }


/* ════════════════════════════════════════════
   9. INVOICES TABLE
   ════════════════════════════════════════════ */
.wa-tbl-wrap {
    overflow-x: auto; border: 2px solid #1e3a8a;
    border-radius: 5px; margin-bottom: 18px;
}
table.wa-tbl {
    width: 100%; border-collapse: collapse;
    font-family: 'Montserrat', sans-serif; font-size: 11.5px;
}

table.wa-tbl thead th {
    text-align: center; padding: 8px 10px; font-weight: 800;
    color: #fff; background: #1e3a8a; border-right: 1px solid #fff;
    font-size: 10px; text-transform: uppercase; letter-spacing: .3px;
    white-space: nowrap;
}
table.wa-tbl thead th:last-child { border-right: none; }

table.wa-tbl tbody td {
    padding: 7px 10px;
    border-right: 1px solid #93c5fd; border-bottom: 1px solid #93c5fd;
    vertical-align: middle; background: #eff6ff;
    color: var(--ink); font-size: 11px; font-weight: 600;
}
table.wa-tbl tbody td:last-child { border-right: none; }
table.wa-tbl tbody tr:nth-child(even) td { background: #dbeafe; }
table.wa-tbl tbody tr:hover td { background: #bfdbfe !important; }
table.wa-tbl .num {
    text-align: right; font-weight: 700; white-space: nowrap;
    font-variant-numeric: tabular-nums;
}
table.wa-tbl .center { text-align: center; }

/* Column widths */
table.wa-tbl col.col-date        { width: 80px; }
table.wa-tbl col.col-idx         { width: 55px; }
table.wa-tbl col.col-qb          { width: 75px; }
table.wa-tbl col.col-job         { width: 75px; }
table.wa-tbl col.col-addr        { width: auto; }
table.wa-tbl col.col-sq          { width: 55px; }
table.wa-tbl col.col-comp        { width: 100px; }
table.wa-tbl col.col-inv         { width: 100px; }
table.wa-tbl col.col-sub         { width: 130px; }
table.wa-tbl col.col-paid        { width: 100px; }
table.wa-tbl col.col-marg        { width: 70px; }
table.wa-tbl col.col-paid-status { width: 90px; }

/* Margin colors */
.wa-margin-cell { text-align: center; font-weight: 800; font-size: 11px; }
.wa-margin-high { color: var(--grn); background: #d1fae5 !important; }
.wa-margin-med  { color: var(--amb); background: #fef3c7 !important; }
.wa-margin-low  { color: var(--red); background: #fee2e2 !important; }

/* Paid toggle button */
.wa-paid-cell { padding: 4px 6px !important; }
.wa-paid-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    padding: 4px 9px; border-radius: 12px;
    font-size: 9.5px; font-weight: 800;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer; transition: all .15s;
    border: 1.5px solid;
    text-transform: uppercase; letter-spacing: .3px;
    white-space: nowrap;
}
.wa-paid-btn i { font-size: 9px; }
.wa-paid-btn.is-paid {
    background: #d1fae5; color: #064e3b; border-color: var(--grn);
}
.wa-paid-btn.is-paid:hover {
    background: #a7f3d0; box-shadow: 0 2px 6px rgba(34,197,94,.25);
}
.wa-paid-btn.is-unpaid {
    background: #fee2e2; color: #991b1b; border-color: #fca5a5;
}
.wa-paid-btn.is-unpaid:hover {
    background: #fecaca; box-shadow: 0 2px 6px rgba(239,68,68,.25);
}
.wa-paid-btn:disabled { opacity: .5; cursor: wait; }

/* Footer (totals row) */
table.wa-tbl tfoot tr { background: #f59e0b !important; }
table.wa-tbl tfoot td {
    padding: 9px 10px; font-size: 12px; font-weight: 800;
    color: #fff !important; background: #f59e0b !important;
    border-top: 2px solid #d97706; border-bottom: none;
    border-right: 1px solid #fff; font-variant-numeric: tabular-nums;
}
table.wa-tbl tfoot td:last-child { border-right: none; }

/* Empty invoices */
.wa-no-invoices {
    text-align: center; padding: 30px 20px;
    color: var(--ink3); font-weight: 600; font-size: 11.5px;
    background: #fafbfd; border-radius: 8px; margin-bottom: 18px;
}
.wa-no-invoices i {
    font-size: 26px; display: block; margin-bottom: 10px; color: #cbd5e1;
}


/* ════════════════════════════════════════════
   10. OPERATING COSTS DASHBOARD (3 columnas)
   ════════════════════════════════════════════ */
.wa-ops-dashboard {
    background: linear-gradient(to bottom, #fafbfd, #fff);
    border: 1px solid var(--bd); border-radius: 11px;
    padding: 14px;
}
.wa-ops-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1.2fr;
    gap: 12px;
    align-items: stretch;
}

/* Common panel title */
.wa-ops-panel-title {
    display: flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 800; color: var(--ink);
    text-transform: uppercase; letter-spacing: .4px;
    margin-bottom: 10px; padding-bottom: 8px;
    border-bottom: 1px solid var(--bd2);
}
.wa-ops-panel-title i { color: var(--blue); font-size: 11px; }

/* COL 1 — Inputs */
.wa-ops-inputs {
    background: #fff; border: 1px solid var(--bd2); border-radius: 9px;
    padding: 12px; display: flex; flex-direction: column;
}
.wa-cost-inputs-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
}
.wa-cost-field { display: flex; flex-direction: column; gap: 4px; }
.wa-cost-field label {
    display: flex; align-items: center; gap: 5px;
    font-size: 9.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .3px;
}
.wa-cost-field label i { color: var(--ink3); font-size: 9px; width: 10px; }

.wa-cost-input-wrap { position: relative; }
.wa-cost-input-wrap::before {
    content: '$'; position: absolute; left: 9px; top: 50%;
    transform: translateY(-50%);
    color: var(--ink3); font-weight: 800; font-size: 12px;
    pointer-events: none;
}
.wa-cost-field input {
    width: 100%; padding: 8px 10px 8px 22px;
    border: 1.5px solid var(--bd); border-radius: 7px;
    font-size: 12px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    background: #fff; transition: all .15s;
    font-variant-numeric: tabular-nums; text-align: right;
}
.wa-cost-field input:hover { border-color: #b9c0cd; }
.wa-cost-field input:focus {
    outline: none; border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(24,85,224,.12); background: #fefce8;
}

/* COL 2 — Breakdown */
.wa-ops-breakdown {
    background: #fff; border: 1px solid var(--bd2); border-radius: 9px;
    padding: 12px; display: flex; flex-direction: column;
}
.wa-breakdown-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 10px; border-bottom: 1px solid #f1f5f9;
    font-size: 11.5px;
}
.wa-breakdown-row:last-of-type { border-bottom: none; }
.wa-breakdown-lbl {
    font-weight: 700; color: var(--ink2);
    display: flex; align-items: center; gap: 6px;
}
.wa-breakdown-lbl i { font-size: 10px; color: var(--ink3); }
.wa-breakdown-val {
    font-weight: 800; color: var(--ink);
    font-variant-numeric: tabular-nums;
}
.wa-breakdown-row.invoiced {
    background: #f1f5f9; border-radius: 6px; margin-bottom: 4px;
}
.wa-breakdown-row.invoiced .wa-breakdown-val { color: #1e293b; font-size: 13px; }
.wa-breakdown-row.minus .wa-breakdown-val { color: #b45309; }
.wa-breakdown-row.minus .wa-breakdown-val::before { content: '−'; margin-right: 3px; }
.wa-breakdown-row.subtotal {
    background: #fff7ed; border-radius: 6px;
    margin-top: 6px; padding: 10px 10px;
    border-bottom: none;
}
.wa-breakdown-row.subtotal .wa-breakdown-lbl { color: #9a3412; }
.wa-breakdown-row.subtotal .wa-breakdown-val { color: #9a3412; font-size: 12.5px; }

/* COL 3 — Result cards */
.wa-ops-results { display: flex; flex-direction: column; gap: 10px; }
.wa-result-card {
    background: #fff; border: 1.5px solid var(--bd2);
    border-radius: 10px; padding: 14px 16px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; flex: 1;
    transition: all .15s;
}
.wa-result-card .left { display: flex; align-items: center; gap: 10px; }
.wa-result-card .icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.wa-result-card .text-wrap { display: flex; flex-direction: column; gap: 2px; }
.wa-result-card .lbl {
    font-size: 9.5px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .4px;
}
.wa-result-card .sub { font-size: 10px; font-weight: 600; color: var(--ink3); }
.wa-result-card .val {
    font-size: 18px; font-weight: 800;
    font-variant-numeric: tabular-nums; letter-spacing: -.5px;
    text-align: right;
}

.wa-result-card.gross {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-color: #f59e0b;
}
.wa-result-card.gross .icon { background: #f59e0b; color: #fff; }
.wa-result-card.gross .lbl  { color: #92400e; }
.wa-result-card.gross .val  { color: #78350f; font-size: 22px; }

.wa-result-card.margin {
    background: linear-gradient(135deg, #faf5ff, #f3e8ff);
    border-color: #a855f7;
}
.wa-result-card.margin .icon { background: #a855f7; color: #fff; }
.wa-result-card.margin .lbl  { color: #6b21a8; }
.wa-result-card.margin .val  { color: #6b21a8; }

.wa-result-card.equity {
    background: linear-gradient(135deg, var(--glt), #d1fae5);
    border-color: var(--grn);
}
.wa-result-card.equity .icon { background: var(--grn); color: #fff; }
.wa-result-card.equity .lbl  { color: #064e3b; }
.wa-result-card.equity .val  { color: #064e3b; font-size: 22px; }


/* ════════════════════════════════════════════
   11. NOTES + SAVE BUTTON
   ════════════════════════════════════════════ */
.wa-ops-bottom {
    margin-top: 12px;
    display: grid; grid-template-columns: 1fr auto;
    gap: 12px; align-items: end;
}
.wa-notes-field { display: flex; flex-direction: column; gap: 4px; }
.wa-notes-field label {
    display: flex; align-items: center; gap: 5px;
    font-size: 9.5px; font-weight: 800; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .3px;
}
.wa-notes-field textarea {
    padding: 8px 10px; border: 1.5px solid var(--bd); border-radius: 7px;
    font-size: 11.5px; font-family: 'Montserrat', sans-serif;
    resize: vertical; background: #fff; transition: all .15s;
    min-height: 38px; max-height: 80px;
}
.wa-notes-field textarea:focus {
    outline: none; border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(24,85,224,.12);
}

.wa-save-btn {
    padding: 10px 20px; border-radius: 8px;
    background: linear-gradient(135deg, var(--blue), #4f80ff);
    color: #fff; border: none;
    font-size: 12px; font-weight: 800; font-family: 'Montserrat', sans-serif;
    cursor: pointer; transition: all .15s;
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    box-shadow: 0 2px 6px rgba(24,85,224,.25); white-space: nowrap;
    height: 40px; min-width: 160px;
}
.wa-save-btn:hover  { box-shadow: 0 4px 10px rgba(24,85,224,.35); transform: translateY(-1px); }
.wa-save-btn:active { transform: translateY(0); }


/* ════════════════════════════════════════════
   12. PAGINATOR + EMPTY STATE
   ════════════════════════════════════════════ */
.wa-pag {
    display: flex; justify-content: center; align-items: center;
    gap: 5px; margin-top: 18px; padding: 12px;
    background: var(--surf); border: 1px solid var(--bd);
    border-radius: 10px; flex-wrap: wrap;
}
.wa-pag-info {
    font-size: 11px; color: var(--ink3); font-weight: 600; margin-right: 8px;
}
.wa-pag-info strong { color: var(--ink); font-weight: 800; }
.wa-pag-btn {
    min-width: 32px; height: 32px; padding: 0 10px;
    border: 1px solid var(--bd); background: var(--surf); color: var(--ink);
    border-radius: 7px; cursor: pointer;
    font-size: 11.5px; font-weight: 700; font-family: 'Montserrat', sans-serif;
    display: inline-flex; align-items: center; justify-content: center;
    text-decoration: none; transition: all .13s;
}
.wa-pag-btn:hover:not(.disabled) {
    border-color: var(--blue); color: var(--blue); background: var(--blt);
}
.wa-pag-btn.active {
    background: var(--blue); color: #fff; border-color: var(--blue);
}
.wa-pag-btn.disabled { opacity: .35; cursor: not-allowed; pointer-events: none; }

.wa-empty {
    padding: 60px 24px; text-align: center;
    background: var(--surf); border: 1px dashed var(--bd); border-radius: 12px;
}
.wa-empty i {
    font-size: 38px; color: var(--bd);
    display: block; margin-bottom: 14px;
}
.wa-empty-t { font-size: 14px; font-weight: 800; color: var(--ink2); margin-bottom: 4px; }
.wa-empty-s { font-size: 11.5px; font-weight: 600; color: var(--ink3); }


/* ════════════════════════════════════════════
   13. RESPONSIVE
   ════════════════════════════════════════════ */
@media (max-width: 1280px) {
    .wa-ops-grid    { grid-template-columns: 1fr 1fr; }
    .wa-ops-results { grid-column: 1 / -1; flex-direction: row; }
    .wa-week-metrics { display: none; }
}
@media (max-width: 900px) {
    .wa-wrap { padding: 14px; }

    /* Summary bar collapses to 1 column with 3 stacked rows */
    .wa-summary {
        grid-template-columns: 1fr;
        grid-template-areas:
            "title"
            "kpis"
            "period";
        gap: 12px;
    }
    .wa-period-wrap {
        justify-content: flex-start;
    }
    .wa-kpis-row {
        border-left: none; padding-left: 0;
        border-top: 1px solid var(--bd2); padding-top: 10px;
        flex-wrap: wrap;
    }
    .wa-kpi-mini { padding: 4px 14px; }

    .wa-headbar { padding: 12px 14px; }
    .wa-week-head { flex-wrap: wrap; }
    .wa-ops-grid { grid-template-columns: 1fr; }
    .wa-ops-results { flex-direction: column; }
    .wa-cost-inputs-grid { grid-template-columns: 1fr; }
    .wa-ops-bottom { grid-template-columns: 1fr; }
}
</style>

<div class="wa-wrap">

    {{-- ══════════════════════════════════════════
         HEADER BAR
         ══════════════════════════════════════════ --}}
    <div class="wa-headbar">
        <div class="wa-headbar-l">
            <div class="wa-headbar-icon"><i class="fas fa-sack-dollar"></i></div>
            <div>
                <div class="wa-headbar-title">Weekly Accounting</div>
                <div class="wa-headbar-sub">Tuesday — Monday · Pay every Friday</div>
            </div>
        </div>
        <div class="wa-headbar-r">
            <div class="wa-quick-stat">
                <span class="wa-quick-stat-lbl">Weeks</span>
                <span class="wa-quick-stat-val">{{ $totalWeeks }}</span>
            </div>
            <div class="wa-quick-stat">
                <span class="wa-quick-stat-lbl">Equity</span>
                <span class="wa-quick-stat-val">{{ number_format($equityPct, 1) }}%</span>
            </div>
            <button class="wa-settings-btn" onclick="waOpenSettings()">
                <i class="fas fa-cog"></i> Settings
            </button>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         SUMMARY BAR (KPIs + Period filter)
         ══════════════════════════════════════════ --}}
    <div class="wa-summary">
        <div class="wa-summary-l">
            <div class="wa-summary-l-icon"><i class="fas fa-chart-line"></i></div>
            <div>
                <h3>Financial Overview</h3>
                <div class="sub" id="wa-period-subtitle">Year to Date</div>
            </div>
        </div>

        <div class="wa-kpis-row" id="wa-kpis"></div>

        <div class="wa-period-wrap">
            <div class="wa-period-group">
                <button class="wa-period-btn"        data-period="this-week">This Week</button>
                <button class="wa-period-btn"        data-period="last-week">Last Week</button>
                <button class="wa-period-btn"        data-period="this-month">Month</button>
                <button class="wa-period-btn"        data-period="last-3-months">3M</button>
                <button class="wa-period-btn active" data-period="ytd">YTD</button>
                <button class="wa-period-btn"        data-period="all">All</button>
            </div>
            <div class="wa-custom-range">
                <input type="date" id="wa-date-from" onchange="waApplyCustomRange()">
                <span class="sep">→</span>
                <input type="date" id="wa-date-to" onchange="waApplyCustomRange()">
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         CHART CARD
         ══════════════════════════════════════════ --}}
    <div class="wa-chart-card">
        <div class="wa-chart-head">
            <div class="wa-chart-title"><i class="fas fa-chart-bar"></i> Gross per week</div>
            <div class="wa-chart-meta" id="wa-chart-meta"></div>
        </div>
        <div class="wa-chart" id="wa-chart"></div>
    </div>


    {{-- ══════════════════════════════════════════
         LEDGER NAVIGATION (Years + Months + Search)
         ══════════════════════════════════════════ --}}
    @if($indexTree->count() > 0)
        <div class="wa-ledger-nav">
            {{-- Year tabs (sin onclick - se enganchan con event listener desde el JS) --}}
            <div class="wa-ledger-row">
                <span class="wa-ledger-lbl"><i class="fas fa-calendar"></i> Year</span>
                <div class="wa-year-tabs" id="wa-year-tabs">
                    @foreach($indexTree as $year => $months)
                        <button type="button"
                                class="wa-year-tab {{ $year == $activeYear ? 'active' : '' }}"
                                data-year="{{ $year }}">
                            {{ $year }}
                        </button>
                    @endforeach
                </div>

                {{-- Search bar --}}
                <div class="wa-search" id="wa-search-wrap">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text"
                           id="wa-search-input"
                           placeholder="Search by address, job # or QB #..."
                           oninput="waApplySearch()"
                           autocomplete="off">
                    <button type="button" class="wa-search-clear" onclick="waClearSearch()" title="Clear">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            {{-- Month tabs (rendered by JS) --}}
            <div class="wa-ledger-row">
                <span class="wa-ledger-lbl"><i class="fas fa-calendar-day"></i> Month</span>
                <div class="wa-month-tabs" id="wa-month-tabs"></div>
            </div>
        </div>

        {{-- Search status banner --}}
        <div class="wa-search-status" id="wa-search-status">
            <i class="fas fa-search"></i>
            <span>Searching:</span>
            <span class="count" id="wa-search-count">0 results</span>
            <span style="margin-left:auto;font-weight:600;color:#92400e">
                Showing matches across all weeks
            </span>
        </div>
    @endif


    {{-- ══════════════════════════════════════════
         TOOLBAR
         ══════════════════════════════════════════ --}}
    <div class="wa-toolbar">
        <div class="wa-toolbar-info">
            <i class="fas fa-layer-group" style="color:var(--ink3)"></i>
            <strong id="wa-visible-count">{{ $totalWeeks }}</strong> total weeks
        </div>
        <div class="wa-toolbar-actions">
            <button class="wa-ctrl-btn" onclick="waExpandAll()">
                <i class="fas fa-chevron-down"></i> Expand all
            </button>
            <button class="wa-ctrl-btn" onclick="waCollapseAll()">
                <i class="fas fa-chevron-up"></i> Collapse all
            </button>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         WEEKS LOOP
         ══════════════════════════════════════════ --}}
    @forelse($weeks as $idx => $w)
        @php
            $isCurrent = isset($currentWeekKey) && $w['week_key'] === $currentWeekKey;
            $t = $w['totals'];
            $c = $w['costs'];
            $collapsed = ($idx === 0 && $page == 1) ? '' : 'collapsed';
        @endphp

        <div class="wa-week {{ $isCurrent ? 'current' : '' }} {{ $collapsed }}"
             data-week-key="{{ $w['week_key'] }}"
             data-year="{{ $w['pay_date']->format('Y') }}"
             data-month="{{ $w['pay_date']->format('Y-m') }}">

            {{-- Week header --}}
            <div class="wa-week-head" onclick="waToggleWeek(this)">
                <div class="wa-week-num">#{{ $w['week_index'] ?? '?' }}</div>

                <div class="wa-week-info">
                    <div class="wa-week-title-row">
                        <span class="wa-week-pill">Week #{{ $w['week_index'] ?? '?' }}</span>
                        <span class="wa-week-dates">{{ $w['pay_date']->format('m/d/Y') }}</span>
                        @if($isCurrent)
                            <span class="wa-week-current-pill">
                                <i class="fas fa-circle" style="font-size:6px"></i> Current
                            </span>
                        @endif
                    </div>
                    <div class="wa-week-meta-row">
                        <i class="fas fa-file-invoice"></i>
                        {{ $w['invoices_count'] }} invoices
                        <span class="dot"></span>
                        Pay date {{ $w['pay_date']->format('m/d/Y') }}
                    </div>
                </div>

                <div class="wa-week-metrics">
                    <div class="wa-metric invoiced">
                        <span class="wa-metric-lbl">Invoiced</span>
                        <span class="wa-metric-val">${{ number_format($t['invoiced'], 0) }}</span>
                    </div>
                    <div class="wa-metric payout">
                        <span class="wa-metric-lbl">Payout</span>
                        <span class="wa-metric-val" data-chip-payout>${{ number_format($t['payout'], 0) }}</span>
                    </div>
                    <div class="wa-metric margin">
                        <span class="wa-metric-lbl">Margin</span>
                        <span class="wa-metric-val" data-chip-margin>{{ number_format($t['margin'], 0) }}%</span>
                    </div>
                    <div class="wa-metric gross">
                        <span class="wa-metric-lbl">Gross</span>
                        <span class="wa-metric-val" data-chip-gross>${{ number_format($t['gross'], 0) }}</span>
                    </div>
                    <div class="wa-metric equity">
                        <span class="wa-metric-lbl">Equity</span>
                        <span class="wa-metric-val" data-chip-equity>${{ number_format($t['equity'], 0) }}</span>
                    </div>
                </div>

                <span class="wa-chevron"><i class="fas fa-chevron-down"></i></span>
            </div>

            {{-- Week body --}}
            <div class="wa-week-body">
                <div class="wa-week-content">

                    {{-- Section: Invoices --}}
                    <div class="wa-section-title">
                        <div class="wa-section-title-l">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Invoices ({{ $w['invoices_count'] }})
                        </div>
                        <div class="wa-section-title-r">
                            Total Invoiced:
                            <span style="color:var(--blue)">${{ number_format($t['invoiced'], 2) }}</span>
                        </div>
                    </div>

                    @if($w['invoices']->count() > 0)
                        <div class="wa-tbl-wrap">
                            <table class="wa-tbl">
                                <colgroup>
                                    <col class="col-date">
                                    <col class="col-idx">
                                    <col class="col-qb">
                                    <col class="col-job">
                                    <col class="col-addr">
                                    <col class="col-sq">
                                    <col class="col-comp">
                                    <col class="col-inv">
                                    <col class="col-sub">
                                    <col class="col-paid">
                                    <col class="col-marg">
                                    <col class="col-paid-status">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>INDEX</th>
                                        <th>QUICKBOOKS</th>
                                        <th>JOB #</th>
                                        <th>JOB ADDRESS</th>
                                        <th>SQ</th>
                                        <th>COMPANY</th>
                                        <th>TOTAL INVOICED</th>
                                        <th>CREW MANAGER</th>
                                        <th>PAID TO SUB</th>
                                        <th>PROFIT MARGIN</th>
                                        <th>PAID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($w['invoices'] as $inv)
                                        @php
                                            $invMargin = $inv->invoiced > 0
                                                ? (($inv->invoiced - $inv->payout) / $inv->invoiced) * 100
                                                : 0;
                                            $invMClass = $invMargin >= 50
                                                ? 'wa-margin-high'
                                                : ($invMargin >= 30 ? 'wa-margin-med' : 'wa-margin-low');
                                            $subFull = $inv->subcontractor !== '—'
                                                ? $inv->subcontractor
                                                : ($inv->crew_name !== '—' ? $inv->crew_name : '—');
                                            $searchData = strtolower(
                                                ($inv->address ?? '').' '.
                                                ($inv->job_label ?? '').' '.
                                                ($inv->quickbooks ?? '')
                                            );
                                        @endphp
                                        <tr data-search="{{ $searchData }}">
                                            <td class="center">{{ $inv->date->format('m/d/Y') }}</td>
                                            <td class="center"><strong>{{ $w['week_index'] ?? '—' }}</strong></td>
                                            <td class="center">{{ $inv->quickbooks }}</td>
                                            <td class="center">{{ $inv->job_label }}</td>
                                            <td title="{{ $inv->address }}">{{ $inv->address ?: '—' }}</td>
                                            <td class="center">
                                                {{ $inv->sq ? number_format($inv->sq, 2) : '—' }}
                                            </td>
                                            <td class="center" title="{{ $inv->company_name }}">
                                                {{ $inv->company_name }}
                                            </td>
                                            <td class="num">
                                                <strong>${{ number_format($inv->invoiced, 2) }}</strong>
                                            </td>
                                            <td title="Crew: {{ $inv->crew_name }} | Sub: {{ $inv->subcontractor }}">
                                                {{ $subFull }}
                                            </td>
                                            <td class="num">${{ number_format($inv->payout, 2) }}</td>
                                            <td class="wa-margin-cell {{ $invMClass }}">
                                                {{ number_format($invMargin, 0) }}%
                                            </td>
                                            <td class="center wa-paid-cell">
                                                <button type="button"
                                                        class="wa-paid-btn {{ $inv->subcontractor_paid ? 'is-paid' : 'is-unpaid' }}"
                                                        data-invoice-id="{{ $inv->id }}"
                                                        onclick="waTogglePaid(this)"
                                                        title="{{ $inv->subcontractor_paid
                                                            ? 'Paid on '.($inv->subcontractor_paid_at ? $inv->subcontractor_paid_at->format('m/d/Y') : 'unknown')
                                                            : 'Mark as paid' }}">
                                                    @if($inv->subcontractor_paid)
                                                        <i class="fas fa-check-circle"></i> Paid
                                                    @else
                                                        <i class="fas fa-circle"></i> Unpaid
                                                    @endif
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align:right">TOTALS:</td>
                                        <td class="center">
                                            {{ $t['sq'] > 0 ? number_format($t['sq'], 2) : '—' }}
                                        </td>
                                        <td></td>
                                        <td class="num">
                                            <strong>${{ number_format($t['invoiced'], 2) }}</strong>
                                        </td>
                                        <td></td>
                                        <td class="num">${{ number_format($t['sub_paid'], 2) }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="wa-no-invoices">
                            <i class="fas fa-file-invoice"></i>
                            No invoices for this week yet.
                        </div>
                    @endif

                    {{-- Section: Operating Costs --}}
                    <div class="wa-section-title">
                        <div class="wa-section-title-l">
                            <i class="fas fa-coins"></i>
                            Operating Costs & Week Calculations
                        </div>
                        <div class="wa-section-title-r" data-sec-ops>
                            Ops Total:
                            <span style="color:#b45309">${{ number_format($c['ops_total'], 2) }}</span>
                        </div>
                    </div>

                    <form data-form="{{ $w['week_key'] }}"
                          data-week-start="{{ $w['week_start']->toDateString() }}"
                          data-week-end="{{ $w['week_end']->toDateString() }}"
                          data-invoiced="{{ $t['invoiced'] }}"
                          data-sub-paid="{{ $t['sub_paid'] }}"
                          onsubmit="return waSaveCosts(event, '{{ $w['week_key'] }}')">

                        <div class="wa-ops-dashboard">
                            <div class="wa-ops-grid">

                                {{-- COL 1: Inputs --}}
                                <div class="wa-ops-inputs">
                                    <div class="wa-ops-panel-title">
                                        <i class="fas fa-pencil-alt"></i> Enter weekly expenses
                                    </div>
                                    <div class="wa-cost-inputs-grid">
                                        <div class="wa-cost-field">
                                            <label><i class="fas fa-dumpster"></i> Landfill</label>
                                            <div class="wa-cost-input-wrap">
                                                <input type="number" name="landfill" step="0.01" min="0"
                                                       value="{{ number_format($c['landfill'], 2, '.', '') }}"
                                                       oninput="waRecalc('{{ $w['week_key'] }}')">
                                            </div>
                                        </div>
                                        <div class="wa-cost-field">
                                            <label><i class="fas fa-gas-pump"></i> Fuel</label>
                                            <div class="wa-cost-input-wrap">
                                                <input type="number" name="fuel" step="0.01" min="0"
                                                       value="{{ number_format($c['fuel'], 2, '.', '') }}"
                                                       oninput="waRecalc('{{ $w['week_key'] }}')">
                                            </div>
                                        </div>
                                        <div class="wa-cost-field">
                                            <label><i class="fas fa-ellipsis-h"></i> Other</label>
                                            <div class="wa-cost-input-wrap">
                                                <input type="number" name="other" step="0.01" min="0"
                                                       value="{{ number_format($c['other'], 2, '.', '') }}"
                                                       oninput="waRecalc('{{ $w['week_key'] }}')">
                                            </div>
                                        </div>
                                        <div class="wa-cost-field">
                                            <label><i class="fas fa-truck"></i> Driver</label>
                                            <div class="wa-cost-input-wrap">
                                                <input type="number" name="driver" step="0.01" min="0"
                                                       value="{{ number_format($c['driver'], 2, '.', '') }}"
                                                       oninput="waRecalc('{{ $w['week_key'] }}')">
                                            </div>
                                        </div>
                                        <div class="wa-cost-field">
                                            <label><i class="fas fa-user-tie"></i> Superint.</label>
                                            <div class="wa-cost-input-wrap">
                                                <input type="number" name="superintendent" step="0.01" min="0"
                                                       value="{{ number_format($c['superintendent'], 2, '.', '') }}"
                                                       oninput="waRecalc('{{ $w['week_key'] }}')">
                                            </div>
                                        </div>
                                        <div class="wa-cost-field">
                                            <label><i class="fas fa-crown"></i> CEO</label>
                                            <div class="wa-cost-input-wrap">
                                                <input type="number" name="ceo" step="0.01" min="0"
                                                       value="{{ number_format($c['ceo'], 2, '.', '') }}"
                                                       oninput="waRecalc('{{ $w['week_key'] }}')">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- COL 2: Breakdown --}}
                                <div class="wa-ops-breakdown">
                                    <div class="wa-ops-panel-title">
                                        <i class="fas fa-calculator"></i> Calculation breakdown
                                    </div>
                                    <div class="wa-breakdown-row invoiced">
                                        <span class="wa-breakdown-lbl">
                                            <i class="fas fa-arrow-down"></i> Total Invoiced
                                        </span>
                                        <span class="wa-breakdown-val">
                                            ${{ number_format($t['invoiced'], 2) }}
                                        </span>
                                    </div>
                                    <div class="wa-breakdown-row minus">
                                        <span class="wa-breakdown-lbl">
                                            <i class="fas fa-hard-hat"></i> Paid to Subs
                                        </span>
                                        <span class="wa-breakdown-val">
                                            ${{ number_format($t['sub_paid'], 2) }}
                                        </span>
                                    </div>
                                    <div class="wa-breakdown-row minus">
                                        <span class="wa-breakdown-lbl">
                                            <i class="fas fa-coins"></i> Operating Costs
                                        </span>
                                        <span class="wa-breakdown-val" data-totals-ops>
                                            ${{ number_format($c['ops_total'], 2) }}
                                        </span>
                                    </div>
                                    <div class="wa-breakdown-row subtotal">
                                        <span class="wa-breakdown-lbl">
                                            <i class="fas fa-money-bill-wave"></i> Total Payout
                                        </span>
                                        <span class="wa-breakdown-val" data-totals-payout>
                                            ${{ number_format($t['payout'], 2) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- COL 3: Result cards --}}
                                <div class="wa-ops-results">
                                    <div class="wa-result-card gross">
                                        <div class="left">
                                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                                            <div class="text-wrap">
                                                <span class="lbl">Weekly Gross</span>
                                                <span class="sub">Invoiced − Payout</span>
                                            </div>
                                        </div>
                                        <span class="val" data-totals-gross>
                                            ${{ number_format($t['gross'], 2) }}
                                        </span>
                                    </div>
                                    <div class="wa-result-card margin">
                                        <div class="left">
                                            <div class="icon"><i class="fas fa-percentage"></i></div>
                                            <div class="text-wrap">
                                                <span class="lbl">Margin</span>
                                                <span class="sub">Gross / Invoiced</span>
                                            </div>
                                        </div>
                                        <span class="val" data-totals-margin>
                                            {{ number_format($t['margin'], 1) }}%
                                        </span>
                                    </div>
                                    <div class="wa-result-card equity">
                                        <div class="left">
                                            <div class="icon"><i class="fas fa-landmark"></i></div>
                                            <div class="text-wrap">
                                                <span class="lbl">A&F Payment</span>
                                                <span class="sub">{{ number_format($equityPct, 1) }}% of Gross</span>
                                            </div>
                                        </div>
                                        <span class="val" data-totals-equity>
                                            ${{ number_format($t['equity'], 2) }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                            {{-- Notes + Save --}}
                            <div class="wa-ops-bottom">
                                <div class="wa-notes-field">
                                    <label><i class="fas fa-sticky-note"></i> Week notes</label>
                                    <textarea name="notes" rows="2"
                                              placeholder="Add notes about this week's expenses, special situations, etc...">{{ $c['notes'] }}</textarea>
                                </div>
                                <button type="submit" class="wa-save-btn">
                                    <i class="fas fa-save"></i> Save Week
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    @empty
        <div class="wa-empty">
            <i class="fas fa-calendar-xmark"></i>
            <div class="wa-empty-t">No weeks found</div>
            <div class="wa-empty-s">Create some invoices first to see weekly accounting data.</div>
        </div>
    @endforelse


    {{-- ══════════════════════════════════════════
         PAGINATOR
         ══════════════════════════════════════════ --}}
    @if($totalPages > 1)
        <div class="wa-pag">
            <div class="wa-pag-info">
                Page <strong>{{ $page }}</strong> of <strong>{{ $totalPages }}</strong>
            </div>
            <a class="wa-pag-btn {{ $page == 1 ? 'disabled' : '' }}"
               href="{{ $page > 1 ? route('superadmin.weekly-accounting.index', ['page' => $page - 1]) : '#' }}">←</a>

            @php
                $startP = max(1, $page - 2);
                $endP   = min($totalPages, $page + 2);
            @endphp

            @if($startP > 1)
                <a class="wa-pag-btn"
                   href="{{ route('superadmin.weekly-accounting.index', ['page' => 1]) }}">1</a>
                @if($startP > 2)
                    <span class="wa-pag-btn disabled">···</span>
                @endif
            @endif

            @for($p = $startP; $p <= $endP; $p++)
                <a class="wa-pag-btn {{ $p == $page ? 'active' : '' }}"
                   href="{{ route('superadmin.weekly-accounting.index', ['page' => $p]) }}">{{ $p }}</a>
            @endfor

            @if($endP < $totalPages)
                @if($endP < $totalPages - 1)
                    <span class="wa-pag-btn disabled">···</span>
                @endif
                <a class="wa-pag-btn"
                   href="{{ route('superadmin.weekly-accounting.index', ['page' => $totalPages]) }}">{{ $totalPages }}</a>
            @endif

            <a class="wa-pag-btn {{ $page >= $totalPages ? 'disabled' : '' }}"
               href="{{ $page < $totalPages ? route('superadmin.weekly-accounting.index', ['page' => $page + 1]) : '#' }}">→</a>
        </div>
    @endif

</div>

<script>
/* ══════════════════════════════════════════════════════════════
   GLOBAL CONSTANTS
   ══════════════════════════════════════════════════════════════ */
const WA_EQUITY_PCT     = {{ $equityPct }};
const WA_CSRF           = document.querySelector('meta[name="csrf-token"]').content;
const WA_WEEK_START_DAY = {{ $weekStartDay ?? 2 }};
const WA_ALL_INVOICES   = @json($allPeriodsData['invoices']);
const WA_INDEX_TREE     = @json($indexTree ?? new \stdClass());

const WA_ROUTES = {
    saveCosts:      '{{ route('superadmin.weekly-accounting.save-costs') }}',
    updateSettings: '{{ route('superadmin.weekly-accounting.update-settings') }}',
    togglePaid:     '{{ route('superadmin.weekly-accounting.toggle-paid') }}',
};

let waActiveYear   = '{{ $activeYear  ?? '' }}';
let waActiveMonth  = '{{ $activeMonth ?? '' }}';
let waSearchQuery  = '';
let waActivePeriod = 'ytd';


/* ══════════════════════════════════════════════════════════════
   FORMATTERS
   ══════════════════════════════════════════════════════════════ */
function waFmt(n)      { return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
function waFmtShort(n) { return '$' + Number(n).toLocaleString('en-US', { maximumFractionDigits: 0 }); }
function waFmtPct(n,d) { return Number(n).toFixed(d ?? 0) + '%'; }


/* ══════════════════════════════════════════════════════════════
   WEEK CARD COLLAPSE/EXPAND
   ══════════════════════════════════════════════════════════════ */
function waToggleWeek(headEl) { headEl.closest('.wa-week').classList.toggle('collapsed'); }
function waExpandAll()   { document.querySelectorAll('.wa-week').forEach(w => w.classList.remove('collapsed')); }
function waCollapseAll() { document.querySelectorAll('.wa-week').forEach(w => w.classList.add('collapsed')); }


/* ══════════════════════════════════════════════════════════════
   LIVE RECALC (operating costs)
   ══════════════════════════════════════════════════════════════ */
function waRecalc(weekKey) {
    const form     = document.querySelector(`[data-form="${weekKey}"]`);
    const invoiced = parseFloat(form.dataset.invoiced) || 0;
    const subPaid  = parseFloat(form.dataset.subPaid)  || 0;

    const landfill       = parseFloat(form.landfill.value)       || 0;
    const fuel           = parseFloat(form.fuel.value)           || 0;
    const other          = parseFloat(form.other.value)          || 0;
    const driver         = parseFloat(form.driver.value)         || 0;
    const superintendent = parseFloat(form.superintendent.value) || 0;
    const ceo            = parseFloat(form.ceo.value)            || 0;

    const opsTotal          = landfill + fuel + other + driver + superintendent + ceo;
    const payout            = subPaid + opsTotal;
    const grossBeforeEquity = invoiced - payout;
    const equity            = grossBeforeEquity * (WA_EQUITY_PCT / 100);
    const gross             = grossBeforeEquity - equity;
    const margin            = invoiced > 0 ? (gross / invoiced) * 100 : 0;

    const card = document.querySelector(`[data-week-key="${weekKey}"]`);

    // Inline metrics in header
    card.querySelector('[data-chip-payout]').textContent = waFmtShort(payout);
    card.querySelector('[data-chip-margin]').textContent = waFmtPct(margin);
    card.querySelector('[data-chip-gross]').textContent  = waFmtShort(gross);
    card.querySelector('[data-chip-equity]').textContent = waFmtShort(equity);

    // Section title
    const secOps = card.querySelector('[data-sec-ops]');
    if (secOps) {
        secOps.innerHTML = `Ops Total: <span style="color:#b45309">${waFmt(opsTotal)}</span>`;
    }

    // Breakdown
    const opsBreakBox = card.querySelector('[data-totals-ops]');
    const payoutBox   = card.querySelector('[data-totals-payout]');
    if (opsBreakBox) opsBreakBox.textContent = waFmt(opsTotal);
    if (payoutBox)   payoutBox.textContent   = waFmt(payout);

    // Result cards
    const grossBox  = card.querySelector('[data-totals-gross]');
    const marginBox = card.querySelector('[data-totals-margin]');
    const equityBox = card.querySelector('[data-totals-equity]');
    if (grossBox)  grossBox.textContent  = waFmt(gross);
    if (marginBox) marginBox.textContent = waFmtPct(margin, 1);
    if (equityBox) equityBox.textContent = waFmt(equity);
}


/* ══════════════════════════════════════════════════════════════
   SAVE OPERATING COSTS (AJAX)
   ══════════════════════════════════════════════════════════════ */
async function waSaveCosts(e, weekKey) {
    e.preventDefault();
    const form = document.querySelector(`[data-form="${weekKey}"]`);

    const body = new FormData();
    body.append('_token',         WA_CSRF);
    body.append('week_start',     form.dataset.weekStart);
    body.append('week_end',       form.dataset.weekEnd);
    body.append('landfill',       form.landfill.value       || 0);
    body.append('fuel',           form.fuel.value           || 0);
    body.append('other',          form.other.value          || 0);
    body.append('driver',         form.driver.value         || 0);
    body.append('superintendent', form.superintendent.value || 0);
    body.append('ceo',            form.ceo.value            || 0);
    body.append('notes',          form.notes.value          || '');

    try {
        const resp = await fetch(WA_ROUTES.saveCosts, {
            method: 'POST', body,
            headers: { 'Accept': 'application/json' }
        });
        const json = await resp.json();
        if (json.success) {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: 'Saved', timer: 1800, showConfirmButton: false,
                customClass: { popup: 'swal-montserrat' }
            });
        } else {
            throw new Error(json.message || 'Save failed');
        }
    } catch (err) {
        Swal.fire({
            icon: 'error', title: 'Error',
            text: err.message || 'Network error',
            customClass: { popup: 'swal-montserrat' }
        });
    }
    return false;
}


/* ══════════════════════════════════════════════════════════════
   TOGGLE PAID (AJAX)
   ══════════════════════════════════════════════════════════════ */
async function waTogglePaid(btn) {
    if (btn.disabled) return;
    const invoiceId = btn.dataset.invoiceId;
    const isPaidNow = btn.classList.contains('is-paid');
    const newPaid   = !isPaidNow;

    btn.disabled = true;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        const body = new FormData();
        body.append('_token',     WA_CSRF);
        body.append('invoice_id', invoiceId);
        body.append('paid',       newPaid ? 1 : 0);

        const resp = await fetch(WA_ROUTES.togglePaid, {
            method: 'POST', body,
            headers: { 'Accept': 'application/json' }
        });
        const json = await resp.json();

        if (json.success) {
            if (json.paid) {
                btn.classList.remove('is-unpaid');
                btn.classList.add('is-paid');
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Paid';
                btn.title = 'Paid on ' + (json.paid_at || 'now');
            } else {
                btn.classList.remove('is-paid');
                btn.classList.add('is-unpaid');
                btn.innerHTML = '<i class="fas fa-circle"></i> Unpaid';
                btn.title = 'Mark as paid';
            }
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: json.paid ? 'Marked as paid' : 'Marked as unpaid',
                timer: 1500, showConfirmButton: false,
                customClass: { popup: 'swal-montserrat' }
            });
        } else {
            throw new Error(json.message || 'Toggle failed');
        }
    } catch (err) {
        btn.innerHTML = originalHtml;
        Swal.fire({
            icon: 'error', title: 'Error',
            text: err.message || 'Network error',
            customClass: { popup: 'swal-montserrat' }
        });
    } finally {
        btn.disabled = false;
    }
}


/* ══════════════════════════════════════════════════════════════
   PERIOD FILTER (Summary KPIs + Chart)
   ══════════════════════════════════════════════════════════════ */
function waGetPeriodRange(period) {
    const now = new Date();
    let from, to = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);

    switch (period) {
        case 'this-week': {
            const d = new Date(now);
            const diff = (d.getDay() - WA_WEEK_START_DAY + 7) % 7;
            from = new Date(d.getFullYear(), d.getMonth(), d.getDate() - diff);
            to   = new Date(from.getFullYear(), from.getMonth(), from.getDate() + 6, 23, 59, 59);
            break;
        }
        case 'last-week': {
            const d = new Date(now);
            const diff = (d.getDay() - WA_WEEK_START_DAY + 7) % 7;
            const thisStart = new Date(d.getFullYear(), d.getMonth(), d.getDate() - diff);
            from = new Date(thisStart.getFullYear(), thisStart.getMonth(), thisStart.getDate() - 7);
            to   = new Date(from.getFullYear(), from.getMonth(), from.getDate() + 6, 23, 59, 59);
            break;
        }
        case 'this-month':
            from = new Date(now.getFullYear(), now.getMonth(), 1);
            to   = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
            break;
        case 'last-3-months':
            from = new Date(now.getFullYear(), now.getMonth() - 3, 1);
            break;
        case 'ytd':
            from = new Date(now.getFullYear(), 0, 1);
            break;
        case 'all':
        default:
            from = new Date(2000, 0, 1);
    }
    return { from, to };
}

function waRenderDashboard() {
    let from, to;
    const customF = document.getElementById('wa-date-from').value;
    const customT = document.getElementById('wa-date-to').value;

    if (waActivePeriod === 'custom' && customF && customT) {
        from = new Date(customF + 'T00:00:00');
        to   = new Date(customT + 'T23:59:59');
    } else {
        const r = waGetPeriodRange(waActivePeriod);
        from = r.from; to = r.to;
    }

    const filtered = WA_ALL_INVOICES.filter(i => {
        const d = new Date(i.date + 'T00:00:00');
        return d >= from && d <= to;
    });

    // Group by week for chart
    const byWeek = {};
    filtered.forEach(i => {
        const d = new Date(i.date + 'T00:00:00');
        const diff = (d.getDay() - WA_WEEK_START_DAY + 7) % 7;
        const ws = new Date(d.getFullYear(), d.getMonth(), d.getDate() - diff);
        const key = ws.toISOString().split('T')[0];
        if (!byWeek[key]) byWeek[key] = { invoiced: 0, payout: 0, date: ws };
        byWeek[key].invoiced += parseFloat(i.invoiced) || 0;
        byWeek[key].payout   += parseFloat(i.payout)   || 0;
    });

    // Totals
    let totInvoiced = 0, totPayoutSubs = 0;
    filtered.forEach(i => {
        totInvoiced   += parseFloat(i.invoiced) || 0;
        totPayoutSubs += parseFloat(i.payout)   || 0;
    });

    const totGross  = totInvoiced - totPayoutSubs;
    const avgMargin = totInvoiced > 0 ? (totGross / totInvoiced) * 100 : 0;
    const totEquity = totGross * (WA_EQUITY_PCT / 100);
    const weeksCnt  = Object.keys(byWeek).length;

    // Period label
    const labels = {
        'this-week':     'This Week',
        'last-week':     'Last Week',
        'this-month':    'This Month',
        'last-3-months': 'Last 3 Months',
        'ytd':           'Year to Date',
        'all':           'All Time',
        'custom':        'Custom Range'
    };
    document.getElementById('wa-period-subtitle').textContent = labels[waActivePeriod];
    document.getElementById('wa-chart-meta').textContent =
        `${weeksCnt} week${weeksCnt !== 1 ? 's' : ''} · ${filtered.length} invoices`;

    // KPIs
    document.getElementById('wa-kpis').innerHTML = `
        <div class="wa-kpi-mini invoiced">
            <span class="wa-kpi-mini-lbl">Invoiced</span>
            <span class="wa-kpi-mini-val">${waFmt(totInvoiced)}</span>
        </div>
        <div class="wa-kpi-mini payout">
            <span class="wa-kpi-mini-lbl">Payout</span>
            <span class="wa-kpi-mini-val">${waFmt(totPayoutSubs)}</span>
        </div>
        <div class="wa-kpi-mini gross">
            <span class="wa-kpi-mini-lbl">Gross</span>
            <span class="wa-kpi-mini-val">${waFmt(totGross)}</span>
        </div>
        <div class="wa-kpi-mini">
            <span class="wa-kpi-mini-lbl">Avg Margin</span>
            <span class="wa-kpi-mini-val">${avgMargin.toFixed(1)}%</span>
        </div>
        <div class="wa-kpi-mini equity">
            <span class="wa-kpi-mini-lbl">Equity ${WA_EQUITY_PCT.toFixed(1)}%</span>
            <span class="wa-kpi-mini-val">${waFmt(totEquity)}</span>
        </div>
    `;

    // Chart
    const chartEl = document.getElementById('wa-chart');
    const sorted  = Object.values(byWeek).sort((a, b) => a.date - b.date);

    if (sorted.length === 0) {
        chartEl.innerHTML = '<div class="wa-chart-empty">No data for this period</div>';
        return;
    }

    const maxVal = Math.max(...sorted.map(w => Math.max(w.invoiced - w.payout, 1)));
    let html = '';
    sorted.forEach((w) => {
        const gross = w.invoiced - w.payout;
        const h     = Math.max(2, (gross / maxVal) * 100);
        const lbl   = w.date.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit' });
        html += `
            <div class="wa-bar-group">
                <div class="wa-bar-wrap">
                    <div class="wa-bar" style="height: ${h}%">
                        <div class="tip">${lbl}: ${waFmt(gross)}</div>
                    </div>
                </div>
                <div class="wa-chart-lbl">${lbl}</div>
            </div>
        `;
    });
    chartEl.innerHTML = html;
}

function waSetPeriod(period) {
    waActivePeriod = period;
    document.querySelectorAll('.wa-period-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.period === period);
    });
    if (period !== 'custom') {
        document.getElementById('wa-date-from').value = '';
        document.getElementById('wa-date-to').value   = '';
    }
    waRenderDashboard();
}

function waApplyCustomRange() {
    const f = document.getElementById('wa-date-from').value;
    const t = document.getElementById('wa-date-to').value;
    if (f && t) {
        waActivePeriod = 'custom';
        document.querySelectorAll('.wa-period-btn').forEach(b => b.classList.remove('active'));
        waRenderDashboard();
    }
}


/* ══════════════════════════════════════════════════════════════
   LEDGER NAVIGATION (Year/Month tabs)
   ══════════════════════════════════════════════════════════════ */
function waRenderMonthTabs() {
    const wrap = document.getElementById('wa-month-tabs');
    if (!wrap) return;

    const yearMonths = WA_INDEX_TREE[waActiveYear] || [];

    if (yearMonths.length === 0) {
        wrap.innerHTML = '<span class="wa-month-tabs-empty">No data for this year</span>';
        return;
    }

    let html = '';
    yearMonths.forEach(m => {
        const active = m.month_key === waActiveMonth ? 'active' : '';
        html += `
            <button type="button" class="wa-month-tab ${active}"
                    data-month="${m.month_key}">
                ${m.month_label}
                <span class="count">${m.count}</span>
            </button>
        `;
    });
    wrap.innerHTML = html;

    // ⭐ Re-attach event listeners después de renderizar (porque innerHTML los borra)
    wrap.querySelectorAll('.wa-month-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            console.log('[Month click]', btn.dataset.month);
            waSelectMonth(btn.dataset.month);
        });
    });
}

function waSelectYear(year) {
    console.log('[waSelectYear]', year);
    waActiveYear = String(year);

    // Si el mes activo no pertenece al año, tomar el primero
    const months = WA_INDEX_TREE[waActiveYear] || [];
    if (months.length > 0) {
        const found = months.find(m => m.month_key === waActiveMonth);
        if (!found) waActiveMonth = months[0].month_key;
    }

    document.querySelectorAll('.wa-year-tab').forEach(t => {
        t.classList.toggle('active', t.dataset.year === waActiveYear);
    });

    waRenderMonthTabs();
    waApplyFilters();
}

function waSelectMonth(monthKey) {
    console.log('[waSelectMonth]', monthKey);
    waActiveMonth = monthKey;
    document.querySelectorAll('.wa-month-tab').forEach(t => {
        t.classList.toggle('active', t.dataset.month === monthKey);
    });
    waApplyFilters();
}


/* ══════════════════════════════════════════════════════════════
   SEARCH
   ══════════════════════════════════════════════════════════════ */
function waApplySearch() {
    const input  = document.getElementById('wa-search-input');
    const wrap   = document.getElementById('wa-search-wrap');
    const status = document.getElementById('wa-search-status');

    waSearchQuery = (input.value || '').trim().toLowerCase();
    wrap.classList.toggle('has-value', waSearchQuery.length > 0);
    status.classList.toggle('active', waSearchQuery.length > 0);

    waApplyFilters();
}

function waClearSearch() {
    document.getElementById('wa-search-input').value = '';
    waSearchQuery = '';
    document.getElementById('wa-search-wrap').classList.remove('has-value');
    document.getElementById('wa-search-status').classList.remove('active');
    waApplyFilters();
}


/* ══════════════════════════════════════════════════════════════
   MAIN FILTER (combina month tab + search)
   ══════════════════════════════════════════════════════════════ */
function waApplyFilters() {
    const weeks = document.querySelectorAll('.wa-week');
    let visibleWeeks = 0;
    let totalMatches = 0;
    const searching  = waSearchQuery.length > 0;

    console.log('[waApplyFilters] active month:', waActiveMonth, '| searching:', searching, '| weeks:', weeks.length);

    weeks.forEach(weekEl => {
        const weekMonth = weekEl.dataset.month;
        const rows      = weekEl.querySelectorAll('table.wa-tbl tbody tr');

        let weekHasMatch = false;

        // Filter rows by search
        rows.forEach(row => {
            const haystack = (row.dataset.search || '').toLowerCase();
            const isMatch  = !searching || haystack.includes(waSearchQuery);

            if (searching) {
                row.classList.toggle('search-hidden', !isMatch);
                if (isMatch) {
                    weekHasMatch = true;
                    totalMatches++;
                }
            } else {
                row.classList.remove('search-hidden');
            }
        });

        // Decide week visibility
        if (searching) {
            // Search mode: show only weeks with matches (any month)
            if (weekHasMatch) {
                weekEl.classList.remove('search-hidden');
                weekEl.classList.remove('month-hidden');
                weekEl.classList.remove('collapsed'); // auto-expand
                visibleWeeks++;
            } else {
                weekEl.classList.add('search-hidden');
            }
        } else {
            // Normal mode: filter by active month
            const showMonth = (weekMonth === waActiveMonth);
            weekEl.classList.toggle('month-hidden', !showMonth);
            weekEl.classList.remove('search-hidden');
            if (showMonth) visibleWeeks++;
        }
    });

    // Update counters
    const countEl = document.getElementById('wa-visible-count');
    if (countEl) countEl.textContent = visibleWeeks;

    const searchCountEl = document.getElementById('wa-search-count');
    if (searchCountEl && searching) {
        searchCountEl.textContent = `${totalMatches} ${totalMatches === 1 ? 'result' : 'results'}`;
    }

    console.log('[waApplyFilters] → visibleWeeks:', visibleWeeks, '| matches:', totalMatches);
}


/* ══════════════════════════════════════════════════════════════
   SETTINGS MODAL (equity %)
   ══════════════════════════════════════════════════════════════ */
function waOpenSettings() {
    Swal.fire({
        title: 'Weekly Accounting Settings',
        html: `
            <div style="text-align:left; font-family:'Montserrat',sans-serif">
                <label style="font-size:11px;font-weight:800;color:#8c95a6;text-transform:uppercase;letter-spacing:.5px">
                    A&F Equity Percentage
                </label>
                <div style="display:flex;gap:6px;align-items:center;margin-top:6px">
                    <input id="wa-cfg-equity" type="number" step="0.1" min="0" max="100" value="${WA_EQUITY_PCT}"
                           style="flex:1;padding:10px 12px;border:1.5px solid #e4e7ed;border-radius:8px;font-size:14px;font-weight:700;font-family:inherit;text-align:center">
                    <span style="font-weight:800;color:#8c95a6">%</span>
                </div>
                <div style="font-size:11px;color:#8c95a6;margin-top:6px">
                    Percentage of Weekly Gross allocated to the company (A&F Payment).
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save',
        cancelButtonText:  'Cancel',
        confirmButtonColor: '#1855e0',
        cancelButtonColor:  '#8c95a6',
        customClass: { popup: 'swal-montserrat' },
        preConfirm: () => {
            const val = parseFloat(document.getElementById('wa-cfg-equity').value);
            if (isNaN(val) || val < 0 || val > 100) {
                Swal.showValidationMessage('Enter a value between 0 and 100');
                return false;
            }
            return val;
        }
    }).then(async result => {
        if (!result.isConfirmed) return;

        const body = new FormData();
        body.append('_token', WA_CSRF);
        body.append('equity_percentage', result.value);

        try {
            const resp = await fetch(WA_ROUTES.updateSettings, {
                method: 'POST', body,
                headers: { 'Accept': 'application/json' }
            });
            const json = await resp.json();
            if (json.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Settings saved',
                    text: 'Reloading to apply new equity %...',
                    timer: 1500, showConfirmButton: false,
                    customClass: { popup: 'swal-montserrat' }
                }).then(() => location.reload());
            }
        } catch (e) {
            Swal.fire({
                icon: 'error', title: 'Error', text: e.message,
                customClass: { popup: 'swal-montserrat' }
            });
        }
    });
}


/* ══════════════════════════════════════════════════════════════
   INITIALIZATION
   ══════════════════════════════════════════════════════════════ */

// Period filter buttons
document.querySelectorAll('.wa-period-btn').forEach(btn => {
    btn.addEventListener('click', () => waSetPeriod(btn.dataset.period));
});

// ⭐ Year tabs (event listeners en lugar de onclick inline)
document.querySelectorAll('.wa-year-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        console.log('[Year click]', btn.dataset.year);
        waSelectYear(btn.dataset.year);
    });
});

// Initial render
console.log('[INIT] activeYear:', waActiveYear, '| activeMonth:', waActiveMonth);
console.log('[INIT] WA_INDEX_TREE:', WA_INDEX_TREE);
waRenderMonthTabs();
waApplyFilters();
waRenderDashboard();
</script>

@endsection