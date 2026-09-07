<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExternalMemoCompletedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $sender_organization,
        public string $memo_subject,
        public string $tracking_id,
        public ?string $reference_number,
        public string $completed_at,
        public ?string $tracking_url = null
    ) {}

    public function build()
    {
        return $this->subject('External Memo Completed: ' . $this->memo_subject)
                    ->view('emails.external-memo-completed');
    }
}
