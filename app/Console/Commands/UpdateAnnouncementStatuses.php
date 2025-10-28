<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateAnnouncementStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update announcement statuses based on start and expiry dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        $this->info("Current time: {$now}");
        
        // Check scheduled announcements
        $scheduledAnnouncements = Announcement::where('status', 'scheduled')->get();
        $this->info("Found {$scheduledAnnouncements->count()} scheduled announcements:");
        
        foreach ($scheduledAnnouncements as $announcement) {
            $this->info("- ID: {$announcement->id}, Title: {$announcement->title}");
            $this->info("  Starts at: {$announcement->starts_at}");
            $this->info("  Should activate: " . ($announcement->starts_at && $announcement->starts_at <= $now ? 'YES' : 'NO'));
            if ($announcement->expires_at) {
                $this->info("  Expires at: {$announcement->expires_at}");
                $this->info("  Should expire: " . ($announcement->expires_at <= $now ? 'YES' : 'NO'));
            }
        }
        
        // Activate scheduled announcements whose start time has arrived
        $activatedCount = Announcement::where('status', 'scheduled')
            ->where('starts_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', $now);
            })
            ->update(['status' => 'active']);

        // Expire announcements whose expiry time has passed
        $expiredCount = Announcement::whereIn('status', ['active', 'scheduled'])
            ->where('expires_at', '<=', $now)
            ->whereNotNull('expires_at')
            ->update(['status' => 'expired']);

        $this->info("Announcement statuses updated:");
        $this->info("- Activated: {$activatedCount} announcements");
        $this->info("- Expired: {$expiredCount} announcements");
        
        return 0;
    }
}
