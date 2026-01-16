<?php

namespace App\Traits;

use App\Services\IdentityClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait IdentityTrait
{
    /**
     * Fetch the authenticated user based on the bearer token.
     */
    public function getAuthenticatedUser(Request $request)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return null;
        }

        $baseUrl = rtrim(config('services.identity.base_url'), '/');
        $url = "{$baseUrl}/users/me";


        try {
            $response = Http::withToken($token)->get($url);

            if ($response->failed()) {
                Log::error("Failed to fetch authenticated user: {$response->status()}");
                return null;
            }

            return $response->json(); // returns user object (id, name, email, etc.)
        } catch (\Throwable $e) {
            Log::error('Error fetching authenticated user: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch any user by their user_id using S2S.
     */
    public function getUserById(string $userId, ?string $token = null)
    {
        try {
            $client = app(IdentityClient::class);
            $response = $client->getUserById($userId);

            if (!$response || !isset($response['user'])) {
                Log::warning("User not found: {$userId}");
                return null;
            }

            return ['data' => $response['user']];
        } catch (\Throwable $e) {
            Log::error("Error fetching user by ID ({$userId}): " . $e->getMessage());
            return null;
        }
    }

    public function verifyRecipientEmails(array $emails, string $institutionId, ?string $token = null)
    {
        $baseUrl = rtrim(config('services.identity.base_url'), '/');
        $url = "{$baseUrl}/users/verify-recipients";

        try {
            $http = Http::timeout(10);
            if ($token) {
                $http = $http->withToken($token);
            }

            $response = $http->post($url, [
                'emails' => $emails,
                'institution_id' => $institutionId,
            ]);

            if ($response->failed()) {
                Log::warning("Failed to verify recipient emails: " . $response->status());
                return ['valid' => [], 'invalid' => $emails];
            }

            $data = $response->json();

            return [
                'valid' => $data['valid_recipients'] ?? [],
                'invalid' => $data['invalid_emails'] ?? [],
            ];
        } catch (\Throwable $e) {
            Log::error("Error verifying recipient emails: " . $e->getMessage());
            return ['valid' => [], 'invalid' => $emails];
        }
    }

    public function getUsersByRole(string $role, ?string $token = null)
    {
        $baseUrl = rtrim(config('services.identity.base_url'), '/');
        $url = "{$baseUrl}api/v1/users/roles/{$role}";

        try {
            $http = Http::timeout(10);

            // Add token if provided
            if ($token) {
                $http = $http->withToken($token);
            }

            $response = $http->get($url);

            if ($response->failed()) {
                Log::warning("Failed to fetch users by role: {$role} - Status: {$response->status()}");
                return collect();
            }

            $responseData = $response->json();
            $users = $responseData['data'] ?? [];
            
            Log::info("Successfully fetched users by role", [
                'role' => $role,
                'count' => is_array($users) ? count($users) : 0
            ]);

            return collect($users);
        } catch (\Throwable $e) {
            Log::error("Error fetching users by role ({$role}): " . $e->getMessage());
            return collect();
        }
    }

    public function getRoleById(string $roleId, ?string $token = null)
    {
        $baseUrl = rtrim(config('services.identity.base_url'), '/');
        $url = "{$baseUrl}/roles/{$roleId}";

        try {
            $http = Http::timeout(10);
            if ($token) {
                $http = $http->withToken($token);
            }

            $response = $http->get($url);

            if ($response->failed()) {
                Log::warning($response);
                return null;
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error("Error fetching role by ID ({$roleId}): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Batch fetch user details for multiple user IDs using S2S
     */
    public function batchGetUserDetails(array $userIds, ?string $token = null): array
    {
        if (empty($userIds)) {
            return [];
        }

        try {
            $client = app(IdentityClient::class);
            $response = $client->getUsersByIds(array_unique($userIds));
            
            $userDetailsMap = [];
            
            if (isset($response['users'])) {
                foreach ($response['users'] as $user) {
                    $userDetailsMap[$user['id']] = [
                        'recipient_name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: 'Unknown User',
                        'recipient_email' => $user['email'] ?? null,
                        'first_name' => $user['first_name'] ?? null,
                        'last_name' => $user['last_name'] ?? null,
                    ];
                }
            }
            
            // Fill missing users
            foreach ($userIds as $userId) {
                if (!isset($userDetailsMap[$userId])) {
                    $userDetailsMap[$userId] = [
                        'recipient_name' => 'Unknown User',
                        'recipient_email' => null,
                        'first_name' => null,
                        'last_name' => null,
                    ];
                }
            }

            return $userDetailsMap;
        } catch (\Throwable $e) {
            Log::error("Error batch fetching users: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Enhance memo recipients with user details
     */
    public function enhanceMemoRecipientsWithNames($memos, ?string $token = null)
    {
        // Collect all unique recipient IDs
        $allRecipientIds = collect();
        foreach ($memos as $memo) {
            if ($memo->recipients) {
                foreach ($memo->recipients as $recipient) {
                    $allRecipientIds->push($recipient->recipient_id);
                }
            }
        }

        // Batch fetch user details
        $userDetailsMap = $this->batchGetUserDetails($allRecipientIds->unique()->toArray(), $token);

        // Enhance recipients with user details
        foreach ($memos as $memo) {
            if ($memo->recipients) {
                foreach ($memo->recipients as $recipient) {
                    $userDetails = $userDetailsMap[$recipient->recipient_id] ?? [
                        'recipient_name' => 'Unknown User',
                        'recipient_email' => null,
                        'first_name' => null,
                        'last_name' => null,
                    ];

                    $recipient->recipient_name = $userDetails['recipient_name'];
                    $recipient->recipient_email = $userDetails['recipient_email'];
                    $recipient->first_name = $userDetails['first_name'];
                    $recipient->last_name = $userDetails['last_name'];
                }
            }
        }

        return $memos;
    }
}
