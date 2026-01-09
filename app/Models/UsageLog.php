<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'summary_id',
        'action',
        'usage_date',
    ];

    protected function casts(): array
    {
        return [
            'usage_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function summary(): BelongsTo
    {
        return $this->belongsTo(Summary::class);
    }

    public static function logUpload(User $user, Document $document): self
    {
        return self::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'action' => 'upload',
            'usage_date' => today(),
        ]);
    }

    public static function logSummarize(User $user, Document $document, Summary $summary): self
    {
        return self::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'summary_id' => $summary->id,
            'action' => 'summarize',
            'usage_date' => today(),
        ]);
    }

    public static function getDailyCount(User $user, string $action = 'upload'): int
    {
        return self::where('user_id', $user->id)
            ->where('action', $action)
            ->whereDate('usage_date', today())
            ->count();
    }
}

