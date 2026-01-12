<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Get user settings
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'settings' => [
                'notifications_enabled' => $user->notifications_enabled ?? true,
                'dark_mode_enabled' => $user->dark_mode_enabled ?? false,
                'language' => $user->language ?? 'en',
            ],
        ]);
    }

    /**
     * Update user settings
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notifications_enabled' => 'sometimes|boolean',
            'dark_mode_enabled' => 'sometimes|boolean',
            'language' => 'sometimes|string|max:10',
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => [
                'notifications_enabled' => $user->notifications_enabled,
                'dark_mode_enabled' => $user->dark_mode_enabled,
                'language' => $user->language,
            ],
        ]);
    }
}
