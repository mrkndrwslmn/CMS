<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\GroupChat;
use App\Models\User;

class PopulateGroupChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'groupchats:populate 
                            {--status=* : Filter by project status (e.g., open, pending, active, in_progress)}
                            {--all : Include all projects regardless of status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create group chats for existing projects that don\'t have one yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting group chat population...');
        
        // Get all projects
        $query = Project::query();
        
        // Filter by status if provided
        $statuses = $this->option('status');
        $includeAll = $this->option('all');
        
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
            $this->info('Filtering projects with status: ' . implode(', ', $statuses));
        } elseif (!$includeAll) {
            // Default: exclude completed/cancelled projects
            $query->whereNotIn('status', ['completed', 'cancelled', 'rejected']);
            $this->info('Excluding completed/cancelled/rejected projects (use --all to include all)');
        } else {
            $this->info('Processing ALL projects');
        }
        
        $projects = $query->get();
        
        if ($projects->isEmpty()) {
            $this->warn('No projects found matching the criteria.');
            return 0;
        }
        
        $this->info("Found {$projects->count()} projects to process.");
        
        $created = 0;
        $existing = 0;
        $errors = 0;
        
        $progressBar = $this->output->createProgressBar($projects->count());
        $progressBar->start();
        
        foreach ($projects as $project) {
            try {
                // Check if group chat already exists
                $existingChat = GroupChat::where('project_id', $project->id)->first();
                
                if ($existingChat) {
                    $existing++;
                    $progressBar->advance();
                    continue;
                }
                
                // Create group chat
                $groupChat = GroupChat::getOrCreateForProject($project->id);
                
                // Sync members from project (admins + assigned adiutors)
                $groupChat->syncMembersFromProject();
                
                $created++;
                $progressBar->advance();
                
            } catch (\Exception $e) {
                $errors++;
                $this->error("\nError processing project #{$project->id}: " . $e->getMessage());
                $progressBar->advance();
            }
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info('Group Chat Population Complete!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Created', $created],
                ['Already Existed', $existing],
                ['Errors', $errors],
                ['Total Processed', $projects->count()],
            ]
        );
        
        if ($created > 0) {
            $this->info("\n✅ Successfully created {$created} new group chats!");
            
            // Show some details about created chats
            $recentChats = GroupChat::with('project', 'members')
                ->latest()
                ->take(5)
                ->get();
            
            $this->newLine();
            $this->info('Sample of recently created group chats:');
            foreach ($recentChats as $chat) {
                $this->line("  - Project #{$chat->project_id}: {$chat->project->title} ({$chat->members->count()} members)");
            }
        } else {
            $this->comment("\nℹ️  All projects already have group chats!");
        }
        
        // Show overall statistics
        $this->newLine();
        $this->info('📊 Overall Group Chat Statistics:');
        $totalChats = GroupChat::count();
        $activeChats = GroupChat::where('status', 'open')->count();
        $archivedChats = GroupChat::where('status', 'archived')->count();
        $totalMembers = \DB::table('group_chat_members')->count();
        $avgMembersPerChat = $totalChats > 0 ? round($totalMembers / $totalChats, 1) : 0;
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Group Chats', $totalChats],
                ['Active Chats', $activeChats],
                ['Archived Chats', $archivedChats],
                ['Total Memberships', $totalMembers],
                ['Avg Members per Chat', $avgMembersPerChat],
            ]
        );
        
        return 0;
    }
}
