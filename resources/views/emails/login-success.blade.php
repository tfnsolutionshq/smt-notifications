@extends('emails.layout')

@section('content')
<h2 style="color: #2c3e50; margin: 0 0 20px 0;">Hello {{ $first_name }},</h2>

<div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0; color: #155724; font-weight: bold;">✓ Login Successful</p>
</div>

<p style="margin: 15px 0; color: #333;">You have successfully logged into your account.</p>

<table style="width: 100%; background: #f8f9fa; border-radius: 4px; margin: 20px 0;">
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Time:</strong> {{ $login_time }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px;">
            <strong>IP Address:</strong> {{ $ip_address }}
        </td>
    </tr>
</table>

<p style="margin: 20px 0; color: #666;">If this wasn't you, please contact support immediately.</p>
@endsection