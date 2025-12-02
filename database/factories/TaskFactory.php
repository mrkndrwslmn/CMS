<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common task titles by category
        $taskCategories = [
            'Design' => [
                'Create logo design',
                'Design website mockup',
                'Design mobile app UI',
                'Create social media graphics',
                'Design business cards',
                'Create brand style guide',
                'Design email newsletter template',
                'Create promotional banners'
            ],
            'Development' => [
                'Develop landing page',
                'Implement user authentication',
                'Set up database structure',
                'Create API endpoints',
                'Integrate payment gateway',
                'Fix responsive design issues',
                'Implement search functionality',
                'Set up automated testing'
            ],
            'Content' => [
                'Write website content',
                'Create blog articles',
                'Develop product descriptions',
                'Create technical documentation',
                'Write email campaign copy',
                'Proofread existing content',
                'Create video script',
                'Write press release'
            ],
            'Marketing' => [
                'Set up Google Analytics',
                'Configure SEO settings',
                'Create social media strategy',
                'Set up email marketing campaign',
                'Perform keyword research',
                'Set up Google Ads campaign',
                'Create marketing reports',
                'Analyze competitor strategies'
            ],
            'Administrative' => [
                'Client onboarding',
                'Project planning',
                'Budget preparation',
                'Contract review',
                'Client meeting preparation',
                'Resource allocation',
                'Timeline scheduling',
                'Project documentation'
            ]
        ];

        // Task description templates
        $taskDescriptionTemplates = [
            '%s for the %s project. This task should follow the provided guidelines and be completed according to the project specifications.',
            
            '%s that aligns with the client\'s brand identity. The deliverable should be professional and meet all specified requirements.',
            
            '%s with attention to detail and following industry best practices. This is a critical component of the overall project.',
            
            '%s based on the client\'s requirements document. Make sure to communicate any questions or clarifications needed.',
            
            '%s and ensure it integrates properly with existing systems. Testing should be performed before delivery.',
            
            '%s according to the project timeline. This task is high priority and should be given immediate attention.',
            
            '%s following the design specifications. The output should be responsive and optimized for all devices.',
            
            '%s while maintaining the highest quality standards. Regular updates on progress should be provided.'
        ];

        // Notes templates
        $notesTemplates = [
            'Client has emphasized that brand colors must be strictly followed.',
            'Previous version was rejected due to performance issues.',
            'Client has provided reference examples in the shared folder.',
            'This is a repeat client, check previous project history for preferences.',
            'The deadline is firm due to upcoming product launch.',
            'Coordinate with the marketing team for consistent messaging.',
            'Client prefers minimalist design with focus on functionality.',
            'Extra attention needed for accessibility compliance.',
            'Budget constraints require cost-effective solutions.',
            'This is part of a larger project with interdependent tasks.',
            null, // Some tasks might not have notes
        ];

        // Select random category and task title
        $category = fake()->randomElement(array_keys($taskCategories));
        $taskTitle = fake()->randomElement($taskCategories[$category]);

        // Generate task description
        $projectContext = fake()->company() . ' ' . fake()->randomElement(['website', 'app', 'marketing campaign', 'branding project', 'system']);
        $taskDescription = sprintf(
            fake()->randomElement($taskDescriptionTemplates),
            $taskTitle,
            $projectContext
        );

        // Generate dates
        $dateAssigned = fake()->dateTimeBetween('-30 days', 'now');
        $deadline = fake()->dateTimeBetween($dateAssigned, '+30 days');
        
        // Determine if task is completed
        $status = fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']);
        $completedAt = ($status === 'completed') ? fake()->dateTimeBetween($dateAssigned, $deadline) : null;

        return [
            'project_id' => Project::factory(),
            'client_id' => User::factory()->client(),
            'createdBy' => User::factory()->admin(),
            'assignedTo' => User::factory()->adiutor(),
            'taskTitle' => $taskTitle,
            'taskDescription' => $taskDescription,
            'status' => $status,
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'deadline' => $deadline,
            'completedAt' => $completedAt,
            'notes' => fake()->randomElement($notesTemplates),
            'dateAssigned' => $dateAssigned,
            'allocated_budget' => fake()->randomFloat(2, 500, 5000),
            'progress_percentage' => $status === 'completed' ? 100 : fake()->numberBetween(0, 90),
            'hourly_rate' => fake()->randomElement([200, 300, 400, 500, 750]),
            'requires_time_tracking' => fake()->boolean(70),
            'total_hours_tracked' => 0,
            'calculated_earnings' => 0,
            'use_fixed_budget' => fake()->boolean(30),
        ];
    }

    /**
     * Configure the factory to create a pending task.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'completedAt' => null,
        ]);
    }

    /**
     * Configure the factory to create an in-progress task.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'completedAt' => null,
        ]);
    }

    /**
     * Configure the factory to create a completed task.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completedAt' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Configure the factory to create a high priority task.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
            'deadline' => fake()->dateTimeBetween('now', '+14 days'),
        ]);
    }

    /**
     * Configure the factory to create an overdue task.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => fake()->randomElement(['pending', 'in_progress']),
            'deadline' => fake()->dateTimeBetween('-30 days', '-1 days'),
            'completedAt' => null,
        ]);
    }

    /**
     * For a specific project
     */
    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes) => [
            'project_id' => $project->id,
            'client_id' => $project->client_id,
        ]);
    }

    /**
     * Assigned to specific adiutor
     */
    public function assignedTo(User $adiutor): static
    {
        return $this->state(fn (array $attributes) => [
            'assignedTo' => $adiutor->id,
        ]);
    }

    /**
     * With time tracking enabled
     */
    public function withTimeTracking(): static
    {
        return $this->state(fn (array $attributes) => [
            'requires_time_tracking' => true,
            'hourly_rate' => fake()->randomElement([300, 400, 500, 750, 1000]),
        ]);
    }

    /**
     * With fixed budget
     */
    public function withFixedBudget(float $amount = null): static
    {
        return $this->state(fn (array $attributes) => [
            'use_fixed_budget' => true,
            'allocated_budget' => $amount ?? fake()->randomFloat(2, 1000, 10000),
            'requires_time_tracking' => false,
        ]);
    }

    /**
     * Urgent priority
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
            'deadline' => fake()->dateTimeBetween('+1 day', '+1 week'),
        ]);
    }
}