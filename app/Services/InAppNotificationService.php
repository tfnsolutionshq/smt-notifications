<?php

namespace App\Services;

use App\Models\InAppNotification;

class InAppNotificationService
{
    public function createMemoNotification($userId, $type, $memoData)
    {
        $titles = [
            'memo_received' => 'New Memo Received',
            'memo_action_required' => 'Action Required',
            'workflow_updated' => 'Workflow Updated',
            'memo_approved' => 'Memo Approved',
            'memo_rejected' => 'Memo Rejected'
        ];

        $messages = [
            'memo_received' => "You have received a new memo: {$memoData['subject']}",
            'memo_action_required' => "Action required for memo: {$memoData['subject']}",
            'workflow_updated' => "Workflow updated for memo: {$memoData['subject']}",
            'memo_approved' => "Memo has been approved: {$memoData['subject']}",
            'memo_rejected' => "Memo has been rejected: {$memoData['subject']}"
        ];

        return InAppNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $titles[$type] ?? 'Notification',
            'message' => $messages[$type] ?? 'You have a new notification',
            'data' => $memoData
        ]);
    }

    public function notifyMultipleUsers($userIds, $type, $memoData)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = $this->createMemoNotification($userId, $type, $memoData);
        }
        return $notifications;
    }
}