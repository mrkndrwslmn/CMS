<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormFile extends Model
{
    use HasFactory;

    protected $table = 'form_files';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'formid',
        'filename',
        'filepath',
        'uploaded_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    /**
     * Get the form this file belongs to.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'formid', 'formID');
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!file_exists($this->filepath)) {
            return 'Unknown';
        }

        $size = filesize($this->filepath);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get the file extension.
     */
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }
}