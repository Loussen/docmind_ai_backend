<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $deviceId = $request->header('X-Device-ID');

        if (!$deviceId) {
            return response()->json([
                'error' => 'Device ID required',
                'message' => 'X-Device-ID header is missing.',
            ], 401);
        }

        $device = Device::where('device_id', $deviceId)->first();

        if (!$device) {
            return response()->json([
                'error' => 'Device not registered',
                'message' => 'Please register your device first.',
            ], 401);
        }

        $request->merge(['device' => $device]);
        $request->attributes->set('device', $device);

        return $next($request);
    }
}
