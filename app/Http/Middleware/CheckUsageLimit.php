<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUsageLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Please log in to access this resource.',
            ], 401);
        }

        // Premium users bypass limits
        if ($user->isPremium()) {
            return $next($request);
        }

        // Check daily upload limit for free users
        if (!$user->canUploadDocument()) {
            $limit = config('docmind.plans.free.docs_per_day', 3);
            
            return response()->json([
                'error' => 'Daily limit exceeded',
                'message' => "You have reached your daily limit of {$limit} documents. Upgrade to Pro for unlimited access.",
                'limit' => $limit,
                'used' => $user->getDailyUsageCount(),
                'resets_at' => now()->addDay()->startOfDay()->toISOString(),
            ], 429);
        }

        return $next($request);
    }
}

