@extends('emails.layout')

@section('content')
<h2 style="color: #2c3e50; margin: 0 0 20px 0;">Memo Processing Completed</h2>

<div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0; color: #2e7d32; font-weight: bold;">&#10003; Your external memo has been successfully completed</p>
</div>

<p style="margin: 15px 0; color: #333;">This is to notify you that the processing of your correspondence from <strong>{{ $sender_organization }}</strong> has been officially completed.</p>

<table style="width: 100%; background: #f8f9fa; border-radius: 4px; margin: 20px 0;">
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Subject:</strong> {{ $memo_subject }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Tracking ID:</strong> <span style="font-weight: bold; color: #2c3e50;">{{ $tracking_id }}</span>
        </td>
    </tr>
    @if(!empty($reference_number))
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Reference Number:</strong> {{ $reference_number }}
        </td>
    </tr>
    @endif
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Sender Organization:</strong> {{ $sender_organization }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Status:</strong> <span style="color: #4caf50; font-weight: bold;">Completed</span>
        </td>
    </tr>
    <tr>
        <td style="padding: 15px;">
            <strong>Completed Date:</strong> {{ $completed_at }}
        </td>
    </tr>
</table>

<p style="margin: 15px 0; color: #555; font-size: 14px;">
    You can review the full movement timeline and status of this memo anytime using your Tracking ID.
</p>

@if(!empty($tracking_url))
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $tracking_url }}" style="display: inline-block; padding: 12px 24px; background: #2c3e50; color: white; text-decoration: none; border-radius: 4px;">View Completed Memo</a>
</div>
@endif
@endsection
