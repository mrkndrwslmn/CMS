<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';
    protected $primaryKey = 'formID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'client_id',
        'companyName',
        'businessType',
        'deadline',
        'projectDescription',
        'specialRequests',
        'status',
        'submissionDate',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'deadline' => 'date',
        'submissionDate' => 'datetime',
    ];

    /**
     * Get the user who submitted this form.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
    
    /**
     * Get the client who submitted this form (alias for user).
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    /**
     * Get the tasks created from this form.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'formID');
    }

    /**
     * Get the files attached to this form.
     */
    public function files(): HasMany
    {
        return $this->hasMany(FormFile::class, 'formid');
    }

    /**
     * Check if form is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if form is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if form is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get formatted deadline.
     */
    public function getFormattedDeadlineAttribute(): string
    {
        return $this->deadline ? date('M d, Y', strtotime($this->deadline)) : 'No deadline';
    }
}