<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AppleReceiptVerificationService
{
    /**
     * Verify an Apple In-App Purchase receipt
     */
    public function verify(string $receiptData, string $productId): array
    {
        $sharedSecret = config('docmind.apple.shared_secret');
        $isSandbox = config('docmind.apple.sandbox', true);

        // Try production first, then sandbox
        $urls = $isSandbox
            ? [config('docmind.apple.verify_url_sandbox')]
            : [
                config('docmind.apple.verify_url_production'),
                config('docmind.apple.verify_url_sandbox'),
            ];

        $lastError = null;

        foreach ($urls as $url) {
            try {
                $response = Http::timeout(30)->post($url, [
                    'receipt-data' => $receiptData,
                    'password' => $sharedSecret,
                    'exclude-old-transactions' => true,
                ]);

                $data = $response->json();
                $status = $data['status'] ?? -1;

                // Status 21007 means this is a sandbox receipt on production
                // Try sandbox URL instead
                if ($status === 21007) {
                    continue;
                }

                if ($status === 0) {
                    return $this->parseValidReceipt($data, $productId);
                }

                $lastError = $this->getStatusMessage($status);

            } catch (\Exception $e) {
                $lastError = $e->getMessage();
            }
        }

        return [
            'valid' => false,
            'message' => $lastError ?? 'Receipt verification failed',
        ];
    }

    /**
     * Parse a valid receipt response
     */
    private function parseValidReceipt(array $data, string $productId): array
    {
        $latestReceipt = $data['latest_receipt_info'] ?? [];
        $pendingRenewal = $data['pending_renewal_info'] ?? [];

        // Find the matching subscription
        $subscription = collect($latestReceipt)
            ->filter(fn($item) => ($item['product_id'] ?? '') === $productId)
            ->sortByDesc('purchase_date_ms')
            ->first();

        if (!$subscription) {
            // Try to find any active subscription
            $subscription = collect($latestReceipt)
                ->sortByDesc('purchase_date_ms')
                ->first();
        }

        if (!$subscription) {
            return [
                'valid' => false,
                'message' => 'No matching subscription found in receipt',
            ];
        }

        $expiresDate = isset($subscription['expires_date_ms'])
            ? (int) ($subscription['expires_date_ms'] / 1000)
            : null;

        $isExpired = $expiresDate && $expiresDate < time();

        // Check auto-renewal status
        $renewalInfo = collect($pendingRenewal)
            ->filter(fn($item) => ($item['original_transaction_id'] ?? '') === ($subscription['original_transaction_id'] ?? ''))
            ->first();

        $autoRenewing = ($renewalInfo['auto_renew_status'] ?? '0') === '1';

        return [
            'valid' => !$isExpired,
            'subscription' => [
                'product_id' => $subscription['product_id'] ?? $productId,
                'transaction_id' => $subscription['transaction_id'] ?? null,
                'original_transaction_id' => $subscription['original_transaction_id'] ?? null,
                'purchase_date' => $subscription['purchase_date'] ?? null,
                'expires_date' => $subscription['expires_date'] ?? null,
            ],
            'original_transaction_id' => $subscription['original_transaction_id'] ?? null,
            'auto_renewing' => $autoRenewing,
            'is_expired' => $isExpired,
            'message' => $isExpired ? 'Subscription has expired' : 'Valid subscription',
        ];
    }

    /**
     * Get human-readable status message
     */
    private function getStatusMessage(int $status): string
    {
        return match ($status) {
            21000 => 'The App Store could not read the JSON object you provided.',
            21002 => 'The data in the receipt-data property was malformed or missing.',
            21003 => 'The receipt could not be authenticated.',
            21004 => 'The shared secret you provided does not match the shared secret on file.',
            21005 => 'The receipt server is not currently available.',
            21006 => 'This receipt is valid but the subscription has expired.',
            21007 => 'This receipt is from the test environment.',
            21008 => 'This receipt is from the production environment.',
            21009 => 'Internal data access error.',
            21010 => 'The user account cannot be found or has been deleted.',
            default => "Unknown error status: {$status}",
        };
    }

    /**
     * Check if a subscription is still active based on receipt
     */
    public function isSubscriptionActive(string $receiptData): bool
    {
        $result = $this->verify($receiptData, '');
        return $result['valid'] && !($result['is_expired'] ?? true);
    }
}

