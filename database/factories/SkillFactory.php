<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Skill::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define categories and skills per category
        $skillsByCategory = [
            'Programming' => [
                'PHP', 'JavaScript', 'Python', 'Java', 'C#', 'Ruby', 'Swift', 
                'TypeScript', 'Go', 'Rust', 'Kotlin', 'C++', 'C', 'Scala',
                'Laravel', 'React', 'Vue.js', 'Angular', 'Django', 'Spring Boot',
                'Express.js', 'ASP.NET', 'Ruby on Rails', 'Flask', 'Node.js'
            ],
            'Database' => [
                'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 'Oracle', 'SQL_Server',
                'Redis', 'Elasticsearch', 'Firebase', 'DynamoDB', 'Cassandra',
                'Database Design', 'SQL', 'NoSQL'
            ],
            'Design' => [
                'UI/UX Design', 'Graphic Design', 'Web Design', 'Logo Design',
                'Wireframing', 'Prototyping', 'Figma', 'Adobe Photoshop',
                'Adobe Illustrator', 'Sketch', 'Adobe XD'
            ],
            'DevOps' => [
                'Docker', 'Kubernetes', 'Jenkins', 'CI/CD', 'AWS', 'Azure',
                'Google Cloud', 'Linux Administration', 'Terraform', 'Ansible',
                'Prometheus', 'Grafana', 'Git', 'GitHub Actions'
            ],
            'Mobile' => [
                'Android Development', 'iOS Development', 'React Native', 
                'Flutter', 'Xamarin', 'Ionic', 'Swift', 'Kotlin', 'Mobile UI Design'
            ],
            'Content' => [
                'Copywriting', 'Content Writing', 'Technical Writing',
                'Editing', 'Proofreading', 'SEO Writing', 'Blog Writing',
                'Article Writing', 'Creative Writing'
            ],
            'Marketing' => [
                'SEO', 'SEM', 'Social Media Marketing', 'Content Marketing',
                'Email Marketing', 'Google Ads', 'Facebook Ads', 'Analytics',
                'Digital Marketing Strategy', 'Influencer Marketing'
            ],
            'Business' => [
                'Project Management', 'Business Analysis', 'Agile', 'Scrum',
                'Requirements Gathering', 'User Stories', 'Product Management',
                'JIRA', 'Confluence', 'Business Strategy'
            ],
        ];

        // Randomly select a category and a skill from that category
        $category = fake()->randomElement(array_keys($skillsByCategory));
        $skillName = fake()->randomElement($skillsByCategory[$category]);
        
        // Make the skill name unique by appending a random number if needed
        $random = fake()->randomNumber(3);
        $skillName = $skillName . ' ' . fake()->randomElement(['Expert', 'Specialist', 'Professional', 'Master', 'Guru']);

        // Icon classes based on Font Awesome for common skills
        $iconMap = [
            'PHP' => 'fab fa-php',
            'JavaScript' => 'fab fa-js',
            'Python' => 'fab fa-python',
            'Java' => 'fab fa-java',
            'React' => 'fab fa-react',
            'Vue.js' => 'fab fa-vuejs',
            'Angular' => 'fab fa-angular',
            'Node.js' => 'fab fa-node-js',
            'Git' => 'fab fa-git-alt',
            'AWS' => 'fab fa-aws',
            'Docker' => 'fab fa-docker',
            'Linux' => 'fab fa-linux',
            // Default icon for other skills
            'default' => 'fas fa-code',
        ];

        $icon = $iconMap[$skillName] ?? $iconMap['default'];

        return [
            'name' => $skillName,
            'category' => $category,
            'icon' => $icon,
            'is_active' => true,
        ];
    }

    /**
     * Configure the factory to create an inactive skill.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Configure the factory to create a skill with a specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }
}