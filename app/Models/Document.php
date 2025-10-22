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

    /**
     * Check if this document is accessible to the client based on payment status
     * Documents follow the same accessibility rules as their parent (task/project)
     * 
     * @return bool
     */
    public function isAccessibleToClient(): bool
    {
        // If document is explicitly public, it's accessible
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