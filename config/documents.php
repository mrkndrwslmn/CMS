<?php

/**
 * Document Management Configuration
 * 
 * Centralized configuration for document uploads, including allowed file types,
 * maximum file sizes, and categorization.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Maximum File Size
    |--------------------------------------------------------------------------
    |
    | Maximum file size in kilobytes for document uploads.
    |
    */
    'max_file_size' => env('DOCUMENT_MAX_FILE_SIZE', 10240), // 10MB default

    /*
    |--------------------------------------------------------------------------
    | Allowed File Extensions
    |--------------------------------------------------------------------------
    |
    | Grouped by category for easy management. These are combined for validation.
    |
    */
    'allowed_extensions' => [
        // Standard Documents
        'documents' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv', 'rtf', 'odt', 'ods', 'odp'],
        
        // Images
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico', 'tiff', 'tif'],
        
        // Archives
        'archives' => ['zip', 'rar', '7z', 'tar', 'gz', 'bz2'],
        
        // Data & Config Files
        'data' => ['json', 'xml', 'yaml', 'yml', 'toml', 'ini', 'env'],
        
        // Web Development
        'web' => ['html', 'htm', 'css', 'scss', 'sass', 'less', 'js', 'jsx', 'ts', 'tsx', 'vue', 'svelte'],
        
        // Backend Development
        'backend' => ['php', 'py', 'rb', 'java', 'kt', 'kts', 'scala', 'go', 'rs', 'c', 'cpp', 'h', 'hpp', 'cs', 'fs', 'swift', 'r', 'jl', 'pl', 'lua', 'ex', 'exs', 'erl', 'hs', 'clj'],
        
        // Shell & DevOps
        'devops' => ['sh', 'bash', 'zsh', 'ps1', 'bat', 'cmd', 'dockerfile'],
        
        // Database & SQL
        'database' => ['sql', 'mysql', 'pgsql', 'sqlite'],
        
        // Documentation
        'documentation' => ['md', 'markdown', 'rst', 'adoc', 'textile'],
        
        // Mobile Development
        'mobile' => ['dart', 'gradle', 'podfile'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Document Categories
    |--------------------------------------------------------------------------
    |
    | Categories for document organization.
    |
    */
    'categories' => [
        'report' => 'Report',
        'contract' => 'Contract',
        'invoice' => 'Invoice',
        'proposal' => 'Proposal',
        'presentation' => 'Presentation',
        'deliverable' => 'Deliverable',
        'code' => 'Code/Development',
        'design' => 'Design',
        'documentation' => 'Documentation',
        'other' => 'Other',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Type Display Groups
    |--------------------------------------------------------------------------
    |
    | Groups for filtering and display in the UI.
    |
    */
    'display_groups' => [
        'documents' => 'Documents',
        'images' => 'Images',
        'code' => 'Code Files',
        'archives' => 'Archives',
        'other' => 'Other',
    ],
];
