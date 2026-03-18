<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'platform',
        'model',
        'os_version',
        'notifications_enabled',
        'dark_mode_enabled',
        'ui_language',
        'output_language',
    ];

    protected function casts(): array
    {
        return [
            'notifications_enabled' => 'boolean',
            'dark_mode_enabled' => 'boolean',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'device_id', 'device_id');
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(Summary::class, 'device_id', 'device_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'device_id', 'device_id')->latestOfMany();
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(UsageLog::class, 'device_id', 'device_id');
    }

    public function isPremium(): bool
    {
        $subscription = $this->subscription;
        if (!$subscription) return false;
        return $subscription->isActive() &&
               in_array($subscription->plan, ['pro', 'pro_plus']);
    }

    public function isProPlus(): bool
    {
        $subscription = $this->subscription;
        if (!$subscription) return false;
        return $subscription->isActive() &&
               $subscription->plan === 'pro_plus';
    }

    public function getTotalUsageCount(): int
    {
        return $this->usageLogs()
            ->where('action', 'upload')
            ->count();
    }

    public function getDailyUsageCount(): int
    {
        return $this->usageLogs()
            ->where('action', 'upload')
            ->whereDate('usage_date', today())
            ->count();
    }

    public function canUploadDocument(): bool
    {
        if ($this->isPremium()) return true;
        $dailyLimit = config('docmind.plans.free.docs_per_day', 2);
        return $this->getDailyUsageCount() < $dailyLimit;
    }

    public function getSubscriptionPlan(): string
    {
        return $this->subscription?->plan ?? 'free';
    }
}
