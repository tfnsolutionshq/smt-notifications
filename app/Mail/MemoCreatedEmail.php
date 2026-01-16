<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemoCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipient_name,
        public string $memo_title,
        public string $sender_name,
        public string $created_date,
        public string $memo_excerpt,
        public string $memo_link
    ) {}

    public function build()
    {
        return $this->view('emails.memo-created')
                    ->subject('New Memo: ' . $this->memo_title);
    }
}