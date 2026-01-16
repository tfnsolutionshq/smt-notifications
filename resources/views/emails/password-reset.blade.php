@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin: 0 0 20px 0;">Hello {{ $user_name }},</h2>
    
    <p style="margin: 15px 0; line-height: 1.6;">
        You have requested a password reset. Click the button below to reset your password:
    </p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $reset_url }}" 
           style="display: inline-block; padding: 14px 32px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            Reset Password
        </a>
    </div>
    
    <p style="margin: 20px 0; color: #666; font-size: 14px;">
        <strong>Note:</strong> This link will expire in 60 minutes.
    </p>
    
    <p style="margin: 20px 0; color: #666;">
        If you didn't request this, please ignore this email.
    </p>
@endsection
