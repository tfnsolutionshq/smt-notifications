<?php

// Add this to your Identity and Memo services config/services.php

return [
    // ... existing services

    'notification' => [
        'base_url' => env('NOTIFICATION_SERVICE_BASE_URL', 'http://127.0.0.1:8003/api/v1'),
    ],

    'memo' => [
        'base_url' => env('MEMO_SERVICE_BASE_URL', 'http://127.0.0.1:8001/api/v1'),
    ],

    'setting' => [
        'base_url' => env('SETTING_SERVICE_BASE_URL', 'http://127.0.0.1:8002/api/v1'),
    ],

    'identity' => [
        'base_url' => env('IDENTITY_SERVICE_BASE_URL', 'http://127.0.0.1:8000/api/v1'),
    ],
];