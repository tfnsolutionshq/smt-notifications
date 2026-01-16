<?php

namespace App\Jobs;

use App\Models\NotificationLog;
use App\Traits\IdentityEmailTrait;
use App\Traits\MemoEmailTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IdentityEmailTrait, MemoEmailTrait;

    public function __construct(
        private NotificationLog $log,
        private string $recipient,
        private string $subject,
        private string $body,
        private array $data = []
    ) {}

    public function handle(): void
    {
        try {
            if ($this->log->service === 'identity' && $this->log->action === 'login') {
                $this->sendLoginEmail($this->recipient, $this->data);
            } elseif ($this->log->service === 'identity' && $this->log->action === 'reset_password_request') {
                $this->sendPasswordResetEmail($this->recipient, $this->data);
            } elseif ($this->log->service === 'memo-service' && ($this->log->action === 'memo_created' || $this->log->action === 'memo')) {
                $this->sendMemoCreatedEmail($this->recipient, $this->data);
            }

            $this->log->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error("Email job failed: " . $e->getMessage());
            
            $this->log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}