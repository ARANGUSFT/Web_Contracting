@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --slate-900: #0f172a;
        --slate-800: #1e293b;
        --slate-700: #334155;
        --slate-600: #475569;
        --slate-500: #64748b;
        --slate-400: #94a3b8;
        --slate-300: #cbd5e1;
        --slate-200: #e2e8f0;
        --slate-100: #f1f5f9;
        --white:        #ffffff;
        --accent:       #dc2626;
        --accent-dark:  #991b1b;
        --accent-light: #fff1f2;
        --accent-ring:  rgba(220,38,38,0.12);
        --auto-bg:      #f0f9ff;
        --auto-bd:      #bae6fd;
        --auto-tx:      #0369a1;
        --success:      #10b981;
        --danger:       #ef4444;
    }

    .em-form-page {
        font-family: 'Montserrat', sans-serif;
        background: var(--slate-100);
        min-height: 100vh;
        padding: 2rem 1rem 3rem;
    }

    /* ── Hero ── */
    .form-hero {
        background: var(--slate-900);
        border-radius: 16px;
        padding: 1.6rem 2.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
        flex-wrap: wrap;
        position: relative;
        overflow: hidden;
    }
    .form-hero::after {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #f87171, var(--accent));
        border-radius: 16px 0 0 16px;
    }
    .form-hero::before {
        content: '';
        position: absolute; right: -60px; top: -60px;
        width: 220px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle, rgba(220,38,38,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-eyebrow {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--slate-400); margin-bottom: 0.3rem;
        display: flex; align-items: center; gap: 0.35rem;
    }
    .hero-title {
        font-size: 1.4rem; font-weight: 800;
        color: var(--white); margin: 0 0 0.25rem;
        letter-spacing: -0.025em; line-height: 1.2;
    }
    .hero-subtitle { font-size: 0.78rem; color: var(--slate-400); }
    .hero-logo {
        width: 56px; height: 56px; border-radius: 10px;
        object-fit: contain; background: rgba(255,255,255,0.06);
        padding: 6px; flex-shrink: 0; position: relative; z-index: 1;
    }
    .btn-hero-back {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-family: 'Montserrat', sans-serif; font-weight: 600;
        font-size: 0.75rem; padding: 0.4rem 0.9rem;
        border-radius: 8px; text-decoration: none;
        background: rgba(255,255,255,0.07); color: var(--slate-400);
        border: 1px solid rgba(255,255,255,0.1);
        margin-top: 0.65rem; transition: all 0.18s;
    }
    .btn-hero-back:hover { color: var(--white); background: rgba(255,255,255,0.13); }

    /* ── Steps bar ── */
    .steps-bar {
        background: var(--white);
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 2rem;
        border: 1px solid var(--slate-200);
        border-bottom: 1px solid var(--slate-200);
        display: flex; align-items: center; justify-content: space-between;
        position: relative; overflow: hidden;
    }
    .steps-bar::before {
        content: '';
        position: absolute;
        top: 32px; left: 3rem; right: 3rem;
        height: 2px; background: var(--slate-200);
        z-index: 0;
    }
    .steps-bar-progress {
        position: absolute;
        top: 32px; left: 3rem;
        height: 2px; background: linear-gradient(90deg, var(--success), var(--accent));
        z-index: 1; transition: width 0.4s cubic-bezier(.4,0,.2,1);
    }
    .step-item {
        display: flex; flex-direction: column; align-items: center;
        position: relative; z-index: 2; gap: 0.4rem; flex: 1;
    }
    .step-circle {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 0.82rem;
        border: 2.5px solid var(--slate-200);
        background: var(--white); color: var(--slate-400);
        transition: all 0.3s cubic-bezier(.4,0,.2,1);
    }
    .step-label-text {
        font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.06em;
        color: var(--slate-400); text-align: center; transition: color 0.3s;
    }
    .step-item.active .step-circle {
        background: var(--accent); border-color: var(--accent); color: var(--white);
        box-shadow: 0 0 0 4px var(--accent-ring);
        transform: scale(1.1);
    }
    .step-item.active .step-label-text { color: var(--accent); }
    .step-item.done .step-circle { background: var(--success); border-color: var(--success); color: var(--white); }
    .step-item.done .step-label-text { color: var(--success); }

    /* ── Form card ── */
    .form-card {
        background: var(--white);
        border-radius: 0 0 16px 16px;
        border: 1px solid var(--slate-200);
        border-top: none;
        box-shadow: 0 4px 24px rgba(15,23,42,0.05);
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .step-panel { display: none; animation: panelIn 0.25s cubic-bezier(.4,0,.2,1); }
    .step-panel.active { display: block; }
    @keyframes panelIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .section-header {
        padding: 1.1rem 1.75rem;
        border-bottom: 1px solid var(--slate-200);
        display: flex; align-items: center; gap: 0.65rem;
        background: linear-gradient(to right, var(--accent-light), transparent 70%);
    }
    .section-header h5 {
        font-size: 0.92rem; font-weight: 700;
        color: var(--slate-900); margin: 0;
    }
    .section-header i { color: var(--accent); font-size: 0.95rem; }

    .panel-body { padding: 1.75rem; }
    .panel-footer {
        padding: 1rem 1.75rem;
        background: var(--slate-100);
        border-top: 1px solid var(--slate-200);
        display: flex; justify-content: space-between; align-items: center;
    }

    /* ── Controls ── */
    .form-label {
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.06em;
        color: var(--slate-600); margin-bottom: 0.4rem;
        display: flex; align-items: center; gap: 0.3rem;
    }
    .req { color: var(--danger); margin-left: 2px; font-weight: 800; }
    .field-hint {
        font-size: 0.7rem; color: var(--slate-400);
        margin-top: 0.3rem; line-height: 1.4;
    }

    .form-control, .form-select {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.86rem; font-weight: 400;
        border: 1.5px solid var(--slate-200); border-radius: 9px;
        padding: 0.55rem 0.85rem; color: var(--slate-900); background: var(--white);
        transition: border-color 0.18s, box-shadow 0.18s;
        width: 100%;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-ring); outline: none;
    }
    .form-control.is-invalid, .form-select.is-invalid { border-color: var(--danger); }
    .invalid-feedback { font-size: 0.7rem; }

    /* ── Auto-filled fields ── */
    .auto-field-wrap { position: relative; }
    .auto-field {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 600 !important;
        background: var(--auto-bg) !important;
        border-color: var(--auto-bd) !important;
        color: var(--auto-tx) !important;
        cursor: not-allowed;
    }
    .auto-field-badge {
        position: absolute; right: 10px; top: 50%;
        transform: translateY(-50%);
        font-size: 0.6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.06em;
        padding: 0.15rem 0.5rem; border-radius: 99px;
        background: var(--auto-bd); color: var(--auto-tx);
        pointer-events: none;
        display: flex; align-items: center; gap: 0.2rem;
    }
    .auto-field-badge i { font-size: 0.55rem; }

    .job-number-field {
        font-weight: 700 !important; font-size: 0.95rem !important;
        background: var(--accent-light) !important;
        border-color: #fecaca !important;
        color: var(--accent-dark) !important;
        letter-spacing: 0.04em;
    }
    .job-number-badge {
        background: #fecaca !important;
        color: var(--accent-dark) !important;
    }

    /* ── Type of Emergency con datalist ── */
    .type-input-wrap { position: relative; }
    .type-input-wrap::after {
        content: "\F282";
        font-family: 'bootstrap-icons';
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%);
        font-size: 0.65rem; color: var(--slate-400);
        pointer-events: none;
    }
    .type-input-wrap input { padding-right: 30px; }

    /* ── Team checkboxes ── */
    .team-check {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.7rem 0.95rem; border-radius: 10px;
        border: 1.5px solid var(--slate-200); background: var(--slate-100);
        cursor: pointer; transition: all 0.15s;
        margin-bottom: 0.5rem;
    }
    .team-check:hover { border-color: var(--slate-300); }
    .team-check:has(input:checked) { border-color: var(--accent); background: var(--accent-light); }
    .team-check input[type="checkbox"] {
        width: 18px; height: 18px; border-radius: 5px;
        accent-color: var(--accent); flex-shrink: 0; cursor: pointer;
    }
    .team-check-name { font-weight: 600; font-size: 0.85rem; color: var(--slate-900); }
    .team-check-role {
        font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.06em; color: var(--slate-400);
    }
    .team-role-heading {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: var(--slate-400); margin: 1rem 0 0.5rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px dashed var(--slate-200);
        display: flex; align-items: center; gap: 0.35rem;
    }
    .team-role-heading:first-of-type { margin-top: 0; }

    /* ── Terms ── */
    .terms-item {
        display: flex; align-items: flex-start; gap: 0.85rem;
        padding: 0.95rem 1.15rem; border-radius: 11px;
        border: 1.5px solid var(--slate-200); background: var(--slate-100);
        margin-bottom: 0.7rem; cursor: pointer; transition: all 0.15s;
    }
    .terms-item:hover { border-color: var(--slate-300); }
    .terms-item:has(input:checked) {
        border-color: var(--success); background: #ecfdf5;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.08);
    }
    .terms-item input[type="checkbox"] {
        width: 18px; height: 18px; flex-shrink: 0;
        margin-top: 2px; accent-color: var(--success); cursor: pointer;
    }
    .terms-title { font-weight: 600; font-size: 0.86rem; color: var(--slate-900); }
    .terms-desc  { font-size: 0.76rem; color: var(--slate-500); margin-top: 0.2rem; line-height: 1.5; }

    /* ── Drop zone ── */
    .drop-card {
        border: 1px solid var(--slate-200);
        border-radius: 12px;
        background: var(--white);
        overflow: hidden;
        transition: all 0.18s;
    }
    .drop-card.has-files { border-color: var(--success); box-shadow: 0 0 0 3px rgba(16,185,129,0.06); }
    .drop-card-header {
        padding: 0.75rem 1rem;
        background: var(--slate-100);
        border-bottom: 1px solid var(--slate-200);
        display: flex; align-items: center; justify-content: space-between;
        gap: 0.75rem;
    }
    .drop-card-label {
        font-size: 0.78rem; font-weight: 700;
        color: var(--slate-800);
        display: flex; align-items: center; gap: 0.4rem;
    }
    .drop-card-label i { color: var(--accent); }
    .drop-card-counter {
        font-size: 0.68rem; font-weight: 700;
        color: var(--slate-400);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .drop-card-counter .count-pill {
        padding: 0.15rem 0.55rem;
        border-radius: 99px;
        background: var(--slate-200);
        color: var(--slate-700);
    }
    .drop-card.has-files .drop-card-counter .count-pill {
        background: var(--success); color: var(--white);
    }

    .drop-zone {
        position: relative;
        margin: 0.85rem;
        border: 2px dashed var(--slate-300);
        border-radius: 10px;
        padding: 1.1rem 1rem;
        background: var(--slate-100);
        text-align: center;
        cursor: pointer;
        transition: all 0.18s cubic-bezier(.4,0,.2,1);
    }
    .drop-zone:hover {
        border-color: var(--accent);
        background: var(--accent-light);
    }
    .drop-zone.drag-over {
        border-color: var(--accent);
        background: var(--accent-light);
        transform: scale(1.01);
        box-shadow: inset 0 0 0 2px var(--accent);
    }
    .drop-zone input[type="file"] {
        position: absolute; inset: 0;
        opacity: 0; cursor: pointer;
    }
    .drop-icon {
        width: 42px; height: 42px;
        margin: 0 auto 0.4rem;
        border-radius: 11px;
        background: var(--white);
        border: 1.5px solid var(--slate-200);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem; color: var(--slate-400);
        transition: all 0.18s;
    }
    .drop-zone:hover .drop-icon,
    .drop-zone.drag-over .drop-icon {
        color: var(--accent);
        border-color: #fecaca;
        transform: translateY(-2px);
    }
    .drop-title {
        font-size: 0.82rem; font-weight: 700;
        color: var(--slate-800);
    }
    .drop-title strong { color: var(--accent); }
    .drop-hint {
        font-size: 0.68rem; color: var(--slate-400);
        margin-top: 0.2rem;
    }

    /* ── Preview grid ── */
    .preview-grid {
        padding: 0 0.85rem 0.85rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(86px, 1fr));
        gap: 0.5rem;
    }
    .preview-grid:empty { display: none; }
    .preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 9px;
        overflow: hidden;
        background: var(--white);
        border: 1.5px solid var(--slate-200);
        transition: all 0.15s;
    }
    .preview-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15,23,42,0.1);
        border-color: var(--accent);
    }
    .preview-item img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }
    .preview-item-pdf {
        width: 100%; height: 100%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--accent-light), #fef2f2);
        gap: 4px; padding: 6px;
    }
    .preview-item-pdf i {
        font-size: 1.4rem;
        color: var(--accent);
    }
    .preview-item-name {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 4px 6px;
        background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0) 100%);
        font-size: 0.6rem;
        font-weight: 600;
        color: white;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.2;
    }
    .preview-item-pdf .preview-item-name {
        background: transparent;
        position: static;
        color: var(--slate-600);
        text-align: center;
        font-size: 0.55rem;
        padding: 0;
    }
    .preview-item-remove {
        position: absolute;
        top: 4px; right: 4px;
        width: 22px; height: 22px;
        border-radius: 50%;
        background: rgba(15,23,42,0.75);
        border: none; color: white;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 0.7rem;
        opacity: 0;
        transition: all 0.15s;
        padding: 0;
    }
    .preview-item:hover .preview-item-remove,
    .preview-item-remove:focus {
        opacity: 1;
    }
    .preview-item-remove:hover {
        background: var(--danger);
        transform: scale(1.1);
    }
    @media(hover: none) {
        .preview-item-remove { opacity: 1; }
    }

    /* ── Nav buttons ── */
    .btn-nav {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-family: 'Montserrat', sans-serif; font-weight: 600;
        font-size: 0.82rem; padding: 0.55rem 1.2rem;
        border-radius: 9px; border: none; cursor: pointer;
        transition: all 0.18s;
    }
    .btn-nav:hover { filter: brightness(1.06); transform: translateY(-1px); }
    .btn-nav:disabled { opacity: 0.35; pointer-events: none; }
    .btn-prev { background: var(--slate-200); color: var(--slate-700); }
    .btn-prev:hover { background: var(--slate-300); }
    .btn-next { background: var(--accent); color: var(--white); box-shadow: 0 3px 10px rgba(220,38,38,0.22); }
    .btn-submit-em {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-family: 'Montserrat', sans-serif; font-weight: 700;
        font-size: 0.88rem; padding: 0.65rem 1.8rem;
        border-radius: 11px; border: none;
        background: var(--accent); color: var(--white);
        box-shadow: 0 4px 16px rgba(220,38,38,0.25);
        cursor: pointer; transition: all 0.18s;
    }
    .btn-submit-em:hover { filter: brightness(1.06); transform: translateY(-1px); }

    /* ── Step counter ── */
    .step-counter {
        font-size: 0.68rem; font-weight: 700;
        color: var(--slate-400); text-transform: uppercase;
        letter-spacing: 0.07em;
    }
    .step-counter strong { color: var(--accent); font-weight: 800; }

    /* ── Alert success ── */
    .alert-ok {
        background: #ecfdf5; border: 1.5px solid #6ee7b7;
        border-radius: 11px; padding: 0.85rem 1.15rem;
        display: flex; align-items: center; gap: 0.7rem;
        font-size: 0.84rem; color: #065f46; margin-bottom: 1.25rem;
    }

    /* ── Validation alert ── */
    .validation-alert {
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        border-radius: 11px;
        padding: 0.85rem 1.15rem;
        margin: 0 1.75rem 1.25rem;
        display: none;
        align-items: flex-start;
        gap: 0.7rem;
        animation: shake 0.35s cubic-bezier(.36,.07,.19,.97);
    }
    .validation-alert.show { display: flex; }
    .validation-alert-icon {
        width: 28px; height: 28px;
        border-radius: 7px;
        background: var(--accent);
        color: white;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 0.85rem;
    }
    .validation-alert-content { flex: 1; }
    .validation-alert-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--accent-dark);
        margin-bottom: 0.3rem;
    }
    .validation-alert-list {
        margin: 0;
        padding-left: 1rem;
        list-style: disc;
    }
    .validation-alert-list li {
        font-size: 0.74rem;
        color: var(--accent-dark);
        line-height: 1.5;
    }
    @keyframes shake {
        0%,100% { transform: translateX(0); }
        20%,60% { transform: translateX(-4px); }
        40%,80% { transform: translateX(4px); }
    }

    .validation-block-error {
        border: 1.5px solid #fecaca !important;
        background: #fef2f2 !important;
        border-radius: 10px;
        padding: 0.5rem !important;
    }

    .drop-card.has-error {
        border-color: var(--danger);
        background: var(--accent-light);
        box-shadow: 0 0 0 3px rgba(239,68,68,0.06);
    }
    .drop-card.has-error .drop-card-header {
        background: var(--accent-light);
    }

    @media (max-width: 768px) {
        .steps-bar { padding: 1rem; }
        .steps-bar::before { display: none; }
        .step-label-text { display: none; }
    }
    @media (max-width: 576px) {
        .form-hero  { padding: 1.25rem 1.5rem; }
        .hero-title { font-size: 1.2rem; }
        .panel-body { padding: 1.25rem; }
        .panel-footer { padding: 1rem 1.25rem; }
    }
