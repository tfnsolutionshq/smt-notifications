<?php

// Example: Memo service controller with notification integration

namespace App\Http\Controllers;

use App\Traits\NotificationTrait;
use App\Traits\IdentityTrait;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    use NotificationTrait, IdentityTrait;

    protected function getServiceName(): string
    {
        return 'memo';
    }

    public function store(Request $request)
    {
        $user = $this->getAuthenticatedUser($request);
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Create memo logic here
        $memo = [
            'id' => 1,
            'title' => $request->title,
            'content' => $request->content,
            'created_at' => now(),
            'author' => $user['data']['first_name'] . ' ' . $user['data']['last_name']
        ];

        // Send notification to recipients
        $recipients = $request->recipients ?? [];
        foreach ($recipients as $recipient) {
            $this->sendNotification('memo_created', $recipient, [
                'recipient_name' => 'User', // You'd get this from user service
                'memo_title' => $memo['title'],
                'sender_name' => $memo['author'],
                'created_date' => $memo['created_at']->format('Y-m-d H:i:s'),
                'memo_excerpt' => substr($memo['content'], 0, 100) . '...',
                'memo_link' => url("/memos/{$memo['id']}")
            ]);
        }

        return response()->json(['memo' => $memo], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $this->getAuthenticatedUser($request);
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Update memo logic here
        $memo = [
            'id' => $id,
            'title' => $request->title,
            'content' => $request->content,
            'updated_at' => now(),
            'updater' => $user['data']['first_name'] . ' ' . $user['data']['last_name']
        ];

        // Send update notification
        $recipients = $request->recipients ?? [];
        foreach ($recipients as $recipient) {
            $this->sendNotification('memo_updated', $recipient, [
                'recipient_name' => 'User',
                'memo_title' => $memo['title'],
                'updater_name' => $memo['updater'],
                'updated_date' => $memo['updated_at']->format('Y-m-d H:i:s'),
                'memo_link' => url("/memos/{$memo['id']}")
            ]);
        }

        return response()->json(['memo' => $memo]);
    }
}