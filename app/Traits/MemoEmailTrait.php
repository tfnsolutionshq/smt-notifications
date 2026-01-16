<?php

namespace App\Traits;

use App\Mail\MemoCreatedEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait MemoEmailTrait
{
    public function sendMemoCreatedEmail($recipient, $data)
    {
        $title = $data['memo_subject'] ?? $data['memo_title'] ?? 'Untitled Memo';
        
        Mail::to($recipient)->send(new MemoCreatedEmail(
            $data['recipient_name'] ?? 'User',
            $title,
            $data['sender_name'] ?? 'System',
            $data['created_date'] ?? now()->format('Y-m-d H:i:s'),
            $data['memo_excerpt'] ?? 'No preview available',
            $data['memo_link'] ?? '#'
        ));
    }
}
