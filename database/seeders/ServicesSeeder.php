<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Web Development Services
            [
                'name' => 'Custom Website Development',
                'description' => 'Full-stack custom website development with modern technologies like React, Laravel, and responsive design.',
                'base_price' => 5000.00,
                'category' => 'Web Development',
                'features' => json_encode([
                    'Custom Design',
                    'Responsive Layout',
                    'Content Management System',
                    'SEO Optimization',
                    'Performance Optimization',
                    '3 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-code',
                'estimated_duration_days' => 45,
                'required_skills' => json_encode(['HTML/CSS', 'JavaScript', 'React', 'Laravel', 'MySQL']),
                'requirements' => 'Detailed project requirements, content, branding materials, and hosting preferences.',
            ],
            [
                'name' => 'E-commerce Platform',
                'description' => 'Complete e-commerce solution with payment integration, inventory management, and admin dashboard.',
                'base_price' => 12000.00,
                'category' => 'Web Development',
                'features' => json_encode([
                    'Shopping Cart',
                    'Payment Gateway Integration',
                    'Inventory Management',
                    'Order Management',
                    'Admin Dashboard',
                    'Customer Accounts',
                    'Email Notifications',
                    '6 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-shopping-cart',
                'estimated_duration_days' => 90,
                'required_skills' => json_encode(['PHP', 'Laravel', 'MySQL', 'Payment APIs', 'JavaScript', 'Security']),
                'requirements' => 'Product catalog, payment preferences, shipping requirements, and business logic specifications.',
            ],
            [
                'name' => 'Web Application Development',
                'description' => 'Custom web application development for business processes, dashboards, and data management.',
                'base_price' => 8000.00,
                'category' => 'Web Development',
                'features' => json_encode([
                    'Custom Functionality',
                    'User Authentication',
                    'Database Integration',
                    'API Development',
                    'Admin Panel',
                    'Reporting System',
                    '4 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-laptop-code',
                'estimated_duration_days' => 60,
                'required_skills' => json_encode(['Full-Stack Development', 'Database Design', 'API Development', 'Security']),
                'requirements' => 'Functional requirements, user workflows, data structure, and integration needs.',
            ],
            [
                'name' => 'WordPress Development',
                'description' => 'Custom WordPress website with themes, plugins, and content management capabilities.',
                'base_price' => 3000.00,
                'category' => 'Web Development',
                'features' => json_encode([
                    'Custom Theme',
                    'Content Management',
                    'Plugin Integration',
                    'SEO Setup',
                    'Responsive Design',
                    'Training Included',
                    '2 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fab fa-wordpress',
                'estimated_duration_days' => 30,
                'required_skills' => json_encode(['WordPress', 'PHP', 'HTML/CSS', 'JavaScript']),
                'requirements' => 'Content, design preferences, required functionality, and hosting information.',
            ],

            // Mobile Development Services
            [
                'name' => 'Mobile App Development',
                'description' => 'Cross-platform mobile application development using React Native or Flutter.',
                'base_price' => 15000.00,
                'category' => 'Mobile Development',
                'features' => json_encode([
                    'iOS & Android Compatible',
                    'Native Performance',
                    'Push Notifications',
                    'App Store Deployment',
                    'User Authentication',
                    'Offline Capability',
                    '6 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-mobile-alt',
                'estimated_duration_days' => 120,
                'required_skills' => json_encode(['React Native', 'Flutter', 'Mobile UI/UX', 'API Integration']),
                'requirements' => 'App specifications, target platforms, design mockups, and required integrations.',
            ],
            [
                'name' => 'Progressive Web App (PWA)',
                'description' => 'Progressive Web Application that works across all devices with app-like experience.',
                'base_price' => 7000.00,
                'category' => 'Mobile Development',
                'features' => json_encode([
                    'Offline Functionality',
                    'Push Notifications',
                    'App-like Experience',
                    'Cross-Platform',
                    'Fast Loading',
                    'Installable',
                    '4 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-tablet-alt',
                'estimated_duration_days' => 50,
                'required_skills' => json_encode(['JavaScript', 'PWA Technologies', 'Service Workers', 'Web APIs']),
                'requirements' => 'Feature requirements, offline capabilities needed, and target user experience.',
            ],

            // Design Services
            [
                'name' => 'UI/UX Design',
                'description' => 'Complete user interface and user experience design including wireframes, prototypes, and final designs.',
                'base_price' => 4000.00,
                'category' => 'Design',
                'features' => json_encode([
                    'User Research',
                    'Wireframes',
                    'Interactive Prototypes',
                    'Visual Design',
                    'Design System',
                    'Usability Testing',
                    'Design Handoff'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-paint-brush',
                'estimated_duration_days' => 35,
                'required_skills' => json_encode(['UI Design', 'UX Research', 'Figma', 'Prototyping', 'User Testing']),
                'requirements' => 'Project goals, target audience, brand guidelines, and platform specifications.',
            ],
            [
                'name' => 'Brand Identity Design',
                'description' => 'Complete brand identity package including logo, color palette, typography, and brand guidelines.',
                'base_price' => 2500.00,
                'category' => 'Design',
                'features' => json_encode([
                    'Logo Design',
                    'Color Palette',
                    'Typography Selection',
                    'Brand Guidelines',
                    'Business Card Design',
                    'Letterhead Design',
                    'Social Media Templates'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-palette',
                'estimated_duration_days' => 21,
                'required_skills' => json_encode(['Brand Design', 'Adobe Creative Suite', 'Logo Design', 'Typography']),
                'requirements' => 'Brand vision, target audience, industry information, and style preferences.',
            ],
            [
                'name' => 'Website Redesign',
                'description' => 'Complete website redesign to improve user experience, modernize appearance, and enhance functionality.',
                'base_price' => 6000.00,
                'category' => 'Design',
                'features' => json_encode([
                    'Current Site Analysis',
                    'New Design Concepts',
                    'Improved User Experience',
                    'Modern Visual Design',
                    'Mobile Optimization',
                    'Performance Improvements',
                    '3 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-magic',
                'estimated_duration_days' => 40,
                'required_skills' => json_encode(['Web Design', 'UX Analysis', 'Frontend Development', 'Performance Optimization']),
                'requirements' => 'Current website, business goals, target audience, and desired improvements.',
            ],

            [
                'name' => 'API Development',
                'description' => 'Custom REST API development for mobile apps, web applications, and third-party integrations.',
                'base_price' => 4500.00,
                'category' => 'Backend Development',
                'features' => json_encode([
                    'RESTful API Design',
                    'Authentication & Authorization',
                    'Database Integration',
                    'API Documentation',
                    'Rate Limiting',
                    'Error Handling',
                    'Testing Suite'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-plug',
                'estimated_duration_days' => 35,
                'required_skills' => json_encode(['API Development', 'Backend Development', 'Database Design', 'Security']),
                'requirements' => 'API specifications, data requirements, authentication needs, and integration points.',
            ],
            [
                'name' => 'Third-party Integration',
                'description' => 'Integration with external services, payment gateways, CRMs, and other third-party systems.',
                'base_price' => 2500.00,
                'category' => 'Integration',
                'features' => json_encode([
                    'Service Integration',
                    'Data Synchronization',
                    'Error Handling',
                    'Testing & Validation',
                    'Documentation',
                    'Monitoring Setup',
                    '2 Months Support'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-link',
                'estimated_duration_days' => 20,
                'required_skills' => json_encode(['API Integration', 'Data Processing', 'System Integration', 'Testing']),
                'requirements' => 'Integration requirements, API documentation, access credentials, and data mapping needs.',
            ],

            // Consulting Services
            [
                'name' => 'Technical Consulting',
                'description' => 'Expert technical consultation for architecture decisions, technology stack selection, and project planning.',
                'base_price' => 150.00, // Per hour
                'category' => 'Consulting',
                'features' => json_encode([
                    'Technical Architecture Review',
                    'Technology Stack Recommendation',
                    'Performance Analysis',
                    'Security Assessment',
                    'Scalability Planning',
                    'Code Review',
                    'Documentation'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-user-tie',
                'estimated_duration_days' => 7,
                'required_skills' => json_encode(['System Architecture', 'Multiple Technologies', 'Performance Optimization', 'Security']),
                'requirements' => 'Current system information, business requirements, and specific consultation needs.',
            ],
            [
                'name' => 'Digital Strategy Consulting',
                'description' => 'Strategic consulting for digital transformation, online presence optimization, and growth planning.',
                'base_price' => 3500.00,
                'category' => 'Consulting',
                'features' => json_encode([
                    'Digital Strategy Development',
                    'Market Analysis',
                    'Competitive Research',
                    'Technology Roadmap',
                    'ROI Planning',
                    'Implementation Guide',
                    'Follow-up Sessions'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-chart-line',
                'estimated_duration_days' => 14,
                'required_skills' => json_encode(['Business Analysis', 'Digital Marketing', 'Strategy Planning', 'Market Research']),
                'requirements' => 'Business information, current digital presence, goals, and target market details.',
            ],

            // Maintenance Services
            [
                'name' => 'Website Maintenance',
                'description' => 'Ongoing website maintenance, updates, security monitoring, and performance optimization.',
                'base_price' => 500.00, // Monthly
                'category' => 'Maintenance',
                'features' => json_encode([
                    'Regular Updates',
                    'Security Monitoring',
                    'Performance Optimization',
                    'Backup Management',
                    'Content Updates',
                    'Bug Fixes',
                    'Monthly Reports'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-tools',
                'estimated_duration_days' => 30, // Monthly service
                'required_skills' => json_encode(['Web Development', 'Security', 'Performance Optimization', 'Monitoring']),
                'requirements' => 'Website access, hosting information, and maintenance requirements.',
            ],
            [
                'name' => 'SEO Optimization',
                'description' => 'Search engine optimization to improve website visibility and organic search rankings.',
                'base_price' => 2000.00,
                'category' => 'Marketing',
                'features' => json_encode([
                    'SEO Analysis',
                    'Keyword Research',
                    'On-page Optimization',
                    'Technical SEO',
                    'Content Optimization',
                    'Link Building Strategy',
                    'Monthly Reporting'
                ]),
                'is_active' => true,
                'icon' => 'fas fa-search',
                'estimated_duration_days' => 30,
                'required_skills' => json_encode(['SEO', 'Content Marketing', 'Analytics', 'Technical SEO']),
                'requirements' => 'Website access, target keywords, business goals, and current SEO status.',
            ]
        ];

        foreach ($services as $service) {
            DB::table('services')->insert(array_merge($service, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Created ' . count($services) . ' services across multiple categories');
    }
}
