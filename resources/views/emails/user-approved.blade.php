<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial; background:#f4f6f9; padding:20px;">

<div style="max-width:600px;margin:auto;background:white;padding:30px;border-radius:8px;">

    <h2 style="color:#198754;">Your Account Has Been Approved 🎉</h2>

    <p>Hello {{ $user->name }},</p>

    <p>
        Great news! Your account has been approved.
        You can now access the platform using your credentials.
    </p>

    <div style="background:#f8f9fa;padding:15px;border-radius:6px;margin:20px 0;">
        <strong>Login Email:</strong><br>
        {{ $user->email }}
    </div>

    <div style="margin:20px 0;">
        <a href="{{ url('/login') }}"
           style="background:#198754;color:white;padding:12px 20px;text-decoration:none;border-radius:6px;">
            Login Now
        </a>
    </div>

    <p>
        If you forgot your password, you can reset it from the login page.
    </p>

    <p style="margin-top:30px;">
        Best regards,<br>
        <strong>{{ config('app.name') }}</strong>
    </p>

</div>

</body>
</html>