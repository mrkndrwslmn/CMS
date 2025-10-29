<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the auditable trait
     */
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            AuditLog::logAction($model, 'created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getDirty();
            
            // Only log if there are actual changes
            if (!empty($newValues)) {
                AuditLog::logAction($model, 'updated', $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            AuditLog::logAction($model, 'deleted', $model->getAttributes(), null);
        });
    }

    /**
     * Get audit logs for this model
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Log a custom action for this model
     */
    public function logAction(string $action, array $metadata = []): void
    {
        AuditLog::logAction($this, $action, null, null, 'model_action', $metadata);
    }
}