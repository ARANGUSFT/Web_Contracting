@extends('layouts.app')

@section('content')

<style>
.pending-wrap {
    text-align: center;
    padding: 8px 0;
}

/* ── Icon ── */
.pending-icon-ring {
    width: 80px; height: 80px; border-radius: 50%;
    background: #fffbeb; border: 2px solid #fde68a;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px;
    animation: pulseRing 2s ease-in-out infinite;
}
.pending-icon-ring i { font-size: 32px; color: #d97706; }

@keyframes pulseRing {
    0%, 100% { box-shadow: 0 0 0 0 rgba(217,119,6,.2); }
    50%       { box-shadow: 0 0 0 10px rgba(217,119,6,.0); }
}

/* ── Badge ── */
.pending-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #fffbeb; border: 1px solid #fde68a;
    border-radius: 999px; padding: 5px 14px;
    font-size: 11px; font-weight: 700; color: #92400e;
    text-transform: uppercase; letter-spacing: 1px;
    margin-bottom: 16px;
}
.pending-badge-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #d97706;
    animation: blink 1.4s ease-in-out infinite;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50%       { opacity: .3; }
}

/* ── Title ── */
.pending-title {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    font-size: 22px; font-weight: 800; color: #0f172a;
    letter-spacing: -.4px; margin-bottom: 8px;
}
.pending-subtitle {
    font-size: 14px; color: #64748b; margin-bottom: 24px; line-height: 1.5;
}

/* ── Info card ── */
.pending-card {
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 12px; padding: 18px 20px;
    margin-bottom: 28px; text-align: left;
}
.pending-card-row {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid #e2e8f0;
}
.pending-card-row:last-child { border-bottom: none; padding-bottom: 0; }
.pending-card-row:first-child { padding-top: 0; }
.pending-card-icon {
    width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.pending-card-icon.amber { background: #fffbeb; color: #d97706; }
.pending-card-icon.blue  { background: #eff6ff; color: #2563eb; }
.pending-card-text strong { display: block; font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
.pending-card-text span   { font-size: 12px; color: #64748b; line-height: 1.5; }

/* ── Actions ── */
.pending-actions { display: flex; flex-direction: column; gap: 10px; }
.btn-back {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    background: #003366; color: #fff;
    padding: 12px 24px; border-radius: 10px;
    font-size: 14px; font-weight: 700;
    text-decoration: none; transition: all .15s;
    box-shadow: 0 4px 12px rgba(0,51,102,.25);
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
.btn-back:hover { background: #002244; box-shadow: 0 6px 18px rgba(0,51,102,.35); transform: translateY(-1px); color: #fff; }
.btn-back:active { transform: translateY(0); }

/* ── Footer help ── */
.pending-help {
    margin-top: 20px; font-size: 12px; color: #94a3b8; text-align: center;
}
.pending-help a { color: #255b88; text-decoration: none; font-weight: 600; }
.pending-help a:hover { text-decoration: underline; }
</style>

<div class="pending-wrap">

    {{-- Animated icon --}}
    <div class="pending-icon-ring">
        <i class="fas fa-clock"></i>
    </div>

    {{-- Badge --}}
    <div class="pending-badge">
        <div class="pending-badge-dot"></div>
        Pending Review
    </div>

    {{-- Title --}}
    <div class="pending-title">Account Registered!</div>
    <div class="pending-subtitle">
        Your account has been successfully created.<br>
        An admin will review it shortly.
    </div>

    {{-- Info card --}}
    <div class="pending-card">
        <div class="pending-card-row">
            <div class="pending-card-icon amber">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="pending-card-text">
                <strong>Under Review</strong>
                <span>Your request is in the queue and will be reviewed by our team.</span>
            </div>
        </div>
        <div class="pending-card-row">
            <div class="pending-card-icon blue">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="pending-card-text">
                <strong>Email Notification</strong>
                <span>You'll receive a confirmation email once your account is activated.</span>
            </div>
        </div>
    </div>

    {{-- Action --}}
    <div class="pending-actions">
        <a href="{{ route('login') }}" class="btn-back">
            <i class="fas fa-arrow-left" style="font-size:12px"></i>
            Back to Login
        </a>
    </div>

    {{-- Help --}}
    <div class="pending-help">
        Need help? <a href="#">Contact support</a>
    </div>

</div>

@endsection