<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

class FixProjectBudgets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:fix-budgets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix project budgets to reflect discounted amounts from service requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing project budgets to reflect coupon/loyalty discounts...');
        
        // Get all projects that have a service request with discounts
        $projects = Project::whereHas('serviceRequest', function($query) {
            $query->where(function($q) {
                $q->where('coupon_discount_amount', '>', 0)
                  ->orWhere('loyalty_discount_amount', '>', 0);
            });
        })->with('serviceRequest')->get();
        
        if ($projects->isEmpty()) {
            $this->info('No projects found with discounts to fix.');
            return 0;
        }
        
        $this->info("Found {$projects->count()} projects with discounts.");
        
        $fixed = 0;
        $skipped = 0;
        
        foreach ($projects as $project) {
            $serviceRequest = $project->serviceRequest;
            
            if (!$serviceRequest) {
                $this->warn("Project {$project->id} has no service request. Skipping.");
                $skipped++;
                continue;
            }
            
            $oldBudget = $project->budget;
            $newBudget = $serviceRequest->approved_budget; // This is already the discounted amount
            
            if ($oldBudget == $newBudget) {
                $this->line("Project {$project->id}: Budget already correct (₱" . number_format($newBudget, 2) . ")");
                $skipped++;
                continue;
            }
            
            // Update project budget
            $project->budget = $newBudget;
            $project->save();
            
            $this->info("Project {$project->id}: Updated budget from ₱" . number_format($oldBudget, 2) . " to ₱" . number_format($newBudget, 2));
            
            // Also update milestone amounts if this is a milestone payment project
            if ($serviceRequest->payment_type === 'milestone_payment') {
                $milestones = $project->milestones;
                if ($milestones->isNotEmpty()) {
                    foreach ($milestones as $milestone) {
                        $newAmount = ($newBudget * $milestone->percentage) / 100;
                        $oldAmount = $milestone->amount;
                        
                        if ($oldAmount != $newAmount) {
                            $milestone->amount = $newAmount;
                            $milestone->save();
                            $this->line("  - Updated milestone '{$milestone->phase_name}' from ₱" . number_format($oldAmount, 2) . " to ₱" . number_format($newAmount, 2));
                        }
                    }
                }
            }
            
            $fixed++;
        }
        
        $this->newLine();
        $this->info("✓ Fixed {$fixed} projects");
        if ($skipped > 0) {
            $this->info("  Skipped {$skipped} projects (already correct or no service request)");
        }
        
        return 0;
    }
}
