<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Models\NotificationLog;
use App\Mail\MemoNotification;
use App\Mail\PasswordResetOtpEmail;
use App\Services\IdentityClient;
use App\Traits\IdentityEmailTrait;
use App\Traits\MemoEmailTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    use IdentityEmailTrait, MemoEmailTrait;
    protected $identityClient;

    public function __construct(IdentityClient $identityClient)
    {
        $this->identityClient = $identityClient;
    }

    public function send(string $service, string $action, string $recipient, array $data = [], string $type = 'email'): bool
    {
        try {
            Log::info("Notification send called", ['service' => $service, 'action' => $action, 'recipient' => $recipient]);
            
            $email = $recipient;

            if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $user = $this->identityClient->getUserById($recipient);
                if (!isset($user['user']['email'])) {
                    throw new \Exception("Email not found for user: {$recipient}");
                }
                $email = $user['user']['email'];
            }

            $log = NotificationLog::create([
                'service' => $service,
                'action' => $action,
                'type' => $type,
                'recipient' => $email,
                'subject' => 'Notification',
                'body' => 'Email notification',
                'status' => 'pending'
            ]);

            if ($type === 'email') {
                if ($service === 'identity' && $action === 'login') {
                    $this->sendLoginEmail($email, $data);
                } elseif ($service === 'identity' && $action === 'reset_password_request') {
                    $this->sendPasswordResetEmail($email, $data);
                } elseif ($service === 'identity' && $action === 'forgot_password_otp') {
                    $this->sendPasswordResetOtpEmail($email, $data);
                } elseif ($service === 'identity' && $action === 'user_created') {
                    $this->sendUserCreatedEmail($email, $data);
                } elseif ($service === 'memo-service' && ($action === 'memo_created' || $action === 'memo')) {
                    $this->sendMemoCreatedEmail($email, $data);
                }

                $log->update(['status' => 'sent', 'sent_at' => now()]);
            }

            Log::info("Notification sent successfully");
            return true;

        } catch (\Exception $e) {
            Log::error("Notification failed: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());

            if (isset($log)) {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }

            return false;
        }
    }

    public function sendToUsers($action, $userIds, $data)
    {
        $users = $this->identityClient->getUsersByIds($userIds);
        $sentCount = 0;

        if (isset($users['users'])) {
            foreach ($users['users'] as $user) {
                if (isset($user['email'])) {
                    try {
                        Mail::to($user['email'])->send(new MemoNotification($action, $data, $user));
                        $sentCount++;
                    } catch (\Exception $e) {
                        Log::error("Failed to send email to {$user['email']}: {$e->getMessage()}");
                    }
                }
            }
        }

        return $sentCount;
    }

    public function sendToRole($action, $roleId, $data)
    {
        $users = $this->identityClient->getUsersByRole($roleId);
        $sentCount = 0;

        if (isset($users['users'])) {
            foreach ($users['users'] as $user) {
                if (isset($user['email'])) {
                    try {
                        Mail::to($user['email'])->send(new MemoNotification($action, $data, $user));
                        $sentCount++;
                    } catch (\Exception $e) {
                        Log::error("Failed to send email to {$user['email']}: {$e->getMessage()}");
                    }
                }
            }
        }

        return $sentCount;
    }
}
