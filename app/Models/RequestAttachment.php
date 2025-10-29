<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    protected $fillable = [
        'service_request_id',
        'file_path',
        'file_url',
        'original_filename',
        'file_size',
        'mime_type',
    ];

    /**
     * Check if this is an R2 file (has file_url)
     */
    public function isR2File(): bool
    {
        return !empty($this->file_url) && (
            str_starts_with($this->file_url, 'https://') ||
            str_starts_with($this->file_url, 'http://')
        );
    }

    /**
     * Get the download URL for this attachment
     */
    public function getDownloadUrl(): string
    {
        if ($this->isR2File()) {
            // R2 files are stored with the full public URL - return directly
            return $this->file_url;
        }
        
        // Legacy local file - use the file_path with asset helper
        return asset('storage/' . $this->file_path);
    }
}
