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
    ];

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

    public function canUploadDocument(): bool
    {
        if ($this->isPremium()) return true;
        return $this->getTotalUsageCount() < 2;
    }

    public function getSubscriptionPlan(): string
    {
        return $this->subscription?->plan ?? 'free';
    }
}
