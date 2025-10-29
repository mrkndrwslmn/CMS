<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;

trait LogsNotifications
{
    /**
     * Send notification with comprehensive logging
     */
    protected function sendNotificationWithLogging($notifiable, Notification $notification, array $context = [])
    {
        $notificationClass = get_class($notification);
        $notificationName = class_basename($notificationClass);
        
        try {
            Log::info("Dispatching {$notificationName}", array_merge([
                'notification_class' => $notificationClass,
                'recipient_id' => $notifiable->id,
                'recipient_email' => $notifiable->email,
                'recipient_role' => $notifiable->role,
                'controller' => static::class,
                'method' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown'
            ], $context));
            
            $notifiable->notify($notification);
            
            Log::info("{$notificationName} dispatched successfully", [
                'notification_class' => $notificationClass,
                'recipient_id' => $notifiable->id,
                'recipient_email' => $notifiable->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error("{$notificationName} dispatch failed", array_merge([
                'notification_class' => $notificationClass,
                'recipient_id' => $notifiable->id,
                'recipient_email' => $notifiable->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'controller' => static::class
            ], $context));
            
            return false;
        }
    }

    /**
     * Send notification to multiple recipients with logging
     */
    protected function sendNotificationToManyWithLogging($notifiables, Notification $notification, array $context = [])
    {
        $notificationClass = get_class($notification);
        $notificationName = class_basename($notificationClass);
        $successCount = 0;
        $failureCount = 0;
        
        Log::info("Dispatching {$notificationName} to multiple recipients", array_merge([
            'notification_class' => $notificationClass,
            'recipient_count' => $notifiables->count(),
            'controller' => static::class,
            'method' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown'
        ], $context));

        foreach ($notifiables as $notifiable) {
            if ($this->sendNotificationWithLogging($notifiable, $notification, $context)) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        Log::info("{$notificationName} batch dispatch completed", [
            'notification_class' => $notificationClass,
            'total_recipients' => $notifiables->count(),
            'successful' => $successCount,
            'failed' => $failureCount,
            'success_rate' => $notifiables->count() > 0 ? round(($successCount / $notifiables->count()) * 100, 2) . '%' : '0%'
        ]);

        return [
            'success' => $successCount,
            'failed' => $failureCount,
            'total' => $notifiables->count()
        ];
    }

    /**
     * Log notification context for debugging
     */
    protected function logNotificationContext(string $event, array $data = [])
    {
        Log::info("Notification context: {$event}", array_merge([
            'controller' => static::class,
            'method' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown',
            'timestamp' => now()->toISOString()
        ], $data));
    }
}