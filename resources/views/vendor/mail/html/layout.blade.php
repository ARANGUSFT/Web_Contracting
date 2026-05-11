<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $subject ?? 'Contracting Alliance' }}</title>
    <style type="text/css">
        body, table, td, p, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { width: 100% !important; height: 100%; margin: 0; padding: 0; background-color: #edf2f7; }

        /* ── Wrapper ── */
        .wrapper { width: 100%; background-color: #edf2f7; padding: 32px 16px; }

        /* ── Card ── */
        .card { background: #ffffff; border-radius: 16px; overflow: hidden; max-width: 580px; margin: 0 auto; }

        /* ── Header ── */
        .header {
            background-color: #003366;
            padding: 32px 40px;
            text-align: center;
        }
        .header-logo-box {
            display: inline-block;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px;
            padding: 10px 20px;
            margin-bottom: 16px;
        }
        .header h1 {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: rgba(255,255,255,.55);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── Divider accent ── */
        .header-accent {
            height: 3px;
            background: linear-gradient(90deg, #255b88, #1a7abf, #255b88);
        }

        /* ── Body ── */
        .body { padding: 36px 40px; }

        .greeting {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 12px 0;
        }
        .body p {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 15px;
            color: #475569;
            line-height: 1.7;
            margin: 0 0 16px 0;
        }

        /* ── Credentials box ── */
        .credentials-box {
            background: #f0f5fa;
            border-radius: 10px;
            border-left: 4px solid #255b88;
            padding: 20px 24px;
            margin: 24px 0;
        }
        .credentials-box table { width: 100%; }
        .cred-label {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 6px 0 2px;
        }
        .cred-value {
            font-family: 'Courier New', monospace;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            padding: 2px 0 10px;
        }

        /* ── Security notice ── */
        .security-notice {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 20px 0;
        }
        .security-notice p {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: #92400e;
            margin: 0;
            line-height: 1.5;
        }

        /* ── CTA Button ── */
        .btn-wrap { text-align: center; margin: 28px 0; }
        .btn-primary {
            background-color: #003366;
            color: #ffffff;
            padding: 13px 32px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 24px 0;
        }

        /* ── Footer ── */
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 22px 40px;
            text-align: center;
        }
        .footer p {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #94a3b8;
            margin: 0 0 4px 0;
            line-height: 1.5;
        }
        .footer .brand {
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 6px;
        }

        /* ── Mobile ── */
        @media only screen and (max-width: 600px) {
            .wrapper { padding: 20px 12px; }
            .header  { padding: 24px 22px; }
            .body    { padding: 26px 22px; }
            .footer  { padding: 18px 22px; }
            .credentials-box { padding: 16px 18px; }
        }
    </style>
</head>
<body>
<table class="wrapper" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td align="center">

    <table class="card" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:580px">

        {{-- ══ HEADER ══ --}}
        <tr>
            <td class="header">
                <div class="header-logo-box">
                    <span style="font-family:'Segoe UI',Arial,sans-serif;font-size:13px;font-weight:800;color:#fff;letter-spacing:2px;text-transform:uppercase">
                        CONTRACTING ALLIANCE
                    </span>
                </div>
                <h1>{{ config('app.name') }}</h1>
                <p>Team Management System</p>
            </td>
        </tr>

        {{-- Accent stripe --}}
        <tr><td class="header-accent"></td></tr>

        {{-- ══ BODY ══ --}}
        <tr>
            <td class="body">
                {{ $slot }}
            </td>
        </tr>

        {{-- ══ FOOTER ══ --}}
        <tr>
            <td class="footer">
                <p class="brand">Contracting Alliance Inc.</p>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>This is an automated message — please do not reply to this email.</p>
            </td>
        </tr>

    </table>

</td></tr>
</table>
</body>
</html>