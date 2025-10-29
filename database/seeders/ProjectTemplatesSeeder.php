<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectTemplate;
use Illuminate\Support\Facades\DB;

class ProjectTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'E-commerce Website',
                'description' => 'Complete e-commerce solution with payment integration, product catalog, and admin panel',
                'category' => 'web-development',
                'default_tasks' => [
                    ['title' => 'Requirements Analysis & Planning', 'description' => 'Gather requirements, create wireframes and technical specifications', 'priority' => 'high', 'estimated_hours' => 16],
                    ['title' => 'UI/UX Design', 'description' => 'Design product pages, checkout flow, and admin dashboard', 'priority' => 'high', 'estimated_hours' => 24],
                    ['title' => 'Frontend Development', 'description' => 'Implement responsive design with React/Vue or vanilla JS', 'priority' => 'high', 'estimated_hours' => 40],
                    ['title' => 'Backend Development', 'description' => 'API development, database design, user authentication', 'priority' => 'high', 'estimated_hours' => 48],
                    ['title' => 'Payment Integration', 'description' => 'Integrate payment gateways (Stripe, PayPal, etc.)', 'priority' => 'medium', 'estimated_hours' => 16],
                    ['title' => 'Testing & QA', 'description' => 'Unit testing, integration testing, user acceptance testing', 'priority' => 'medium', 'estimated_hours' => 20],
                    ['title' => 'Deployment & Launch', 'description' => 'Server setup, SSL configuration, go-live', 'priority' => 'medium', 'estimated_hours' => 8],
                ],
                'skills_required' => ['PHP', 'Laravel', 'JavaScript', 'HTML/CSS', 'MySQL', 'Payment Gateways'],
                'milestones_template' => [
                    ['phase_name' => 'Design & Planning', 'percentage' => 20, 'description' => 'Requirements, wireframes, and design completion'],
                    ['phase_name' => 'Frontend Development', 'percentage' => 30, 'description' => 'User interface implementation'],
                    ['phase_name' => 'Backend Development', 'percentage' => 35, 'description' => 'API and database implementation'],
                    ['phase_name' => 'Testing & Launch', 'percentage' => 15, 'description' => 'QA testing and deployment'],
                ],
                'estimated_budget_min' => 5000,
                'estimated_budget_max' => 12000,
                'estimated_duration_days' => 45,
                'budget_type' => 'fixed',
                'payment_type' => 'milestone_payment',
                'requirements_template' => 'Please provide: Product catalog requirements, Payment methods needed, Shipping requirements, Admin panel features, Design preferences, Target audience information',
                'is_active' => true,
            ],
            [
                'name' => 'Mobile App Development (iOS/Android)',
                'description' => 'Cross-platform mobile application with native features and backend integration',
                'category' => 'mobile-development',
                'default_tasks' => [
                    ['title' => 'App Concept & Strategy', 'description' => 'Define app goals, target audience, and feature set', 'priority' => 'high', 'estimated_hours' => 12],
                    ['title' => 'UI/UX Design', 'description' => 'Create app wireframes, user flow, and visual design', 'priority' => 'high', 'estimated_hours' => 32],
                    ['title' => 'Backend API Development', 'description' => 'Build RESTful APIs and database architecture', 'priority' => 'high', 'estimated_hours' => 40],
                    ['title' => 'Frontend App Development', 'description' => 'Develop React Native or Flutter app', 'priority' => 'high', 'estimated_hours' => 60],
                    ['title' => 'Native Features Integration', 'description' => 'Camera, GPS, push notifications, device APIs', 'priority' => 'medium', 'estimated_hours' => 20],
                    ['title' => 'Testing & Optimization', 'description' => 'Device testing, performance optimization, bug fixes', 'priority' => 'medium', 'estimated_hours' => 24],
                    ['title' => 'App Store Deployment', 'description' => 'App store submission and approval process', 'priority' => 'medium', 'estimated_hours' => 8],
                ],
                'skills_required' => ['React Native', 'Flutter', 'Node.js', 'API Development', 'Mobile UI/UX', 'App Store Optimization'],
                'milestones_template' => [
                    ['phase_name' => 'Design & Planning', 'percentage' => 25, 'description' => 'App concept, wireframes, and design'],
                    ['phase_name' => 'Backend Development', 'percentage' => 30, 'description' => 'API and database setup'],
                    ['phase_name' => 'Mobile App Development', 'percentage' => 35, 'description' => 'App development and native features'],
                    ['phase_name' => 'Testing & Launch', 'percentage' => 10, 'description' => 'Testing and app store submission'],
                ],
                'estimated_budget_min' => 8000,
                'estimated_budget_max' => 25000,
                'estimated_duration_days' => 60,
                'budget_type' => 'fixed',
                'payment_type' => 'milestone_payment',
                'requirements_template' => 'Please provide: App concept and goals, Target platforms (iOS/Android), Required features, Design preferences, Third-party integrations needed, Expected user base',
                'is_active' => true,
            ],
            [
                'name' => 'Brand Identity & Logo Design',
                'description' => 'Complete brand identity package including logo, color palette, and brand guidelines',
                'category' => 'design',
                'default_tasks' => [
                    ['title' => 'Brand Discovery & Research', 'description' => 'Understand brand values, target audience, and competitors', 'priority' => 'high', 'estimated_hours' => 8],
                    ['title' => 'Concept Development', 'description' => 'Create initial logo concepts and mood boards', 'priority' => 'high', 'estimated_hours' => 16],
                    ['title' => 'Logo Design Refinement', 'description' => 'Refine selected concepts based on feedback', 'priority' => 'high', 'estimated_hours' => 12],
                    ['title' => 'Brand Guidelines Creation', 'description' => 'Color palette, typography, usage guidelines', 'priority' => 'medium', 'estimated_hours' => 8],
                    ['title' => 'Brand Collateral Design', 'description' => 'Business cards, letterhead, social media templates', 'priority' => 'medium', 'estimated_hours' => 12],
                    ['title' => 'File Preparation & Delivery', 'description' => 'Prepare all file formats and organize final deliverables', 'priority' => 'low', 'estimated_hours' => 4],
                ],
                'skills_required' => ['Graphic Design', 'Adobe Creative Suite', 'Brand Strategy', 'Typography', 'Color Theory'],
                'milestones_template' => [
                    ['phase_name' => 'Discovery & Concepts', 'percentage' => 40, 'description' => 'Research and initial concepts'],
                    ['phase_name' => 'Logo Refinement', 'percentage' => 30, 'description' => 'Logo finalization'],
                    ['phase_name' => 'Brand Guidelines', 'percentage' => 20, 'description' => 'Complete brand identity system'],
                    ['phase_name' => 'Final Delivery', 'percentage' => 10, 'description' => 'All files and documentation'],
                ],
                'estimated_budget_min' => 1500,
                'estimated_budget_max' => 5000,
                'estimated_duration_days' => 14,
                'budget_type' => 'fixed',
                'payment_type' => 'milestone_payment',
                'requirements_template' => 'Please provide: Company/brand information, Target audience, Brand values and personality, Design preferences, Competitor examples, Intended use cases for logo',
                'is_active' => true,
            ],
            [
                'name' => 'WordPress Website',
                'description' => 'Custom WordPress website with theme development and content management',
                'category' => 'web-development',
                'default_tasks' => [
                    ['title' => 'Requirements & Planning', 'description' => 'Site structure, features, and content planning', 'priority' => 'high', 'estimated_hours' => 8],
                    ['title' => 'Design & Wireframing', 'description' => 'Custom theme design and page layouts', 'priority' => 'high', 'estimated_hours' => 20],
                    ['title' => 'WordPress Setup', 'description' => 'WordPress installation, hosting setup, security', 'priority' => 'high', 'estimated_hours' => 4],
                    ['title' => 'Theme Development', 'description' => 'Custom WordPress theme development', 'priority' => 'high', 'estimated_hours' => 32],
                    ['title' => 'Plugin Integration', 'description' => 'Install and configure necessary plugins', 'priority' => 'medium', 'estimated_hours' => 8],
                    ['title' => 'Content Migration', 'description' => 'Content entry and SEO optimization', 'priority' => 'medium', 'estimated_hours' => 12],
                    ['title' => 'Testing & Launch', 'description' => 'Cross-browser testing and go-live', 'priority' => 'medium', 'estimated_hours' => 6],
                ],
                'skills_required' => ['WordPress', 'PHP', 'HTML/CSS', 'JavaScript', 'SEO', 'Responsive Design'],
                'milestones_template' => [
                    ['phase_name' => 'Design & Setup', 'percentage' => 30, 'description' => 'Design approval and WordPress setup'],
                    ['phase_name' => 'Theme Development', 'percentage' => 50, 'description' => 'Custom theme implementation'],
                    ['phase_name' => 'Content & Launch', 'percentage' => 20, 'description' => 'Content migration and launch'],
                ],
                'estimated_budget_min' => 2000,
                'estimated_budget_max' => 6000,
                'estimated_duration_days' => 21,
                'budget_type' => 'fixed',
                'payment_type' => 'milestone_payment',
                'requirements_template' => 'Please provide: Site purpose and goals, Number of pages needed, Design references, Content (text and images), Required plugins/features, Hosting preferences',
                'is_active' => true,
            ],
            [
                'name' => 'SEO Optimization Package',
                'description' => 'Comprehensive SEO audit and optimization for improved search rankings',
                'category' => 'marketing',
                'default_tasks' => [
                    ['title' => 'SEO Audit & Analysis', 'description' => 'Complete technical and content SEO audit', 'priority' => 'high', 'estimated_hours' => 12],
                    ['title' => 'Keyword Research', 'description' => 'Target keyword identification and competition analysis', 'priority' => 'high', 'estimated_hours' => 8],
                    ['title' => 'On-Page Optimization', 'description' => 'Meta tags, headers, content optimization', 'priority' => 'high', 'estimated_hours' => 16],
                    ['title' => 'Technical SEO Fixes', 'description' => 'Site speed, mobile optimization, schema markup', 'priority' => 'medium', 'estimated_hours' => 12],
                    ['title' => 'Content Strategy', 'description' => 'Content calendar and optimization recommendations', 'priority' => 'medium', 'estimated_hours' => 8],
                    ['title' => 'Reporting & Monitoring', 'description' => 'Setup analytics and monthly reporting', 'priority' => 'low', 'estimated_hours' => 4],
                ],
                'skills_required' => ['SEO', 'Google Analytics', 'Technical SEO', 'Content Marketing', 'Keyword Research'],
                'milestones_template' => [
                    ['phase_name' => 'Audit & Strategy', 'percentage' => 35, 'description' => 'SEO audit and keyword research'],
                    ['phase_name' => 'Implementation', 'percentage' => 50, 'description' => 'On-page and technical optimizations'],
                    ['phase_name' => 'Monitoring Setup', 'percentage' => 15, 'description' => 'Analytics and reporting setup'],
                ],
                'estimated_budget_min' => 1000,
                'estimated_budget_max' => 3000,
                'estimated_duration_days' => 30,
                'budget_type' => 'fixed',
                'payment_type' => 'downpayment',
                'requirements_template' => 'Please provide: Website URL, Target keywords (if known), Current analytics access, Business goals, Target audience, Geographic targeting needs',
                'is_active' => true,
            ],
            [
                'name' => 'Content Management System',
                'description' => 'Custom CMS development for content-heavy websites and applications',
                'category' => 'web-development',
                'default_tasks' => [
                    ['title' => 'Requirements Analysis', 'description' => 'Content types, user roles, workflow requirements', 'priority' => 'high', 'estimated_hours' => 12],
                    ['title' => 'Database Design', 'description' => 'Content structure, relationships, and optimization', 'priority' => 'high', 'estimated_hours' => 16],
                    ['title' => 'Admin Panel Development', 'description' => 'Content management interface and user controls', 'priority' => 'high', 'estimated_hours' => 36],
                    ['title' => 'Frontend Integration', 'description' => 'Public-facing content display and templates', 'priority' => 'high', 'estimated_hours' => 28],
                    ['title' => 'User Management System', 'description' => 'Authentication, authorization, role management', 'priority' => 'medium', 'estimated_hours' => 20],
                    ['title' => 'API Development', 'description' => 'RESTful APIs for content access and management', 'priority' => 'medium', 'estimated_hours' => 16],
                    ['title' => 'Testing & Documentation', 'description' => 'User testing, documentation, and training', 'priority' => 'medium', 'estimated_hours' => 12],
                ],
                'skills_required' => ['PHP', 'Laravel', 'MySQL', 'JavaScript', 'API Development', 'System Architecture'],
                'milestones_template' => [
                    ['phase_name' => 'Planning & Database', 'percentage' => 25, 'description' => 'Requirements and database design'],
                    ['phase_name' => 'Admin Development', 'percentage' => 40, 'description' => 'CMS admin panel creation'],
                    ['phase_name' => 'Frontend & API', 'percentage' => 25, 'description' => 'Public interface and API'],
                    ['phase_name' => 'Testing & Launch', 'percentage' => 10, 'description' => 'Testing and deployment'],
                ],
                'estimated_budget_min' => 6000,
                'estimated_budget_max' => 15000,
                'estimated_duration_days' => 50,
                'budget_type' => 'fixed',
                'payment_type' => 'milestone_payment',
                'requirements_template' => 'Please provide: Content types needed, User roles and permissions, Workflow requirements, Integration needs, Performance requirements, Hosting environment',
                'is_active' => true,
            ],
            [
                'name' => 'Social Media Marketing Campaign',
                'description' => 'Comprehensive social media strategy and content creation for brand growth',
                'category' => 'marketing',
                'default_tasks' => [
                    ['title' => 'Brand Analysis & Strategy', 'description' => 'Brand positioning, competitor analysis, goal setting', 'priority' => 'high', 'estimated_hours' => 10],
                    ['title' => 'Content Calendar Planning', 'description' => 'Monthly content strategy and posting schedule', 'priority' => 'high', 'estimated_hours' => 12],
                    ['title' => 'Visual Content Creation', 'description' => 'Graphics, videos, and branded content assets', 'priority' => 'high', 'estimated_hours' => 24],
                    ['title' => 'Copywriting & Content', 'description' => 'Engaging captions, blog posts, and marketing copy', 'priority' => 'medium', 'estimated_hours' => 16],
                    ['title' => 'Community Management', 'description' => 'Daily posting, engagement, and community building', 'priority' => 'medium', 'estimated_hours' => 30],
                    ['title' => 'Analytics & Reporting', 'description' => 'Performance tracking and monthly reports', 'priority' => 'medium', 'estimated_hours' => 8],
                ],
                'skills_required' => ['Social Media Marketing', 'Content Creation', 'Graphic Design', 'Copywriting', 'Analytics'],
                'milestones_template' => [
                    ['phase_name' => 'Strategy & Planning', 'percentage' => 20, 'description' => 'Social media strategy development'],
                    ['phase_name' => 'Content Creation', 'percentage' => 40, 'description' => 'Visual and written content'],
                    ['phase_name' => 'Campaign Launch', 'percentage' => 30, 'description' => 'Active campaign management'],
                    ['phase_name' => 'Optimization', 'percentage' => 10, 'description' => 'Performance analysis and optimization'],
                ],
                'estimated_budget_min' => 2000,
                'estimated_budget_max' => 5000,
                'estimated_duration_days' => 30,
                'budget_type' => 'hourly',
                'payment_type' => 'milestone_payment',
                'requirements_template' => 'Please provide: Brand information, Target audience, Current social presence, Marketing goals, Budget for ads (if any), Preferred platforms',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            ProjectTemplate::create($template);
        }
    }
}