</style>

<div class="em-form-page">
    <div class="container-xl px-0" style="max-width:900px;">

        {{-- Hero --}}
        <div class="form-hero">
            <div style="position:relative;z-index:1;">
                <div class="hero-eyebrow"><i class="bi bi-exclamation-octagon"></i> {{ $user->company_name ?? 'Contracting Alliance Inc.' }}</div>
                <h1 class="hero-title">Emergency Request Form</h1>
                <div class="hero-subtitle">Complete all sections to submit your emergency request</div>
                <a href="{{ route('calendar.view') }}" class="btn-hero-back">
                    <i class="bi bi-arrow-left"></i> Back to Calendar
                </a>
            </div>
            <img src="https://www.jotform.com/uploads/fredysanchezc1980/form_files/IMG_7040.663336b07e6656.75204432.jpeg"
                 alt="Logo" class="hero-logo">
        </div>

        {{-- Alert success --}}
        @if(session('success'))
            <div class="alert-ok">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Steps bar --}}
        <div class="steps-bar" id="stepsBar">
            <div class="steps-bar-progress" id="stepsProgress" style="width:0%"></div>
            <div class="step-item active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label-text">Job Info</div>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label-text">Address</div>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label-text">Team</div>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label-text">Terms</div>
            </div>
            <div class="step-item" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label-text">Documents</div>
            </div>
        </div>

        <div class="form-card">
            <form action="{{ route('emergency.store') }}" method="POST" enctype="multipart/form-data"
                  id="emForm" novalidate>
                @csrf

                {{-- Validation Alert (compartida entre todos los steps) --}}
                <div class="validation-alert" id="validationAlert" role="alert">
                    <div class="validation-alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="validation-alert-content">
                        <div class="validation-alert-title">Please fix the following before continuing:</div>
                        <ul class="validation-alert-list" id="validationAlertList"></ul>
                    </div>
                </div>

                {{-- ── STEP 1 · Job Information ── --}}
                <div class="step-panel active" data-step="1">
                    <div class="section-header">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <h5>Job Information</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-hash"></i> Job Number</label>
                                <div class="auto-field-wrap">
                                    <input type="text" class="form-control auto-field job-number-field"
                                           name="job_number_name" value="{{ $nextEmNumber }}" readonly>
                                    <span class="auto-field-badge job-number-badge"><i class="bi bi-magic"></i> Auto</span>
                                </div>
                                <div class="field-hint">Per company sequence</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="date_submitted"><i class="bi bi-calendar3"></i> Date Submitted <span class="req">*</span></label>
                                <input type="date" class="form-control" id="date_submitted" name="date_submitted" required>
                                <div class="invalid-feedback">Required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="type_of_supplement"><i class="bi bi-tag"></i> Type of Emergency <span class="req">*</span></label>
                                <div class="type-input-wrap">
                                    <input type="text" class="form-control" id="type_of_supplement" name="type_of_supplement"
                                           list="emergency-types"
                                           placeholder="Type or select…"
                                           autocomplete="off" required>
                                </div>
                                <datalist id="emergency-types">
                                    <option value="New roof installed by a Contracting Alliance sub is leaking (warranty)"></option>
                                    <option value="New job, please identify and Stop Leak (minimum $750 charge)"></option>
                                    <option value="Emergency Hurricane Tarping Labor and Materials"></option>
                                    <option value="Emergency Hurricane Tarping Labor Only"></option>
                                </datalist>
                                <div class="invalid-feedback">Required.</div>
                                <div class="field-hint">Type freely or pick from suggestions</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-building"></i> Company Name</label>
                                <div class="auto-field-wrap">
                                    <input type="text" class="form-control auto-field"
                                           name="company_name" value="{{ $user->company_name ?? '' }}" readonly>
                                    <span class="auto-field-badge"><i class="bi bi-lock-fill"></i> Auto</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_contact_email"><i class="bi bi-envelope"></i> Contact Email <span class="req">*</span></label>
                                <div class="auto-field-wrap">
                                    <input type="email" class="form-control auto-field"
                                           id="company_contact_email" name="company_contact_email"
                                           value="{{ $user->email ?? '' }}" readonly required>
                                    <span class="auto-field-badge"><i class="bi bi-lock-fill"></i> Auto</span>
                                </div>
                                <div class="field-hint">From your company profile</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="step-counter">Step <strong>1</strong> of 5</span>
                        <button type="button" class="btn-nav btn-next">Next <i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>

                {{-- ── STEP 2 · Address ── --}}
                <div class="step-panel" data-step="2">
                    <div class="section-header">
                        <i class="bi bi-geo-alt-fill"></i>
                        <h5>Job Address</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label" for="job_address"><i class="bi bi-signpost"></i> Street Address <span class="req">*</span></label>
                                <input type="text" class="form-control" id="job_address" name="job_address" required>
                                <div class="invalid-feedback">Required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="job_address_line2"><i class="bi bi-building-add"></i> Address Line 2</label>
                                <input type="text" class="form-control" id="job_address_line2" name="job_address_line2" placeholder="Suite, Unit, Apt…">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="job_city"><i class="bi bi-geo"></i> City <span class="req">*</span></label>
                                <input type="text" class="form-control" id="job_city" name="job_city" required>
                                <div class="invalid-feedback">Required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="job_state"><i class="bi bi-map"></i> State <span class="req">*</span></label>
                                <select class="form-select" id="job_state" name="job_state" required>
                                    <option value="" disabled selected>Select…</option>
                                    @foreach(['AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'] as $abbr => $name)
                                        <option value="{{ $abbr }}">{{ $abbr }} – {{ $name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Required.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="job_zip_code"><i class="bi bi-mailbox"></i> ZIP Code <span class="req">*</span></label>
                                <input type="text" class="form-control" id="job_zip_code" name="job_zip_code" maxlength="10" required>
                                <div class="invalid-feedback">Required.</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn-nav btn-prev"><i class="bi bi-arrow-left"></i> Previous</button>
                        <span class="step-counter">Step <strong>2</strong> of 5</span>
                        <button type="button" class="btn-nav btn-next">Next <i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>

                {{-- ── STEP 3 · Team Members ── --}}
                <div class="step-panel" data-step="3">
                    <div class="section-header">
                        <i class="bi bi-people-fill"></i>
                        <h5>Assign Team Members <span style="font-weight:400;color:var(--slate-400);font-size:0.7rem;margin-left:0.25rem;">(Optional)</span></h5>
                    </div>
                    <div class="panel-body" id="teamMembersBlock">
                        @php
                            $teamMembers = \App\Models\Team::whereIn('role', ['manager', 'project_manager', 'crew'])
                                ->where('is_active', true)->get();
                            $grouped = $teamMembers->groupBy('role');
                        @endphp
                        @if($teamMembers->isEmpty())
                            <p style="font-size:0.85rem;color:var(--slate-400);font-style:italic;text-align:center;padding:2rem;">
                                <i class="bi bi-person-x" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;opacity:.5;"></i>
                                No active team members available.
                            </p>
                        @else
                            @foreach($grouped as $role => $members)
                                <div class="team-role-heading">
                                    <i class="bi bi-{{ $role === 'manager' ? 'briefcase-fill' : ($role === 'project_manager' ? 'kanban' : 'tools') }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    <span style="font-weight:400;color:var(--slate-300);">· {{ $members->count() }}</span>
                                </div>
                                <div class="row g-0">
                                    @foreach($members as $member)
                                        <div class="col-md-6 pe-md-2">
                                            <label class="team-check" for="em_member_{{ $member->id }}">
                                                <input type="checkbox" name="assigned_team_members[]"
                                                       value="{{ $member->id }}" id="em_member_{{ $member->id }}">
                                                <div>
                                                    <div class="team-check-name">{{ $member->name }}</div>
                                                    <div class="team-check-role">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn-nav btn-prev"><i class="bi bi-arrow-left"></i> Previous</button>
                        <span class="step-counter">Step <strong>3</strong> of 5</span>
                        <button type="button" class="btn-nav btn-next">Next <i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>

                {{-- ── STEP 4 · Terms & Conditions ── --}}
                <div class="step-panel" data-step="4">
                    <div class="section-header">
                        <i class="bi bi-file-earmark-check-fill"></i>
                        <h5>Terms & Conditions <span class="req">*</span></h5>
                    </div>
                    <div class="panel-body" id="termsBlock">
                        <label class="terms-item" for="terms_conditions">
                            <input type="checkbox" id="terms_conditions" name="terms_conditions" required>
                            <div>
                                <div class="terms-title">Supplement Submission Responsibility</div>
                                <div class="terms-desc">I understand it is my company's responsibility to submit the supplement. Contracting Alliance will create the document on your behalf.</div>
                            </div>
                        </label>
                        <label class="terms-item" for="requirements">
                            <input type="checkbox" id="requirements" name="requirements" required>
                            <div>
                                <div class="terms-title">Supplement Processing</div>
                                <div class="terms-desc">I understand that the speed and accuracy of the supplement is based on the information provided, including pictures and/or contracts.</div>
                            </div>
                        </label>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn-nav btn-prev"><i class="bi bi-arrow-left"></i> Previous</button>
                        <span class="step-counter">Step <strong>4</strong> of 5</span>
                        <button type="button" class="btn-nav btn-next">Next <i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>

                {{-- ── STEP 5 · Documents ── --}}
                <div class="step-panel" data-step="5">
                    <div class="section-header">
                        <i class="bi bi-paperclip"></i>
                        <h5>Required Documents</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row g-3">

                            {{-- Aerial Measurement --}}
                            <div class="col-md-6">
                                <div class="drop-card" id="drop-card-aerial_measurement">
                                    <div class="drop-card-header">
                                        <span class="drop-card-label"><i class="bi bi-map"></i> Aerial Measurement <span class="req">*</span></span>
                                        <span class="drop-card-counter">
                                            <span class="count-pill" id="counter-aerial_measurement">0 files</span>
                                        </span>
                                    </div>
                                    <div class="drop-zone" data-field="aerial_measurement">
                                        <input type="file" name="aerial_measurement[]" multiple
                                               accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        <div class="drop-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                        <div class="drop-title"><strong>Drop files here</strong> or click to browse</div>
                                        <div class="drop-hint">PDF, JPG, PNG, WEBP · Max 5MB each</div>
                                    </div>
                                    <div class="preview-grid" id="preview-aerial_measurement"></div>
                                </div>
                            </div>

                            {{-- Contract --}}
                            <div class="col-md-6">
                                <div class="drop-card" id="drop-card-contract_upload">
                                    <div class="drop-card-header">
                                        <span class="drop-card-label"><i class="bi bi-file-earmark-richtext"></i> Contract Upload <span class="req">*</span></span>
                                        <span class="drop-card-counter">
                                            <span class="count-pill" id="counter-contract_upload">0 files</span>
                                        </span>
                                    </div>
                                    <div class="drop-zone" data-field="contract_upload">
                                        <input type="file" name="contract_upload[]" multiple
                                               accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        <div class="drop-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                        <div class="drop-title"><strong>Drop files here</strong> or click to browse</div>
                                        <div class="drop-hint">PDF, JPG, PNG, WEBP · Max 5MB each</div>
                                    </div>
                                    <div class="preview-grid" id="preview-contract_upload"></div>
                                </div>
                            </div>

                            {{-- Additional Pictures --}}
                            <div class="col-12">
                                <div class="drop-card" id="drop-card-file_picture_upload">
                                    <div class="drop-card-header">
                                        <span class="drop-card-label">
                                            <i class="bi bi-images"></i> Additional Pictures
                                            <span style="font-weight:400;color:var(--slate-400);font-size:0.7rem;margin-left:0.25rem;">(Optional)</span>
                                        </span>
                                        <span class="drop-card-counter">
                                            <span class="count-pill" id="counter-file_picture_upload">0 files</span>
                                        </span>
                                    </div>
                                    <div class="drop-zone" data-field="file_picture_upload">
                                        <input type="file" name="file_picture_upload[]" multiple
                                               accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        <div class="drop-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                        <div class="drop-title"><strong>Drop pictures here</strong> or click to browse</div>
                                        <div class="drop-hint">PDF, JPG, PNG, WEBP · Multiple files allowed</div>
                                    </div>
                                    <div class="preview-grid" id="preview-file_picture_upload"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn-nav btn-prev"><i class="bi bi-arrow-left"></i> Previous</button>
                        <span class="step-counter">Step <strong>5</strong> of 5</span>
                        <button type="submit" class="btn-submit-em">
                            <i class="bi bi-send-check"></i> Submit Emergency Request
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
// ── File state global (debe definirse antes del DOMContentLoaded para que validateStep lo lea) ──
const fileState = {
    aerial_measurement:  [],
    contract_upload:     [],
    file_picture_upload: []
};
window.fileState = fileState;

const MAX_SIZE = 5 * 1024 * 1024; // 5MB

document.addEventListener('DOMContentLoaded', function () {
    let current = 1;
    const TOTAL = 5;

    document.getElementById('date_submitted').value = new Date().toISOString().split('T')[0];

    function showStep(n) {
        document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
        document.querySelector(`.step-panel[data-step="${n}"]`).classList.add('active');
        document.querySelectorAll('.step-item').forEach(s => {
            const sn = parseInt(s.dataset.step);
            const circle = s.querySelector('.step-circle');
            s.classList.remove('active', 'done');
            if (sn < n) {
                s.classList.add('done');
                circle.innerHTML = '<i class="bi bi-check-lg"></i>';
            } else if (sn === n) {
                s.classList.add('active');
                circle.textContent = sn;
            } else {
                circle.textContent = sn;
            }
        });
        const progress = ((n - 1) / (TOTAL - 1)) * 100;
        const progressEl = document.getElementById('stepsProgress');
        const barWidth = document.getElementById('stepsBar').offsetWidth - 96;
        progressEl.style.width = (barWidth * (progress/100)) + 'px';

        document.getElementById('stepsBar').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ── Validación robusta por step ──────────────────────────
    function showValidationAlert(errors) {
        const alert = document.getElementById('validationAlert');
        const list  = document.getElementById('validationAlertList');
        list.innerHTML = '';
        errors.forEach(err => {
            const li = document.createElement('li');
            li.textContent = err;
            list.appendChild(li);
        });
        alert.classList.add('show');
        alert.style.animation = 'none';
        alert.offsetHeight;
        alert.style.animation = '';
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearValidationAlert() {
        document.getElementById('validationAlert').classList.remove('show');
    }

    function validateStep() {
        clearValidationAlert();
        const panel = document.querySelector(`.step-panel[data-step="${current}"]`);
        const errors = [];
        let firstInvalid = null;

        // 1. Campos required estándar (no readonly, no checkbox, no file)
        const required = panel.querySelectorAll('[required]:not([readonly]):not([type="checkbox"]):not([type="file"])');
        required.forEach(el => {
            const val = (el.value || '').trim();
            if (!val) {
                el.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = el;
                const labelEl = panel.querySelector(`label[for="${el.id}"]`);
                const labelText = labelEl ? labelEl.textContent.replace('*','').trim() : (el.name || 'Field');
                errors.push(`${labelText} is required`);
            } else {
                el.classList.remove('is-invalid');
            }
        });

        // 2. Email format
        const emailEl = panel.querySelector('input[type="email"]:not([readonly])');
        if (emailEl && emailEl.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value.trim())) {
            emailEl.classList.add('is-invalid');
            if (!firstInvalid) firstInvalid = emailEl;
            errors.push('Email format is invalid');
        }

        // 3. ZIP code
        const zipEl = panel.querySelector('input[name="job_zip_code"]');
        if (zipEl && zipEl.value.trim() && !/^\d{5}(-\d{4})?$/.test(zipEl.value.trim())) {
            zipEl.classList.add('is-invalid');
            if (!firstInvalid) firstInvalid = zipEl;
            errors.push('ZIP code must be 5 digits (or 5+4 format)');
        }

        // 4. Validaciones específicas por step
        if (current === 4) {
            const termsBlock = document.getElementById('termsBlock');
            const terms = ['terms_conditions','requirements'];
            const termLabels = {
                terms_conditions: 'Submission Responsibility',
                requirements:     'Supplement Processing',
            };
            let termsMissing = false;
            terms.forEach(name => {
                const el = panel.querySelector(`input[name="${name}"]`);
                if (el && !el.checked) {
                    errors.push(`Accept: ${termLabels[name]}`);
                    termsMissing = true;
                }
            });
            if (termsMissing) {
                termsBlock?.classList.add('validation-block-error');
                if (!firstInvalid) firstInvalid = termsBlock;
            } else {
                termsBlock?.classList.remove('validation-block-error');
            }
        }

        if (current === 5) {
            const aerialCount   = (window.fileState?.aerial_measurement || []).length;
            const contractCount = (window.fileState?.contract_upload || []).length;

            const aerialCard   = document.getElementById('drop-card-aerial_measurement');
            const contractCard = document.getElementById('drop-card-contract_upload');

            if (aerialCount === 0) {
                aerialCard?.classList.add('has-error');
                errors.push('Upload at least one Aerial Measurement file');
                if (!firstInvalid) firstInvalid = aerialCard;
            } else {
                aerialCard?.classList.remove('has-error');
            }

            if (contractCount === 0) {
                contractCard?.classList.add('has-error');
                errors.push('Upload at least one Contract file');
                if (!firstInvalid) firstInvalid = contractCard;
            } else {
                contractCard?.classList.remove('has-error');
            }
        }

        if (errors.length > 0) {
            showValidationAlert(errors);
            if (firstInvalid && firstInvalid.scrollIntoView) {
                setTimeout(() => firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
            }
            return false;
        }
        return true;
    }

    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!validateStep()) return;
            current++; showStep(current);
        });
    });

    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', () => {
            clearValidationAlert();
            current--; showStep(current);
        });
    });

    document.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.addEventListener('input', () => {
            el.classList.remove('is-invalid');
            clearValidationAlert();
        });
    });

    // Limpiar errores de team y terms al cambiar checkboxes
    document.querySelectorAll('input[name="assigned_team_members[]"]').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('teamMembersBlock')?.classList.remove('validation-block-error');
            clearValidationAlert();
        });
    });
    ['terms_conditions','requirements'].forEach(name => {
        document.querySelector(`input[name="${name}"]`)?.addEventListener('change', () => {
            document.getElementById('termsBlock')?.classList.remove('validation-block-error');
            clearValidationAlert();
        });
    });

    // Validar al submit final
    document.getElementById('emForm').addEventListener('submit', e => {
        if (!validateStep()) {
            e.preventDefault();
        }
    });
});

