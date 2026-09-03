@extends('emails.layout')

@section('subject', 'Welcome to ' . config('app.name'))

@section('content')
<h2>Hello {{ $user_name }},</h2>

<div class="alert">
    <p><strong>Welcome to {{ config('app.name') }}! Your account has been created successfully.</strong></p>
</div>

<p><strong>Your Login Credentials:</strong></p>
<ul style="line-height: 1.8;">
    <li><strong>Email:</strong> {{ $email }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
</ul>

<p>You can access your account using the login link below:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $login_url }}" 
       style="display: inline-block; padding: 14px 32px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
        Log In to Your Account
    </a>
</div>

<p style="color: #666; font-size: 13px; word-break: break-all;">
    Or copy and paste this link into your browser:<br>
    <a href="{{ $login_url }}" style="color: #007bff;">{{ $login_url }}</a>
</p>

<p>Please log in and change your password after your first login for security purposes.</p>
@endsection