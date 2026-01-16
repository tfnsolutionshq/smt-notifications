<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IdentityClient;
use Illuminate\Support\Facades\Http;

class TestIdentityConnection extends Command
{
    protected $signature = 'identity:test';
    protected $description = 'Test identity service connection';

    public function handle()
    {
        $this->info('Testing Identity Service Connection...');
        
        $baseUrl = config('services.identity.base_url');
        $apiKey = config('services.identity.api_key');
        
        $this->info("Base URL: {$baseUrl}");
        $this->info("API Key configured: " . ($apiKey ? 'Yes' : 'No'));
        
        try {
            $response = Http::timeout(5)->get($baseUrl);
            $this->info("HTTP Status: {$response->status()}");
            
            if ($response->successful()) {
                $this->info('✅ Basic HTTP connection successful');
            } else {
                $this->error('❌ HTTP connection failed');
                return 1;
            }
            
            $client = new IdentityClient();
            $result = $client->getUserById(1);
            
            if ($result) {
                $this->info('✅ Identity service connection working');
                $this->info('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
            } else {
                $this->error('❌ Identity service returned empty response');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Connection failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}