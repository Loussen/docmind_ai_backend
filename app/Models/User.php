<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'apple_id',
        'avatar_url',
        'notifications_enabled',
        'dark_mode_enabled',
        'language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notifications_enabled' => 'boolean',
            'dark_mode_enabled' => 'boolean',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(Summary::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(UsageLog::class);
    }

    public function isPremium(): bool
    {
        $subscription = $this->subscription;
        
        if (!$subscription) {
            return false;
        }
        
        return $subscription->status === 'active' && 
               in_array($subscription->plan, ['pro', 'pro_plus']);
    }

    public function isProPlus(): bool
    {
        $subscription = $this->subscription;
        
        if (!$subscription) {
            return false;
        }
        
        return $subscription->status === 'active' && 
               $subscription->plan === 'pro_plus';
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
        if ($this->isPremium()) {
            return true;
        }
        
        $dailyLimit = config('docmind.plans.free.docs_per_day', 3);
        return $this->getDailyUsageCount() < $dailyLimit;
    }

    public function getSubscriptionPlan(): string
    {
        return $this->subscription?->plan ?? 'free';
    }
}

