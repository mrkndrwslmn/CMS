<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'category',
        'features',
        'is_active',
        'icon',
        'estimated_duration_days',
        'required_skills',
        'requirements',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'features' => 'array',
        'required_skills' => 'array',
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'estimated_duration_days' => 'integer',
    ];

    /**
     * Get the service requests for this service.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'template_service_id');
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->base_price, 2);
    }

    /**
     * Get all unique categories.
     */
    public static function getCategories()
    {
        return self::distinct()->pluck('category')->filter()->sort()->values()->toArray();
    }
}
