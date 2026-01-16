@extends('emails.layout')

@section('content')
<p>Dear {{ $user['first_name'] ?? 'User' }},</p>

<p>{{ $emailMessage }}</p>

@if(isset($data['memo_subject']))
<div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <strong>Memo Subject:</strong> {{ $data['memo_subject'] }}
    @if(isset($data['workflow_step']))
    <br><strong>Workflow Step:</strong> {{ $data['workflow_step'] }}
    @endif
</div>
@endif
@endsection