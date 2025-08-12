<!DOCTYPE html>
<html>
<head>
    <title>{{ $type === 'password-reset' ? 'Password Reset' : 'Verification' }} OTP</title>
</head>
<body>
<h1>{{ $type === 'password-reset' ? 'Password Reset' : 'Verification' }} OTP</h1>
<p>Your OTP is: <strong>{{ $otp }}</strong></p>
<p>This OTP is valid for 15 minutes.</p>
</body>
</html>
