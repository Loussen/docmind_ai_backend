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
        $device = $request->attributes->get('device');

        if (!$device) {
            return response()->json([
                'error' => 'Device not found',
                'message' => 'Please register your device first.',
            ], 401);
        }

        if ($device->isPremium()) {
            return $next($request);
        }

        if (!$device->canUploadDocument()) {
            $dailyLimit = config('docmind.plans.free.docs_per_day', 2);
            return response()->json([
                'error' => 'Free limit reached',
                'message' => "You have used your {$dailyLimit} free documents for today. Upgrade to Pro for unlimited access.",
                'free_limit' => $dailyLimit,
                'total_used' => $device->getDailyUsageCount(),
            ], 429);
        }

        return $next($request);
    }
}

