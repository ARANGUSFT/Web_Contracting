<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | Contracting Alliance Inc.</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --primary:     #003366;
      --primary-mid: #1a4d80;
      --primary-lt:  #e8f0fb;
      --accent:      #0066cc;
      --surf:        #ffffff;
      --bg:          #f0f4f8;
      --ink:         #0f172a;
      --ink2:        #475569;
      --ink3:        #94a3b8;
      --bd:          #e2e8f0;
      --red:         #dc2626;
      --rlt:         #fef2f2;
    }

    html, body {
      height: 100%;
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--ink);
    }

    /* ══════════════════════════════
       DESKTOP — two column
    ══════════════════════════════ */
    .shell {
      min-height: 100vh;
      display: flex;
      align-items: stretch;
    }

    /* LEFT */
    .panel-left {
      width: 48%; flex-shrink: 0;
      background: var(--primary);
      position: relative;
      display: flex; flex-direction: column; justify-content: space-between;
      padding: 44px 52px;
      overflow: hidden;
    }
    .panel-left::before {
      content: ''; position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size: 48px 48px;
    }
    .panel-left::after {
      content: ''; position: absolute; top: -80px; right: -80px;
      width: 360px; height: 360px; border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 65%);
      pointer-events: none;
    }
    .glow-b {
      position: absolute; bottom: -60px; left: -60px;
      width: 280px; height: 280px; border-radius: 50%;
      background: radial-gradient(circle, rgba(0,102,204,.35) 0%, transparent 65%);
      pointer-events: none;
    }

    /* Logo row */
    .left-logo { position: relative; display: flex; align-items: center; gap: 12px; }
    .left-logo-box {
      width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
      display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .left-logo-box img  { height: 24px; width: auto; object-fit: contain; }
    .left-brand         { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.2; }
    .left-brand-sub     { font-size: 10px; font-weight: 600; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: 1.2px; margin-top: 2px; }

    /* Body */
    .left-body { position: relative; }
    .left-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
      border-radius: 999px; padding: 5px 12px;
      font-size: 11px; font-weight: 600; color: rgba(255,255,255,.7);
      text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px;
    }
    .left-badge-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: #4ade80; box-shadow: 0 0 0 2px rgba(74,222,128,.25);
    }
    .left-headline {
      font-size: 38px; font-weight: 800; color: #fff;
      line-height: 1.15; letter-spacing: -.8px; margin-bottom: 16px;
    }
    .left-headline span { color: rgba(255,255,255,.4); font-weight: 400; }
    .left-desc { font-size: 14px; color: rgba(255,255,255,.5); line-height: 1.7; max-width: 300px; margin-bottom: 36px; }
    .left-features { display: flex; flex-direction: column; gap: 12px; }
    .left-feat { display: flex; align-items: center; gap: 12px; }
    .left-feat-icon {
      width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
      background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
      display: flex; align-items: center; justify-content: center;
    }
    .left-feat-icon svg { width: 15px; height: 15px; color: rgba(255,255,255,.6); }
    .left-feat-text { font-size: 13px; font-weight: 500; color: rgba(255,255,255,.55); }

    .left-foot {
      position: relative; display: flex; align-items: center; gap: 8px;
      font-size: 11.5px; color: rgba(255,255,255,.3); font-weight: 500;
      padding-top: 24px; border-top: 1px solid rgba(255,255,255,.08);
    }

    /* RIGHT */
    .panel-right {
      flex: 1; background: var(--surf);
      display: flex; align-items: center; justify-content: center;
      padding: 40px 32px;
    }

    .form-box { width: 100%; max-width: 400px; }

    /* Form elements */
    .form-title    { font-size: 26px; font-weight: 800; color: var(--ink); letter-spacing: -.4px; margin-bottom: 6px; }
    .form-subtitle { font-size: 14px; color: var(--ink3); margin-bottom: 32px; }

    .form-alert {
      display: flex; align-items: flex-start; gap: 10px;
      background: var(--rlt); border: 1px solid #fecaca;
      border-radius: 10px; padding: 12px 14px; margin-bottom: 24px;
      font-size: 13px; color: var(--red); font-weight: 500;
    }
    .form-alert svg { flex-shrink: 0; margin-top: 1px; }

    .field { margin-bottom: 18px; }
    .field-label { display: block; font-size: 12px; font-weight: 600; color: var(--ink2); margin-bottom: 7px; }
    .field-wrap  { position: relative; }
    .field-ico {
      position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
      display: flex; pointer-events: none; color: var(--ink3);
    }
    .field-ico svg { width: 15px; height: 15px; }
    .field-input {
      width: 100%; padding: 11px 14px 11px 40px;
      border: 1.5px solid var(--bd); border-radius: 10px;
      font-size: 14px; font-family: 'Inter', sans-serif;
      color: var(--ink); background: #f8fafc; outline: none;
      transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .field-input::placeholder { color: #b8c4cf; }
    .field-input:focus {
      border-color: var(--primary); background: var(--surf);
      box-shadow: 0 0 0 3px rgba(0,51,102,.08);
    }
    .field-input.is-error { border-color: var(--red); background: var(--rlt); }
    .field-err { font-size: 11.5px; color: var(--red); font-weight: 500; margin-top: 5px; }

    .pwd-eye {
      position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer;
      color: var(--ink3); display: flex; padding: 3px; transition: color .13s;
    }
    .pwd-eye:hover { color: var(--ink2); }
    .pwd-eye svg { width: 16px; height: 16px; }

    .field-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .check-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .check-label input { width: 15px; height: 15px; border-radius: 4px; accent-color: var(--primary); }
    .check-text { font-size: 13px; font-weight: 500; color: var(--ink2); }
    .forgot { font-size: 13px; font-weight: 600; color: var(--accent); text-decoration: none; transition: color .13s; }
    .forgot:hover { color: var(--primary); }

    .submit {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
      color: #fff; border: none; border-radius: 10px;
      font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;
      cursor: pointer; transition: all .15s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 4px 14px rgba(0,51,102,.3);
    }
    .submit:hover {
      background: linear-gradient(135deg, #002244 0%, #003d80 100%);
      box-shadow: 0 6px 20px rgba(0,51,102,.4); transform: translateY(-1px);
    }
    .submit:active { transform: translateY(0); }
    .submit svg { width: 16px; height: 16px; }

    .form-foot {
      margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--bd);
      text-align: center; font-size: 11.5px; color: var(--ink3);
    }

    /* Animations */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: none; }
    }
    .form-title    { animation: fadeUp .45s ease both .1s;  opacity: 0; }
    .form-subtitle { animation: fadeUp .45s ease both .15s; opacity: 0; }
    .field         { animation: fadeUp .45s ease both .2s;  opacity: 0; }
    .field:nth-child(2) { animation-delay: .25s; }
    .field-row     { animation: fadeUp .45s ease both .3s;  opacity: 0; }
    .submit        { animation: fadeUp .45s ease both .35s; opacity: 0; }
    .form-foot     { animation: fadeUp .45s ease both .4s;  opacity: 0; }

    /* ══════════════════════════════
       MOBILE — full navy bg,
       centered card with logo inside
    ══════════════════════════════ */
    @media (max-width: 860px) {

      /* Navy fills entire screen */
      html, body { background: var(--primary); }

      .shell {
        flex-direction: column;
        min-height: 100vh;
        background: var(--primary);
        align-items: center;
        justify-content: center;
        padding: 32px 20px;
        position: relative;
        overflow: hidden;
      }

      /* Grid texture on shell */
      .shell::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background-image:
          linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
        background-size: 48px 48px;
      }
      /* Glow blobs */
      .shell::after {
        content: ''; position: absolute; top: -80px; right: -80px;
        width: 320px; height: 320px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 65%);
        pointer-events: none;
      }

      /* Left panel disappears */
      .panel-left { display: none; }

      /* Right panel: transparent wrapper */
      .panel-right {
        flex: none; background: transparent; padding: 0;
        width: 100%; max-width: 420px;
        position: relative;
      }

      /* The card */
      .form-box {
        max-width: 100%;
        background: var(--surf);
        border-radius: 22px;
        padding: 36px 28px 28px;
        box-shadow: 0 24px 64px rgba(0,15,40,.4);
        position: relative;
      }

      /* Logo inside card at the top, centered */
      .form-box::before {
        content: '';
        display: block;
        width: 52px; height: 52px;
        border-radius: 14px;
        background: var(--primary-lt) url("{{ asset('img/dd.png') }}") center/26px no-repeat;
        border: 1px solid rgba(0,51,102,.12);
        margin: 0 auto 20px;
      }

      .form-title    { font-size: 22px; text-align: center; }
      .form-subtitle { text-align: center; margin-bottom: 28px; }
    }

    @media (max-width: 480px) {
      .shell    { padding: 24px 16px; }
      .form-box { padding: 30px 20px 24px; border-radius: 18px; }
      .field-row { flex-direction: column; align-items: flex-start; gap: 10px; }
    }
  </style>
