@extends('emails.layout')

@section('subject', 'Login Alert - ' . config('app.name'))

@section('content')
<h2>Hello {{ $first_name }},</h2>

<div class="alert">
    <p><strong>You have successfully logged into your account.</strong></p>
</div>

<p><strong>Login Details:</strong></p>
<ul>
    <li><strong>Time:</strong> {{ $login_time }}</li>
    <li><strong>IP Address:</strong> {{ $ip_address }}</li>
</ul>

<p>If this wasn't you, please contact support immediately.</p>
@endsection