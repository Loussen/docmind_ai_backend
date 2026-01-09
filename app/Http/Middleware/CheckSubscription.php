<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $requiredPlan = 'pro'): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Please log in to access this resource.',
            ], 401);
        }

        $subscription = $user->subscription;

        // Check if user has required subscription level
        $hasRequiredPlan = match ($requiredPlan) {
            'pro_plus' => $user->isProPlus(),
            'pro' => $user->isPremium(),
            'free' => true,
            default => false,
        };

        if (!$hasRequiredPlan) {
            return response()->json([
                'error' => 'Subscription required',
                'message' => "This feature requires a {$requiredPlan} subscription. Please upgrade to continue.",
                'required_plan' => $requiredPlan,
                'current_plan' => $user->getSubscriptionPlan(),
            ], 403);
        }

        return $next($request);
    }
}

