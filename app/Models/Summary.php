<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Summary extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'document_id',
        'user_id',
        'title',
        'overview',
        'key_points',
        'action_items',
        'keywords',
        'important_facts',
        'obligations',
        'risks',
        'findings',
        'word_count',
        'processing_time_ms',
        'language',
        'summary_type',
    ];

    protected function casts(): array
    {
        return [
            'key_points' => 'array',
            'action_items' => 'array',
            'keywords' => 'array',
            'word_count' => 'integer',
            'processing_time_ms' => 'integer',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProcessingTimeFormatted(): string
    {
        $seconds = $this->processing_time_ms / 1000;
        return number_format($seconds, 1) . 's';
    }

    public function hasActionItems(): bool
    {
        return !empty($this->action_items);
    }

    public function hasObligations(): bool
    {
        return !empty($this->obligations);
    }

    public function hasRisks(): bool
    {
        return !empty($this->risks);
    }

    public function hasFindings(): bool
    {
        return !empty($this->findings);
    }
}

