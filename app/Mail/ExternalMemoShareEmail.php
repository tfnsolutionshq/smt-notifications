<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExternalMemoShareEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $tracking_id,
        public string $tracking_url,
        public string $memo_subject,
        public string $reference_number,
        public string $status,
        public array $timeline,
        public ?string $message = null
    ) {}

    public function build()
    {
        return $this->view('emails.external-memo-share')
                    ->with(['email_subject' => 'External Memo Shared: ' . $this->memo_subject])
                    ->subject('External Memo Shared: ' . $this->memo_subject);
    }

}
