@php
// Helper functions for task views that might be missing from the model

// Define these functions in global scope
if (!function_exists('getTaskStatusBadgeClass')) {
    /**
     * Get status badge class for UI.
     */
    function getTaskStatusBadgeClass($status)
    {
        return match($status) {
            'pending' => 'bg-warning-100 text-warning-800',
            'in_progress' => 'bg-info-100 text-info-800',
            'completed' => 'bg-success-100 text-success-800',
            'cancelled' => 'bg-error-100 text-error-800',
            default => 'bg-neutral-100 text-neutral-800'
        };
    }
}

if (!function_exists('getTaskPriorityBadgeClass')) {
    /**
     * Get priority badge class for UI.
     */
    function getTaskPriorityBadgeClass($priority)
    {
        return match($priority) {
            'high' => 'bg-error-100 text-error-800',
            'medium' => 'bg-warning-100 text-warning-800',
            'low' => 'bg-success-100 text-success-800',
            default => 'bg-neutral-100 text-neutral-800'
        };
    }
}
@endphp