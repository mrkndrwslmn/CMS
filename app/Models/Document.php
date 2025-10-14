<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    protected $primaryKey = 'documentID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'taskID',
        'fileName',
        'filePath',
        'fileType',
        'fileSize',
        'uploadedAt',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'uploadedAt' => 'datetime',
    ];

    /**
     * Get the task this document belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'taskID', 'taskID');
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!file_exists($this->filePath)) {
            return 'Unknown';
        }

        $size = filesize($this->filePath);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get the file extension.
     */
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->fileName, PATHINFO_EXTENSION);
    }
}