<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Models\NotificationLog;
use App\Jobs\SendEmailJob;
use App\Mail\MemoNotification;
use App\Services\IdentityClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    protected $identityClient;

    public function __construct(IdentityClient $identityClient)
    {
        $this->identityClient = $identityClient;
    }

    public function send(string $service, string $action, string $recipient, array $data = [], string $type = 'email'): bool
    {
        try {
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
                SendEmailJob::dispatch($log, $email, 'Notification', 'Email notification', $data);
            }

            return true;

        } catch (\Exception $e) {
            Log::error("Notification failed: " . $e->getMessage());

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
