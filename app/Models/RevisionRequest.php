<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'requested_by',
        'reason',
        'requested_due_date',
        'revision_number',
        'status',
        'priority',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
        'task_id',
        'service_request_id',
        'project_id',
        'source_type',
        'assigned_adiutor_id',
        'completed_at',
        'completed_by',
        'allows_new_tasks',
        'reopened_task_ids'
    ];

    protected $casts = [
        'requested_due_date' => 'date',
        'reviewed_at' => 'datetime',
        'completed_at' => 'datetime',
        'allows_new_tasks' => 'boolean',
        'reopened_task_ids' => 'array'
    ];

    /**
     * Get the document that needs revision
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the user who requested the revision (client)
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the admin who reviewed the request
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the adiutor assigned to handle the revision
     */
    public function assignedAdiutor()
    {
        return $this->belongsTo(User::class, 'assigned_adiutor_id');
    }

    /**
     * Get the user who completed the revision
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get the task (if task-based document)
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the service request
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if revision is from a task
     */
    public function isTaskBased()
    {
        return $this->source_type === 'task';
    }

    /**
     * Check if revision is from a project
     */
    public function isProjectBased()
    {
        return $this->source_type === 'project';
    }

    /**
     * Check if revision is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if revision is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if revision is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if revision is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Get source description (task name or project title)
     */
    public function getSourceDescription()
    {
        if ($this->isTaskBased() && $this->task) {
            return "Task: {$this->task->title}";
        }
        
        if ($this->isProjectBased() && $this->project) {
            return "Project: {$this->project->title}";
        }
        
        return "Unknown source";
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'completed' => 'success',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Scope for pending revisions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved revisions
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for adiutor's revisions
     */
    public function scopeForAdiutor($query, $adiutorId)
    {
        return $query->where('assigned_adiutor_id', $adiutorId);
    }

    /**
     * Scope for client's revisions
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('requested_by', $clientId);
    }
}
