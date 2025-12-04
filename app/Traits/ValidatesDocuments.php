<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

/**
 * Trait for document upload validation.
 * 
 * Provides standardized validation rules for document uploads across controllers.
 */
trait ValidatesDocuments
{
    /**
     * Get all allowed file extensions as a comma-separated string.
     *
     * @return string
     */
    protected function getAllowedExtensions(): string
    {
        $extensions = config('documents.allowed_extensions', []);
        $allExtensions = [];
        
        foreach ($extensions as $group => $exts) {
            $allExtensions = array_merge($allExtensions, $exts);
        }
        
        return implode(',', array_unique($allExtensions));
    }
    
    /**
     * Get allowed extensions for specific groups.
     *
     * @param array $groups Array of group names (e.g., ['documents', 'images'])
     * @return string
     */
    protected function getAllowedExtensionsForGroups(array $groups): string
    {
        $extensions = config('documents.allowed_extensions', []);
        $selectedExtensions = [];
        
        foreach ($groups as $group) {
            if (isset($extensions[$group])) {
                $selectedExtensions = array_merge($selectedExtensions, $extensions[$group]);
            }
        }
        
        return implode(',', array_unique($selectedExtensions));
    }
    
    /**
     * Get the maximum file size in kilobytes.
     *
     * @return int
     */
    protected function getMaxFileSize(): int
    {
        return config('documents.max_file_size', 10240);
    }
    
    /**
     * Get standard document validation rules.
     *
     * @param bool $required Whether the document field is required
     * @param string|null $fieldName The name of the file field
     * @return array
     */
    protected function getDocumentValidationRules(bool $required = true, string $fieldName = 'document'): array
    {
        $maxSize = $this->getMaxFileSize();
        $extensions = $this->getAllowedExtensions();
        $requiredRule = $required ? 'required' : 'nullable';
        
        return [
            $fieldName => "{$requiredRule}|file|max:{$maxSize}|mimes:{$extensions}",
        ];
    }
    
    /**
     * Get validation rules for multiple document uploads.
     *
     * @param int $maxFiles Maximum number of files allowed
     * @return array
     */
    protected function getBulkDocumentValidationRules(int $maxFiles = 20): array
    {
        $maxSize = $this->getMaxFileSize();
        $extensions = $this->getAllowedExtensions();
        
        return [
            'documents' => "required|array|min:1|max:{$maxFiles}",
            'documents.*' => "required|file|max:{$maxSize}|mimes:{$extensions}",
        ];
    }
    
    /**
     * Determine the document category based on file extension.
     *
     * @param string $extension The file extension
     * @return string
     */
    protected function categorizeByExtension(string $extension): string
    {
        $extension = strtolower($extension);
        $extensions = config('documents.allowed_extensions', []);
        
        // Check code files first (web + backend + devops + database)
        $codeGroups = ['web', 'backend', 'devops', 'database'];
        foreach ($codeGroups as $group) {
            if (isset($extensions[$group]) && in_array($extension, $extensions[$group])) {
                return 'code';
            }
        }
        
        // Check documents
        if (isset($extensions['documents']) && in_array($extension, $extensions['documents'])) {
            return 'document';
        }
        
        // Check images
        if (isset($extensions['images']) && in_array($extension, $extensions['images'])) {
            return 'image';
        }
        
        // Check archives
        if (isset($extensions['archives']) && in_array($extension, $extensions['archives'])) {
            return 'archive';
        }
        
        // Check documentation
        if (isset($extensions['documentation']) && in_array($extension, $extensions['documentation'])) {
            return 'documentation';
        }
        
        return 'other';
    }
    
    /**
     * Get human-readable list of allowed file types for display.
     *
     * @return string
     */
    protected function getHumanReadableFileTypes(): string
    {
        return 'PDF, Word, Excel, PowerPoint, images, code files (PHP, JS, Python, etc.), archives, and more';
    }
    
    /**
     * Check if a file extension is allowed.
     *
     * @param string $extension
     * @return bool
     */
    protected function isExtensionAllowed(string $extension): bool
    {
        $extension = strtolower($extension);
        $allExtensions = explode(',', $this->getAllowedExtensions());
        
        return in_array($extension, $allExtensions);
    }
}
