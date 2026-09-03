<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $user_name,
        public string $email,
        public string $password,
        public ?string $login_url = null
    ) {
        if (!$this->login_url) {
            $this->login_url = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/') . '/login';
        }
    }

    public function build()
    {
        return $this->view('emails.welcome')
                    ->subject('Welcome to ' . config('app.name'));
    }
}