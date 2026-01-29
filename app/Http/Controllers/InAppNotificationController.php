<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InAppNotificationController extends Controller
{
    public function getUserNotifications($userId, Request $request)
    {
        try {
            $limit = $request->get('limit', 20);
            $offset = $request->get('offset', 0);

            $notifications = InAppNotification::forUser($userId)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'total' => InAppNotification::forUser($userId)->count(),
                'unread_count' => InAppNotification::forUser($userId)->unread()->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get notifications'
            ], 500);
        }
    }

    public function markAsRead($notificationId)
    {
        try {
            $notification = InAppNotification::findOrFail($notificationId);
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ], 500);
        }
    }

    public function markAllAsRead($userId)
    {
        try {
            $count = InAppNotification::forUser($userId)
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'updated_count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark all notifications as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read'
            ], 500);
        }
    }

    public function getUnreadCount($userId)
    {
        try {
            $count = InAppNotification::forUser($userId)->unread()->count();

            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count'
            ], 500);
        }
    }

    public function create(Request $request)
    {
        Log::info('=== NEW REQUEST RECEIVED ===', [
            'timestamp' => now(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_id' => $request->header('X-Request-ID')
        ]);
        
        try {
            Log::info('In-app notification create called', ['request' => $request->all()]);
            
            $validated = $request->validate([
                'user_id' => 'required|string',
                'type' => 'required|string',
                'title' => 'required|string',
                'message' => 'required|string',
                'data' => 'nullable|array'
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            $notification = InAppNotification::create($validated);

            Log::info('Notification created', ['notification' => $notification]);

            return response()->json([
                'success' => true,
                'data' => $notification,
                'message' => 'Notification created'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createBulk(Request $request)
    {
        try {
            $validated = $request->validate([
                'notifications' => 'required|array',
                'notifications.*.user_id' => 'required|string',
                'notifications.*.type' => 'required|string',
                'notifications.*.title' => 'required|string',
                'notifications.*.message' => 'required|string',
                'notifications.*.data' => 'nullable|array'
            ]);

            $created = [];
            foreach ($validated['notifications'] as $notificationData) {
                $created[] = InAppNotification::create($notificationData);
            }

            return response()->json([
                'success' => true,
                'data' => $created,
                'count' => count($created)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create bulk notifications: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create notifications'], 500);
        }
    }
}
