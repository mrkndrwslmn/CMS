<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdiutorCalendarIntegration;
use App\Services\GoogleCalendarService;

class DebugGoogleEvent extends Command
{
    protected $signature = 'calendar:debug-event {adiutor_id} {event_id}';
    protected $description = 'Debug a Google Calendar event raw data';

    public function handle()
    {
        $adiutorId = $this->argument('adiutor_id');
        $eventId = $this->argument('event_id');
        
        $integration = AdiutorCalendarIntegration::where('adiutor_id', $adiutorId)->first();
        
        if (!$integration) {
            $this->error("No calendar integration found");
            return;
        }

        $service = new GoogleCalendarService();
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('initializeService');
        $method->setAccessible(true);
        $method->invoke($service, $integration->adiutor);
        
        $property = $reflection->getProperty('service');
        $property->setAccessible(true);
        $calendarService = $property->getValue($service);
        
        try {
            $event = $calendarService->events->get('primary', $eventId);
            
            $this->info("Event: " . $event->getSummary());
            $this->line("");
            
            $this->info("Start:");
            $this->line("  DateTime: " . ($event->getStart()->getDateTime() ?? 'null'));
            $this->line("  Date: " . ($event->getStart()->getDate() ?? 'null'));
            $this->line("  TimeZone: " . ($event->getStart()->getTimeZone() ?? 'null'));
            $this->line("");
            
            $this->info("End:");
            $this->line("  DateTime: " . ($event->getEnd()->getDateTime() ?? 'null'));
            $this->line("  Date: " . ($event->getEnd()->getDate() ?? 'null'));
            $this->line("  TimeZone: " . ($event->getEnd()->getTimeZone() ?? 'null'));
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
