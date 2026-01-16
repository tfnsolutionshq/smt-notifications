<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginSuccessEmail;




Route::prefix('v1/')->group(function () {
    Route::get('test-identity', function () {
        try {
            $client = app(\App\Services\IdentityClient::class);

            return response()->json([
                'config' => [
                    'base_url' => config('services.identity.base_url'),
                    'has_api_key' => !empty(config('services.identity.api_key')),
                ],
                's2s_endpoints' => [
                    'single_user' => $client->getUserById("2e160ea0-a953-45ea-9090-f6b3243b6526"),
                    'batch_users' => $client->getUsersByIds(["2e160ea0-a953-45ea-9090-f6b3243b6526", "019abf45-9f3e-70b9-a1ef-dbc8dec13601"]),
                    'validate_user' => $client->getUsersByRole("019abf45-6685-7350-aaa6-3b69e7998664"),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
});


Route::prefix('v1')->group(function () {
    Route::post('notifications/send', [NotificationController::class, 'send']);
    Route::post('notifications/send-to-role', [NotificationController::class, 'sendToRole']);
    Route::post('share-memo', [NotificationController::class, 'shareMemo']);

    Route::get('test', function () {
        Mail::to('promisedeco24@gmail.com')->send(new LoginSuccessEmail(
            'John Doe',
            now()->format('Y-m-d H:i:s'),
            '127.0.0.1'
        ));

        return response()->json([
            'message' => 'Login success email sent!',
            'timestamp' => now()
        ]);
    });
});

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
