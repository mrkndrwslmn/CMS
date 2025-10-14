<?php

namespace Database\Factories;

use App\Models\AdiutorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdiutorProfile>
 */
class AdiutorProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdiutorProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Professional titles
        $professionalTitles = [
            'Full Stack Developer', 'Web Designer', 'UX/UI Designer', 
            'Backend Developer', 'Frontend Developer', 'DevOps Engineer',
            'Data Scientist', 'Mobile App Developer', 'Software Engineer',
            'Database Administrator', 'Technical Writer', 'Project Manager',
            'WordPress Developer', 'SEO Specialist', 'Content Strategist',
            'Digital Marketer', 'Graphic Designer', 'UI/UX Researcher',
            'Cloud Engineer', 'AI Specialist', 'Machine Learning Engineer',
            'QA Specialist', 'System Administrator'
        ];

        // Common languages with native speaker levels
        $languages = [
            'English' => ['Native', 'Fluent', 'Professional', 'Conversational'],
            'Spanish' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'French' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'German' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'Chinese' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'Japanese' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'Portuguese' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'Russian' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'Arabic' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
            'Hindi' => ['Native', 'Fluent', 'Professional', 'Conversational', 'Basic'],
        ];

        // Generate 2-4 spoken languages with proficiency levels
        $userLanguages = [];
        $languageKeys = array_keys($languages);
        shuffle($languageKeys);
        $selectedLanguages = array_slice($languageKeys, 0, fake()->numberBetween(1, 4));
        
        foreach ($selectedLanguages as $language) {
            $userLanguages[$language] = fake()->randomElement($languages[$language]);
        }

        // Ensure at least one language is fluent or native
        if (!in_array('Native', $userLanguages) && !in_array('Fluent', $userLanguages)) {
            $randomLang = $selectedLanguages[0];
            $userLanguages[$randomLang] = fake()->randomElement(['Native', 'Fluent']);
        }

        // Generate availability data
        $availabilityData = [
            'hours_per_week' => fake()->randomElement([10, 20, 30, 40]),
            'preferred_hours' => fake()->randomElement(['Morning', 'Afternoon', 'Evening', 'Flexible']),
            'timezone' => fake()->timezone,
            'available_weekends' => fake()->boolean(30),
            'notice_period' => fake()->randomElement(['24 hours', '48 hours', '1 week', 'Immediate']),
        ];

        return [
            'bio' => fake()->paragraph(3),
            'title' => fake()->randomElement($professionalTitles),
            'hourly_rate' => fake()->randomFloat(2, 15, 150),
            'availability' => $availabilityData,
            'portfolio_url' => fake()->optional(0.7)->url(),
            'linkedin_url' => fake()->optional(0.8)->url(),
            'github_url' => fake()->optional(0.6)->url(),
            'experience' => fake()->paragraph(2),
            'languages' => $userLanguages,
            'location' => fake()->city() . ', ' . fake()->country(),
            'is_verified' => fake()->boolean(70),
            'rating' => fake()->randomFloat(2, 3.0, 5.0),
            'total_projects' => fake()->numberBetween(0, 50),
            'status' => fake()->randomElement(['active', 'busy', 'inactive']),
        ];
    }

    /**
     * Configure the factory to create a highly experienced profile.
     */
    public function expert(): static
    {
        return $this->state(fn (array $attributes) => [
            'hourly_rate' => fake()->randomFloat(2, 80, 200),
            'rating' => fake()->randomFloat(2, 4.5, 5.0),
            'total_projects' => fake()->numberBetween(20, 100),
            'is_verified' => true,
        ]);
    }

    /**
     * Configure the factory to create a beginner profile.
     */
    public function beginner(): static
    {
        return $this->state(fn (array $attributes) => [
            'hourly_rate' => fake()->randomFloat(2, 10, 30),
            'rating' => fake()->randomFloat(2, 0, 4.0),
            'total_projects' => fake()->numberBetween(0, 5),
            'is_verified' => fake()->boolean(30),
        ]);
    }

    /**
     * Configure the factory to create a busy profile.
     */
    public function busy(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'busy',
        ]);
    }
}