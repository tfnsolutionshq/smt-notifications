<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $user_name,
        public string $reset_url
    ) {}

    public function build()
    {
        return $this->view('emails.password-reset')
                    ->subject('Password Reset Request - ' . config('app.name'));
    }
}
