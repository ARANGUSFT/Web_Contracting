<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Account Credentials - Contracting Alliance Inc</title>
    <style>
        /* Reset and base styles */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            background: #f8f9fa; 
            margin: 0; 
            padding: 0;
        }
        .email-wrapper { 
            width: 100%; 
            padding: 20px 0;
        }
        .email-container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .email-header { 
            background: linear-gradient(135deg, #0963e9 0%, #0a58ca 100%); 
            color: white; 
            padding: 30px; 
            text-align: center;
            position: relative;
        }
        .logo-container {
            margin-bottom: 15px;
        }
        .company-logo {
            max-width: 180px;
            height: auto;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            padding: 10px;
            border: 2px solid rgba(255,255,255,0.2);
        }
        .logo-placeholder {
            width: 180px;
            height: 60px;
            background: rgba(255,255,255,0.1);
            border: 2px dashed rgba(255,255,255,0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            font-weight: 600;
        }
        .email-body { 
            padding: 40px;
        }
        .credentials-box { 
            background: #f8f9fa; 
            border-radius: 8px; 
            padding: 25px; 
            margin: 25px 0; 
            border-left: 4px solid #0d6efd;
        }
        .security-notice { 
            background: #fff3cd; 
            border: 1px solid #ffeaa7; 
            border-radius: 8px; 
            padding: 20px; 
            margin: 25px 0;
        }
        .login-button { 
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); 
            color: white; 
            padding: 14px 35px; 
            text-decoration: none; 
            border-radius: 8px; 
            display: inline-block; 
            font-weight: 600; 
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .login-button:hover {
            background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(13, 110, 253, 0.3);
        }
        .footer { 
            text-align: center; 
            padding: 25px; 
            color: #6c757d; 
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        .credential-item {
            margin: 15px 0;
            padding: 10px 0;
        }
        .credential-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            font-size: 15px;
        }
        .credential-value {
            color: #0d6efd;
            font-weight: 600;
            font-size: 16px;
        }
        .password-value {
            color: #dc3545;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
            background: rgba(220, 53, 69, 0.05);
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
        }
        .help-section {
            background: #e7f3ff;
            border-radius: 8px;
            padding: 18px;
            margin: 20px 0;
        }
        .industry-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px;
            }
            .email-header {
                padding: 25px 20px;
            }
            .logo-placeholder {
                width: 150px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header with Logo -->
            <div class="email-header">
                <!-- Logo Placeholder - Replace with actual logo -->
                <div class="logo-container">
                    <div class="logo-placeholder">
                         CONTRACTING ALLIANCE INC
                    </div>

                </div>
                
                <h1 style="margin: 0; font-size: 28px; font-weight: 700;">Contracting Alliance Inc</h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 16px;">Professional Contracting Solutions</p>
            </div>
            
            <!-- Body Content -->
            <div class="email-body">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Welcome to the Team, {{ $name }}! 👋</h2>
                
                <p style="margin: 0 0 20px 0; color: #555; font-size: 16px;">
                    We're excited to welcome you to <strong>Contracting Alliance Inc</strong>! 
                    Your account has been created in our team management system. 
                    Below are your login credentials to access our platform.
                </p>
                
                <!-- Credentials Box -->
                <div class="credentials-box">
                    <h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 20px;">
                        <span class="industry-icon">🔐</span> Your Login Credentials
                    </h3>
                    
                    <div class="credential-item">
                        <div class="credential-label">📧 Email Address</div>
                        <div class="credential-value">{{ $email }}</div>
                    </div>
                    
                    <div class="credential-item">
                        <div class="credential-label">🔑 Temporary Password</div>
                        <div class="password-value">{{ $password }}</div>
                    </div>
                </div>
                
                <!-- Login Button -->
                <div style="text-align: center;">
                    <a href="https://crm.contractingallianceinc.com/team/login" class="login-button" style="color: white; text-decoration: none;">
                        🚀 Access Your Account
                    </a>
                </div>
                
                <!-- Security Notice -->
                <div class="security-notice">
                    <h4 style="margin: 0 0 12px 0; color: #856404;">
                        <span class="industry-icon">⚠️</span> Important Security Notice
                    </h4>
                    <p style="margin: 0; color: #856404; line-height: 1.5;">
                        <strong>For your security:</strong> Please change your password immediately after your first login. 
                        Keep your credentials confidential and never share them with anyone.
                    </p>
                </div>
                
                <!-- Help Section -->
                <div class="help-section">
                    <p style="margin: 0; color: #0a58ca; font-size: 14px; line-height: 1.5;">
                        <span class="industry-icon">💡</span> 
                        <strong>Getting Started Tip:</strong> Bookmark the login page for quick access. 
                        If you encounter any issues or need assistance, please contact your project manager or system administrator.
                    </p>
                </div>

                <!-- Industry Specific Welcome -->
                <div style="background: #d1ecf1; border-radius: 8px; padding: 18px; margin: 20px 0;">
                    <p style="margin: 0; color: #0c5460; font-size: 14px; line-height: 1.5;">
                        <span class="industry-icon">🏗️</span> 
                        <strong>Welcome to Contracting Alliance Inc!</strong> We're committed to excellence 
                        in construction and contracting services. We're glad to have you on board as part of our professional team.
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0; font-size: 14px; font-weight: 600;">
                    Contracting Alliance Inc
                </p>
                <p style="margin: 5px 0; font-size: 13px; color: #495057;">
                    Professional Contracting Solutions • Building Excellence Since 2024
                </p>
                <p style="margin: 8px 0 0 0; font-size: 12px; color: #868e96;">
                    &copy; {{ date('Y') }} Contracting Alliance Inc. All rights reserved.
                    <br>This is an automated message. Please do not reply to this email.
                </p>
            </div>
        </div>
    </div>
</body>
</html>