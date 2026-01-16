<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginSuccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $first_name,
        public string $login_time,
        public string $ip_address
    ) {}

    public function build()
    {
        return $this->view('emails.login-success')
                    ->subject('Login Alert - ' . config('app.name'));
    }
}