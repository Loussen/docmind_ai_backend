<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\VerifyPurchaseRequest;
use App\Models\Subscription;
use App\Models\UsageLog;
use App\Services\AppleReceiptVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        private AppleReceiptVerificationService $appleVerification
    ) {}

    public function show(Request $request): JsonResponse
    {
        $subscription = $request->user()->subscription;

        if (!$subscription) {
            // Create default free subscription
            $subscription = Subscription::create([
                'user_id' => $request->user()->id,
                'plan' => 'free',
                'status' => 'active',
                'start_date' => now(),
            ]);
        }

        return response()->json([
            'subscription' => $this->formatSubscription($subscription),
        ]);
    }

    public function plans(): JsonResponse
    {
        $plans = collect(config('docmind.plans'))->map(function ($plan, $key) {
            return [
                'id' => $key,
                'name' => $plan['name'],
                'description' => $plan['name'] . ' Plan',
                'monthly_price' => $plan['price_monthly'],
                'yearly_price' => $plan['price_yearly'],
                'features' => $plan['features'],
                'is_popular' => $key === 'pro',
                'plan_type' => $key,
            ];
        })->values();

        return response()->json([
            'plans' => $plans,
        ]);
    }

    public function verify(VerifyPurchaseRequest $request): JsonResponse
    {
        $user = $request->user();
        
        try {
            \Log::info('Purchase verification started', [
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'transaction_id' => $request->transaction_id,
            ]);
            
            $verificationResult = $this->appleVerification->verify(
                receiptData: $request->receipt_data,
                productId: $request->product_id
            );

            \Log::info('Verification result', $verificationResult);

            // Check if verification was successful
            if (!$verificationResult['valid']) {
                \Log::warning('Purchase verification failed', [
                    'user_id' => $user->id,
                    'message' => $verificationResult['message'] ?? 'Unknown error',
                    'environment' => $verificationResult['environment'] ?? 'unknown',
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => $verificationResult['message'] ?? 'Receipt verification failed',
                ], 400);
            }

            // Determine plan from product ID (use receipt product ID if available)
            $actualProductId = $verificationResult['subscription']['product_id'] ?? $request->product_id;
            $plan = $this->getPlanFromProductId($actualProductId);
            
            // Calculate end date - use from receipt if available, otherwise calculate
            $receiptExpiresDate = $verificationResult['subscription']['expires_date'] ?? null;
            if ($receiptExpiresDate) {
                $endDate = new \DateTime($receiptExpiresDate);
            } else {
                $endDate = $this->calculateEndDate($actualProductId);
            }

            // Create or update subscription
            $subscription = $user->subscription;
            
            $subscriptionData = [
                'plan' => $plan,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => $endDate,
                'apple_transaction_id' => $request->transaction_id,
                'apple_original_transaction_id' => $verificationResult['original_transaction_id'] ?? $request->transaction_id,
                'apple_product_id' => $actualProductId,
                'is_auto_renewing' => $verificationResult['auto_renewing'] ?? false,
                'receipt_data' => ['receipt' => $request->receipt_data],
            ];
            
            if ($subscription) {
                $subscription->update($subscriptionData);
                \Log::info('Subscription updated', ['subscription_id' => $subscription->id, 'plan' => $plan]);
            } else {
                $subscriptionData['user_id'] = $user->id;
                $subscription = Subscription::create($subscriptionData);
                \Log::info('Subscription created', ['subscription_id' => $subscription->id, 'plan' => $plan]);
            }

            return response()->json([
                'success' => true,
                'subscription' => $this->formatSubscription($subscription),
                'message' => 'Subscription activated successfully',
            ]);

        } catch (\Exception $e) {
            \Log::error('Purchase verification error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request): JsonResponse
    {
        $subscription = $request->user()->subscription;

        if (!$subscription || $subscription->plan === 'free') {
            return response()->json([
                'error' => 'No active subscription to cancel',
            ], 400);
        }

        $subscription->cancel();

        return response()->json([
            'message' => 'Subscription cancelled. You will retain access until the end of your billing period.',
            'subscription' => $this->formatSubscription($subscription),
        ]);
    }

    public function usage(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = $user->subscription;
        
        $dailyLimit = $subscription?->getDocsPerDay() ?? 3;
        $dailyUsed = UsageLog::getDailyCount($user, 'upload');
        
        $totalDocs = $user->documents()->count();
        $totalSummaries = $user->summaries()->count();

        return response()->json([
            'usage' => [
                'daily_docs_used' => $dailyUsed,
                'daily_docs_limit' => $dailyLimit,
                'total_docs_processed' => $totalDocs,
                'total_summaries_generated' => $totalSummaries,
                'last_reset_date' => today()->startOfDay()->toISOString(),
            ],
        ]);
    }

    private function formatSubscription(Subscription $subscription): array
    {
        return [
            'id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'plan' => $subscription->plan,
            'status' => $subscription->status,
            'start_date' => $subscription->start_date?->toISOString(),
            'end_date' => $subscription->end_date?->toISOString(),
            'apple_transaction_id' => $subscription->apple_transaction_id,
            'apple_original_transaction_id' => $subscription->apple_original_transaction_id,
            'is_auto_renewing' => $subscription->is_auto_renewing,
            'created_at' => $subscription->created_at->toISOString(),
            'updated_at' => $subscription->updated_at->toISOString(),
        ];
    }

    private function getPlanFromProductId(string $productId): string
    {
        $plans = config('docmind.plans');
        
        foreach ($plans as $key => $plan) {
            if (isset($plan['apple_product_monthly']) && $plan['apple_product_monthly'] === $productId) {
                return $key;
            }
            if (isset($plan['apple_product_yearly']) && $plan['apple_product_yearly'] === $productId) {
                return $key;
            }
        }
        
        return 'pro';
    }

    private function calculateEndDate(string $productId): \DateTime
    {
        $isYearly = str_contains($productId, 'yearly');
        
        return $isYearly
            ? now()->addYear()
            : now()->addMonth();
    }
}

