<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subject,
        public string $body
    ) {}

    public function build()
    {
        return $this->view('emails.generic')
                    ->subject($this->subject)
                    ->with(['body' => $this->body]);
    }
}