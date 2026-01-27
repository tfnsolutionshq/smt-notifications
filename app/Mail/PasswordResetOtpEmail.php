<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $user_name,
        public string $otp,
        public int $expires_in_minutes = 10
    ) {}

    public function build()
    {
        return $this->subject('Password Reset OTP')
                    ->view('emails.password-reset-otp');
    }
}
