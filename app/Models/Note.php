<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $primaryKey = 'noteID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'clientID',
        'noteContent',
        'addedBy',
        'dateAdded',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'dateAdded' => 'datetime',
    ];

    /**
     * Get the client this note belongs to.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clientID', 'clientID');
    }

    /**
     * Get the user who added this note.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'addedBy', 'userID');
    }

    /**
     * Get truncated note content for display.
     */
    public function getTruncatedContentAttribute(): string
    {
        return strlen($this->noteContent) > 100 
            ? substr($this->noteContent, 0, 100) . '...' 
            : $this->noteContent;
    }
}