@extends('emails.layout')

@section('subject', 'Welcome to ' . config('app.name'))

@section('content')
<h2>Hello {{ $first_name }},</h2>

<div class="alert">
    <p><strong>Welcome to {{ config('app.name') }}! Your account has been created successfully.</strong></p>
</div>

<p><strong>Account Details:</strong></p>
<ul>
    <li><strong>Email:</strong> {{ $email }}</li>
    <li><strong>Department:</strong> {{ $department }}</li>
</ul>

<p>You can now log in to your account and start using our services.</p>
@endsection