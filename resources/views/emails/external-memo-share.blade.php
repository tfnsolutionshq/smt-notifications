@extends('emails.layout')

@section('content')
<h2 style="color: #2c3e50; margin: 0 0 20px 0;">External Memo Shared</h2>

<div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0; color: #2e7d32; font-weight: bold;">📄 An external memo has been shared with you</p>
</div>

@if(!empty($message))
<div style="background: #fff3e0; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #ff9800;">
    <strong>Message:</strong>
    <p style="margin: 10px 0 0 0; color: #333;">{{ $message }}</p>
</div>
@endif

<table style="width: 100%; background: #f8f9fa; border-radius: 4px; margin: 20px 0;">
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Subject:</strong> {{ $memo_subject }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Reference Number:</strong> {{ $reference_number }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
            <strong>Tracking ID:</strong> {{ $tracking_id }}
        </td>
    </tr>
    <tr>
        <td style="padding: 15px;">
            <strong>Status:</strong> <span style="color: #4caf50;">{{ ucfirst($status) }}</span>
        </td>
    </tr>
</table>

@if(!empty($timeline))
<h3 style="color: #2c3e50; margin: 30px 0 15px 0;">Movement Timeline</h3>
<div style="background: #f8f9fa; padding: 15px; border-radius: 4px;">
    @foreach($timeline as $index => $event)
    <div style="padding: 10px 0; {{ $index < count($timeline) - 1 ? 'border-bottom: 1px solid #dee2e6;' : '' }}">
        <div style="color: #666; font-size: 12px;">{{ $event['date'] ?? '' }}</div>
        <div style="color: #333; font-weight: bold; margin: 5px 0;">{{ $event['action'] ?? '' }}</div>
        @if(isset($event['location']))
        <div style="color: #666; font-size: 14px;">{{ $event['location'] }}</div>
        @endif
    </div>
    @endforeach
</div>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $tracking_url }}" style="display: inline-block; padding: 12px 24px; background: #2c3e50; color: white; text-decoration: none; border-radius: 4px;">Track Memo</a>
</div>
@endsection
