<?php

namespace App\Http\Middleware;

use App\Models\Announcement;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class UpdateAnnouncementStatuses
{
    /**
     * Handle an incoming request.
     * Throttled to run at most once per minute to avoid performance impact.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run on web routes and throttle to once per minute
        if ($request->is('admin/*') || $request->is('/')) {
            // Use cache lock to prevent concurrent updates and limit frequency
            $cacheKey = 'announcement_status_update_lock';
            
            if (!Cache::has($cacheKey)) {
                // Set cache for 60 seconds to throttle updates
                Cache::put($cacheKey, true, 60);
                $this->updateAnnouncementStatuses();
            }
        }

        return $next($request);
    }

    /**
     * Update announcement statuses based on current time
     */
    private function updateAnnouncementStatuses()
    {
        $now = Carbon::now();
        
        // Activate scheduled announcements whose start time has arrived
        Announcement::where('status', 'scheduled')
            ->where('starts_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', $now);
            })
            ->update(['status' => 'active']);

        // Expire announcements whose expiry time has passed
        Announcement::whereIn('status', ['active', 'scheduled'])
            ->where('expires_at', '<=', $now)
            ->whereNotNull('expires_at')
            ->update(['status' => 'expired']);
    }
}
