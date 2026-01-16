<?php

// Test script for memo notifications
$baseUrl = 'http://127.0.0.1:8003/api/v1';

// Test 1: Send to specific users
$payload1 = [
    'service' => 'memo-service',
    'action' => 'memo_created',
    'recipient' => ['user-id-1', 'user-id-2'],
    'data' => [
        'memo_subject' => 'Test Memo Subject',
        'sender_name' => 'John Doe'
    ],
    'type' => 'email'
];

// Test 2: Send to role
$payload2 = [
    'service' => 'memo-service',
    'action' => 'approval_required',
    'role_id' => 'role-id-123',
    'data' => [
        'memo_subject' => 'Approval Needed',
        'workflow_step' => 'Manager Review'
    ],
    'type' => 'email'
];

echo "Test payloads created:\n";
echo "1. Send to users: " . json_encode($payload1, JSON_PRETTY_PRINT) . "\n\n";
echo "2. Send to role: " . json_encode($payload2, JSON_PRETTY_PRINT) . "\n\n";

echo "To test, run:\n";
echo "curl -X POST {$baseUrl}/notifications/send -H 'Content-Type: application/json' -d '" . json_encode($payload1) . "'\n";
echo "curl -X POST {$baseUrl}/notifications/send-to-role -H 'Content-Type: application/json' -d '" . json_encode($payload2) . "'\n";