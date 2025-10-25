<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Business types
        $businessTypes = [
            'E-commerce', 'Technology', 'Education', 'Healthcare', 'Finance',
            'Manufacturing', 'Retail', 'Real Estate', 'Hospitality', 'Legal Services',
            'Consulting', 'Marketing Agency', 'Media & Entertainment', 'Non-profit',
            'Food & Beverage', 'Fitness & Wellness', 'Travel & Tourism', 'Automotive'
        ];

        // Project description templates
        $projectDescriptionTemplates = [
            'We need to create a new website for our %s business. The site should highlight our services, provide a way for customers to contact us, and include testimonials from satisfied clients.',
            
            'Our %s company needs to revamp our online presence. We\'re looking for a complete redesign of our website with improved functionality and modern design.',
            
            'As a %s business, we need a custom web application that can help us manage our client relationships, track projects, and handle billing in one integrated system.',
            
            'We need an e-commerce solution for our %s business to sell our products online. The site should include product listings, shopping cart, payment processing, and order management.',
            
            'Our %s company is looking to improve our digital marketing strategy. We need help with SEO optimization, content creation, and social media management to increase our online visibility.',
            
            'We require a mobile app for our %s business that complements our existing web platform. The app should allow customers to access their accounts, place orders, and receive notifications.',
            
            'Our %s organization needs a content management system that allows multiple team members to update content, publish blog posts, and manage media files without technical knowledge.',
            
            'We\'re a startup in the %s industry looking to establish our brand identity. We need logo design, brand guidelines, business cards, and other marketing materials.',
            
            'As a growing %s business, we need to implement a CRM system to better manage our customer relationships, track leads, and improve our sales process.',
            
            'Our %s company requires technical documentation for our products, including user guides, API documentation, and internal process documentation for our team.'
        ];

        // Special requests templates
        $specialRequestsTemplates = [
            'We need this project completed as soon as possible, preferably within the next 4 weeks.',
            'We have a limited budget for this project, so please provide cost-effective solutions.',
            'We would like to have weekly progress updates throughout the project duration.',
            'The solution must be fully mobile-responsive and work well on all devices.',
            'We require comprehensive training for our staff once the project is completed.',
            'Security is a top priority for us, so please ensure all best practices are followed.',
            'We need the system to integrate with our existing tools like %s.',
            'We would like to have ongoing maintenance and support after the initial project is completed.',
            'Please ensure the solution is accessible and complies with WCAG guidelines.',
            'We\'re looking for a long-term partnership, so this initial project may lead to future work.',
            null,  // Some forms might not have special requests
        ];

        // Integration tools for special requests
        $integrationTools = [
            'Salesforce', 'HubSpot', 'QuickBooks', 'Shopify', 'WordPress', 
            'Mailchimp', 'Slack', 'Google Workspace', 'Microsoft 365',
            'Asana', 'Trello', 'Jira', 'Zendesk', 'PayPal', 'Stripe'
        ];

        // Generate submission date (between 7 days ago and 60 days ago)
        $submissionDate = fake()->dateTimeBetween('-60 days', '-1 days');
        
        // Generate deadline (between 7 days and 90 days after submission date)
        $deadline = fake()->dateTimeBetween($submissionDate, '+90 days');
        
        // Get a random business type
        $businessType = fake()->randomElement($businessTypes);
        
        // Generate project description based on the business type
        $projectDescription = sprintf(
            fake()->randomElement($projectDescriptionTemplates),
            $businessType
        );
        
        // Generate special requests
        $specialRequest = fake()->randomElement($specialRequestsTemplates);
        if ($specialRequest && strpos($specialRequest, '%s') !== false) {
            $specialRequest = sprintf($specialRequest, fake()->randomElement($integrationTools));
        }

        return [
            'client_id' => null, // This will be set by the seeder
            'companyName' => fake()->company(),
            'businessType' => $businessType,
            'deadline' => $deadline,
            'projectDescription' => $projectDescription,
            'specialRequests' => $specialRequest,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'submissionDate' => $submissionDate,
        ];
    }

    /**
     * Configure the factory to create a pending form.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'submissionDate' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Configure the factory to create an approved form.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Configure the factory to create a rejected form.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Configure the factory to create a form with an urgent deadline.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'deadline' => fake()->dateTimeBetween('now', '+14 days'),
            'specialRequests' => 'This is an urgent request. We need this completed as soon as possible due to business requirements.',
        ]);
    }
}