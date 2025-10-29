<?php

namespace App\Services;

class BrandfetchService
{
    /**
     * The Brandfetch Logo API base URL
     */
    private const LOGO_API_BASE = 'https://cdn.brandfetch.io';

    /**
     * Get the Brandfetch client ID from environment
     */
    private static function getClientId(): string
    {
        return config('services.brandfetch.client_id', '');
    }

    /**
     * Generate a Brandfetch Logo API URL
     * 
     * @param string $identifier Domain, Brand ID, ISIN, or Stock Ticker
     * @param string $type Type of logo: 'icon', 'logo', or 'symbol'
     * @param string $theme Theme: 'light' or 'dark'
     * @param string $fallback Fallback option: 'brandfetch', 'transparent', 'lettermark', or '404'
     * @param int|null $width Optional width
     * @param int|null $height Optional height
     * @return string The complete Brandfetch Logo API URL
     */
    public static function getLogoUrl(
        string $identifier,
        string $type = 'icon',
        string $theme = 'light',
        string $fallback = 'lettermark',
        ?int $width = null,
        ?int $height = null
    ): string {
        $clientId = self::getClientId();
        
        if (empty($clientId)) {
            \Log::warning('Brandfetch client ID not configured');
            return '';
        }

        // Build query parameters
        $params = [
            'c' => $clientId,
            'type' => $type,
            'theme' => $theme,
            'fallback' => $fallback,
        ];

        if ($width !== null) {
            $params['width'] = $width;
        }

        if ($height !== null) {
            $params['height'] = $height;
        }

        $queryString = http_build_query($params);
        
        return self::LOGO_API_BASE . '/' . urlencode($identifier) . '?' . $queryString;
    }

    /**
     * Map common technology names to their official domains
     * 
     * @param string $techName The technology name
     * @return string|null The domain or null if not found
     */
    public static function getDomainForTech(string $techName): ?string
    {
        $domainMap = [
            // Programming Languages
            'C' => 'wikipedia.org/wiki/C_(programming_language)',
            'C#' => 'dotnet.microsoft.com',
            'C++' => 'isocpp.org',
            'Python' => 'python.org',
            'Java' => 'java.com',
            'PHP' => 'php.net',
            'JavaScript' => 'javascript.com',
            'TypeScript' => 'typescriptlang.org',
            
            // Frontend
            'HTML' => 'w3.org',
            'CSS' => 'w3.org',
            'React.js' => 'react.dev',
            'Vue.js' => 'vuejs.org',
            'Bootstrap' => 'getbootstrap.com',
            'Tailwind CSS' => 'tailwindcss.com',
            
            // Backend
            'Flask' => 'flask.palletsprojects.com',
            'Django' => 'djangoproject.com',
            'Express.js' => 'expressjs.com',
            'Next.js' => 'nextjs.org',
            'Node.js' => 'nodejs.org',
            'Laravel' => 'laravel.com',
            
            // Databases & Database Management
            'SQL' => 'wikipedia.org/wiki/SQL',
            'MySQL' => 'mysql.com',
            'PostgreSQL' => 'postgresql.org',
            'MongoDB' => 'mongodb.com',
            'Firebase' => 'firebase.google.com',
            'SQLite' => 'sqlite.org',
            
            // Version Control & Collaboration
            'Git' => 'git-scm.com',
            'GitHub' => 'github.com',
            'GitLab' => 'gitlab.com',
            
            // DevOps & Cloud
            'Docker' => 'docker.com',
            'AWS (Amazon Web Services)' => 'aws.amazon.com',
            'GCP (Google Cloud Platform)' => 'cloud.google.com',
            'Azure (Microsoft Cloud Platform)' => 'azure.microsoft.com',
            
            // Testing & CI/CD
            'Cypress' => 'cypress.io',
            'Selenium' => 'selenium.dev',
            'Jenkins' => 'jenkins.io',
            'GitHub Actions' => 'github.com',
            
            // Productivity Tools
            'Microsoft Word' => 'microsoft.com',
            'Google Docs' => 'id6O2oGzv-',
            'Microsoft Excel' => 'microsoft.com',
            'Google Sheets' => 'sheets.google.com',
            'Google Slides' => 'slides.google.com',
            'Microsoft Powerpoint' => 'microsoft.com',
            
            // Design & Multimedia Tools
            'Figma' => 'figma.com',
            'Canva' => 'canva.com',
            'Adobe Photoshop' => 'adobe.com',
            'Adobe Illustrator' => 'adobe.com',
            'Adobe Premiere Pro' => 'adobe.com',
            'Adobe After Effects' => 'adobe.com',
            'Adobe InDesign' => 'adobe.com',
            'Adobe Lightroom' => 'adobe.com',
        ];

        return $domainMap[$techName] ?? null;
    }
}
