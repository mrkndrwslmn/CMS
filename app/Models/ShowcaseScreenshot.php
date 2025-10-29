<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ShowcaseScreenshot extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'showcase_screenshots';

    protected $fillable = [
        'showcase_id',
        'image_url',
        'caption',
        'sort_order',
    ];

    /**
     * Get the showcase that owns the screenshot.
     */
    public function showcase()
    {
        return $this->belongsTo(Showcase::class);
    }
}
