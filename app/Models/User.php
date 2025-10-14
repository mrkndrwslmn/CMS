<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fullName',
        'email',
        'password',
        'role',
        'phoneNumber',
        'profilePic',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Check if user is adiutor
     */
    public function isAdiutor(): bool
    {
        return $this->hasRole('adiutor');
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the dashboard route for this user's role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'client' => 'client.dashboard',
            'adiutor' => 'adiutor.dashboard',
            default => 'login'
        };
    }

    /**
     * Get the adiutor profile for this user
     */
    public function adiutorProfile()
    {
        return $this->hasOne(AdiutorProfile::class);
    }
    
    /**
     * Get the client profile for this user
     */
    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }

    /**
     * Get the skills for this adiutor user
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'adiutor_skills')
                    ->withPivot('proficiency', 'years_experience')
                    ->withTimestamps();
    }

    /**
     * Get projects assigned to this adiutor
     */
    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_assignments', 'adiutor_id', 'project_id')
                    ->withPivot('agreed_rate', 'start_date', 'expected_completion', 'status', 'notes', 'progress_percentage')
                    ->withTimestamps();
    }

    /**
     * Get projects created by this client
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Get notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Get tasks assigned to this user (for adiutors)
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignedTo');
    }

    /**
     * Get tasks created for this user (for clients)
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'client_id');
    }

    /**
     * Get forms/requests submitted by this user (for clients)
     */
    public function forms()
    {
        return $this->hasMany(Form::class, 'client_id');
    }

    /**
     * Get documents uploaded by this user
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * Get documents associated with this user as client
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'client_id');
    }

    /**
     * Get feedback given by this user
     */
    public function feedbacks()
    {
        // This relationship uses either client_id (if available) or falls back to giver_id
        return $this->hasMany(Feedback::class, 'client_id');
    }

    /**
     * Get feedback received by this user (for adiutors)
     */
    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'receiver_id');
    }

    /**
     * Get notes related to this client
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'client_id');
    }
}
