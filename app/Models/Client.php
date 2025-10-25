<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'clientID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'userID',
        'companyName',
        'industry',
        'dateAdded',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dateAdded' => 'datetime',
    ];

    /**
     * Get the user associated with this client.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    /**
     * Get the notes for this client.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'clientID');
    }

    /**
     * Get the forms submitted by this client.
     */
    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'userID', 'userID');
    }

    /**
     * Get the display name for this client.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->companyName ?: $this->user->fullName;
    }

    /**
     * Get total projects count for this client.
     */
    public function getTotalProjectsAttribute(): int
    {
        return $this->forms()->count();
    }

    /**
     * Get active projects count for this client.
     */
    public function getActiveProjectsAttribute(): int
    {
        return $this->forms()->whereIn('status', ['approved', 'in_progress'])->count();
    }
}