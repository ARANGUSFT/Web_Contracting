<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $subject ?? 'Welcome' }}</title>
    <style type="text/css" rel="stylesheet" media="all">
        /* Base styles */
        body {
            width: 100% !important;
            height: 100%;
            margin: 0;
            -webkit-text-size-adjust: none;
        }
        
        .email-container {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f6f9fc;
        }
        
        .email-content {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .email-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e8ed;
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }
        
        .email-body {
            padding: 40px;
        }
        
        .credentials-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #0d6efd;
        }
        
        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <table class="email-container" role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="email-content" role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <!-- Header -->
                    <tr>
                        <td class="email-card">
                            <table class="email-header" role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
                                            {{ config('app.name') }}
                                        </h1>
                                        <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 16px;">
                                            Team Management System
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Email Body -->
                            <table class="email-body" role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        {{ $slot }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer -->
                            <table class="footer" role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <p style="margin: 0;">
                                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                                        </p>
                                        <p style="margin: 5px 0 0 0; font-size: 12px;">
                                            This is an automated message, please do not reply to this email.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>