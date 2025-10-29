<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'default_tasks',
        'skills_required',
        'milestones_template',
        'estimated_budget_min',
        'estimated_budget_max',
        'estimated_duration_days',
        'budget_type',
        'payment_type',
        'requirements_template',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'default_tasks' => 'array',
        'skills_required' => 'array',
        'milestones_template' => 'array',
        'estimated_budget_min' => 'decimal:2',
        'estimated_budget_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this template
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get templates by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get estimated budget range as string
     */
    public function getBudgetRangeAttribute(): string
    {
        if ($this->estimated_budget_min && $this->estimated_budget_max) {
            return '$' . number_format($this->estimated_budget_min, 0) . ' - $' . number_format($this->estimated_budget_max, 0);
        }
        
        if ($this->estimated_budget_min) {
            return 'From $' . number_format($this->estimated_budget_min, 0);
        }
        
        if ($this->estimated_budget_max) {
            return 'Up to $' . number_format($this->estimated_budget_max, 0);
        }
        
        return 'Contact for pricing';
    }

    /**
     * Get duration as human readable string
     */
    public function getDurationAttribute(): string
    {
        if (!$this->estimated_duration_days) {
            return 'TBD';
        }
        
        if ($this->estimated_duration_days < 7) {
            return $this->estimated_duration_days . ' days';
        }
        
        $weeks = round($this->estimated_duration_days / 7);
        return $weeks . ' weeks';
    }
}
