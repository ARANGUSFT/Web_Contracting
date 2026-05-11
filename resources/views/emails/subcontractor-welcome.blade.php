<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Welcome to Contracting Alliance</title>
</head>
<body style="margin:0;padding:0;font-family:'Montserrat',Arial,sans-serif;background-color:#f1f5f9;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f1f5f9;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);">

                    {{-- HERO --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);padding:36px 32px;text-align:center;">
                            <div style="display:inline-block;background:rgba(37,99,235,0.15);color:#60a5fa;padding:6px 14px;border-radius:99px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:14px;">
                                Account Created
                            </div>
                            <h1 style="font-size:26px;font-weight:800;color:#ffffff;margin:0 0 8px;letter-spacing:-0.025em;">
                                Welcome to Contracting Alliance!
                            </h1>
                            <p style="font-size:14px;color:#94a3b8;margin:0;line-height:1.5;">
                                Your subcontractor account has been successfully created
                            </p>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:15px;color:#334155;line-height:1.6;margin:0 0 24px;">
                                Hi <strong style="color:#0f172a;">{{ $subcontractor->name }} {{ $subcontractor->last_name }}</strong>,
                            </p>
                            <p style="font-size:14px;color:#475569;line-height:1.6;margin:0 0 28px;">
                                We've created an account for <strong style="color:#0f172a;">{{ $subcontractor->company_name }}</strong>
                                in our platform. With these credentials, you'll be able to access the system,
                                manage your jobs, update your profile, and much more.
                            </p>

                            {{-- DOWNLOAD THE APP --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(135deg,#0c4a6e 0%,#0e7490 100%);border-radius:14px;padding:24px;margin-bottom:24px;">
                                <tr>
                                    <td align="center">
                                        <div style="display:inline-block;background:rgba(255,255,255,0.15);color:#67e8f9;padding:4px 12px;border-radius:99px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:10px;">
                                            📱 Mobile App Required
                                        </div>
                                        <h2 style="font-size:18px;font-weight:800;color:#ffffff;margin:0 0 6px;letter-spacing:-0.02em;">
                                            Download the App to Get Started
                                        </h2>
                                        <p style="font-size:13px;color:#bae6fd;margin:0 0 20px;line-height:1.5;">
                                            Access your account from your phone or tablet
                                        </p>

                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;">
                                            <tr>
                                                <td style="padding:6px;">
                                                    <a href="{{ $googlePlayUrl }}" target="_blank"
                                                       style="display:inline-block;background-color:#000000;color:#ffffff;text-decoration:none;padding:10px 18px;border-radius:8px;border:1.5px solid #1e293b;min-width:160px;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td style="padding-right:10px;vertical-align:middle;">
                                                                    <span style="font-size:24px;line-height:1;color:#ffffff;">▶</span>
                                                                </td>
                                                                <td style="vertical-align:middle;text-align:left;">
                                                                    <div style="font-size:9px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;line-height:1;">Get it on</div>
                                                                    <div style="font-size:15px;font-weight:700;color:#ffffff;line-height:1.2;margin-top:2px;">Google Play</div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </a>
                                                </td>
                                                <td style="padding:6px;">
                                                    <a href="{{ $appStoreUrl }}" target="_blank"
                                                       style="display:inline-block;background-color:#000000;color:#ffffff;text-decoration:none;padding:10px 18px;border-radius:8px;border:1.5px solid #1e293b;min-width:160px;">
                                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td style="padding-right:10px;vertical-align:middle;">
                                                                    <span style="font-size:24px;line-height:1;color:#ffffff;">&#63743;</span>
                                                                </td>
                                                                <td style="vertical-align:middle;text-align:left;">
                                                                    <div style="font-size:9px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;line-height:1;">Download on the</div>
                                                                    <div style="font-size:15px;font-weight:700;color:#ffffff;line-height:1.2;margin-top:2px;">App Store</div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="font-size:11px;color:#bae6fd;margin:18px 0 0;line-height:1.4;">
                                            Tap the button that matches your device
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- CREDENTIALS --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;padding:24px;margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <p style="font-size:11px;font-weight:700;color:#0369a1;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 4px;">
                                            🔐 Your Login Credentials
                                        </p>
                                        <p style="font-size:12px;color:#0369a1;margin:0 0 18px;line-height:1.5;">
                                            Use these credentials to sign in once you've installed the app:
                                        </p>

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:14px;">
                                            <tr>
                                                <td style="padding-bottom:6px;">
                                                    <span style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">
                                                        📧 Username / Email
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color:#ffffff;border:1.5px solid #cbd5e1;border-radius:8px;padding:12px 14px;">
                                                    <span style="font-size:15px;font-weight:600;color:#0f172a;font-family:'Courier New',monospace;word-break:break-all;">
                                                        {{ $subcontractor->email }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding-bottom:6px;">
                                                    <span style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">
                                                        🔑 Password
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color:#ffffff;border:1.5px solid #cbd5e1;border-radius:8px;padding:12px 14px;">
                                                    <span style="font-size:15px;font-weight:700;color:#0f172a;font-family:'Courier New',monospace;letter-spacing:0.05em;">
                                                        {{ $plainPassword }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- SECURITY NOTE --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fffbeb;border:1px solid #fde68a;border-left:3px solid #d97706;border-radius:0 10px 10px 0;padding:14px 18px;margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <p style="font-size:13px;color:#92400e;line-height:1.55;margin:0;">
                                            <strong>⚠️ For your security:</strong> We recommend changing your password the first time you log in. Do not share these credentials with anyone.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- QUICK STEPS --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:11px;padding:18px 22px;">
                                <tr>
                                    <td>
                                        <p style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 10px;">
                                            🚀 Quick Start
                                        </p>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding:5px 0;font-size:13px;color:#334155;line-height:1.55;">
                                                    <strong style="color:#2563eb;">1.</strong> Download the app on your device (links above)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;font-size:13px;color:#334155;line-height:1.55;">
                                                    <strong style="color:#2563eb;">2.</strong> Open the app and tap "Sign In"
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;font-size:13px;color:#334155;line-height:1.55;">
                                                    <strong style="color:#2563eb;">3.</strong> Enter your email and password from above
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;font-size:13px;color:#334155;line-height:1.55;">
                                                    <strong style="color:#2563eb;">4.</strong> Update your profile and start working
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- COMPANY INFO --}}
                    <tr>
                        <td style="padding:0 32px 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px dashed #e2e8f0;padding-top:24px;">
                                <tr>
                                    <td>
                                        <p style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 12px;">
                                            📋 Your Account Information
                                        </p>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding:4px 0;font-size:12px;color:#64748b;width:40%;">Company</td>
                                                <td style="padding:4px 0;font-size:13px;color:#0f172a;font-weight:600;">{{ $subcontractor->company_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0;font-size:12px;color:#64748b;">Base State</td>
                                                <td style="padding:4px 0;font-size:13px;color:#0f172a;font-weight:600;">{{ $subcontractor->state }}</td>
                                            </tr>
                                            @if($subcontractor->phone)
                                            <tr>
                                                <td style="padding:4px 0;font-size:12px;color:#64748b;">Phone</td>
                                                <td style="padding:4px 0;font-size:13px;color:#0f172a;font-weight:600;">{{ $subcontractor->phone }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding:4px 0;font-size:12px;color:#64748b;">Account Status</td>
                                                <td style="padding:4px 0;font-size:13px;color:#059669;font-weight:600;">
                                                    {{ $subcontractor->is_active ? '✅ Active' : '⏸️ Pending Activation' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background-color:#f8fafc;padding:24px 32px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="font-size:12px;color:#64748b;line-height:1.5;margin:0 0 8px;">
                                Questions? Reach out to us anytime.
                            </p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">
                                © {{ date('Y') }} Contracting Alliance Inc. · All rights reserved
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>