<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Task $task;
    protected array $changes;
    protected string $updatedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, array $changes, string $updatedBy = 'Admin')
    {
        $this->task = $task;
        $this->changes = $changes;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $changesText = $this->formatChanges();
        
        return [
            'type' => 'task_updated',
            'task_id' => $this->task->taskID,
            'task_title' => $this->task->taskTitle,
            'project_title' => $this->task->project->title ?? 'Unknown Project',
            'changes' => $this->changes,
            'changes_text' => $changesText,
            'updated_by' => $this->updatedBy,
            'message' => "Task '{$this->task->taskTitle}' has been updated: {$changesText}",
            'action_url' => route('admin.tasks.show', $this->task->taskID),
            'icon' => 'edit',
            'color' => 'info'
        ];
    }

    protected function formatChanges(): string
    {
        $formatted = [];
        
        foreach ($this->changes as $field => $change) {
            $fieldName = str_replace('_', ' ', $field);
            $fieldName = ucwords($fieldName);
            
            if (is_array($change) && isset($change['old'], $change['new'])) {
                $formatted[] = "{$fieldName}: {$change['old']} → {$change['new']}";
            } else {
                $formatted[] = "{$fieldName} updated";
            }
        }
        
        return implode(', ', $formatted);
    }
}