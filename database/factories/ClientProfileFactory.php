<?php

namespace Database\Factories;

use App\Models\ClientProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientProfile>
 */
class ClientProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common company names and suffixes
        $companySuffixes = ['Inc.', 'LLC', 'Ltd.', 'Group', 'Co.', 'Solutions', 'Partners', 'Technologies', 'Innovations'];
        $companyTypes = ['Tech', 'Digital', 'Media', 'Creative', 'Design', 'Software', 'Data', 'Global', 'Insight', 'Logic', 'Smart', 'Web'];
        
        // Generate company name
        $companyName = fake()->optional(0.8, function () use ($companyTypes, $companySuffixes) {
            return fake()->randomElement($companyTypes) . ' ' . fake()->word() . ' ' . fake()->randomElement($companySuffixes);
        })->company();

        // Industry options
        $industries = [
            'Technology', 'Marketing', 'Healthcare', 'Education', 'Finance',
            'E-commerce', 'Entertainment', 'Real Estate', 'Manufacturing',
            'Retail', 'Food & Beverage', 'Consulting', 'Legal Services',
            'Travel & Tourism', 'Automotive', 'Non-Profit'
        ];

        // Company sizes
        $companySizes = [
            'Startup (1-10)',
            'Small (11-50)',
            'Medium (51-200)',
            'Large (201-500)',
            'Enterprise (500+)'
        ];

        // Contact methods
        $contactMethods = [
            'email' => fake()->boolean(90),
            'phone' => fake()->boolean(60),
            'video_call' => fake()->boolean(50),
            'messaging_app' => fake()->boolean(40),
        ];

        // Business hours format
        $businessHours = [
            'monday' => ['start' => '09:00', 'end' => '17:00'],
            'tuesday' => ['start' => '09:00', 'end' => '17:00'],
            'wednesday' => ['start' => '09:00', 'end' => '17:00'],
            'thursday' => ['start' => '09:00', 'end' => '17:00'],
            'friday' => ['start' => '09:00', 'end' => '17:00'],
            'saturday' => fake()->boolean(30) ? ['start' => '10:00', 'end' => '15:00'] : null,
            'sunday' => fake()->boolean(10) ? ['start' => '10:00', 'end' => '15:00'] : null,
        ];

        return [
            'company_name' => $companyName,
            'company_description' => fake()->paragraph(3),
            'industry' => fake()->randomElement($industries),
            'company_size' => fake()->randomElement($companySizes),
            'address' => fake()->address(),
            'website' => fake()->optional(0.8)->url(),
            'preferred_contact_methods' => $contactMethods,
            'timezone' => fake()->timezone(),
            'business_hours' => $businessHours,
            'notes' => fake()->optional(0.5)->paragraph(),
            'is_verified' => fake()->boolean(80),
            'total_projects' => fake()->numberBetween(0, 20),
            'total_spent' => fake()->randomFloat(2, 0, 50000),
            'client_type' => fake()->randomElement(['individual', 'small_business', 'enterprise']),
        ];
    }

    /**
     * Configure the factory to create a verified client profile.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
        ]);
    }

    /**
     * Configure the factory to create an individual client profile.
     */
    public function individual(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_type' => 'individual',
            'company_name' => null,
            'company_size' => null,
            'total_projects' => fake()->numberBetween(0, 5),
            'total_spent' => fake()->randomFloat(2, 0, 5000),
        ]);
    }

    /**
     * Configure the factory to create an enterprise client profile.
     */
    public function enterprise(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_type' => 'enterprise',
            'is_verified' => true,
            'company_size' => 'Enterprise (500+)',
            'total_projects' => fake()->numberBetween(5, 50),
            'total_spent' => fake()->randomFloat(2, 10000, 250000),
        ]);
    }
}