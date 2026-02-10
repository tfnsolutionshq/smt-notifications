@extends('emails.layout')

@section('subject', 'Welcome to ' . config('app.name'))

@section('content')
<h2>Hello {{ $user_name }},</h2>

<div class="alert">
    <p><strong>Welcome to {{ config('app.name') }}! Your account has been created successfully.</strong></p>
</div>

<p><strong>Your Login Credentials:</strong></p>
<ul>
    <li><strong>Email:</strong> {{ $email }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
</ul>

<p>Please log in and change your password after your first login for security purposes.</p>
@endsection