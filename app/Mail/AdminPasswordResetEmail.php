<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $user_name,
        public string $admin_name,
        public string $reset_url
    ) {}

    public function build()
    {
        return $this->view('emails.admin-password-reset')
                    ->subject('Password Reset by Administrator - ' . config('app.name'));
    }
}
