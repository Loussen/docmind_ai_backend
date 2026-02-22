<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Summary;
use App\Models\UsageLog;
use App\Services\OpenAIService;
use App\Services\SummaryFormatterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function __construct(
        private OpenAIService $openAI,
        private SummaryFormatterService $formatter
    ) {}

    public function index(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');

        $summaries = $device->summaries()
            ->with('document:id,original_name,type')
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $summaries->map(fn($s) => $this->formatSummary($s)),
            'meta' => [
                'current_page' => $summaries->currentPage(),
                'last_page' => $summaries->lastPage(),
                'per_page' => $summaries->perPage(),
                'total' => $summaries->total(),
            ],
        ]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $device = $request->attributes->get('device');

        $summary = $device->summaries()
            ->with('document')
            ->findOrFail($id);

        return response()->json([
            'summary' => $this->formatSummary($summary),
        ]);
    }

    public function generate(Request $request, string $documentId): JsonResponse
    {
        $device = $request->attributes->get('device');

        $document = $device->documents()->findOrFail($documentId);

        if (empty($document->extracted_text)) {
            return response()->json([
                'error' => 'Document not processed',
                'message' => 'Please wait for document processing to complete.',
            ], 400);
        }

        $subscription = $device->subscription;
        $pagesLimit = config('docmind.plans.free.pages_per_doc', 5);

        if (!$device->isPremium() && $document->page_count > $pagesLimit) {
            return response()->json([
                'error' => 'Page limit exceeded',
                'message' => "Free plan allows up to {$pagesLimit} pages. Upgrade to Pro for unlimited pages.",
            ], 403);
        }

        if ($document->summary) {
            return response()->json([
                'summary' => $this->formatSummary($document->summary),
                'message' => 'Summary already exists for this document.',
            ]);
        }

        set_time_limit(120);
        $startTime = microtime(true);

        try {
            $summaryData = $this->openAI->generateSummary(
                text: $document->extracted_text,
                type: $request->summary_type ?? 'standard',
                language: $request->language ?? 'en'
            );

            $formattedSummary = $this->formatter->format($summaryData);

            $processingTime = (int) ((microtime(true) - $startTime) * 1000);

            $summary = Summary::create([
                'document_id' => $document->id,
                'device_id' => $device->device_id,
                'title' => $formattedSummary['title'],
                'overview' => $formattedSummary['overview'],
                'key_points' => $formattedSummary['key_points'],
                'action_items' => $formattedSummary['action_items'],
                'keywords' => $formattedSummary['keywords'],
                'important_facts' => $formattedSummary['important_facts'] ?? null,
                'obligations' => $formattedSummary['obligations'] ?? null,
                'risks' => $formattedSummary['risks'] ?? null,
                'findings' => $formattedSummary['findings'] ?? null,
                'word_count' => str_word_count($formattedSummary['overview']),
                'processing_time_ms' => $processingTime,
                'language' => $request->language ?? 'en',
                'summary_type' => $request->summary_type ?? 'standard',
            ]);

            UsageLog::logSummarizeByDevice($device, $document, $summary);

            return response()->json([
                'summary' => $this->formatSummary($summary),
                'message' => 'Summary generated successfully',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Summary generation failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function byDocument(Request $request, string $documentId): JsonResponse
    {
        $device = $request->attributes->get('device');

        $document = $device->documents()
            ->with('summary')
            ->findOrFail($documentId);

        if (!$document->summary) {
            return response()->json([
                'error' => 'Summary not found',
                'message' => 'No summary exists for this document.',
            ], 404);
        }

        return response()->json([
            'summary' => $this->formatSummary($document->summary),
        ]);
    }

    private function formatSummary(Summary $summary): array
    {
        return [
            'id' => $summary->id,
            'document_id' => $summary->document_id,
            'device_id' => $summary->device_id,
            'title' => $summary->title,
            'overview' => $summary->overview,
            'key_points' => $summary->key_points ?? [],
            'action_items' => $summary->action_items ?? [],
            'keywords' => $summary->keywords ?? [],
            'important_facts' => $summary->important_facts,
            'obligations' => $summary->obligations,
            'risks' => $summary->risks,
            'findings' => $summary->findings,
            'word_count' => $summary->word_count,
            'processing_time_ms' => $summary->processing_time_ms,
            'created_at' => $summary->created_at->toISOString(),
            'updated_at' => $summary->updated_at->toISOString(),
        ];
    }
}
