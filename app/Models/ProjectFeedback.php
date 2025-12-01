<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFeedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'client_id',
        'adiutor_id',
        'task_id',
        'project_id',
        'title',
        'message',
        'rating',
        'type',
        'status',
        'priority',
        'admin_response',
        'responded_by',
        'responded_at',
        'resolved_by',
        'resolved_at',
        'internal_notes',
        'category',
        'tags'
    ];

    protected $casts = [
        'rating' => 'integer',
        'tags' => 'array',
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function adiutor()
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }
}
