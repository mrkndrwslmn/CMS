<?php

namespace Database\Seeders;

use App\Models\AdiutorProfile;
use App\Models\ClientProfile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Users
        $admin1 = User::create([
            'fullName' => 'Mark Admin',
            'email' => 'admin@treisadiutor.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phoneNumber' => '+1-555-0101',
            'status' => 'active',
        ]);

        $admin2 = User::create([
            'fullName' => 'Sarah Manager',
            'email' => 'manager@treisadiutor.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phoneNumber' => '+1-555-0102',
            'status' => 'active',
        ]);

        // Create Client Users with Profiles
        $clients = [
            [
                'user' => [
                    'fullName' => 'John Smith',
                    'email' => 'john.smith@techstartup.com',
                    'password' => Hash::make('client123'),
                    'role' => 'client',
                    'phoneNumber' => '+1-555-0201',
                    'status' => 'active',
                ],
                'profile' => [
                    'company_name' => 'TechStartup Inc.',
                    'industry' => 'Technology',
                    'company_size' => '11-50',
                    'website' => 'https://techstartup.com',
                    'address' => '123 Innovation Drive, Silicon Valley, CA 94025',
                    'company_description' => 'A fast-growing tech startup focused on AI solutions for small businesses.',
                    'preferred_contact_methods' => json_encode(['email']),
                ]
            ],
            [
                'user' => [
                    'fullName' => 'Maria Rodriguez',
                    'email' => 'maria@designstudio.com',
                    'password' => Hash::make('client123'),
                    'role' => 'client',
                    'phoneNumber' => '+1-555-0202',
                    'status' => 'active',
                ],
                'profile' => [
                    'company_name' => 'Creative Design Studio',
                    'industry' => 'Design & Marketing',
                    'company_size' => '2-10',
                    'website' => 'https://creativedesignstudio.com',
                    'address' => '456 Art District, New York, NY 10001',
                    'company_description' => 'A boutique design agency specializing in brand identity and digital experiences.',
                    'preferred_contact_methods' => json_encode(['phone']),
                ]
            ],
            [
                'user' => [
                    'fullName' => 'David Chen',
                    'email' => 'david@ecommerceco.com',
                    'password' => Hash::make('client123'),
                    'role' => 'client',
                    'phoneNumber' => '+1-555-0203',
                    'status' => 'active',
                ],
                'profile' => [
                    'company_name' => 'E-commerce Solutions Co.',
                    'industry' => 'E-commerce',
                    'company_size' => '51-200',
                    'website' => 'https://ecommerceco.com',
                    'address' => '789 Business Park, Austin, TX 78701',
                    'company_description' => 'Helping businesses build and scale their online presence with custom e-commerce solutions.',
                    'preferred_contact_methods' => json_encode(['email']),
                ]
            ],
            [
                'user' => [
                    'fullName' => 'Lisa Thompson',
                    'email' => 'lisa@nonprofithelp.org',
                    'password' => Hash::make('client123'),
                    'role' => 'client',
                    'phoneNumber' => '+1-555-0204',
                    'status' => 'active',
                ],
                'profile' => [
                    'company_name' => 'Help Communities Nonprofit',
                    'industry' => 'Non-profit',
                    'company_size' => '11-50',
                    'website' => 'https://nonprofithelp.org',
                    'address' => '321 Community Street, Denver, CO 80202',
                    'company_description' => 'A nonprofit organization focused on community development and education initiatives.',
                    'preferred_contact_methods' => json_encode(['email']),
                ]
            ],
        ];

        foreach ($clients as $clientData) {
            $user = User::create($clientData['user']);
            ClientProfile::create(array_merge($clientData['profile'], ['user_id' => $user->id]));
        }

        // Create Adiutor Users with Profiles
        $adiutors = [
            [
                'user' => [
                    'fullName' => 'Alex Developer',
                    'email' => 'alex@treisadiutor.com',
                    'password' => Hash::make('adiutor123'),
                    'role' => 'adiutor',
                    'phoneNumber' => '+1-555-0301',
                    'status' => 'active',
                ],
                'profile' => [
                    'title' => 'Full-Stack Developer',
                    'hourly_rate' => 75.00,
                    'portfolio_url' => 'https://alexdev.portfolio.com',
                    'bio' => 'Experienced full-stack developer with expertise in React, Node.js, and Laravel. Passionate about creating scalable web applications.',
                    'experience' => 'BS Computer Science, MIT. 5 years of professional development experience.',
                    'languages' => json_encode(['English', 'Spanish']),
                    'location' => 'New York, NY',
                    'status' => 'active',
                    'availability' => json_encode(['timezone' => 'America/New_York', 'max_projects' => 3]),
                ],
                'skills' => [
                    ['name' => 'JavaScript', 'proficiency' => 'expert', 'years_experience' => 5],
                    ['name' => 'React', 'proficiency' => 'expert', 'years_experience' => 4],
                    ['name' => 'Node.js', 'proficiency' => 'advanced', 'years_experience' => 4],
                    ['name' => 'Laravel', 'proficiency' => 'advanced', 'years_experience' => 3],
                    ['name' => 'MySQL', 'proficiency' => 'advanced', 'years_experience' => 5],
                ]
            ],
            [
                'user' => [
                    'fullName' => 'Jessica Designer',
                    'email' => 'jessica@treisadiutor.com',
                    'password' => Hash::make('adiutor123'),
                    'role' => 'adiutor',
                    'phoneNumber' => '+1-555-0302',
                    'status' => 'active',
                ],
                'profile' => [
                    'title' => 'UI/UX Designer',
                    'hourly_rate' => 65.00,
                    'portfolio_url' => 'https://jessicadesign.portfolio.com',
                    'bio' => 'Creative UI/UX designer with a passion for user-centered design and modern aesthetics. Expert in Figma and Adobe Creative Suite.',
                    'experience' => 'MFA Design, RISD. 6 years of professional design experience.',
                    'languages' => json_encode(['English', 'French']),
                    'location' => 'Los Angeles, CA',
                    'status' => 'active',
                    'availability' => json_encode(['timezone' => 'America/Los_Angeles', 'max_projects' => 4]),
                ],
                'skills' => [
                    ['name' => 'UI Design', 'proficiency' => 'expert', 'years_experience' => 6],
                    ['name' => 'UX Research', 'proficiency' => 'expert', 'years_experience' => 5],
                    ['name' => 'Figma', 'proficiency' => 'expert', 'years_experience' => 4],
                    ['name' => 'Adobe Creative Suite', 'proficiency' => 'expert', 'years_experience' => 6],
                    ['name' => 'Prototyping', 'proficiency' => 'advanced', 'years_experience' => 5],
                ]
            ],
            [
                'user' => [
                    'fullName' => 'Michael Backend',
                    'email' => 'michael@treisadiutor.com',
                    'password' => Hash::make('adiutor123'),
                    'role' => 'adiutor',
                    'phoneNumber' => '+1-555-0303',
                    'status' => 'active',
                ],
                'profile' => [
                    'title' => 'Senior Backend Developer',
                    'hourly_rate' => 80.00,
                    'portfolio_url' => 'https://michaelbackend.dev',
                    'bio' => 'Senior backend developer specializing in scalable API development and database optimization. Expert in Python, Django, and cloud architecture.',
                    'experience' => 'MS Computer Science, Stanford. 7 years of professional backend development experience.',
                    'languages' => json_encode(['English', 'German']),
                    'location' => 'London, UK',
                    'status' => 'busy',
                    'availability' => json_encode(['timezone' => 'Europe/London', 'max_projects' => 2]),
                ],
                'skills' => [
                    ['name' => 'Python', 'proficiency' => 'expert', 'years_experience' => 7],
                    ['name' => 'Django', 'proficiency' => 'expert', 'years_experience' => 6],
                    ['name' => 'PostgreSQL', 'proficiency' => 'expert', 'years_experience' => 7],
                    ['name' => 'AWS', 'proficiency' => 'advanced', 'years_experience' => 5],
                    ['name' => 'API Development', 'proficiency' => 'expert', 'years_experience' => 7],
                ]
            ],
            [
                'user' => [
                    'fullName' => 'Sarah Frontend',
                    'email' => 'sarah@treisadiutor.com',
                    'password' => Hash::make('adiutor123'),
                    'role' => 'adiutor',
                    'phoneNumber' => '+1-555-0304',
                    'status' => 'active',
                ],
                'profile' => [
                    'title' => 'Frontend Developer',
                    'hourly_rate' => 60.00,
                    'portfolio_url' => 'https://sarahfrontend.dev',
                    'bio' => 'Frontend developer passionate about creating beautiful, responsive user interfaces. Specializes in Vue.js and modern CSS frameworks.',
                    'experience' => 'BS Web Development, UCLA. 4 years of professional frontend development experience.',
                    'languages' => json_encode(['English', 'Japanese']),
                    'location' => 'Los Angeles, CA',
                    'status' => 'active',
                    'availability' => json_encode(['timezone' => 'America/Los_Angeles', 'max_projects' => 3]),
                ],
                'skills' => [
                    ['name' => 'Vue.js', 'proficiency' => 'expert', 'years_experience' => 4],
                    ['name' => 'HTML/CSS', 'proficiency' => 'expert', 'years_experience' => 4],
                    ['name' => 'Tailwind CSS', 'proficiency' => 'advanced', 'years_experience' => 3],
                    ['name' => 'JavaScript', 'proficiency' => 'advanced', 'years_experience' => 4],
                    ['name' => 'Responsive Design', 'proficiency' => 'expert', 'years_experience' => 4],
                ]
            ],
            [
                'user' => [
                    'fullName' => 'Carlos Mobile',
                    'email' => 'carlos@treisadiutor.com',
                    'password' => Hash::make('adiutor123'),
                    'role' => 'adiutor',
                    'phoneNumber' => '+1-555-0305',
                    'status' => 'active',
                ],
                'profile' => [
                    'title' => 'Mobile Developer',
                    'hourly_rate' => 70.00,
                    'portfolio_url' => 'https://carlosmobile.dev',
                    'bio' => 'Mobile app developer with expertise in React Native and Flutter. Experienced in building cross-platform applications for startups and enterprises.',
                    'experience' => 'BS Software Engineering, USC. 5 years of professional mobile development experience.',
                    'languages' => json_encode(['English', 'Spanish', 'Portuguese']),
                    'location' => 'Mexico City, Mexico',
                    'status' => 'active',
                    'availability' => json_encode(['timezone' => 'America/Mexico_City', 'max_projects' => 2]),
                ],
                'skills' => [
                    ['name' => 'React Native', 'proficiency' => 'expert', 'years_experience' => 5],
                    ['name' => 'Flutter', 'proficiency' => 'advanced', 'years_experience' => 3],
                    ['name' => 'iOS Development', 'proficiency' => 'intermediate', 'years_experience' => 3],
                    ['name' => 'Android Development', 'proficiency' => 'advanced', 'years_experience' => 4],
                    ['name' => 'Mobile UI/UX', 'proficiency' => 'advanced', 'years_experience' => 5],
                ]
            ],
        ];

        foreach ($adiutors as $adiutorData) {
            $user = User::create($adiutorData['user']);
            $profile = AdiutorProfile::create(array_merge($adiutorData['profile'], ['user_id' => $user->id]));
            
            // Create skills for this adiutor using the pivot table
            foreach ($adiutorData['skills'] as $skillData) {
                $skill = Skill::firstOrCreate(['name' => $skillData['name']]);
                // Insert directly into the pivot table since it connects adiutor_profiles to skills
                DB::table('adiutor_skills')->insert([
                    'adiutor_id' => $profile->id,
                    'skill_id' => $skill->id,
                    'proficiency_level' => $skillData['proficiency'],
                    'years_experience' => $skillData['years_experience'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Created ' . count($clients) . ' clients, ' . count($adiutors) . ' adiutors, and 2 admins with profiles and skills');
    }
}