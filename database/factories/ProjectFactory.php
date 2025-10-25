<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Project titles by type
        $projectTypes = [
            'Website' => [
                'E-commerce Website Development',
                'Corporate Website Redesign',
                'Landing Page Design',
                'Web Portal Development',
                'Blog Website Creation',
                'Website Performance Optimization',
                'Website Migration',
                'Responsive Website Design'
            ],
            'Mobile App' => [
                'iOS App Development',
                'Android App Development',
                'Cross-platform Mobile App',
                'Mobile App UI/UX Design',
                'App Performance Optimization',
                'Mobile App Testing'
            ],
            'Design' => [
                'Brand Identity Design',
                'Logo Design Package',
                'UI/UX Design for App',
                'Marketing Material Design',
                'Product Packaging Design',
                'Infographic Design'
            ],
            'Marketing' => [
                'Digital Marketing Campaign',
                'SEO Optimization',
                'Social Media Management',
                'Content Marketing Strategy',
                'Email Marketing Automation',
                'Google Ads Campaign'
            ],
            'Content' => [
                'Website Content Writing',
                'Technical Documentation',
                'Blog Content Creation',
                'Product Description Writing',
                'Whitepaper Creation',
                'Case Study Development'
            ],
            'Backend' => [
                'API Development',
                'Database Design & Implementation',
                'Backend System Optimization',
                'Authentication System',
                'Payment Gateway Integration',
                'Cloud Migration'
            ],
            'Other' => [
                'IT Consulting Services',
                'Business Process Automation',
                'Software Quality Assurance',
                'Data Analysis & Reporting',
                'Custom Software Development',
                'Legacy System Maintenance'
            ]
        ];

        // Random project type and title
        $projectType = fake()->randomElement(array_keys($projectTypes));
        $title = fake()->randomElement($projectTypes[$projectType]);

        // Deadline between now and 3 months in the future
        $deadline = fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d');

        // Create sample requirements based on project type
        $requirementTemplates = [
            'Website' => [
                'Responsive design that works on all devices',
                'Admin panel for content management',
                'SEO optimization for search engines',
                'Contact form with validation',
                'Integration with payment gateway',
                'User account management',
                'Product catalog management',
                'Blog section with categories',
                'Multilingual support',
                'Performance optimization',
                'Cross-browser compatibility',
                'Google Analytics integration'
            ],
            'Mobile App' => [
                'User registration and authentication',
                'Push notifications system',
                'In-app purchase capabilities',
                'Offline functionality',
                'Social media integration',
                'User profile management',
                'Location-based services',
                'Analytics tracking',
                'App store optimization',
                'Secure data storage'
            ],
            'Design' => [
                'Brand guidelines document',
                'Multiple logo variations',
                'Social media templates',
                'Business card design',
                'Letterhead design',
                'Email signature design',
                'High-resolution files in multiple formats',
                'Style guide with color palette'
            ],
            'Marketing' => [
                'Keyword research and strategy',
                'Competitor analysis',
                'Monthly reporting',
                'A/B testing',
                'Campaign performance tracking',
                'Content calendar',
                'Social media strategy',
                'Lead generation tracking'
            ],
            'Content' => [
                'SEO optimization for all content',
                'Original content with plagiarism check',
                'Proper formatting and structure',
                'Research on industry topics',
                'Regular content updates',
                'Content strategy documentation',
                'Target audience analysis'
            ],
            'Backend' => [
                'Secure API development',
                'Database optimization',
                'Performance benchmarking',
                'Thorough documentation',
                'Unit and integration tests',
                'Scalable architecture',
                'Authentication and authorization',
                'Rate limiting and security measures'
            ],
            'Other' => [
                'Regular progress reports',
                'Knowledge transfer sessions',
                'Documentation of all processes',
                'Post-implementation support',
                'Training materials',
                'System integration planning',
                'Risk assessment documentation'
            ]
        ];

        // Select 4-8 random requirements from the appropriate template
        $requirements = $requirementTemplates[$projectType] ?? $requirementTemplates['Other'];
        shuffle($requirements);
        $selectedRequirements = array_slice($requirements, 0, fake()->numberBetween(4, 8));

        // Get random skills based on project type
        $skillsByProjectType = [
            'Website' => ['PHP', 'JavaScript', 'HTML', 'CSS', 'React', 'Vue.js', 'WordPress', 'Laravel', 'MySQL'],
            'Mobile App' => ['Swift', 'Kotlin', 'React Native', 'Flutter', 'Java', 'Firebase', 'iOS Development', 'Android Development'],
            'Design' => ['UI/UX Design', 'Graphic Design', 'Logo Design', 'Adobe Photoshop', 'Adobe Illustrator', 'Figma'],
            'Marketing' => ['SEO', 'SEM', 'Content Marketing', 'Social Media Marketing', 'Google Ads', 'Email Marketing'],
            'Content' => ['Copywriting', 'Content Writing', 'Technical Writing', 'Editing', 'Proofreading', 'SEO Writing'],
            'Backend' => ['PHP', 'Python', 'Java', 'Node.js', 'SQL', 'MongoDB', 'API Development', 'DevOps'],
            'Other' => ['Project Management', 'Business Analysis', 'Quality Assurance', 'Data Analysis', 'Technical Support']
        ];

        $skillsForProject = $skillsByProjectType[$projectType] ?? $skillsByProjectType['Other'];
        shuffle($skillsForProject);
        $selectedSkills = array_slice($skillsForProject, 0, fake()->numberBetween(3, 6));

        return [
            'title' => $title,
            'description' => fake()->paragraph(3),
            'requirements' => json_encode($selectedRequirements),
            'skills_required' => json_encode($selectedSkills),
            'budget' => fake()->randomFloat(2, 500, 10000),
            'budget_type' => fake()->randomElement(['fixed', 'hourly']),
            'deadline' => $deadline,
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['draft', 'open', 'assigned', 'in_progress', 'review', 'completed', 'cancelled']),
            'attachments' => null,
        ];
    }

    /**
     * Configure the factory to create a high-priority project.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }

    /**
     * Configure the factory to create an urgent priority project.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
            'deadline' => now()->addDays(fake()->numberBetween(3, 14))->format('Y-m-d'),
        ]);
    }

    /**
     * Configure the factory to create a completed project.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'deadline' => now()->subDays(fake()->numberBetween(1, 30))->format('Y-m-d'),
        ]);
    }

    /**
     * Configure the factory to create an in-progress project.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Configure the factory to create a large budget project.
     */
    public function largeBudget(): static
    {
        return $this->state(fn (array $attributes) => [
            'budget' => fake()->randomFloat(2, 10000, 50000),
        ]);
    }
}