// ── File Upload System (drag-drop + thumbnails + remove) ─────
function setupDropZone(field) {
    const zone   = document.querySelector(`.drop-zone[data-field="${field}"]`);
    const input  = zone.querySelector('input[type="file"]');

    ['dragenter','dragover'].forEach(evt => {
        zone.addEventListener(evt, e => {
            e.preventDefault(); e.stopPropagation();
            zone.classList.add('drag-over');
        });
    });
    ['dragleave','dragend'].forEach(evt => {
        zone.addEventListener(evt, e => {
            e.preventDefault(); e.stopPropagation();
            if (!zone.contains(e.relatedTarget)) {
                zone.classList.remove('drag-over');
            }
        });
    });
    zone.addEventListener('drop', e => {
        e.preventDefault(); e.stopPropagation();
        zone.classList.remove('drag-over');
        addFiles(field, Array.from(e.dataTransfer.files));
    });

    input.addEventListener('change', e => {
        addFiles(field, Array.from(e.target.files));
        input.value = '';
    });
}

function addFiles(field, newFiles) {
    const accepted = ['application/pdf','image/jpeg','image/jpg','image/png','image/webp'];
    const valid = newFiles.filter(f => {
        if (f.size > MAX_SIZE) {
            alert(`"${f.name}" excede el límite de 5MB y no fue agregado.`);
            return false;
        }
        if (!accepted.includes(f.type) && !/\.(pdf|jpe?g|png|webp)$/i.test(f.name)) {
            alert(`"${f.name}" no es un tipo de archivo válido.`);
            return false;
        }
        return true;
    });

    valid.forEach(f => {
        const exists = fileState[field].some(ef => ef.name === f.name && ef.size === f.size);
        if (!exists) fileState[field].push(f);
    });

    // Limpiar error visual si ya hay archivos
    if (fileState[field].length > 0) {
        document.getElementById(`drop-card-${field}`)?.classList.remove('has-error');
        document.getElementById('validationAlert')?.classList.remove('show');
    }

    syncInput(field);
    renderPreviews(field);
}

