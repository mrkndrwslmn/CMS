<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechStackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techStackItems = [
            // Programming Languages
            ['category' => 'Programming Languages', 'item_name' => 'C', 'domain' => 'wikipedia.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/clang.png'],
            ['category' => 'Programming Languages', 'item_name' => 'C#', 'domain' => 'dotnet.microsoft.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/cslang.png'],
            ['category' => 'Programming Languages', 'item_name' => 'C++', 'domain' => 'isocpp.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/c++lang.png'],
            ['category' => 'Programming Languages', 'item_name' => 'Python', 'domain' => 'python.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/pythonlang.png'],
            ['category' => 'Programming Languages', 'item_name' => 'Java', 'domain' => 'java.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/java.png'],
            ['category' => 'Programming Languages', 'item_name' => 'PHP', 'domain' => 'php.net', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/php.png'],
            ['category' => 'Programming Languages', 'item_name' => 'JavaScript', 'domain' => 'javascript.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/javascript.png'],
            ['category' => 'Programming Languages', 'item_name' => 'TypeScript', 'domain' => 'typescriptlang.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/typescript.png'],
            
            // Frontend
            ['category' => 'Frontend', 'item_name' => 'HTML', 'domain' => 'w3.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/html.png'],
            ['category' => 'Frontend', 'item_name' => 'CSS', 'domain' => 'w3.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/css.png'],
            ['category' => 'Frontend', 'item_name' => 'React.js', 'domain' => 'react.dev', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/react.png'],
            ['category' => 'Frontend', 'item_name' => 'Vue.js', 'domain' => 'vuejs.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/vuejs.png'],
            ['category' => 'Frontend', 'item_name' => 'Bootstrap', 'domain' => 'getbootstrap.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/bootstrap.png'],
            ['category' => 'Frontend', 'item_name' => 'Tailwind CSS', 'domain' => 'tailwindcss.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/tailwind.png'],
            
            // Backend
            ['category' => 'Backend', 'item_name' => 'Flask', 'domain' => 'flask.palletsprojects.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/flaskpy.png'],
            ['category' => 'Backend', 'item_name' => 'Django', 'domain' => 'djangoproject.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/django.png'],
            ['category' => 'Backend', 'item_name' => 'Express.js', 'domain' => 'expressjs.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/express.png'],
            ['category' => 'Backend', 'item_name' => 'Next.js', 'domain' => 'nextjs.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/nextjs.png'],
            ['category' => 'Backend', 'item_name' => 'Node.js', 'domain' => 'nodejs.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/nodejs.png'],
            ['category' => 'Backend', 'item_name' => 'Laravel', 'domain' => 'laravel.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/laravel.png'],
            
            // Databases & Database Management
            ['category' => 'Databases & Database Management', 'item_name' => 'SQL', 'domain' => 'wikipedia.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/sql.png'],
            ['category' => 'Databases & Database Management', 'item_name' => 'MySQL', 'domain' => 'mysql.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/mysql.png'],
            ['category' => 'Databases & Database Management', 'item_name' => 'PostgreSQL', 'domain' => 'postgresql.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/postgresql.png'],
            ['category' => 'Databases & Database Management', 'item_name' => 'MongoDB', 'domain' => 'mongodb.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/mongodb.png'],
            ['category' => 'Databases & Database Management', 'item_name' => 'Firebase', 'domain' => 'firebase.google.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/firebase.png'],
            ['category' => 'Databases & Database Management', 'item_name' => 'SQLite', 'domain' => 'sqlite.org', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/sqlite.png'],
            
            // Version Control & Collaboration
            ['category' => 'Version Control & Collaboration', 'item_name' => 'Git', 'domain' => 'git-scm.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/git.png'],
            ['category' => 'Version Control & Collaboration', 'item_name' => 'GitHub', 'domain' => 'github.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/github.png'],
            ['category' => 'Version Control & Collaboration', 'item_name' => 'GitLab', 'domain' => 'gitlab.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/gitlab.png'],
            
            // DevOps & Cloud
            ['category' => 'DevOps & Cloud', 'item_name' => 'Docker', 'domain' => 'docker.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/docker.png'],
            ['category' => 'DevOps & Cloud', 'item_name' => 'AWS (Amazon Web Services)', 'domain' => 'aws.amazon.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/aws.png'],
            ['category' => 'DevOps & Cloud', 'item_name' => 'GCP (Google Cloud Platform)', 'domain' => 'cloud.google.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/gcp.png'],
            ['category' => 'DevOps & Cloud', 'item_name' => 'Azure (Microsoft Cloud Platform)', 'domain' => 'azure.microsoft.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/azure.png'],
            
            // Testing & CI/CD
            ['category' => 'Testing & CI/CD', 'item_name' => 'Cypress', 'domain' => 'cypress.io', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/cypress.png'],
            ['category' => 'Testing & CI/CD', 'item_name' => 'Selenium', 'domain' => 'selenium.dev', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/selenium.png'],
            ['category' => 'Testing & CI/CD', 'item_name' => 'Jenkins', 'domain' => 'jenkins.io', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/jenkins.png'],
            ['category' => 'Testing & CI/CD', 'item_name' => 'GitHub Actions', 'domain' => 'github.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/github-actions.png'],
            
            // Productivity Tools
            ['category' => 'Productivity Tools', 'item_name' => 'Microsoft Word', 'domain' => 'microsoft.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/word.png'],
            ['category' => 'Productivity Tools', 'item_name' => 'Google Docs', 'domain' => 'id6O2oGzv-', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/docs.png'],
            ['category' => 'Productivity Tools', 'item_name' => 'Microsoft Excel', 'domain' => 'microsoft.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/excel.png'],
            ['category' => 'Productivity Tools', 'item_name' => 'Google Sheets', 'domain' => 'id6O2oGzv-', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/docs.png'],
            ['category' => 'Productivity Tools', 'item_name' => 'Microsoft Powerpoint', 'domain' => 'microsoft.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/ppt.png'],
            
            // Design & Multimedia Tools
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Figma', 'domain' => 'figma.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/figma.png'],
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Canva', 'domain' => 'canva.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/canva.png'],
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Adobe Photoshop', 'domain' => 'adobe.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/photoshop.png'],
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Adobe Illustrator', 'domain' => 'adobe.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/illustrator.png'],
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Adobe Premiere Pro', 'domain' => 'adobe.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/premiere-pro.png'],
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Adobe After Effects', 'domain' => 'adobe.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/after-effects.png'],
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Adobe InDesign', 'domain' => 'adobe.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/indesign.png'],
            ['category' => 'Design & Multimedia Tools', 'item_name' => 'Adobe Lightroom', 'domain' => 'adobe.com', 'image_url' => 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/LOGOS/lightroom.png'],
        ];

        foreach ($techStackItems as $item) {
            DB::table('tech_stack')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
