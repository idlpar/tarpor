<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification Successful</title>
</head>
<body>
<h1>OTP Verification Successful</h1>
<p>Hello {{ $user->name }},</p>
<p>Your OTP verification was successful. You can now access your account.</p>
<p>If you did not initiate this verification, please contact our support team immediately.</p>
<p>Thank you,</p>
<p>{{ config('app.name') }}</p>
</body>
</html>
