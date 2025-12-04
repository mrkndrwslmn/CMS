<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Task Deliverable Model
 * 
 * Represents a deliverable item for a task. Deliverables can be:
 * - Files/Documents (uploaded to R2 storage)
 * - Images (uploaded to R2 storage)
 * - Links (external URLs)
 * 
 * @property int $id
 * @property int $task_id
 * @property string $type (file, image, link)
 * @property int|null $document_id
 * @property string|null $link_url
 * @property string $title
 * @property string|null $description
 * @property bool $is_approved
 * @property int|null $approved_by
 * @property \Carbon\Carbon|null $approved_at
 * @property int $uploaded_by
 * @property int $sort_order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class TaskDeliverable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'task_deliverables';

    protected $fillable = [
        'task_id',
        'type',
        'document_id',
        'link_url',
        'title',
        'description',
        'is_approved',
        'approved_by',
        'approved_at',
        'uploaded_by',
        'sort_order',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    /**
     * Get the task this deliverable belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the associated document (for file/image types).
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'documentID');
    }

    /**
     * Get the user who uploaded this deliverable.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }

    /**
     * Get the user who approved this deliverable.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    /**
     * Scope for approved deliverables.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending deliverables.
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope for file type deliverables.
     */
    public function scopeFiles($query)
    {
        return $query->where('type', 'file');
    }

    /**
     * Scope for image type deliverables.
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Scope for link type deliverables.
     */
    public function scopeLinks($query)
    {
        return $query->where('type', 'link');
    }

    /**
     * Check if this is a file deliverable.
     */
    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    /**
     * Check if this is an image deliverable.
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Check if this is a link deliverable.
     */
    public function isLink(): bool
    {
        return $this->type === 'link';
    }

    /**
     * Get the URL for this deliverable.
     * For files/images, returns the document download URL.
     * For links, returns the link_url.
     */
    public function getUrl(): ?string
    {
        if ($this->isLink()) {
            return $this->link_url;
        }

        if ($this->document) {
            return $this->document->getDownloadUrl();
        }

        return null;
    }

    /**
     * Get the icon class based on deliverable type.
     */
    public function getIconClass(): string
    {
        switch ($this->type) {
            case 'image':
                return 'fas fa-image';
            case 'link':
                return 'fas fa-link';
            case 'file':
            default:
                // If we have a document, try to get a more specific icon
                if ($this->document) {
                    $extension = strtolower(pathinfo($this->document->fileName, PATHINFO_EXTENSION));
                    return match($extension) {
                        'pdf' => 'fas fa-file-pdf',
                        'doc', 'docx' => 'fas fa-file-word',
                        'xls', 'xlsx' => 'fas fa-file-excel',
                        'ppt', 'pptx' => 'fas fa-file-powerpoint',
                        'zip', 'rar', '7z' => 'fas fa-file-archive',
                        'php', 'js', 'ts', 'py', 'java', 'css', 'html' => 'fas fa-file-code',
                        default => 'fas fa-file'
                    };
                }
                return 'fas fa-file';
        }
    }

    /**
     * Approve this deliverable.
     */
    public function approve(User $approver): bool
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        return true;
    }

    /**
     * Revoke approval of this deliverable.
     */
    public function revokeApproval(): bool
    {
        $this->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return true;
    }
}
