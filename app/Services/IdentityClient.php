<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IdentityClient
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.identity.base_url'), '/') . '/s2s';
        $this->apiKey = config('services.identity.api_key');
    }

    public function validateUser($userId)
    {
        return Http::withHeaders(['X-Service-Key' => $this->apiKey])
            ->post("{$this->baseUrl}/validate-user", ['user_id' => $userId])
            ->json();
    }

    public function getUsersByRole($roleId)
    {
        return Http::withHeaders(['X-Service-Key' => $this->apiKey])
            ->post("{$this->baseUrl}/users-by-role", ['role_id' => $roleId])
            ->json();
    }

    public function getUsersByDepartment($departmentId, ?string $search = null)
    {
        $payload = ['department_id' => $departmentId];
        if (!empty($search)) {
            $payload['search'] = $search;
        }

        return Http::withHeaders(['X-Service-Key' => $this->apiKey])
            ->post("{$this->baseUrl}/users-by-department", $payload)
            ->json();
    }

    public function getUserById($userId)
    {
        return Http::withHeaders(['X-Service-Key' => $this->apiKey])
            ->post("{$this->baseUrl}/user-by-id", ['user_id' => $userId])
            ->json();
    }

    public function getUsersByIds(array $userIds)
    {
        return Http::withHeaders(['X-Service-Key' => $this->apiKey])
            ->post("{$this->baseUrl}/users-by-ids", ['user_ids' => $userIds])
            ->json();
    }
}
