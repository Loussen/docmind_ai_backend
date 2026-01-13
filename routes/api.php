<?php

use App\Http\Controllers\Api\AuthController;
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
        'version' => '1.0.0',
    ]);
});

// Authentication Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/apple', [AuthController::class, 'appleAuth']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // User Profile
    Route::prefix('user')->group(function () {
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });

    // User
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
        Route::post('/cancel', [SubscriptionController::class, 'cancel']);
    });

    // History (combined documents and summaries)
    Route::get('/history', function (\Illuminate\Http\Request $request) {
        $documents = $request->user()
            ->documents()
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

