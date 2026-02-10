<?php

namespace App\Traits;

use App\Mail\LoginSuccessEmail;
use App\Mail\PasswordResetEmail;
use App\Mail\PasswordResetOtpEmail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

trait IdentityEmailTrait
{
    public function sendLoginEmail($recipient, $data)
    {
        Mail::to($recipient)->send(new LoginSuccessEmail(
            $data['first_name'] ?? 'User',
            $data['login_time'] ?? now()->format('Y-m-d H:i:s'),
            $data['ip_address'] ?? '127.0.0.1'
        ));
    }

    public function sendPasswordResetEmail($recipient, $data)
    {
        Mail::to($recipient)->send(new PasswordResetEmail(
            $data['user_name'] ?? 'User',
            $data['reset_url'] ?? ''
        ));
    }

    public function sendPasswordResetOtpEmail($recipient, $data)
    {
        Mail::to($recipient)->send(new PasswordResetOtpEmail(
            $data['user_name'] ?? 'User',
            $data['otp'],
            $data['expires_in_minutes'] ?? 10
        ));
    }

    public function sendUserCreatedEmail($recipient, $data)
    {
        Mail::to($recipient)->send(new WelcomeEmail(
            $data['user_name'] ?? 'User',
            $data['email'],
            $data['password']
        ));
    }
}