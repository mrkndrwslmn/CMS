<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoring
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // Enable query logging for performance monitoring
        if (config('app.debug')) {
            DB::enableQueryLog();
        }
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds
        $memoryUsage = round(($endMemory - $startMemory) / 1024 / 1024, 2); // Convert to MB
        
        // Get query statistics if debugging is enabled
        $queryStats = [];
        if (config('app.debug')) {
            $queries = DB::getQueryLog();
            $queryCount = count($queries);
            $slowQueries = collect($queries)->filter(fn($q) => $q['time'] > 100)->count();
            $totalQueryTime = collect($queries)->sum('time');
            
            $queryStats = [
                'query_count' => $queryCount,
                'slow_queries' => $slowQueries,
                'total_query_time' => $totalQueryTime . 'ms',
            ];
        }
        
        // Log slow requests (over 1 second)
        if ($executionTime > 1000) {
            Log::warning('Slow request detected', array_merge([
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime . 'ms',
                'memory_usage' => $memoryUsage . 'MB',
                'user_id' => auth()->id(),
            ], $queryStats));
        }
        
        // Add performance headers for development
        if (config('app.debug')) {
            $response->headers->add([
                'X-Execution-Time' => $executionTime . 'ms',
                'X-Memory-Usage' => $memoryUsage . 'MB',
                'X-Query-Count' => $queryStats['query_count'] ?? 0,
                'X-Slow-Queries' => $queryStats['slow_queries'] ?? 0,
            ]);
        }
        
        return $response;
    }
}