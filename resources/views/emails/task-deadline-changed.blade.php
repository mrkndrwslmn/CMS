@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); padding: 30px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 28px;">Task Deadline Updated</h1>
    </div>
    
    <div style="padding: 30px; background: #f8f9fa;">
        <p>Hello,</p>
        
        <p>The deadline for one of your assigned tasks has been updated:</p>
        
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Task Details:</strong><br>
            <strong>Title:</strong> {{ $task->taskTitle }}<br>
            <strong>Project:</strong> {{ $task->project->title ?? 'Unknown Project' }}<br>
            <strong>Priority:</strong> {{ ucfirst($task->priority) }}
        </div>
        
        <div style="background: white; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <div style="width: 48%;">
                    <strong>Old Deadline:</strong><br>
                    <span style="color: #dc3545;">{{ $oldDeadline ? \Carbon\Carbon::parse($oldDeadline)->format('M d, Y') : 'No deadline' }}</span>
                </div>
                <div style="width: 48%;">
                    <strong>New Deadline:</strong><br>
                    <span style="color: #28a745;">{{ $newDeadline ? \Carbon\Carbon::parse($newDeadline)->format('M d, Y') : 'No deadline' }}</span>
                </div>
            </div>
        </div>
        
        <p>Please update your schedule accordingly and ensure the task is completed by the new deadline.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $taskUrl }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Task</a>
        </div>
    </div>
    
    <div style="background: #333; color: white; padding: 20px; text-align: center; font-size: 12px;">
        <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
    </div>
</div>
@endsection