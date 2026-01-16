@extends('emails.layout')

@section('content')
<h2 style="color: #2c3e50; margin: 0 0 20px 0;">Hello {{ $recipient_name }},</h2>

<div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0; color: #1976d2; font-weight: bold;">📝 New Memo Created</p>
</div>

<p style="margin: 15px 0; color: #333;">A new memo has been created for your attention.</p>

<table style="width: 100%; background: #f8f9fa; border-radius: 4px; margin: 20px 0;">
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Title:</strong> {{ $memo_title }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>From:</strong> {{ $sender_name }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px;">
            <strong>Date:</strong> {{ $created_date }}
        </td>
    </tr>
</table>

<div style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin: 20px 0;">
    {{ $memo_excerpt }}
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $memo_link }}" style="display: inline-block; padding: 12px 24px; background: #2c3e50; color: white; text-decoration: none; border-radius: 4px;">View Full Memo</a>
</div>
@endsection