function removeFile(field, index) {
    fileState[field].splice(index, 1);
    syncInput(field);
    renderPreviews(field);
}

function syncInput(field) {
    const input = document.querySelector(`.drop-zone[data-field="${field}"] input[type="file"]`);
    const dt = new DataTransfer();
    fileState[field].forEach(f => dt.items.add(f));
    input.files = dt.files;
}

function renderPreviews(field) {
    const grid    = document.getElementById(`preview-${field}`);
    const card    = document.getElementById(`drop-card-${field}`);
    const counter = document.getElementById(`counter-${field}`);
    const files   = fileState[field];

    grid.innerHTML = '';
    counter.textContent = `${files.length} file${files.length !== 1 ? 's' : ''}`;
    card.classList.toggle('has-files', files.length > 0);

    files.forEach((file, idx) => {
        const item = document.createElement('div');
        item.className = 'preview-item';

        const isPdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);

        if (isPdf) {
            item.innerHTML = `
                <div class="preview-item-pdf">
                    <i class="bi bi-file-pdf-fill"></i>
                    <span class="preview-item-name">${file.name}</span>
                </div>
                <button type="button" class="preview-item-remove" data-field="${field}" data-idx="${idx}" aria-label="Remove">
                    <i class="bi bi-x-lg"></i>
                </button>`;
        } else {
            const url = URL.createObjectURL(file);
            item.innerHTML = `
                <img src="${url}" alt="${file.name}">
                <span class="preview-item-name">${file.name}</span>
                <button type="button" class="preview-item-remove" data-field="${field}" data-idx="${idx}" aria-label="Remove">
                    <i class="bi bi-x-lg"></i>
                </button>`;
        }

        item.querySelector('.preview-item-remove').addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            removeFile(field, idx);
        });

        grid.appendChild(item);
    });
}

// Inicializar todas las drop zones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    ['aerial_measurement','contract_upload','file_picture_upload'].forEach(setupDropZone);
});
</script>

@endsection