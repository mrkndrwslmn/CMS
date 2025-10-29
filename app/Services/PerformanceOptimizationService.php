<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class PerformanceOptimizationService
{
    /**
     * Cache TTL for different types of data
     */
    const CACHE_TTL = [
        'short' => 300,   // 5 minutes
        'medium' => 1800, // 30 minutes
        'long' => 3600,   // 1 hour
        'daily' => 86400, // 24 hours
    ];

    /**
     * Get cached query results with automatic key generation
     */
    public function getCachedQuery(string $key, callable $callback, string $duration = 'medium')
    {
        $ttl = self::CACHE_TTL[$duration] ?? self::CACHE_TTL['medium'];
        
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Clear specific cache patterns
     */
    public function clearCachePattern(string $pattern)
    {
        $keys = Cache::getRedis()->keys("*{$pattern}*");
        
        if (!empty($keys)) {
            Cache::getRedis()->del($keys);
            return count($keys);
        }
        
        return 0;
    }

    /**
     * Get database performance metrics
     */
    public function getDatabaseMetrics()
    {
        return [
            'active_connections' => $this->getActiveConnections(),
            'slow_queries' => $this->getSlowQueryCount(),
            'table_sizes' => $this->getTableSizes(),
            'index_usage' => $this->getIndexUsage(),
        ];
    }

    /**
     * Optimize Laravel application
     */
    public function optimizeApplication()
    {
        $results = [];
        
        try {
            // Clear all caches first
            Artisan::call('optimize:clear');
            $results['cache_clear'] = 'Success';
            
            // Cache configuration
            Artisan::call('config:cache');
            $results['config_cache'] = 'Success';
            
            // Cache routes
            Artisan::call('route:cache');
            $results['route_cache'] = 'Success';
            
            // Cache views
            Artisan::call('view:cache');
            $results['view_cache'] = 'Success';
            
        } catch (\Exception $e) {
            Log::error('Application optimization failed: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }
        
        return $results;
    }

    /**
     * Get recommendations for performance improvements
     */
    public function getPerformanceRecommendations()
    {
        $recommendations = [];
        
        // Check cache configuration
        if (config('cache.default') === 'file') {
            $recommendations[] = [
                'type' => 'cache',
                'priority' => 'high',
                'message' => 'Consider using Redis or Memcached instead of file cache for better performance',
                'action' => 'Change CACHE_DRIVER in .env to redis or memcached'
            ];
        }
        
        // Check session configuration
        if (config('session.driver') === 'file') {
            $recommendations[] = [
                'type' => 'session',
                'priority' => 'medium',
                'message' => 'File-based sessions can be slow with many concurrent users',
                'action' => 'Change SESSION_DRIVER in .env to database or redis'
            ];
        }
        
        // Check debug mode
        if (config('app.debug') === true && config('app.env') !== 'local') {
            $recommendations[] = [
                'type' => 'debug',
                'priority' => 'critical',
                'message' => 'Debug mode is enabled in production environment',
                'action' => 'Set APP_DEBUG=false in .env'
            ];
        }
        
        return $recommendations;
    }

    /**
     * Get active database connections
     */
    private function getActiveConnections()
    {
        try {
            $result = DB::select('SHOW STATUS LIKE "Threads_connected"');
            return $result[0]->Value ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Get slow query count (if slow query log is enabled)
     */
    private function getSlowQueryCount()
    {
        try {
            $result = DB::select('SHOW STATUS LIKE "Slow_queries"');
            return $result[0]->Value ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Get table sizes for optimization insights
     */
    private function getTableSizes()
    {
        try {
            $query = "
                SELECT 
                    table_name,
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) as size_mb,
                    table_rows
                FROM information_schema.TABLES 
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC
                LIMIT 10
            ";
            
            return DB::select($query);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get index usage statistics
     */
    private function getIndexUsage()
    {
        try {
            $query = "
                SELECT 
                    table_name,
                    index_name,
                    cardinality
                FROM information_schema.STATISTICS 
                WHERE table_schema = DATABASE()
                AND index_name != 'PRIMARY'
                ORDER BY cardinality DESC
                LIMIT 20
            ";
            
            return DB::select($query);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}