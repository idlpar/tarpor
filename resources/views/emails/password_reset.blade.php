<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Successful</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
<div style="max-width: 600px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
    <h2 style="color: #4CAF50; text-align: center;">Password Changed Successfully</h2>
    <p>Hello {{ $user->name }},</p>
    <p>We wanted to inform you that your password has been successfully changed. If you did not make this request, please contact our support team immediately.</p>
    <p><strong>If this was you, no further action is required.</strong></p>
    <p style="margin-top: 20px; text-align: center;">
        <a href="{{ route('login') }}" style="background: #4CAF50; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Log in Now</a>
    </p>
    <p style="margin-top: 20px;">Best Regards,<br><strong>Your Support Team</strong></p>
</div>
</body>
</html>
