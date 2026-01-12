<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\UploadDocumentRequest;
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
        $query = $request->user()
            ->documents()
            ->with('summary:id,document_id,title');

        // Filter by type
        $type = $request->get('type');
        if ($type && $type !== 'all') {
            if ($type === 'image') {
                $query->whereIn('type', ['image', 'jpg', 'jpeg', 'png']);
            } else {
                $query->where('type', $type);
            }
        }

        // Filter by status
        $status = $request->get('status');
        if ($status) {
            $query->where('status', $status);
        }

        // Search by name
        $search = $request->get('search');
        if ($search) {
            $query->where('original_name', 'like', "%{$search}%");
        }

        // Date filter
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

    public function store(UploadDocumentRequest $request): JsonResponse
    {
        $user = $request->user();

        // TODO: Re-enable usage limits after testing
        // Check usage limits for free users
         if (!$user->canUploadDocument()) {
             return response()->json([
                 'error' => 'Daily upload limit reached',
                 'message' => 'You have reached your daily document limit. Upgrade to Pro for unlimited access.',
             ], 429);
         }

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();

        // Determine document type
        $type = match ($extension) {
            'pdf' => 'pdf',
            'docx' => 'docx',
            'doc' => 'doc',
            'jpg', 'jpeg', 'png' => 'image',
            default => 'pdf',
        };

        // Generate unique file name
        $fileName = Str::uuid() . '.' . $extension;
        $filePath = $file->storeAs('documents/' . $user->id, $fileName, 'local');

        // Create document record
        $document = Document::create([
            'user_id' => $user->id,
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'type' => $type,
            'file_size' => $fileSize,
            'status' => 'processing',
        ]);

        // Log usage
        UsageLog::logUpload($user, $document);

        // Process document in background (for now, we'll do it synchronously)
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
        $document = $request->user()
            ->documents()
            ->with('summary')
            ->findOrFail($id);

        return response()->json([
            'document' => $this->formatDocument($document),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $document = $request->user()
            ->documents()
            ->findOrFail($id);

        // Delete file from storage
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
        $document = $request->user()
            ->documents()
            ->findOrFail($id);

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

    private function formatDocument(Document $document): array
    {
        return [
            'id' => $document->id,
            'user_id' => $document->user_id,
            'file_name' => $document->file_name,
            'original_name' => $document->original_name,
            'type' => $document->type,
            'file_size' => $document->file_size,
            'page_count' => $document->page_count,
            'status' => $document->status,
            'file_path' => $document->getFileUrl(),
            'extracted_text' => $document->extracted_text,
            'thumbnail_url' => $document->getThumbnailUrl(),
            'summary_id' => $document->summary?->id,
            'error_message' => $document->error_message,
            'created_at' => $document->created_at->toISOString(),
            'updated_at' => $document->updated_at->toISOString(),
            'processed_at' => $document->processed_at?->toISOString(),
        ];
    }
}

