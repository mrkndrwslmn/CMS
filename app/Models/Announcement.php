<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'priority',
        'status',
        'target_audience',
        'starts_at',
        'expires_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Allowed HTML tags for announcement content
     */
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><ul><ol><li><a><h1><h2><h3><h4><h5><h6>';

    /**
     * Sanitize the content attribute to prevent XSS attacks
     * Allows only safe HTML tags and strips dangerous attributes
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                // Strip all tags except allowed ones
                $sanitized = strip_tags($value, self::ALLOWED_TAGS);
                
                // Remove any event handlers and javascript: URLs from allowed tags
                $sanitized = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $sanitized);
                $sanitized = preg_replace('/href\s*=\s*["\']javascript:[^"\']*["\']/i', 'href="#"', $sanitized);
                
                return $sanitized;
            }
        );
    }

    /**
     * Sanitize the title attribute to prevent XSS attacks
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strip_tags($value)
        );
    }

    /**
     * Relationship with the user who created the announcement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with the user who last updated the announcement
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for active announcements
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for scheduled announcements that should be active
     */
    public function scopeShouldBeActive($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('starts_at', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for expired announcements
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now())
                    ->whereNotNull('expires_at');
    }

    /**
     * Check if announcement is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if scheduled announcement should be active
     */
    public function getShouldBeActiveAttribute()
    {
        return $this->status === 'scheduled' 
               && $this->starts_at 
               && $this->starts_at->isPast()
               && (!$this->expires_at || $this->expires_at->isFuture());
    }

    /**
     * Get status with expiry check
     */
    public function getCurrentStatusAttribute()
    {
        if ($this->is_expired) {
            return 'expired';
        }
        return $this->status;
    }

    /**
     * Boot method to automatically update expired and scheduled announcements
     */
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($announcement) {
            $updated = false;
            
            // Check if expired
            if ($announcement->is_expired && $announcement->status !== 'expired') {
                $announcement->update(['status' => 'expired']);
                $updated = true;
            }
            
            // Check if scheduled announcement should be active
            if (!$updated && $announcement->should_be_active) {
                $announcement->update(['status' => 'active']);
            }
        });
    }
}
