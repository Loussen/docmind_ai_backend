<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\UsageLog;
use App\Services\DocumentParserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function __construct(
        private DocumentParserService $documentParser
    ) {}

    public function index(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');

        $query = $device->documents()->with('summary:id,document_id,title');

        $type = $request->get('type');
        if ($type && $type !== 'all') {
            if ($type === 'image') {
                $query->whereIn('type', ['image', 'jpg', 'jpeg', 'png']);
            } else {
                $query->where('type', $type);
            }
        }

        $status = $request->get('status');
        if ($status) {
            $query->where('status', $status);
        }

        $search = $request->get('search');
        if ($search) {
            $query->where('original_name', 'like', "%{$search}%");
        }

        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $documents = $query
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $documents->items(),
            'meta' => [
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
                'per_page' => $documents->perPage(),
                'total' => $documents->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:10240',
        ]);

        $device = $request->attributes->get('device');

        if (!$device->canUploadDocument()) {
            return response()->json([
                'error' => 'Free limit reached',
                'message' => 'You have used your 2 free documents. Upgrade to Pro for unlimited access.',
            ], 429);
        }

        set_time_limit(120);

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();

        $type = match ($extension) {
            'pdf' => 'pdf',
            'docx' => 'docx',
            'doc' => 'doc',
            'jpg', 'jpeg', 'png' => 'image',
            default => 'pdf',
        };

        $fileName = Str::uuid() . '.' . $extension;
        $filePath = $file->storeAs('documents/' . $device->device_id, $fileName, 'local');

        $document = Document::create([
            'device_id' => $device->device_id,
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'type' => $type,
            'file_size' => $fileSize,
            'status' => 'processing',
        ]);

        UsageLog::logUploadByDevice($device, $document);

        try {
            $result = $this->documentParser->parse($document);

            $document->update([
                'extracted_text' => $result['text'],
                'page_count' => $result['page_count'],
                'status' => 'completed',
                'processed_at' => now(),
            ]);
        } catch (\Exception $e) {
            $document->markAsFailed($e->getMessage());
        }

        $document->load('summary:id,document_id,title');

        return response()->json([
            'document' => $this->formatDocument($document),
            'message' => 'Document uploaded successfully',
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $device = $request->attributes->get('device');

        $document = $device->documents()
            ->with('summary')
            ->findOrFail($id);

        return response()->json([
            'document' => $this->formatDocument($document),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $device = $request->attributes->get('device');

        $document = $device->documents()->findOrFail($id);

        if ($document->file_path) {
            Storage::disk('local')->delete($document->file_path);
        }

        if ($document->thumbnail_path) {
            Storage::disk('local')->delete($document->thumbnail_path);
        }

        $document->delete();

        return response()->json([
            'message' => 'Document deleted successfully',
        ]);
    }

    public function process(Request $request, string $id): JsonResponse
    {
        $device = $request->attributes->get('device');

        $document = $device->documents()->findOrFail($id);

        if ($document->status === 'processing') {
            return response()->json([
                'error' => 'Document is already being processed',
            ], 400);
        }

        $document->markAsProcessing();

        try {
            $result = $this->documentParser->parse($document);

            $document->update([
                'extracted_text' => $result['text'],
                'page_count' => $result['page_count'],
                'status' => 'completed',
                'processed_at' => now(),
            ]);
        } catch (\Exception $e) {
            $document->markAsFailed($e->getMessage());

            return response()->json([
                'error' => 'Processing failed',
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'document' => $this->formatDocument($document),
        ]);
    }

    public function preview(Request $request, string $id)
    {
        $device = $request->attributes->get('device');
        $document = $device->documents()->findOrFail($id);

        $filePath = Storage::disk('local')->path($document->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        if (in_array($document->type, ['image', 'jpg', 'jpeg', 'png'])) {
            return response()->file($filePath, [
                'Content-Type' => $document->mime_type,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        if ($document->type === 'pdf' && extension_loaded('imagick')) {
            $cacheDir = storage_path('app/previews/' . $device->device_id);
            $cachePath = $cacheDir . '/' . $document->id . '.jpg';

            if (file_exists($cachePath)) {
                return response()->file($cachePath, [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }

            try {
                if (!is_dir($cacheDir)) {
                    mkdir($cacheDir, 0755, true);
                }

                $imagick = new \Imagick();
                $imagick->setResolution(150, 150);
                $imagick->readImage($filePath . '[0]');
                $imagick->setImageFormat('jpg');
                $imagick->setImageCompressionQuality(80);
                $imagick->thumbnailImage(400, 0);
                $imagick->writeImage($cachePath);
                $imagick->destroy();

                return response()->file($cachePath, [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            } catch (\Exception $e) {
                \Log::warning('PDF preview generation failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'type' => $document->type,
            'preview' => null,
        ]);
    }

    /**
     * Serve the original document file (PDF, DOC, image) for in-app viewing.
     */
    public function file(Request $request, string $id)
    {
        $device = $request->attributes->get('device');
        $document = $device->documents()->findOrFail($id);

        $filePath = Storage::disk('local')->path($document->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $mimeType = $document->mime_type ?: 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($document->original_name) . '"',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    private function formatDocument(Document $document): array
    {
        return [
            'id' => $document->id,
            'device_id' => $document->device_id,
            'file_name' => $document->file_name,
            'original_name' => $document->original_name,
            'type' => $document->type,
            'file_size' => $document->file_size,
            'page_count' => $document->page_count,
            'status' => $document->status,
            'file_path' => $document->getFileUrl(),
            'extracted_text' => $document->extracted_text,
            'thumbnail_url' => $document->getThumbnailUrl(),
            'preview_url' => url("/api/documents/{$document->id}/preview"),
            'file_url' => url("/api/documents/{$document->id}/file"),
            'summary_id' => $document->summary?->id,
            'error_message' => $document->error_message,
            'created_at' => $document->created_at->toISOString(),
            'updated_at' => $document->updated_at->toISOString(),
            'processed_at' => $document->processed_at?->toISOString(),
        ];
    }
}
