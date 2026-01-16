<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IdentityService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.identity.base_url');
        $this->token = config('services.identity.token');
    }

    public function getUser($id, $token = null)
    {
        $url = config('services.identity.base_url') . "users/{$id}";
        $http = Http::timeout(10);
        if ($token) {
            $http = $http->withToken($token);
        }

        $response = $http->get($url);

        Log::info('Identity getUser call', ['url' => $url, 'status' => $response->status(), 'body' => $response->json()]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    // Example for calling other endpoints later
    public function getAllUsers()
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}users");

        return $response->json();
    }
}
