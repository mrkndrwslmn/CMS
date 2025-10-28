<?php

namespace App\Http\Middleware;

use App\Models\Announcement;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class UpdateAnnouncementStatuses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run this for web routes to avoid API overhead
        if ($request->is('admin/*') || $request->is('/')) {
            $this->updateAnnouncementStatuses();
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
