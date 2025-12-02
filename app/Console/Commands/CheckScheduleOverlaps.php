<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskSchedule;
use Carbon\Carbon;

class CheckScheduleOverlaps extends Command
{
    protected $signature = 'schedule:check-overlaps {adiutor_id}';
    protected $description = 'Check for schedule overlaps for an adiutor';

    public function handle()
    {
        $adiutorId = $this->argument('adiutor_id');
        
        $schedules = TaskSchedule::with('task')
            ->where('adiutor_id', $adiutorId)
            ->orderBy('scheduled_start')
            ->get();
        
        $this->info("Schedules for Adiutor {$adiutorId}:");
        $this->info("===================================");
        $this->line("Total schedules: {$schedules->count()}");
        $this->newLine();
        
        foreach ($schedules as $schedule) {
            $this->line("Schedule {$schedule->id}: Task {$schedule->task_id} - {$schedule->task->taskTitle}");
            $this->line("  Type: {$schedule->schedule_type}");
            $this->line("  Start: {$schedule->scheduled_start}");
            $this->line("  End: {$schedule->scheduled_end}");
            $this->newLine();
        }
        
        // Check for overlaps
        $this->info("Overlap Analysis:");
        $this->info("=================");
        
        $overlaps = [];
        
        for ($i = 0; $i < $schedules->count(); $i++) {
            $current = $schedules[$i];
            $currentStart = Carbon::parse($current->scheduled_start);
            $currentEnd = Carbon::parse($current->scheduled_end);
            
            for ($j = $i + 1; $j < $schedules->count(); $j++) {
                $next = $schedules[$j];
                $nextStart = Carbon::parse($next->scheduled_start);
                $nextEnd = Carbon::parse($next->scheduled_end);
                
                // Check if schedules overlap
                if ($currentStart < $nextEnd && $nextStart < $currentEnd) {
                    $overlaps[] = [
                        'schedule1' => $current,
                        'schedule2' => $next,
                    ];
                    
                    $this->error("⚠ OVERLAP DETECTED:");
                    $this->line("  Schedule {$current->id} (Task {$current->task_id}): {$current->scheduled_start} to {$current->scheduled_end}");
                    $this->line("  Schedule {$next->id} (Task {$next->task_id}): {$next->scheduled_start} to {$next->scheduled_end}");
                    
                    if ($current->task_id < $next->task_id) {
                        $this->line("  → Task {$current->task_id} is OLDER - should stay scheduled");
                        $this->line("  → Task {$next->task_id} is NEWER - should be unscheduled");
                        $this->error("  ❌ BUG: Both are scheduled!");
                    } else {
                        $this->line("  → Task {$next->task_id} is OLDER - should stay scheduled");
                        $this->line("  → Task {$current->task_id} is NEWER - should be unscheduled");
                        $this->error("  ❌ BUG: Both are scheduled!");
                    }
                    $this->newLine();
                }
            }
        }
        
        if (empty($overlaps)) {
            $this->info("✓ No schedule overlaps found");
        } else {
            $this->error("Found " . count($overlaps) . " schedule overlap(s)");
        }
        
        return 0;
    }
}
