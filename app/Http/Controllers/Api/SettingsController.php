<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');

        return response()->json([
            'settings' => [
                'notifications_enabled' => true,
                'dark_mode_enabled' => false,
                'language' => 'en',
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notifications_enabled' => 'sometimes|boolean',
            'dark_mode_enabled' => 'sometimes|boolean',
            'language' => 'sometimes|string|max:10',
        ]);

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => [
                'notifications_enabled' => $validated['notifications_enabled'] ?? true,
                'dark_mode_enabled' => $validated['dark_mode_enabled'] ?? false,
                'language' => $validated['language'] ?? 'en',
            ],
        ]);
    }
}
