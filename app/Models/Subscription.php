<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'device_id',
        'plan',
        'status',
        'start_date',
        'end_date',
        'apple_transaction_id',
        'apple_original_transaction_id',
        'apple_product_id',
        'is_auto_renewing',
        'receipt_data',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_auto_renewing' => 'boolean',
            'receipt_data' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               (!$this->end_date || $this->end_date->isFuture());
    }

    public function isPro(): bool
    {
        return in_array($this->plan, ['pro', 'pro_plus']) && $this->isActive();
    }

    public function isProPlus(): bool
    {
        return $this->plan === 'pro_plus' && $this->isActive();
    }

    public function isFree(): bool
    {
        return $this->plan === 'free';
    }

    public function getPagesPerDoc(): int
    {
        $planConfig = config("docmind.plans.{$this->plan}");
        return $planConfig['pages_per_doc'] ?? 5;
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'is_auto_renewing' => false,
        ]);
    }

    public function expire(): void
    {
        $this->update([
            'status' => 'expired',
        ]);
    }
}

