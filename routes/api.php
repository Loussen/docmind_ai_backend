<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\SummaryController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '2.0.0',
    ]);
});

// Device registration (public - no middleware)
Route::post('/device/register', [DeviceController::class, 'register']);

// Protected Routes (device.auth middleware)
Route::middleware('device.auth')->group(function () {

    // Device
    Route::get('/device/sync', [DeviceController::class, 'sync']);

    // User/Device usage & settings
    Route::prefix('user')->group(function () {
        Route::get('/usage', [SubscriptionController::class, 'usage']);
        Route::get('/settings', [SettingsController::class, 'show']);
        Route::put('/settings', [SettingsController::class, 'update']);
    });

    // Documents
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::post('/upload', [DocumentController::class, 'store']);
        Route::get('/{id}', [DocumentController::class, 'show']);
        Route::delete('/{id}', [DocumentController::class, 'destroy']);
        Route::post('/{id}/process', [DocumentController::class, 'process']);
        Route::get('/{id}/preview', [DocumentController::class, 'preview']);
        Route::post('/{documentId}/summarize', [SummaryController::class, 'generate']);
        Route::get('/{documentId}/summary', [SummaryController::class, 'byDocument']);
    });

    // Summaries
    Route::prefix('summaries')->group(function () {
        Route::get('/', [SummaryController::class, 'index']);
        Route::get('/{id}', [SummaryController::class, 'show']);
    });

    // Subscription
    Route::prefix('subscription')->group(function () {
        Route::get('/', [SubscriptionController::class, 'show']);
        Route::get('/plans', [SubscriptionController::class, 'plans']);
        Route::post('/verify', [SubscriptionController::class, 'verify']);
        Route::post('/restore', [SubscriptionController::class, 'restore']);
        Route::post('/cancel', [SubscriptionController::class, 'cancel']);
    });

    // History
    Route::get('/history', function (\Illuminate\Http\Request $request) {
        $device = $request->attributes->get('device');

        $documents = $device->documents()
            ->with('summary:id,document_id,title,overview')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'data' => $documents->items(),
            'meta' => [
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
                'per_page' => $documents->perPage(),
                'total' => $documents->total(),
            ],
        ]);
    });
});
