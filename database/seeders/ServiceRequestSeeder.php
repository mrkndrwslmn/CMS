<?php

namespace Database\Seeders;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all client users
        $clients = User::where('role', 'client')->get();
        
        if ($clients->isEmpty()) {
            $this->command->warn('No client users found. Please run UserSeeder first.');
            return;
        }

        $serviceRequests = [
            [
                'client_id' => $clients->where('email', 'john.smith@techstartup.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'john.smith@techstartup.com',
                'service_type' => 'Custom Website Development',
                'project_name' => 'TechStartup Corporate Website',
                'request_description' => 'We need a modern, professional website for our AI startup that showcases our products, team, and company culture. The site should be responsive, fast-loading, and include a contact form, blog section, and integration with our CRM system.',
                'expectations' => 'Professional website with CRM integration, responsive design, and analytics dashboard',
                'additional_notes' => 'Target audience: Small business owners, tech entrepreneurs, and potential investors',
                'requirements' => json_encode([
                    'hubspot_integration' => true,
                    'lead_capture_forms' => true,
                    'analytics_dashboard' => true,
                    'responsive_design' => true,
                    'blog_section' => true
                ]),
                'estimated_budget' => 6500.00,
                'deadline' => now()->addDays(60),
                'priority' => 'high',
                'status' => 'pending',
                'created_at' => now()->subDays(2),
            ],
            [
                'client_id' => $clients->where('email', 'maria@designstudio.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'maria@designstudio.com',
                'service_type' => 'Brand Identity Design',
                'project_name' => 'Complete Brand Identity Package',
                'request_description' => 'Complete rebrand for our design studio including new logo, color palette, typography, business cards, letterhead, and brand guidelines. We want a modern, creative identity that reflects our innovative approach to design.',
                'expectations' => 'Modern, creative brand identity package with comprehensive style guide',
                'additional_notes' => 'Target audience: Creative professionals, small businesses, and marketing agencies',
                'requirements' => json_encode([
                    'logo_variations' => true,
                    'color_palette' => true,
                    'typography_guide' => true,
                    'business_cards' => true,
                    'letterhead' => true,
                    'social_media_templates' => true,
                    'brand_guidelines' => true
                ]),
                'estimated_budget' => 3250.00,
                'approved_budget' => 3250.00,
                'deadline' => now()->addDays(30),
                'priority' => 'medium',
                'status' => 'approved',
                'approved_at' => now()->subDays(1),
                'approved_by' => 1, // Admin user
                'created_at' => now()->subDays(5),
            ],
            [
                'client_id' => $clients->where('email', 'david@ecommerceco.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'david@ecommerceco.com',
                'service_type' => 'E-commerce Platform Development',
                'project_name' => 'Multi-vendor E-commerce Platform',
                'request_description' => 'Custom e-commerce platform that allows multiple vendors to sell their products. Features needed include vendor registration, product management, order processing, payment integration (Stripe/PayPal), inventory tracking, and admin dashboard.',
                'expectations' => 'Full-featured multi-vendor e-commerce platform with advanced reporting and mobile-responsive design',
                'additional_notes' => 'Target audience: Multiple vendors and their customers across various product categories',
                'requirements' => json_encode([
                    'multi_vendor' => true,
                    'vendor_registration' => true,
                    'product_management' => true,
                    'order_processing' => true,
                    'payment_integration' => ['stripe', 'paypal'],
                    'inventory_tracking' => true,
                    'admin_dashboard' => true,
                    'commission_tracking' => true,
                    'automated_payouts' => true,
                    'advanced_reporting' => true,
                    'mobile_responsive' => true
                ]),
                'estimated_budget' => 20000.00,
                'deadline' => now()->addDays(120),
                'priority' => 'high',
                'status' => 'pending',
                'created_at' => now()->subDays(7),
            ],
            [
                'client_id' => $clients->where('email', 'lisa@nonprofithelp.org')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'lisa@nonprofithelp.org',
                'service_type' => 'WordPress Development',
                'project_name' => 'Nonprofit Website with Donation System',
                'request_description' => 'WordPress website for our nonprofit with donation functionality, volunteer registration, event management, and blog. Need to integrate with popular donation platforms and make it easy for visitors to get involved.',
                'expectations' => 'Complete WordPress website with donation system, volunteer management, and event calendar',
                'additional_notes' => 'Target audience: Community members, potential donors, volunteers, and grant organizations',
                'requirements' => json_encode([
                    'donation_platform' => true,
                    'volunteer_registration' => true,
                    'event_management' => true,
                    'blog_functionality' => true,
                    'event_calendar' => true,
                    'newsletter_signup' => true,
                    'wordpress_cms' => true
                ]),
                'estimated_budget' => 4000.00,
                'approved_budget' => 4000.00,
                'deadline' => now()->addDays(45),
                'priority' => 'medium',
                'status' => 'approved',
                'approved_at' => now()->subHours(12),
                'approved_by' => 1, // Admin user
                'created_at' => now()->subDays(3),
            ],
            [
                'client_id' => $clients->where('email', 'john.smith@techstartup.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'john.smith@techstartup.com',
                'service_type' => 'Mobile App Development',
                'project_name' => 'AI Assistant Mobile App',
                'request_description' => 'Cross-platform mobile app for our AI assistant service. Users should be able to interact with AI, save conversations, manage settings, and sync across devices. Integration with our existing API is required.',
                'expectations' => 'Full-featured mobile app with AI integration, real-time chat, and cross-device sync',
                'additional_notes' => 'Target audience: Tech-savvy professionals and students who need AI assistance',
                'requirements' => json_encode([
                    'cross_platform' => true,
                    'ai_integration' => true,
                    'real_time_chat' => true,
                    'voice_input_output' => true,
                    'offline_capability' => true,
                    'push_notifications' => true,
                    'api_integration' => true,
                    'conversation_sync' => true,
                    'user_settings' => true
                ]),
                'estimated_budget' => 21500.00,
                'deadline' => now()->addDays(150),
                'priority' => 'high',
                'status' => 'pending',
                'created_at' => now()->subDays(1),
            ],
            [
                'client_id' => $clients->where('email', 'maria@designstudio.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'maria@designstudio.com',
                'service_type' => 'UI/UX Design',
                'project_name' => 'Design Portfolio Web App',
                'request_description' => 'Modern web application to showcase our design portfolio with advanced filtering, project case studies, client testimonials, and contact forms. Should have smooth animations and excellent user experience.',
                'expectations' => 'Modern portfolio web app with interactive gallery and excellent UX',
                'additional_notes' => 'Target audience: Potential clients, design community, and business partners',
                'requirements' => json_encode([
                    'portfolio_gallery' => true,
                    'advanced_filtering' => true,
                    'case_studies' => true,
                    'client_testimonials' => true,
                    'contact_forms' => true,
                    'smooth_animations' => true,
                    'responsive_design' => true,
                    'project_brief_system' => true
                ]),
                'estimated_budget' => 8000.00,
                'deadline' => now()->addDays(75),
                'priority' => 'medium',
                'status' => 'rejected',
                'rejection_reason' => 'Budget constraints - client requested to resubmit with revised scope',
                'reviewed_at' => now()->subHours(8),
                'created_at' => now()->subDays(10),
            ],
            [
                'client_id' => $clients->where('email', 'david@ecommerceco.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'david@ecommerceco.com',
                'service_type' => 'API Development',
                'project_name' => 'E-commerce Integration API',
                'request_description' => 'REST API to integrate our e-commerce platform with various third-party services including inventory management, shipping providers, and accounting software. Need comprehensive documentation and testing.',
                'expectations' => 'Complete REST API with comprehensive documentation and testing',
                'additional_notes' => 'Target audience: Internal development team and third-party service providers',
                'requirements' => json_encode([
                    'restful_design' => true,
                    'comprehensive_docs' => true,
                    'rate_limiting' => true,
                    'authentication' => true,
                    'webhook_support' => true,
                    'inventory_integration' => true,
                    'shipping_integration' => true,
                    'accounting_integration' => true,
                    'testing_suite' => true
                ]),
                'estimated_budget' => 10000.00,
                'approved_budget' => 10000.00,
                'deadline' => now()->addDays(90),
                'priority' => 'medium',
                'status' => 'approved',
                'approved_at' => now()->subDays(2),
                'approved_by' => 2, // Second admin user
                'created_at' => now()->subDays(8),
            ],
            [
                'client_id' => $clients->where('email', 'lisa@nonprofithelp.org')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'lisa@nonprofithelp.org',
                'service_type' => 'Technical Consulting',
                'project_name' => 'Technology Strategy Consultation',
                'request_description' => 'Need expert consultation on selecting the right technology stack for our organization. Want to modernize our systems and improve efficiency while staying within nonprofit budget constraints.',
                'expectations' => 'Technology strategy recommendations with budget-conscious implementation roadmap',
                'additional_notes' => 'Target audience: Internal staff and board members',
                'requirements' => json_encode([
                    'budget_conscious' => true,
                    'technology_recommendations' => true,
                    'staff_training_plan' => true,
                    'implementation_roadmap' => true,
                    'efficiency_improvements' => true,
                    'nonprofit_focused' => true
                ]),
                'estimated_budget' => 2250.00,
                'deadline' => now()->addDays(21),
                'priority' => 'low',
                'status' => 'pending',
                'created_at' => now()->subDays(4),
            ],
            [
                'client_id' => $clients->where('email', 'john.smith@techstartup.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'john.smith@techstartup.com',
                'service_type' => 'SEO Optimization',
                'project_name' => 'Website SEO Optimization',
                'request_description' => 'Complete SEO optimization for our existing website to improve search rankings and organic traffic. Need keyword research, on-page optimization, and ongoing monitoring.',
                'expectations' => 'Improved search rankings and organic traffic with ongoing monitoring',
                'additional_notes' => 'Target audience: Potential customers searching for AI solutions',
                'requirements' => json_encode([
                    'keyword_research' => true,
                    'on_page_optimization' => true,
                    'competitor_analysis' => true,
                    'monthly_reporting' => true,
                    'ai_tech_focus' => true,
                    'organic_traffic_improvement' => true,
                    'search_ranking_improvement' => true
                ]),
                'estimated_budget' => 2750.00,
                'deadline' => now()->addDays(60),
                'priority' => 'medium',
                'status' => 'pending',
                'created_at' => now()->subHours(18),
            ],
            [
                'client_id' => $clients->where('email', 'david@ecommerceco.com')->first()->id,
                'contact_method' => 'email',
                'contact_details' => 'david@ecommerceco.com',
                'service_type' => 'Website Maintenance',
                'project_name' => 'Ongoing Website Maintenance',
                'request_description' => 'Monthly maintenance service for our corporate website including security updates, performance monitoring, content updates, and backup management.',
                'expectations' => 'Monthly website maintenance with monitoring and emergency support',
                'additional_notes' => 'Target audience: Website visitors and internal team',
                'requirements' => json_encode([
                    'security_updates' => true,
                    'performance_monitoring' => true,
                    'content_updates' => true,
                    'backup_management' => true,
                    'monthly_reports' => true,
                    '24_7_monitoring' => true,
                    'emergency_support' => true,
                    'content_management' => true
                ]),
                'estimated_budget' => 650.00,
                'approved_budget' => 650.00,
                'deadline' => now()->addDays(30), // Monthly service
                'priority' => 'low',
                'status' => 'approved',
                'approved_at' => now()->subHours(6),
                'approved_by' => 1, // Admin user
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($serviceRequests as $requestData) {
            ServiceRequest::create($requestData);
        }

        $this->command->info('Created ' . count($serviceRequests) . ' service requests with various statuses and realistic data');
    }
}