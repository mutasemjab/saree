<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ApiRequestLogger
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $endpoint = $request->path();
        $method = $request->method();
        $user = $request->user();
        $userId = $user ? $user->id : 'guest';
        
        // Track request count per endpoint (last 5 minutes)
        $cacheKey = "api_call_{$endpoint}_{$userId}_" . now()->format('Y-m-d_H:i');
        $callCount = Cache::increment($cacheKey);
        Cache::put($cacheKey, $callCount, now()->addMinutes(5));
        
        // Log request start
        Log::channel('api')->info("API Request Started", [
            'endpoint' => $endpoint,
            'method' => $method,
            'user_id' => $userId,
            'ip' => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
            'call_count_last_minute' => $callCount,
        ]);
        
        // Check for potential loop (more than 100 calls per minute to same endpoint)
        if ($callCount > 100) {
            Log::channel('api')->critical("POTENTIAL LOOP DETECTED", [
                'endpoint' => $endpoint,
                'user_id' => $userId,
                'call_count' => $callCount,
                'timestamp' => now()->toDateTimeString(),
            ]);
        }
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // in milliseconds
        
        // Log request completion
        Log::channel('api')->info("API Request Completed", [
            'endpoint' => $endpoint,
            'method' => $method,
            'user_id' => $userId,
            'duration_ms' => $duration,
            'status_code' => $response->status(),
            'memory_used_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'timestamp' => now()->toDateTimeString(),
        ]);
        
        // Log slow requests (> 2 seconds)
        if ($duration > 2000) {
            Log::channel('api')->warning("SLOW API REQUEST", [
                'endpoint' => $endpoint,
                'duration_ms' => $duration,
                'user_id' => $userId,
            ]);
        }
        
        return $response;
    }
}