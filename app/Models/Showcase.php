<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Showcase extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'showcases';

    protected $fillable = [
        'project_name',
        'category',
        'slug',
        'short_description',
        'full_description',
        'features',
        'tools_used',
        'thumbnail',
        'login_details',
        'live_link',
        'github_link',
        'client_name',
        'duration',
        'status',
        'attachments',
    ];

    protected $casts = [
        'features' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Get the screenshots for the showcase.
     */
    public function screenshots()
    {
        return $this->hasMany(ShowcaseScreenshot::class)->orderBy('sort_order');
    }

    /**
     * Scope a query to only include completed showcases.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include showcases by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
