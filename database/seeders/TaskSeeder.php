<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get projects to break down into tasks
        $projects = Project::all();
        
        if ($projects->isEmpty()) {
            $this->command->warn('No projects found. Please run ProjectSeeder first.');
            return;
        }

        // Get users for task assignments
        $adiutors = User::where('role', 'adiutor')->get();
        $admins = User::where('role', 'admin')->get();

        $tasksCreated = 0;

        foreach ($projects as $project) {
            $tasks = $this->getTasksForProject($project);
            
            foreach ($tasks as $taskData) {
                // Select appropriate adiutor based on project assignments
                $assignedAdiutor = $this->getAdiutorForTask($project, $adiutors, null);
                
                Task::create([
                    'project_id' => $project->id,
                    'service_request_id' => $project->service_request_id,
                    'client_id' => $project->client_id,
                    'assignedTo' => $assignedAdiutor->id,
                    'createdBy' => $admins->random()->id,
                    'taskTitle' => $taskData['title'],
                    'taskDescription' => $taskData['description'],
                    'priority' => $taskData['priority'],
                    'status' => $taskData['status'],
                    'allocated_budget' => $taskData['budget'] ?? null,
                    'actual_cost' => $taskData['actual_cost'] ?? null,
                    'deadline' => now()->addDays($taskData['deadline_days']),
                    'dateAssigned' => $project->started_at ?? now(),
                    'completedAt' => $taskData['status'] === 'completed' ? now()->subDays(rand(1, 5)) : null,
                    'progress_percentage' => $taskData['progress'],
                    'notes' => $taskData['notes'] ?? 'Task created from project breakdown',
                    'created_at' => $project->created_at->addHours(rand(1, 72)),
                ]);
                
                $tasksCreated++;
            }
        }

        $this->command->info("Created {$tasksCreated} realistic tasks broken down from {$projects->count()} projects");
    }

    private function getTasksForProject(Project $project): array
    {
        // Simplified task generation based on project status
        // All projects get similar tasks, varying by complexity
        return $this->getGeneralTasks($project);
    }

    private function getWebDevelopmentTasks(Project $project): array
    {
        $baseTasks = [
            [
                'title' => 'Project Setup & Environment Configuration',
                'description' => 'Set up development environment, configure version control, and establish project structure',
                'category' => 'Development',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 8,
                'actual_hours' => 9,
                'deadline_days' => 3,
                'tags' => ['setup', 'configuration', 'git'],
                'requirements' => 'Development environment setup, Git repository creation, project scaffolding',
                'dependencies' => null,
                'progress' => 100,
            ],
            [
                'title' => 'Database Design & Migration Setup',
                'description' => 'Design database schema, create migrations, and set up initial data structure',
                'category' => 'Backend',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 12,
                'actual_hours' => 14,
                'deadline_days' => 7,
                'tags' => ['database', 'migrations', 'schema'],
                'requirements' => 'Database schema design, migration files, seed data preparation',
                'dependencies' => 'Project Setup & Environment Configuration',
                'progress' => 100,
            ],
            [
                'title' => 'Frontend UI/UX Implementation',
                'description' => 'Implement responsive frontend interface based on design mockups',
                'category' => 'Frontend',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'completed']),
                'estimated_hours' => 40,
                'actual_hours' => rand(25, 45),
                'deadline_days' => 21,
                'tags' => ['frontend', 'ui', 'responsive', 'css'],
                'requirements' => 'Responsive design implementation, cross-browser compatibility, accessibility standards',
                'dependencies' => 'Database Design & Migration Setup',
                'progress' => rand(60, 100),
            ],
            [
                'title' => 'Backend API Development',
                'description' => 'Develop REST API endpoints for frontend data consumption',
                'category' => 'Backend',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'review']),
                'estimated_hours' => 32,
                'actual_hours' => rand(20, 35),
                'deadline_days' => 28,
                'tags' => ['api', 'backend', 'rest', 'endpoints'],
                'requirements' => 'RESTful API design, authentication, data validation, error handling',
                'dependencies' => 'Database Design & Migration Setup',
                'progress' => rand(40, 90),
            ],
            [
                'title' => 'User Authentication System',
                'description' => 'Implement user registration, login, and authentication middleware',
                'category' => 'Security',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 16,
                'actual_hours' => rand(0, 18),
                'deadline_days' => 14,
                'tags' => ['auth', 'security', 'middleware'],
                'requirements' => 'User registration, login, password reset, role-based access control',
                'dependencies' => 'Backend API Development',
                'progress' => rand(0, 70),
            ],
            [
                'title' => 'Testing & Quality Assurance',
                'description' => 'Write unit tests, integration tests, and perform quality assurance',
                'category' => 'Testing',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 24,
                'actual_hours' => rand(0, 15),
                'deadline_days' => 35,
                'tags' => ['testing', 'qa', 'unit-tests'],
                'requirements' => 'Unit test coverage, integration testing, manual QA testing',
                'dependencies' => 'Frontend UI/UX Implementation',
                'progress' => rand(0, 50),
            ],
        ];

        // Add project-specific tasks based on requirements
        if (str_contains($project->requirements, 'CRM')) {
            $baseTasks[] = [
                'title' => 'CRM Integration Setup',
                'description' => 'Integrate with external CRM system for lead management',
                'category' => 'Integration',
                'priority' => 'medium',
                'status' => 'pending',
                'estimated_hours' => 16,
                'actual_hours' => 0,
                'deadline_days' => 25,
                'tags' => ['crm', 'integration', 'api'],
                'requirements' => 'CRM API integration, data synchronization, error handling',
                'dependencies' => 'Backend API Development',
                'progress' => 0,
            ];
        }

        return $baseTasks;
    }

    private function getMobileDevelopmentTasks(Project $project): array
    {
        return [
            [
                'title' => 'Mobile App Architecture Planning',
                'description' => 'Design app architecture, navigation flow, and component structure',
                'category' => 'Planning',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 16,
                'actual_hours' => 18,
                'deadline_days' => 7,
                'tags' => ['architecture', 'planning', 'mobile'],
                'requirements' => 'App architecture document, navigation wireframes, component breakdown',
                'dependencies' => null,
                'progress' => 100,
            ],
            [
                'title' => 'Cross-Platform Setup (React Native/Flutter)',
                'description' => 'Set up cross-platform development environment and project structure',
                'category' => 'Development',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 12,
                'actual_hours' => 15,
                'deadline_days' => 10,
                'tags' => ['setup', 'cross-platform', 'react-native', 'flutter'],
                'requirements' => 'Development environment setup, project scaffolding, build configuration',
                'dependencies' => 'Mobile App Architecture Planning',
                'progress' => 100,
            ],
            [
                'title' => 'UI Components Development',
                'description' => 'Develop reusable UI components and screens',
                'category' => 'Frontend',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'review']),
                'estimated_hours' => 60,
                'actual_hours' => rand(35, 65),
                'deadline_days' => 45,
                'tags' => ['ui', 'components', 'screens'],
                'requirements' => 'Reusable components, responsive design, platform-specific adaptations',
                'dependencies' => 'Cross-Platform Setup (React Native/Flutter)',
                'progress' => rand(50, 85),
            ],
            [
                'title' => 'API Integration & State Management',
                'description' => 'Integrate with backend APIs and implement state management',
                'category' => 'Backend',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'pending']),
                'estimated_hours' => 32,
                'actual_hours' => rand(15, 35),
                'deadline_days' => 35,
                'tags' => ['api', 'state-management', 'integration'],
                'requirements' => 'API integration, state management, offline capabilities',
                'dependencies' => 'UI Components Development',
                'progress' => rand(25, 75),
            ],
            [
                'title' => 'Push Notifications Setup',
                'description' => 'Implement push notification system for user engagement',
                'category' => 'Features',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 20,
                'actual_hours' => rand(0, 12),
                'deadline_days' => 50,
                'tags' => ['notifications', 'engagement', 'push'],
                'requirements' => 'Push notification service integration, scheduling, user preferences',
                'dependencies' => 'API Integration & State Management',
                'progress' => rand(0, 60),
            ],
            [
                'title' => 'App Store Deployment',
                'description' => 'Prepare and deploy app to iOS App Store and Google Play Store',
                'category' => 'Deployment',
                'priority' => 'medium',
                'status' => 'pending',
                'estimated_hours' => 16,
                'actual_hours' => 0,
                'deadline_days' => 80,
                'tags' => ['deployment', 'app-store', 'google-play'],
                'requirements' => 'App store optimization, screenshots, descriptions, submission process',
                'dependencies' => 'Push Notifications Setup',
                'progress' => 0,
            ],
        ];
    }

    private function getDesignTasks(Project $project): array
    {
        return [
            [
                'title' => 'Brand Research & Analysis',
                'description' => 'Research target audience, competitors, and brand positioning',
                'category' => 'Research',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 12,
                'actual_hours' => 14,
                'deadline_days' => 5,
                'tags' => ['research', 'branding', 'analysis'],
                'requirements' => 'Market research, competitor analysis, brand positioning document',
                'dependencies' => null,
                'progress' => 100,
            ],
            [
                'title' => 'Concept Development & Sketching',
                'description' => 'Create initial design concepts and sketches',
                'category' => 'Design',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 16,
                'actual_hours' => 18,
                'deadline_days' => 10,
                'tags' => ['concepts', 'sketching', 'ideation'],
                'requirements' => 'Initial concept sketches, design directions, client feedback incorporation',
                'dependencies' => 'Brand Research & Analysis',
                'progress' => 100,
            ],
            [
                'title' => 'Digital Design Implementation',
                'description' => 'Create final digital designs and variations',
                'category' => 'Design',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'review']),
                'estimated_hours' => 32,
                'actual_hours' => rand(20, 35),
                'deadline_days' => 18,
                'tags' => ['digital-design', 'implementation', 'variations'],
                'requirements' => 'Final design variations, color schemes, typography selection',
                'dependencies' => 'Concept Development & Sketching',
                'progress' => rand(60, 95),
            ],
            [
                'title' => 'Brand Guidelines Creation',
                'description' => 'Develop comprehensive brand guidelines document',
                'category' => 'Documentation',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 16,
                'actual_hours' => rand(0, 10),
                'deadline_days' => 21,
                'tags' => ['guidelines', 'documentation', 'brand-standards'],
                'requirements' => 'Brand guidelines document, usage examples, do/don\'t examples',
                'dependencies' => 'Digital Design Implementation',
                'progress' => rand(0, 40),
            ],
        ];
    }

    private function getBackendTasks(Project $project): array
    {
        return [
            [
                'title' => 'API Architecture Design',
                'description' => 'Design RESTful API architecture and endpoint structure',
                'category' => 'Planning',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 12,
                'actual_hours' => 14,
                'deadline_days' => 5,
                'tags' => ['api', 'architecture', 'rest'],
                'requirements' => 'API documentation, endpoint specification, data models',
                'dependencies' => null,
                'progress' => 100,
            ],
            [
                'title' => 'Database Schema Implementation',
                'description' => 'Implement database schema and relationships',
                'category' => 'Database',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 16,
                'actual_hours' => 18,
                'deadline_days' => 10,
                'tags' => ['database', 'schema', 'relationships'],
                'requirements' => 'Database tables, relationships, indexing, constraints',
                'dependencies' => 'API Architecture Design',
                'progress' => 100,
            ],
            [
                'title' => 'API Endpoints Development',
                'description' => 'Develop and implement API endpoints with proper validation',
                'category' => 'Development',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'review']),
                'estimated_hours' => 40,
                'actual_hours' => rand(25, 45),
                'deadline_days' => 25,
                'tags' => ['endpoints', 'validation', 'development'],
                'requirements' => 'CRUD operations, data validation, error handling, authentication',
                'dependencies' => 'Database Schema Implementation',
                'progress' => rand(50, 90),
            ],
            [
                'title' => 'API Testing & Documentation',
                'description' => 'Create comprehensive API tests and documentation',
                'category' => 'Testing',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 24,
                'actual_hours' => rand(0, 15),
                'deadline_days' => 30,
                'tags' => ['testing', 'documentation', 'api-docs'],
                'requirements' => 'Unit tests, integration tests, API documentation, Postman collection',
                'dependencies' => 'API Endpoints Development',
                'progress' => rand(0, 60),
            ],
        ];
    }

    private function getConsultingTasks(Project $project): array
    {
        return [
            [
                'title' => 'Current System Analysis',
                'description' => 'Analyze existing systems and identify improvement opportunities',
                'category' => 'Analysis',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 8,
                'actual_hours' => 10,
                'deadline_days' => 3,
                'tags' => ['analysis', 'assessment', 'current-state'],
                'requirements' => 'System audit, performance analysis, bottleneck identification',
                'dependencies' => null,
                'progress' => 100,
            ],
            [
                'title' => 'Recommendations Development',
                'description' => 'Develop strategic recommendations and implementation roadmap',
                'category' => 'Strategy',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'review']),
                'estimated_hours' => 16,
                'actual_hours' => rand(10, 18),
                'deadline_days' => 7,
                'tags' => ['recommendations', 'strategy', 'roadmap'],
                'requirements' => 'Strategic recommendations, implementation plan, resource requirements',
                'dependencies' => 'Current System Analysis',
                'progress' => rand(60, 95),
            ],
            [
                'title' => 'Final Report & Presentation',
                'description' => 'Create comprehensive report and presentation for stakeholders',
                'category' => 'Documentation',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 8,
                'actual_hours' => rand(0, 6),
                'deadline_days' => 14,
                'tags' => ['report', 'presentation', 'deliverables'],
                'requirements' => 'Executive summary, detailed findings, presentation slides',
                'dependencies' => 'Recommendations Development',
                'progress' => rand(0, 50),
            ],
        ];
    }

    private function getIntegrationTasks(Project $project): array
    {
        return [
            [
                'title' => 'Integration Planning & Documentation Review',
                'description' => 'Review third-party API documentation and plan integration approach',
                'category' => 'Planning',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 8,
                'actual_hours' => 10,
                'deadline_days' => 3,
                'tags' => ['planning', 'documentation', 'api-review'],
                'requirements' => 'API documentation review, integration strategy, data mapping',
                'dependencies' => null,
                'progress' => 100,
            ],
            [
                'title' => 'Integration Development',
                'description' => 'Develop integration code and handle data transformation',
                'category' => 'Development',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'review']),
                'estimated_hours' => 32,
                'actual_hours' => rand(20, 35),
                'deadline_days' => 15,
                'tags' => ['integration', 'development', 'data-transformation'],
                'requirements' => 'Integration code, error handling, data validation, logging',
                'dependencies' => 'Integration Planning & Documentation Review',
                'progress' => rand(50, 85),
            ],
            [
                'title' => 'Testing & Validation',
                'description' => 'Test integration thoroughly and validate data flow',
                'category' => 'Testing',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 16,
                'actual_hours' => rand(0, 12),
                'deadline_days' => 20,
                'tags' => ['testing', 'validation', 'data-flow'],
                'requirements' => 'Integration testing, data validation, error scenarios testing',
                'dependencies' => 'Integration Development',
                'progress' => rand(0, 60),
            ],
        ];
    }

    private function getMaintenanceTasks(Project $project): array
    {
        return [
            [
                'title' => 'Monthly Security Updates',
                'description' => 'Apply security patches and update dependencies',
                'category' => 'Security',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'completed']),
                'estimated_hours' => 4,
                'actual_hours' => rand(3, 6),
                'deadline_days' => 5,
                'tags' => ['security', 'updates', 'patches'],
                'requirements' => 'Security patch application, dependency updates, vulnerability scanning',
                'dependencies' => null,
                'progress' => rand(70, 100),
            ],
            [
                'title' => 'Performance Monitoring & Optimization',
                'description' => 'Monitor website performance and optimize as needed',
                'category' => 'Performance',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 6,
                'actual_hours' => rand(0, 8),
                'deadline_days' => 15,
                'tags' => ['performance', 'monitoring', 'optimization'],
                'requirements' => 'Performance metrics review, optimization recommendations, implementation',
                'dependencies' => null,
                'progress' => rand(20, 80),
            ],
            [
                'title' => 'Content Updates & Backup Verification',
                'description' => 'Update content as requested and verify backup integrity',
                'category' => 'Maintenance',
                'priority' => 'low',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 4,
                'actual_hours' => rand(0, 5),
                'deadline_days' => 20,
                'tags' => ['content', 'backup', 'verification'],
                'requirements' => 'Content updates, backup verification, documentation updates',
                'dependencies' => null,
                'progress' => rand(0, 70),
            ],
        ];
    }

    private function getMarketingTasks(Project $project): array
    {
        return [
            [
                'title' => 'SEO Audit & Keyword Research',
                'description' => 'Conduct comprehensive SEO audit and keyword research',
                'category' => 'SEO',
                'priority' => 'high',
                'status' => 'completed',
                'estimated_hours' => 16,
                'actual_hours' => 18,
                'deadline_days' => 7,
                'tags' => ['seo', 'audit', 'keywords'],
                'requirements' => 'Technical SEO audit, keyword research, competitor analysis',
                'dependencies' => null,
                'progress' => 100,
            ],
            [
                'title' => 'On-Page SEO Optimization',
                'description' => 'Optimize website pages for target keywords',
                'category' => 'SEO',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'review']),
                'estimated_hours' => 32,
                'actual_hours' => rand(20, 35),
                'deadline_days' => 21,
                'tags' => ['on-page', 'optimization', 'content'],
                'requirements' => 'Meta tags optimization, content optimization, internal linking',
                'dependencies' => 'SEO Audit & Keyword Research',
                'progress' => rand(50, 85),
            ],
            [
                'title' => 'Performance Tracking Setup',
                'description' => 'Set up analytics and tracking for SEO performance',
                'category' => 'Analytics',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'estimated_hours' => 12,
                'actual_hours' => rand(0, 10),
                'deadline_days' => 30,
                'tags' => ['analytics', 'tracking', 'performance'],
                'requirements' => 'Google Analytics setup, Search Console setup, reporting dashboard',
                'dependencies' => 'On-Page SEO Optimization',
                'progress' => rand(0, 60),
            ],
        ];
    }

    private function getGeneralTasks(Project $project): array
    {
        // Calculate budget allocation based on project budget
        $projectBudget = $project->budget ?? 5000;
        $taskBudgets = [
            $projectBudget * 0.20, // 20% for requirements
            $projectBudget * 0.60, // 60% for development
            $projectBudget * 0.20, // 20% for testing
        ];

        return [
            [
                'title' => 'Project Requirements Analysis',
                'description' => 'Analyze and document project requirements in detail, including stakeholder interviews, scope definition, and technical specifications.',
                'priority' => 'high',
                'status' => 'completed',
                'budget' => round($taskBudgets[0], 2),
                'actual_cost' => round($taskBudgets[0] * rand(90, 110) / 100, 2),
                'deadline_days' => 5,
                'progress' => 100,
                'notes' => 'Requirements documentation completed with stakeholder approval',
            ],
            [
                'title' => 'Implementation & Development',
                'description' => 'Main development work for the project including coding, feature implementation, and integration with required systems.',
                'priority' => 'high',
                'status' => $this->getRandomStatus(['in_progress', 'completed']),
                'budget' => round($taskBudgets[1], 2),
                'actual_cost' => $this->getRandomStatus(['in_progress', 'completed']) === 'completed' 
                    ? round($taskBudgets[1] * rand(90, 110) / 100, 2) 
                    : round($taskBudgets[1] * rand(40, 70) / 100, 2),
                'deadline_days' => 30,
                'progress' => rand(40, 100),
                'notes' => 'Core development in progress with regular client updates',
            ],
            [
                'title' => 'Testing & Quality Assurance',
                'description' => 'Comprehensive testing including unit tests, integration tests, and end-to-end quality assurance to ensure project meets requirements.',
                'priority' => 'medium',
                'status' => $this->getRandomStatus(['pending', 'in_progress']),
                'budget' => round($taskBudgets[2], 2),
                'actual_cost' => $this->getRandomStatus(['pending', 'in_progress']) === 'in_progress' 
                    ? round($taskBudgets[2] * rand(20, 50) / 100, 2) 
                    : 0,
                'deadline_days' => 35,
                'progress' => rand(0, 60),
                'notes' => 'QA testing planned after development completion',
            ],
        ];
    }

    private function getAdiutorForTask(Project $project, $adiutors, ?string $taskCategory = null): User
    {
        // First try to get an adiutor already assigned to this project
        $projectAdiutors = $project->adiutors;
        
        if ($projectAdiutors && $projectAdiutors->isNotEmpty()) {
            // Return random project adiutor
            return $projectAdiutors->random();
        }
        
        // Fallback to random adiutor
        return $adiutors->random();
    }

    private function adiutorHasSkillForCategory(User $adiutor, string $category): bool
    {
        $skillMaps = [
            'Frontend' => ['HTML/CSS', 'JavaScript', 'React', 'Vue.js', 'Responsive Design'],
            'Backend' => ['PHP', 'Python', 'Node.js', 'API Development', 'Laravel', 'Django'],
            'Database' => ['MySQL', 'PostgreSQL', 'Database Design'],
            'Design' => ['UI Design', 'UX Research', 'Figma', 'Adobe Creative Suite'],
            'Mobile' => ['React Native', 'Flutter', 'Mobile UI/UX'],
            'Security' => ['Security', 'Authentication'],
            'Testing' => ['Testing', 'QA'],
            'SEO' => ['SEO', 'Content Marketing'],
        ];

        $requiredSkills = $skillMaps[$category] ?? [];
        
        foreach ($requiredSkills as $skillName) {
            if ($adiutor->skills()->where('name', $skillName)->exists()) {
                return true;
            }
        }
        
        return false;
    }

    private function getRandomStatus(array $options = null): string
    {
        $statuses = $options ?? ['pending', 'in_progress', 'review', 'completed'];
        return $statuses[array_rand($statuses)];
    }
}