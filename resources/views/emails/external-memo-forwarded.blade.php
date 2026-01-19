@extends('emails.layout')

@section('content')
<h2 style="color: #2c3e50; margin: 0 0 20px 0;">Hello {{ $recipient_name }},</h2>

<div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0; color: #1976d2; font-weight: bold;">📨 An external memo has been forwarded to you</p>
</div>

<p style="margin: 15px 0; color: #333;">{{ $forwarded_by }} has forwarded an external memo to you for your attention.</p>

<table style="width: 100%; background: #f8f9fa; border-radius: 4px; margin: 20px 0;">
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Subject:</strong> {{ $memo_subject }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Reference Number:</strong> {{ $memo_reference }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>From Organization:</strong> {{ $sender_organization }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px;">
            <strong>Forwarded By:</strong> {{ $forwarded_by }}
        </td>
    </tr>
</table>

@if(!empty($remarks))
<div style="background: #fff3e0; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #ff9800;">
    <strong>Remarks:</strong>
    <p style="margin: 10px 0 0 0; color: #333;">{{ $remarks }}</p>
</div>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $tracking_url }}" style="display: inline-block; padding: 12px 24px; background: #2c3e50; color: white; text-decoration: none; border-radius: 4px;">View Memo</a>
</div>
@endsection
