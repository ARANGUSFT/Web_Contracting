<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial; background:#f4f6f9; padding:20px;">

<div style="max-width:600px;margin:auto;background:white;padding:30px;border-radius:8px;">

    <h2 style="color:#dc3545;">Application Not Approved</h2>

    <p>Hello {{ $user->name }},</p>

    <p>
        Thank you for your interest in joining our platform.
        After reviewing your application, we regret to inform you
        that it was not approved at this time.
    </p>

    @if($reason)
        <div style="background:#f8d7da;padding:15px;border-radius:6px;margin:20px 0;">
            <strong>Reason provided:</strong><br><br>
            {{ $reason }}
        </div>
    @endif

    <p>
        If you believe this decision was made in error or would like
        to submit updated documentation, please contact our support team.
    </p>

    <p style="margin-top:30px;">
        Best regards,<br>
        <strong>{{ config('app.name') }}</strong>
    </p>

</div>

</body>
</html>