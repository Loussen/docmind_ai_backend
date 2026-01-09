<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\AppleAuthRequest;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create free subscription
        Subscription::create([
            'user_id' => $user->id,
            'plan' => 'free',
            'status' => 'active',
            'start_date' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration', 60 * 24 * 7),
            'user' => $this->formatUser($user),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke previous tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration', 60 * 24 * 7),
            'user' => $this->formatUser($user),
        ]);
    }

    public function appleAuth(AppleAuthRequest $request): JsonResponse
    {
        // Verify Apple identity token (simplified - in production, verify with Apple's servers)
        $identityToken = $request->identity_token;
        $authorizationCode = $request->authorization_code;
        
        // Decode the identity token to get user info
        $tokenParts = explode('.', $identityToken);
        if (count($tokenParts) !== 3) {
            return response()->json(['error' => 'Invalid identity token'], 400);
        }
        
        $payload = json_decode(base64_decode($tokenParts[1]), true);
        $appleId = $payload['sub'] ?? null;
        
        if (!$appleId) {
            return response()->json(['error' => 'Invalid token payload'], 400);
        }

        // Find or create user
        $user = User::where('apple_id', $appleId)->first();
        
        if (!$user) {
            $email = $request->email ?? $payload['email'] ?? null;
            
            if ($email) {
                $user = User::where('email', $email)->first();
            }
            
            if (!$user) {
                $user = User::create([
                    'name' => $request->full_name,
                    'email' => $email ?? "{$appleId}@privaterelay.appleid.com",
                    'apple_id' => $appleId,
                    'password' => Hash::make(str()->random(32)),
                ]);

                // Create free subscription
                Subscription::create([
                    'user_id' => $user->id,
                    'plan' => 'free',
                    'status' => 'active',
                    'start_date' => now(),
                ]);
            } else {
                $user->update(['apple_id' => $appleId]);
            }
        }

        // Revoke previous tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration', 60 * 24 * 7),
            'user' => $this->formatUser($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $this->formatUser($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    private function formatUser(User $user): array
    {
        $user->load('subscription');
        
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'avatar_url' => $user->avatar_url,
            'subscription_plan' => $user->getSubscriptionPlan(),
            'subscription_expires_at' => $user->subscription?->end_date?->toISOString(),
            'daily_usage_count' => $user->getDailyUsageCount(),
            'last_usage_date' => today()->toISOString(),
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
        ];
    }
}

