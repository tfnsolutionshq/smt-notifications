<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExternalMemoForwardedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipient_name,
        public string $forwarded_by,
        public string $memo_subject,
        public string $memo_reference,
        public string $sender_organization,
        public ?string $remarks,
        public string $tracking_url
    ) {}

    public function build()
    {
        return $this->subject('External Memo Forwarded to You')
                    ->view('emails.external-memo-forwarded');
    }
}
