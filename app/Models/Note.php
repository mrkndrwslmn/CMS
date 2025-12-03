<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'client_id',
        'added_by',
        'title',
        'content',
        'type',
    ];

    /**
     * Get the client this note belongs to.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the user who added this note.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get truncated note content for display.
     */
    public function getTruncatedContentAttribute(): string
    {
        return strlen($this->content) > 100 
            ? substr($this->content, 0, 100) . '...' 
            : $this->content;
    }
}