<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Mail\ExternalMemoShareEmail;
use App\Mail\ExternalMemoForwardedEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'service' => 'required|string',
                'action' => 'required|in:login,register,reset_password_request,reset_password,memo_created,workflow_step_moved,approval_action_taken,workflow_completed',
                'recipient' => 'required',
                'data' => 'required|array',
                'type' => 'required|in:email'
            ]);

            $recipients = is_array($validated['recipient']) ? $validated['recipient'] : [$validated['recipient']];
            $recipients = array_unique($recipients);
            $sentCount = 0;

            foreach ($recipients as $recipient) {
                $result = $this->notificationService->send(
                    $validated['service'],
                    $validated['action'],
                    $recipient,
                    $validated['data'],
                    $validated['type']
                );
                if ($result) $sentCount++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Notifications sent',
                'sent_count' => $sentCount
            ]);

        } catch (\Exception $e) {
            Log::error('Notification send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notifications'
            ], 500);
        }
    }

    public function sendToRole(Request $request)
    {
        try {
            $validated = $request->validate([
                'service' => 'required|string',
                'action' => 'required|in:approval_required,memo_created,workflow_step_moved,approval_action_taken,workflow_completed',
                'role_id' => 'required|string',
                'data' => 'required|array',
                'type' => 'required|in:email'
            ]);

            $result = $this->notificationService->sendToRole(
                $validated['action'],
                $validated['role_id'],
                $validated['data']
            );

            return response()->json([
                'success' => true,
                'message' => 'Notifications sent',
                'sent_count' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Role notification send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notifications'
            ], 500);
        }
    }


    public function shareMemo(Request $request)
{
    try {
        $validated = $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'template' => 'required|string',
            'data' => 'required|array'
        ]);

        if ($validated['template'] === 'external_memo_share') {
            Log::info('Email data:', $validated['data']);

            $mailable = new ExternalMemoShareEmail(
                $validated['data']['tracking_id'],
                $validated['data']['tracking_url'],
                $validated['data']['memo_subject'],
                $validated['data']['reference_number'],
                $validated['data']['status'],
                $validated['data']['timeline'] ?? [],
                $validated['data']['message'] ?? null
            );

            Log::info('Mailable properties:', [
                'tracking_id' => $mailable->tracking_id,
                'memo_subject' => $mailable->memo_subject,
                'status' => $mailable->status,
            ]);

            Mail::to($validated['to'])->send($mailable);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Email send failed: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function shareMemo2(Request $request)
    {
        try {
            $validated = $request->validate([
                'to' => 'required|email',
                'subject' => 'required|string',
                'template' => 'required|string',
                'data' => 'required|array'
            ]);

            if ($validated['template'] === 'external_memo_share') {
                Mail::to($validated['to'])->send(new ExternalMemoShareEmail(
                    $validated['data']['tracking_id'],
                    $validated['data']['tracking_url'],
                    $validated['data']['memo_subject'],
                    $validated['data']['reference_number'],
                    $validated['data']['status'],
                    $validated['data']['timeline'] ?? [],
                    $validated['data']['message'] ?? null
                ));
            }

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email'
            ], 500);
        }
    }

    public function forwardMemo(Request $request)
    {
        try {
            $validated = $request->validate([
                'to' => 'required|email',
                'subject' => 'required|string',
                'template' => 'required|string',
                'data' => 'required|array'
            ]);

            if ($validated['template'] === 'external_memo_forwarded') {
                Mail::to($validated['to'])->send(new ExternalMemoForwardedEmail(
                    $validated['data']['recipient_name'],
                    $validated['data']['forwarded_by'],
                    $validated['data']['memo_subject'],
                    $validated['data']['memo_reference'],
                    $validated['data']['sender_organization'],
                    $validated['data']['remarks'] ?? null,
                    $validated['data']['tracking_url']
                ));
            }

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email'
            ], 500);
        }
    }
}
