<?php

// Example: Updated Identity AuthController with notification integration

namespace App\Http\Controllers;

use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    use NotificationTrait;

    protected function getServiceName(): string
    {
        return 'identity';
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'department_id' => 'required|exists:departments,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'department_id' => $request->department_id,
            'role_id' => $request->role_id,
            'institution_id' => Auth::user()->institution_id,
            'is_active' => true
        ]);

        $token = $user->createToken('auth')->plainTextToken;
        $user->load(['department', 'role']);

        // Send welcome email
        $this->sendNotification('register', $user->email, [
            'first_name' => $user->first_name,
            'email' => $user->email,
            'department' => $user->department->name,
            'app_name' => config('app.name')
        ]);

        return $this->successResponse([
            'token' => $token,
            'user' => $user
        ], 'User registered successfully');
    }

    public function login(Request $request)
    {
        $key = 'login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->logFailedLogin($request->email, 'rate_limit_exceeded');
            return response()->json([
                'status' => false,
                'message' => 'Too many login attempts. Try again in ' . gmdate("i:s", $seconds)
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            $this->logFailedLogin($request->email, 'invalid_credentials');
            return $this->errorResponse('The email address or password you entered is incorrect. Please check and try again.', 401);
        }

        if ($user->default_password ?? false) {
            $this->logFailedLogin($request->email, 'default_password_not_changed');
            return $this->errorResponse('Invalid credentials', 419);
        }

        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('auth')->plainTextToken;
        $user->load(['department', 'role']);

        $this->logLogin($user, 'email_password');

        // Send login notification
        $this->sendNotification('login', $user->email, [
            'first_name' => $user->first_name,
            'login_time' => now()->format('Y-m-d H:i:s'),
            'ip_address' => $request->ip(),
            'app_name' => config('app.name')
        ]);

        return $this->successResponse([
            'token' => $token,
            'user' => $user
        ], 'Logged in successfully');
    }

    public function resetPasswordRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            // Generate reset token logic here
            $resetLink = url('/reset-password?token=example_token');
            
            $this->sendNotification('reset_password_request', $user->email, [
                'first_name' => $user->first_name,
                'reset_link' => $resetLink,
                'app_name' => config('app.name')
            ]);
        }

        return $this->successResponse([], 'If the email exists, a reset link has been sent');
    }
}