<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DocumentParserService;
use App\Services\OpenAIService;
use App\Services\SummaryFormatterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    private const MAX_GUEST_SUMMARIES = 2;
    private const RATE_LIMIT_MINUTES = 1440; // 24 hours

    public function __construct(
        private DocumentParserService $documentParser,
        private OpenAIService $openAI,
        private SummaryFormatterService $formatter
    ) {}

    /**
     * Upload and summarize document for guest users
     */
    public function summarize(Request $request): JsonResponse
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'document' => 'required|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:10240',
            'device_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $deviceId = $request->input('device_id');
        $cacheKey = 'guest_usage:' . $deviceId;

        // Check usage limit
        $usageCount = Cache::get($cacheKey, 0);
        
        if ($usageCount >= self::MAX_GUEST_SUMMARIES) {
            return response()->json([
                'error' => 'Trial limit reached',
                'message' => 'You have used all your free trials. Create an account to continue using DocMind AI.',
                'limit_reached' => true,
                'used' => $usageCount,
                'limit' => self::MAX_GUEST_SUMMARIES,
            ], 429);
        }

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();

        // Check file size (limit to 5MB for guests)
        if ($fileSize > 5 * 1024 * 1024) {
            return response()->json([
                'error' => 'File too large',
                'message' => 'Guest users can upload files up to 5MB. Create an account for larger files.',
            ], 413);
        }

        // Determine document type
        $type = match ($extension) {
            'pdf' => 'pdf',
            'docx' => 'docx',
            'doc' => 'doc',
            'jpg', 'jpeg', 'png' => 'image',
            default => 'pdf',
        };

        // Generate unique file name and store temporarily
        $fileName = 'guest_' . Str::uuid() . '.' . $extension;
        $filePath = $file->storeAs('guest_documents', $fileName, 'local');

        try {
            // Create temporary document object for parsing
            $tempDocument = new \stdClass();
            $tempDocument->file_path = $filePath;
            $tempDocument->type = $type;
            $tempDocument->mime_type = $file->getMimeType();

            // Parse document
            $parseResult = $this->documentParser->parseFile(
                Storage::disk('local')->path($filePath),
                $type,
                $file->getMimeType()
            );

            $extractedText = $parseResult['text'];
            $pageCount = $parseResult['page_count'];

            // Check page limit for guests (max 5 pages)
            if ($pageCount > 5) {
                Storage::disk('local')->delete($filePath);
                return response()->json([
                    'error' => 'Page limit exceeded',
                    'message' => 'Guest users can summarize documents up to 5 pages. Create an account for unlimited pages.',
                    'page_count' => $pageCount,
                    'limit' => 5,
                ], 403);
            }

            if (empty($extractedText)) {
                Storage::disk('local')->delete($filePath);
                return response()->json([
                    'error' => 'Could not extract text',
                    'message' => 'Unable to extract text from this document. Please try a different file.',
                ], 400);
            }

            // Generate summary using OpenAI
            $summaryData = $this->openAI->generateSummary(
                text: $extractedText,
                type: 'standard',
                language: 'en'
            );

            // Format the summary
            $formattedSummary = $this->formatter->format($summaryData);

            // Increment usage count
            $newUsageCount = $usageCount + 1;
            Cache::put($cacheKey, $newUsageCount, now()->addMinutes(self::RATE_LIMIT_MINUTES));

            // Clean up temporary file
            Storage::disk('local')->delete($filePath);

            return response()->json([
                'success' => true,
                'document' => [
                    'original_name' => $originalName,
                    'type' => $type,
                    'file_size' => $fileSize,
                    'page_count' => $pageCount,
                ],
                'summary' => [
                    'title' => $formattedSummary['title'],
                    'overview' => $formattedSummary['overview'],
                    'key_points' => $formattedSummary['key_points'] ?? [],
                    'action_items' => $formattedSummary['action_items'] ?? [],
                    'keywords' => $formattedSummary['keywords'] ?? [],
                ],
                'usage' => [
                    'used' => $newUsageCount,
                    'limit' => self::MAX_GUEST_SUMMARIES,
                    'remaining' => self::MAX_GUEST_SUMMARIES - $newUsageCount,
                ],
                'message' => 'Summary generated successfully! ' . 
                    ($newUsageCount >= self::MAX_GUEST_SUMMARIES 
                        ? 'This was your last free trial. Create an account to continue.'
                        : 'You have ' . (self::MAX_GUEST_SUMMARIES - $newUsageCount) . ' free trial(s) remaining.'),
            ]);

        } catch (\Exception $e) {
            // Clean up on error
            Storage::disk('local')->delete($filePath);
            
            return response()->json([
                'error' => 'Processing failed',
                'message' => 'An error occurred while processing your document. Please try again.',
            ], 500);
        }
    }

    /**
     * Check remaining guest usage
     */
    public function checkUsage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $deviceId = $request->input('device_id');
        $cacheKey = 'guest_usage:' . $deviceId;
        $usageCount = Cache::get($cacheKey, 0);

        return response()->json([
            'used' => $usageCount,
            'limit' => self::MAX_GUEST_SUMMARIES,
            'remaining' => max(0, self::MAX_GUEST_SUMMARIES - $usageCount),
            'limit_reached' => $usageCount >= self::MAX_GUEST_SUMMARIES,
        ]);
    }
}