</head>
<body>
<div class="shell">

  {{-- ══ LEFT (desktop only) ══ --}}
  <div class="panel-left">
    <div class="glow-b"></div>

    <div class="left-logo">
      <div class="left-logo-box">
        <img src="{{ asset('img/dd.png') }}" alt="Contracting Alliance Logo">
      </div>
      <div>
        <div class="left-brand">Contracting Alliance</div>
        <div class="left-brand-sub">Admin Portal</div>
      </div>
    </div>

    <div class="left-body">
      <div class="left-badge">
        <div class="left-badge-dot"></div>
        System Online
      </div>
      <h1 class="left-headline">
        Your operations,<br>
        <span>all in one place.</span>
      </h1>
      <p class="left-desc">
        Manage crews, invoices, contracts and job requests from a single secure dashboard.
      </p>
      <div class="left-features">
        <div class="left-feat">
          <div class="left-feat-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <span class="left-feat-text">Enterprise-grade security & access control</span>
        </div>
        <div class="left-feat">
          <div class="left-feat-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <span class="left-feat-text">Role-based access for all team members</span>
        </div>
        <div class="left-feat">
          <div class="left-feat-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <span class="left-feat-text">Real-time monitoring and reporting</span>
        </div>
      </div>
    </div>

    <div class="left-foot">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
      </svg>
      &copy; {{ date('Y') }} Contracting Alliance Inc. · Authorized access only.
    </div>
  </div>

  {{-- ══ RIGHT ══ --}}
  <div class="panel-right">
    <div class="form-box">

      <div class="form-title">Sign in</div>
      <div class="form-subtitle">Enter your credentials to continue</div>

      @if ($errors->any())
      <div class="form-alert">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ $errors->first() }}
      </div>
      @endif

      <form action="{{ route('superadmin.login') }}" method="POST" novalidate>
        @csrf

        <div class="field">
          <label class="field-label" for="email">Email address</label>
          <div class="field-wrap">
            <span class="field-ico">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </span>
            <input id="email" type="email" name="email" required autofocus
                   value="{{ old('email') }}"
                   class="field-input {{ $errors->has('email') ? 'is-error' : '' }}"
                   placeholder="admin@contractingalliance.com">
          </div>
          @error('email')
            <div class="field-err">{{ $message }}</div>
          @enderror
        </div>

        <div class="field">
          <label class="field-label" for="password">Password</label>
          <div class="field-wrap">
            <span class="field-ico">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </span>
            <input id="password" type="password" name="password" required
                   class="field-input {{ $errors->has('password') ? 'is-error' : '' }}"
                   placeholder="••••••••">
            <button type="button" class="pwd-eye" onclick="togglePwd()" aria-label="Toggle password">
              <svg id="eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          @error('password')
            <div class="field-err">{{ $message }}</div>
          @enderror
        </div>

      

        <button type="submit" class="submit">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          Sign in to Dashboard
        </button>

      </form>

      <div class="form-foot">
        &copy; {{ date('Y') }} Contracting Alliance Inc. All rights reserved.
      </div>

    </div>
  </div>

</div>

<script>
function togglePwd() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('eye-icon');
  const show  = input.type === 'password';
  input.type  = show ? 'text' : 'password';
  icon.innerHTML = show
    ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.548-4.26M15 12a3 3 0 00-4.243-2.829M9.88 9.88L4.12 4.12M6.1 6.1L3 3"/>`
    : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}
</script>

</body>
</html>