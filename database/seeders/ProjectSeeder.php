<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get approved service requests to convert to projects
        $approvedRequests = ServiceRequest::where('status', 'approved')->get();
        
        if ($approvedRequests->isEmpty()) {
            $this->command->warn('No approved service requests found. Please run ServiceRequestSeeder first.');
            return;
        }

        // Get adiutors for project assignments
        $adiutors = User::where('role', 'adiutor')->get();

        $projects = [];

        foreach ($approvedRequests as $request) {
            // Calculate project budget based on approved budget
            $finalPrice = ($request->approved_budget ?? $request->estimated_budget ?? 5000);

            $project = [
                'service_request_id' => $request->id,
                'client_id' => $request->client_id,
                'title' => $request->project_name,
                'description' => $request->request_description,
                'requirements' => $request->requirements, // JSON requirements from service request
                'budget' => $finalPrice,
                'budget_type' => 'fixed',
                'deadline' => $request->deadline,
                'status' => $this->getRandomProjectStatus(),
                'priority' => $request->priority,
                'started_at' => now()->subDays(rand(1, 5)),
                'created_at' => $request->approved_at,
                'updated_at' => now(),
            ];

            $projects[] = $project;
        }

        // Create projects
        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            
            // Assign adiutors to projects based on skills needed
            $this->assignAdiutorsToProject($project, $adiutors);
        }

                $this->command->info('Created ' . count($projects) . ' projects from approved service requests');
    }

    private function getRandomProjectStatus(): string
    {
        $statuses = ['active', 'in_progress', 'review', 'completed', 'cancelled'];
        $weights = [20, 50, 15, 10, 5]; // Higher chance for in_progress
        
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $statuses[$index];
            }
        }
        
        return 'in_progress';
    }

    private function getCategoryFromServiceType(string $serviceType): string
    {
        $categories = [
            'Custom Website Development' => 'Web Development',
            'E-commerce Platform Development' => 'Web Development', 
            'Web Application Development' => 'Web Development',
            'WordPress Development' => 'Web Development',
            'Mobile App Development' => 'Mobile Development',
            'Progressive Web App' => 'Mobile Development',
            'UI/UX Design' => 'Design',
            'Brand Identity Design' => 'Design',
            'Website Redesign' => 'Design',
            'API Development' => 'Backend Development',
            'Backend Development' => 'Backend Development',
            'Third-party Integration' => 'Integration',
            'Technical Consulting' => 'Consulting',
            'Digital Strategy Consulting' => 'Consulting',
            'Website Maintenance' => 'Maintenance',
            'SEO Optimization' => 'Marketing',
        ];

        return $categories[$serviceType] ?? 'General';
    }

    private function calculateEstimatedHours(string $serviceType): int
    {
        $hourEstimates = [
            'Custom Website Development' => 180,
            'E-commerce Platform Development' => 360,
            'Web Application Development' => 240,
            'WordPress Development' => 120,
            'Mobile App Development' => 480,
            'Progressive Web App' => 200,
            'UI/UX Design' => 140,
            'Brand Identity Design' => 84,
            'Website Redesign' => 160,
            'API Development' => 140,
            'Backend Development' => 140,
            'Third-party Integration' => 80,
            'Technical Consulting' => 28, // 7 days * 4 hours
            'Digital Strategy Consulting' => 56,
            'Website Maintenance' => 20, // monthly
            'SEO Optimization' => 120,
        ];

        return $hourEstimates[$serviceType] ?? 100;
    }

    private function getProjectDuration(string $serviceType): int
    {
        $durations = [
            'Custom Website Development' => 45,
            'E-commerce Platform Development' => 90,
            'Web Application Development' => 60,
            'WordPress Development' => 30,
            'Mobile App Development' => 120,
            'Progressive Web App' => 50,
            'UI/UX Design' => 35,
            'Brand Identity Design' => 21,
            'Website Redesign' => 40,
            'API Development' => 35,
            'Backend Development' => 35,
            'Third-party Integration' => 20,
            'Technical Consulting' => 7,
            'Digital Strategy Consulting' => 14,
            'Website Maintenance' => 30,
            'SEO Optimization' => 30,
        ];

        return $durations[$serviceType] ?? 30;
    }

    private function getPaymentStatus(): string
    {
        $statuses = ['pending', 'partial', 'paid', 'overdue'];
        $weights = [30, 25, 35, 10];
        
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $statuses[$index];
            }
        }
        
        return 'pending';
    }

    private function assignAdiutorsToProject(Project $project, $adiutors)
    {
        // Assign 1-3 adiutors based on project status
        $adiutorCount = match($project->status) {
            'active', 'in_progress' => rand(2, 3),
            'review' => rand(1, 2),
            default => rand(1, 2)
        };

        $selectedAdiutors = $adiutors->random(min($adiutorCount, $adiutors->count()));

        foreach ($selectedAdiutors as $adiutor) {
            $hourlyRate = $adiutor->adiutorProfile->hourly_rate ?? 65.00;
            $agreedRate = $hourlyRate * (1 + rand(-10, 15) / 100);

            $project->adiutors()->attach($adiutor->id, [
                'agreed_rate' => $agreedRate,
                'start_date' => $project->started_at ?? now(),
                'expected_completion' => $project->deadline,
                'status' => 'active',
                'progress_percentage' => rand(0, 100),
                'notes' => 'Assigned based on skills match and availability',
            ]);
        }
    }
}