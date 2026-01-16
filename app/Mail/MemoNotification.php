<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemoNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $action;
    protected $data;
    protected $user;

    public function __construct($action, $data, $user)
    {
        $this->action = $action;
        $this->data = $data;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject($this->getSubject())
                    ->view('emails.memo-notification')
                    ->with([
                        'emailMessage' => $this->getMessage(),
                        'user' => $this->user,
                        'data' => $this->data
                    ]);
    }

    private function getSubject()
    {
        switch ($this->action) {
            case 'memo_created':
                return 'New Memo: ' . ($this->data['memo_subject'] ?? 'Untitled');
            case 'approval_required':
                return 'Approval Required: ' . ($this->data['memo_subject'] ?? 'Untitled');
            case 'workflow_step_moved':
                return 'Memo Updated: ' . ($this->data['memo_subject'] ?? 'Untitled');
            case 'approval_action_taken':
                return 'Memo Action: ' . ($this->data['memo_subject'] ?? 'Untitled');
            case 'workflow_completed':
                return 'Memo Completed: ' . ($this->data['memo_subject'] ?? 'Untitled');
            default:
                return 'Memo Notification';
        }
    }

    private function getMessage()
    {
        switch ($this->action) {
            case 'memo_created':
                return "You have received a new memo: {$this->data['memo_subject']} from {$this->data['sender_name']}";
            case 'approval_required':
                return "Memo '{$this->data['memo_subject']}' requires your approval at step: {$this->data['workflow_step']}";
            case 'workflow_step_moved':
                return "Memo '{$this->data['memo_subject']}' has moved to workflow step: {$this->data['workflow_step']}";
            case 'approval_action_taken':
                return "{$this->data['actor_name']} {$this->data['action']} memo: {$this->data['memo_subject']}";
            case 'workflow_completed':
                return "Workflow for memo '{$this->data['memo_subject']}' has been completed";
            default:
                return 'You have a memo notification';
        }
    }
}