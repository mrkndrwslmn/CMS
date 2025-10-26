<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',   // CORRECT: Projects are created FROM service requests
        'client_id',
        'title',
        'description',
        'requirements',
        'skills_required',
        'status',
        'budget',
        'budget_type',
        'deadline',
        'priority',
        'started_at',
        'completed_at',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'budget' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the SERVICE REQUEST this project was created from
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    /**
     * Get the client who owns this project
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the adiutors assigned to this project
     */
    public function adiutors()
    {
        return $this->belongsToMany(User::class, 'project_assignments', 'project_id', 'adiutor_id')
                    ->withPivot('agreed_rate', 'start_date', 'expected_completion', 'status', 'notes', 'progress_percentage')
                    ->withTimestamps();
    }

    /**
     * Get project assignments
     */
    public function assignments()
    {
        return $this->hasMany(ProjectAssignment::class, 'project_id');
    }

    /**
     * Get TASKS related to this project (CORRECT: Only projects have tasks!)
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    /**
     * Get milestones/phases for this project
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    /**
     * Get ordered milestones (by phase_order)
     */
    public function orderedMilestones()
    {
        return $this->milestones()->orderBy('phase_order', 'asc');
    }

    /**
     * Get the current active milestone
     */
    public function currentMilestone()
    {
        return $this->milestones()->where('status', 'in_progress')->first();
    }

    /**
     * Get paid milestones
     */
    public function paidMilestones()
    {
        return $this->milestones()->where('is_paid', true);
    }

    /**
     * Get unpaid milestones
     */
    public function unpaidMilestones()
    {
        return $this->milestones()->where('is_paid', false);
    }

    /**
     * Get all documents for this project (polymorphic).
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Get documents using legacy foreign key.
     */
    public function directDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'project_id');
    }

    /**
     * Get feedback for this project
     */
    public function feedback()
    {
        return $this->hasMany(ProjectFeedback::class);
    }

    /**
     * Get messages for this project
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get conversation for this project
     */
    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }
}