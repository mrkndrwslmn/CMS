<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    protected $primaryKey = 'documentID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'taskID',
        'service_request_id',
        'project_id',
        'client_id',
        'fileName',
        'filePath',
        'fileType',
        'fileSize',
        'document_type',
        'description',
        'is_public',
        'is_archived',
        'uploaded_by',
        'uploadedAt',
        'verified_by',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'uploadedAt' => 'datetime',
        'verified_at' => 'datetime',
        'is_public' => 'boolean',
        'is_archived' => 'boolean',
        'fileSize' => 'integer',
    ];

    /**
     * Get the parent documentable model (polymorphic).
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the task this document belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'taskID', 'taskID');
    }

    /**
     * Get the service request this document belongs to.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    /**
     * Get the project this document belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'projectID');
    }

    /**
     * Get the client who owns this document.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the user who uploaded this document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the admin who verified this document.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
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