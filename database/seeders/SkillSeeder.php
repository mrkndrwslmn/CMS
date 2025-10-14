<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define predefined skills by category
        $skillsByCategory = [
            'Programming' => [
                ['name' => 'PHP', 'icon' => 'fab fa-php'],
                ['name' => 'JavaScript', 'icon' => 'fab fa-js'],
                ['name' => 'Python', 'icon' => 'fab fa-python'],
                ['name' => 'Java', 'icon' => 'fab fa-java'],
                ['name' => 'C#', 'icon' => 'fas fa-code'],
                ['name' => 'Ruby', 'icon' => 'fas fa-gem'],
                ['name' => 'Swift', 'icon' => 'fab fa-swift'],
                ['name' => 'TypeScript', 'icon' => 'fas fa-code'],
                ['name' => 'React', 'icon' => 'fab fa-react'],
                ['name' => 'Vue.js', 'icon' => 'fab fa-vuejs'],
                ['name' => 'Angular', 'icon' => 'fab fa-angular'],
                ['name' => 'Node.js', 'icon' => 'fab fa-node-js'],
                ['name' => 'Laravel', 'icon' => 'fab fa-laravel'],
            ],
            'Database' => [
                ['name' => 'MySQL', 'icon' => 'fas fa-database'],
                ['name' => 'PostgreSQL', 'icon' => 'fas fa-database'],
                ['name' => 'MongoDB', 'icon' => 'fas fa-database'],
                ['name' => 'SQLite', 'icon' => 'fas fa-database'],
                ['name' => 'Oracle', 'icon' => 'fas fa-database'],
                ['name' => 'SQL_Server', 'icon' => 'fas fa-database'],
            ],
            'Design' => [
                ['name' => 'UI/UX Design', 'icon' => 'fas fa-paint-brush'],
                ['name' => 'Graphic Design', 'icon' => 'fas fa-palette'],
                ['name' => 'Web Design', 'icon' => 'fas fa-desktop'],
                ['name' => 'Logo Design', 'icon' => 'far fa-object-group'],
                ['name' => 'Adobe Photoshop', 'icon' => 'fas fa-image'],
                ['name' => 'Adobe Illustrator', 'icon' => 'fas fa-bezier-curve'],
                ['name' => 'Figma', 'icon' => 'fas fa-pencil-ruler'],
            ],
            'DevOps' => [
                ['name' => 'Docker', 'icon' => 'fab fa-docker'],
                ['name' => 'Kubernetes', 'icon' => 'fas fa-dharmachakra'],
                ['name' => 'AWS', 'icon' => 'fab fa-aws'],
                ['name' => 'Azure', 'icon' => 'fab fa-microsoft'],
                ['name' => 'Google Cloud', 'icon' => 'fab fa-google'],
                ['name' => 'Linux', 'icon' => 'fab fa-linux'],
                ['name' => 'Git', 'icon' => 'fab fa-git-alt'],
            ],
            'Marketing' => [
                ['name' => 'SEO', 'icon' => 'fas fa-search'],
                ['name' => 'Social Media Marketing', 'icon' => 'fas fa-hashtag'],
                ['name' => 'Content Marketing', 'icon' => 'fas fa-newspaper'],
                ['name' => 'Email Marketing', 'icon' => 'fas fa-envelope'],
                ['name' => 'Google Ads', 'icon' => 'fab fa-google'],
                ['name' => 'Analytics', 'icon' => 'fas fa-chart-line'],
            ],
            'Content' => [
                ['name' => 'Copywriting', 'icon' => 'fas fa-pen'],
                ['name' => 'Content Writing', 'icon' => 'fas fa-file-alt'],
                ['name' => 'Technical Writing', 'icon' => 'fas fa-book'],
                ['name' => 'Editing', 'icon' => 'fas fa-edit'],
                ['name' => 'Proofreading', 'icon' => 'fas fa-check-double'],
                ['name' => 'SEO Writing', 'icon' => 'fas fa-keyboard'],
            ],
            'Business' => [
                ['name' => 'Project Management', 'icon' => 'fas fa-tasks'],
                ['name' => 'Business Analysis', 'icon' => 'fas fa-chart-bar'],
                ['name' => 'Agile', 'icon' => 'fas fa-sync'],
                ['name' => 'Scrum', 'icon' => 'fas fa-users-cog'],
                ['name' => 'JIRA', 'icon' => 'fab fa-jira'],
                ['name' => 'Product Management', 'icon' => 'fas fa-clipboard-list'],
            ],
        ];

        // Create predefined skills
        foreach ($skillsByCategory as $category => $skills) {
            foreach ($skills as $skill) {
                // Skip if skill already exists to avoid unique constraint violation
                if (!Skill::where('name', $skill['name'])->exists()) {
                    Skill::create([
                        'name' => $skill['name'],
                        'category' => $category,
                        'is_active' => true,
                    ]);
                }
            }
        }
        
        $this->command->info('Created skills across ' . count($skillsByCategory) . ' categories');
    }
}