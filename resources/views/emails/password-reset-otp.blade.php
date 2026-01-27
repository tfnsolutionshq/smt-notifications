@extends('emails.layout')

@section('content')
<h2 style="color: #2c3e50; margin: 0 0 20px 0;">Hello {{ $user_name }},</h2>

<div style="background: #fff3e0; border-left: 4px solid #ff9800; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0; color: #e65100; font-weight: bold;">🔐 Password Reset Request</p>
</div>

<p style="margin: 15px 0; color: #333;">You have requested to reset your password. Use the OTP code below to complete the process:</p>

<div style="background: #f5f5f5; border: 2px dashed #2c3e50; padding: 20px; margin: 30px 0; text-align: center; border-radius: 8px;">
    <div style="font-size: 32px; font-weight: bold; color: #2c3e50; letter-spacing: 8px; font-family: 'Courier New', monospace;">{{ $otp }}</div>
</div>

<div style="background: #ffebee; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #f44336;">
    <p style="margin: 0; color: #c62828; font-size: 14px;">
        <strong>⚠️ Important:</strong> This OTP will expire in {{ $expires_in_minutes }} minutes.
    </p>
</div>

<p style="margin: 15px 0; color: #666; font-size: 14px;">If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
@endsection
