<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

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
        'link_url',
        'link_type',
        'fileType',
        'fileSize',
        'version',
        'parent_document_id',
        'original_document_id',
        'document_type',
        'description',
        'is_public',
        'is_archived',
        'is_deliverable',
        'is_approved',
        'approved_by',
        'approved_at',
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
        'approved_at' => 'datetime',
        'is_public' => 'boolean',
        'is_archived' => 'boolean',
        'is_deliverable' => 'boolean',
        'is_approved' => 'boolean',
        'fileSize' => 'integer',
        'version' => 'integer',
    ];

    /**
     * Get the parent documentable model (polymorphic).
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the parent document (previous version).
     */
    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_document_id', 'documentID');
    }

    /**
     * Get the original document in the version chain.
     */
    public function originalDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'original_document_id', 'documentID');
    }

    /**
     * Get all versions of this document (including this one).
     */
    public function allVersions()
    {
        $originalId = $this->original_document_id ?? $this->documentID;
        
        return static::where(function ($query) use ($originalId) {
            $query->where('documentID', $originalId)
                  ->orWhere('original_document_id', $originalId);
        })->orderBy('version', 'desc');
    }

    /**
     * Get the latest version of this document.
     */
    public function latestVersion()
    {
        $originalId = $this->original_document_id ?? $this->documentID;
        
        return static::where(function ($query) use ($originalId) {
            $query->where('documentID', $originalId)
                  ->orWhere('original_document_id', $originalId);
        })->orderBy('version', 'desc')->first();
    }

    /**
     * Check if this is the latest version.
     */
    public function isLatestVersion(): bool
    {
        $latest = $this->latestVersion();
        return $latest && $latest->documentID === $this->documentID;
    }

    /**
     * Get the version count for this document chain.
     */
    public function getVersionCount(): int
    {
        $originalId = $this->original_document_id ?? $this->documentID;
        
        return static::where(function ($query) use ($originalId) {
            $query->where('documentID', $originalId)
                  ->orWhere('original_document_id', $originalId);
        })->count();
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
        return $this->belongsTo(Project::class, 'project_id');
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
     * Get the admin who approved this document (for client visibility).
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if this is a link (not a file upload).
     */
    public function isLink(): bool
    {
        return $this->link_type === 'link' && !empty($this->link_url);
    }

    /**
     * Get the URL for this document (file path or link URL).
     */
    public function getUrl(): string
    {
        if ($this->isLink()) {
            return $this->link_url;
        }
        return $this->getDownloadUrl();
    }

    /**
     * Approve this document for client visibility.
     */
    public function approve(User|int $approver): bool
    {
        $approverId = $approver instanceof User ? $approver->id : $approver;
        
        return $this->update([
            'is_approved' => true,
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejection_reason' => null, // Clear any previous rejection
            'rejected_at' => null,
        ]);
    }

    /**
     * Reject this document with a reason.
     */
    public function reject(User|int $rejector, string $reason): bool
    {
        return $this->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);
    }

    /**
     * Revoke approval of this document.
     */
    public function revokeApproval(): bool
    {
        return $this->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Mark document as a deliverable.
     */
    public function markAsDeliverable(): bool
    {
        return $this->update(['is_deliverable' => true]);
    }

    /**
     * Unmark document as a deliverable.
     */
    public function unmarkAsDeliverable(): bool
    {
        return $this->update(['is_deliverable' => false]);
    }

    /**
     * Scope: Get only approved documents.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope: Get only pending (unapproved) documents.
     */
    public function scopePendingApproval($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope: Get only deliverables.
     */
    public function scopeDeliverables($query)
    {
        return $query->where('is_deliverable', true);
    }

    /**
     * Scope: Get documents visible to clients (approved).
     */
    public function scopeVisibleToClient($query)
    {
        return $query->where('is_approved', true)
                     ->where('is_archived', false);
    }

    /**
     * Get all revision requests for this document.
     */
    public function revisionRequests()
    {
        return $this->hasMany(RevisionRequest::class, 'document_id', 'documentID');
    }

    /**
     * Get the latest revision request.
     */
    public function latestRevisionRequest()
    {
        return $this->hasOne(RevisionRequest::class, 'document_id', 'documentID')->latest();
    }

    /**
     * Get pending revision requests.
     */
    public function pendingRevisionRequests()
    {
        return $this->hasMany(RevisionRequest::class, 'document_id', 'documentID')
                    ->where('status', 'pending');
    }

    /**
     * Check if document has pending revision requests.
     */
    public function hasPendingRevision(): bool
    {
        return $this->revisionRequests()->where('status', 'pending')->exists();
    }

    /**
     * Check if document has any revision requests.
     */
    public function hasRevisionRequests(): bool
    {
        return $this->revisionRequests()->exists();
    }

    /**
     * Get total number of revision requests.
     */
    public function getRevisionCount(): int
    {
        return $this->revisionRequests()->count();
    }

    /**
     * Check if document can be revised (not already under revision).
     */
    public function canRequestRevision(): bool
    {
        // Can't request revision if there's already a pending or approved request
        return !$this->revisionRequests()
                     ->whereIn('status', ['pending', 'approved'])
                     ->exists();
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFormattedSizeAttribute(): string
    {
        // If fileSize is already stored in database, use it
        if ($this->fileSize && $this->fileSize > 0) {
            $size = $this->fileSize;
            $units = ['B', 'KB', 'MB', 'GB'];
            
            $i = 0;
            while ($size >= 1024 && $i < count($units) - 1) {
                $size /= 1024;
                $i++;
            }

            return round($size, 2) . ' ' . $units[$i];
        }

        // Legacy support: Check if it's a local file
        if (!$this->isR2File() && file_exists($this->filePath)) {
            $size = filesize($this->filePath);
            $units = ['B', 'KB', 'MB', 'GB'];
            
            $i = 0;
            while ($size >= 1024 && $i < count($units) - 1) {
                $size /= 1024;
                $i++;
            }

            return round($size, 2) . ' ' . $units[$i];
        }

        return 'Unknown';
    }

    /**
     * Get the file extension.
     */
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->fileName, PATHINFO_EXTENSION);
    }

    /**
     * Check if the document is an image.
     */
    public function isImage(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Check if the document is a PDF.
     */
    public function isPdf(): bool
    {
        return strtolower($this->file_extension) === 'pdf';
    }

    /**
     * Check if the document can be previewed in browser.
     */
    public function isPreviewable(): bool
    {
        return $this->isImage() || $this->isPdf();
    }

    /**
     * Get the file icon name based on file type (Lucide icon names).
     */
    public function getFileIconAttribute(): string
    {
        $extension = strtolower($this->file_extension);
        
        return match($extension) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file-text',
            'xls', 'xlsx' => 'file-spreadsheet',
            'ppt', 'pptx' => 'presentation',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg' => 'image',
            'zip', 'rar', '7z', 'tar', 'gz' => 'archive',
            'mp3', 'wav', 'ogg', 'flac' => 'file-audio',
            'mp4', 'avi', 'mov', 'wmv', 'mkv' => 'file-video',
            'txt', 'log' => 'file-text',
            'csv' => 'file-spreadsheet',
            'json', 'xml' => 'file-code',
            'html', 'css', 'js', 'php' => 'file-code',
            default => 'file',
        };
    }

    /**
     * Get the file icon color class based on file type.
     */
    public function getFileIconColorAttribute(): string
    {
        $extension = strtolower($this->file_extension);
        
        return match($extension) {
            'pdf' => 'text-red-500',
            'doc', 'docx' => 'text-blue-500',
            'xls', 'xlsx' => 'text-green-500',
            'ppt', 'pptx' => 'text-orange-500',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg' => 'text-purple-500',
            'zip', 'rar', '7z', 'tar', 'gz' => 'text-yellow-500',
            'mp3', 'wav', 'ogg', 'flac' => 'text-pink-500',
            'mp4', 'avi', 'mov', 'wmv', 'mkv' => 'text-indigo-500',
            'txt', 'log' => 'text-gray-500',
            'csv' => 'text-green-600',
            'json', 'xml' => 'text-cyan-500',
            'html', 'css', 'js', 'php' => 'text-teal-500',
            default => 'text-neutral-500',
        };
    }

    /**
     * Check if file is stored in Cloudflare R2
     */
    public function isR2File(): bool
    {
        return $this->filePath && (
            str_starts_with($this->filePath, 'https://') || 
            str_starts_with($this->filePath, 'http://')
        );
    }

    /**
     * Get the download URL for this document
     */
    public function getDownloadUrl(): string
    {
        if ($this->isR2File()) {
            // R2 files are stored with the full public URL - return directly
            return $this->filePath;
        }
        
        // Legacy local file - construct URL
        return route('client.documents.download', $this->documentID);
    }

    /**
     * Get the display URL for this document (for images and previews)
     */
    public function getDisplayUrl(): string
    {
        if ($this->isR2File()) {
            // R2 files are stored with the full public URL - return directly
            return $this->filePath;
        }
        
        // Legacy local file - use asset helper
        return asset('storage/' . $this->filePath);
    }

    /**
     * Check if document file exists
     */
    public function fileExists(): bool
    {
        if ($this->isR2File()) {
            // For R2 files, we'll assume they exist unless we can verify otherwise
            // You could implement an R2 service call here if needed
            return true;
        }
        
        // Legacy local file check
        return file_exists($this->filePath);
    }

    /**
     * Check if this document is accessible to the client based on approval and payment status
     * Documents uploaded by adiutors need admin approval before clients can see them.
     * 
     * @return bool
     */
    public function isAccessibleToClient(): bool
    {
        // Documents must be approved to be visible to clients
        // (unless explicitly marked public by admin)
        if (!$this->is_approved && !$this->is_public) {
            return false;
        }

        // If document is explicitly public, it's accessible (regardless of payment)
        if ($this->is_public) {
            return true;
        }

        // Determine the payment type from the related entity
        $serviceRequest = null;

        // Try to get service request through different relationships
        if ($this->documentable_type === 'App\\Models\\Task' && $this->documentable) {
            $serviceRequest = $this->documentable->project->serviceRequest ?? null;
        } elseif ($this->documentable_type === 'App\\Models\\Project' && $this->documentable) {
            $serviceRequest = $this->documentable->serviceRequest ?? null;
        } elseif ($this->documentable_type === 'App\\Models\\ServiceRequest' && $this->documentable) {
            $serviceRequest = $this->documentable;
        } elseif ($this->taskID) {
            // Legacy: Use taskID relationship
            $serviceRequest = $this->task->project->serviceRequest ?? null;
        } elseif ($this->project_id) {
            // Legacy: Use project_id relationship
            $serviceRequest = $this->project->serviceRequest ?? null;
        } elseif ($this->service_request_id) {
            // Legacy: Use service_request_id relationship
            $serviceRequest = $this->serviceRequest;
        }

        // If no service request found, default to accessible
        if (!$serviceRequest || !$serviceRequest->payment_type) {
            return true;
        }

        // Full payment: All documents accessible if paid
        if ($serviceRequest->isFullPayment()) {
            return $serviceRequest->isPaid();
        }

        // Milestone payment: Check task's phase payment status
        if ($serviceRequest->isMilestonePayment()) {
            // If document belongs to a task with a phase
            if ($this->documentable_type === 'App\\Models\\Task' && $this->documentable && $this->documentable->phase_id) {
                return $this->documentable->phase->isPaid();
            }
            
            // Legacy check
            if ($this->taskID && $this->task && $this->task->phase_id) {
                return $this->task->phase->isPaid();
            }

            // If no phase association, accessible by default
            return true;
        }

        // Downpayment: Documents accessible only after remaining balance paid
        if ($serviceRequest->isDownpayment()) {
            return $serviceRequest->isRemainingBalancePaid();
        }

        return false;
    }

    /**
     * Get the lock status for UI display
     */
    public function getLockStatus(): array
    {
        $isAccessible = $this->isAccessibleToClient();
        
        if ($isAccessible) {
            return [
                'locked' => false,
                'message' => 'Accessible',
                'icon' => 'unlock',
            ];
        }

        // Determine lock reason
        $serviceRequest = null;

        if ($this->documentable_type === 'App\\Models\\Task' && $this->documentable) {
            $serviceRequest = $this->documentable->project->serviceRequest ?? null;
            $phase = $this->documentable->phase;
        } elseif ($this->taskID && $this->task) {
            $serviceRequest = $this->task->project->serviceRequest ?? null;
            $phase = $this->task->phase;
        } elseif ($this->documentable_type === 'App\\Models\\Project' && $this->documentable) {
            $serviceRequest = $this->documentable->serviceRequest ?? null;
        } elseif ($this->project_id && $this->project) {
            $serviceRequest = $this->project->serviceRequest ?? null;
        }

        if ($serviceRequest && $serviceRequest->isMilestonePayment() && isset($phase) && $phase) {
            return [
                'locked' => true,
                'message' => "Locked: Payment required for {$phase->phase_name}",
                'icon' => 'lock',
                'phase' => $phase->phase_name,
            ];
        }

        if ($serviceRequest && $serviceRequest->isDownpayment()) {
            return [
                'locked' => true,
                'message' => 'Locked: Remaining balance payment required',
                'icon' => 'lock',
            ];
        }

        return [
            'locked' => true,
            'message' => 'Locked: Payment required',
            'icon' => 'lock',
        ];
    }

    /**
     * Scope: Get only accessible documents for a client
     */
    public function scopeAccessibleToClient($query)
    {
        // This is a simplified version - you may need to adjust based on your specific needs
        return $query->where('is_public', true)
                    ->orWhereHas('task', function($q) {
                        // Add logic to check task accessibility
                    });
    }
}