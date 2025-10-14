<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',   // CORRECT: Projects are created FROM service requests
        'client_id',
        'title',
        'description',
        'status',
        'budget',
        'deadline',
        'priority',
        'started_at',
        'completed_at',
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
     * Get TASKS related to this project (CORRECT: Only projects have tasks!)
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    /**
     * Get feedback for this project
     */
    public function feedback()
    {
        return $this->hasMany(ProjectFeedback::class);
    }
}