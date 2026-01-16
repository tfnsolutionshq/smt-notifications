<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $first_name,
        public string $email,
        public string $department
    ) {}

    public function build()
    {
        return $this->view('emails.welcome')
                    ->subject('Welcome to ' . config('app.name'));
    }
}