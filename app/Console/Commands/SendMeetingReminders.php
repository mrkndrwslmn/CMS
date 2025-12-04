<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Notifications\MeetingReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMeetingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meetings:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for upcoming meetings (1 hour and 15 minutes before)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $oneHourFromNow = $now->copy()->addHour();
        $fifteenMinutesFromNow = $now->copy()->addMinutes(15);
        
        $this->info('Checking for meetings to send reminders...');
        
        // Get approved meetings
        $meetings = Meeting::where('status', Meeting::STATUS_APPROVED)
            ->whereNotNull('scheduled_date')
            ->whereNotNull('scheduled_time')
            ->with(['client', 'admin', 'project'])
            ->get();
        
        $remindersSent = 0;
        
        foreach ($meetings as $meeting) {
            $scheduledDateTime = $meeting->getScheduledDateTime();
            
            if (!$scheduledDateTime || $scheduledDateTime->isPast()) {
                continue;
            }
            
            // Calculate time difference in minutes
            $minutesUntilMeeting = $now->diffInMinutes($scheduledDateTime, false);
            
            // Send 1-hour reminder (between 59-61 minutes before)
            if ($minutesUntilMeeting >= 59 && $minutesUntilMeeting <= 61) {
                $this->sendReminder($meeting, '1_hour');
                $remindersSent++;
            }
            
            // Send 15-minute reminder (between 14-16 minutes before)
            if ($minutesUntilMeeting >= 14 && $minutesUntilMeeting <= 16) {
                $this->sendReminder($meeting, '15_minutes');
                $remindersSent++;
            }
        }
        
        $this->info("Sent {$remindersSent} meeting reminder(s).");
        
        return Command::SUCCESS;
    }
    
    /**
     * Send reminder notification to meeting participants
     */
    protected function sendReminder(Meeting $meeting, string $reminderType): void
    {
        $notification = new MeetingReminderNotification($meeting, $reminderType);
        
        // Notify the client
        if ($meeting->client) {
            try {
                $meeting->client->notify($notification);
                $this->line("  - Sent {$reminderType} reminder to client: {$meeting->client->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send meeting reminder to client", [
                    'meeting_id' => $meeting->id,
                    'client_id' => $meeting->client_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Notify the admin who approved the meeting
        if ($meeting->admin) {
            try {
                $meeting->admin->notify($notification);
                $this->line("  - Sent {$reminderType} reminder to admin: {$meeting->admin->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send meeting reminder to admin", [
                    'meeting_id' => $meeting->id,
                    'admin_id' => $meeting->admin_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

