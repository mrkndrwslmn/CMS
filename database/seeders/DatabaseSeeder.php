<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding with realistic data...');

        // 1. First seed users (admin, clients, adiutors) with profiles and skills
        $this->command->info('👥 Seeding users, profiles, and skills...');
        $this->call([
            SkillSeeder::class,      // Create skills first (for adiutor relationships)
            UserSeeder::class,       // Create users with profiles and skill assignments
        ]);

        // 2. Then seed services (what we offer)
        $this->command->info('🛠️ Seeding services catalog...');
        $this->call([
            ServicesSeeder::class,   // Create service offerings
        ]);

        // 3. Then seed the main workflow: SERVICE REQUESTS → PROJECTS → TASKS
        $this->command->info('📋 Seeding workflow: Service Requests → Projects → Tasks...');
        $this->call([
            ServiceRequestSeeder::class,  // Client submissions (some approved, some pending)
            ProjectSeeder::class,         // Convert approved requests to projects
            TaskSeeder::class,            // Break down projects into tasks
        ]);

        // 4. Finally seed feedback from clients
        $this->call([
            FeedbackSeeder::class,        // Client feedback on completed work
        ]);

        // 5. Seed showcases (portfolio/featured projects)
        $this->command->info('🎨 Seeding showcase portfolio...');
        $this->call([
            ShowcaseSeeder::class,        // Portfolio projects with screenshots
        ]);

        // 6. Seed tech stack (technologies we use)
        $this->command->info('💻 Seeding tech stack...');
        $this->call([
            TechStackSeeder::class,       // Technologies and tools we use
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('🎯 Summary:');
        $this->command->info('   • Users: Admins, Clients with realistic profiles, Adiutors with skills');
        $this->command->info('   • Services: 15 realistic service offerings across multiple categories');
        $this->command->info('   • Service Requests: Client submissions with various statuses');
        $this->command->info('   • Projects: Approved requests converted to active projects');
        $this->command->info('   • Tasks: Project work broken down into actionable tasks');
        $this->command->info('   • Feedback: Client feedback with ratings and admin responses');
        $this->command->info('   • Showcases: Portfolio projects with screenshots for public display');
        $this->command->info('   • Tech Stack: 51 technologies and tools across 9 categories');
        $this->command->info('');
        $this->command->info('🔑 Login credentials:');
        $this->command->info('   Admin: admin@treisadiutor.com / admin123');
        $this->command->info('   Manager: manager@treisadiutor.com / admin123');
        $this->command->info('   Client: john.smith@techstartup.com / client123');
        $this->command->info('   Adiutor: alex@treisadiutor.com / adiutor123');
    }
}
