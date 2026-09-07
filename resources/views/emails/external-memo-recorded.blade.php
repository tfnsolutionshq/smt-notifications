@extends('emails.layout')

@section('content')
<h2 style="color: #2c3e50; margin: 0 0 20px 0;">Memo Recorded Successfully</h2>

<div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0; color: #2e7d32; font-weight: bold;">&#10003; Your external memo has been received and recorded</p>
</div>

<p style="margin: 15px 0; color: #333;">This is to acknowledge that your correspondence from <strong>{{ $sender_organization }}</strong> has been officially received and logged into our system.</p>

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
        <td style="padding: 15px;">
            <strong>Date Received:</strong> {{ $date_received }}
        </td>
    </tr>
</table>

<p style="margin: 15px 0; color: #555; font-size: 14px;">
    You can use your <strong>Tracking ID</strong> (<strong>{{ $tracking_id }}</strong>) to track the progress of this memo.
</p>

@if(!empty($tracking_url))
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $tracking_url }}" style="display: inline-block; padding: 12px 24px; background: #2c3e50; color: white; text-decoration: none; border-radius: 4px;">Track Memo</a>
</div>
@endif
@endsection
