<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdiutorCalendarIntegration;
use App\Services\GoogleCalendarService;

class ListGoogleCalendarEvents extends Command
{
    protected $signature = 'calendar:list-events {adiutor_id}';
    protected $description = 'List all events in an adiutor\'s Google Calendar';

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
            // Get events for next 30 days
            $start = now();
            $end = now()->addDays(30);
            
            $events = $calendarService->getEvents($integration->adiutor, $start, $end);
            
            $this->info("Events in Google Calendar for Adiutor {$adiutorId}:");
            $this->info("Total events: " . count($events));
            $this->line("");
            
            $cmsEvents = [];
            $otherEvents = [];
            
            foreach ($events as $event) {
                $title = $event['title'] ?? 'Untitled';
                if (str_starts_with($title, '[CMS]')) {
                    $cmsEvents[] = $event;
                } else {
                    $otherEvents[] = $event;
                }
            }
            
            $this->info("CMS Events (" . count($cmsEvents) . "):");
            foreach ($cmsEvents as $event) {
                $this->line("  - {$event['title']}");
                $this->line("    ID: {$event['id']}");
                $this->line("    Start: {$event['start']} | End: {$event['end']}");
                $this->line("");
            }
            
            $this->info("Other Events (" . count($otherEvents) . "):");
            foreach ($otherEvents as $event) {
                $this->line("  - {$event['title']}");
                $this->line("    ID: {$event['id']}");
                $this->line("    Start: {$event['start']} | End: {$event['end']}");
                $this->line("");
            }
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
