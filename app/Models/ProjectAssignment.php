<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'project_assignments';

    protected $fillable = [
        'project_id',
        'adiutor_id',
        'agreed_rate',
        'start_date',
        'expected_completion',
        'status',
        'notes',
        'progress_percentage'
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_completion' => 'date',
        'agreed_rate' => 'decimal:2',
        'progress_percentage' => 'integer'
    ];

    /**
     * Get the project that this assignment belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the adiutor assigned to this project
     */
    public function adiutor()
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }
}
