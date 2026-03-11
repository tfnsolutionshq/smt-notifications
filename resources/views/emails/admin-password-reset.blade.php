@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin: 0 0 20px 0;">Hello {{ $user_name }},</h2>
    
    <p style="margin: 15px 0; line-height: 1.6;">
        Your account administrator, <strong>{{ $admin_name }}</strong>, has initiated a password reset for your account.
    </p>
    
    <p style="margin: 15px 0; line-height: 1.6;">
        To set a new password and regain access to your account, please click the button below:
    </p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $reset_url }}" 
           style="display: inline-block; padding: 14px 32px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            Set New Password
        </a>
    </div>
    
    <p style="margin: 20px 0; color: #666;">
        If you have any questions or did not expect this password reset, please contact your system administrator immediately.
    </p>
@endsection
