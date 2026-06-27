<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|string|max:255',
            'platform' => 'sometimes|string|in:ios,android',
            'model' => 'sometimes|string|max:255',
            'os_version' => 'sometimes|string|max:50',
        ]);

        $device = Device::where('device_id', $validated['device_id'])->first();

        if ($device) {
            $device->update(array_filter([
                'platform' => $validated['platform'] ?? null,
                'model' => $validated['model'] ?? null,
                'os_version' => $validated['os_version'] ?? null,
            ]));
        } else {
            $device = Device::create([
                'device_id' => $validated['device_id'],
                'platform' => $validated['platform'] ?? 'ios',
                'model' => $validated['model'] ?? null,
                'os_version' => $validated['os_version'] ?? null,
            ]);

            Subscription::create([
                'device_id' => $device->device_id,
                'plan' => 'free',
                'status' => 'active',
                'start_date' => now(),
            ]);
        }

        $device->load('subscription');

        return response()->json([
            'success' => true,
            'device' => [
                'id' => $device->id,
                'device_id' => $device->device_id,
                'platform' => $device->platform,
                'subscription_plan' => $device->getSubscriptionPlan(),
                'total_usage_count' => $device->getTotalUsageCount(),
                'free_limit' => 2,
                'created_at' => $device->created_at->toISOString(),
            ],
        ]);
    }

    public function sync(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');

        $device->load('subscription');

        return response()->json([
            'device' => [
                'id' => $device->id,
                'device_id' => $device->device_id,
                'platform' => $device->platform,
                'subscription_plan' => $device->getSubscriptionPlan(),
                'total_usage_count' => $device->getTotalUsageCount(),
                'free_limit' => 2,
                'created_at' => $device->created_at->toISOString(),
            ],
        ]);
    }

    public function destroyData(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');

        foreach ($device->documents as $document) {
            if ($document->file_path) {
                Storage::disk('local')->delete($document->file_path);
            }
            if ($document->thumbnail_path) {
                Storage::disk('local')->delete($document->thumbnail_path);
            }
            if ($document->summary) {
                $document->summary->delete();
            }
            $document->delete();
        }

        $device->usageLogs()->delete();

        $device->update([
            'notifications_enabled' => true,
            'dark_mode_enabled' => false,
            'ui_language' => 'en',
            'output_language' => 'en',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All app data deleted successfully',
        ]);
    }
}
