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
                'notifications_enabled' => (bool) ($device->notifications_enabled ?? true),
                'dark_mode_enabled' => (bool) ($device->dark_mode_enabled ?? false),
                // App UI language (fallback to previous key "language" if clients still send it)
                'ui_language' => $device->ui_language ?? ($device->language ?? 'en'),
                // AI output language for summaries/translations
                'output_language' => $device->output_language ?? 'en',
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notifications_enabled' => 'sometimes|boolean',
            'dark_mode_enabled' => 'sometimes|boolean',
            // Back-compat: accept "language" as ui_language
            'language' => 'sometimes|string|max:10',
            'ui_language' => 'sometimes|string|max:10',
            'output_language' => 'sometimes|string|max:10',
        ]);

        $device = $request->attributes->get('device');

        $uiLanguage = $validated['ui_language'] ?? $validated['language'] ?? null;
        $outputLanguage = $validated['output_language'] ?? null;

        if ($device) {
            $update = [];
            if (array_key_exists('notifications_enabled', $validated)) {
                $update['notifications_enabled'] = $validated['notifications_enabled'];
            }
            if (array_key_exists('dark_mode_enabled', $validated)) {
                $update['dark_mode_enabled'] = $validated['dark_mode_enabled'];
            }
            if ($uiLanguage !== null) {
                $update['ui_language'] = $uiLanguage;
            }
            if ($outputLanguage !== null) {
                $update['output_language'] = $outputLanguage;
            }
            if (!empty($update)) {
                $device->update($update);
                $device->refresh();
            }
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => [
                'notifications_enabled' => (bool) ($device->notifications_enabled ?? ($validated['notifications_enabled'] ?? true)),
                'dark_mode_enabled' => (bool) ($device->dark_mode_enabled ?? ($validated['dark_mode_enabled'] ?? false)),
                'ui_language' => $device->ui_language ?? ($uiLanguage ?? 'en'),
                'output_language' => $device->output_language ?? ($outputLanguage ?? 'en'),
            ],
        ]);
    }
}
