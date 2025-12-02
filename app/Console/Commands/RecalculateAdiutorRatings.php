<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RecalculateAdiutorRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adiutors:recalculate-ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all adiutor ratings based on project feedback';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recalculating adiutor ratings from project feedback...');
        
        $adiutors = User::where('role', 'adiutor')
            ->with('adiutorProfile')
            ->get();
        
        $updated = 0;
        $skipped = 0;
        
        $progressBar = $this->output->createProgressBar($adiutors->count());
        $progressBar->start();
        
        foreach ($adiutors as $adiutor) {
            // Calculate rating from project feedback
            $calculatedRating = $adiutor->calculateAdiutorRating();
            
            if ($adiutor->adiutorProfile) {
                $oldRating = $adiutor->adiutorProfile->rating;
                $newRating = $calculatedRating ?? 0;
                
                $adiutor->adiutorProfile->update([
                    'rating' => $newRating
                ]);
                
                if ($oldRating != $newRating) {
                    $updated++;
                    $this->newLine();
                    $this->line("✓ {$adiutor->fullName}: {$oldRating} → {$newRating}");
                } else {
                    $skipped++;
                }
            } else {
                $skipped++;
                $this->newLine();
                $this->warn("⚠ {$adiutor->fullName}: No profile found");
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Recalculation complete!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Adiutors', $adiutors->count()],
                ['Ratings Updated', $updated],
                ['Unchanged/Skipped', $skipped],
            ]
        );
        
        return Command::SUCCESS;
    }
}
