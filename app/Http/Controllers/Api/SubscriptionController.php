<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $device = $request->attributes->get('device');
        $subscription = $device->subscription;

        if (!$subscription) {
            $subscription = Subscription::create([
                'device_id' => $device->device_id,
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

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|string',
            'transaction_id' => 'required|string',
            'receipt_data' => 'required|string',
        ]);

        $device = $request->attributes->get('device');

        try {
            \Log::info('Purchase verification started', [
                'device_id' => $device->device_id,
                'product_id' => $request->product_id,
                'transaction_id' => $request->transaction_id,
            ]);

            $verificationResult = $this->appleVerification->verify(
                receiptData: $request->receipt_data,
                productId: $request->product_id
            );

            \Log::info('Verification result', $verificationResult);

            if (!$verificationResult['valid']) {
                \Log::warning('Purchase verification failed', [
                    'device_id' => $device->device_id,
                    'message' => $verificationResult['message'] ?? 'Unknown error',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $verificationResult['message'] ?? 'Receipt verification failed',
                ], 400);
            }

            $actualProductId = $verificationResult['subscription']['product_id'] ?? $request->product_id;
            $plan = $this->getPlanFromProductId($actualProductId);

            $isSandbox = config('docmind.apple.sandbox', false);
            $receiptExpiresDate = $verificationResult['subscription']['expires_date'] ?? null;

            if ($receiptExpiresDate && !$isSandbox) {
                $endDate = new \DateTime($receiptExpiresDate);
            } else {
                // In sandbox, Apple uses very short durations (yearly=1h, monthly=5min).
                // Always calculate real end date based on product ID.
                $endDate = $this->calculateEndDate($actualProductId);
            }

            $subscription = $device->subscription;

            $subscriptionData = [
                'device_id' => $device->device_id,
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
            } else {
                $subscription = Subscription::create($subscriptionData);
            }

            return response()->json([
                'success' => true,
                'subscription' => $this->formatSubscription($subscription),
                'message' => 'Subscription activated successfully',
            ]);

        } catch (\Exception $e) {
            \Log::error('Purchase verification error', [
                'device_id' => $device->device_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function restore(Request $request): JsonResponse
    {
        $request->validate([
            'receipt_data' => 'required|string',
        ]);

        $device = $request->attributes->get('device');

        try {
            $verificationResult = $this->appleVerification->verify(
                receiptData: $request->receipt_data,
                productId: ''
            );

            if (!$verificationResult['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription found to restore.',
                ], 400);
            }

            $actualProductId = $verificationResult['subscription']['product_id'] ?? '';
            if (empty($actualProductId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription found.',
                ], 400);
            }

            $plan = $this->getPlanFromProductId($actualProductId);

            $isSandbox = config('docmind.apple.sandbox', false);
            $receiptExpiresDate = $verificationResult['subscription']['expires_date'] ?? null;
            if ($receiptExpiresDate && !$isSandbox) {
                $endDate = new \DateTime($receiptExpiresDate);
            } else {
                $endDate = $this->calculateEndDate($actualProductId);
            }

            $subscription = $device->subscription;

            $subscriptionData = [
                'device_id' => $device->device_id,
                'plan' => $plan,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => $endDate,
                'apple_original_transaction_id' => $verificationResult['original_transaction_id'] ?? null,
                'apple_product_id' => $actualProductId,
                'is_auto_renewing' => $verificationResult['auto_renewing'] ?? false,
                'receipt_data' => ['receipt' => $request->receipt_data],
            ];

            if ($subscription) {
                $subscription->update($subscriptionData);
            } else {
                $subscription = Subscription::create($subscriptionData);
            }

            return response()->json([
                'success' => true,
                'subscription' => $this->formatSubscription($subscription),
                'message' => 'Subscription restored successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');
        $subscription = $device->subscription;

        if (!$subscription || $subscription->plan === 'free') {
            return response()->json([
                'error' => 'No active subscription to cancel',
            ], 400);
        }

        $subscription->cancel();

        return response()->json([
            'message' => 'Subscription cancelled.',
            'subscription' => $this->formatSubscription($subscription),
        ]);
    }

    public function usage(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');

        $totalUsed = $device->getTotalUsageCount();
        $freeLimit = 2;
        $totalDocs = $device->documents()->count();
        $totalSummaries = $device->summaries()->count();

        return response()->json([
            'usage' => [
                'total_used' => $totalUsed,
                'free_limit' => $freeLimit,
                'is_premium' => $device->isPremium(),
                'total_docs_processed' => $totalDocs,
                'total_summaries_generated' => $totalSummaries,
            ],
        ]);
    }

    private function formatSubscription(Subscription $subscription): array
    {
        $billingPeriod = 'monthly';
        if ($subscription->apple_product_id && str_contains($subscription->apple_product_id, 'yearly')) {
            $billingPeriod = 'yearly';
        }

        return [
            'id' => $subscription->id,
            'device_id' => $subscription->device_id,
            'plan' => $subscription->plan,
            'status' => $subscription->status,
            'billing_period' => $billingPeriod,
            'start_date' => $subscription->start_date?->toISOString(),
            'end_date' => $subscription->end_date?->toISOString(),
            'apple_transaction_id' => $subscription->apple_transaction_id,
            'apple_original_transaction_id' => $subscription->apple_original_transaction_id,
            'apple_product_id' => $subscription->apple_product_id,
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
        return $isYearly ? now()->addYear() : now()->addMonth();
    }
}
