<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdiutorCalendarIntegration;
use App\Models\TaskSchedule;
use App\Services\GoogleCalendarService;

class CleanupGoogleCalendarEvents extends Command
{
    protected $signature = 'calendar:cleanup {adiutor_id}';
    protected $description = 'Remove duplicate Google Calendar events, keeping only those in database';

    public function handle()
    {
        $adiutorId = $this->argument('adiutor_id');
        $integration = AdiutorCalendarIntegration::where('adiutor_id', $adiutorId)->first();
        
        if (!$integration) {
            $this->error("No calendar integration found for adiutor {$adiutorId}");
            return;
        }

        $calendarService = new GoogleCalendarService();
        
        try {
            // Get all events from Google Calendar
            $start = now()->subDays(30);
            $end = now()->addDays(60);
            
            $events = $calendarService->getEvents($integration->adiutor, $start, $end);
            
            // Get valid event IDs from our database
            $validEventIds = TaskSchedule::whereNotNull('google_calendar_event_id')
                ->pluck('google_calendar_event_id')
                ->toArray();
            
            $this->info("Valid event IDs in database: " . count($validEventIds));
            foreach ($validEventIds as $id) {
                $this->line("  - {$id}");
            }
            $this->line("");
            
            $cmsEvents = array_filter($events, function($event) {
                return str_starts_with($event['title'] ?? '', '[CMS]');
            });
            
            $this->info("Total CMS events in Google Calendar: " . count($cmsEvents));
            
            $deleted = 0;
            $kept = 0;
            
            foreach ($cmsEvents as $event) {
                if (!in_array($event['id'], $validEventIds)) {
                    // This event is not in our database, delete it
                    $this->line("Deleting orphaned event: {$event['title']} (ID: {$event['id']})");
                    $calendarService->deleteTaskEvent($integration->adiutor, $event['id']);
                    $deleted++;
                } else {
                    $this->line("Keeping valid event: {$event['title']} (ID: {$event['id']})");
                    $kept++;
                }
            }
            
            $this->line("");
            $this->info("Cleanup complete!");
            $this->info("Events kept: {$kept}");
            $this->info("Events deleted: {$deleted}");
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
